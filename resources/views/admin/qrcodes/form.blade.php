@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.qrcodes') }}" class="text-blue-500 hover:text-blue-700 mr-2">← Kembali</a>
        <h2 class="text-3xl font-bold text-gray-800">{{ isset($qrCode) ? 'Edit QR Code' : 'Tambah QR Code' }}</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form action="{{ isset($qrCode) ? route('admin.qrcodes.update', $qrCode->id) : route('admin.qrcodes.store') }}" method="POST">
            @csrf
            @if(isset($qrCode))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Judul QR Code</label>
                <input type="text" name="title" placeholder="Contoh: QR Code Buku Pemrograman" value="{{ isset($qrCode) ? $qrCode->title : old('title') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('title') border-red-500 @enderror" required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Kode QR (Unik)</label>
                <input type="text" name="code" placeholder="Contoh: BOOK_001_QR" value="{{ isset($qrCode) ? $qrCode->code : old('code') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('code') border-red-500 @enderror font-mono" required>
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Pilih Buku (Opsional)</label>
                <select name="book_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('book_id') border-red-500 @enderror">
                    <option value="">Tidak ada buku</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ (isset($qrCode) && $qrCode->book_id == $book->id) || old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }}
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Tipe QR Code</label>
                <select name="type" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('type') border-red-500 @enderror" required>
                    <option value="book" {{ (isset($qrCode) && $qrCode->type === 'book') || old('type') === 'book' ? 'selected' : '' }}>Buku</option>
                    <option value="transaction" {{ (isset($qrCode) && $qrCode->type === 'transaction') || old('type') === 'transaction' ? 'selected' : '' }}>Transaksi</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" placeholder="Masukkan deskripsi untuk QR Code ini..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror" rows="4">{{ isset($qrCode) ? $qrCode->description : old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">{{ isset($qrCode) ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('admin.qrcodes') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
