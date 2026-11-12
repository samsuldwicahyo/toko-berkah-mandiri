@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
    <div class="mx-auto max-w-xl">
        <section class="app-card p-6 sm:p-8">
            <div class="space-y-6">
                <div class="space-y-3">
                    <span class="app-badge-primary">Atur Ulang Akses</span>
                    <div class="space-y-2">
                        <h1 class="text-2xl font-bold text-gray-900">Lupa Password</h1>
                        <p class="text-sm leading-6 text-gray-500">
                            Masukkan email akun Anda. Kami akan mengirimkan tautan untuk membuat password baru.
                        </p>
                    </div>
                </div>

                <x-auth-session-status class="mb-0" :status="session('status')" />

                @if ($errors->any())
                    <x-alert type="danger" :message="$errors->first()" />
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <x-input-label for="email" value="Email" />
                        <x-text-input
                            id="email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@tokoberkahmandiri.test"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <x-primary-button class="w-full sm:w-auto">
                            Kirim Tautan Atur Ulang
                        </x-primary-button>

                        <a href="{{ route('login') }}" class="app-button-secondary w-full sm:w-auto">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
