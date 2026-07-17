<?php

namespace App\Filament\Customer\Pages;

use App\Models\Cat;
use App\Models\Penjualan;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class Checkout extends CustomerPage implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $slug = 'checkout';

    protected static ?string $navigationLabel = 'Checkout';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.customer.pages.checkout';

    public ?array $data = [];

    public bool $fromCart = false;

    public function mount(): void
    {
        $this->fromCart = request()->query('from_cart') === '1';

        if ($this->fromCart) {
            $this->form->fill([]);

            return;
        }

        $catId = request()->query('cat_id');

        $this->form->fill([
            'cat_id' => $catId,
            'jumlah' => 1,
        ]);

        if ($catId) {
            $this->updatePrice($catId);
        }
    }

    public function form(Schema $form): Schema
    {
        if ($this->fromCart) {
            return $form->schema([])->statePath('data');
        }

        return $form
            ->schema([
                Select::make('cat_id')
                    ->label('Pilih Produk')
                    ->options(fn () => Cat::where('stok', '>', 0)->pluck('nama', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->updatePrice($state)),

                Placeholder::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->content(fn () => 'Rp'.number_format($this->data['harga_satuan'] ?? 0)),

                Placeholder::make('stok_tersedia')
                    ->label('Stok Tersedia')
                    ->content(fn () => ($this->data['stok_tersedia'] ?? 0).' unit'),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(fn () => $this->data['stok_tersedia'] ?? 1)
                    ->required()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->updateTotal()),

                Placeholder::make('total_harga')
                    ->label('Total Harga')
                    ->content(fn () => 'Rp'.number_format($this->data['total_harga'] ?? 0)),
            ])
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

    public function updatePrice($catId): void
    {
        if ($catId) {
            $cat = Cat::find($catId);

            if (! $cat) {
                return;
            }

            $this->data['harga_satuan'] = $cat->harga;
            $this->data['stok_tersedia'] = $cat->stok;
            $this->updateTotal();
        }
    }

    public function updateTotal(): void
    {
        $harga = $this->data['harga_satuan'] ?? 0;
        $jumlah = $this->data['jumlah'] ?? 0;
        $this->data['total_harga'] = $harga * $jumlah;
    }

    public function submit(): void
    {
        if ($this->fromCart) {
            $this->submitCart();
        } else {
            $this->submitSingle();
        }
    }

    protected function submitSingle(): void
    {
        $data = $this->form->getState();

        DB::beginTransaction();

        try {
            $cat = Cat::lockAndFind($data['cat_id']);

            if (! $cat || $cat->stok < $data['jumlah']) {
                DB::rollBack();
                session()->flash('error', 'Stok tidak mencukupi!');

                return;
            }

            Penjualan::create([
                'pelanggan_id' => Filament::auth()->id(),
                'cat_id' => $data['cat_id'],
                'tanggal_penjualan' => now()->toDateString(),
                'jumlah' => $data['jumlah'],
                'harga_satuan' => $cat->harga,
                'total_harga' => $cat->harga * $data['jumlah'],
            ]);

            $cat->decrement('stok', $data['jumlah']);

            DB::commit();

            session()->flash('success', 'Pembelian berhasil!');
            $this->form->fill([]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal melakukan pembelian: '.$e->getMessage());
        }
    }

    protected function submitCart(): void
    {
        $cart = $this->getCartItems();

        if (empty($cart)) {
            session()->flash('error', 'Keranjang kosong!');

            return;
        }

        DB::beginTransaction();

        try {
            foreach ($cart as $item) {
                $cat = Cat::lockAndFind($item['cat_id']);

                if (! $cat || $cat->stok < $item['jumlah']) {
                    DB::rollBack();
                    session()->flash('error', 'Stok '.($cat->nama ?? '').' tidak mencukupi!');

                    return;
                }

                Penjualan::create([
                    'pelanggan_id' => Filament::auth()->id(),
                    'cat_id' => $item['cat_id'],
                    'tanggal_penjualan' => now()->toDateString(),
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $cat->harga,
                    'total_harga' => $cat->harga * $item['jumlah'],
                ]);

                $cat->decrement('stok', $item['jumlah']);
            }

            session()->forget('cart');
            DB::commit();

            session()->flash('success', 'Semua pembelian berhasil!');
            $this->fromCart = false;
            $this->form->fill([]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal melakukan pembelian: '.$e->getMessage());
        }
    }
}
