@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="row">
                <div class="col-2">
                    <img src="{{ $article->imagePath() }}" class="card-img-top p-2" alt="{{ $article->getTranslation('title', app()->getLocale()) }}">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $article->getTranslation('title', app()->getLocale()) }}
                        </h5>
                        <h6 class="card-subtitle">
                            {{ __("Categoría") }}: {{ $article->category->getTranslation('name', app()->getLocale()) }}
                        </h6>
                        <hr />
                        <p class="card-text pb-3">
                            {{ $article->getTranslation('content', app()->getLocale()) }}
                        </p>

                        <a
                            href="{{ route('articles.index', ['locale' => app()->getLocale()]) }}"
                            class="btn btn-primary"
                            style="position: absolute; bottom: 10px"
                        >
                            {{ __("Volver") }}
                        </a>
                    </div>
                </div>
            </div>
            @if($article->isOwner())
                @include('articles.owner_buttons')
            @endif
        </div>

        <h3 class="text-center text-muted mt-3">{{ __("Comentarios") }}</h3>
        @if(!$article->userHasCommented())
            @include('articles.comments_form')
        @endif
        <hr />
        @include('articles.comments')
    </div>
@endsection
