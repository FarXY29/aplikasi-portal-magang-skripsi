<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Balasan Magang - {{ $app->user->name }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm 1.5cm 2.5cm; /* Atas Kanan Bawah Kiri */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt; /* Ukuran font standar surat dinas */
            line-height: 1.2;
            text-align: justify;
        }

        /* KOP SURAT */
        .header-container {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda */
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header-table {
            width: 100%;
        }
        .logo-cell {
            width: 15%;
            text-align: center;
            vertical-align: middle;
        }
        .text-cell {
            width: 85%;
            text-align: center;
            vertical-align: middle;
        }
        .logo {
            width: 70px;
            height: auto;
        }
        .header-pemko { margin: 0; font-size: 14pt; font-weight: normal; text-transform: uppercase; letter-spacing: 1px; }
        .header-dinas { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
        .header-alamat { margin: 0; font-size: 9pt; margin-top: 2px; }

        /* TANGGAL */
        .date-section {
            text-align: right;
            margin-bottom: 10px;
        }

        /* TABEL INFO (Nomor &amp; Tujuan) */
        .info-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding: 1px 0;
        }
        
        /* Lebar Kolom Kiri (Nomor, Sifat, Lampiran, Hal) */
        .col-left-label { width: 12%; white-space: nowrap; }
        .col-left-sep { width: 2%; text-align: center; }
        .col-left-content { width: 46%; padding-right: 10px; }

        /* Lebar Kolom Kanan (Yth) */
        .col-right { width: 40%; }

        /* ISI SURAT */
        .content {
            margin-bottom: 10px;
        }
        .paragraph {
            text-indent: 30px; /* Menjorok ke dalam */
            margin-bottom: 8px;
            text-align: justify;
        }

        /* DATA MAHASISWA */
        .student-table {
            width: 95%;
            margin-left: 20px; /* Indentasi tabel */
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .student-table td {
            padding: 2px;
            vertical-align: top;
        }
        .st-num { width: 5%; text-align: center; }
        .st-label { width: 20%; }
        .st-sep { width: 3%; }
        .st-content { width: 72%; font-weight: bold; }

        /* TANDA TANGAN */
        .signature-wrapper {
            width: 100%;
            margin-top: 30px;
            display: table; /* Hack untuk float clearing */
        }
        .signature-box {
            float: right;
            width: 45%; /* Lebar area tanda tangan */
            text-align: center;
        }
        .ttd-img {
            height: 65px;
            margin: 5px 0;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Helper */
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="logo" alt="Logo">
                </td>
                <td class="text-cell">
                    <h3 class="header-pemko">PEMERINTAH KOTA BANJARMASIN</h3>
                    <h1 class="header-dinas">{{ $app->position->instansi->nama_dinas }}</h1>
                    <p class="header-alamat">{{ $app->position->instansi->alamat ?? 'Alamat Kantor Belum Diatur' }}</p>
                    <p class="header-alamat">Email: {{ $app->position->instansi->email ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="date-section">
        Banjarmasin, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
    </div>

    <div class="info-container">
        <table class="info-table">
            <tr>
                <td class="col-left-label">Nomor</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content">
                    800/{{ str_pad($app->id, 3, '0', STR_PAD_LEFT) }}-Sekr/{{ $app->position->instansi->singkatan ?? 'INSTANSI' }}/{{ \Carbon\Carbon::now()->format('m') }}/{{ date('Y') }}
                </td>

                <td class="col-right" rowspan="4" style="vertical-align: top;">
                    Yth.<br>
                    Dekan / Pimpinan<br>
                    <strong>{{ $app->user->asal_instansi }}</strong><br>
                    di-<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempat
                </td>
            </tr>
            <tr>
                <td class="col-left-label">Sifat</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content">Biasa</td>
            </tr>
            <tr>
                <td class="col-left-label">Lampiran</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content">-</td>
            </tr>
            <tr>
                <td class="col-left-label">Hal</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content" style="font-weight: bold;">Surat Balasan Persetujuan Magang</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p class="paragraph">
            Sehubungan dengan surat dari <strong>{{ $app->user->asal_instansi }}</strong> perihal Permohonan Kesediaan Menerima Praktik Kerja Lapangan (PKL) / Magang Mahasiswa/i.
        </p>

        <p class="paragraph">
            Berkaitan hal tersebut di atas, maka Dinas Komunikasi, Informatika dan Statistik Kota Banjarmasin pada prinsipnya <strong>MENYETUJUI</strong> Mahasiswa yang akan Magang pada Dinas kami, yaitu atas nama:
        </p>

        <table class="student-table">
            <tr>
                <td class="st-num">1.</td>
                <td class="st-label">Nama</td>
                <td class="st-sep">:</td>
                <td class="st-content">{{ $app->user->name }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">NIM / NISN</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">{{ $app->user->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">Prodi / Jurusan</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">{{ $app->user->major ?? '-' }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">Waktu</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">
                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <p class="paragraph">
            Selama mengikuti program magang tersebut, diharapkan Mahasiswa dapat mengikuti ketentuan dan tata tertib yang berlaku pada {{ $app->position->instansi->nama_dinas }}.
        </p>

        <p class="paragraph">
            Demikian disampaikan, untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-wrapper">
        <div class="signature-box">
            <span>a.n. KEPALA DINAS</span><br>
            <span style="text-transform: uppercase;">{{ $app->position->instansi->jabatan_pejabat ?? 'Sekretaris' }},</span>
            
            <br>
            @if(!empty($app->position->instansi->ttd_kepala))
                <img src="{{ storage_path('app/public/' . $app->position->instansi->ttd_kepala) }}" class="ttd-img" alt="TTD">
            @else
                <br><br><br>
            @endif
            
            <span class="bold underline" style="text-transform: uppercase;">
                {{ $app->position->instansi->nama_pejabat ?? 'Nama Pejabat Belum Diatur' }}
            </span><br>
            <span>{{ $app->position->instansi->pangkat_pejabat ?? 'Pembina' }}</span><br>
            <span>NIP. {{ $app->position->instansi->nip_pejabat ?? '-' }}</span>
        </div>
    </div>

</body>
</html>