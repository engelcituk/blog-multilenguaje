<div class="row">
    <div class="col-md-12">
        <div class="card-footer">
            @foreach($article->getCardActions() as $element)
                @if($element['type'] === 'button')
                    <a
                        class="btn btn-sm {{ $element['class'] }}"
                        href="{{ $element['route'] }}"
                    >
                        {{ $element['text'] }}
                    </a>
                @else
                    <a
                        href="{{ $element['route'] }}"
                        class="btn btn-sm {{ $element['class'] }}"
                        onclick="event.preventDefault(); document.getElementById('{{ $element["formId"] }}').submit();"
                    >
                        {{ $element['text'] }}
                    </a>
                    <form
                        id="{{ $element['formId'] }}"
                        action="{{ $element['route'] }}"
                        method="POST"
                        style="display: none;"
                    >
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endforeach
        </div>
    </div>
</div>
