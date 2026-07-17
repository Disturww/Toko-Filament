<?php

namespace App\Filament\Customer\Pages;

use App\Models\Cat;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;

class Cart extends CustomerPage implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $slug = 'cart';

    protected static ?string $navigationLabel = 'Keranjang';

    protected static ?int $navigationSort = 50;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.customer.pages.cart';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([]);
    }

    public function form(Schema $form): Schema
    {
        return $form->schema([])
            ->statePath('data');
    }

    public function getCartItems(): array
    {
        return session()->get('cart', []);
    }

    public function getCartTotal(): int
    {
        $cart = $this->getCartItems();
        $total = 0;
        foreach ($cart as $item) {
            $cat = Cat::find($item['cat_id']);
            if ($cat) {
                $total += $cat->harga * $item['jumlah'];
            }
        }

        return $total;
    }

    public function addToCart(int $catId, int $jumlah = 1): void
    {
        $cart = session()->get('cart', []);

        $existingIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['cat_id'] == $catId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $cart[$existingIndex]['jumlah'] += $jumlah;
        } else {
            $cart[] = [
                'cat_id' => $catId,
                'jumlah' => $jumlah,
            ];
        }

        session()->put('cart', $cart);
        session()->flash('success', 'Produk ditambahkan ke keranjang!');
    }

    public function removeFromCart(int $index): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$index])) {
            unset($cart[$index]);
            $cart = array_values($cart);
            session()->put('cart', $cart);
            session()->flash('success', 'Produk dihapus dari keranjang.');
        }
    }

    public function updateQuantity(int $index, int $jumlah): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$index])) {
            if ($jumlah <= 0) {
                $this->removeFromCart($index);

                return;
            }
            $cat = Cat::find($cart[$index]['cat_id']);
            if ($cat && $jumlah > $cat->stok) {
                session()->flash('error', 'Stok tidak mencukupi!');

                return;
            }
            $cart[$index]['jumlah'] = $jumlah;
            session()->put('cart', $cart);
        }
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        session()->flash('success', 'Keranjang dikosongkan.');
    }
}
