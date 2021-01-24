@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('articles.postComment', ['locale' => app()->getLocale()]) }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="article_id" value="{{ $article->id }}">
    <div class="input-group">
        <textarea
            name="comment"
            class="form-control"
            placeholder="{{ __("Añade un comentario") }}"
        ></textarea>
        <div class="input-group-append">
            <button class="btn btn-secondary" type="submit">
                <i class="fa fa-save"></i>
            </button>
        </div>
    </div>
</form>
