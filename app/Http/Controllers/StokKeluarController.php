<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StokKeluarController extends Controller
{
    /**
     * Show the outgoing stock form.
     */
    public function create(): View
    {
        return view('stok.keluar', [
            'items' => Barang::query()
                ->with('kategori')
                ->orderBy('nama_barang')
                ->get(),
        ]);
    }

    /**
     * Store a newly created outgoing stock mutation.
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
            'jumlah.required' => 'Jumlah stok keluar wajib diisi.',
            'jumlah.integer' => 'Jumlah stok keluar harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah stok keluar minimal 1.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal.date' => 'Tanggal transaksi tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $request): void {
            $barang = Barang::query()
                ->lockForUpdate()
                ->findOrFail($validated['barang_id']);

            $stokSebelum = (int) $barang->stok;
            $jumlahKeluar = (int) $validated['jumlah'];

            if ($jumlahKeluar > $stokSebelum) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah stok keluar tidak boleh melebihi stok yang tersedia. Stok saat ini: '.$stokSebelum.'.',
                ]);
            }

            $stokSesudah = $stokSebelum - $jumlahKeluar;

            $barang->update([
                'stok' => $stokSesudah,
            ]);

            MutasiStok::create([
                'barang_id' => $barang->id,
                'user_id' => $request->user()->id,
                'tipe' => 'keluar',
                'jumlah' => $jumlahKeluar,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal' => $validated['tanggal'],
            ]);
        });

        return redirect()
            ->route('stok.keluar')
            ->with('success', 'Stok keluar berhasil disimpan.');
    }
}
