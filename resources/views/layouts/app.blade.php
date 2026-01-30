<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-100 to-gray-200">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-purple-600 to-blue-600 text-white shadow-lg">
            <div class="p-4 bg-white bg-opacity-10 rounded-t-lg">
                <h1 class="text-xl font-bold text-white">Perpustakaan Digital</h1>
            </div>
            <nav class="mt-4 px-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">📊 Dashboard</a>
                    <a href="{{ route('admin.books') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">📚 Kelola Buku</a>
                    <a href="{{ route('admin.members') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">👥 Kelola Anggota</a>
                    <a href="{{ route('admin.transactions') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">💳 Transaksi</a>
                    <a href="{{ route('admin.borrowhistory') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">📖 Riwayat Peminjaman</a>
                    <a href="{{ route('admin.fines') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">⚠️ Denda</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">🏠 Dashboard</a>
                    <a href="{{ route('user.statistics') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">📊 Statistik</a>
                    <a href="{{ route('user.fines') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">💰 Tracker Denda</a>
                    <a href="{{ route('user.return') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">↩️ Pengembalian Buku</a>
                    <a href="{{ route('user.history') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">📚 Riwayat Peminjaman</a>
                    <a href="{{ route('user.review') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">⭐ Review & Rating</a>
                    <a href="{{ route('user.search') }}" class="block px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition duration-300">🔍 Cari Buku</a>
                    
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-3 rounded-lg hover:bg-red-500 hover:bg-opacity-70 transition duration-300">🚪 Logout</button>
                </form>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="flex-1 p-6 bg-white bg-opacity-90">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 animate-pulse">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-pulse">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

<!-- Enforce 1:1 aspect ratio for images inside card-like containers -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    try {
        const selectors = [
            'div[class*="rounded"][class*="shadow"] img',
            'div[class*="bg-white"] img',
            '.card img',
            '.rounded img'
        ];
        const imgs = document.querySelectorAll(selectors.join(','));
        imgs.forEach(function(img){
            img.style.display = 'block';
            img.style.width = '100%';
            img.style.aspectRatio = '1 / 1';
            img.style.objectFit = 'cover';
            img.style.maxHeight = 'none';
        });
    } catch (e) {
        // fail silently
        console.error(e);
    }
});
</script>
</body>
</html>
