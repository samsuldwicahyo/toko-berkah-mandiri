@props([
    'action',
    'buttonLabel',
    'barang' => null,
    'categories',
    'kodeBarang',
    'isEdit' => false,
])

@php
    $hasCategories = $categories->isNotEmpty();
    $imageUrl = $barang?->gambar ? \Illuminate\Support\Facades\Storage::disk('public')->url($barang->gambar) : null;
@endphp

@if (! $hasCategories)
    <x-alert
        type="warning"
        title="Kategori Belum Tersedia"
        message="Tambahkan minimal satu kategori terlebih dahulu sebelum membuat barang baru."
    />
@endif

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    @if ($isEdit)
        @method('PATCH')
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="kode_barang_preview" class="text-sm font-semibold text-gray-700">Kode Barang</label>
            <input
                id="kode_barang_preview"
                type="text"
                value="{{ $kodeBarang }}"
                disabled
                class="app-input bg-gray-50 text-gray-500"
            >
            <p class="text-sm text-gray-500">
                {{ $isEdit ? 'Kode barang tetap dan tidak dapat diubah.' : 'Kode barang dibuat otomatis saat data disimpan.' }}
            </p>
        </div>

        <div class="space-y-2">
            <label for="nama_barang" class="text-sm font-semibold text-gray-700">Nama Barang</label>
            <input
                id="nama_barang"
                type="text"
                name="nama_barang"
                value="{{ old('nama_barang', $barang?->nama_barang) }}"
                placeholder="Contoh: Minyak Goreng 1L"
                maxlength="150"
                required
                class="app-input"
            >
            <x-input-error :messages="$errors->get('nama_barang')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <label for="kategori_id" class="text-sm font-semibold text-gray-700">Kategori</label>
            <select
                id="kategori_id"
                name="kategori_id"
                required
                class="app-input"
                @disabled(! $hasCategories)
            >
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected((string) old('kategori_id', $barang?->kategori_id) === (string) $category->id)
                    >
                        {{ $category->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <label for="satuan" class="text-sm font-semibold text-gray-700">Satuan</label>
            <input
                id="satuan"
                type="text"
                name="satuan"
                list="daftar-satuan"
                value="{{ old('satuan', $barang?->satuan) }}"
                placeholder="pcs / kg / dus"
                maxlength="20"
                required
                class="app-input"
            >
            <datalist id="daftar-satuan">
                <option value="pcs"></option>
                <option value="kg"></option>
                <option value="dus"></option>
                <option value="liter"></option>
                <option value="sak"></option>
                <option value="pack"></option>
            </datalist>
            <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <label for="harga_beli" class="text-sm font-semibold text-gray-700">Harga Beli</label>
            <input
                id="harga_beli"
                type="number"
                name="harga_beli"
                value="{{ old('harga_beli', $barang?->harga_beli ?? 0) }}"
                min="0"
                step="1"
                required
                class="app-input"
            >
            <x-input-error :messages="$errors->get('harga_beli')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <label for="harga_jual" class="text-sm font-semibold text-gray-700">Harga Jual</label>
            <input
                id="harga_jual"
                type="number"
                name="harga_jual"
                value="{{ old('harga_jual', $barang?->harga_jual ?? 0) }}"
                min="0"
                step="1"
                required
                class="app-input"
            >
            <x-input-error :messages="$errors->get('harga_jual')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <label for="stok" class="text-sm font-semibold text-gray-700">Stok</label>
            <input
                id="stok"
                type="number"
                name="stok"
                value="{{ old('stok', $barang?->stok ?? 0) }}"
                min="0"
                step="1"
                required
                class="app-input"
            >
            <x-input-error :messages="$errors->get('stok')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <label for="stok_minimum" class="text-sm font-semibold text-gray-700">Stok Minimum</label>
            <input
                id="stok_minimum"
                type="number"
                name="stok_minimum"
                value="{{ old('stok_minimum', $barang?->stok_minimum ?? 5) }}"
                min="0"
                step="1"
                required
                class="app-input"
            >
            <x-input-error :messages="$errors->get('stok_minimum')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="space-y-2">
            <label for="gambar" class="text-sm font-semibold text-gray-700">Foto Barang</label>
            <input
                id="gambar"
                type="file"
                name="gambar"
                accept="image/*"
                class="app-file-input"
            >
            <p class="text-sm text-gray-500">Opsional. Format gambar umum, maksimal 2 MB.</p>
            <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
        </div>

        <div class="app-card flex items-center gap-4 p-4">
            @if ($imageUrl)
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $barang?->nama_barang }}"
                    class="h-24 w-24 rounded-2xl object-cover"
                >
            @else
                <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-surface text-gray-400">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V6.75A1.75 1.75 0 0 1 4.75 5h14.5A1.75 1.75 0 0 1 21 6.75v10.5A1.75 1.75 0 0 1 19.25 19H4.75A1.75 1.75 0 0 1 3 17.25v-.75Zm0 0 4.9-4.9a1.5 1.5 0 0 1 2.12 0l1.98 1.98a1.5 1.5 0 0 0 2.12 0l.88-.88a1.5 1.5 0 0 1 2.12 0L21 15.6M8.5 9.5h.01" />
                    </svg>
                </div>
            @endif

            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-900">
                    {{ $imageUrl ? 'Preview foto saat ini' : 'Belum ada foto barang' }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $imageUrl ? 'Upload gambar baru jika ingin mengganti foto lama.' : 'Upload foto agar barang lebih mudah dikenali pada daftar inventory.' }}
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="submit" class="app-button-primary" @disabled(! $hasCategories)>
            {{ $buttonLabel }}
        </button>

        <a href="{{ route('barang.index') }}" class="app-button-secondary">
            Batal
        </a>
    </div>
</form>
