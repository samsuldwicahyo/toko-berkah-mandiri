@props([
    'action',
    'buttonLabel',
    'title',
    'description',
    'items',
    'type',
])

@php
    $selectedBarangId = (string) old('barang_id', '');
    $defaultDate = old('tanggal', now()->toDateString());
    $itemsPayload = $items->map(fn ($item) => [
        'id' => (string) $item->id,
        'kode' => $item->kode_barang,
        'nama' => $item->nama_barang,
        'kategori' => $item->kategori?->nama_kategori ?? '-',
        'stok' => (int) $item->stok,
        'satuan' => $item->satuan,
        'search' => mb_strtolower($item->kode_barang.' '.$item->nama_barang.' '.($item->kategori?->nama_kategori ?? '')),
    ])->values();
@endphp

@if ($errors->any())
    <x-alert type="danger" :message="$errors->first()" />
@endif

@if ($items->isEmpty())
    <x-alert
        type="warning"
        title="Belum Ada Barang"
        message="Tambahkan barang terlebih dahulu sebelum mencatat transaksi stok."
    />
@endif

<section class="app-card p-6">
    <div class="max-w-5xl space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        </div>

        <form method="POST" action="{{ $action }}" class="space-y-6">
            @csrf

            <div
                x-data="{
                    query: '',
                    selectedId: '{{ $selectedBarangId }}',
                    items: {{ \Illuminate\Support\Js::from($itemsPayload) }},
                    selectItem(id) { this.selectedId = String(id); },
                    isVisible(item) {
                        if (this.query.trim() === '') return true;
                        return item.search.includes(this.query.trim().toLowerCase());
                    },
                    get selectedItem() {
                        return this.items.find((item) => item.id === this.selectedId) ?? null;
                    }
                }"
                class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]"
            >
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label for="barang_search" class="text-sm font-semibold text-gray-700">Pilih Barang</label>
                        <input
                            id="barang_search"
                            type="text"
                            x-model="query"
                            placeholder="Cari nama, kode, atau kategori barang..."
                            class="app-input"
                            @disabled($items->isEmpty())
                        >
                        <input type="hidden" name="barang_id" :value="selectedId">
                        <x-input-error :messages="$errors->get('barang_id')" class="mt-2" />
                    </div>

                    <div class="max-h-80 space-y-3 overflow-y-auto rounded-2xl border border-gray-100 bg-white p-3">
                        <template x-for="item in items" :key="item.id">
                            <button
                                type="button"
                                x-show="isVisible(item)"
                                @click="selectItem(item.id)"
                                :class="selectedId === item.id
                                    ? 'border-primary bg-primary-light/60 ring-1 ring-primary/20'
                                    : 'border-gray-100 bg-white hover:border-primary/30 hover:bg-surface'"
                                class="flex w-full items-start justify-between rounded-2xl border px-4 py-3 text-left transition"
                            >
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-gray-900" x-text="item.nama"></p>
                                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500" x-text="item.kode"></p>
                                    <p class="text-sm text-gray-500" x-text="item.kategori"></p>
                                </div>

                                <div class="text-right text-sm text-gray-500">
                                    <p class="font-semibold text-gray-900" x-text="item.stok"></p>
                                    <p x-text="'stok ' + item.satuan"></p>
                                </div>
                            </button>
                        </template>

                        <div
                            x-show="items.every((item) => !isVisible(item))"
                            class="rounded-2xl border border-dashed border-gray-200 px-4 py-6 text-center text-sm text-gray-500"
                        >
                            Tidak ada barang yang cocok dengan pencarian.
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="app-card p-4">
                        <p class="text-sm font-semibold text-gray-900">Barang Terpilih</p>
                        <template x-if="selectedItem">
                            <div class="mt-3 space-y-2">
                                <p class="text-base font-semibold text-gray-900" x-text="selectedItem.nama"></p>
                                <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500" x-text="selectedItem.kode"></p>
                                <p class="text-sm text-gray-500" x-text="selectedItem.kategori"></p>
                                <div class="rounded-xl bg-surface px-3 py-3 text-sm text-gray-600">
                                    <span class="font-semibold text-gray-900" x-text="selectedItem.stok"></span>
                                    <span x-text="' stok tersedia (' + selectedItem.satuan + ')'"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="!selectedItem">
                            <p class="mt-3 text-sm text-gray-500">
                                Pilih salah satu barang dari daftar di sebelah kiri.
                            </p>
                        </template>
                    </div>

                    <div class="space-y-2">
                        <label for="jumlah" class="text-sm font-semibold text-gray-700">Jumlah</label>
                        <input
                            id="jumlah"
                            type="number"
                            name="jumlah"
                            value="{{ old('jumlah') }}"
                            min="1"
                            step="1"
                            required
                            class="app-input"
                            @disabled($items->isEmpty())
                        >
                        <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="tanggal" class="text-sm font-semibold text-gray-700">Tanggal</label>
                        <input
                            id="tanggal"
                            type="date"
                            name="tanggal"
                            value="{{ $defaultDate }}"
                            required
                            class="app-input"
                            @disabled($items->isEmpty())
                        >
                        <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="keterangan" class="text-sm font-semibold text-gray-700">Keterangan</label>
                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="4"
                            placeholder="{{ $type === 'masuk' ? 'Contoh: Kulakan dari agen, retur supplier, atau stok tambahan.' : 'Contoh: Terjual, rusak, kadaluarsa, atau sampel produk.' }}"
                            class="app-input"
                            @disabled($items->isEmpty())
                        >{{ old('keterangan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="app-button-primary" @disabled($items->isEmpty())>
                    {{ $buttonLabel }}
                </button>

                <a href="{{ route('dashboard') }}" class="app-button-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</section>
