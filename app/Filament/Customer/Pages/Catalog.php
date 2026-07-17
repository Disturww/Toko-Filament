<?php

namespace App\Filament\Customer\Pages;

use App\Models\Cat;
use App\Models\Merek;
use Livewire\WithPagination;

class Catalog extends CustomerPage
{
    use WithPagination;

    protected static ?string $slug = 'catalog';

    protected static ?string $navigationLabel = 'Katalog';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.customer.pages.catalog';

    public string $search = '';

    public string $sortBy = 'newest';

    public ?int $filterMerek = null;

    public ?string $filterWarna = null;

    public int $perPage = 12;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterMerek(): void
    {
        $this->resetPage();
    }

    public function updatingFilterWarna(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function getProducts()
    {
        $query = Cat::with(['merek', 'supplier'])
            ->where('stok', '>', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('warna', 'like', '%'.$this->search.'%')
                    ->orWhereHas('merek', function ($mq) {
                        $mq->where('nama', 'like', '%'.$this->search.'%');
                    });
            });
        }

        if ($this->filterMerek) {
            $query->where('merek_id', $this->filterMerek);
        }

        if ($this->filterWarna) {
            $query->where('warna', $this->filterWarna);
        }

        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nama', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getMereks()
    {
        return Merek::orderBy('nama')->get();
    }

    public function getWarnas()
    {
        return Cat::where('stok', '>', 0)
            ->whereNotNull('warna')
            ->distinct()
            ->pluck('warna')
            ->sort()
            ->values();
    }

    public function addToCart(int $catId): void
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
            $cat = Cat::find($catId);
            if ($cat && $cart[$existingIndex]['jumlah'] < $cat->stok) {
                $cart[$existingIndex]['jumlah']++;
            } else {
                session()->flash('error', 'Stok tidak mencukupi!');

                return;
            }
        } else {
            $cart[] = [
                'cat_id' => $catId,
                'jumlah' => 1,
            ];
        }

        session()->put('cart', $cart);
        session()->flash('success', 'Produk ditambahkan ke keranjang!');
    }
}
