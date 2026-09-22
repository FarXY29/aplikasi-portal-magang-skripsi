{{--
    Partial: Logika Alpine/vanilla untuk form pengguna (create & edit).
    Menampilkan field instansi_id / asal_instansi secara kondisional berdasarkan role.
    Asumsi markup host menyediakan elemen:
      #roleSelect, #instansiDinasField, #asalSekolahField, #noneField,
      [name="instansi_id"], [name="asal_instansi"]
--}}

@push('scripts')
<script>
    window.toggleFields = function () {
        const roleSelect = document.getElementById('roleSelect');
        if (!roleSelect) return;

        const role = roleSelect.value;
        const instansiDinasField = document.getElementById('instansiDinasField');
        const asalSekolahField = document.getElementById('asalSekolahField');
        const noneField = document.getElementById('noneField');
        const instansiInput = document.querySelector('[name="instansi_id"]');
        const asalInstansiInput = document.querySelector('[name="asal_instansi"]');

        const needsInstansi = role === 'admin_instansi' || role === 'pembimbing_lapangan';
        const needsAsalInstansi = role === 'pembimbing' || role === 'peserta';

        if (instansiDinasField) instansiDinasField.classList.add('hidden');
        if (asalSekolahField) asalSekolahField.classList.add('hidden');
        if (noneField) noneField.classList.add('hidden');

        if (instansiInput) {
            instansiInput.disabled = !needsInstansi;
            instansiInput.required = needsInstansi;
        }
        if (asalInstansiInput) {
            asalInstansiInput.disabled = !needsAsalInstansi;
            asalInstansiInput.required = needsAsalInstansi;
        }

        if (needsInstansi) {
            if (instansiDinasField) instansiDinasField.classList.remove('hidden');
        } else if (needsAsalInstansi) {
            if (asalSekolahField) asalSekolahField.classList.remove('hidden');
        } else {
            if (noneField) noneField.classList.remove('hidden');
        }
    };

    document.addEventListener('DOMContentLoaded', window.toggleFields);
    document.addEventListener('turbo:load', window.toggleFields);
</script>
@endpush
