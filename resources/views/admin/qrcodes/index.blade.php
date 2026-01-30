@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Kelola QR Code</h2>
        <a href="{{ route('admin.qrcodes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Tambah QR Code</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="q" placeholder="Cari QR Code atau buku..." value="{{ $query }}" class="flex-1 px-4 py-2 border rounded-lg">
                <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cari</button>
                @if($query)
                    <a href="{{ route('admin.qrcodes') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">Reset</a>
                @endif
            </div>
        </form>

        @if($qrCodes->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Judul</th>
                        <th class="px-4 py-2 text-left">Kode</th>
                        <th class="px-4 py-2 text-left">Buku</th>
                        <th class="px-4 py-2 text-left">Tipe</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($qrCodes as $key => $qrCode)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $key + 1 }}</td>
                        <td class="px-4 py-2">{{ $qrCode->title }}</td>
                        <td class="px-4 py-2 font-mono text-sm">{{ $qrCode->code }}</td>
                        <td class="px-4 py-2">{{ $qrCode->book ? $qrCode->book->title : '-' }}</td>
                        <td class="px-4 py-2">
                            @if($qrCode->type === 'book')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">Buku</span>
                            @else
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">Transaksi</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('admin.qrcodes.generate', $qrCode->id) }}" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition inline-block">Generate</a>
                            <a href="{{ route('admin.qrcodes.edit', $qrCode->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition inline-block">Edit</a>
                            <form action="{{ route('admin.qrcodes.destroy', $qrCode->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus QR Code ini?');">
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
            <p class="text-gray-500 text-center py-8">Tidak ada data QR Code.</p>
        @endif
    </div>
</div>
@endsection
