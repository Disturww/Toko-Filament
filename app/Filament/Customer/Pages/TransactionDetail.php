<?php

namespace App\Filament\Customer\Pages;

use App\Models\Penjualan;
use Filament\Facades\Filament;

class TransactionDetail extends CustomerPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.customer.pages.transaction-detail';

    public ?Penjualan $penjualan = null;

    public function mount(): void
    {
        $id = request()->query('id');

        if ($id) {
            $this->penjualan = Penjualan::where('pelanggan_id', Filament::auth()->id())
                ->with('cat')
                ->find($id);
        }
    }
}
