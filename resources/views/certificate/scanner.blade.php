<x-guest-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h2 class="text-2xl font-bold mb-4 text-teal-700">Scan QR Code Verifikasi</h2>
                    <p class="mb-6 text-gray-600">Arahkan kamera ke QR Code pada ID Card atau Sertifikat peserta untuk melakukan verifikasi data magang.</p>
                    
                    <!-- QR Reader Container -->
                    <div id="qr-reader" class="mx-auto border-2 border-dashed border-teal-300 rounded-lg overflow-hidden" style="width:100%; max-width:500px;"></div>
                    <div id="qr-reader-results" class="mt-4 font-bold text-lg text-teal-600 h-8"></div>

                    <div class="mt-6 text-sm text-gray-500 pt-6 border-t border-gray-100">
                        <p>Atau gunakan fitur pencarian manual jika kamera tidak tersedia.</p>
                        <form action="{{ route('certificate.search') }}" method="POST" class="mt-3 flex justify-center gap-2 max-w-sm mx-auto">
                            @csrf
                            <input type="text" name="nomor_sertifikat" placeholder="Masukkan Nomor Sertifikat" class="border rounded-l px-4 py-2 text-sm w-full focus:ring-teal-500 focus:border-teal-500" required>
                            <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-r text-sm font-bold hover:bg-teal-700 transition">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === "complete"
                || document.readyState === "interactive") {
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        docReady(function () {
            var resultContainer = document.getElementById('qr-reader-results');
            var lastResult, countResults = 0;
            
            // Konfigurasi scanner
            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", { 
                    fps: 10, 
                    qrbox: {width: 250, height: 250},
                    aspectRatio: 1.0
                });
            
            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    ++countResults;
                    lastResult = decodedText;
                    
                    // Mainkan suara sukses (opsional)
                    // var audio = new Audio('/audio/beep.mp3');
                    // audio.play();

                    resultContainer.innerHTML = "QR Code Ditemukan! Mengalihkan...";
                    
                    // Stop scanning
                    html5QrcodeScanner.clear().then(_ => {
                        // Redirect to the URL
                        if (decodedText.includes('/verify-certificate/')) {
                            window.location.href = decodedText;
                        } else {
                            resultContainer.innerHTML = "<span class='text-red-500'>QR Code bukan dari sistem Portal Magang!</span>";
                            // Reset lastResult after 3 seconds so they can scan again
                            setTimeout(() => { 
                                lastResult = null; 
                                resultContainer.innerHTML = "";
                                html5QrcodeScanner.render(onScanSuccess);
                            }, 3000);
                        }
                    }).catch(error => {
                        console.error("Failed to clear scanner", error);
                    });
                }
            }
            
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
    <style>
        /* Styling overrides for html5-qrcode */
        #qr-reader__dashboard_section_csr span { margin-right: 5px; }
        #qr-reader__dashboard_section_csr button {
            background-color: #0d9488;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        #qr-reader__dashboard_section_swaplink {
            color: #0d9488;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
    @endpush
</x-guest-layout>
