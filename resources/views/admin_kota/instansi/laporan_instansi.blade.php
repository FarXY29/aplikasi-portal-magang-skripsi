<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2 print:hidden">
            <i class="fas fa-file-alt text-teal-600"></i>
            {{ __('Laporan Master Data Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900/50 min-h-screen print:bg-white dark:print:bg-gray-800 print:py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-full print:px-0">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 print:hidden">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 bg-gray-800 text-white rounded-xl font-bold hover:bg-gray-700 shadow-lg transition transform active:scale-95 text-sm">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden print:shadow-none print:rounded-none">
                <div class="p-8 print:p-0">
                    
                    <div class="hidden print:flex items-center justify-center mb-8 border-b-2 border-gray-800 pb-4 gap-4">
                        <img src="{{ asset('images/Banjarmasin_Logo.svg.png') }}" class="h-20 w-auto" alt="Logo Pemkot">
                        
                        <div class="text-center">
                            <h1 class="text-2xl font-bold uppercase tracking-wide">Pemerintah Kota Banjarmasin</h1>
                            <h2 class="text-xl font-semibold">Laporan Data Satuan Kerja Perangkat Daerah</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 italic mt-1">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-6 print:hidden border-b border-gray-100 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Preview Laporan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Berikut adalah data seluruh Instansi yang terdaftar dalam sistem.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900 print:bg-gray-100 dark:print:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border w-12">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border">Nama Instansi</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border">Kode Unit</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border">Alamat Kantor</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border w-40">Koordinat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse($instansis as $index => $instansi)
                                <tr class="break-inside-avoid">
                                    <td class="px-4 py-3 text-center text-sm text-gray-900 dark:text-gray-100 border">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 border">{{ $instansi->nama_dinas }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900 dark:text-gray-100 border">{{ $instansi->kode_unit_kerja }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border">{{ $instansi->alamat }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 font-mono border">
                                        Lat: {{ $instansi->latitude }}<br>
                                        Lng: {{ $instansi->longitude }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 border italic">
                                        Tidak ada data Instansi yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="hidden print:flex justify-end mt-16 break-inside-avoid">
                        <div class="text-center w-64">
                            <p class="mb-1">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            <p class="font-bold">Kepala Bakesbangpol</p>
                            <div class="h-24"></div> <p class="font-bold underline">H. LUKMAN FADLUN, SH</p>
                            <p class="text-sm">NIP. 19700101 200001 1 001</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page {
                margin: 20mm;
                size: landscape; /* Orientasi Landscape agar tabel muat */
            }
            body {
                background: white;
                -webkit-print-color-adjust: exact;
            }
            /* Hide Navbar, Sidebar, Toolbar */
            nav, header, .print\:hidden {
                display: none !important;
            }
            /* Ensure table borders show up */
            table, th, td {
                border: 1px solid #000 !important;
            }
            /* Avoid breaking rows inside table */
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</x-app-layout>