@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')
    <div class="mx-auto max-w-xl">
        <section class="app-card p-6 sm:p-8">
            <div class="space-y-6">
                <div class="space-y-3">
                    <span class="app-badge-primary">Akun Baru</span>
                    <div class="space-y-2">
                        <h1 class="text-2xl font-bold text-gray-900">Daftar Akun</h1>
                        <p class="text-sm leading-6 text-gray-500">
                            Buat akun baru untuk mengakses aplikasi inventory Toko Berkah Mandiri.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <x-alert type="danger" :message="$errors->first()" />
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-primary transition hover:text-primary-dark">
                            Sudah punya akun?
                        </a>

                        <x-primary-button class="w-full sm:w-auto">
                            Daftar
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
