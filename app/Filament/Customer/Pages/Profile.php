<?php

namespace App\Filament\Customer\Pages;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;

class Profile extends CustomerPage
{
    protected static ?string $slug = 'profile';

    protected static ?string $navigationLabel = 'Profil';

    protected static ?int $navigationSort = 99;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.customer.pages.profile';

    public string $nama = '';

    public string $email = '';

    public string $no_hp = '';

    public string $alamat = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Filament::auth()->user();
        $this->nama = $user->nama ?? '';
        $this->email = $user->email ?? '';
        $this->no_hp = $user->no_hp ?? '';
        $this->alamat = $user->alamat ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|max:20',
            'alamat' => 'nullable|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = Filament::auth()->user();

        $updateData = [
            'nama' => $this->nama,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ];

        if (filled($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $user->update($updateData);

        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('success', 'Profil berhasil diperbarui!');
    }
}
