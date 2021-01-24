<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Comment extends Model
{
    use HasTranslations;

    protected $fillable = [
        'comment', 'user_id', 'article_id'
    ];

    protected $translatable = [
        'comment'
    ];

    protected static function boot() {
        parent::boot();
        self::saving(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function article() {
        return $this->belongsTo(Article::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commentText() {
        $locale = array_key_first($this->getTranslations('comment'));
        return $this->getTranslations('comment')[$locale];
    }

    public function commentLanguage() {
        $locale = array_key_first($this->getTranslations('comment'));
        return getCurrentStringLanguage($locale);
    }
}
