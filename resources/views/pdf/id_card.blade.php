<!DOCTYPE html>
<html>
<head>
    <title>ID Card Magang</title>
    <style>
        @page {
            margin: 0;
            size: 153.07pt 242.64pt portrait; /* Explicitly set size in @page just in case */
        }
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica", sans-serif;
            background-color: #f8fafc;
        }

        #wrapper {
            position: absolute;
            top: 0; left: 0;
            width: 153.07pt;
            height: 242.64pt;
            overflow: hidden;
        }

        /* Pure CSS Background */
        .border-outer {
            position: absolute;
            top: 4pt; left: 4pt; right: 4pt; bottom: 4pt;
            border: 1.5pt solid #0F766E;
            z-index: 1;
        }
        .border-inner {
            position: absolute;
            top: 6pt; left: 6pt; right: 6pt; bottom: 6pt;
            border: 0.75pt solid #D4AF37;
            z-index: 2;
        }
        .header-bg {
            position: absolute;
            top: 6pt; left: 6pt; right: 6pt;
            height: 48pt;
            background-color: #0F766E;
            z-index: 3;
        }

        /* Content Container */
        .card-content {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            text-align: center;
            box-sizing: border-box;
            padding-top: 15pt;
            z-index: 10;
        }

        /* Header Area */
        .header {
            height: 39pt;
            box-sizing: border-box;
        }

        .header-title {
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            line-height: 1.1;
        }

        .header-subtitle {
            color: #ccfbf1;
            font-size: 4.5pt;
            margin-top: 2pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Photo Area */
        .photo-container {
            margin-top: 10pt;
            margin-bottom: 5pt;
            height: 55pt;
        }

        .photo {
            width: 55pt;
            height: 55pt;
            border-radius: 50%;
            object-fit: cover;
            border: 2pt solid #ffffff; 
        }
        
        .no-photo {
            width: 55pt;
            height: 55pt;
            border-radius: 50%;
            background-color: #e2e8f0;
            border: 2pt solid #ffffff;
            margin: 0 auto;
            line-height: 55pt;
            font-size: 8pt;
            color: #64748b;
        }

        /* Information Area */
        .info-container {
            padding: 0 10pt;
        }

        .name {
            font-size: 8.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
            word-wrap: break-word;
            min-height: 10pt;
        }

        .role-badge {
            display: inline-block;
            background-color: #0F766E;
            color: white;
            font-size: 5.5pt;
            font-weight: bold;
            padding: 2.5pt 6pt;
            border-radius: 10pt;
            margin-top: 4pt;
            margin-bottom: 5pt;
            text-transform: uppercase;
        }

        .details {
            font-size: 5.5pt;
            color: #334155;
            line-height: 1.4;
        }
        
        .details strong {
            color: #0f172a;
        }

        /* QR Code Area */
        .qr-container {
            position: absolute;
            bottom: 12pt;
            width: 100%;
            text-align: center;
        }

        .qr-img {
            width: 32pt;
            height: 32pt;
            border: 1pt solid #cbd5e1;
            padding: 2pt;
            background: white;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Pure CSS Background Elements -->
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        <div class="header-bg"></div>

        <div class="card-content">
            <!-- Header -->
            <div class="header">
                <h1 class="header-title">ID Card Magang</h1>
                <div class="header-subtitle">{{ Str::limit($app->position->instansi->nama_dinas, 35) }}</div>
            </div>

            <!-- Photo -->
            <div class="photo-container">
                @if ($app->user->photo && file_exists(public_path('storage/' . $app->user->photo)))
                    <img src="{{ public_path('storage/' . $app->user->photo) }}" class="photo" alt="Photo">
                @else
                    <div class="no-photo">NO PHOTO</div>
                @endif
            </div>

            <!-- Info -->
            <div class="info-container">
                <h2 class="name">{{ $app->user->name }}</h2>
                <div class="role-badge">PESERTA MAGANG</div>
                
                <div class="details">
                    {{ $app->user->asal_instansi }}<br>
                    NIM/NIK: <strong>{{ $app->user->nik ?? '-' }}</strong><br>
                    Periode: <strong>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('M') }} - {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('M Y') }}</strong>
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-container">
                <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(70)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }}" class="qr-img">
            </div>
        </div>
    </div>
</body>
</html>
