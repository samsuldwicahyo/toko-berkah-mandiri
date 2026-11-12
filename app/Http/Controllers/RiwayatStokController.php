<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatStokController extends Controller
{
    /**
     * Display the stock mutation history.
     */
    public function index(Request $request): View
    {
        $tanggal = trim($request->string('tanggal')->toString());
        $barangId = $request->integer('barang_id');

        $mutations = MutasiStok::query()
            ->with(['barang', 'user'])
            ->when($tanggal !== '', function ($query) use ($tanggal): void {
                $query->whereDate('tanggal', $tanggal);
            })
            ->when($barangId > 0, function ($query) use ($barangId): void {
                $query->where('barang_id', $barangId);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('stok.riwayat', [
            'mutations' => $mutations,
            'items' => Barang::query()->orderBy('nama_barang')->get(),
            'selectedDate' => $tanggal,
            'selectedBarang' => $barangId,
        ]);
    }
}
