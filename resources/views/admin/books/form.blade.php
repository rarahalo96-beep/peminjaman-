@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">{{ isset($book) ? 'Edit Buku' : 'Tambah Buku' }}</h2>
<form method="POST" action="{{ isset($book) ? route('admin.books.update', $book) : route('admin.books.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
    @csrf
    @if(isset($book)) @method('PUT') @endif
    <div class="mb-4">
        <label class="block text-gray-700">Judul</label>
        <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Penulis</label>
        <input type="text" name="author" value="{{ old('author', $book->author ?? '') }}" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Penerbit</label>
        <input type="text" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Tahun</label>
        <input type="number" name="year" value="{{ old('year', $book->year ?? '') }}" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Stok</label>
        <input type="number" name="stock" value="{{ old('stock', $book->stock ?? '') }}" class="w-full p-2 border rounded" required min="0">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Gambar Buku</label>
        <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
        @if(isset($book) && $book->image)
        <img src="{{ asset('images/books/' . $book->image) }}" alt="Gambar Buku" class="mt-2 w-32 h-32 object-cover">
        @endif
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ isset($book) ? 'Update' : 'Tambah' }}</button>
    <a href="{{ route('admin.books') }}" class="ml-2 text-gray-500">Batal</a>
</form>
@endsection