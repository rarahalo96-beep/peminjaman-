<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500">
        <div class="max-w-md w-full bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
                <p class="text-gray-600">Perpustakaan Sekolah Digital</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
                </div>

                <!-- Remember Me -->
                <div class="block mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-purple-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-purple-600 hover:bg-purple-700 focus:ring-purple-500">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
