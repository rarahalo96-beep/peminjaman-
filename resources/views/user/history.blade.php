@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">📚 Riwayat Peminjaman</h2>

@if($transactions->count() > 0)
<div class="space-y-4">
    @foreach($transactions as $transaction)
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition duration-300">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $transaction->book->title }}</h3>
                <p class="text-sm text-gray-600">Penulis: {{ $transaction->book->author }}</p>
            </div>
            
            <div class="space-y-2">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Tanggal Pinjam</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $transaction->borrow_date->format('d M Y') }}</p>
                </div>
            </div>

            <div class="space-y-2">
                @if($transaction->status === 'borrowed')
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold text-sm">
                        📖 Sedang Dipinjam
                    </span>
                @elseif($transaction->status === 'returned')
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Tanggal Kembali</p>
                        <p class="text-lg font-semibold text-green-600">{{ $transaction->return_date->format('d M Y') }}</p>
                    </div>
                    <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold text-sm">
                        ✓ Sudah Dikembalikan
                    </span>
                @endif
            </div>
        </div>

        <div class="border-t pt-4 mt-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Durasi Peminjaman</p>
                    @if($transaction->status === 'borrowed')
                        <p class="font-semibold text-gray-800">
                            {{ (int) now()->diffInDays($transaction->borrow_date) }} hari
                        </p>
                    @else
                        <p class="font-semibold text-gray-800">
                            {{ (int) $transaction->return_date->diffInDays($transaction->borrow_date) }} hari
                        </p>
                    @endif
                </div>

                <div>
                    <p class="text-gray-600">Status</p>
                    <p class="font-semibold text-gray-800">
                        @if($transaction->status === 'borrowed')
                            Aktif
                        @else
                            Selesai
                        @endif
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-600">Catatan Transaksi</p>
                    <p class="font-semibold text-gray-800">ID: {{ $transaction->id }}</p>
                </div>
            </div>
        </div>

        @if($transaction->status === 'borrowed')
        <div class="mt-4 pt-4 border-t flex gap-2">
            <form method="POST" action="{{ route('user.return', $transaction) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition duration-300 font-semibold">
                    ↩️ Kembalikan Buku
                </button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
    <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
        <p class="text-gray-600 text-sm">Total Peminjaman</p>
        <p class="text-3xl font-bold text-blue-600">{{ $transactions->count() }}</p>
    </div>
    
    <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
        <p class="text-gray-600 text-sm">Sedang Dipinjam</p>
        <p class="text-3xl font-bold text-blue-600">{{ $transactions->where('status', 'borrowed')->count() }}</p>
    </div>
    
    <div class="bg-green-50 p-6 rounded-xl border border-green-200">
        <p class="text-gray-600 text-sm">Sudah Dikembalikan</p>
        <p class="text-3xl font-bold text-green-600">{{ $transactions->where('status', 'returned')->count() }}</p>
    </div>
</div>
@else
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
    <p class="text-gray-600 text-lg">📭 Belum ada riwayat peminjaman</p>
    <p class="text-gray-500 text-sm mt-2">Mulai pinjam buku sekarang!</p>
    <a href="{{ route('user.borrow') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
        📖 Pinjam Buku
    </a>
</div>
@endif
@endsection
