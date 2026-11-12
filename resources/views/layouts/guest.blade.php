@php
    $appName = 'Toko Berkah Mandiri';
    $titleValue = isset($title) ? trim((string) $title) : trim((string) $__env->yieldContent('title'));
    $documentTitle = $titleValue !== '' ? $titleValue.' - '.$appName : $appName;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $documentTitle }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900">
        <div class="relative min-h-screen overflow-hidden bg-surface">
            <div class="absolute inset-y-0 left-0 hidden w-1/2 bg-primary/5 blur-3xl lg:block"></div>
            <div class="absolute -top-24 right-0 h-64 w-64 rounded-full bg-accent/15 blur-3xl"></div>

            <div class="relative flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
                <div class="w-full max-w-6xl">
                    @if (isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
