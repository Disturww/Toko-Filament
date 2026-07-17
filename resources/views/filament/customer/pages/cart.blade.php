<div>
    @php
        $cartItems = $this->getCartItems();
        $cartTotal = $this->getCartTotal();
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Keranjang Belanja</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ count($cartItems) }} item di keranjang</p>
        </div>
        @if(count($cartItems) > 0)
            <div class="flex gap-2">
                <button wire:click="clearCart" onclick="return confirm('Kosongkan keranjang?')" class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Kosongkan
                </button>
                <a href="{{ route('filament.customer.pages.checkout') }}?from_cart=1" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Checkout
                </a>
            </div>
        @endif
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
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(empty($cartItems))
        <div class="text-center py-24">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-1">Keranjang kosong</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-5">Tambahkan produk dari katalog untuk mulai belanja</p>
            <a href="{{ route('filament.customer.pages.catalog') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($cartItems as $index => $item)
                @php
                    $product = \App\Models\Cat::with(['merek'])->find($item['cat_id']);
                    if (!$product) continue;
                    $subtotal = $product->harga * $item['jumlah'];
                    $color = getCatSwatchColors($product->warna);
                @endphp
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-5 hover:shadow-lg hover:shadow-gray-200/40 dark:hover:shadow-gray-900/40 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-750 overflow-hidden">
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover"/>
                            @else
                                <svg class="w-7 h-7 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $product->nama }}</h3>
                                @if($product->merek)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 shrink-0">{{ $product->merek->nama }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                @if($product->warna)
                                    <span class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="w-3 h-3 rounded-full border-2 border-white dark:border-gray-600 shadow-sm inline-block" style="background-color: {{ $color['accent'] }}"></span>
                                        {{ ucfirst($product->warna) }}
                                    </span>
                                @endif
                                <span class="text-sm text-gray-400 dark:text-gray-500">&middot;</span>
                                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-semibold">Rp{{ number_format($product->harga) }}/unit</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden">
                                <button wire:click="updateQuantity({{ $index }}, {{ $item['jumlah'] - 1 }})" class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm font-bold">-</button>
                                <span class="px-3 py-2 text-sm font-bold text-gray-900 dark:text-white min-w-[40px] text-center">{{ $item['jumlah'] }}</span>
                                <button wire:click="updateQuantity({{ $index }}, {{ $item['jumlah'] + 1 }})" class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm font-bold">+</button>
                            </div>

                            <div class="text-right min-w-[100px]">
                                <p class="font-extrabold text-gray-900 dark:text-white">Rp{{ number_format($subtotal) }}</p>
                            </div>

                            <button wire:click="removeFromCart({{ $index }})" onclick="return confirm('Hapus dari keranjang?')" class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 p-6">
            <div class="flex items-center justify-between">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Total</span>
                <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp{{ number_format($cartTotal) }}</span>
            </div>
            <div class="mt-4">
                <a href="{{ route('filament.customer.pages.checkout') }}?from_cart=1" class="w-full group inline-flex items-center justify-center gap-2.5 bg-emerald-600 text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Checkout Semua
                </a>
            </div>
        </div>
    @endif
</div>
