@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                @include('articles.search_form')
            </div>
        </div>
        <hr />
        @forelse($articles as $article)
            <div class="card mb-2">
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
                            <a
                                href="{{ route('articles.show', ['locale' => app()->getLocale(), 'article' => $article->id]) }}"
                                class="btn btn-primary"
                                style="position: absolute; bottom: 10px"
                            >
                                {{ __("Ver más") }}
                            </a>
                        </div>
                    </div>
                </div>
                @if($article->isOwner())
                    @include('articles.owner_buttons')
                @endif
            </div>
        @empty
            <div class="alert alert-warning" role="alert">
                {{ __("No hay artículos en este momento") }}
            </div>
        @endforelse

        <div class="float-right mt-2">{{ $articles->links() }}</div>
    </div>
@endsection
