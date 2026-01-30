@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Riwayat Peminjaman</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <input type="text" name="q" placeholder="Cari nama anggota atau judul buku..." value="{{ $query }}" class="px-4 py-2 border rounded-lg">
                <select name="status" class="px-4 py-2 border rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="borrowed" {{ $status === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="returned" {{ $status === 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cari</button>
                    @if($query || $status)
                        <a href="{{ route('admin.borrowhistory') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        @if($borrowHistories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Nama Anggota</th>
                        <th class="px-4 py-2 text-left">Judul Buku</th>
                        <th class="px-4 py-2 text-left">Tanggal Pinjam</th>
                        <th class="px-4 py-2 text-left">Tanggal Kembali</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Durasi</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowHistories as $key => $history)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $key + 1 }}</td>
                        <td class="px-4 py-2">{{ $history->user->name }}</td>
                        <td class="px-4 py-2">{{ $history->book->title }}</td>
                        <td class="px-4 py-2">{{ $history->borrow_date->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">{{ $history->return_date ? $history->return_date->format('d-m-Y') : '-' }}</td>
                        <td class="px-4 py-2">
                            @if($history->status === 'borrowed')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-semibold">Sedang Dipinjam</span>
                            @else
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-semibold">Sudah Dikembalikan</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if($history->return_date)
                                {{ (int) $history->return_date->diffInDays($history->borrow_date) }} hari
                            @else
                                {{ (int) now()->diffInDays($history->borrow_date) }} hari
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('admin.borrowhistory.detail', $history->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition inline-block">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-500 text-center py-8">Tidak ada riwayat peminjaman.</p>
        @endif
    </div>
</div>
@endsection
