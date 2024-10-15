<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;


    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'stok',
        'harga',
        'gambar',
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

}
