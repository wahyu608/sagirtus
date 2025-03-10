<!DOCTYPE html>
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
            <script>
                // untuk menghindari blink white
                if (
                    localStorage.getItem("color-theme") === "dark" ||
                    (!("color-theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)
                ) {
                    document.documentElement.classList.remove("dark");
                }
            </script>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="csrf-token" content="{{ csrf_token() }}">
        
                <title>{{ config('app.name', 'Laravel') }}</title>
        
                <!-- Fonts -->
                <link rel="preconnect" href="https://fonts.bunny.net">
                <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
                <!-- Scripts -->
                @vite(['resources/css/app.css', 'resources/js/app.js'])
                <style>
                    [x-cloack]{ display: none !important; }
                </style>
            </head>
            <body class="h-full flex flex-col">
                <div class="flex-grow bg-gray-100 dark:bg-gray-900">
                    @include('layouts.navigationGuest')
        
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset
        
                    <!-- Page Content -->
                    <main class="flex-grow">
                        {{ $slot }}
                    </main>
                </div>
                @include('layouts.footer')
            </body>
        </html>
