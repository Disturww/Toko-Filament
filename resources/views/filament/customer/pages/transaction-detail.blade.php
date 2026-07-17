<div>
    <div class="mb-6">
        <a href="{{ route('filament.customer.pages.history') }}" class="group inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition font-medium">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Riwayat
        </a>
    </div>

    @if($penjualan)
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/80 overflow-hidden max-w-lg mx-auto shadow-xl shadow-gray-200/30 dark:shadow-gray-900/30">
            <div class="bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative z-10">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 w-[72px] h-[72px]">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-2xl font-extrabold text-white">Pembelian Berhasil</h2>
                    <p class="text-emerald-100/80 text-sm mt-1.5">Transaksi #{{ $penjualan->id }}</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                @php
                    $swatch = getCatSwatchColors($penjualan->cat->warna);
                    $bgColor = $swatch['bg'];
                    $textColor = $swatch['text'];
                @endphp

                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl mb-6 border border-gray-100 dark:border-gray-700/50">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-750 overflow-hidden">
                        @if($penjualan->cat->gambar)
                            <img src="{{ asset('storage/' . $penjualan->cat->gambar) }}" alt="{{ $penjualan->cat->nama }}" class="w-full h-full object-cover"/>
                        @else
                            <svg class="w-7 h-7 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $penjualan->cat->nama }}</h3>
                        @if($penjualan->cat->warna)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ ucfirst($penjualan->cat->warna) }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-0">
                    <div class="flex justify-between items-center py-3.5 border-b border-gray-100 dark:border-gray-700/50">
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Tanggal
                        </span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $penjualan->tanggal_penjualan->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3.5 border-b border-gray-100 dark:border-gray-700/50">
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Jumlah
                        </span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $penjualan->jumlah }} unit</span>
                    </div>
                    <div class="flex justify-between items-center py-3.5 border-b border-gray-100 dark:border-gray-700/50">
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Harga Satuan
                        </span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp{{ number_format($penjualan->harga_satuan) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-4 bg-gradient-to-r from-emerald-50 to-emerald-50/50 dark:from-emerald-900/20 dark:to-emerald-900/10 -mx-8 px-8 rounded-b-2xl mt-0">
                        <span class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Total Bayar
                        </span>
                        <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp{{ number_format($penjualan->total_harga) }}</span>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('filament.customer.pages.history') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-3.5 rounded-2xl font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat
                    </a>
                    <a href="{{ route('filament.customer.pages.catalog') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-4 py-3.5 rounded-2xl font-semibold hover:bg-emerald-700 transition shadow-md shadow-emerald-600/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Beli Lagi
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-24">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">Transaksi tidak ditemukan</p>
        </div>
    @endif
</div>
