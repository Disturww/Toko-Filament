<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Cat;
use App\Models\Pembelian;
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

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-truck';

    protected static string|UnitEnum|null $navigationGroup = 'Toko Cat';

    protected static ?string $modelLabel = 'Pembelian';

    protected static ?string $pluralModelLabel = 'Daftar Pembelian';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\DatePicker::make('tanggal_pembelian')
                    ->required()
                    ->default(now()),

                Forms\Components\Select::make('supplier_id')
                    ->relationship('supplier', 'nama')
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
                Tables\Columns\TextColumn::make('tanggal_pembelian')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.nama')
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
                            ->title('Pembelian Diubah')
                            ->body(auth()->user()->name." mengubah pembelian dari {$record->supplier->nama} - Rp ".number_format($record->total_harga, 0, ',', '.'))
                            ->info()
                            ->sendToDatabase($admins);
                    }),
                DeleteAction::make()
                    ->action(function ($record) {
                        $admins = User::role(['admin', 'super_admin'])->get();
                        $supplierNama = $record->supplier->nama;
                        $totalHarga = $record->total_harga;
                        $cat = Cat::lockForUpdate()->find($record->cat_id);
                        if ($cat) {
                            $cat->decrement('stok', $record->jumlah);
                        }
                        $record->delete();
                        Notification::make()
                            ->title('Pembelian Dihapus')
                            ->body(auth()->user()->name." menghapus pembelian dari {$supplierNama} - Rp ".number_format($totalHarga, 0, ',', '.'))
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
                                    $cat->decrement('stok', $record->jumlah);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            '{record}/edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
