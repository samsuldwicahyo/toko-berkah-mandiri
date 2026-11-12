@extends('layouts.app')

@section('title', 'Barang')
@section('page_title', 'Barang')

@section('content')
    @php
        $user = auth()->user();

        $formatRupiah = static fn (int $value): string => 'Rp'.number_format($value, 0, ',', '.');
    @endphp

    <section class="app-card p-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Barang</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola master barang, stok awal, harga beli, harga jual, dan status stok inventory.
                </p>
            </div>

            <div class="flex flex-col gap-3 xl:flex-row xl:items-center">
                <form method="GET" action="{{ route('barang.index') }}" class="flex flex-col gap-3 xl:flex-row xl:items-center">
                    <select name="kategori_id" class="app-input min-w-[220px]">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($selectedKategori === $category->id)>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau kode barang..."
                        class="app-input min-w-[260px]"
                    >

                    <div class="flex gap-3">
                        <button type="submit" class="app-button-secondary">
                            Filter
                        </button>

                        @if ($search !== '' || $selectedKategori > 0)
                            <a href="{{ route('barang.index') }}" class="app-button-secondary">
                                Bersihkan
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('barang.create') }}" class="app-button-primary">
                    Tambah Barang
                </a>
            </div>
        </div>
    </section>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-4 sm:px-6">
            <div>
                <p class="text-sm font-semibold text-gray-900">Total {{ $barangs->total() }} barang</p>
                <p class="text-sm text-gray-500">
                    {{ $search !== '' || $selectedKategori > 0 ? 'Menampilkan hasil pencarian dan filter yang dipilih.' : 'Menampilkan seluruh barang inventory.' }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="app-table min-w-[980px]">
                <thead class="app-table-head">
                    <tr>
                        <th class="app-th">Barang</th>
                        <th class="app-th hidden lg:table-cell">Kategori</th>
                        <th class="app-th hidden md:table-cell">Satuan</th>
                        <th class="app-th hidden xl:table-cell">Harga Beli</th>
                        <th class="app-th hidden xl:table-cell">Harga Jual</th>
                        <th class="app-th">Stok</th>
                        <th class="app-th">Status</th>
                        <th class="app-th">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($barangs as $barang)
                        @php
                            $imageUrl = $barang->gambar ? \Illuminate\Support\Facades\Storage::disk('public')->url($barang->gambar) : null;
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="app-td">
                                <div class="flex min-w-[260px] items-center gap-4">
                                    @if ($imageUrl)
                                        <img
                                            src="{{ $imageUrl }}"
                                            alt="{{ $barang->nama_barang }}"
                                            class="h-14 w-14 rounded-2xl object-cover"
                                        >
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-surface text-gray-400">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V6.75A1.75 1.75 0 0 1 4.75 5h14.5A1.75 1.75 0 0 1 21 6.75v10.5A1.75 1.75 0 0 1 19.25 19H4.75A1.75 1.75 0 0 1 3 17.25v-.75Zm0 0 4.9-4.9a1.5 1.5 0 0 1 2.12 0l1.98 1.98a1.5 1.5 0 0 0 2.12 0l.88-.88a1.5 1.5 0 0 1 2.12 0L21 15.6M8.5 9.5h.01" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="space-y-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $barang->nama_barang }}</p>
                                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500">
                                            {{ $barang->kode_barang }}
                                        </p>
                                        <div class="flex flex-wrap gap-2 text-xs text-gray-500 lg:hidden">
                                            <span>{{ $barang->kategori?->nama_kategori ?? '-' }}</span>
                                            <span class="hidden md:inline">•</span>
                                            <span>{{ $barang->satuan }}</span>
                                        </div>
                                        <div class="grid gap-1 text-xs text-gray-500 xl:hidden">
                                            <span>Beli {{ $formatRupiah((int) $barang->harga_beli) }}</span>
                                            <span>Jual {{ $formatRupiah((int) $barang->harga_jual) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="app-td hidden lg:table-cell">{{ $barang->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="app-td hidden md:table-cell">{{ $barang->satuan }}</td>
                            <td class="app-td hidden xl:table-cell">{{ $formatRupiah((int) $barang->harga_beli) }}</td>
                            <td class="app-td hidden xl:table-cell">{{ $formatRupiah((int) $barang->harga_jual) }}</td>
                            <td class="app-td">
                                <p class="text-sm font-semibold text-gray-900">{{ $barang->stok }}</p>
                                <p class="text-xs text-gray-500">Min. {{ $barang->stok_minimum }}</p>
                            </td>
                            <td class="app-td">
                                <x-badge-stok :stok="$barang->stok" :stok-minimum="$barang->stok_minimum" />
                            </td>
                            <td class="app-td">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('barang.edit', $barang) }}" class="app-button-secondary !px-3 !py-2">
                                        Edit
                                    </a>

                                    @if ($user?->role === 'admin')
                                        <form method="POST" action="{{ route('barang.destroy', $barang) }}" onsubmit="return confirm('Hapus barang ini?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="app-button-danger !px-3 !py-2">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center sm:px-6">
                                <p class="text-base font-semibold text-gray-900">Belum ada barang</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Tambahkan barang pertama untuk mulai mengelola stok inventory toko.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-4 sm:px-6">
            {{ $barangs->links() }}
        </div>
    </section>
@endsection
