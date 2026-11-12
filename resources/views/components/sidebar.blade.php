@php
    $user = auth()->user();
    $menuItems = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard'],
        ['label' => 'Kategori', 'route' => 'kategori.index', 'pattern' => 'kategori.*'],
        ['label' => 'Barang', 'route' => 'barang.index', 'pattern' => 'barang.*'],
        ['label' => 'Stok Masuk', 'route' => 'stok.masuk', 'pattern' => 'stok.masuk'],
        ['label' => 'Stok Keluar', 'route' => 'stok.keluar', 'pattern' => 'stok.keluar'],
        ['label' => 'Riwayat Stok', 'route' => 'stok.riwayat', 'pattern' => 'stok.riwayat'],
        ['label' => 'Laporan Stok Menipis', 'route' => 'laporan.stok-menipis', 'pattern' => 'laporan.stok-menipis'],
        ['label' => 'Manajemen User', 'route' => 'user.index', 'pattern' => 'user.*', 'admin_only' => true],
    ];
@endphp

<div class="flex h-full flex-col">
    <div class="flex items-center justify-between border-b border-white/10 px-5 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 text-white">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V8.2c0-.5.2-.98.56-1.32l5.88-5.53a.8.8 0 0 1 1.11 0l5.89 5.53c.35.34.56.82.56 1.32V21M9 21v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5M9 10h.01M15 10h.01" />
                </svg>
            </div>

            <div>
                <p class="text-sm font-semibold text-white">Toko Berkah Mandiri</p>
                <p class="text-xs text-primary-light">Inventory & stok barang</p>
            </div>
        </div>

        <button
            type="button"
            class="rounded-xl p-2 text-white/80 transition hover:bg-white/10 hover:text-white lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Tutup sidebar"
        >
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M4.22 4.22a.75.75 0 0 1 1.06 0L10 8.94l4.72-4.72a.75.75 0 1 1 1.06 1.06L11.06 10l4.72 4.72a.75.75 0 1 1-1.06 1.06L10 11.06l-4.72 4.72a.75.75 0 0 1-1.06-1.06L8.94 10 4.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5">
        @foreach ($menuItems as $item)
            @if (($item['admin_only'] ?? false) && ($user?->role !== 'admin'))
                @continue
            @endif

            @php
                $isActive = request()->routeIs($item['pattern']);
                $isAvailable = \Illuminate\Support\Facades\Route::has($item['route']);
                $href = $isAvailable ? route($item['route']) : '#';
            @endphp

            <a
                href="{{ $href }}"
                @if (! $isAvailable) aria-disabled="true" @endif
                class="{{ $isActive ? 'bg-white/15 text-white shadow-sm' : 'text-primary-light hover:bg-white/10 hover:text-white' }} flex items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition"
            >
                <span>{{ $item['label'] }}</span>

                @if (! $isAvailable)
                    <span class="rounded-full bg-white/10 px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-white/80">
                        Segera
                    </span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="border-t border-white/10 px-4 py-4">
        <div class="mb-3 rounded-xl bg-white/10 px-4 py-3 text-xs text-primary-light">
            {{ $user?->role === 'admin' ? 'Akun admin dapat mengakses seluruh modul inventory.' : 'Akun staff dapat mengelola mutasi stok dan melihat laporan.' }}
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="flex w-full items-center justify-center rounded-xl border border-white/15 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
            >
                Logout
            </button>
        </form>
    </div>
</div>
