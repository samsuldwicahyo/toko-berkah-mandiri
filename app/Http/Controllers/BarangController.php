<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Display the item listing.
     */
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $selectedKategori = $request->integer('kategori_id');

        $barangs = Barang::query()
            ->with('kategori')
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
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('barang.index', [
            'barangs' => $barangs,
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'search' => $search,
            'selectedKategori' => $selectedKategori,
        ]);
    }

    /**
     * Show the form for creating a new item.
     */
    public function create(): View
    {
        return view('barang.create', [
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'nextKodeBarang' => $this->generateKodeBarang(),
        ]);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBarang($request);
        $validated['kode_barang'] = $this->generateKodeBarang();
        $validated['gambar'] = $this->storeImage($request);

        Barang::create($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Barang $barang): View
    {
        return view('barang.edit', [
            'barang' => $barang,
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
        ]);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $validated = $this->validateBarang($request);
        unset($validated['gambar']);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $validated['gambar'] = $this->storeImage($request);
        }

        $barang->update($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Validate incoming item data.
     *
     * @return array<string, mixed>
     */
    private function validateBarang(Request $request): array
    {
        return $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'max:2048'],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 150 karakter.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'satuan.required' => 'Satuan wajib diisi.',
            'satuan.max' => 'Satuan maksimal 20 karakter.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.integer' => 'Harga beli harus berupa angka bulat.',
            'harga_beli.min' => 'Harga beli tidak boleh kurang dari 0.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.integer' => 'Harga jual harus berupa angka bulat.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'stok_minimum.integer' => 'Stok minimum harus berupa angka bulat.',
            'stok_minimum.min' => 'Stok minimum tidak boleh kurang dari 0.',
            'gambar.image' => 'File gambar harus berupa gambar yang valid.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);
    }

    /**
     * Generate the next available item code.
     */
    private function generateKodeBarang(): string
    {
        $nextNumber = ((int) Barang::query()->max('id')) + 1;

        return 'BRG-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store the uploaded image when available.
     */
    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('gambar')) {
            return null;
        }

        return $request->file('gambar')->store('barangs', 'public');
    }
}
