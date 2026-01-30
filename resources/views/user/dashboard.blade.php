@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Dashboard Siswa</h2>

<!-- Summary cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <p class="text-xs text-gray-500 uppercase font-semibold">Sedang Dipinjam</p>
        <p class="text-2xl font-bold text-blue-600">{{ $borrowedBooks->count() }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <p class="text-xs text-gray-500 uppercase font-semibold">Rata-rata Lama</p>
        <p class="text-2xl font-bold text-purple-600">{{ $borrowedBooks->count() > 0 ? round($borrowedBooks->sum(fn($t) => $t->borrow_date->diffInDays(now())) / $borrowedBooks->count()) : 0 }} <span class="text-xs text-gray-500">hari</span></p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <p class="text-xs text-gray-500 uppercase font-semibold">Aksi Cepat</p>
        <div class="mt-2 flex flex-col gap-2">
            <a href="{{ route('user.return') }}" class="inline-block text-sm bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-2 rounded">Kelola Pengembalian</a>
            <a href="{{ route('user.history') }}" class="inline-block text-sm bg-blue-50 text-blue-700 px-3 py-2 rounded border border-blue-100">Riwayat Peminjaman</a>
            <a href="{{ route('user.review') }}" class="inline-block text-sm bg-yellow-50 text-yellow-700 px-3 py-2 rounded border border-yellow-100">Review & Rating</a>
        </div>
    </div>
</div>

<!-- Borrowed books list (grid with square cards) -->
<h3 class="text-lg font-semibold mb-3">Buku yang Dipinjam</h3>
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
            <h4 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2">{{ $transaction->book->title }}</h4>
            <div class="space-y-1 mb-2 text-xs text-gray-600 flex-1">
                <p><strong class="text-gray-700">Penulis:</strong> {{ $transaction->book->author }}</p>
                <p><strong class="text-gray-700">Dipinjam:</strong> {{ $transaction->borrow_date->format('d M Y') }}</p>
                <p><strong class="text-gray-700">Durasi:</strong> {{ (int) $transaction->borrow_date->diffInDays(now()) }} hari</p>
            </div>
            <a href="{{ route('user.return') }}" class="bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600 transition w-full text-center font-semibold">
                Kembalikan
            </a>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
    <p class="text-gray-600">Anda belum meminjam buku.</p>
    <a href="{{ route('user.borrow') }}" class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded">Pinjam Buku</a>
</div>
@endif

@endsection