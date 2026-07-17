<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
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

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-truck';

    protected static string|UnitEnum|null $navigationGroup = 'Toko Cat';

    protected static ?string $modelLabel = 'Supplier';

    protected static ?string $pluralModelLabel = 'Daftar Supplier';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('alamat')
                    ->maxLength(255),

                Forms\Components\TextInput::make('no_hp')
                    ->maxLength(20),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cats_count')
                    ->counts('cats')
                    ->label('Jumlah Cat')
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
                            ->title('Supplier Diubah')
                            ->body(auth()->user()->name." mengubah supplier '{$record->nama}'")
                            ->info()
                            ->sendToDatabase($admins);
                    }),
                DeleteAction::make()
                    ->action(function ($record) {
                        $admins = User::role(['admin', 'super_admin'])->get();
                        $nama = $record->nama;
                        $record->delete();
                        Notification::make()
                            ->title('Supplier Dihapus')
                            ->body(auth()->user()->name." menghapus supplier '{$nama}'")
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            '{record}/edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
