@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Kelola Anggota</h2>
<a href="{{ route('admin.members.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Anggota</a>

<!-- Filter Form -->
<form method="GET" class="mb-6 bg-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari nama atau email anggota" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300">Cari</button>
            @if(isset($query) && $query)
            <a href="{{ route('admin.members') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300">Reset</a>
            @endif
        </div>
    </div>
</form>

@if(isset($query) && $query)
<h3 class="text-lg font-semibold mb-4 text-gray-700">Hasil Pencarian untuk "{{ $query }}"</h3>
@endif

<div class="bg-white p-4 rounded shadow">
    <table class="w-full table-auto">
        <thead>
            <tr>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td class="px-4 py-2">{{ $member->name }}</td>
                <td class="px-4 py-2">{{ $member->email }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.members.edit', $member) }}" class="text-blue-500">Edit</a>
                    <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection