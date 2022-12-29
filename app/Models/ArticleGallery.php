<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'article_id'
    ];

    protected $table = 'article_gallery';

    public function article(){
        return $this->belongsTo(Article::class);
    }
}
