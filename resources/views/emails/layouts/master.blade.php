<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #0f766e; /* Teal-700 */
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header.danger {
            background-color: #be123c; /* Rose-700 */
        }
        .header.warning {
            background-color: #b45309; /* Amber-700 */
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #374151; /* Gray-700 */
        }
        .content p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #0d9488; /* Teal-600 */
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            background-color: #f9fafb; /* Gray-50 */
            padding: 20px;
            text-align: center;
            color: #6b7280; /* Gray-500 */
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        .details-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #4b5563;
        }
        .alert-box {
            background-color: #fffbeb; 
            border: 1px solid #fef3c7; 
            padding: 20px; 
            border-radius: 8px; 
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .alert-box h3 {
            margin-top: 0; 
            color: #b45309; 
            font-size: 16px;
        }
        .alert-box p {
            margin-bottom: 0; 
            color: #78350f; 
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header @yield('header_class')">
            <h1>@yield('header_title')</h1>
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ env('APP_NAME', 'Portal Magang Banjarmasin') }}. All rights reserved.</p>
            <p>Pemerintah Kota Banjarmasin</p>
        </div>
    </div>
</body>
</html>
