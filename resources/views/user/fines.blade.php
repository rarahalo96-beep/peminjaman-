@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-8 text-gray-800">💰 Tracker Denda</h2>

<!-- Summary Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Denda -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Total Denda</p>
                <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-2">semua denda</p>
            </div>
            <div class="text-5xl">💸</div>
        </div>
    </div>

    <!-- Denda Belum Dibayar -->
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Belum Dibayar</p>
                <p class="text-3xl font-bold text-orange-600">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $unpaidCount }} denda</p>
            </div>
            <div class="text-5xl">⚠️</div>
        </div>
    </div>

    <!-- Denda Sudah Dibayar -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Sudah Dibayar</p>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalAmount - $unpaidAmount, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $paidCount }} denda</p>
            </div>
            <div class="text-5xl">✅</div>
        </div>
    </div>

    <!-- Total Denda Count -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Total Denda</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalFines }}</p>
                <p class="text-xs text-gray-500 mt-2">denda tercatat</p>
            </div>
            <div class="text-5xl">📋</div>
        </div>
    </div>
</div>

<!-- Status Bar -->
@if($unpaidAmount > 0)
<div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-8">
    <div class="flex items-center gap-3">
        <div class="text-3xl">🚨</div>
        <div>
            <p class="font-bold text-red-700">Ada Denda yang Belum Dibayar!</p>
            <p class="text-sm text-red-600">Anda memiliki denda sebesar <span class="font-bold">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</span> yang belum dibayar. Segera lunasi untuk menghindari masalah lebih lanjut.</p>
        </div>
    </div>
</div>
@else
<div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-8">
    <div class="flex items-center gap-3">
        <div class="text-3xl">🎉</div>
        <div>
            <p class="font-bold text-green-700">Tidak Ada Denda yang Tertunggak!</p>
            <p class="text-sm text-green-600">Selamat! Semua denda Anda telah terbayar dengan baik.</p>
        </div>
    </div>
</div>
@endif

<!-- Daftar Denda -->
@if($fines->count() > 0)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h3 class="text-xl font-bold text-gray-800">📜 Detail Denda</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Buku / Alasan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah Denda</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fines as $fine)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $fine->created_at->format('d M Y') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            @if($fine->transaction && $fine->transaction->book)
                            <p class="font-semibold text-gray-800">{{ $fine->transaction->book->title }}</p>
                            @endif
                            <p class="text-sm text-gray-600">{{ $fine->reason ?? 'Keterlambatan pengembalian' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-lg text-red-600">Rp {{ number_format($fine->amount, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($fine->status === 'unpaid')
                        <span class="inline-block px-4 py-2 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                            ❌ Belum Dibayar
                        </span>
                        @else
                        <span class="inline-block px-4 py-2 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                            ✅ Sudah Dibayar
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($fine->paid_date)
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($fine->paid_date)->format('d M Y') }}</span>
                        @else
                        <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Informasi Denda -->
<div class="mt-8">
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">📌 Informasi Denda</h3>
        
        <div class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-600 mb-1">Jenis Denda</p>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Keterlambatan pengembalian buku</li>
                    <li>• Buku rusak atau hilang</li>
                    <li>• Pelanggaran peraturan perpustakaan</li>
                </ul>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <p class="text-sm text-gray-600 mb-1">Denda Per Hari</p>
                <p class="text-lg font-bold text-purple-600">Rp 5.000 - Rp 10.000 / hari</p>
                <p class="text-xs text-gray-500 mt-1">Tergantung jenis buku dan kondisi</p>
            </div>
        </div>
    </div>
</div>

@else
<div class="bg-green-50 border border-green-200 rounded-lg p-8 text-center">
    <p class="text-3xl mb-3">🎊</p>
    <p class="text-gray-600 text-lg font-semibold">Tidak Ada Denda</p>
    <p class="text-gray-500 text-sm mt-2">Selamat! Anda tidak memiliki denda. Teruskan kebiasaan baik dalam peminjaman buku.</p>
    <a href="{{ route('user.dashboard') }}" class="mt-4 inline-block bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
        ← Kembali ke Dashboard
    </a>
</div>
@endif
@endsection
