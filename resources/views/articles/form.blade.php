<form method="post" action="{{ $route }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    @if($update)
        @method('put')
    @endif

    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <label class="col-sm-2 col-form-label" for="input-title">{{ __('Título') }}</label>
                <div class="col-sm-10">
                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                        <input
                            class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                            name="title"
                            id="input-title"
                            type="text"
                            placeholder="{{ __('Título') }}"
                            value="{{ old('title', $article->getTranslation('title', $locale)) }}"
                            required="true"
                            aria-required="true"
                        />
                        @if ($errors->has('title'))
                            <span id="title-error" class="error text-danger">
                                {{ $errors->first('title') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="category_id">
                    {{ __('Categoría del artículo') }}
                </label>
                <div class="col-sm-10">
                    <select
                        class="form-control"
                        id="category_id"
                        name="category_id"
                        {{ $locale !== config('translatable.fallback_locale') ? 'disabled' : '' }}
                    >
                        <option>{{ __("Selecciona una categoría") }}</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option
                                value="{{ old('category_id', $category->id ?? $article->category_id) }}"
                                {{ old('category_id') ?
                                    (old('category_id') === $category->id ? 'selected' : '') :
                                    ($article->category_id === $category->id ? 'selected' : '')
                                }}
                            >
                                {{ $category->getTranslation('name', $locale) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 col-form-label" for="content">{{ __('Artículo') }}</label>
                <div class="col-sm-10">
                    <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                        <textarea
                            id="content"
                            class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                            name="content"
                            placeholder="{{ __('Artículo') }}"
                            required
                        >{{ old('content', $article->getTranslation('content', $locale)) }}</textarea>
                        @if ($errors->has('content'))
                            <span
                                id="content-error"
                                class="error text-danger"
                            >
                                {{ $errors->first('content') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Imagen del artículo') }}</label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="image">
                        <label class="custom-file-label" for="image">
                            {{ __('Selecciona un archivo') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="card-footer ml-auto mr-auto float-right">
                <button type="submit" class="btn btn-info">{{ $button }}</button>
            </div>
        </div>
    </div>

</form>
