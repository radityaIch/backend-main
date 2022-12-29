<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagline',
        'visi',
        'description',
        'nib',
        'image_1',
        'image_2'
    ];

    protected $table = 'about_us';

    public function misi(){
        return $this->hasMany(Misi::class, 'about_us_id');
    }
}
