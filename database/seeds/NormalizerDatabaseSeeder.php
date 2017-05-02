<?php

use App\Models\Article;
use App\Models\Author;
use Illuminate\Database\Seeder;

class NormalizerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Article::whereRaw('LENGTH(title) < 20')->delete();
//        Article::where('author', 'LIKE', "%}%")->delete();
//        Article::where('title', 'LIKE', '%mục lục%')->delete();
//        Article::where('title', 'LIKE', '%MỤC LỤC%')->delete();
//        Article::where('title', 'LIKE', '%Giới Thiệu Sách%')->delete();
//        Article::where('title', 'LIKE', '%Lời giới thiệu%')->delete();
//        Article::where('title', 'LIKE', '%Đời sống - Tư liệu%')->delete();
//        Article::where('title', 'LIKE', '%Table of Contents%')->delete();
//        Article::where('title', 'LIKE', '%Thông báo của Hội%')->delete();
//        Article::where('author', 'LIKE', '%Ban Biên Tập%')->delete();

        // replace year
//        $this->normalizeArticle();
//        $this->normalizeTitleArticle();
//        $this->deleteDuplicate();
//        $this->deleteAuthorEmpty();
        $this->normalizeAuthor();
    }

    private function deleteAuthorEmpty()
    {

        /**
         * @var Author $authors , $author
         */
        $authors = Author::withCount('articles')->get();

        foreach ($authors as $author) {
            if ($author->articles_count == 0) {
//                logger("delete author: " . $author->id);
                $author->delete();
            }
        }

    }


    private function normalizeArticle()
    {
        $journals = \App\Models\Journal::all();
        foreach ($journals as $journal) {
            $journal->name = VciHelper::bibtextNormalize($journal->name);
            $journal->save();
        }
    }


    private function normalizeTitleArticle()
    {
        /**
         * @var Article $articles , $article
         */
        $articles = Article::all();
        foreach ($articles as $article) {
            $article->title = \App\Facade\VciHelper::removeWhiteSpace($article->title);
            $article->save();
        }
    }

    private function deleteDuplicate()
    {
        $articles_non_reviewd = Article::all();
        $count_delete = 0;

        foreach ($articles_non_reviewd as $article) {
            $find_count = Article::whereTitle($article->title)->count();
            if ($find_count > 1) {
                $count_delete++;
                Log::debug('duplicate: count = ' . $find_count . ' =' . $article->title);
                $article->delete();
            }
        }
        Log::debug('deleted  count = ' . $count_delete);
    }

    private function normalizeAuthor()
    {
        $authors = Author::all();
        foreach ($authors as $author) {
            $author->name = \App\Facade\VciHelper::removeWhiteSpace($author->name);
            $author->save();
        }
    }

}
