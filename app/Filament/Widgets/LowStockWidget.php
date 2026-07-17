<?php

namespace App\Filament\Widgets;

use App\Models\Cat;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockWidget extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = '1';

    protected static ?string $heading = 'Stok Menipis';

    protected static ?int $pageSize = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(Cat::where('stok', '<=', 20)->orderBy('stok', 'asc'))
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->limit(20)
                    ->sortable(),

                Tables\Columns\TextColumn::make('merek.nama')
                    ->label('Merek')
                    ->limit(10),

                Tables\Columns\TextColumn::make('stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 10 => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('satuan')
                    ->limit(5),
            ])
            ->paginated([5]);
    }
}
