<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StokMasukController extends Controller
{
    /**
     * Show the incoming stock form.
     */
    public function create(): View
    {
        return view('stok.masuk', [
            'items' => Barang::query()
                ->with('kategori')
                ->orderBy('nama_barang')
                ->get(),
        ]);
    }

    /**
     * Store a newly created incoming stock mutation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barangs,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
        ], [
            'barang_id.required' => 'Barang wajib dipilih.',
            'barang_id.exists' => 'Barang yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah stok masuk wajib diisi.',
            'jumlah.integer' => 'Jumlah stok masuk harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah stok masuk minimal 1.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal.date' => 'Tanggal transaksi tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $request): void {
            $barang = Barang::query()
                ->lockForUpdate()
                ->findOrFail($validated['barang_id']);

            $stokSebelum = (int) $barang->stok;
            $stokSesudah = $stokSebelum + (int) $validated['jumlah'];

            $barang->update([
                'stok' => $stokSesudah,
            ]);

            MutasiStok::create([
                'barang_id' => $barang->id,
                'user_id' => $request->user()->id,
                'tipe' => 'masuk',
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal' => $validated['tanggal'],
            ]);
        });

        return redirect()
            ->route('stok.masuk')
            ->with('success', 'Stok masuk berhasil disimpan.');
    }
}
