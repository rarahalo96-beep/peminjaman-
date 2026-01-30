@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Transaksi Peminjaman</h2>
<div class="bg-white p-4 rounded shadow">
    <table class="w-full table-auto">
        <thead>
            <tr>
                <th class="px-4 py-2">Anggota</th>
                <th class="px-4 py-2">Buku</th>
                <th class="px-4 py-2">Tanggal Pinjam</th>
                <th class="px-4 py-2">Tanggal Kembali</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="px-4 py-2">{{ $transaction->user->name }}</td>
                <td class="px-4 py-2">{{ $transaction->book->title }}</td>
                <td class="px-4 py-2">{{ $transaction->borrow_date->format('d-m-Y') }}</td>
                <td class="px-4 py-2">{{ $transaction->return_date ? $transaction->return_date->format('d-m-Y') : '-' }}</td>
                <td class="px-4 py-2">{{ $transaction->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection