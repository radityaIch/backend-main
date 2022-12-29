<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_do',
        'pengirim',
        'alamat_muat',
        'alamat_kirim',
        'no_telp',
        'nopol',
        'driver',
        'unit',
        'pengiriman',
        'harga_jual',
        'harga_beli'
    ];

    protected $table = 'travel_permits';
}
