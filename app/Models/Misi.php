<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Misi extends Model
{
    use HasFactory;
    protected $fillable = [
        'about_us_id',
        'misi'
    ];

    protected $table = 'misi';

    public function aboutUs(){
        return $this->belongsTo(AboutUs::class);
    }
}
