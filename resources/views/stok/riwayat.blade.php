@extends('layouts.app')

@section('title', 'Riwayat Stok')
@section('page_title', 'Riwayat Stok')

@section('content')
    <section class="app-card p-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Mutasi Stok</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau seluruh transaksi stok masuk dan stok keluar berdasarkan tanggal dan barang.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <form method="GET" action="{{ route('stok.riwayat') }}" class="flex flex-col gap-3 xl:flex-row xl:items-center">
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ $selectedDate }}"
                        class="app-input min-w-[200px]"
                    >

                    <select name="barang_id" class="app-input min-w-[240px]">
                        <option value="">Semua barang</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" @selected($selectedBarang === $item->id)>
                                {{ $item->kode_barang }} - {{ $item->nama_barang }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-3">
                        <button type="submit" class="app-button-secondary">
                            Filter
                        </button>

                        @if ($selectedDate !== '' || $selectedBarang > 0)
                            <a href="{{ route('stok.riwayat') }}" class="app-button-secondary">
                                Bersihkan
                            </a>
                        @endif
                    </div>
                </form>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('laporan.mutasi-stok', array_filter([
                            'tanggal_awal' => $selectedDate !== '' ? $selectedDate : null,
                            'tanggal_akhir' => $selectedDate !== '' ? $selectedDate : null,
                            'barang_id' => $selectedBarang > 0 ? $selectedBarang : null,
                        ])) }}"
                        class="app-button-primary"
                    >
                        Laporan Mutasi
                    </a>

                    <a
                        href="{{ route('laporan.mutasi-stok', array_filter([
                            'tanggal_awal' => $selectedDate !== '' ? $selectedDate : null,
                            'tanggal_akhir' => $selectedDate !== '' ? $selectedDate : null,
                            'barang_id' => $selectedBarang > 0 ? $selectedBarang : null,
                            'print' => 1,
                        ])) }}"
                        class="app-button-secondary"
                    >
                        Cetak Laporan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="app-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-4 py-4 sm:px-6">
            <div>
                <p class="text-sm font-semibold text-gray-900">Total {{ $mutations->total() }} mutasi</p>
                <p class="text-sm text-gray-500">
                    {{ $selectedDate !== '' || $selectedBarang > 0 ? 'Menampilkan hasil filter yang dipilih.' : 'Menampilkan seluruh riwayat mutasi stok.' }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="app-table min-w-[980px]">
                <thead class="app-table-head">
                    <tr>
                        <th class="app-th">Tanggal</th>
                        <th class="app-th">Barang</th>
                        <th class="app-th">Tipe</th>
                        <th class="app-th">Jumlah</th>
                        <th class="app-th hidden md:table-cell">Stok Sebelum</th>
                        <th class="app-th hidden md:table-cell">Stok Sesudah</th>
                        <th class="app-th hidden lg:table-cell">Keterangan</th>
                        <th class="app-th hidden lg:table-cell">User Input</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($mutations as $mutation)
                        <tr class="hover:bg-gray-50">
                            <td class="app-td">
                                {{ $mutation->tanggal?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="app-td">
                                <p class="text-sm font-semibold text-gray-900">{{ $mutation->barang?->nama_barang ?? '-' }}</p>
                                <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500">
                                    {{ $mutation->barang?->kode_barang ?? '-' }}
                                </p>
                                <div class="mt-2 space-y-1 text-xs text-gray-500 lg:hidden">
                                    <p>Keterangan: {{ $mutation->keterangan !== null && $mutation->keterangan !== '' ? $mutation->keterangan : '-' }}</p>
                                    <p class="md:hidden">Stok {{ $mutation->stok_sebelum }} → {{ $mutation->stok_sesudah }}</p>
                                    <p>User: {{ $mutation->user?->name ?? '-' }}</p>
                                </div>
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
                            <td class="app-td hidden md:table-cell">{{ $mutation->stok_sebelum }}</td>
                            <td class="app-td hidden md:table-cell">{{ $mutation->stok_sesudah }}</td>
                            <td class="app-td hidden lg:table-cell">
                                {{ $mutation->keterangan !== null && $mutation->keterangan !== '' ? $mutation->keterangan : '-' }}
                            </td>
                            <td class="app-td hidden lg:table-cell">{{ $mutation->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center sm:px-6">
                                <p class="text-base font-semibold text-gray-900">Belum ada riwayat mutasi</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Mutasi stok masuk dan keluar akan muncul di halaman ini setelah transaksi dicatat.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-4 sm:px-6">
            {{ $mutations->links() }}
        </div>
    </section>
@endsection
