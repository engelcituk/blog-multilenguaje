<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(string $locale) {
        $articles = Article::search();
        return view('articles.index', compact('articles'));
    }

    public function create(string $locale) {
        if ($locale !== config('translatable.fallback_locale')) {
            return redirect(
                route('articles.create', ['locale' => config('translatable.fallback_locale')])
            );
        }

        $article = new Article;
        $route = route('articles.store', ['locale' => $locale]);
        $update = false;
        $title = __('Crear un nuevo artículo en :locale', ['locale' => getCurrentStringLanguage($locale)]);
        $button = __("Crear artículo");
        return view('articles.create', compact(
            'article', 'route', 'update', 'title', 'button', 'locale'
        ));
    }

    public function store(string $locale) {
        $this->validate(request(), [
            'title' => 'required|unique_translation:articles',
            'content' => 'required',
            'image' => 'required',
            'category_id' => 'required'
        ]);

        $imagePath = request()->file('image')->store(Article::IMAGES_PATH);

        app()->setLocale($locale);
        $article = new Article;
        $article->title = request('title');
        $article->content = request('content');
        $article->image = $imagePath;
        $article->category_id = request('category_id');
        $article->save();
        app()->setLocale(session('language'));
        session()->flash('message', [
            "title" => __('Información'),
            "message" => __('Nuevo artículo creado'),
            "type" => 'success'
        ]);
        return redirect(route('articles.index', ['locale' => $locale]));
    }

    public function formAddTranslation(string $locale, Article $article) {
        if ($locale === config('translatable.fallback_locale')) {
            return back();
        }

        $route = route('articles.pushTranslation', ['article' => $article->id, 'locale' => $locale]);
        $update = true;

        $title = __('Añadir un nuevo idioma al artículo :article en el idioma :locale', [
            'article' => $article->getTranslation('title', config('translatable.fallback_locale')),
            'locale' => getCurrentStringLanguage($locale)
        ]);

        $button = __("Añadir idioma al artículo");
        return view('articles.edit', compact(
            'article', 'route', 'update', 'title', 'button', 'locale'
        ));
    }

    public function pushTranslation(string $locale, Article $article) {
        $this->validate(request(), [
            'title' => 'required|unique_translation:articles,title,' . $article->id,
            'content' => 'required',
        ]);

        if (request()->hasFile('image')) {
            if ($article->getTranslation('image', $locale, false)) {
                Storage::delete($article->getTranslation('image', $locale));
            }
            $imagePath = request()->file('image')->store(Article::IMAGES_PATH);
            $article->setTranslation('image', $locale, $imagePath);
        }

        if (request()->has('category_id')) {
            $article->category_id = request('category_id');
        }

        $article
            ->setTranslation('title', $locale, request('title'))
            ->setTranslation('content', $locale, request('content'))
            ->save();

        session()->flash('message', [
            "title" => __('Información'),
            "message" => __('Traducción añadida'),
            "type" => 'success'
        ]);
        return redirect(route('articles.index', ['locale' => $locale]));
    }

    public function edit (string $locale, Article $article) {
        $route = route('articles.pushTranslation', ['article' => $article->id, 'locale' => $locale]);
        $update = true;
        $title = __('Actualizar artículo :article en el idioma :locale', [
            'article' => $article->getTranslation('title', $locale),
            'locale' => getCurrentStringLanguage($locale)
        ]);
        $button = __("Actualizar artículo");
        return view('articles.edit', compact(
            'article', 'route', 'update', 'title', 'button', 'locale'
        ));
    }

    public function search(string $locale) {
        $searching = request()->input('search');
        session()->put('search[articles]', $searching);
        session()->save();
        return redirect(route('articles.index', ['locale' => $locale]));
    }

    public function show (string $locale, Article $article) {
        $article->load('category', 'comments.owner');
        return view('articles.show', compact('article'));
    }

    public function postComment(string $locale) {
        $this->validate(request(), [
            'comment' => 'required',
        ]);
        Comment::create(request()->except('_token'));
        session()->flash('message', [
            "title" => __('Información'),
            "message" => __('¡Gracias por tu comentario!'),
            "type" => 'success'
        ]);
        return back();
    }

    public function destroy (string $locale, Article $article) {
        DB::beginTransaction();
        try {
            if ($locale === config('translatable.fallback_locale')) {
                foreach (config('languages') as $key => $value) {
                    if ($article->getTranslation('title', $key, false)) {
                        Storage::delete($article->getTranslation('image', $key));
                        $article->forgetAllTranslations($key);
                    }
                }
                $article->comments()->delete();
                $article->delete();
            } else {
                Storage::delete($article->getTranslation('image', $locale));
                $article->forgetAllTranslations($locale);
                $article->save();
            }
            DB::commit();
            session()->flash('message', [
                "title" => __('Información'),
                "message" => __('Traducciones eliminadas correctamente'),
                "type" => 'success'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            session()->flash('message', [
                "title" => __('Error'),
                "message" => __($exception->getMessage()),
                "type" => 'danger'
            ]);
        }
        return back();
    }
}
