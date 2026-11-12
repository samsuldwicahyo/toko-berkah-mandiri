@extends('layouts.guest')

@section('title', 'Konfirmasi Password')

@section('content')
    <div class="mx-auto max-w-xl">
        <section class="app-card p-6 sm:p-8">
            <div class="space-y-6">
                <div class="space-y-3">
                    <span class="app-badge-primary">Keamanan Akun</span>
                    <div class="space-y-2">
                        <h1 class="text-2xl font-bold text-gray-900">Konfirmasi Password</h1>
                        <p class="text-sm leading-6 text-gray-500">
                            Area ini membutuhkan konfirmasi password sebelum Anda melanjutkan.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <x-alert type="danger" :message="$errors->first()" />
                @endif

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <x-input-label for="password" value="Password" />
                        <x-text-input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <x-primary-button class="w-full sm:w-auto">
                            Konfirmasi
                        </x-primary-button>

                        <a href="{{ route('dashboard') }}" class="app-button-secondary w-full sm:w-auto">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
