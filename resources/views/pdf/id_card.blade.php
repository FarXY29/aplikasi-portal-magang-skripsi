<!DOCTYPE html>
<html>
<head>
    <title>ID Card Magang</title>
    <style>
        @page {
            margin: 0;
            size: 153.07pt 242.64pt portrait;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica", sans-serif;
            background-color: #ffffff;
        }
        .wrapper {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            overflow: hidden;
            border: 1pt solid #cbd5e1;
            box-sizing: border-box;
        }

        /* Top Background */
        .bg-top {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 60pt;
            background-color: #0F766E;
        }
        /* Bottom thick accent line */
        .bg-bottom {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 4pt;
            background-color: #D4AF37;
        }

        /* Header Texts */
        .header-title {
            position: absolute;
            top: 8pt; left: 0; right: 0;
            text-align: center;
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .header-instansi {
            position: absolute;
            top: 20pt; left: 5pt; right: 5pt;
            text-align: center;
            color: #ccfbf1;
            font-size: 5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        /* Photo Container */
        .photo-container {
            position: absolute;
            top: 36pt;
            left: 48.5pt; /* (153.07 - 56) / 2 = 48.53 */
            width: 52pt;
            height: 52pt;
            background: white;
            border-radius: 50%;
            padding: 2pt;
            border: 0.5pt solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .photo {
            width: 52pt;
            height: 52pt;
            border-radius: 50%;
            object-fit: cover;
        }
        .no-photo {
            width: 52pt;
            height: 52pt;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #94a3b8;
            text-align: center;
            line-height: 52pt;
            font-size: 8pt;
            font-weight: bold;
        }

        /* Identity */
        .identity {
            position: absolute;
            top: 96pt; left: 5pt; right: 5pt;
            text-align: center;
        }
        .name {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.1;
        }
        .role {
            font-size: 6pt;
            color: #0F766E;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3pt;
        }

        /* Details Box */
        .details-box {
            position: absolute;
            top: 128pt; left: 10pt; right: 10pt;
            background-color: #f8fafc;
            border: 0.5pt solid #e2e8f0;
            border-radius: 5pt;
            padding: 6pt 6pt;
        }
        .detail-item {
            margin-bottom: 4pt;
        }
        .detail-item:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-size: 4.5pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 1.5pt;
        }
        .detail-val {
            font-size: 6.5pt;
            color: #0f172a;
            font-weight: bold;
            line-height: 1.1;
        }

        /* QR and Footer */
        .qr-container {
            position: absolute;
            bottom: 6pt;
            right: 10pt;
            width: 36pt;
            height: 36pt;
            background: white;
            padding: 2pt;
            border: 0.5pt solid #e2e8f0;
            border-radius: 3pt;
        }
        .qr-img {
            width: 36pt;
            height: 36pt;
        }

        .footer-text {
            position: absolute;
            bottom: 12pt;
            left: 10pt;
            width: 85pt;
        }
        .footer-validity {
            font-size: 5.5pt;
            color: #0F766E;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3pt;
            letter-spacing: 0.5px;
        }
        .footer-desc {
            font-size: 4.5pt;
            color: #64748b;
            line-height: 1.4;
        }
        .footer-desc strong {
            color: #334155;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="bg-top"></div>
        
        <div class="header-title">ID CARD</div>
        <div class="header-instansi">{{ $app->position->instansi->nama_dinas }}</div>

        <div class="photo-container">
            @if ($app->user->photo && file_exists(public_path('storage/' . $app->user->photo)))
                <img src="{{ public_path('storage/' . $app->user->photo) }}" class="photo" alt="Photo">
            @else
                <div class="no-photo">NO PHOTO</div>
            @endif
        </div>

        <div class="identity">
            <div class="name">{{ $app->user->name }}</div>
            <div class="role">PESERTA MAGANG</div>
        </div>

        <div class="details-box">
            <div class="detail-item">
                <div class="detail-label">NIM / NIK</div>
                <div class="detail-val">{{ $app->user->nik ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Asal Kampus / Instansi</div>
                <div class="detail-val">{{ Str::limit($app->user->asal_instansi, 35) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Periode Magang</div>
                <div class="detail-val">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</div>
            </div>
        </div>

        <div class="footer-text">
            <div class="footer-validity">KARTU RESMI</div>
            <div class="footer-desc">
                Harap digunakan selama<br>
                berada di lingkungan kerja.<br>
                <strong>Scan QR untuk validasi.</strong>
            </div>
        </div>

        <div class="qr-container">
            <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(70)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }}" class="qr-img">
        </div>

        <div class="bg-bottom"></div>
    </div>
</body>
</html>
