<?php

use App\Models\Article;
use App\Models\Journal;
use Illuminate\Database\Seeder;

class ValidateDataChecker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->validateCitationRaw();
    }

    private function validateCitationRaw()
    {

        $articles_output = [];
        $articles = Article::whereNotNull('citation_raw')->newQuery()->where('citation_raw', 'NOT LIKE', "")->where('citation_raw', 'NOT LIKE', '%}')->get();

        foreach ($articles as $article) {
            $article_out = [];
            $article_out["id"] = $article->id;
            $article_out["title"] = $article->title;
            $article_out["journalName"] = Journal::find($article["journal_id"])->name;
            $articles_output[] = $article_out;

        }

        Log::debug($articles_output);
    }
}
