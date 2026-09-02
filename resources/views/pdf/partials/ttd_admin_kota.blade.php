@php
    $pNama = $pejabat_nama ?? \App\Models\Setting::value('pejabat_name') ?? 'H. Lukman Fadlun, SH';
    $pNip = $pejabat_nip ?? \App\Models\Setting::value('pejabat_nip') ?? '-';
    $pJabatan = $pejabat_jabatan ?? \App\Models\Setting::value('pejabat_jabatan') ?? 'Kepala Badan Kesatuan Bangsa dan Politik';
    $ttdImg = \App\Models\Setting::value('ttd_image');
    $ttdFile = $ttd_image_path ?? null;
    if (!$ttdFile && $ttdImg) {
        if (\Illuminate\Support\Facades\Storage::disk('private')->exists($ttdImg)) {
            $ttdFile = \Illuminate\Support\Facades\Storage::disk('private')->path($ttdImg);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($ttdImg)) {
            $ttdFile = \Illuminate\Support\Facades\Storage::disk('public')->path($ttdImg);
        } elseif (file_exists(storage_path('app/public/' . $ttdImg))) {
            $ttdFile = storage_path('app/public/' . $ttdImg);
        } elseif (file_exists(public_path('storage/' . $ttdImg))) {
            $ttdFile = public_path('storage/' . $ttdImg);
        }
    }
    $tanggalCetak = $custom_date ?? \Carbon\Carbon::now()->translatedFormat('d F Y');
@endphp

<div class="ttd-container" style="width: 100%; margin-top: 25px; page-break-inside: avoid; font-family: 'Times New Roman', Times, serif;">
    <table style="width: 100%; border: none; border-collapse: collapse;">
        <tr style="border: none;">
            <td style="width: 58%; border: none; padding: 0;"></td>
            <td style="width: 42%; border: none; padding: 0; text-align: center; vertical-align: top;">
                <div style="font-size: 10pt; margin-bottom: 2px;">Banjarmasin, {{ $tanggalCetak }}</div>
                <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase;">{{ $pJabatan }}</div>
                
                <div style="height: 60px; margin: 4px 0; text-align: center; vertical-align: middle;">
                    @if($ttdFile && file_exists($ttdFile))
                        <img src="{{ $ttdFile }}" style="max-height: 58px; max-width: 140px; display: inline-block;">
                    @else
                        <div style="height: 58px;"></div>
                    @endif
                </div>

                <div style="font-size: 10pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">{{ $pNama }}</div>
                <div style="font-size: 9.5pt; color: #333; margin-top: 2px;">NIP. {{ $pNip }}</div>
            </td>
        </tr>
    </table>
</div>

