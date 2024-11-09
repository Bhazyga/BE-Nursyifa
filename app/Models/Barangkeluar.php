<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangkeluar extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'quantity', 'tanggal_keluar', 'keterangan'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

