@extends('layouts.guest')

@section('title', 'Atur Ulang Password')

@section('content')
    <div class="mx-auto max-w-xl">
        <section class="app-card p-6 sm:p-8">
            <div class="space-y-6">
                <div class="space-y-3">
                    <span class="app-badge-primary">Password Baru</span>
                    <div class="space-y-2">
                        <h1 class="text-2xl font-bold text-gray-900">Atur Ulang Password</h1>
                        <p class="text-sm leading-6 text-gray-500">
                            Buat password baru untuk melanjutkan akses ke aplikasi inventory.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <x-alert type="danger" :message="$errors->first()" />
                @endif

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="space-y-2">
                        <x-input-label for="email" value="Email" />
                        <x-text-input
                            id="email"
                            type="email"
                            name="email"
                            :value="old('email', $request->email)"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="password" value="Password Baru" />
                        <x-text-input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Masukkan password baru"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-text-input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <x-primary-button class="w-full sm:w-auto">
                            Simpan Password Baru
                        </x-primary-button>

                        <a href="{{ route('login') }}" class="app-button-secondary w-full sm:w-auto">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
