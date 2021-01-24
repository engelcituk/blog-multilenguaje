<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/set_language/{lang}', 'Controller@setLanguage')->name( 'set_language');

Auth::routes();

Route::group(['middleware' => ['auth'], 'prefix' => '{locale}'], function () {
    Route::resource('articles', 'ArticleController');
    Route::get('articles/{article}/add-translation', 'ArticleController@formAddTranslation')->name('articles.formAddTranslation');
    Route::put('articles/{article}/push-translation', 'ArticleController@pushTranslation')->name('articles.pushTranslation');
    Route::post('articles/search', 'ArticleController@search')->name('articles.search');
    Route::post('articles/comment', 'ArticleController@postComment')->name('articles.postComment');
});
