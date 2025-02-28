<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')

        {{-- @sn todo 这里要把 tailwindcss 移除 --}}
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="antialiased min-h-screen bg-gray-50 font-normal text-gray-950 dark:bg-gray-950 dark:text-white">
        @livewire('sn-shop-navigation')

        {{ $slot }}

        @livewire('notifications')
        {{-- @livewire('database-notifications') --}}

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
