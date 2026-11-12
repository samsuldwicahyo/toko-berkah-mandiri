@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card
            title="Total Jenis Barang"
            :value="$stats['total_barang']"
            description="Jumlah seluruh item barang yang terdaftar."
        />
        <x-stat-card
            title="Total Kategori"
            :value="$stats['total_kategori']"
            description="Kategori aktif yang dipakai untuk pengelompokan."
            tone="accent"
        />
        <x-stat-card
            title="Stok Menipis"
            :value="$stats['stok_menipis']"
            description="Barang dengan stok di bawah batas minimum."
            tone="warning"
        />
        <x-stat-card
            title="Stok Habis"
            :value="$stats['stok_habis']"
            description="Barang yang perlu segera direstok."
            tone="danger"
        />
    </div>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-5 sm:px-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">5 Mutasi Stok Terakhir</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Menampilkan aktivitas stok terbaru yang tercatat dari transaksi masuk dan keluar.
                </p>
            </div>

            <span class="app-badge-primary">
                {{ $recentMutations->count() }} data
            </span>
        </div>

        @if ($recentMutations->isEmpty())
            <div class="px-6 py-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface text-primary">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-semibold text-gray-900">Belum ada mutasi stok</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Setelah transaksi stok mulai dicatat, dashboard akan menampilkan 5 aktivitas terbaru di bagian ini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="app-table min-w-[700px]">
                    <thead class="app-table-head">
                        <tr>
                            <th class="app-th">Tanggal</th>
                            <th class="app-th">Barang</th>
                            <th class="app-th">Tipe</th>
                            <th class="app-th">Jumlah</th>
                            <th class="app-th hidden md:table-cell">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($recentMutations as $mutation)
                            <tr class="hover:bg-gray-50">
                                <td class="app-td">
                                    {{ $mutation->tanggal?->translatedFormat('d M Y') ?? '-' }}
                                </td>
                                <td class="app-td">
                                    <p class="text-sm font-medium text-gray-900">{{ $mutation->barang?->nama_barang ?? '-' }}</p>
                                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500">
                                        {{ $mutation->barang?->kode_barang ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500 md:hidden">
                                        Petugas: {{ $mutation->user?->name ?? '-' }}
                                    </p>
                                </td>
                                <td class="app-td">
                                    <span class="{{ $mutation->tipe === 'masuk' ? 'app-badge-success' : 'app-badge-danger' }}">
                                        {{ $mutation->tipe === 'masuk' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                </td>
                                <td class="app-td">
                                    <span class="{{ $mutation->tipe === 'masuk' ? 'text-success' : 'text-danger' }} text-sm font-semibold">
                                        {{ $mutation->tipe === 'masuk' ? '+' : '-' }}{{ $mutation->jumlah }}
                                    </span>
                                </td>
                                <td class="app-td hidden md:table-cell">{{ $mutation->user?->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
