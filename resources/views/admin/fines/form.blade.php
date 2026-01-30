@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.fines') }}" class="text-blue-500 hover:text-blue-700 mr-2">← Kembali</a>
        <h2 class="text-3xl font-bold text-gray-800">{{ isset($fine) ? 'Edit Denda' : 'Tambah Denda' }}</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form action="{{ isset($fine) ? route('admin.fines.update', $fine->id) : route('admin.fines.store') }}" method="POST">
            @csrf
            @if(isset($fine))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nama Anggota</label>
                <select name="user_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('user_id') border-red-500 @enderror" required>
                    <option value="">Pilih Anggota</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (isset($fine) && $fine->user_id == $user->id) || old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Transaksi (Opsional)</label>
                <select name="transaction_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('transaction_id') border-red-500 @enderror">
                    <option value="">Pilih Transaksi</option>
                    @foreach($transactions as $transaction)
                        <option value="{{ $transaction->id }}" {{ (isset($fine) && $fine->transaction_id == $transaction->id) || old('transaction_id') == $transaction->id ? 'selected' : '' }}>
                            {{ $transaction->user->name }} - {{ $transaction->book->title }}
                        </option>
                    @endforeach
                </select>
                @error('transaction_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Jumlah Denda (Rp)</label>
                <input type="number" name="amount" step="0.01" placeholder="Contoh: 50000" value="{{ isset($fine) ? $fine->amount : old('amount') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('amount') border-red-500 @enderror" required>
                @error('amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Alasan Denda</label>
                <textarea name="reason" placeholder="Contoh: Keterlambatan pengembalian buku" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('reason') border-red-500 @enderror" rows="4" required>{{ isset($fine) ? $fine->reason : old('reason') }}</textarea>
                @error('reason')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if(isset($fine))
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                    <option value="unpaid" {{ $fine->status === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ $fine->status === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6" id="paid_date_container" style="display: {{ $fine->status === 'paid' ? 'block' : 'none' }};">
                <label class="block text-gray-700 font-semibold mb-2">Tanggal Pembayaran</label>
                <input type="date" name="paid_date" value="{{ isset($fine) && $fine->paid_date ? $fine->paid_date : old('paid_date') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('paid_date') border-red-500 @enderror">
                @error('paid_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">{{ isset($fine) ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('admin.fines') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    const statusSelect = document.querySelector('select[name="status"]');
    const paidDateContainer = document.getElementById('paid_date_container');

    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'paid') {
                paidDateContainer.style.display = 'block';
                document.querySelector('input[name="paid_date"]').required = true;
            } else {
                paidDateContainer.style.display = 'none';
                document.querySelector('input[name="paid_date"]').required = false;
            }
        });
    }
</script>
@endsection
