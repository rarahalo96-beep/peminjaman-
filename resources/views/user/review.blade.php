@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">⭐ Review & Rating Buku</h2>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 animate-pulse">
    ✓ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-pulse">
    ✗ {{ session('error') }}
</div>
@endif

@if($books->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($books as $book)
    @php
        $userReview = $book->reviews->first();
    @endphp
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition duration-300">
        @if($book->image)
        <img src="{{ asset('images/books/' . $book->image) }}" alt="{{ $book->title }}" class="w-full aspect-square object-cover rounded-lg mb-4">
        @else
        <div class="w-full aspect-square bg-gradient-to-br from-purple-300 to-blue-300 rounded-lg mb-4 flex items-center justify-center">
            <span class="text-white text-5xl">📖</span>
        </div>
        @endif

        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $book->title }}</h3>
        <p class="text-sm text-gray-600 mb-4"><strong>Penulis:</strong> {{ $book->author }}</p>

        <form method="POST" action="{{ route('user.review.store', $book) }}" class="space-y-4">
            @csrf

            <!-- Rating -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rating ⭐</label>
                <div class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer group">
                        <input type="radio" name="rating" value="{{ $i }}" 
                            {{ $userReview && $userReview->rating == $i ? 'checked' : '' }}
                            class="hidden peer">
                        <span class="text-3xl transition duration-300 hover:scale-125"
                            id="star-{{ $book->id }}-{{ $i }}">
                            @if($userReview && $userReview->rating >= $i)
                            ⭐
                            @else
                            ☆
                            @endif
                        </span>
                    </label>
                    @endfor
                </div>
                @error('rating')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Comment -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Komentar (Opsional)</label>
                <textarea name="comment" maxlength="500" rows="3" 
                    placeholder="Bagikan pengalaman Anda tentang buku ini..."
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none">{{ $userReview?->comment ?? '' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                @error('comment')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-3 rounded-lg hover:shadow-lg transition duration-300 font-semibold">
                {{ $userReview ? '✏️ Update Review' : '➕ Tambah Review' }}
            </button>
        </form>

        <!-- Existing Review Info -->
        @if($userReview)
        <div class="mt-4 pt-4 border-t">
            <p class="text-xs text-gray-500 mb-1">Rating Anda: 
                <span class="font-bold text-yellow-500">
                    {{ str_repeat('⭐', $userReview->rating) }}
                    ({{ $userReview->rating }}/5)
                </span>
            </p>
            <p class="text-xs text-gray-400">Diupdate: {{ $userReview->updated_at->format('d M Y H:i') }}</p>
        </div>
        @endif
    </div>
    @endforeach
</div>

<!-- Stats -->
<div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Total Buku</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $books->count() }}</p>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Buku yang Direview</p>
        <p class="text-3xl font-bold text-blue-600">{{ $books->filter(fn($b) => $b->reviews->count() > 0)->count() }}</p>
    </div>
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Rating Rata-rata</p>
        <p class="text-3xl font-bold text-purple-600">
            @php
                $avgRating = $books->filter(fn($b) => $b->reviews->count() > 0)->avg(fn($b) => $b->reviews->first()->rating);
            @endphp
            {{ $avgRating > 0 ? number_format($avgRating, 1) : '-' }}
        </p>
    </div>
</div>

@else
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
    <p class="text-gray-600 text-lg">📭 Tidak ada buku yang tersedia untuk direview</p>
    <a href="{{ route('user.borrow') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
        📖 Pinjam Buku Dulu
    </a>
</div>
@endif
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    // For each review form, update star display on change
    document.querySelectorAll('form').forEach(function(form){
        const stars = Array.from(form.querySelectorAll('span[id^="star-"]'));
        const radios = Array.from(form.querySelectorAll('input[type="radio"][name="rating"]'));
        if(radios.length === 0 || stars.length === 0) return;

        function updateStars(value){
            stars.forEach(function(star, idx){
                star.textContent = (idx < value) ? '⭐' : '☆';
            });
        }

        radios.forEach(function(radio, idx){
            radio.addEventListener('change', function(){
                const val = parseInt(this.value, 10) || 0;
                updateStars(val);
            });
            // ensure clicking star (span) checks radio when label click doesn't work in some browsers
            const correspondingStar = stars[idx];
            if(correspondingStar){
                correspondingStar.addEventListener('click', function(){
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
        });
    });
});
</script>

