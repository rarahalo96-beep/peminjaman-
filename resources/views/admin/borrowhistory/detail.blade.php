@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.borrowhistory') }}" class="text-blue-500 hover:text-blue-700 mr-2">← Kembali</a>
        <h2 class="text-3xl font-bold text-gray-800">Detail Riwayat Peminjaman</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informasi Buku -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Buku</h3>
            
            @if($transaction->book->image)
                <div class="mb-4">
                    <img src="{{ asset('images/books/' . $transaction->book->image) }}" alt="{{ $transaction->book->title }}" class="w-full h-64 object-cover rounded-lg">
                </div>
            @endif

            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Judul Buku</p>
                    <p class="text-gray-800 font-semibold">{{ $transaction->book->title }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Penulis</p>
                    <p class="text-gray-800">{{ $transaction->book->author }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Penerbit</p>
                    <p class="text-gray-800">{{ $transaction->book->publisher }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tahun Terbit</p>
                    <p class="text-gray-800">{{ $transaction->book->year }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Peminjaman -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Peminjaman</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Nama Anggota</p>
                    <p class="text-gray-800 font-semibold">{{ $transaction->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-gray-800">{{ $transaction->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tanggal Peminjaman</p>
                    <p class="text-gray-800 font-semibold">{{ $transaction->borrow_date->format('d-m-Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tanggal Pengembalian</p>
                    @if($transaction->return_date)
                        <p class="text-gray-800 font-semibold">{{ $transaction->return_date->format('d-m-Y H:i') }}</p>
                    @else
                        <p class="text-red-600 font-semibold">Belum dikembalikan</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Durasi Peminjaman</p>
                    <p class="text-gray-800 font-semibold">
                        @if($transaction->return_date)
                            {{ $transaction->return_date->diffInDays($transaction->borrow_date) }} hari
                        @else
                            {{ now()->diffInDays($transaction->borrow_date) }} hari
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <div class="mt-1">
                        @if($transaction->status === 'borrowed')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm font-semibold inline-block">Sedang Dipinjam</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold inline-block">Sudah Dikembalikan</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.borrowhistory') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition inline-block">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
