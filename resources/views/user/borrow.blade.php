@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">📚 Peminjaman Buku</h2>

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

@if($books->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($books as $book)
    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 border border-gray-200 overflow-hidden flex flex-col">
        @if($book->image)
        <img src="{{ asset('images/books/' . $book->image) }}" alt="{{ $book->title }}" class="w-full aspect-square object-cover">
        @else
        <div class="w-full aspect-square bg-gradient-to-br from-purple-300 to-blue-300 flex items-center justify-center">
            <span class="text-white text-5xl">📖</span>
        </div>
        @endif
        
        <div class="p-3 flex flex-col flex-1">
            <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2">{{ $book->title }}</h3>
            
            <div class="space-y-1 mb-3 text-xs text-gray-600 flex-1">
                <p><strong class="text-gray-700">Penulis:</strong> {{ $book->author }}</p>
                <p><strong class="text-gray-700">Penerbit:</strong> {{ $book->publisher }}</p>
                <p><strong class="text-gray-700">Tahun:</strong> {{ $book->year }}</p>
                <p class="mt-2"><strong class="text-gray-700">Stok:</strong> <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">{{ $book->stock }}</span></p>
            </div>

            <form method="POST" action="{{ route('user.borrow.store', $book) }}" class="">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600 transition w-full font-semibold">
                    Pinjam
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
    <p class="text-gray-600 text-lg">📭 Tidak ada buku yang tersedia untuk dipinjam saat ini</p>
    <a href="{{ route('user.dashboard') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
        Kembali ke Dashboard
    </a>
</div>
@endif
@endsection
