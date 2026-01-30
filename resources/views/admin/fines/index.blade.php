@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Kelola Denda</h2>
        <a href="{{ route('admin.fines.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Tambah Denda</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="q" placeholder="Cari nama anggota..." value="{{ $query }}" class="flex-1 px-4 py-2 border rounded-lg">
                <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cari</button>
                @if($query)
                    <a href="{{ route('admin.fines') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">Reset</a>
                @endif
            </div>
        </form>

        @if($fines->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Nama Anggota</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Alasan</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Tanggal Dibuat</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fines as $key => $fine)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $key + 1 }}</td>
                        <td class="px-4 py-2">{{ $fine->user->name }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ $fine->reason }}</td>
                        <td class="px-4 py-2">
                            @if($fine->status === 'paid')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-semibold">Lunas</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $fine->created_at->format('d-m-Y') }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('admin.fines.edit', $fine->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition inline-block">Edit</a>
                            <form action="{{ route('admin.fines.destroy', $fine->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus denda ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-500 text-center py-8">Tidak ada data denda.</p>
        @endif
    </div>
</div>
@endsection
