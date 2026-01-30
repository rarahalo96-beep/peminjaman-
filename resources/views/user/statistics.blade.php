@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-8 text-gray-800">📊 Statistik Peminjaman Anda</h2>

<!-- Main Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Peminjaman -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Total Peminjaman</p>
                <p class="text-4xl font-bold text-blue-600">{{ $totalBorrowed }}</p>
                <p class="text-xs text-gray-500 mt-2">sepanjang waktu</p>
            </div>
            <div class="text-5xl">📚</div>
        </div>
    </div>

    <!-- Sedang Dipinjam -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Sedang Dipinjam</p>
                <p class="text-4xl font-bold text-purple-600">{{ $currentlyBorrowing }}</p>
                <p class="text-xs text-gray-500 mt-2">aktif sekarang</p>
            </div>
            <div class="text-5xl">📖</div>
        </div>
    </div>

    <!-- Sudah Dikembalikan -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Sudah Dikembalikan</p>
                <p class="text-4xl font-bold text-green-600">{{ $totalReturned }}</p>
                <p class="text-xs text-gray-500 mt-2">total</p>
            </div>
            <div class="text-5xl">✅</div>
        </div>
    </div>

    <!-- Durasi Rata-rata -->
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-300 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-1">Durasi Rata-rata</p>
                <p class="text-4xl font-bold text-orange-600">{{ round($avgBorrowDuration) }}</p>
                <p class="text-xs text-gray-500 mt-2">hari</p>
            </div>
            <div class="text-5xl">⏱️</div>
        </div>
    </div>
</div>

<!-- Review Statistics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Review & Rating Stats -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-6">⭐ Review & Rating</h3>
        
        <div class="space-y-4">
            <!-- Rating Average -->
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <p class="text-sm text-gray-600 mb-2">Rating Rata-rata</p>
                <div class="flex items-center gap-3">
                    <p class="text-4xl font-bold text-yellow-600">{{ number_format($avgRating, 1) }}</p>
                    <div class="text-2xl">{{ str_repeat('⭐', round($avgRating)) }}</div>
                </div>
            </div>

            <!-- Total Reviews -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Review</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalReviews }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 text-center">
                    <p class="text-sm text-gray-600 mb-1">Buku Direview</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ count(array_filter($reviewStats)) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-6">📈 Distribusi Rating</h3>
        
        <div class="space-y-3">
            @for($i = 5; $i >= 1; $i--)
            <div class="flex items-center gap-3">
                <div class="min-w-[60px]">
                    <span class="text-sm font-semibold text-gray-700">{{ str_repeat('⭐', $i) }}</span>
                </div>
                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-yellow-400 h-full rounded-full transition-all duration-300" 
                        style="width: {{ $totalReviews > 0 ? ($reviewStats[$i] / $totalReviews * 100) : 0 }}%"></div>
                </div>
                <div class="min-w-[40px] text-right">
                    <span class="text-sm font-semibold text-gray-700">{{ $reviewStats[$i] }}</span>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

<!-- Recent & Top Rated -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Peminjaman Terakhir -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-6">📚 Peminjaman Terakhir</h3>
        
        @if($recentBorrows->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($recentBorrows as $transaction)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden flex flex-col">
                @if($transaction->book->image)
                <img src="{{ asset('images/books/' . $transaction->book->image) }}" alt="{{ $transaction->book->title }}" class="w-full aspect-square object-cover">
                @else
                <div class="w-full aspect-square bg-gradient-to-br from-purple-300 to-blue-300 flex items-center justify-center">
                    <span class="text-white text-5xl">📖</span>
                </div>
                @endif
                <div class="p-3 flex flex-col flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2">{{ $transaction->book->title }}</h4>
                    <div class="space-y-1 mb-3 text-xs text-gray-600 flex-1">
                        <p><strong class="text-gray-700">Penulis:</strong> {{ $transaction->book->author }}</p>
                        <p><strong class="text-gray-700">Penerbit:</strong> {{ $transaction->book->publisher }}</p>
                        <p><strong class="text-gray-700">Dipinjam:</strong> {{ $transaction->borrow_date->format('d M Y') }}</p>
                        <p><strong class="text-gray-700">Durasi:</strong> {{ (int) $transaction->borrow_date->diffInDays(now()) }} hari</p>
                    </div>
                    <div>
                        @if($transaction->status === 'borrowed')
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Aktif</span>
                        @else
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Kembali</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Belum ada riwayat peminjaman</p>
        @endif
    </div>

    <!-- Buku dengan Rating Tertinggi -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-6">⭐ Buku Rating Tertinggi</h3>
        
        @if($topRatedBooks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($topRatedBooks as $book)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden flex flex-col">
                @if($book->image)
                <img src="{{ asset('images/books/' . $book->image) }}" alt="{{ $book->title }}" class="w-full aspect-square object-cover">
                @else
                <div class="w-full aspect-square bg-gradient-to-br from-purple-300 to-blue-300 flex items-center justify-center">
                    <span class="text-white text-5xl">📖</span>
                </div>
                @endif
                <div class="p-3 flex flex-col flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2">{{ $book->title }}</h4>
                    <div class="space-y-1 mb-3 text-xs text-gray-600 flex-1">
                        <p><strong class="text-gray-700">Penulis:</strong> {{ $book->author }}</p>
                        <p><strong class="text-gray-700">Penerbit:</strong> {{ $book->publisher }}</p>
                        <p><strong class="text-gray-700">Rating:</strong> {{ number_format($book->reviews->first()->rating ?? 0, 1) }}/5</p>
                    </div>
                    <div>
                        <div class="text-yellow-400 text-lg">{{ str_repeat('⭐', $book->reviews->first()->rating ?? 0) }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Belum ada review</p>
        @endif
    </div>
</div>

<!-- Summary Card -->
<div class="mt-8 bg-gradient-to-r from-purple-500 to-blue-500 text-white p-6 rounded-xl shadow-lg">
    <h3 class="text-xl font-bold mb-3">📋 Ringkasan Aktivitas</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <p class="text-purple-100 text-sm">Buku Saat Ini</p>
            <p class="text-3xl font-bold">{{ $currentlyBorrowing }}/{{ $totalBorrowed }}</p>
        </div>
        <div>
            <p class="text-purple-100 text-sm">Tingkat Penyelesaian</p>
            <p class="text-3xl font-bold">{{ $totalBorrowed > 0 ? round(($totalReturned / $totalBorrowed) * 100) : 0 }}%</p>
        </div>
        <div>
            <p class="text-purple-100 text-sm">Review Completion</p>
            <p class="text-3xl font-bold">{{ $totalBorrowed > 0 ? round(($totalReviews / $totalBorrowed) * 100) : 0 }}%</p>
        </div>
    </div>
</div>
@endsection
