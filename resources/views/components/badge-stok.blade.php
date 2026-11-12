@props([
    'stok' => 0,
    'stokMinimum' => 0,
])

@php
    if ((int) $stok === 0) {
        $label = 'Habis';
        $classes = 'bg-danger/10 text-danger';
    } elseif ((int) $stok <= (int) $stokMinimum) {
        $label = 'Menipis';
        $classes = 'bg-warning/10 text-warning';
    } else {
        $label = 'Aman';
        $classes = 'bg-success/10 text-success';
    }
@endphp

<span {{ $attributes->merge(['class' => $classes.' inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold']) }}>
    {{ $label }}
</span>
