<?php

use Illuminate\Database\Seeder;

class ExtractAuthors extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = \App\Models\Article::all();
        foreach ($articles as $article) {
            if (isset($article->author) && $article->author != "") {
                $authorsExtract = explode(",", $article->author);

                foreach ($authorsExtract as $authorExtract) {
                    $authorSaved = \App\Models\Author::create([
                        "name" => $this->removeWhiteSpace($authorExtract)
                    ]);
                    $article->assignAuthor($authorSaved);
                }
            }
        }
    }

    private function removeWhiteSpace($text)
    {
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = trim($text);
        return $text;
    }
}
