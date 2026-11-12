@props([
    'title',
    'value',
    'description' => null,
    'icon' => null,
    'tone' => 'primary',
])

@php
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary-dark',
        'accent' => 'bg-accent/10 text-warning',
        'danger' => 'bg-danger/10 text-danger',
        'warning' => 'bg-warning/10 text-warning',
        'success' => 'bg-success/10 text-success',
    ][$tone] ?? 'bg-primary-light text-primary-dark';
@endphp

<div {{ $attributes->merge(['class' => 'app-card p-5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-2">
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900">{{ $value }}</p>

            @if ($description)
                <p class="text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>

        <div class="{{ $toneClasses }} flex h-12 w-12 items-center justify-center rounded-2xl">
            @if ($icon)
                {!! $icon !!}
            @else
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V8.25M10 19.5V4.5M16 19.5v-7.125M22 19.5V10.5" />
                </svg>
            @endif
        </div>
    </div>
</div>
