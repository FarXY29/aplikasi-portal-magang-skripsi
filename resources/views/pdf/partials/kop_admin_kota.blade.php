<table class="kop-surat" style="border: none; width: 100%;">
    <tr style="border: none;">
        <td width="12%" align="center" style="border: none; padding: 0; vertical-align: middle;">
            @if(!empty($kop_logo_path) && file_exists($kop_logo_path))
                <img src="{{ $kop_logo_path }}" class="kop-logo" alt="Logo">
            @else
                <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="kop-logo" alt="Logo">
            @endif
        </td>
        <td width="88%" class="kop-text" style="border: none; padding: 0; text-align: center; vertical-align: middle;">
            <div class="kop-pemerintah">{{ $kop_line1 ?? 'PEMERINTAH KOTA BANJARMASIN' }}</div>
            <div class="kop-dinas">{{ $kop_line2 ?? 'BADAN KESATUAN BANGSA DAN POLITIK' }}</div>
            <div class="kop-alamat">{{ $kop_line3 ?? 'Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111' }}</div>
        </td>
    </tr>
</table>
