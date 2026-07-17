<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Cat;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    $cat = Cat::lockForUpdate()->find($this->record->cat_id);
                    if ($cat) {
                        $cat->increment('stok', $this->record->jumlah);
                    }
                }),
        ];
    }

    protected function beforeSave(): void
    {
        DB::beginTransaction();

        $oldCat = Cat::lockForUpdate()->find($this->old('cat_id') ?? $this->record->cat_id);
        if ($oldCat) {
            $oldCat->increment('stok', $this->old('jumlah') ?? $this->record->jumlah);
        }
    }

    protected function afterSave(): void
    {
        $newCat = Cat::lockForUpdate()->find($this->record->fresh()->cat_id);

        if ($newCat && $newCat->stok < $this->record->fresh()->jumlah) {
            DB::rollBack();

            Notification::make()
                ->title('Stok tidak mencukupi')
                ->body("Stok {$newCat->nama} hanya tersisa {$newCat->stok} {$newCat->satuan}.")
                ->danger()
                ->send();

            return;
        }

        if ($newCat) {
            $newCat->decrement('stok', $this->record->fresh()->jumlah);
        }

        DB::commit();
    }
}
