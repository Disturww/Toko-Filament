<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'cat_id',
        'tanggal_pembelian',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pembelian' => 'date',
            'jumlah' => 'integer',
            'harga_satuan' => 'decimal:0',
            'total_harga' => 'decimal:0',
        ];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
