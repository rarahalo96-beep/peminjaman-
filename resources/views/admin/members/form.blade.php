@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">{{ isset($member) ? 'Edit Anggota' : 'Tambah Anggota' }}</h2>
<form method="POST" action="{{ isset($member) ? route('admin.members.update', $member) : route('admin.members.store') }}" class="bg-white p-6 rounded shadow">
    @csrf
    @if(isset($member)) @method('PUT') @endif
    <div class="mb-4">
        <label class="block text-gray-700">Nama</label>
        <input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $member->email ?? '') }}" class="w-full p-2 border rounded" required>
    </div>
    @if(!isset($member))
    <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" class="w-full p-2 border rounded" required>
    </div>
    @endif
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ isset($member) ? 'Update' : 'Tambah' }}</button>
    <a href="{{ route('admin.members') }}" class="ml-2 text-gray-500">Batal</a>
</form>
@endsection