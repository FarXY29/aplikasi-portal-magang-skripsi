{{--
    Partial: Factory komponen Alpine untuk form "Tambah Rumpun Keilmuan" cepat.
    Dipakai oleh majors/index, majors/create, dan majors/edit.

    Parameter `mode`:
      - 'reload' (default): setelah sukses, muat ulang halaman (dipakai di index).
      - 'append': setelah sukses, tambahkan <option> ke #major_category_id & reset form
                  tanpa reload (dipakai di create/edit).

    Pemakaian:
      <div x-data="majorCategoryForm()"> ... </div>
      <div x-data="majorCategoryForm({ mode: 'append' })"> ... </div>
--}}

@once
@push('scripts')
<script>
    window.majorCategoryForm = function (options) {
        const opts = options || {};
        const mode = opts.mode || 'reload';

        return {
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
                    const response = await fetch(@js(route('admin.master.major-categories.store')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @js(csrf_token())
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
                        return;
                    }

                    this.catSuccess = 'Rumpun ' + result.category.name + ' berhasil ditambahkan!';

                    if (mode === 'append') {
                        const select = document.getElementById('major_category_id');
                        if (select) {
                            const opt = document.createElement('option');
                            opt.value = result.category.id;
                            opt.text = result.category.name + ' (' + result.category.code + ')';
                            opt.selected = true;
                            select.appendChild(opt);
                        }
                        setTimeout(() => {
                            this.modalRumpunOpen = false;
                            this.newCatName = '';
                            this.newCatCode = '';
                            this.newCatDesc = '';
                            this.catSuccess = '';
                        }, 1000);
                    } else {
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
        };
    };
</script>
@endpush
@endonce
