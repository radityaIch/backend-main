<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'article_id',
        'client_id'
    ];

    protected $table = 'article_link';

    public function client(){
        return $this->hasOne(Client::class, 'client_id');
    }

    public function article(){
        return $this->hasOne(Article::class, 'article_id');
    }
}
