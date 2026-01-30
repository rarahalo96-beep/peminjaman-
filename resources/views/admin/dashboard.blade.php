@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6 text-gray-800">Admin Dashboard</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition duration-300">
        <h3 class="text-lg font-semibold mb-2">Total Buku</h3>
        <p class="text-3xl font-bold">{{ $totalBooks }}</p>
    </div>
    <div class="bg-gradient-to-r from-green-500 to-blue-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition duration-300">
        <h3 class="text-lg font-semibold mb-2">Total Anggota</h3>
        <p class="text-3xl font-bold">{{ $totalUsers }}</p>
    </div>
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition duration-300">
        <h3 class="text-lg font-semibold mb-2">Total Transaksi</h3>
        <p class="text-3xl font-bold">{{ $totalTransactions }}</p>
    </div>
    <div class="bg-gradient-to-r from-red-500 to-pink-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition duration-300">
        <h3 class="text-lg font-semibold mb-2">Buku Dipinjam</h3>
        <p class="text-3xl font-bold">{{ $borrowedBooks }}</p>
    </div>
</div>
@endsection