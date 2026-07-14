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
        'harga',
        'stok',
        'satuan',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:0',
            'stok' => 'integer',
        ];
    }
}
