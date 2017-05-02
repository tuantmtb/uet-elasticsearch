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

//Auth::routes();

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');
Route::get('register', 'Web\HomeController@disable')->name('register');
// Password Reset Routes...
//$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
//$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('film/test', 'Web\FilmController@test')->name('film.test');

//Route::get('', 'Web\HomeController@main')->name('main');
//Route::get('home', 'Web\HomeController@home')->name('home');

Route::group(['middleware' => 'auth'], function() {

    Route::get('search', 'Web\SearchController@view')->name('search');
    Route::get('search/article', 'Web\SearchArticleController@search')->name('search.article');
    Route::get('search/journal', 'Web\SearchJournalController@search')->name('search.journal');
    Route::get('search/organize', 'Web\SearchOrganizeController@search')->name('search.organize');

    Route::get('statistics', 'Web\StatisticsController@view')->name('statistics');
    Route::get('statistics/journals', 'Web\StatisticsController@journals')->name('statistics.journals');
    Route::get('statistics/organizes', 'Web\StatisticsController@organizes')->name('statistics.organizes');
    Route::get('statistics/journals/{id}', 'Web\StatisticsController@journal')->name('statistics.journal');
    Route::get('statistics/organizes/{id}', 'Web\StatisticsController@organize')->name('statistics.organize');

    Route::get('articles/create', 'Web\ArticleController@create')->name('article.create');
    Route::post('articles/create', 'Web\ArticleController@store')->name('article.store');
    Route::get('articles/reviewed', 'Web\ArticleController@reviewed')->name('article.reviewed');
    Route::get('articles/non-reviewed', 'Web\ArticleController@non_reviewed')->name('article.non_reviewed');
    Route::get('articles/{id}', 'Web\ArticleController@show')->name('article.show');
    Route::get('articles/{id}/edit', 'Web\ArticleController@edit')->name('article.edit');
    Route::post('articles/{id}/edit', 'Web\ArticleController@update')->name('article.update');
    Route::post('articles/{id}/review', 'Web\ArticleController@review')->name('article.review');

    Route::get('organizes', 'Web\OrganizeController@index')->name('organize.index');
    Route::get('organizes/{id}/articles', 'Web\SearchArticleFromOrganizeController@searchFromOrganize')->name('organize.articles');

    Route::get('journals', 'Web\JournalController@index')->name('journal.index');
    Route::get('journals/statistics', 'Web\JournalController@statistics')->name('journal.statistics');
    Route::get('journals/create', 'Web\JournalController@create')->name('journal.create');
    Route::post('journals/create', 'Web\JournalController@store')->name('journal.store');
    Route::get('journals/{id}/articles', 'Web\SearchArticleFromJournalController@searchFromJournal')->name('journal.articles');
    Route::get('journals/{id}/articles/reviewed', 'Web\JournalController@reviewedArticles')->name('journal.articles.reviewed');
    Route::get('journals/{id}/articles/non-reviewed', 'Web\JournalController@nonReviewedArticles')->name('journal.articles.non_reviewed');

    Route::get('authors/{id}/articles', 'Web\SearchArticleFromAuthorController@searchFromAuthor')->name('author.articles');

    Route::get('manage/dashboard', 'Web\ManageController@dashboard')->name('manage.dashboard');

    Route::get('manage/backup', 'Web\BackupController@index')->name('manage.backup');
    Route::post('manage/backup/run', 'Web\BackupController@backup')->name('manage.backup.run');
    Route::get('manage/backup/download/{file_name}', 'Web\BackupController@download')->name('manage.backup.download');
    Route::post('manage/backup/delete', 'Web\BackupController@delete')->name('manage.backup.delete');
    Route::get('manage/editor-statistics', 'Web\ManageController@editorStatistic')->name('manage.editor_statistics');

    Route::get('/search/organizes/advance', 'Web\SearchController@organizes_advance')->name('search.organizes.advance');

    Route::get('users/{id}', [
        'uses' => 'Web\UserController@show',
        'as' => 'user.show'
    ]);

    Route::get('/organizes/create', 'Web\OrganizeController@create')->name('organize.create');

    Route::get('manage/organize/create', 'Web\ManageController@create_organize')->name('manage.organize.new');
    Route::post('manage/organize/create', 'Web\ManageController@store_organize')->name('manage.organize.store');
    Route::get('manage/organize/{id}/edit', 'Web\ManageController@edit_organize')->name('manage.organize.edit');
    Route::post('manage/organize/{id}/edit', 'Web\ManageController@update_organize')->name('manage.organize.update');

    Route::get('manage/articles/review-citation', 'Web\ReviewCitationController@index')->name('manage.review_citation.index');
    Route::get('manage/articles/{id}/review-citation', 'Web\ReviewCitationController@show')->name('manage.review_citation.show');
    Route::get('manage/articles/{id}/review-citation/raw', 'Web\ReviewCitationController@raw');

    Route::get('manage/journal/{id}/subjects', 'Web\ManageController@journal_subjects')->name('manage.journal.subjects');

    Route::get('manage/organizes', 'Web\OrganizeController@tree')->name('manage.organizes.tree');

    // doing background analytic - tạm thời chưa dùng
    Route::get('admin/analytic/citationAllArticle', [
        'uses' => 'Web\AnalyticController@updateCitation',
        'as' => 'admin.analytic'
    ]);

    // test mode
    Route::get('execute/test/{id_org_save}/{id_org_remove}', [
        'uses' => 'Web\OrganizeController@mergeOrganizes',
        'as' => 'test'
    ]);


    Route::get('test', 'Web\AnalyticController@testMode');

    //merge organizations
    Route::post('org/merge', 'Web\OrganizeController@merge')->name('org.merge');
    Route::get('org/revert', 'Web\OrganizeController@revert')->name('org.revert');
    Route::get('org/history', 'Web\OrganizeController@history')->name('org.history');
    Route::get('org/list', 'Web\OrganizeController@getAllView')->name('org.list');
    Route::get('org/similar', 'Web\OrganizeController@getSimilarOrgsProposed')->name('org.similar');
});
