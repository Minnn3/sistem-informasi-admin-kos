<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Informasi Kos') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass-panel {
                background: rgba(17, 24, 39, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    </head>
    <body class="font-sans text-gray-100 antialiased selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen flex w-full">
            <!-- Left Side / Split Screen Image -->
            <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2070&auto=format&fit=crop');">
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 to-gray-900/40"></div>
                <div class="absolute bottom-0 left-0 p-12 text-white z-10 w-full mb-10">
                    <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl mb-8 transform transition hover:scale-110 duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-extrabold mb-4 leading-tight">Kelola Kos<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Lebih Modern</span></h1>
                    <p class="text-lg text-gray-300 max-w-md mt-4">Sistem Informasi Kos terintegrasi. Pantau data kamar, manajemen penyewa, hingga transaksi dengan satu klik.</p>
                </div>
            </div>

            <!-- Right Side / Form Container -->
            <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-900 relative overflow-hidden">
                <!-- Decorative blurred orbs -->
                <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-70 animate-blob"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-70 animate-blob" style="animation-delay: 2s;"></div>
                
                <div class="w-full max-w-md px-8 py-10 z-10">
                    <div class="flex lg:hidden flex-col items-center mb-10">
                        <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-widest">KOS<span class="text-blue-500">KU</span></h2>
                    </div>

                    <div class="glass-panel p-8 sm:p-10 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all hover:border-white/20">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
