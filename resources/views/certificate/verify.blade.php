<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - Portal Magang</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden">
        <div class="bg-green-600 p-6 text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Sertifikat Valid</h2>
            <p class="text-green-100 text-sm mt-1">Terdaftar di Database Pemerintah Kota Banjarmasin</p>
        </div>

        <div class="p-6 space-y-4">
            
            <div class="text-center pb-4 border-b border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Pemilik Sertifikat</p>
                <h3 class="text-xl font-bold text-gray-800">{{ $app->user->name }}</h3>
                <p class="text-sm text-gray-600">{{ $app->user->asal_instansi ?? 'Universitas/Sekolah' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs">Nomor Sertifikat</p>
                    <p class="font-mono font-bold text-gray-800">{{ $app->nomor_sertifikat }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Predikat</p>
                    <p class="font-bold text-gray-800">
                        {{ $app->nilai_rata_rata >= 85 ? 'Sangat Baik' : 'Baik' }} 
                        ({{ $app->nilai_rata_rata }})
                    </p>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Lokasi Magang</p>
                <p class="font-bold text-gray-800">{{ $app->position->instansi->nama_dinas }}</p>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg text-xs text-gray-500 text-center mt-4">
                Verifikasi ini dihasilkan secara otomatis oleh sistem Portal Magang.
            </div>
        </div>
    </div>
    <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
</body>
</html>