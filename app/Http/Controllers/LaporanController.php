<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Display the low-stock report.
     */
    public function stokMenipis(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $selectedKategori = $request->integer('kategori_id');
        $selectedStatus = trim($request->string('status')->toString());
        $isPrint = $request->boolean('print');

        $itemsQuery = Barang::query()
            ->with('kategori')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nestedQuery) use ($search): void {
                    $nestedQuery
                        ->where('nama_barang', 'like', '%'.$search.'%')
                        ->orWhere('kode_barang', 'like', '%'.$search.'%');
                });
            })
            ->when($selectedKategori > 0, function ($query) use ($selectedKategori): void {
                $query->where('kategori_id', $selectedKategori);
            })
            ->when($selectedStatus === 'habis', function ($query): void {
                $query->where('stok', 0);
            })
            ->when($selectedStatus === 'menipis', function ($query): void {
                $query->where('stok', '>', 0)
                    ->whereColumn('stok', '<=', 'stok_minimum');
            })
            ->orderBy('stok')
            ->orderBy('stok_minimum')
            ->orderBy('nama_barang');

        $summaryItems = (clone $itemsQuery)->get();

        $items = $isPrint
            ? $summaryItems
            : (clone $itemsQuery)->paginate(10)->withQueryString();

        $summary = [
            'total_kritis' => $summaryItems->count(),
            'habis' => $summaryItems->where('stok', 0)->count(),
            'menipis' => $summaryItems->filter(function ($item): bool {
                return (int) $item->stok > 0 && (int) $item->stok <= (int) $item->stok_minimum;
            })->count(),
            'qty_restock' => $summaryItems->sum(function ($item): int {
                return max((int) $item->stok_minimum - (int) $item->stok, 0);
            }),
            'estimasi_modal' => $summaryItems->sum(function ($item): int {
                return max((int) $item->stok_minimum - (int) $item->stok, 0) * (int) $item->harga_beli;
            }),
        ];

        return view('laporan.stok-menipis', [
            'items' => $items,
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'displayTotal' => $summary['total_kritis'],
            'generatedAt' => now(),
            'hasFilters' => $search !== '' || $selectedKategori > 0 || $selectedStatus !== '',
            'isPrint' => $isPrint,
            'search' => $search,
            'selectedKategori' => $selectedKategori,
            'selectedStatus' => $selectedStatus,
            'summary' => $summary,
        ]);
    }

    /**
     * Display the stock mutation report.
     */
    public function mutasiStok(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $selectedKategori = $request->integer('kategori_id');
        $selectedBarang = $request->integer('barang_id');
        $selectedTipe = trim($request->string('tipe')->toString());
        $tanggalAwal = trim($request->string('tanggal_awal')->toString());
        $tanggalAkhir = trim($request->string('tanggal_akhir')->toString());
        $isPrint = $request->boolean('print');

        $itemsQuery = MutasiStok::query()
            ->with(['barang.kategori', 'user'])
            ->when($tanggalAwal !== '', function ($query) use ($tanggalAwal): void {
                $query->whereDate('tanggal', '>=', $tanggalAwal);
            })
            ->when($tanggalAkhir !== '', function ($query) use ($tanggalAkhir): void {
                $query->whereDate('tanggal', '<=', $tanggalAkhir);
            })
            ->when($selectedKategori > 0, function ($query) use ($selectedKategori): void {
                $query->whereHas('barang', function ($barangQuery) use ($selectedKategori): void {
                    $barangQuery->where('kategori_id', $selectedKategori);
                });
            })
            ->when($selectedBarang > 0, function ($query) use ($selectedBarang): void {
                $query->where('barang_id', $selectedBarang);
            })
            ->when(in_array($selectedTipe, ['masuk', 'keluar'], true), function ($query) use ($selectedTipe): void {
                $query->where('tipe', $selectedTipe);
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nestedQuery) use ($search): void {
                    $nestedQuery
                        ->where('keterangan', 'like', '%'.$search.'%')
                        ->orWhereHas('barang', function ($barangQuery) use ($search): void {
                            $barangQuery
                                ->where('nama_barang', 'like', '%'.$search.'%')
                                ->orWhere('kode_barang', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search): void {
                            $userQuery->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        $summaryItems = (clone $itemsQuery)->get();

        $items = $isPrint
            ? $summaryItems
            : (clone $itemsQuery)->paginate(10)->withQueryString();

        $summary = [
            'total_transaksi' => $summaryItems->count(),
            'total_masuk' => $summaryItems->where('tipe', 'masuk')->sum('jumlah'),
            'total_keluar' => $summaryItems->where('tipe', 'keluar')->sum('jumlah'),
        ];
        $summary['selisih_bersih'] = $summary['total_masuk'] - $summary['total_keluar'];

        return view('laporan.mutasi-stok', [
            'items' => $items,
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'barangs' => Barang::query()
                ->with('kategori')
                ->when($selectedKategori > 0, function ($query) use ($selectedKategori): void {
                    $query->where('kategori_id', $selectedKategori);
                })
                ->orderBy('nama_barang')
                ->get(),
            'displayTotal' => $summary['total_transaksi'],
            'generatedAt' => now(),
            'hasFilters' => $search !== ''
                || $selectedKategori > 0
                || $selectedBarang > 0
                || $selectedTipe !== ''
                || $tanggalAwal !== ''
                || $tanggalAkhir !== '',
            'isPrint' => $isPrint,
            'search' => $search,
            'selectedKategori' => $selectedKategori,
            'selectedBarang' => $selectedBarang,
            'selectedTipe' => $selectedTipe,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'summary' => $summary,
        ]);
    }
}
