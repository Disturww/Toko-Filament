<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'cat_id',
        'tanggal_penjualan',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penjualan' => 'date',
            'jumlah' => 'integer',
            'harga_satuan' => 'decimal:0',
            'total_harga' => 'decimal:0',
        ];
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
