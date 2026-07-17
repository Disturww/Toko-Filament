<?php

namespace App\Filament\Customer\Pages;

use App\Models\Penjualan;
use Filament\Facades\Filament;

class History extends CustomerPage
{
    protected static ?string $slug = 'history';

    protected static ?string $navigationLabel = 'Riwayat';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.customer.pages.history';

    public function getPenjualans()
    {
        return Penjualan::where('pelanggan_id', Filament::auth()->id())
            ->with('cat')
            ->latest('tanggal_penjualan')
            ->get();
    }
}
