@php
    $curInstansi = $instansi ?? (Auth::check() ? Auth::user()->instansi : null);
    $dinasName = $curInstansi?->nama_dinas ? strtoupper($curInstansi->nama_dinas) : 'DINAS / INSTANSI TERKAIT';
    
    // Address & contact line
    $alamat = $curInstansi?->alamat ?? 'Jalan RE Martadinata No. 1, Banjarmasin';
    $kontakParts = [];
    if (!empty($curInstansi?->contact_whatsapp)) {
        $kontakParts[] = 'Telp/WA: ' . $curInstansi->contact_whatsapp;
    }
    if (!empty($curInstansi?->email)) {
        $kontakParts[] = 'Pos-el: ' . $curInstansi->email;
    }
    $infoKontak = !empty($kontakParts) ? implode(' | ', $kontakParts) : '';
    $baris3 = $alamat . ($infoKontak ? ' - ' . $infoKontak : '');

    // Logo default Pemkot
    $logoPath = public_path('images/Banjarmasin_Logo.svg.png');
    if (!file_exists($logoPath)) {
        $logoPath = null;
    }
@endphp

<table class="kop-surat" style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 0;">
    <tr style="border: none;">
        <td style="width: 14%; border: none; padding: 0; text-align: center; vertical-align: middle;">
            @if($logoPath)
                <img src="{{ $logoPath }}" style="width: 72px; height: auto;" alt="Logo Pemkot">
            @endif
        </td>
        <td style="width: 86%; border: none; padding: 0; text-align: center; vertical-align: middle;">
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 13pt; font-weight: normal; text-transform: uppercase; letter-spacing: 1px; line-height: 1.1; margin: 0;">
                PEMERINTAH KOTA BANJARMASIN
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2; margin: 2px 0;">
                {{ $dinasName }}
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 8.5pt; font-style: italic; line-height: 1.2; margin: 0; color: #222;">
                {{ $baris3 }}
            </div>
        </td>
    </tr>
</table>
<div style="border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 2px; margin: 4px 0 14px 0;"></div>

