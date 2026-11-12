@props([
    'action',
    'buttonLabel',
    'category' => null,
    'isEdit' => false,
])

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf

    @if ($isEdit)
        @method('PATCH')
    @endif

    <div class="space-y-2">
        <label for="nama_kategori" class="text-sm font-semibold text-gray-700">Nama Kategori</label>
        <input
            id="nama_kategori"
            type="text"
            name="nama_kategori"
            value="{{ old('nama_kategori', $category?->nama_kategori) }}"
            placeholder="Contoh: Sembako"
            maxlength="100"
            required
            class="app-input"
        >
        <p class="text-sm text-gray-500">
            Nama kategori harus unik dan akan dipakai untuk pengelompokan barang.
        </p>
        <x-input-error :messages="$errors->get('nama_kategori')" class="mt-2" />
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="submit" class="app-button-primary">
            {{ $buttonLabel }}
        </button>

        <a href="{{ route('kategori.index') }}" class="app-button-secondary">
            Batal
        </a>
    </div>
</form>
