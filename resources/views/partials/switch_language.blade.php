<li class="nav-item dropdown">
    <a
        class="nav-link dropdown-toggle"
        href="#"
        id="navDropdownLanguage"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
    >
        {{ __(getCurrentStringLanguage(session('language'))) }}
    </a>
    <div class="dropdown-menu" aria-labelledby="navDropdownLanguage">
        @foreach(config('languages') as $key => $value)
            <a class="dropdown-item" href="{{ route('set_language', [$key]) }}">
                {{ __($value['language']) }}
            </a>
        @endforeach
    </div>
</li>
