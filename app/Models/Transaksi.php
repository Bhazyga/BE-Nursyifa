<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'namapenyewa', 'alamatpenyewa', 'notelp', 'bis', 'harga', 'berapalama','tanggal',
    ];

}
