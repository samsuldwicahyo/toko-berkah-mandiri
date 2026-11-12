@extends('layouts.app')

@section('title', 'Laporan Stok Menipis')
@section('page_title', 'Laporan Stok Menipis')

@section('content')
    @php
        $formatRupiah = static fn (int $value): string => 'Rp'.number_format($value, 0, ',', '.');
        $activeFilters = [];

        if ($search !== '') {
            $activeFilters[] = 'Pencarian: '.$search;
        }

        if ($selectedKategori > 0) {
            $selectedCategoryName = $categories->firstWhere('id', $selectedKategori)?->nama_kategori;

            if ($selectedCategoryName) {
                $activeFilters[] = 'Kategori: '.$selectedCategoryName;
            }
        }

        if ($selectedStatus === 'habis') {
            $activeFilters[] = 'Status: Habis';
        } elseif ($selectedStatus === 'menipis') {
            $activeFilters[] = 'Status: Menipis';
        }
    @endphp

    <section class="app-card p-6 app-print-hidden">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Laporan Barang Kritis</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau barang habis dan menipis, lalu siapkan daftar restock untuk belanja kulakan toko.
                </p>
            </div>

            <form method="GET" action="{{ route('laporan.stok-menipis') }}" class="flex flex-col gap-3 xl:flex-row xl:items-center">
                <select name="kategori_id" class="app-input min-w-[220px]">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($selectedKategori === $category->id)>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="app-input min-w-[180px]">
                    <option value="">Semua status</option>
                    <option value="habis" @selected($selectedStatus === 'habis')>Habis</option>
                    <option value="menipis" @selected($selectedStatus === 'menipis')>Menipis</option>
                </select>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau kode barang..."
                    class="app-input min-w-[250px]"
                >

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="app-button-secondary">
                        Filter
                    </button>

                    @if ($hasFilters)
                        <a href="{{ route('laporan.stok-menipis') }}" class="app-button-secondary">
                            Bersihkan
                        </a>
                    @endif

                    <a
                        href="{{ route('laporan.stok-menipis', array_filter([
                            'kategori_id' => $selectedKategori > 0 ? $selectedKategori : null,
                            'status' => $selectedStatus !== '' ? $selectedStatus : null,
                            'search' => $search !== '' ? $search : null,
                            'print' => 1,
                        ])) }}"
                        class="app-button-primary"
                    >
                        Versi Cetak
                    </a>

                    <a href="{{ route('laporan.mutasi-stok') }}" class="app-button-secondary">
                        Laporan Masuk Keluar
                    </a>
                </div>
            </form>
        </div>
    </section>

    @if ($isPrint)
        <section class="app-card p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-primary">Toko Berkah Mandiri</p>
                    <h2 class="mt-2 text-2xl font-bold text-gray-900">Laporan Stok Menipis</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Dicetak pada {{ $generatedAt->translatedFormat('d M Y, H:i') }}
                    </p>

                    @if ($activeFilters !== [])
                        <p class="mt-2 text-sm text-gray-500">
                            {{ implode(' | ', $activeFilters) }}
                        </p>
                    @endif
                </div>

                <div class="app-print-hidden flex gap-3">
                    <button type="button" onclick="window.print()" class="app-button-primary">
                        Cetak Sekarang
                    </button>

                    <a
                        href="{{ route('laporan.stok-menipis', array_filter([
                            'kategori_id' => $selectedKategori > 0 ? $selectedKategori : null,
                            'status' => $selectedStatus !== '' ? $selectedStatus : null,
                            'search' => $search !== '' ? $search : null,
                        ])) }}"
                        class="app-button-secondary"
                    >
                        Kembali
                    </a>
                </div>
            </div>
        </section>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card
            title="Total Barang Kritis"
            :value="$summary['total_kritis']"
            description="Barang yang perlu perhatian restock."
        />
        <x-stat-card
            title="Barang Habis"
            :value="$summary['habis']"
            description="Stok 0 dan perlu diisi ulang segera."
            tone="danger"
        />
        <x-stat-card
            title="Barang Menipis"
            :value="$summary['menipis']"
            description="Masih ada stok, tetapi sudah di bawah batas aman."
            tone="warning"
        />
        <x-stat-card
            title="Estimasi Modal Restock"
            :value="$formatRupiah((int) $summary['estimasi_modal'])"
            :description="'Perlu tambahan minimal '.$summary['qty_restock'].' item untuk mencapai stok minimum.'"
            tone="accent"
        />
    </div>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-4 sm:px-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Belanja Restock</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Barang diurutkan dari stok paling sedikit, lalu dari batas minimum terkecil ke terbesar.
                </p>
            </div>

            <span class="app-badge-primary">
                {{ $displayTotal }} barang
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="app-table min-w-[980px]">
                <thead class="app-table-head">
                    <tr>
                        <th class="app-th">Barang</th>
                        <th class="app-th hidden lg:table-cell">Kategori</th>
                        <th class="app-th">Stok</th>
                        <th class="app-th hidden md:table-cell">Stok Minimum</th>
                        <th class="app-th">Status</th>
                        <th class="app-th">Perlu Tambah</th>
                        <th class="app-th hidden lg:table-cell">Estimasi Modal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($items as $item)
                        @php
                            $qtyRestock = max((int) $item->stok_minimum - (int) $item->stok, 0);
                            $estimasiModal = $qtyRestock * (int) $item->harga_beli;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="app-td">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->nama_barang }}</p>
                                <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500">
                                    {{ $item->kode_barang }}
                                </p>
                                <div class="mt-2 grid gap-1 text-xs text-gray-500 lg:hidden">
                                    <span>{{ $item->kategori?->nama_kategori ?? '-' }}</span>
                                    <span>{{ $item->satuan }} | Min. {{ $item->stok_minimum }}</span>
                                    <span>Estimasi {{ $formatRupiah($estimasiModal) }}</span>
                                </div>
                            </td>
                            <td class="app-td hidden lg:table-cell">{{ $item->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="app-td">
                                <span class="text-sm font-semibold text-gray-900">{{ $item->stok }}</span>
                                <span class="ml-1 text-xs text-gray-500">{{ $item->satuan }}</span>
                            </td>
                            <td class="app-td hidden md:table-cell">{{ $item->stok_minimum }}</td>
                            <td class="app-td">
                                <x-badge-stok :stok="$item->stok" :stok-minimum="$item->stok_minimum" />
                            </td>
                            <td class="app-td">
                                <span class="text-sm font-semibold text-gray-900">{{ $qtyRestock }}</span>
                                <span class="ml-1 text-xs text-gray-500">{{ $item->satuan }}</span>
                            </td>
                            <td class="app-td hidden lg:table-cell">{{ $formatRupiah($estimasiModal) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center sm:px-6">
                                <p class="text-base font-semibold text-gray-900">Tidak ada barang kritis</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Semua stok barang saat ini masih berada di atas batas minimum.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (! $isPrint)
            <div class="border-t border-gray-100 px-4 py-4 sm:px-6">
                {{ $items->links() }}
            </div>
        @endif
    </section>
@endsection
