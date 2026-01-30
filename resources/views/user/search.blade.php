@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-gray-800">Cari Buku</h2>
<form method="GET" class="mb-6 bg-white p-6 rounded-xl shadow-lg">
    <div class="flex">
        <input type="text" name="q" value="{{ $query }}" placeholder="Cari judul, penulis, atau penerbit" class="flex-1 p-3 border border-gray-300 rounded-l-lg focus:ring-purple-500 focus:border-purple-500">
        <button type="submit" class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-6 py-3 rounded-r-lg hover:shadow-lg transition duration-300">Cari</button>
    </div>
</form>
@if($query)
<h3 class="text-lg font-semibold mb-4 text-gray-700">Hasil Pencarian untuk "{{ $query }}"</h3>
@else
<h3 class="text-lg font-semibold mb-4 text-gray-700">Semua Buku Tersedia</h3>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($books as $book)
    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 border border-gray-200">
        @if($book->image)
        <img src="{{ asset('images/books/' . $book->image) }}" alt="Gambar Buku" class="w-full h-48 object-cover rounded-lg mb-4">
        @endif
        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $book->title }}</h3>
        <p class="text-gray-600 mb-1"><strong>Penulis:</strong> {{ $book->author }}</p>
        <p class="text-gray-600 mb-1"><strong>Penerbit:</strong> {{ $book->publisher }}</p>
        <p class="text-gray-600 mb-1"><strong>Tahun:</strong> {{ $book->year }}</p>
        <p class="text-gray-600 mb-4"><strong>Stok:</strong> {{ $book->stock }}</p>
        @if($book->stock > 0)
        <form method="POST" action="{{ route('user.borrow', $book) }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition duration-300 w-full">Pinjam</button>
        </form>
        @else
        <p class="text-red-500 font-semibold">Stok habis</p>
        @endif
    </div>
    @endforeach
</div>
@endsection