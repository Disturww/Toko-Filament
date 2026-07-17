<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'warna',
        'gambar',
        'harga',
        'harga_beli',
        'stok',
        'satuan',
        'merek_id',
        'supplier_id',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:0',
            'harga_beli' => 'decimal:0',
            'stok' => 'integer',
        ];
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    public static function lockAndFind(int $id): self
    {
        return static::lockForUpdate()->find($id);
    }
}
