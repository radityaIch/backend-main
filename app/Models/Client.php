<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
    ];

    protected $table = 'client';

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function articleLink(){
        return $this->hasMany(ArticleLink::class, 'client_id');
    }

}
