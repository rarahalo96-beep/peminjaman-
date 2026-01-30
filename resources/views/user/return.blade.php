@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">↩️ Pengembalian Buku</h2>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 animate-pulse">
    ✓ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-pulse">
    ✗ {{ session('error') }}
</div>
@endif

@if($borrowedBooks->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($borrowedBooks as $transaction)
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden flex flex-col">
        @if($transaction->book->image)
        <img src="{{ asset('images/books/' . $transaction->book->image) }}" alt="{{ $transaction->book->title }}" class="w-full aspect-square object-cover">
        @else
        <div class="w-full aspect-square bg-gradient-to-br from-purple-300 to-blue-300 flex items-center justify-center">
            <span class="text-white text-5xl">📖</span>
        </div>
        @endif
        <div class="p-3 flex flex-col flex-1">
            <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2">{{ $transaction->book->title }}</h3>
            <div class="space-y-1 mb-3 text-xs text-gray-600 flex-1">
                <p><strong class="text-gray-700">Penulis:</strong> {{ $transaction->book->author }}</p>
                <p><strong class="text-gray-700">Penerbit:</strong> {{ $transaction->book->publisher }}</p>
                <p><strong class="text-gray-700">Dipinjam:</strong> {{ $transaction->borrow_date->format('d M Y') }}</p>
                <p><strong class="text-gray-700">Durasi:</strong> {{ (int) $transaction->borrow_date->diffInDays(now()) }} hari</p>
            </div>
            <form method="POST" action="{{ route('user.return.store', $transaction) }}" class="">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600 transition w-full font-semibold">
                    Kembalikan
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<!-- Summary -->
<div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-xl p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Ringkasan Peminjaman</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg border border-blue-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase font-bold mb-2">Total Buku Dipinjam</p>
            <p class="text-3xl font-bold text-blue-600">{{ $borrowedBooks->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-purple-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase font-bold mb-2">Durasi Rata-rata</p>
            <p class="text-3xl font-bold text-purple-600">
                {{ $borrowedBooks->count() > 0 ? round($borrowedBooks->sum(fn($t) => $t->borrow_date->diffInDays(now())) / $borrowedBooks->count()) : 0 }}
            </p>
            <p class="text-xs text-gray-500 mt-1">hari</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-green-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase font-bold mb-2">Peminjaman Terbaru</p>
            <p class="text-base font-bold text-green-600">
                {{ $borrowedBooks->first() ? $borrowedBooks->first()->borrow_date->format('d M Y') : '-' }}
            </p>
        </div>
    </div>
</div>

@else
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
    <p class="text-gray-600 text-lg">📭 Tidak ada buku yang perlu dikembalikan</p>
    <p class="text-gray-500 text-sm mt-2">Anda tidak memiliki buku yang sedang dipinjam</p>
    <a href="{{ route('user.borrow') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
        📖 Pinjam Buku
    </a>
</div>
@endif
@endsection
