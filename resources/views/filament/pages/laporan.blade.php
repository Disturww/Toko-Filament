<x-filament-panels::page>
    {{-- Filter Bar --}}
    <div class="mb-8 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex flex-wrap items-end gap-4 p-5">
            <div class="flex-1 min-w-[200px]">
                <label for="periode" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Periode</label>
                <select
                    id="periode"
                    wire:model.live="periode"
                    class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                >
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            @if($periode === 'custom')
            <div class="flex-1 min-w-[200px]">
                <label for="tanggal_mulai" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Dari</label>
                <input
                    type="date"
                    id="tanggal_mulai"
                    wire:model.live="tanggal_mulai"
                    class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                />
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="tanggal_akhir" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Sampai</label>
                <input
                    type="date"
                    id="tanggal_akhir"
                    wire:model.live="tanggal_akhir"
                    class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                />
            </div>
            @endif

            <div class="flex items-center gap-2">
                <button
                    wire:click="loadData"
                    type="button"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-amber-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-500 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:outline-none"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                    Terapkan
                </button>
                <button
                    wire:click="exportExcel"
                    type="button"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-green-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-green-500 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:outline-none"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Export Excel
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 p-5 text-white shadow-lg shadow-amber-500/25">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 -right-6 h-20 w-20 rounded-full bg-white/5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-100">Penjualan</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight">Rp {{ number_format($summary['total_penjualan'] ?? 0) }}</p>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-5 text-white shadow-lg shadow-blue-500/25">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 -right-6 h-20 w-20 rounded-full bg-white/5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-100">Pembelian</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight">Rp {{ number_format($summary['total_pembelian'] ?? 0) }}</p>
        </div>

        @php $isProfit = ($summary['total_laba'] ?? 0) >= 0; @endphp
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br {{ $isProfit ? 'from-emerald-500 to-green-600 shadow-emerald-500/25' : 'from-red-500 to-rose-600 shadow-red-500/25' }} p-5 text-white shadow-lg">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 -right-6 h-20 w-20 rounded-full bg-white/5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider {{ $isProfit ? 'text-emerald-100' : 'text-red-100' }}">Laba Bersih</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight">Rp {{ number_format($summary['total_laba'] ?? 0) }}</p>
            <p class="mt-1 text-xs {{ $isProfit ? 'text-emerald-100' : 'text-red-100' }}">{{ $isProfit ? 'Untung' : 'Rugi' }}</p>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 p-5 text-white shadow-lg shadow-violet-500/25">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 -right-6 h-20 w-20 rounded-full bg-white/5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-violet-100">Total Transaksi</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight">{{ number_format($summary['jumlah_transaksi'] ?? 0) }}</p>
            <p class="mt-1 text-xs text-violet-100">Rata-rata Rp {{ number_format($summary['rata_per_hari'] ?? 0) }}/hari</p>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 p-5 text-white shadow-lg shadow-cyan-500/25">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 -right-6 h-20 w-20 rounded-full bg-white/5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-cyan-100">Total Nilai Stok</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight">Rp {{ number_format(collect($produkData)->sum('nilai_stok')) }}</p>
            <p class="mt-1 text-xs text-cyan-100">{{ count($produkData) }} produk aktif</p>
        </div>
    </div>

    {{-- Tabel Penjualan --}}
    @if(count($penjualanData) > 0)
    <x-filament::section class="mb-6">
        <x-slot name="heading">Data Penjualan</x-slot>

        <div class="overflow-x-auto rounded-xl border border-gray-200/60 dark:border-gray-700/60">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pelanggan</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Produk</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Qty</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Harga</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($penjualanData as $index => $item)
                    <tr class="hover:bg-amber-50/50 dark:hover:bg-amber-950/20 transition-colors duration-150">
                        <td class="px-5 py-3.5 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['tanggal'] }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-2">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-bold ring-2 ring-amber-200/50 dark:ring-amber-800/50">
                                    {{ strtoupper(substr($item['pelanggan'], 0, 1)) }}
                                </span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item['pelanggan'] }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">
                                {{ $item['produk'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right tabular-nums text-gray-700 dark:text-gray-300">{{ $item['jumlah'] }}</td>
                        <td class="px-5 py-3.5 text-right tabular-nums text-gray-500 dark:text-gray-400">Rp {{ number_format($item['harga_satuan']) }}</td>
                        <td class="px-5 py-3.5 text-right font-bold tabular-nums text-gray-900 dark:text-white">Rp {{ number_format($item['total']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-amber-50 dark:bg-amber-950/30 border-t-2 border-amber-200 dark:border-amber-800">
                        <td colspan="6" class="px-5 py-3.5 text-right text-sm font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">Total</td>
                        <td class="px-5 py-3.5 text-right text-lg font-extrabold tabular-nums text-amber-600 dark:text-amber-400">Rp {{ number_format(collect($penjualanData)->sum('total')) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-filament::section>
    @endif

    {{-- Tabel Pembelian --}}
    @if(count($pembelianData) > 0)
    <x-filament::section class="mb-6">
        <x-slot name="heading">Data Pembelian</x-slot>

        <div class="overflow-x-auto rounded-xl border border-gray-200/60 dark:border-gray-700/60">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Supplier</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Produk</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Qty</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Harga</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($pembelianData as $index => $item)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-950/20 transition-colors duration-150">
                        <td class="px-5 py-3.5 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['tanggal'] }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-2">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-xs font-bold ring-2 ring-blue-200/50 dark:ring-blue-800/50">
                                    {{ strtoupper(substr($item['supplier'], 0, 1)) }}
                                </span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item['supplier'] }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">
                                {{ $item['produk'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right tabular-nums text-gray-700 dark:text-gray-300">{{ $item['jumlah'] }}</td>
                        <td class="px-5 py-3.5 text-right tabular-nums text-gray-500 dark:text-gray-400">Rp {{ number_format($item['harga_satuan']) }}</td>
                        <td class="px-5 py-3.5 text-right font-bold tabular-nums text-gray-900 dark:text-white">Rp {{ number_format($item['total']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-blue-50 dark:bg-blue-950/30 border-t-2 border-blue-200 dark:border-blue-800">
                        <td colspan="6" class="px-5 py-3.5 text-right text-sm font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">Total</td>
                        <td class="px-5 py-3.5 text-right text-lg font-extrabold tabular-nums text-blue-600 dark:text-blue-400">Rp {{ number_format(collect($pembelianData)->sum('total')) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-filament::section>
    @endif

    {{-- Tabel Inventory --}}
    @if(count($produkData) > 0)
    <x-filament::section>
        <x-slot name="heading">Data Inventory Stok</x-slot>

        <div class="overflow-x-auto rounded-xl border border-gray-200/60 dark:border-gray-700/60">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Produk</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Warna</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Stok</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Satuan</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jual</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Beli</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Margin</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($produkData as $item)
                    <tr class="hover:bg-cyan-50/50 dark:hover:bg-cyan-950/20 transition-colors duration-150">
                        <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-white">{{ $item['nama'] }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['warna'] }}</td>
                        <td class="px-5 py-3.5 text-right">
                            @if($item['stok'] <= 5)
                                <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-950/50 px-2.5 py-0.5 text-xs font-bold text-red-700 dark:text-red-300 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/30">
                                    {{ $item['stok'] }}
                                </span>
                            @elseif($item['stok'] <= 15)
                                <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-950/50 px-2.5 py-0.5 text-xs font-bold text-amber-700 dark:text-amber-300 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-500/30">
                                    {{ $item['stok'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-950/50 px-2.5 py-0.5 text-xs font-bold text-green-700 dark:text-green-300 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30">
                                    {{ $item['stok'] }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $item['satuan'] }}</td>
                        <td class="px-5 py-3.5 text-right tabular-nums text-gray-700 dark:text-gray-300">Rp {{ number_format($item['harga_jual']) }}</td>
                        <td class="px-5 py-3.5 text-right tabular-nums text-gray-500 dark:text-gray-400">Rp {{ number_format($item['harga_beli']) }}</td>
                        <td class="px-5 py-3.5 text-right font-bold tabular-nums {{ $item['margin'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            Rp {{ number_format($item['margin']) }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold tabular-nums text-gray-900 dark:text-white">Rp {{ number_format($item['nilai_stok']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-cyan-50 dark:bg-cyan-950/30 border-t-2 border-cyan-200 dark:border-cyan-800">
                        <td colspan="5" class="px-5 py-3.5 text-right text-sm font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">Total Nilai Stok</td>
                        <td colspan="2" class="px-5 py-3.5 text-right text-sm font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">Total Margin</td>
                        <td class="px-5 py-3.5 text-right text-lg font-extrabold tabular-nums text-cyan-600 dark:text-cyan-400">Rp {{ number_format(collect($produkData)->sum('nilai_stok')) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-filament::section>
    @endif
</x-filament-panels::page>
