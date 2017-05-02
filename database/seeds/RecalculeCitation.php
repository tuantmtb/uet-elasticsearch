<?php

use App\Models\Article;
use Illuminate\Database\Seeder;

class RecalculeCitation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $article_count = 0;
        $articles = Article::whereNotNull('citation_raw')->get(['citation_raw', 'id', 'cites_count']);

        foreach ($articles as $article) {
            $rawCitationTrans = new \App\Http\Controllers\Api\RawCitationReviewedTrans();
            $citation = $rawCitationTrans->get_reviewed($article->citation_raw);
            if (isset($citation) && isset($citation->citedList)) {
                $count = sizeof($citation->citedList);
                if (isset($count) && $article->cites_count != $count) {
                    Log::debug('update id= ' . $article->id . ' count old = ' . $article->cites_count . ' new = ' . $count);
                    $article->cites_count = $count;
                    $article->save();
                    $article_count++;
                }

            }
        }
        Log::debug('done recaculate citation article count = ' . $article_count);
    }
}
