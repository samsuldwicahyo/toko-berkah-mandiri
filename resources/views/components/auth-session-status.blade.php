@props(['status'])

@if ($status)
    <x-alert type="success" :message="$status" {{ $attributes }} />
@endif
