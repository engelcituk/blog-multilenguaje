@forelse($article->comments as $comment)
    <div class="card mt-2">
        <div class="row">
            <div class="card-body p-5">
                <h4 class="card-title">
                    {{ __("Autor") }}: {{ $comment->owner->name }}
                    <span class="badge badge-info text-white">{{ $comment->commentLanguage() }}</span>
                </h4>
                <h5 class="card-subtitle">
                    {{ $comment->created_at->calendar() }}
                </h5>
                <hr />
                <h6 class="card-text">
                    {{ $comment->commentText() }}
                </h6>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-warning" role="alert">
        {{ __("No hay comentarios en este momento") }}
    </div>
@endforelse
