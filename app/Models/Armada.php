<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'width',
        'height',
        'long',
        'cbm',
        'tonase',
    ];

    protected $table = 'armada';
}
