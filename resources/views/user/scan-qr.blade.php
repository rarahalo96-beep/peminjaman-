@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Scan QR Code</h2>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <video id="qr-video" width="100%" height="400" class="border-2 border-gray-300 rounded-lg"></video>
        </div>

        <div id="result-section" class="hidden mt-6 p-4 bg-green-100 border border-green-400 rounded-lg">
            <h3 class="text-lg font-semibold text-green-700 mb-2">✓ Peminjaman Berhasil!</h3>
            <div id="result-content">
                <p id="book-title" class="font-semibold text-lg"></p>
                <p id="book-author" class="text-gray-700"></p>
            </div>
            <button onclick="resetScanner()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Scan Lagi</button>
        </div>

        <div id="error-section" class="hidden mt-6 p-4 bg-red-100 border border-red-400 rounded-lg">
            <h3 class="text-lg font-semibold text-red-700 mb-2">✗ Error</h3>
            <p id="error-message" class="text-red-700"></p>
            <button onclick="resetScanner()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Scan Lagi</button>
        </div>

        <p class="text-gray-600 text-sm mt-4 text-center">
            Arahkan kamera ke QR code pada buku untuk meminjamnya secara otomatis
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    const video = document.getElementById('qr-video');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    let isScanning = true;
    let lastScannedCode = null;

    // Request camera access
    navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: 'environment'
        }
    }).then(stream => {
        video.srcObject = stream;
        video.setAttribute('playsinline', true);
        video.play();
        requestAnimationFrame(tick);
    }).catch(err => {
        console.error('Error accessing camera:', err);
        showError('Tidak dapat mengakses kamera. Silakan periksa izin akses kamera Anda.');
    });

    function tick() {
        if (!isScanning) {
            requestAnimationFrame(tick);
            return;
        }

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 2,
            });

            if (code) {
                const qrValue = code.data;
                
                // Prevent duplicate scans
                if (lastScannedCode !== qrValue) {
                    lastScannedCode = qrValue;
                    isScanning = false;
                    processQrCode(qrValue);
                }
            }
        }

        requestAnimationFrame(tick);
    }

    function processQrCode(qrCode) {
        fetch('{{ route("user.scan-qr.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qr_code: qrCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.book);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memproses QR code');
            isScanning = true;
        });
    }

    function showSuccess(book) {
        document.getElementById('book-title').textContent = book.title;
        document.getElementById('book-author').textContent = 'Penulis: ' + book.author;
        document.getElementById('error-section').classList.add('hidden');
        document.getElementById('result-section').classList.remove('hidden');
    }

    function showError(message) {
        document.getElementById('error-message').textContent = message;
        document.getElementById('result-section').classList.add('hidden');
        document.getElementById('error-section').classList.remove('hidden');
    }

    function resetScanner() {
        lastScannedCode = null;
        isScanning = true;
        document.getElementById('result-section').classList.add('hidden');
        document.getElementById('error-section').classList.add('hidden');
    }

    // Stop video on page unload
    window.addEventListener('beforeunload', () => {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endsection
