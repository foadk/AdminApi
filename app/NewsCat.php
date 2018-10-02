<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCat extends Model
{
    protected $fillable = [
        'title', 'news_cat_id'
    ];

    public function news() {
        return $this->hasMany('App\News');
    }
}
