<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Cat;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected ?Cat $lockedCat = null;

    protected function beforeCreate(): void
    {
        $this->lockedCat = Cat::lockForUpdate()->find($this->data['cat_id']);

        if (! $this->lockedCat || $this->lockedCat->stok < ($this->data['jumlah'] ?? 0)) {
            Notification::make()
                ->title('Stok tidak mencukupi')
                ->body('Stok '.($this->lockedCat->nama ?? '-').' hanya tersisa '.($this->lockedCat->stok ?? 0).'.')
                ->danger()
                ->send();

            $this->halt();
        }

        $this->data['harga_satuan'] = $this->lockedCat->harga;
        $this->data['total_harga'] = $this->lockedCat->harga * $this->data['jumlah'];
    }

    protected function afterCreate(): void
    {
        $cat = $this->lockedCat ?? Cat::find($this->record->cat_id);
        $cat->decrement('stok', $this->record->jumlah);

        $admins = User::role(['admin', 'super_admin'])->get();
        $pelanggan = $this->record->pelanggan->nama ?? '-';
        $actor = auth()->user()->name;

        Notification::make()
            ->title('Penjualan Baru')
            ->body("[{$actor}] {$pelanggan} membeli {$this->record->jumlah} {$cat->satuan} {$cat->nama} seharga Rp ".number_format($this->record->total_harga, 0, ',', '.'))
            ->success()
            ->sendToDatabase($admins);

        if ($cat->stok <= 20) {
            Notification::make()
                ->title('Stok Menipis!')
                ->body("[{$actor}] Stok {$cat->nama} tersisa {$cat->stok} {$cat->satuan}. Segera lakukan pembelian.")
                ->warning()
                ->sendToDatabase($admins);
        }

        if ($cat->stok == 0) {
            Notification::make()
                ->title('STOK HABIS!')
                ->body("[{$actor}] Stok {$cat->nama} sudah HABIS total! Segera lakukan pembelian sebelum kehabisan.")
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->sendToDatabase($admins);
        }
    }
}
