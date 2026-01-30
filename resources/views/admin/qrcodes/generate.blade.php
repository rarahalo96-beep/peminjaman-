@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.qrcodes') }}" class="text-blue-500 hover:text-blue-700 mr-2">← Kembali</a>
        <h2 class="text-3xl font-bold text-gray-800">Generate QR Code</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <div class="mb-4">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $qrCode->title }}</h3>
            <p class="text-gray-600 mb-4">Kode: <span class="font-mono">{{ $qrCode->code }}</span></p>
            @if($qrCode->description)
                <p class="text-gray-600 mb-4">{{ $qrCode->description }}</p>
            @endif
        </div>

        <div class="mb-6 p-4 bg-gray-100 rounded-lg flex justify-center">
            <svg id="qrcode" style="width: 300px; height: 300px;"></svg>
        </div>

        <div class="flex gap-2">
            <button onclick="downloadQR()" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">Download QR Code</button>
            <button onclick="printQR()" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">Print QR Code</button>
            <a href="{{ route('admin.qrcodes') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Kembali</a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $qrCode->code }}",
        width: 300,
        height: 300,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    function downloadQR() {
        const canvas = document.querySelector('#qrcode canvas');
        if (canvas) {
            const link = document.createElement('a');
            link.href = canvas.toDataURL();
            link.download = '{{ $qrCode->code }}.png';
            link.click();
        }
    }

    function printQR() {
        const printWindow = window.open('', '', 'width=400,height=400');
        const canvas = document.querySelector('#qrcode canvas');
        if (canvas) {
            printWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
            printWindow.document.write('<h2>{{ $qrCode->title }}</h2>');
            printWindow.document.write('<p>Kode: {{ $qrCode->code }}</p>');
            printWindow.document.write('<img src="' + canvas.toDataURL() + '" style="width: 300px; height: 300px;">');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    }
</script>
@endsection
