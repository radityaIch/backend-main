<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillabel = [
        'title',
        'category',
        'image',
        'user_id',
        'description',
        'client_id'
    ];

    protected $table = 'article';

    public function client(){
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function created_by(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function gallery(){
        return $this->hasMany(ArticleGallery::class, 'article_id');
    }
}
