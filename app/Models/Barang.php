<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Barang.php
class Barang extends Model
{
    protected $guarded = [];
    protected $table = 'barang';

    // Relasi ke model User untuk verified_by
    public function user()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Relasi ke ArsipBarang
    public function arsipBarang()
    {
        return $this->hasOne(ArsipBarang::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
