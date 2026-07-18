<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\University;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class BackfillRolesAndMasterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magang:backfill-roles-master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi role statis ke Spatie RBAC dan migrasi data asal_instansi ke tabel universities/schools';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi role Spatie untuk seluruh pengguna...');

        $users = User::all();
        $roleCount = 0;

        foreach ($users as $user) {
            if ($user->role && Role::where('name', $user->role)->exists()) {
                $user->assignRole($user->role);
                $roleCount++;
            }
        }

        $this->info("Berhasil mengassign role ke {$roleCount} pengguna.");

        $this->info('Memulai backfill data asal_instansi ke university_id / school_id...');

        $universities = University::all();
        $schools = School::all();
        $masterCount = 0;

        foreach ($users as $user) {
            if (!empty($user->asal_instansi)) {
                $asal = trim($user->asal_instansi);
                
                // Cari di universitas terlebih dahulu
                $univ = $universities->first(function ($u) use ($asal) {
                    return stripos($u->name, $asal) !== false || stripos($asal, $u->name) !== false || ($u->acronym && stripos($asal, $u->acronym) !== false);
                });

                if ($univ && !$user->university_id) {
                    $user->university_id = $univ->id;
                    $user->save();
                    $masterCount++;
                    continue;
                }

                // Cari di sekolah
                $school = $schools->first(function ($s) use ($asal) {
                    return stripos($s->name, $asal) !== false || stripos($asal, $s->name) !== false;
                });

                if ($school && !$user->school_id) {
                    $user->school_id = $school->id;
                    $user->save();
                    $masterCount++;
                }
            }
        }

        $this->info("Berhasil memetakan {$masterCount} pengguna ke tabel master universities / schools.");
        return Command::SUCCESS;
    }
}
