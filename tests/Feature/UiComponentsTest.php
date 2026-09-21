<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class UiComponentsTest extends TestCase
{
    public function test_secondary_button_renders_slot_without_stuck_spinner(): void
    {
        $rendered = Blade::render('<x-ui.secondary-button id="btn-cancel">Batal</x-ui.secondary-button>');

        $this->assertStringContainsString('Batal', $rendered);
        $this->assertStringContainsString('type="button"', $rendered);
        $this->assertStringNotContainsString('x-data="{ loading: false }"', $rendered);
    }

    public function test_primary_button_renders_with_slot_and_submit_type(): void
    {
        $rendered = Blade::render('<x-ui.primary-button>Simpan Data</x-ui.primary-button>');

        $this->assertStringContainsString('Simpan Data', $rendered);
        $this->assertStringContainsString('type="submit"', $rendered);
    }

    public function test_student_switcher_handles_array_and_collection_without_fatal_error(): void
    {
        // 1. Array with default empty array (previously caused fatal crash calling ->count() on array)
        $renderedEmpty = Blade::render('<x-ui.student-switcher />');
        $this->assertStringContainsString('Mahasiswa:', $renderedEmpty);

        // 2. Plain PHP array with multiple items
        $appsArray = [
            (object)['id' => 1, 'user' => (object)['name' => 'Budi Santoso']],
            (object)['id' => 2, 'user' => (object)['name' => 'Siti Rahma']],
        ];
        $renderedArray = Blade::render('<x-ui.student-switcher :applications="$apps" />', ['apps' => $appsArray]);
        $this->assertStringContainsString('Budi Santoso', $renderedArray);
        $this->assertStringContainsString('Siti Rahma', $renderedArray);

        // 3. Collection with single item
        $appsCollection = collect([
            (object)['id' => 1, 'user' => (object)['name' => 'Ahmad Dani']],
        ]);
        $renderedSingle = Blade::render('<x-ui.student-switcher :applications="$apps" :current="$apps->first()" />', ['apps' => $appsCollection]);
        $this->assertStringContainsString('Ahmad Dani', $renderedSingle);
    }

    public function test_modal_handles_custom_or_null_max_width_without_undefined_array_key(): void
    {
        $rendered = Blade::render('<x-ui.modal name="test-modal" maxWidth="unknown-size">Modal Body</x-ui.modal>');

        $this->assertStringContainsString('Modal Body', $rendered);
        $this->assertStringContainsString('sm:max-w-2xl', $rendered);
    }

    public function test_input_error_handles_omitted_messages_prop_gracefully(): void
    {
        // When messages prop is omitted, it should not throw Undefined variable $messages
        $renderedEmpty = Blade::render('<x-ui.input-error />');
        $this->assertEmpty(trim($renderedEmpty));

        // When messages has errors
        $renderedWithErrors = Blade::render('<x-ui.input-error :messages="[\'Email wajib diisi\']" />');
        $this->assertStringContainsString('Email wajib diisi', $renderedWithErrors);
    }

    public function test_input_label_handles_slot_without_undefined_variable_notice(): void
    {
        // Used with slot instead of :value
        $renderedSlot = Blade::render('<x-ui.input-label for="nama">Nama Lengkap</x-ui.input-label>');
        $this->assertStringContainsString('Nama Lengkap', $renderedSlot);

        // Used with :value prop
        $renderedValue = Blade::render('<x-ui.input-label for="email" value="Alamat Email" />');
        $this->assertStringContainsString('Alamat Email', $renderedValue);
    }

    public function test_stat_card_renders_color_aliases_and_slot_content(): void
    {
        $rendered = Blade::render('
            <x-ui.stat-card title="Pelanggaran" value="12" color="red">
                <span class="text-xs">Badge Slot</span>
            </x-ui.stat-card>
        ');

        $this->assertStringContainsString('Pelanggaran', $rendered);
        $this->assertStringContainsString('12', $rendered);
        $this->assertStringContainsString('bg-rose-500', $rendered);
        $this->assertStringContainsString('Badge Slot', $rendered);
    }

    public function test_empty_state_renders_with_and_without_slot(): void
    {
        $rendered = Blade::render('
            <x-ui.empty-state title="Kosong" description="Tidak ada aktivitas">
                <a href="/refresh">Muat Ulang</a>
            </x-ui.empty-state>
        ');

        $this->assertStringContainsString('Kosong', $rendered);
        $this->assertStringContainsString('Tidak ada aktivitas', $rendered);
        $this->assertStringContainsString('Muat Ulang', $rendered);
    }

    public function test_alert_renders_slot_safely(): void
    {
        $rendered = Blade::render('<x-ui.alert type="success">Data berhasil disimpan</x-ui.alert>');

        $this->assertStringContainsString('Data berhasil disimpan', $rendered);
        $this->assertStringContainsString('fa-check-circle', $rendered);
    }

    public function test_toolbar_merges_attributes_and_renders_slot(): void
    {
        $rendered = Blade::render('
            <x-ui.toolbar class="custom-toolbar-class">
                <button type="button">Aksi Kustom</button>
            </x-ui.toolbar>
        ');

        $this->assertStringContainsString('custom-toolbar-class', $rendered);
        $this->assertStringContainsString('Aksi Kustom', $rendered);
        $this->assertStringContainsString('Kembali ke Pusat Laporan', $rendered);
    }

    public function test_header_merges_attributes_and_renders_slot(): void
    {
        $rendered = Blade::render('
            <x-ui.header title="Laporan Absensi" class="my-custom-header">
                <span class="badge">Aktif</span>
            </x-ui.header>
        ');

        $this->assertStringContainsString('my-custom-header', $rendered);
        $this->assertStringContainsString('Laporan Absensi', $rendered);
        $this->assertStringContainsString('Aktif', $rendered);
    }

    public function test_chart_card_merges_attributes_and_renders_slot(): void
    {
        $rendered = Blade::render('
            <x-ui.chart-card title="Grafik Penyerapan" canvasId="chart-test" class="col-span-2">
                <div class="legend">Keterangan Legend</div>
            </x-ui.chart-card>
        ');

        $this->assertStringContainsString('col-span-2', $rendered);
        $this->assertStringContainsString('Grafik Penyerapan', $rendered);
        $this->assertStringContainsString('chart-test', $rendered);
        $this->assertStringContainsString('Keterangan Legend', $rendered);
    }

    public function test_legacy_forwarder_aliases_resolve_properly(): void
    {
        // 1. report.stat-card forwarder
        $reportStat = Blade::render('<x-report.stat-card title="Total" value="100" />');
        $this->assertStringContainsString('Total', $reportStat);
        $this->assertStringContainsString('100', $reportStat);

        // 2. report.pill forwarder
        $reportPill = Blade::render('<x-report.pill color="teal">Selesai</x-report.pill>');
        $this->assertStringContainsString('Selesai', $reportPill);

        // 3. secondary-button root forwarder
        $rootSecBtn = Blade::render('<x-secondary-button>Tutup Modal</x-secondary-button>');
        $this->assertStringContainsString('Tutup Modal', $rootSecBtn);

        // 4. input-error root forwarder
        $rootInputErr = Blade::render('<x-input-error :messages="[\'Format salah\']" />');
        $this->assertStringContainsString('Format salah', $rootInputErr);
    }
}
