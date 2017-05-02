<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/organizes/search', 'Api\OrganizeApiController@search')->name('organize.search');
Route::get('/organizes/search/jstree', 'Api\OrganizeApiController@jstreeSearch')->name('api.organize.search.jstree');
Route::get('/journals/search', 'Api\JournalApiController@search')->name('api.journal.search');

Route::post('/articles/updateBatch', 'Api\ArticleController@updateBatch');
Route::get('/articles/citation/{cite_id}/{cited_id}', 'Api\ArticleController@relationCitation')->name('articles.relation');

Route::get('/organizes/roots', 'Api\OrganizeApiController@roots')->name('api.organizes.roots');
Route::get('/organizes/{id}', 'Api\OrganizeApiController@show')->name('api.organizes.show');
Route::get('/organizes/{id}/children', 'Api\OrganizeApiController@children')->name('api.organizes.children');
Route::post('/organizes/create', 'Api\OrganizeApiController@store');
Route::post('/organizes/{id}/rename', 'Api\OrganizeApiController@rename');
Route::post('/organizes/{id}/delete', 'Api\OrganizeApiController@destroy');
Route::post('/organizes/{id}/move', 'Api\OrganizeApiController@move');
Route::post('/organizes/merge', 'Api\OrganizeApiController@mergeOrganizes');

Route::get('/subjects/roots', 'Api\SubjectApiController@roots')->name('api.subjects.roots');
Route::get('/subjects/{id}', 'Api\SubjectApiController@show')->name('api.subjects.show');
Route::get('/subjects/{id}/children', 'Api\SubjectApiController@children')->name('api.subjects.children');
Route::post('/subjects/search-journals', 'Api\JournalApiController@searchBySubject')->name('api.subjects.search_journals');
Route::post('/journals/{id}/updateSubjects', 'Api\JournalApiController@updateSubjects')->name('api.journal.updateSubjects');

// Organize update glink
Route::get('/organizes/{id}/updateglink', 'Api\OrganizeApiController@update_glink');


// Intergrate citation service
Route::get('/articles/needupdate', 'Api\IntegrateCitationApiController@getArticleNeedUpdate');
Route::get('/articles/{journal_id}/needupdate', 'Api\IntegrateCitationApiController@getArticleNeedUpdateByJournal');
Route::post('/articles/{id}/updatecitation', 'Api\IntegrateCitationApiController@updateCitationRaw');
Route::post('/articles/{id}/review-citation', 'Api\ReviewCitationApiController@review')->name('api.article.review_citation');
Route::post('/articles/review_citation/add-cite-view', 'Api\ReviewCitationApiController@addCiteView')->name('api.article.review_citation.add_cite_view');
Route::post('/articles/review_citation/author-input-tpl', 'Api\ReviewCitationApiController@authorInputTpl')->name('api.article.review_citation.author_input_tpl');
Route::post('/articles/review_citation/add-author-view', 'Api\ReviewCitationApiController@addAuthorView')->name('api.article.review_citation.add_author_view');


// Interarate elastic search
Route::get('/elasticsearch/getIdArticles', 'Api\IndexingElasticsearchApiController@getIdArticles');
Route::get('/elasticsearch/getInfoArticleFromSQL/{article_id}', 'Api\IndexingElasticsearchApiController@getInfoArticleFromSQL');
Route::get('/elasticsearch/getInfoAuthorFromSQL/{author_id}', 'Api\IndexingElasticsearchApiController@getInfoAuthorFromSQL');
Route::get('/elasticsearch/getInfoOrganizeFromSQL/{organize_id}', 'Api\IndexingElasticsearchApiController@getInfoOrganizeFromSQL');
Route::get('/elasticsearch/getInfoOrganizesFromSQL', 'Api\IndexingElasticsearchApiController@getInfoOrganizes');
Route::get('/elasticsearch/getInfoJournalFromSQL/{journal_id}', 'Api\IndexingElasticsearchApiController@getInfoJournalFromSQL');

// GET /elasticsearch/test
Route::get('/elasticsearch/test', 'Api\ElasticsearchApiController@test');
Route::get('/job/test', 'Api\job\IndexElasticsearch@test');
