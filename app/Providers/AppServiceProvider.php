<?php

namespace App\Providers;

use App\Facade\VciQueryES;
use App\Http\Controllers\Api\IndexingElasticsearchApiController;
use App\Models\Article;
use App\Models\Journal;
use App\Models\Organize;
use Illuminate\Support\ServiceProvider;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Article::saved(function ($article) {
            try {
                $id = $article->id;

                $ESindexing = new IndexingElasticsearchApiController();

                $params = [
                    'index' => 'test',
                    'type' => 'article',
                    'id' => $id,
                    'body' => $ESindexing->getInfoArticle($id)
                ];
                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->index($params);
                }
            } catch (\Exception $e) {

            }

            return true;
        });

        Article::deleted(function ($article) {
            try {
                $id = $article->id;
                $params = [
                    'index' => 'test',
                    'type' => 'article',
                    'id' => $id
                ];
                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->delete($params);
                }
            } catch (\Exception $e) {

            }
            return true;

        });

        Organize::saved(function ($organize) {
            try {


                $id = $organize->id;
                $ESindexing = new IndexingElasticsearchApiController();

                $params = [
                    'index' => 'test',
                    'type' => 'organize',
                    'id' => $id,
                    'body' => $ESindexing->getInfoOrganize($id)
                ];
                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->index($params);
                }
            } catch (\Exception $e) {

            }

            return true;
        });

        Organize::deleted(function ($organize) {
            try {
                $id = $organize->id;
                $params = [
                    'index' => 'test',
                    'type' => 'organize',
                    'id' => $id
                ];
                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->delete($params);
                }
            } catch (\Exception $e) {

            }
            return true;
        });

        Journal::saved(function ($journal) {
            try {


                $id = $journal->id;
                $ESindexing = new IndexingElasticsearchApiController();

                $params = [
                    'index' => 'test',
                    'type' => 'journal',
                    'id' => $id,
                    'body' => $ESindexing->getInfojournal($id)
                ];
                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->index($params);
                }
            } catch (\Exception $e) {

            }

            return true;
        });

        Journal::deleted(function ($journal) {
            try {
                $id = $journal->id;
                $params = [
                    'index' => 'test',
                    'type' => 'journal',
                    'id' => $id
                ];
                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->delete($params);
                }
            } catch (\Exception $e) {

            }
            return true;
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
