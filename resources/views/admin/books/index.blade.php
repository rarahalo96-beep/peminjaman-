@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-gray-800">Kelola Buku</h2>
<a href="{{ route('admin.books.create') }}" class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition duration-300 mb-6 inline-block transform hover:scale-105">Tambah Buku</a>

<!-- Filter Form -->
<form method="GET" class="mb-6 bg-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari judul, penulis, atau penerbit" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition duration-300">Cari</button>
            @if(isset($query) && $query)
            <a href="{{ route('admin.books') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300">Reset</a>
            @endif
        </div>
    </div>
</form>

@if(isset($query) && $query)
<h3 class="text-lg font-semibold mb-4 text-gray-700">Hasil Pencarian untuk "{{ $query }}"</h3>
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
        <div class="flex space-x-2">
            <a href="{{ route('admin.books.edit', $book) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300">Edit</a>
            <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Hapus</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection