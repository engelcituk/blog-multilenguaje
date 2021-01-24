<?php

if (!function_exists('getCurrentStringLanguage')) {
    function getCurrentStringLanguage(string $language) {
        return config('languages.' . $language . '.language');
    }
}

if (!function_exists('setLanguageSession')) {
    function setLanguageSession(string $language) {
        if(array_key_exists( $language, config('languages'))) {
            session()->put('language', $language);
        }
    }
}

if (!function_exists('setCurrentLanguage')) {
    function setCurrentLanguage() {
        if (session('language')) {
            $configLanguage = config('languages')[session('language')];
            setlocale(LC_TIME, $configLanguage['lc']);
        } else {
            session()->put('language', config('app.fallback_locale'));
            setlocale(LC_TIME, 'es_ES');
        }
        app()->setLocale(session('language'));
    }
}
