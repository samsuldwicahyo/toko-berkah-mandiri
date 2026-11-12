@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="mx-auto max-w-xl">
        <section class="app-card p-6 sm:p-8">
            <div class="space-y-6">
                <div class="space-y-3">
                    <span class="app-badge-primary">Verifikasi Email</span>
                    <div class="space-y-2">
                        <h1 class="text-2xl font-bold text-gray-900">Periksa Kotak Masuk Anda</h1>
                        <p class="text-sm leading-6 text-gray-500">
                            Sebelum melanjutkan, klik tautan verifikasi yang sudah kami kirim ke email Anda.
                            Jika belum menerima email, kirim ulang dari halaman ini.
                        </p>
                    </div>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <x-alert
                        type="success"
                        message="Link verifikasi baru berhasil dikirim ke alamat email Anda."
                    />
                @endif

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <x-primary-button class="w-full sm:w-auto">
                            Kirim Ulang Email Verifikasi
                        </x-primary-button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="app-button-secondary w-full sm:w-auto">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
