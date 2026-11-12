<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\MutasiStok;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     */
    public function index(): View
    {
        return view('dashboard.index', [
            'stats' => [
                'total_barang' => Barang::query()->count(),
                'total_kategori' => Kategori::query()->count(),
                'stok_menipis' => Barang::query()
                    ->where('stok', '>', 0)
                    ->whereColumn('stok', '<=', 'stok_minimum')
                    ->count(),
                'stok_habis' => Barang::query()
                    ->where('stok', 0)
                    ->count(),
            ],
            'recentMutations' => MutasiStok::query()
                ->with(['barang', 'user'])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
        ]);
    }
}
