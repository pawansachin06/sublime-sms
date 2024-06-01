<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BGC SMS Portal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.scss', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        @php
        $version = date('Y-m-d-h-i-s');
        $stylesArr = [
            'reboot' => '/css/reboot.css?v=1',
            'global' => '/css/global.css?v=' . $version,
            'toastify' => '/css/lib/toastify.min.css?v=1.12.0',
            'inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@500;600;800&display=swap',
            'widMadeForDisplay' => 'https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;600;700;800&display=swap',
        ];
        @endphp
        @foreach($stylesArr as $stylePath)
            @if( !empty($stylePath) )
                <link rel="preload" as="style" href="{{ $stylePath }}" />
                <link rel="stylesheet" href="{{ $stylePath }}" />
            @endif
        @endforeach

    </head>
    <body class="antialiased bg-gray-100">
        <x-banner />
        @livewire('navigation-menu')
        <main>
            {{ $slot }}
        </main>
        @stack('modals')
        @livewireScripts
    </body>
</html>
