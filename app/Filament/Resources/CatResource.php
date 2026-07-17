<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatResource\Pages;
use App\Models\Cat;
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

class CatResource extends Resource
{
    protected static ?string $model = Cat::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|UnitEnum|null $navigationGroup = 'Toko Cat';

    protected static ?string $modelLabel = 'Cat';

    protected static ?string $pluralModelLabel = 'Daftar Cat';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('warna')
                    ->maxLength(255),

                Forms\Components\FileUpload::make('gambar')
                    ->disk('public')
                    ->directory('cats')
                    ->image()
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Forms\Components\TextInput::make('harga_beli')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Beli'),

                Forms\Components\TextInput::make('stok')
                    ->required()
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->default('kaleng')
                    ->maxLength(50),

                Forms\Components\Select::make('merek_id')
                    ->relationship('merek', 'nama')
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('supplier_id')
                    ->relationship('supplier', 'nama')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->disk('public')
                    ->circular()
                    ->label('Gambar'),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('warna')
                    ->searchable(),

                Tables\Columns\TextColumn::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_beli')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Beli')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan'),

                Tables\Columns\TextColumn::make('merek.nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.nama')
                    ->searchable()
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
                            ->title('Cat Diubah')
                            ->body(auth()->user()->name." mengubah {$record->nama} ({$record->warna})")
                            ->info()
                            ->sendToDatabase($admins);
                    }),
                DeleteAction::make()
                    ->action(function ($record) {
                        $admins = User::role(['admin', 'super_admin'])->get();
                        $nama = $record->nama;
                        $warna = $record->warna;
                        $record->delete();
                        Notification::make()
                            ->title('Cat Dihapus')
                            ->body(auth()->user()->name." menghapus {$nama} ({$warna})")
                            ->danger()
                            ->sendToDatabase($admins);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCats::route('/'),
            'create' => Pages\CreateCat::route('/create'),
            '{record}/edit' => Pages\EditCat::route('/{record}/edit'),
        ];
    }
}
