<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
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
                    <i class="fas fa-graduation-cap text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Master Program Studi & Jurusan</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Standarisasi data program studi, jenjang pendidikan, dan rumpun keilmuan</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.master.major-categories.index') }}" class="action-btn inline-flex items-center px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50 dark:hover:bg-gray-700 shadow-xs">
                    <i class="fas fa-layer-group text-teal-600 mr-1.5"></i> Kelola Rumpun Keilmuan
                </a>
                <div class="text-xs font-bold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
                    Total: <span class="font-black text-teal-600 dark:text-teal-400">{{ $majors->total() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 font-[Inter]" x-data="{
        modalRumpunOpen: false,
        newCatName: '',
        newCatCode: '',
        newCatDesc: '',
        catLoading: false,
        catError: '',
        catSuccess: '',
        async submitNewCategory() {
            if (!this.newCatName.trim() || !this.newCatCode.trim()) {
                this.catError = 'Nama dan Kode Rumpun wajib diisi.';
                return;
            }
            this.catLoading = true;
            this.catError = '';
            try {
                const response = await fetch('{{ route('admin.master.major-categories.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: this.newCatName,
                        code: this.newCatCode,
                        description: this.newCatDesc
                    })
                });
                const result = await response.json();
                if (!response.ok) {
                    this.catError = result.message || 'Gagal menyimpan rumpun keilmuan.';
                } else {
                    this.catSuccess = 'Rumpun ' + result.category.name + ' berhasil ditambahkan!';
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                }
            } catch (err) {
                this.catError = 'Terjadi kesalahan koneksi sistem.';
            } finally {
                this.catLoading = false;
            }
        }
    }">
        <div class="flex flex-col gap-4 mb-6 print:hidden">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" @click="modalRumpunOpen = true" class="action-btn inline-flex items-center px-3.5 py-1.5 bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 border border-teal-200 dark:border-teal-800 rounded-xl text-xs font-bold hover:bg-teal-100 transition shadow-2xs">
                        <i class="fas fa-plus-circle mr-1.5"></i> Tambah Rumpun Manual
                    </button>
                    <a href="{{ route('admin.master.majors.create') }}" class="action-btn inline-flex items-center px-3.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        <i class="fas fa-plus mr-1.5 text-[10px]"></i> Tambah Jurusan
                    </a>
                </div>
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

            <!-- Filter Bar -->
            <form method="GET" action="{{ route('admin.master.majors.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama jurusan atau rumpun..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                </div>

                <div class="w-48">
                    <select name="category_id" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                        <option value="">Semua Rumpun</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-32">
                    <select name="degree_level" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                        <option value="">Semua Jenjang</option>
                        <option value="SMK" {{ request('degree_level') == 'SMK' ? 'selected' : '' }}>SMK</option>
                        <option value="D3" {{ request('degree_level') == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ request('degree_level') == 'D4' ? 'selected' : '' }}>D4</option>
                        <option value="S1" {{ request('degree_level') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ request('degree_level') == 'S2' ? 'selected' : '' }}>S2</option>
                    </select>
                </div>

                <div class="w-32">
                    <select name="status" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <button type="submit" class="action-btn px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-xl font-bold text-xs hover:bg-gray-700 transition">
                    <i class="fas fa-filter mr-1.5"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'category_id', 'degree_level', 'status']))
                    <a href="{{ route('admin.master.majors.index') }}" class="action-btn px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">No</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Program Studi / Jurusan</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Jenjang</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Rumpun Keilmuan</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Total Peserta</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Status</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                        @forelse($majors as $index => $major)
                            <tr class="table-row hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                                <td class="py-3.5 px-4 font-mono text-gray-400">{{ $majors->firstItem() + $index }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $major->name }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg font-black text-[10px] uppercase {{ $major->degree_level === 'S1' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800' : ($major->degree_level === 'SMK' ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800' : 'bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400 border border-teal-200 dark:border-teal-800') }}">
                                        {{ $major->degree_level }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $major->category->name ?? '-' }}</span>
                                        <span class="text-[10px] font-mono text-gray-400">({{ $major->category->code ?? '' }})</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold font-mono text-gray-700 dark:text-gray-300">{{ $major->users_count }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <form action="{{ route('admin.master.majors.toggle', $major->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black transition {{ $major->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 hover:bg-gray-200' }}">
                                            <i class="fas fa-circle text-[6px] mr-1.5 {{ $major->is_active ? 'text-emerald-500' : 'text-gray-400' }}"></i>
                                            {{ $major->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.master.majors.edit', $major->id) }}" class="action-btn w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center hover:bg-amber-100" title="Edit Jurusan">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>

                                        @if($major->users_count == 0)
                                            <form action="{{ route('admin.master.majors.destroy', $major->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn w-7 h-7 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center hover:bg-red-100" title="Hapus Jurusan">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-graduation-cap text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="font-medium">Belum ada data program studi / jurusan yang sesuai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($majors->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $majors->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah Rumpun Keilmuan Manual -->
        <div x-show="modalRumpunOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
            <div @click.away="modalRumpunOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h3 class="font-black text-base text-gray-900 dark:text-gray-100">Tambah Rumpun Keilmuan Manual</h3>
                    </div>
                    <button @click="modalRumpunOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div x-show="catError" class="p-3 mb-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs" x-text="catError"></div>
                <div x-show="catSuccess" class="p-3 mb-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs" x-text="catSuccess"></div>

                <form @submit.prevent="submitNewCategory" class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                            Nama Rumpun Keilmuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="newCatName" placeholder="Contoh: Pariwisata & Perhotelan" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                            Kode Singkatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="newCatCode" placeholder="Contoh: PARIWISATA, SENI" class="w-full text-xs font-mono uppercase rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                            Deskripsi (Opsional)
                        </label>
                        <textarea x-model="newCatDesc" rows="2" placeholder="Penjelasan rumpun keilmuan..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2 px-3 focus:ring-teal-500 focus:border-teal-500"></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2.5">
                        <button type="button" @click="modalRumpunOpen = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit" :disabled="catLoading" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm disabled:opacity-50">
                            <span x-show="!catLoading"><i class="fas fa-save mr-1"></i> Simpan Rumpun</span>
                            <span x-show="catLoading"><i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
