<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @method static paginate()
 * @method static search()
 * @property array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Request|mixed|string title
 * @property array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Request|mixed|string content
 * @property false|mixed|string image
 * @property array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Request|mixed|string category_id
 */
class Article extends Model
{
    use HasTranslations;

    const IMAGES_PATH = 'images-articles';

    protected $fillable = [
        'title', 'content', 'user_id', 'image', 'category_id'
    ];

    protected $translatable = [
        'title', 'content', 'image'
    ];

    protected $perPage = 10;

    protected static function boot() {
        parent::boot();
        self::saving(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function scopeSearch(Builder $builder) {
        $locale = app()->getLocale();
        if (session('search[articles]')) {
            $search = transliterator_transliterate(
                "Any-Latin; Latin-ASCII; Lower()",
                session('search[articles]')
            );
            $builder->whereRaw(
                "json_extract(LOWER(title), \"$.$locale\") LIKE convert(? using utf8mb4) collate utf8mb4_general_ci", ['%' . $search . '%']
            );
        }
        return $builder->with('category')->paginate();
    }

    public function comments() {
        return $this->hasMany(Comment::class)->orderByDesc('created_at');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function imagePath() {
        return asset(
            sprintf('storage/%s', $this->getTranslation("image", app()->getLocale()))
        );
    }

    public function isOwner() {
        return $this->user_id === auth()->id();
    }

    /**
     *
     * GENERATE DATA FOR BUTTONS AND FORM ON ARTICLES LIST
     *
     * @return array
     */
    public function getCardActions()
    {
        $elements = [];
        foreach (config('languages') as $key => $value) {
            if (!$this->getTranslation('title', $key, false)) {
                array_push($elements, [
                    "text" => __("Añadir :locale", ["locale" => __($value['language'])]),
                    "route" => route("articles.formAddTranslation", ["locale" => $key, "article" => $this->id]),
                    "class" => "btn-info text-white mb-2",
                    "type" => "button"
                ]);
            } else {
                $formId = sprintf('remove-translation-%s-%s-form', $this->id, $key);
                array_push($elements, [
                    "text" => __("Editar :locale", ["locale" => __($value['language'])]),
                    "route" => route("articles.edit", ["locale" => $key, "article" => $this->id]),
                    "class" => "btn-success mb-2",
                    "type" => "button"
                ], [
                    "text" => __("Eliminar :locale", ["locale" => __($value['language'])]),
                    "route" => route("articles.destroy", ["locale" => $key, "article" => $this->id]),
                    "class" => "btn-danger text-white mb-2",
                    "formId" => $formId,
                    "type" => "form"
                ]);
            }
        }
        return $elements;
    }

    public function userHasCommented() {
        return $this->comments()->where('user_id', auth()->id())->exists();
    }
}
