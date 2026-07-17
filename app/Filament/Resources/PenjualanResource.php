<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Cat;
use App\Models\Penjualan;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string|UnitEnum|null $navigationGroup = 'Toko Cat';

    protected static ?string $modelLabel = 'Penjualan';

    protected static ?string $pluralModelLabel = 'Daftar Penjualan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\DatePicker::make('tanggal_penjualan')
                    ->required()
                    ->default(now()),

                Forms\Components\Select::make('pelanggan_id')
                    ->relationship('pelanggan', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('cat_id')
                    ->relationship('cat', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($set, ?string $state) {
                        if ($state) {
                            $cat = Cat::find($state);
                            if ($cat) {
                                $set('harga_satuan', $cat->harga);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->live()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        $harga = (int) $get('harga_satuan');
                        $jumlah = (int) $state;
                        $set('total_harga', $harga * $jumlah);
                    }),

                Forms\Components\TextInput::make('harga_satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),

                Forms\Components\TextInput::make('total_harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_penjualan')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cat.nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->after(function ($record) {
                        $admins = User::role(['admin', 'super_admin'])->get();
                        Notification::make()
                            ->title('Penjualan Diubah')
                            ->body(auth()->user()->name." mengubah penjualan {$record->pelanggan->nama} - Rp ".number_format($record->total_harga, 0, ',', '.'))
                            ->info()
                            ->sendToDatabase($admins);
                    }),
                DeleteAction::make()
                    ->action(function ($record) {
                        $admins = User::role(['admin', 'super_admin'])->get();
                        $pelangganNama = $record->pelanggan->nama;
                        $totalHarga = $record->total_harga;
                        $cat = Cat::lockForUpdate()->find($record->cat_id);
                        if ($cat) {
                            $cat->increment('stok', $record->jumlah);
                        }
                        $record->delete();
                        Notification::make()
                            ->title('Penjualan Dihapus')
                            ->body(auth()->user()->name." menghapus penjualan {$pelangganNama} - Rp ".number_format($totalHarga, 0, ',', '.'))
                            ->danger()
                            ->sendToDatabase($admins);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $cat = Cat::lockForUpdate()->find($record->cat_id);
                                if ($cat) {
                                    $cat->increment('stok', $record->jumlah);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            '{record}/edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
