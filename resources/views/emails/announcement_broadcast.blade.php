<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0f766e, #0d9488);
            padding: 30px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 12px;
            color: #ccfbf1;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .body-content {
            padding: 30px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .badge-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        .type-urgent { background-color: #ffe4e6; color: #e11d48; }
        .type-warning { background-color: #fef3c7; color: #d97706; }
        .type-event { background-color: #d1fae5; color: #059669; }
        .type-info { background-color: #ccfbf1; color: #0f766e; }

        .announcement-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 16px 0;
            line-height: 1.4;
        }
        .announcement-body {
            font-size: 14px;
            color: #475569;
            white-space: pre-line;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .cta-btn {
            display: block;
            text-align: center;
            background-color: #0f766e;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Pemerintah Kota Banjarmasin</p>
            <h1>Portal Magang Resmi</h1>
        </div>

        <div class="body-content">
            <div class="greeting">
                Yth. {{ $recipient->name }},
            </div>

            @php
                $typeClass = match($announcement->type) {
                    'urgent' => 'type-urgent',
                    'warning' => 'type-warning',
                    'event' => 'type-event',
                    default => 'type-info',
                };
            @endphp

            <span class="badge-type {{ $typeClass }}">
                {{ strtoupper($announcement->type) }}
            </span>

            <h2 class="announcement-title">{{ $announcement->title }}</h2>

            <div class="announcement-body">
{!! nl2br(e($announcement->content)) !!}
            </div>

            <a href="{{ route('login') }}" class="cta-btn">
                Buka Portal Magang Kota Banjarmasin &rarr;
            </a>
        </div>

        <div class="footer">
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh Sistem Portal Magang Pemerintah Kota Banjarmasin.</p>
            <p style="margin: 4px 0 0;">Mohon tidak membalas langsung ke email ini.</p>
        </div>
    </div>
</body>
</html>
