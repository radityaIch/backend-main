<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_permit_id',
        'keterangan',
        'kendala'
    ];

    protected $table = 'travel_trackings';

    public function travelPermit(){
        return $this->belongsTo(TravelPermit::class, 'travel_permit_id');
    }
}
