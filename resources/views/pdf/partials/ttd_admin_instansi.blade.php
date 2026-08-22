@php
    $curInstansi = $instansi ?? (Auth::check() ? Auth::user()->instansi : null);
    $pNama = $curInstansi?->nama_pejabat ?? '........................................';
    $pNip = $curInstansi?->nip_pejabat ?? '....................';
    $pJabatan = $curInstansi?->jabatan_pejabat ?? 'Kepala Dinas';
    $dinasName = $curInstansi?->nama_dinas ?? '';
    
    $ttdKepalaPath = null;
    if (!empty($curInstansi?->ttd_kepala)) {
        $rawK = $curInstansi->ttd_kepala;
        if (file_exists(storage_path('app/public/' . $rawK))) {
            $ttdKepalaPath = storage_path('app/public/' . $rawK);
        } elseif (file_exists(public_path('storage/' . $rawK))) {
            $ttdKepalaPath = public_path('storage/' . $rawK);
        }
    }

    $tanggalCetak = $custom_date ?? \Carbon\Carbon::now()->translatedFormat('d F Y');
    $mode = $mode ?? 'single'; // 'single' or 'dual'
@endphp

<div class="ttd-container" style="width: 100%; margin-top: 25px; page-break-inside: avoid; font-family: 'Times New Roman', Times, serif;">
    @if($mode === 'dual')
        @php
            $pl = $pembimbing_lapangan ?? ($app->pembimbing_lapangan ?? null);
            $plNama = $pl?->name ?? '........................................';
            $plNip = $pl?->nik ?? $pl?->nomor_induk ?? '-';
            
            $ttdPlPath = null;
            if (!empty($pl?->signature)) {
                $rawPl = $pl->signature;
                if (file_exists(storage_path('app/public/' . $rawPl))) {
                    $ttdPlPath = storage_path('app/public/' . $rawPl);
                } elseif (file_exists(public_path('storage/' . $rawPl))) {
                    $ttdPlPath = public_path('storage/' . $rawPl);
                }
            }
        @endphp
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr style="border: none;">
                {{-- Left Header: Mengetahui Kepala Dinas --}}
                <td style="width: 48%; border: none; padding: 0; text-align: center; vertical-align: top;">
                    <div style="font-size: 10pt; margin-bottom: 2px;">Mengetahui,</div>
                    <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase;">{{ $pJabatan }}</div>
                    @if($dinasName)
                        <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase;">{{ $dinasName }}</div>
                    @endif
                    
                    <div style="height: 60px; margin: 4px 0; text-align: center; vertical-align: middle;">
                        @if($ttdKepalaPath)
                            <img src="{{ $ttdKepalaPath }}" style="max-height: 58px; max-width: 140px; display: inline-block;">
                        @else
                            <div style="height: 58px;"></div>
                        @endif
                    </div>

                    <div style="font-size: 10pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">{{ $pNama }}</div>
                    <div style="font-size: 9.5pt; color: #333; margin-top: 2px;">NIP. {{ $pNip }}</div>
                </td>
                <td style="width: 4%; border: none;"></td>
                {{-- Right Header: Pembimbing Lapangan --}}
                <td style="width: 48%; border: none; padding: 0; text-align: center; vertical-align: top;">
                    <div style="font-size: 10pt; margin-bottom: 2px;">Banjarmasin, {{ $tanggalCetak }}</div>
                    <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase;">Pembimbing Lapangan</div>
                    @if($dinasName)
                        <div style="font-size: 9pt; color: transparent;">&nbsp;</div>
                    @endif

                    <div style="height: 60px; margin: 4px 0; text-align: center; vertical-align: middle;">
                        @if($ttdPlPath)
                            <img src="{{ $ttdPlPath }}" style="max-height: 58px; max-width: 140px; display: inline-block;">
                        @else
                            <div style="height: 58px;"></div>
                        @endif
                    </div>

                    <div style="font-size: 10pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">{{ $plNama }}</div>
                    <div style="font-size: 9.5pt; color: #333; margin-top: 2px;">NIP/NIK. {{ $plNip }}</div>
                </td>
            </tr>
        </table>
    @else
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr style="border: none;">
                <td style="width: 58%; border: none; padding: 0;"></td>
                <td style="width: 42%; border: none; padding: 0; text-align: center; vertical-align: top;">
                    <div style="font-size: 10pt; margin-bottom: 2px;">Banjarmasin, {{ $tanggalCetak }}</div>
                    <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase;">{{ $pJabatan }}</div>
                    @if($dinasName)
                        <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase;">{{ $dinasName }}</div>
                    @endif
                    
                    <div style="height: 60px; margin: 4px 0; text-align: center; vertical-align: middle;">
                        @if($ttdKepalaPath)
                            <img src="{{ $ttdKepalaPath }}" style="max-height: 58px; max-width: 140px; display: inline-block;">
                        @else
                            <div style="height: 58px;"></div>
                        @endif
                    </div>

                    <div style="font-size: 10pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">{{ $pNama }}</div>
                    <div style="font-size: 9.5pt; color: #333; margin-top: 2px;">NIP. {{ $pNip }}</div>
                </td>
            </tr>
        </table>
    @endif
</div>

