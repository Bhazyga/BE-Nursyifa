<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = [
        'id',
        'material_id',
        'nohp',
        'namamaterial',
        'alamat',
        'status',
        'namapembeli',
        'quantity',
    ];
    public function material()
    {
        return $this->belongsTo(Material::class);
    }


}
