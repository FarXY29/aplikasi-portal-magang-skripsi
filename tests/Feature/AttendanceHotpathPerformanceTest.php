<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Enums\AttendanceStatus;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AttendanceHotpathPerformanceTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    private function validChallenge(User $user): string
    {
        return $this->actingAs($user)
            ->getJson(route('peserta.absensi.challenge'))
            ->json('nonce');
    }

    public function test_attendance_service_history_summary_single_pass_aggregation(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $user->assignRole('peserta');
        $instansi = Instansi::factory()->create();
        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);
        $app = Application::factory()->create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Diterima,
            'tanggal_mulai' => Carbon::now()->subMonths(2)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addMonths(2)->toDateString(),
        ]);

        Attendance::create([
            'application_id' => $app->id,
            'date' => Carbon::now()->subDays(3)->toDateString(),
            'status' => 'hadir',
            'validation_status' => 'approved',
        ]);
        Attendance::create([
            'application_id' => $app->id,
            'date' => Carbon::now()->subDays(2)->toDateString(),
            'status' => 'izin',
            'validation_status' => 'approved',
        ]);
        Attendance::create([
            'application_id' => $app->id,
            'date' => Carbon::now()->subDays(1)->toDateString(),
            'status' => 'alpa',
            'validation_status' => 'pending',
        ]);

        $service = app(AttendanceService::class);
        $request = Request::create('/peserta/absensi', 'GET');

        // Monitor query count during summary preparation
        DB::enableQueryLog();
        $data = $service->historyData($user, $request);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertEquals(3, $data['attendanceSummary']['total']);
        $this->assertEquals(1, $data['attendanceSummary']['hadir']);
        $this->assertEquals(1, $data['attendanceSummary']['izin']);
        $this->assertEquals(1, $data['attendanceSummary']['alpa']);

        // Verify summary is computed in a single aggregate query rather than 4 separate queries
        $summaryQueries = collect($queries)->filter(fn ($q) => str_contains($q['query'], 'SUM(CASE'));
        $this->assertNotEmpty($summaryQueries, 'Attendance summary should utilize single-pass conditional aggregation');
    }

    public function test_expelled_or_inactive_intern_cannot_clock_in_immediately(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $user->assignRole('peserta');
        $instansi = Instansi::factory()->create([
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 500,
            'jam_mulai_masuk' => '00:00:00',
        ]);
        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);
        $app = Application::factory()->create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Dikeluarkan,
            'tanggal_mulai' => Carbon::now()->subDays(10)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addMonths(2)->toDateString(),
        ]);

        $this->actingAs($user);

        // Attempt clock-in with valid challenge
        $response = $this->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge($user),
        ]);

        $response->assertSessionHas('error', 'Anda tidak memiliki status magang aktif untuk melakukan absensi.');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $app->id,
            'date' => Carbon::today()->toDateString(),
        ]);
    }

    public function test_clock_out_is_atomic_and_does_not_overwrite_first_timestamp(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $user->assignRole('peserta');
        $instansi = Instansi::factory()->create([
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 500,
            'jam_mulai_pulang' => '00:00:00',
        ]);
        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);
        $app = Application::factory()->create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Diterima,
            'tanggal_mulai' => Carbon::now()->subDays(10)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addMonths(2)->toDateString(),
        ]);

        $today = Carbon::today()->toDateString();
        $initialClockOut = '16:05:00';

        $attendance = Attendance::create([
            'application_id' => $app->id,
            'date' => $today,
            'status' => AttendanceStatus::Hadir->value,
            'clock_in' => '07:55:00',
            'clock_out' => $initialClockOut,
            'validation_status' => 'approved',
        ]);

        $this->actingAs($user);

        // Attempting to clock out again should be rejected and NOT overwrite initial clock_out
        $response = $this->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge($user),
        ]);

        $response->assertSessionHas('error', 'Anda sudah melakukan absen pulang sebelumnya.');

        $attendance->refresh();
        $this->assertEquals($initialClockOut, $attendance->clock_out);
    }

    public function test_admin_instansi_absensi_statistics_use_agency_start_time(): void
    {
        $admin = User::factory()->create(['role' => 'admin_instansi']);
        $admin->assignRole('admin_instansi');
        $instansi = Instansi::factory()->create([
            'jam_mulai_masuk' => '08:30:00',
        ]);
        $admin->update(['instansi_id' => $instansi->id]);

        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);
        $peserta = User::factory()->create(['role' => 'peserta']);
        $peserta->assignRole('peserta');

        $app = Application::factory()->create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Diterima,
            'tanggal_mulai' => Carbon::now()->subMonths(1)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addMonths(2)->toDateString(),
        ]);

        // Clocked in at 08:15:00 (on-time because agency starts at 08:30:00)
        Attendance::create([
            'application_id' => $app->id,
            'date' => Carbon::now()->subDays(2)->toDateString(),
            'status' => AttendanceStatus::Hadir->value,
            'clock_in' => '08:15:00',
            'validation_status' => 'approved',
        ]);

        // Clocked in at 08:45:00 (late because after 08:30:00)
        Attendance::create([
            'application_id' => $app->id,
            'date' => Carbon::now()->subDays(1)->toDateString(),
            'status' => AttendanceStatus::Hadir->value,
            'clock_in' => '08:45:00',
            'validation_status' => 'approved',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('dinas.peserta.absensi', $app->id));
        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            return $stats['tepat_waktu'] === 1 && $stats['terlambat'] === 1;
        });
    }
}
