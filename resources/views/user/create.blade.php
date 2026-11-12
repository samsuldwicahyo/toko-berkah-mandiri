@extends('layouts.app')

@section('title', 'Tambah User')
@section('page_title', 'Tambah User')

@section('content')
    <section class="app-card p-6">
        <div class="max-w-3xl space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tambah Pengguna Baru</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Buat akun admin atau staff baru dan tentukan status aktifnya sejak awal.
                </p>
            </div>

            @if ($errors->any())
                <x-alert type="danger" :message="$errors->first()" />
            @endif

            <form method="POST" action="{{ route('user.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-gray-700">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="app-input">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="app-input">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="text-sm font-semibold text-gray-700">Role</label>
                        <select id="role" name="role" class="app-input" required>
                            <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="aktif" class="text-sm font-semibold text-gray-700">Status Akun</label>
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-600">
                            <input id="aktif" type="checkbox" name="aktif" value="1" @checked(old('aktif', '1')) class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span>Akun aktif dan bisa dipakai login</span>
                        </label>
                        <x-input-error :messages="$errors->get('aktif')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                        <input id="password" type="password" name="password" required class="app-input">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="app-input">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="submit" class="app-button-primary">
                        Simpan User
                    </button>

                    <a href="{{ route('user.index') }}" class="app-button-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection
