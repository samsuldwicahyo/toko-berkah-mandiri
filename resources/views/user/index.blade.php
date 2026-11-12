@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')

@section('content')
    <section class="app-card p-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Pengguna</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola akun admin dan staff, termasuk status aktif untuk akses login aplikasi.
                </p>
            </div>

            <div class="flex flex-col gap-3 xl:flex-row xl:items-center">
                <form method="GET" action="{{ route('user.index') }}" class="flex flex-col gap-3 xl:flex-row xl:items-center">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, atau role..."
                        class="app-input min-w-[250px]"
                    >

                    <select name="status" class="app-input min-w-[180px]">
                        <option value="">Semua status</option>
                        <option value="aktif" @selected($status === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($status === 'nonaktif')>Nonaktif</option>
                    </select>

                    <div class="flex gap-3">
                        <button type="submit" class="app-button-secondary">
                            Filter
                        </button>

                        @if ($search !== '' || $status !== '')
                            <a href="{{ route('user.index') }}" class="app-button-secondary">
                                Bersihkan
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('user.create') }}" class="app-button-primary">
                    Tambah User
                </a>
            </div>
        </div>
    </section>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-4 sm:px-6">
            <div>
                <p class="text-sm font-semibold text-gray-900">Total {{ $users->total() }} pengguna</p>
                <p class="text-sm text-gray-500">
                    {{ $search !== '' || $status !== '' ? 'Menampilkan hasil pencarian dan filter yang dipilih.' : 'Menampilkan seluruh akun pengguna aplikasi.' }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="app-table min-w-[860px]">
                <thead class="app-table-head">
                    <tr>
                        <th class="app-th">Pengguna</th>
                        <th class="app-th">Role</th>
                        <th class="app-th">Status</th>
                        <th class="app-th hidden md:table-cell">Dibuat</th>
                        <th class="app-th">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $managedUser)
                        <tr class="hover:bg-gray-50">
                            <td class="app-td">
                                <p class="text-sm font-semibold text-gray-900">{{ $managedUser->name }}</p>
                                <p class="text-sm text-gray-500">{{ $managedUser->email }}</p>
                                <p class="mt-1 text-xs text-gray-500 md:hidden">
                                    Dibuat {{ $managedUser->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </p>
                            </td>
                            <td class="app-td">
                                <span class="{{ $managedUser->role === 'admin' ? 'app-badge-primary' : 'app-badge-warning' }}">
                                    {{ ucfirst($managedUser->role) }}
                                </span>
                            </td>
                            <td class="app-td">
                                <span class="{{ $managedUser->aktif ? 'app-badge-success' : 'app-badge-danger' }}">
                                    {{ $managedUser->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="app-td hidden md:table-cell">
                                {{ $managedUser->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="app-td">
                                <a href="{{ route('user.edit', $managedUser) }}" class="app-button-secondary !px-3 !py-2">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center sm:px-6">
                                <p class="text-base font-semibold text-gray-900">Belum ada pengguna</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Tambahkan akun baru untuk admin atau staff toko dari halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-4 sm:px-6">
            {{ $users->links() }}
        </div>
    </section>
@endsection
