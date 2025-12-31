<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/ArsipBarang.php
class ArsipBarang extends Model
{
    protected $fillable = ['barang_id', 'kode_barcode', 'status'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
