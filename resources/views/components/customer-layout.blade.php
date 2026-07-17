@php
    $customer = \Filament\Facades\Filament::auth()->user();
    $currentUrl = request()->url();
    $cartCount = count(session()->get('cart', []));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Toko Cat') }}</title>
    <style>[x-cloak]{display:none!important}</style>
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 min-h-screen">
    {{-- E-Commerce Navbar --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200/80 dark:border-gray-800/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('filament.customer.pages.dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-md shadow-emerald-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <span class="text-lg font-extrabold text-gray-900 dark:text-white tracking-tight hidden sm:block">Toko Cat</span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('filament.customer.pages.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        Beranda
                    </a>
                    <a href="{{ route('filament.customer.pages.catalog') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/catalog') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        Katalog
                    </a>
                    <a href="{{ route('filament.customer.pages.history') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/history') || str_contains($currentUrl, '/transaction') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        Riwayat
                    </a>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    @if($customer)
                        {{-- Cart Icon --}}
                        <a href="{{ route('filament.customer.pages.cart') }}" class="relative p-2 rounded-xl transition {{ str_contains($currentUrl, '/cart') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" title="Keranjang">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                            @endif
                        </a>

                        <div class="hidden sm:flex items-center gap-2.5 pl-3 border-l border-gray-200 dark:border-gray-700">
                            <a href="{{ route('filament.customer.pages.profile') }}" class="flex items-center gap-2.5 hover:opacity-80 transition">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ strtoupper(substr($customer->nama, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 max-w-[100px] truncate">{{ $customer->nama }}</span>
                            </a>
                        </div>
                        <form method="POST" action="{{ route('filament.customer.auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    @endif

                    {{-- Mobile menu toggle --}}
                    <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200/80 dark:border-gray-800/80 bg-white dark:bg-gray-900">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('filament.customer.pages.dashboard') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">Beranda</a>
                <a href="{{ route('filament.customer.pages.catalog') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/catalog') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">Katalog</a>
                <a href="{{ route('filament.customer.pages.cart') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/cart') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">
                    <span>Keranjang</span>
                    @if($cartCount > 0)
                        <span class="w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('filament.customer.pages.history') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ str_contains($currentUrl, '/history') || str_contains($currentUrl, '/transaction') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">Riwayat</a>
                @if($customer)
                    <div class="border-t border-gray-100 dark:border-gray-800 pt-2 mt-2">
                        <a href="{{ route('filament.customer.pages.profile') }}" class="flex items-center gap-2.5 px-4 py-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($customer->nama, 0, 1)) }}</div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $customer->nama }}</span>
                        </a>
                        <form method="POST" action="{{ route('filament.customer.auth.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">Keluar</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200/80 dark:border-gray-800/80 bg-white dark:bg-gray-900 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Toko Cat</span>
                </div>
                <p class="text-sm text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} Toko Cat. Hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    @filamentScripts
    @stack('scripts')
</body>
</html>
