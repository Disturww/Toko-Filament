<?php

namespace App\Filament\Resources\CatResource\Pages;

use App\Filament\Resources\CatResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCat extends CreateRecord
{
    protected static string $resource = CatResource::class;

    protected function afterCreate(): void
    {
        $admins = User::role(['admin', 'super_admin'])->get();
        $actor = auth()->user()->name;

        Notification::make()
            ->title('Produk Baru Ditambah')
            ->body("[{$actor}] {$this->record->nama} ({$this->record->warna}) - Stok: {$this->record->stok} {$this->record->satuan}")
            ->success()
            ->sendToDatabase($admins);
    }
}
