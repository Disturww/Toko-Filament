<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Riwayat Belanja</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Semua transaksi pembelian Anda</p>
        </div>
        <a href="{{ route('filament.customer.pages.catalog') }}" class="group inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-2xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20 hover:shadow-lg hover:-translate-y-0.5">
            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            Beli Lagi
        </a>
    </div>

    @php
        $penjualans = $this->getPenjualans();
    @endphp

    @if($penjualans->isEmpty())
        <div class="text-center py-24">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-1">Belum ada riwayat belanja</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-5">Pembelian Anda akan muncul di sini</p>
            <a href="{{ route('filament.customer.pages.catalog') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($penjualans as $p)
                @php
                    $colors = getCatSwatchColors($p->cat->warna);
                @endphp
                <a href="{{ route('filament.customer.pages.transaction-detail') }}?id={{ $p->id }}" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-5 hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-gray-900/50 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-750 overflow-hidden">
                            @if($p->cat->gambar)
                                <img src="{{ asset('storage/' . $p->cat->gambar) }}" alt="{{ $p->cat->nama }}" class="w-full h-full object-cover"/>
                            @else
                                <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $p->cat->nama }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300">Selesai</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $p->tanggal_penjualan->format('d M Y') }} &middot; {{ $p->jumlah }} unit &middot; Rp{{ number_format($p->harga_satuan) }}/unit</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-extrabold text-gray-900 dark:text-white text-lg">Rp{{ number_format($p->total_harga) }}</p>
                            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors ml-auto mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
