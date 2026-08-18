<x-app-layout>
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <style>
            .action-btn { transition: all 0.2s ease; }
            .action-btn:hover { transform: translateY(-1px); }
            .table-row { transition: background-color 0.15s ease; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-layer-group text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Master Rumpun Keilmuan</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Pengelompokan bidang keahlian untuk standarisasi kebutuhan formasi OPD</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.master.majors.index') }}" class="action-btn inline-flex items-center px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50 dark:hover:bg-gray-700 shadow-xs">
                    <i class="fas fa-graduation-cap text-teal-600 mr-1.5"></i> Lihat Semua Jurusan
                </a>
                <div class="text-xs font-bold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
                    Total Rumpun: <span class="font-black text-teal-600 dark:text-teal-400">{{ $categories->total() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 font-[Inter]">
        <div class="flex flex-col gap-4 mb-6 print:hidden">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.master.majors.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                    </div>
                    Kembali ke Master Jurusan
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-2">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="error" class="mb-2">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <!-- Search and Action Bar -->
            <form method="GET" action="{{ route('admin.master.major-categories.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama rumpun, kode, atau deskripsi..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                </div>

                <button type="submit" class="action-btn px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-xl font-bold text-xs hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-1.5"></i> Cari
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route('admin.master.major-categories.index') }}" class="action-btn px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif

                <div class="ml-auto">
                    <a href="{{ route('admin.master.major-categories.create') }}" class="action-btn inline-flex items-center px-4 py-2 bg-teal-600 dark:bg-teal-500 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-teal-700 transition shadow-sm">
                        <i class="fas fa-plus mr-1.5 text-[10px]"></i> Tambah Rumpun
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">No</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Kode</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Rumpun Keilmuan</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Deskripsi Cakupan</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Jumlah Jurusan</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                        @forelse($categories as $index => $category)
                            <tr class="table-row hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                                <td class="py-3.5 px-4 font-mono text-gray-400">{{ $categories->firstItem() + $index }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono font-black text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $category->code }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $category->name }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-400 max-w-xs">
                                    <p class="truncate">{{ $category->description ?? '-' }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('admin.master.majors.index', ['category_id' => $category->id]) }}" class="inline-flex items-center gap-1 font-bold font-mono text-teal-600 dark:text-teal-400 hover:underline">
                                        {{ $category->majors_count }} Jurusan
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.master.major-categories.edit', $category->id) }}" class="action-btn w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center hover:bg-amber-100" title="Edit Rumpun">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>

                                        @if($category->majors_count == 0 && $category->internship_positions_count == 0)
                                            <form action="{{ route('admin.master.major-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rumpun keilmuan ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn w-7 h-7 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center hover:bg-red-100" title="Hapus Rumpun">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-layer-group text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="font-medium">Belum ada data rumpun keilmuan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
