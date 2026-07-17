<div>
    @if(!$fromCart)
        <div class="mb-6">
            <a href="{{ route('filament.customer.pages.catalog') }}" class="group inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition font-medium">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Katalog
            </a>
        </div>
    @else
        <div class="mb-6">
            <a href="{{ route('filament.customer.pages.cart') }}" class="group inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition font-medium">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Keranjang
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm">
            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium">{{ session('success') }} <a href="{{ route('filament.customer.pages.history') }}" class="underline font-bold ml-1">Lihat Riwayat</a></span>
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

    @if($fromCart)
        @php
            $cartItems = $this->getCartItems();
            $cartTotal = $this->getCartTotal();
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/80 bg-gradient-to-r from-emerald-50/80 to-transparent dark:from-emerald-900/10 dark:to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Checkout Keranjang</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ count($cartItems) }} item akan dibeli</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($cartItems as $item)
                        @php
                            $product = \App\Models\Cat::with(['merek'])->find($item['cat_id']);
                            if (!$product) continue;
                            $subtotal = $product->harga * $item['jumlah'];
                        @endphp
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gradient-to-b from-gray-100 to-gray-200 dark:from-gray-600 dark:to-gray-700 shrink-0">
                                @if($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $product->nama }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item['jumlah'] }} x Rp{{ number_format($product->harga) }}</p>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white shrink-0">Rp{{ number_format($subtotal) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">Total Pembayaran</span>
                    <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp{{ number_format($cartTotal) }}</span>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button wire:click="submit" class="group inline-flex items-center justify-center gap-2.5 bg-emerald-600 text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Konfirmasi Pembelian
                    </button>
                    <a href="{{ route('filament.customer.pages.cart') }}" class="inline-flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 px-6 py-3.5 rounded-2xl font-medium hover:bg-gray-100 dark:hover:bg-gray-700/50 transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/80 bg-gradient-to-r from-emerald-50/80 to-transparent dark:from-emerald-900/10 dark:to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Checkout</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi data pembelian Anda</p>
                    </div>
                </div>
            </div>
            <div class="p-6 md:p-8">
                <form wire:submit="submit">
                    {{ $this->form }}

                    <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <button type="submit" class="group inline-flex items-center justify-center gap-2.5 bg-emerald-600 text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi Pembelian
                        </button>
                        <a href="{{ route('filament.customer.pages.catalog') }}" class="inline-flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 px-6 py-3.5 rounded-2xl font-medium hover:bg-gray-100 dark:hover:bg-gray-700/50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
