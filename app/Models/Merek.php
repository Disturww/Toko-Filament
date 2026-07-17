<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merek extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'negara',
    ];

    public function cats()
    {
        return $this->hasMany(Cat::class);
    }
}
