@extends('layouts.app')

@section('title', 'Kategori')
@section('page_title', 'Kategori')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    <section class="app-card p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Kategori</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola kategori barang agar pencarian dan pengelompokan stok lebih rapi.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="GET" action="{{ route('kategori.index') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama kategori..."
                        class="app-input min-w-[240px]"
                    >

                    <div class="flex gap-3">
                        <button type="submit" class="app-button-secondary">
                            Cari
                        </button>

                        @if ($search !== '')
                            <a href="{{ route('kategori.index') }}" class="app-button-secondary">
                                Bersihkan
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('kategori.create') }}" class="app-button-primary">
                    Tambah Kategori
                </a>
            </div>
        </div>
    </section>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-4 sm:px-6">
            <div>
                <p class="text-sm font-semibold text-gray-900">Total {{ $categories->total() }} kategori</p>
                <p class="text-sm text-gray-500">
                    {{ $search !== '' ? 'Menampilkan hasil pencarian untuk "'.$search.'".' : 'Menampilkan seluruh kategori yang tersedia.' }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="app-table min-w-[680px]">
                <thead class="app-table-head">
                    <tr>
                        <th class="app-th">No</th>
                        <th class="app-th">Nama Kategori</th>
                        <th class="app-th hidden md:table-cell">Dibuat</th>
                        <th class="app-th">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($categories as $index => $category)
                        <tr class="hover:bg-gray-50">
                            <td class="app-td text-gray-500">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            <td class="app-td">
                                <p class="text-sm font-semibold text-gray-900">{{ $category->nama_kategori }}</p>
                                <p class="mt-1 text-xs text-gray-500 md:hidden">
                                    Dibuat {{ $category->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </p>
                            </td>
                            <td class="app-td hidden md:table-cell text-gray-500">
                                {{ $category->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="app-td">
                                @if ($user?->role === 'admin')
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('kategori.edit', $category) }}" class="app-button-secondary !px-3 !py-2">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('kategori.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="app-button-danger !px-3 !py-2">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Hanya admin yang dapat mengubah data.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center sm:px-6">
                                <p class="text-base font-semibold text-gray-900">Belum ada kategori</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Tambahkan kategori pertama untuk mulai mengelompokkan barang inventory.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-4 sm:px-6">
            {{ $categories->links() }}
        </div>
    </section>
@endsection
