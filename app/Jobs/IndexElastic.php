<?php

namespace App\Jobs;

use App\Http\Controllers\Api\IndexingElasticsearchApiController;
use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use VciQueryES;

class IndexElastic implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        \Log::debug('construct: ');
        $this->indexArticles();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::debug('handle: ');

    }

    public function indexArticles()
    {
        $articles = Article::orderBy('updated_at', 'desc')->get();
        $stt = 0;
        foreach ($articles as $article) {
            $stt++;
            if ($stt == 80) break;
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
                    \Log::debug('res: ', $response);
                }

            } catch (\Exception $e) {
                \Log::debug('exception: ' . $e);
            }
        }
        \Log::debug('done jobs: ');
    }
}
