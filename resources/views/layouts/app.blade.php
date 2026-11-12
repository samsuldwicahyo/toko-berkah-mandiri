@php
    $appName = 'Toko Berkah Mandiri';
    $titleValue = isset($title) ? trim((string) $title) : trim((string) $__env->yieldContent('title'));
    $headerValue = isset($header) ? trim(strip_tags((string) $header)) : trim(strip_tags((string) $__env->yieldContent('header')));
    $pageTitleValue = isset($pageTitle) ? trim((string) $pageTitle) : trim((string) $__env->yieldContent('page_title'));
    $pageTitleValue = $pageTitleValue !== '' ? $pageTitleValue : ($headerValue !== '' ? $headerValue : 'Dashboard');
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
    <body class="font-sans">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-surface">
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity
                class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
                @click="sidebarOpen = false"
            ></div>

            <div class="flex min-h-screen">
                <aside
                    class="app-shell-sidebar fixed inset-y-0 left-0 z-40 w-64 transform bg-primary transition duration-200 ease-out lg:translate-x-0"
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                >
                    <x-sidebar />
                </aside>

                <div class="app-shell-content flex min-h-screen flex-1 flex-col lg:pl-64">
                    <div class="app-shell-navbar">
                        <x-navbar :title="$pageTitleValue" />
                    </div>

                    <main class="app-shell-main flex-1 px-4 py-6 sm:px-6 lg:px-8">
                        <div class="app-shell-container mx-auto flex w-full max-w-7xl flex-col gap-4">
                            @if (session('success'))
                                <x-alert type="success" :message="session('success')" />
                            @endif

                            @if (session('error'))
                                <x-alert type="danger" :message="session('error')" />
                            @endif

                            @if (isset($slot))
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
