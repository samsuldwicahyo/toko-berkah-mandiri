@props(['title' => 'Dashboard'])

@php
    $user = auth()->user();
    $name = $user?->name ?? 'Pengguna';
    $initials = collect(explode(' ', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<header class="sticky top-0 z-20 border-b border-gray-200/80 bg-white/90 backdrop-blur">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50 lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Buka sidebar"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2.75 5A.75.75 0 0 1 3.5 4.25h13a.75.75 0 0 1 0 1.5h-13A.75.75 0 0 1 2.75 5Zm0 5A.75.75 0 0 1 3.5 9.25h13a.75.75 0 0 1 0 1.5h-13A.75.75 0 0 1 2.75 10Zm0 5a.75.75 0 0 1 .75-.75h13a.75.75 0 0 1 0 1.5h-13a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>
            </button>

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-primary">Inventory Toko</p>
                <h1 class="text-xl font-bold text-gray-900">{{ $title }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-white px-3 py-2 shadow-sm">
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-900">{{ $name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst($user?->role ?? 'staff') }}</p>
            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-light text-sm font-bold text-primary-dark">
                {{ $initials !== '' ? $initials : 'TB' }}
            </div>
        </div>
    </div>
</header>
