<?php

namespace App\Filament\Customer\Pages;

use App\Models\Penjualan;
use Filament\Facades\Filament;

class Dashboard extends CustomerPage
{
    protected static ?string $slug = 'dashboard';

    protected static ?string $navigationLabel = 'Beranda';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.customer.pages.dashboard';

    public function getPenjualans()
    {
        return Penjualan::where('pelanggan_id', Filament::auth()->id())
            ->with('cat')
            ->latest('tanggal_penjualan')
            ->get();
    }
}
