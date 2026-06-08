<?php

$base = __DIR__ . '/resources/views/';

$files = [
    // ADMIN KOTA
    'admin/laporan/grading.blade.php' => 'admin.laporan.grading.print',
    'admin/laporan/instansi_disiplin.blade.php' => 'admin.laporan.instansi_disiplin.print',
    'admin/laporan/durasi_magang.blade.php' => 'admin.laporan.durasi_magang.print',
    'admin/laporan/demografi_jurusan.blade.php' => 'admin.laporan.demografi_jurusan.print',
    'admin/laporan/penyerapan_kuota.blade.php' => 'admin.laporan.penyerapan_kuota.print',
    // DINAS
    'dinas/laporan/grading.blade.php' => 'dinas.laporan.grading.print',
    'dinas/laporan/evaluasi_pembimbing.blade.php' => 'dinas.laporan.evaluasi_pembimbing.print',
    'dinas/laporan/tren_pendaftaran.blade.php' => 'dinas.laporan.tren_pendaftaran.print',
    'dinas/laporan/produktivitas_logbook.blade.php' => 'dinas.laporan.produktivitas_logbook.print',
    'dinas/laporan/keterisian_posisi.blade.php' => 'dinas.laporan.keterisian_posisi.print',
    'dinas/laporan/saran_peserta.blade.php' => 'dinas.laporan.saran_peserta.print',
];

foreach ($files as $path => $route_name) {
    $fullPath = $base . $path;
    if (!file_exists($fullPath)) {
        echo "Missing: $path\n";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    if (strpos($content, '<i class="fas fa-print') !== false) {
        echo "Already modified: $path\n";
        continue;
    }

    $button = <<<HTML
                <a href="{{ route('$route_name') }}" target="_blank" class="bg-white border border-gray-200 hover:border-teal-500 text-gray-700 hover:text-teal-600 font-bold py-2 px-4 rounded-full shadow-sm flex items-center gap-2 transition text-sm">
                    <i class="fas fa-print text-teal-500"></i> Cetak Laporan PDF
                </a>
            </div>
HTML;
    
    // Replace the closing div of the return block
    // Specifically looking for "Kembali ke Pusat Laporan\n                </a>\n            </div>"
    $pattern = '/Kembali ke Pusat Laporan\s*<\/a>\s*<\/div>/i';
    
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, "Kembali ke Pusat Laporan\n                </a>\n" . $button, $content);
        file_put_contents($fullPath, $content);
        echo "Updated: $path\n";
    } else {
        echo "Pattern not found in: $path\n";
    }
}
echo "Done.\n";
