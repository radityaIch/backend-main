<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pros extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'description'
    ];

    protected $table = 'pros';
}
