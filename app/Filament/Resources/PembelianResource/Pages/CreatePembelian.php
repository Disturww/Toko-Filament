<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Cat;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function afterCreate(): void
    {
        $cat = Cat::lockForUpdate()->find($this->record->cat_id);
        $cat->increment('stok', $this->record->jumlah);

        $admins = User::role(['admin', 'super_admin'])->get();
        $supplier = $this->record->supplier->nama ?? '-';
        $actor = auth()->user()->name;

        Notification::make()
            ->title('Pembelian Baru')
            ->body("[{$actor}] Pembelian dari {$supplier}: {$this->record->jumlah} {$cat->satuan} {$cat->nama} seharga Rp ".number_format($this->record->total_harga, 0, ',', '.'))
            ->success()
            ->sendToDatabase($admins);
    }
}
