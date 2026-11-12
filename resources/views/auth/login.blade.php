@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="overflow-hidden rounded-[2rem] border border-gray-100 bg-white shadow-sm">
        <div class="grid min-h-[720px] lg:grid-cols-[1.08fr_0.92fr]">
            <section class="relative overflow-hidden bg-primary px-6 py-10 text-white sm:px-10 lg:px-12">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.20),_transparent_35%),radial-gradient(circle_at_bottom_left,_rgba(245,158,11,0.18),_transparent_30%)]"></div>

                <div class="relative flex h-full flex-col justify-between gap-10">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V8.2c0-.5.2-.98.56-1.32l5.88-5.53a.8.8 0 0 1 1.11 0l5.89 5.53c.35.34.56.82.56 1.32V21M9 21v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold">Toko Berkah Mandiri</p>
                                <p class="text-xs text-primary-light">Sistem inventory UMKM</p>
                            </div>
                        </div>

                        <div class="max-w-xl space-y-4">
                            <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-primary-light">
                                Kelola stok lebih rapi
                            </span>
                            <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl">
                                Pantau barang masuk dan keluar tanpa pencatatan manual yang berantakan.
                            </h1>
                            <p class="max-w-lg text-base leading-7 text-primary-light sm:text-lg">
                                Satu tempat untuk memantau kategori, stok aman, barang menipis, dan riwayat mutasi toko dengan tampilan yang ringan dipakai tiap hari.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-primary-light">Stok Aman</p>
                            <p class="mt-2 text-2xl font-bold">Real-time</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-primary-light">Mutasi</p>
                            <p class="mt-2 text-2xl font-bold">Tercatat</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-primary-light">Akses</p>
                            <p class="mt-2 text-2xl font-bold">Admin & Staff</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex items-center bg-white px-6 py-10 sm:px-10 lg:px-12">
                <div class="mx-auto w-full max-w-md space-y-8">
                    <div class="space-y-3">
                        <span class="inline-flex rounded-full bg-primary-light px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-primary-dark">
                            Login inventory
                        </span>
                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold text-gray-900">Masuk ke akun Anda</h2>
                            <p class="text-sm leading-6 text-gray-500">
                                Gunakan email dan password terdaftar untuk melanjutkan ke dashboard inventory toko.
                            </p>
                        </div>
                    </div>

                    @if (session('status'))
                        <x-alert type="success" :message="session('status')" />
                    @endif

                    @if ($errors->any())
                        <x-alert type="danger" :message="$errors->first('email') ?: $errors->first('password')" />
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@tokoberkahmandiri.test"
                                class="app-input"
                            >
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-3">
                                <label for="password" class="text-sm font-semibold text-gray-700">Password</label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-primary transition hover:text-primary-dark">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="app-input"
                            >
                        </div>

                        <label for="remember" class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-600">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                            >
                            <span>Ingat sesi login pada perangkat ini</span>
                        </label>

                        <button type="submit" class="app-button-primary w-full">
                            Masuk
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
