<?php

namespace App\Filament\Customer\Pages;

use App\Models\Pelanggan;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class Register extends BaseRegister
{
    protected string $userModel = Pelanggan::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getNoHpFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('nama')
            ->label('Nama Lengkap')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getNoHpFormComponent(): Component
    {
        return TextInput::make('no_hp')
            ->label('No. HP')
            ->tel()
            ->maxLength(20);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        unset($data['passwordConfirmation']);

        return $data;
    }
}
