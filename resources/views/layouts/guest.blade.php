<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .login-bg {
                background-image: url('{{ asset("img/fondo.jpg") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
            .login-overlay {
                background-color: rgba(0, 0, 0, 0.45);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="login-bg min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="login-overlay absolute inset-0"></div>

            <div class="relative z-10 flex flex-col items-center w-full">
                <div class="mb-4">
                    <a href="/">
                        <img src="{{ asset('img/logo-fruna.png') }}"
                             alt="VoicePOS Fruna"
                             class="object-contain"
                             style="width: auto; height: auto; max-width: 200px; max-height: 120px;">
                    </a>
                </div>

                <div class="w-full sm:max-w-md px-6 py-6 bg-white bg-opacity-95 shadow-lg overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>