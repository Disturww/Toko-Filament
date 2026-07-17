<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Cat;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    $cat = Cat::lockForUpdate()->find($this->record->cat_id);
                    if ($cat) {
                        $cat->decrement('stok', $this->record->jumlah);
                    }
                }),
        ];
    }

    protected function beforeSave(): void
    {
        DB::beginTransaction();

        $oldCat = Cat::lockForUpdate()->find($this->old('cat_id') ?? $this->record->cat_id);
        if ($oldCat) {
            $oldCat->decrement('stok', $this->old('jumlah') ?? $this->record->jumlah);
        }
    }

    protected function afterSave(): void
    {
        $newCat = Cat::lockForUpdate()->find($this->record->fresh()->cat_id);
        if ($newCat) {
            $newCat->increment('stok', $this->record->fresh()->jumlah);
        }

        DB::commit();
    }
}
