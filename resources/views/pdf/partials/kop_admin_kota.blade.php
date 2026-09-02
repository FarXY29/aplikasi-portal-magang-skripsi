@php
    $logoPath = $kop_logo_path ?? null;
    if (!$logoPath || !file_exists($logoPath)) {
        $defaultLogo = public_path('images/Banjarmasin_Logo.svg.png');
        $logoPath = file_exists($defaultLogo) ? $defaultLogo : null;
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
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 12.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; line-height: 1.15; margin: 0; color: #000;">
                {{ $kop_line1 ?? 'PEMERINTAH KOTA BANJARMASIN' }}
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 14.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2; margin: 2px 0; color: #000;">
                {{ $kop_line2 ?? 'BADAN KESATUAN BANGSA DAN POLITIK' }}
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 8.5pt; font-style: normal; line-height: 1.25; margin: 0; color: #222;">
                {{ $kop_line3 ?? 'Jalan RE Martadinata No. 1, Telp (0511) 3352932, Pos-el: bakesbangpol@banjarmasinkota.go.id, Banjarmasin 70111' }}
            </div>
        </td>
    </tr>
</table>
<div style="margin: 4px 0 12px 0;">
    <div style="border-top: 2.5px solid #000; margin-bottom: 1.5px;"></div>
    <div style="border-top: 1px solid #000;"></div>
</div>
