<div>
    @php
        $products = $this->getProducts();
        $mereks = $this->getMereks();
        $warnas = $this->getWarnas();
    @endphp

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm">
            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium">{{ session('success') }} <a href="{{ route('filament.customer.pages.cart') }}" class="underline font-bold ml-1">Lihat Keranjang</a></span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-2xl shadow-sm">
            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/40 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $products->total() }} produk tersedia</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-4 mb-6 shadow-sm">
        <div class="flex flex-col lg:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk, merek, warna..." class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select wire:model.live="filterMerek" class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition min-w-[160px]">
                <option value="">Semua Merek</option>
                @foreach($mereks as $merek)
                    <option value="{{ $merek->id }}">{{ $merek->nama }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterWarna" class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition min-w-[160px]">
                <option value="">Semua Warna</option>
                @foreach($warnas as $warna)
                    <option value="{{ $warna }}">{{ ucfirst($warna) }}</option>
                @endforeach
            </select>

            <select wire:model.live="sortBy" class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition min-w-[160px]">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="price_asc">Harga Terendah</option>
                <option value="price_desc">Harga Tertinggi</option>
                <option value="name_asc">Nama A-Z</option>
                <option value="name_desc">Nama Z-A</option>
            </select>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-24">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">Tidak ada produk ditemukan</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($products as $product)
                @php
                    $color = getCatSwatchColors($product->warna);
                @endphp
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-gray-900/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="h-52 flex items-center justify-center relative overflow-hidden bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
                        @else
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                        @endif
                        @if($product->stok <= 5)
                            <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-lg shadow-red-500/30">Stok Habis</span>
                        @elseif($product->stok <= 10)
                            <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-400 text-amber-900 shadow-lg shadow-amber-400/30">Stok Sedikit</span>
                        @endif
                    </div>
                    <div class="p-5">
                        @if($product->merek)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $product->merek->nama }}</span>
                        @endif
                        <h3 class="font-bold text-gray-900 dark:text-white mt-2.5 line-clamp-2 text-[15px] leading-snug">{{ $product->nama }}</h3>
                        @if($product->warna)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5 flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded-full border-2 border-white dark:border-gray-600 shadow-sm inline-block" style="background-color: {{ $color['accent'] }}"></span>
                                {{ ucfirst($product->warna) }}
                            </p>
                        @endif
                        <div class="flex items-end justify-between mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                            <div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Harga</p>
                                <p class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp{{ number_format($product->harga) }}</p>
                            </div>
                            <button wire:click="addToCart({{ $product->id }})" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 active:scale-95 transition-all shadow-md shadow-emerald-600/20 hover:shadow-lg hover:shadow-emerald-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
