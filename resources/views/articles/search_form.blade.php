<form
    action="{{ route('articles.search', ['locale' => app()->getLocale()]) }}"
    method="POST"
    autocomplete="off"
>
    @csrf
    <div class="input-group">
        <input
            type="text"
            name="search"
            value="{{ session('search[articles]') }}"
            class="form-control"
            placeholder="{{ __("Buscar artículos") }}"
            autocomplete="off"
        />
        <div class="input-group-append">
            <button class="btn btn-secondary" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
</form>
