<x-app-layout>
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-600 dark:bg-amber-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-pen text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Edit Program Studi / Jurusan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Perbarui informasi nama jurusan, jenjang, atau rumpun keilmuan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6 font-[Inter]" x-data="{
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
                    const select = document.getElementById('major_category_id');
                    const opt = document.createElement('option');
                    opt.value = result.category.id;
                    opt.text = result.category.name + ' (' + result.category.code + ')';
                    opt.selected = true;
                    select.appendChild(opt);
                    this.catSuccess = 'Rumpun ' + result.category.name + ' berhasil ditambahkan!';
                    setTimeout(() => {
                        this.modalRumpunOpen = false;
                        this.newCatName = '';
                        this.newCatCode = '';
                        this.newCatDesc = '';
                        this.catSuccess = '';
                    }, 1000);
                }
            } catch (err) {
                this.catError = 'Terjadi kesalahan koneksi sistem.';
            } finally {
                this.catLoading = false;
            }
        }
    }">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.master.majors.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                </div>
                Kembali ke Daftar Jurusan
            </a>

            <button type="button" @click="modalRumpunOpen = true" class="inline-flex items-center px-3.5 py-1.5 bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 border border-teal-200 dark:border-teal-800 rounded-xl text-xs font-bold hover:bg-teal-100 transition shadow-2xs">
                <i class="fas fa-plus-circle mr-1.5"></i> Tambah Rumpun Manual
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8">
            <form action="{{ route('admin.master.majors.update', $major->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Rumpun Keilmuan -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="major_category_id" class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Rumpun Keilmuan <span class="text-red-500">*</span>
                        </label>
                        <button type="button" @click="modalRumpunOpen = true" class="text-xs font-bold text-teal-600 hover:underline inline-flex items-center gap-1">
                            <i class="fas fa-plus text-[10px]"></i> Tambah Rumpun Baru
                        </button>
                    </div>
                    <select id="major_category_id" name="major_category_id" required class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 shadow-xs">
                        <option value="">-- Pilih Rumpun Keilmuan --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('major_category_id', $major->major_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('major_category_id')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Jurusan -->
                <div>
                    <label for="name" class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                        Nama Program Studi / Jurusan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $major->name) }}" required placeholder="Contoh: Teknik Informatika, Ilmu Komunikasi, Akuntansi" class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 shadow-xs">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenjang Pendidikan -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                        Jenjang Pendidikan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                        @foreach(['SMK', 'D3', 'D4', 'S1', 'S2'] as $degree)
                            <label class="flex items-center justify-center p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 cursor-pointer hover:border-teal-500 transition has-[:checked]:bg-teal-50 dark:has-[:checked]:bg-teal-950/40 has-[:checked]:border-teal-500 has-[:checked]:text-teal-700 dark:has-[:checked]:text-teal-400 font-bold text-xs">
                                <input type="radio" name="degree_level" value="{{ $degree }}" {{ old('degree_level', $major->degree_level) == $degree ? 'checked' : '' }} class="sr-only">
                                <span>{{ $degree }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('degree_level')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $major->is_active) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 rounded border-gray-300 focus:ring-teal-500">
                        <div>
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">Status Aktif</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jurusan aktif akan muncul pada formulir pendaftaran peserta magang.</p>
                        </div>
                    </label>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ route('admin.master.majors.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal Tambah Rumpun Keilmuan Manual -->
        <div x-show="modalRumpunOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
            <div @click.away="modalRumpunOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h3 class="font-black text-base text-gray-900 dark:text-gray-100">Tambah Rumpun Keilmuan</h3>
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
                            Nama Rumpun <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="newCatName" placeholder="Contoh: Seni, Desain & Komunikasi Visual" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                            Kode Singkatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="newCatCode" placeholder="Contoh: DKV_SENI, BISNIS" class="w-full text-xs font-mono uppercase rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
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
