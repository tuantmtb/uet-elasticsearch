<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', 'Web\HomeController@main')->name('main');

Route::get('search', 'Web\SearchController@view')->name('search');
Route::get('search/article', 'Web\SearchArticleController@search')->name('search.article');
