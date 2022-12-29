<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_permit_id',
        'barang',
        'qty',
        'keterangan'
    ];

    protected $table = 'travel_supplies';

    public function travelPermit(){
        return $this->belongsTo(TravelPermit::class, 'travel_permit_id');
    }
}
