<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name'
    ];

    protected $translatable = [
        'name'
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
