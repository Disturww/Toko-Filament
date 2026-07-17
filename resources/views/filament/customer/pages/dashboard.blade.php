<div>
    @php
        $penjualans = $this->getPenjualans();
        $totalBelanja = $penjualans->sum('total_harga');
        $totalItem = $penjualans->sum('jumlah');
    @endphp

    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500 rounded-3xl mb-8 p-8 md:p-10 shadow-xl shadow-emerald-600/20">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white/90 text-xs font-medium px-3 py-1 rounded-full mb-4">
                <span class="w-1.5 h-1.5 bg-emerald-300 rounded-full animate-pulse"></span>
                Toko Cat
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2 tracking-tight">Selamat Datang,<br class="hidden md:block"> {{ auth()->guard('pelanggan')->user()->nama }}!</h1>
            <p class="text-emerald-100/80 text-sm md:text-base mb-6 max-w-md">Temukan cat berkualitas untuk kebutuhan rumah dan proyek Anda</p>
            <a href="{{ route('filament.customer.pages.catalog') }}" class="group inline-flex items-center gap-2.5 bg-white text-emerald-700 font-bold px-7 py-3.5 rounded-2xl hover:bg-emerald-50 transition-all duration-300 shadow-lg shadow-emerald-800/20 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Mulai Belanja
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
        <div class="absolute -right-12 -top-12 w-56 h-56 bg-white/10 rounded-full blur-sm"></div>
        <div class="absolute -right-4 -bottom-8 w-40 h-40 bg-white/5 rounded-full blur-sm"></div>
        <div class="absolute right-24 top-8 w-20 h-20 bg-white/10 rounded-full blur-sm hidden md:block"></div>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm">
            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-2xl shadow-sm">
            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/40 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-5 flex items-center gap-4 hover:shadow-lg hover:shadow-gray-200/40 dark:hover:shadow-gray-900/40 transition-all duration-300 hover:-translate-y-0.5">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-900/10 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform">
                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $penjualans->count() }}</p>
            </div>
        </div>
        <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-5 flex items-center gap-4 hover:shadow-lg hover:shadow-gray-200/40 dark:hover:shadow-gray-900/40 transition-all duration-300 hover:-translate-y-0.5">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-900/10 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform">
                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Item</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($totalItem) }}</p>
            </div>
        </div>
        <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-5 flex items-center gap-4 hover:shadow-lg hover:shadow-gray-200/40 dark:hover:shadow-gray-900/40 transition-all duration-300 hover:-translate-y-0.5">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-900/10 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform">
                <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Belanja</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Rp{{ number_format($totalBelanja) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-700/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="font-bold text-gray-900 dark:text-white">Pesanan Terbaru</h2>
            </div>
            @if($penjualans->isNotEmpty())
                <a href="{{ route('filament.customer.pages.history') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        @if($penjualans->isEmpty())
            <div class="p-14 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700/50 rounded-3xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-medium mb-1">Belum ada pesanan</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mb-4">Mulai belanja untuk melihat pesanan di sini</p>
                <a href="{{ route('filament.customer.pages.catalog') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @foreach($penjualans->take(5) as $p)
                    @php $swatch = getCatSwatchColors($p->cat->warna); @endphp
                    <a href="{{ route('filament.customer.pages.transaction-detail') }}?id={{ $p->id }}" class="group flex items-center gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-750 overflow-hidden">
                            @if($p->cat->gambar)
                                <img src="{{ asset('storage/' . $p->cat->gambar) }}" alt="{{ $p->cat->nama }}" class="w-full h-full object-cover"/>
                            @else
                                <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $p->cat->nama }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $p->tanggal_penjualan->format('d M Y') }} &middot; {{ $p->jumlah }} unit</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-bold text-gray-900 dark:text-white">Rp{{ number_format($p->total_harga) }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 mt-0.5">Selesai</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
