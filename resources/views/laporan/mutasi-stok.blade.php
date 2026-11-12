@extends('layouts.app')

@section('title', 'Laporan Mutasi Stok')
@section('page_title', 'Laporan Mutasi Stok')

@section('content')
    @php
        $activeFilters = [];

        if ($tanggalAwal !== '' || $tanggalAkhir !== '') {
            $activeFilters[] = 'Periode: '.($tanggalAwal !== '' ? $tanggalAwal : '...').' s/d '.($tanggalAkhir !== '' ? $tanggalAkhir : '...');
        }

        if ($selectedKategori > 0) {
            $selectedCategoryName = $categories->firstWhere('id', $selectedKategori)?->nama_kategori;

            if ($selectedCategoryName) {
                $activeFilters[] = 'Kategori: '.$selectedCategoryName;
            }
        }

        if ($selectedBarang > 0) {
            $selectedItemName = $barangs->firstWhere('id', $selectedBarang)?->nama_barang;

            if ($selectedItemName) {
                $activeFilters[] = 'Barang: '.$selectedItemName;
            }
        }

        if ($selectedTipe === 'masuk') {
            $activeFilters[] = 'Tipe: Masuk';
        } elseif ($selectedTipe === 'keluar') {
            $activeFilters[] = 'Tipe: Keluar';
        }

        if ($search !== '') {
            $activeFilters[] = 'Pencarian: '.$search;
        }
    @endphp

    <section class="app-card p-6 app-print-hidden">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Laporan Masuk Keluar Barang</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Rekap transaksi stok masuk dan stok keluar yang siap dicetak sebagai laporan akhir operasional toko.
                </p>
            </div>

            <form method="GET" action="{{ route('laporan.mutasi-stok') }}" class="grid gap-3 xl:grid-cols-3">
                <input
                    type="date"
                    name="tanggal_awal"
                    value="{{ $tanggalAwal }}"
                    class="app-input min-w-[200px]"
                >

                <input
                    type="date"
                    name="tanggal_akhir"
                    value="{{ $tanggalAkhir }}"
                    class="app-input min-w-[200px]"
                >

                <select name="kategori_id" class="app-input min-w-[220px]">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($selectedKategori === $category->id)>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <select name="barang_id" class="app-input min-w-[260px]">
                    <option value="">Semua barang</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}" @selected($selectedBarang === $barang->id)>
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>

                <select name="tipe" class="app-input min-w-[180px]">
                    <option value="">Semua tipe</option>
                    <option value="masuk" @selected($selectedTipe === 'masuk')>Masuk</option>
                    <option value="keluar" @selected($selectedTipe === 'keluar')>Keluar</option>
                </select>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari barang, petugas, atau keterangan..."
                    class="app-input min-w-[260px]"
                >

                <div class="flex flex-wrap gap-3 xl:col-span-3">
                    <button type="submit" class="app-button-secondary">
                        Filter
                    </button>

                    @if ($hasFilters)
                        <a href="{{ route('laporan.mutasi-stok') }}" class="app-button-secondary">
                            Bersihkan
                        </a>
                    @endif

                    <a
                        href="{{ route('laporan.mutasi-stok', array_filter([
                            'tanggal_awal' => $tanggalAwal !== '' ? $tanggalAwal : null,
                            'tanggal_akhir' => $tanggalAkhir !== '' ? $tanggalAkhir : null,
                            'kategori_id' => $selectedKategori > 0 ? $selectedKategori : null,
                            'barang_id' => $selectedBarang > 0 ? $selectedBarang : null,
                            'tipe' => $selectedTipe !== '' ? $selectedTipe : null,
                            'search' => $search !== '' ? $search : null,
                            'print' => 1,
                        ])) }}"
                        class="app-button-primary"
                    >
                        Versi Cetak
                    </a>

                    <a href="{{ route('laporan.stok-menipis') }}" class="app-button-secondary">
                        Laporan Stok Menipis
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
                    <h2 class="mt-2 text-2xl font-bold text-gray-900">Laporan Mutasi Stok</h2>
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
                        href="{{ route('laporan.mutasi-stok', array_filter([
                            'tanggal_awal' => $tanggalAwal !== '' ? $tanggalAwal : null,
                            'tanggal_akhir' => $tanggalAkhir !== '' ? $tanggalAkhir : null,
                            'kategori_id' => $selectedKategori > 0 ? $selectedKategori : null,
                            'barang_id' => $selectedBarang > 0 ? $selectedBarang : null,
                            'tipe' => $selectedTipe !== '' ? $selectedTipe : null,
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
            title="Total Transaksi"
            :value="$summary['total_transaksi']"
            description="Jumlah mutasi stok sesuai filter laporan."
        />
        <x-stat-card
            title="Total Stok Masuk"
            :value="$summary['total_masuk']"
            description="Akumulasi qty barang masuk."
            tone="success"
        />
        <x-stat-card
            title="Total Stok Keluar"
            :value="$summary['total_keluar']"
            description="Akumulasi qty barang keluar."
            tone="danger"
        />
        <x-stat-card
            title="Selisih Bersih"
            :value="$summary['selisih_bersih']"
            description="Hasil stok masuk dikurangi stok keluar."
            tone="accent"
        />
    </div>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-4 sm:px-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Mutasi Masuk Keluar</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Menampilkan seluruh transaksi stok masuk dan keluar yang sesuai dengan filter laporan.
                </p>
            </div>

            <span class="app-badge-primary">
                {{ $displayTotal }} transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="app-table min-w-[1120px]">
                <thead class="app-table-head">
                    <tr>
                        <th class="app-th">Tanggal</th>
                        <th class="app-th">Barang</th>
                        <th class="app-th">Tipe</th>
                        <th class="app-th">Jumlah</th>
                        <th class="app-th hidden md:table-cell">Stok Sebelum</th>
                        <th class="app-th hidden md:table-cell">Stok Sesudah</th>
                        <th class="app-th hidden lg:table-cell">Keterangan</th>
                        <th class="app-th hidden lg:table-cell">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="app-td">
                                {{ $item->tanggal?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="app-td">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->barang?->nama_barang ?? '-' }}</p>
                                <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500">
                                    {{ $item->barang?->kode_barang ?? '-' }}
                                </p>
                                <div class="mt-2 space-y-1 text-xs text-gray-500 lg:hidden">
                                    <p>Keterangan: {{ $item->keterangan !== null && $item->keterangan !== '' ? $item->keterangan : '-' }}</p>
                                    <p class="md:hidden">Stok {{ $item->stok_sebelum }} -> {{ $item->stok_sesudah }}</p>
                                    <p>Petugas: {{ $item->user?->name ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="app-td">
                                <span class="{{ $item->tipe === 'masuk' ? 'app-badge-success' : 'app-badge-danger' }}">
                                    {{ $item->tipe === 'masuk' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="app-td">
                                <span class="{{ $item->tipe === 'masuk' ? 'text-success' : 'text-danger' }} text-sm font-semibold">
                                    {{ $item->tipe === 'masuk' ? '+' : '-' }}{{ $item->jumlah }}
                                </span>
                            </td>
                            <td class="app-td hidden md:table-cell">{{ $item->stok_sebelum }}</td>
                            <td class="app-td hidden md:table-cell">{{ $item->stok_sesudah }}</td>
                            <td class="app-td hidden lg:table-cell">
                                {{ $item->keterangan !== null && $item->keterangan !== '' ? $item->keterangan : '-' }}
                            </td>
                            <td class="app-td hidden lg:table-cell">{{ $item->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center sm:px-6">
                                <p class="text-base font-semibold text-gray-900">Tidak ada data mutasi</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Belum ada transaksi stok masuk atau keluar yang cocok dengan filter laporan ini.
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
