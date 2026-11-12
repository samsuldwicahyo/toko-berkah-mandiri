@props([
    'type' => 'success',
    'title' => null,
    'message' => null,
])

@php
    $styles = [
        'success' => [
            'wrapper' => 'border-success/20 bg-success/10 text-success',
            'icon' => 'text-success',
        ],
        'danger' => [
            'wrapper' => 'border-danger/20 bg-danger/10 text-danger',
            'icon' => 'text-danger',
        ],
        'warning' => [
            'wrapper' => 'border-warning/20 bg-warning/10 text-warning',
            'icon' => 'text-warning',
        ],
        'info' => [
            'wrapper' => 'border-primary-light bg-primary-light/60 text-primary-dark',
            'icon' => 'text-primary-dark',
        ],
    ][$type] ?? [
        'wrapper' => 'border-success/20 bg-success/10 text-success',
        'icon' => 'text-success',
    ];

    $content = $message ?? trim((string) $slot);
@endphp

@if ($content !== '')
    <div {{ $attributes->merge(['class' => $styles['wrapper'].' flex items-start gap-3 rounded-xl border px-4 py-3 shadow-sm']) }}>
        <div class="{{ $styles['icon'] }} mt-0.5">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.28-9.22a.75.75 0 0 0-1.06-1.06L9.25 10.69 7.78 9.22a.75.75 0 0 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l3.5-3.5Z" clip-rule="evenodd" />
            </svg>
        </div>

        <div class="space-y-1">
            @if ($title)
                <p class="text-sm font-semibold">{{ $title }}</p>
            @endif

            <p class="text-sm">{{ $content }}</p>
        </div>
    </div>
@endif
