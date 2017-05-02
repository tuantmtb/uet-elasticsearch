<?php

use App\Http\Controllers\Api\IndexingElasticsearchApiController;
use App\Models\Article;
use Illuminate\Database\Seeder;

class IndexArticleExecute extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->indexArticles();
    }

    public function indexArticles()
    {
        $articles = Article::orderBy('updated_at', 'desc')->get();

        foreach ($articles as $article) {
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
                    \Log::debug('indexed article id= : ' . $id);
                }

            } catch (\Exception $e) {
                \Log::debug('indexArticles exception: ' . $e);
            }
        }
        \Log::debug('indexArticles done ');
    }
}
