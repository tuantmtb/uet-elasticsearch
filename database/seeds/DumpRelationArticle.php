<?php

use App\Models\Article;
use App\Models\Journal;
use App\Models\ArticleRelation;
use Illuminate\Database\Seeder;

class DumpRelationArticle extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        $articleController = new \App\Http\Controllers\Api\ArticleController();

//        $pathFolder = 'resource_doc/dump/vjol/';
//        $this->dumpPerDirectory($pathFolder);
//
//        $pathFolder2 = 'resource_doc/dump/vnujs/';
//        $this->dumpPerDirectory($pathFolder2);
        $this->dumpBatchArticles("resource_doc/dump/total-dump/vjol-total.json");
        $this->dumpBatchArticles("resource_doc/dump/total-dump/vnujs-total.json");

    }

    // normalize author
    private function bibtextNormalize($str)
    {
        $rules = [
            ['{\`a}', 'à'],
            ["{\'a}", 'á'],
            ['{\~a}', 'ã'],
            ['{\u{a}}', 'ă'],
            ['{\^a}', 'â'],

            ['{\`A}', 'À'],
            ["{\'A}", 'Á'],
            ['{\~A}', 'Ã'],
            ['{\u{A}}', 'Ă'],
            ['{\^A}', 'Â'],

            ['{\`e}', 'è'],
            ["{\'e}", 'é'],
            ['{\~e}', 'ẽ'],
            ['{\^e}', 'ê'],

            ['{\`E}', 'È'],
            ["{\'E}", 'É'],
            ['{\~E}', 'Ẽ'],
            ['{\^E}', 'Ê'],

            ['{\`o}', 'ò'],
            ["{\'o}", 'ó'],
            ['{\~o}', 'õ'],
            ['{\^o}', 'ô'],

            ['{\`O}', 'Ò'],
            ["{\'O}", 'Ó'],
            ['{\~O}', 'Õ'],
            ['{\^O}', 'Ô'],

            ['{\`u}', 'ù'],
            ["{\'u}", 'ú'],
            ['{\~u}', 'ũ'],
            ['{\"u}', 'ü'],


            ['{\`U}', 'Ù'],
            ["{\'U}", 'Ú'],
            ['{\~U}', 'Ũ'],


            ['{\`y}', 'ỳ'],
            ["{\\'y", 'ý'],

            ['{\`Y}', 'Ỳ'],
            ["{\\'Y}", 'Ý'],

            ['{\~\i}', 'ĩ'],
            ['{\`\i}', 'ì'],

            ["{\\'\\i}", 'í'],
            ["{\\'i}", 'í'],
            ['{\`i}', 'ì'],

            ["{\\'\\I}", 'Í'],
            ["{\\'I}", 'Í'],
            ['{\~\I}', 'Ĩ'],
            ['{\`\I}', 'Ì'],
            ['{\`I}', 'Ì'],

            ['{\DJ}', 'Đ'],
            ['{\dj}', 'đ'],


        ];
        $str = $this->decodeStringbibtex($rules, $str);
        return $str;
    }

    private function decodeStringbibtex($rules, $str)
    {
        foreach ($rules as $rule) {
            $str = str_replace($rule[0], $rule[1], $str);
        }
        return $str;
    }


    private function dumpPerDirectory($pathFolder)
    {
        $filesInFolder = File::allFiles($pathFolder);
        foreach ($filesInFolder as $path) {
//            try {
            $pathLink = pathinfo($path);
            $link = $pathLink["dirname"] . "/" . $pathLink["basename"];
            $this->dumpPerArtile($link);
//            } catch (Exception $e) {
//                $e->getMessage();
//            }
        }
    }

    private function dumpBatchArticles($filePath)
    {
//        $json_path = base_path('resource_doc/dump/vjol/1 2.json');
        $results = json_decode(file_get_contents($filePath), true);
        foreach ($results as $result) {
            /**
             * @var Article $article
             */
            $article = $this->createOrUpdateArticle($result);
            // citation
            if (isset($result["citedList"])) {
                $citationArticles = $result["citedList"];
                $count = count($citationArticles);
//                dd($count);
                $c = 0;
                for ($pos = 0; $pos < $count; $pos++) {
                    $citationArticle = $citationArticles[$pos];
                    $citationArticleSaved = $this->createOrUpdateArticle($citationArticle);
                    if ($citationArticleSaved && $citationArticleSaved->count() > 0) {
                        if (!$article->cites->contains($citationArticleSaved->id)) {
////                        // chưa có relation đến nhau
                            $c++;
                            $article->assignCitation($citationArticleSaved);
//                        $x = ArticleRelation::create([
//                            'cite_id' => $article->id,
//                            'cited_id' => $citationArticleSaved->id
//                        ]);

                        };
                    }
                }
            }
        }

    }

    private function dumpPerArtile($filePath)
    {
//        $json_path = base_path('resource_doc/dump/vjol/1 2.json');
        $json_path = base_path($filePath);
        $result = json_decode(file_get_contents($json_path), true);
        /**
         * @var Article $article
         */
        $article = $this->createOrUpdateArticle($result);
        // citation
        if (isset($result["citedList"])) {
            $citationArticles = $result["citedList"];
            $count = count($citationArticles);
//                dd($count);
            $c = 0;
            for ($pos = 0; $pos < $count; $pos++) {
                $citationArticle = $citationArticles[$pos];
                $citationArticleSaved = $this->createOrUpdateArticle($citationArticle);
                if ($citationArticleSaved && $citationArticleSaved->count() > 0) {
                    if (!$article->cites->contains($citationArticleSaved->id)) {
////                        // chưa có relation đến nhau
                        $c++;
                        $article->assignCitation($citationArticleSaved);
//                        $x = ArticleRelation::create([
//                            'cite_id' => $article->id,
//                            'cited_id' => $citationArticleSaved->id
//                        ]);

                    };
                }
            }
        }
    }

    private function createOrUpdateArticle($result)
    {
        $journal = $this->findOrCreateJournal($result["journalName"]);

        $articleFind = $this->searchArticle($result);
        $article = null;
        if ($articleFind) {
            // found an update
//            dd($articleFind);
            $article = $articleFind;

            // update
            $article = $this->updateArticle($result, $article);

        } else {
            // create
//            $article = App\Article::create([
//                'title' => $result["title"],
//                'abstract' => $result["abstracts"],
//                'uri' => $result["uri"],
//                'author' => $result["authors"],
//                'volume' => $result["volume"],
//                'year' => $result["year"],
//                'source' => $result["source"],
//                'usable' => $result["usable"],
//                'journal_id' => $journal->id,
//                'cluster_id' => $result["clusterId"],
//                'cites_id' => $result["citeId"],
//            ]);
            $article = $this->updateArticle($result, null);
        }
        $article->save();
        return $article;
    }

    /**
     *
     * update info article
     * @param $result
     * @param Article $article
     */
    private function updateArticle($result, $article)
    {
        if ($article) {

        } else {
            // can not found article by id
            $article = new Article();
        }

        $journal = $this->findOrCreateJournal($result["journalName"]);
        // update article đã có trong csdl
        // found article by id
        $title = $result["title"];
        $abstracts = $result["abstracts"];
        $uri = $result["uri"];
        $author = $result["authors"];
        $volume = $result["volume"];
        $year = $result["year"];
        $source = $result["source"];
        $journal_id = $journal->id;
        $clusterId = null;

        if (isset($result["clusterId"])) {
            $clusterId = $result["clusterId"];

        };

        $citeId = null;
        if (isset($result["citeId"])) {
            $citeId = $result["citeId"];
        }

        if (isset($title)) {
            $article->title = $title;
        }
        if ($abstracts) {
            $article->abstract = $abstracts;
        }
        if ($uri) {
            $article->uri = $uri;
        }
        if (isset($author)) {
            // normalize
            $article->author = $this->reFormatAuthorFromBibtex($this->bibtextNormalize($author));
        }
        if ($volume) {
            $article->volume = $volume;
        }
        if ($year) {
            $article->year = $year;
        }
        if ($source) {
            $article->source = $source;
        }
        if ($journal_id) {
            $article->journal_id = $journal_id;
        }
        if (isset($clusterId)) {
            $article->cluster_id = $clusterId;
        }
        if (isset($citeId)) {
            $article->cites_id = $citeId;
        }

        $article->save();

        // extract authors and add relation
        if (isset($article->author)) {
            $authorsExtract = explode(",", $article->author);

            foreach ($authorsExtract as $authorExtract) {
                $authorSaved = \App\Models\Author::create([
                    "name" => $authorExtract
                ]);
                $article->assignAuthor($authorSaved);
            }

        }

        return $article;
    }


    private function searchArticle($result)
    {
        $article = null;
        $articles = null;
//        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
//            return $articles->first();
//        } else {
//            if (isset($result["clusterId"])) {
//                // Gửi đi cả cluster id
//                $articles = Article::whereClusterId($result["clusterId"])->get();
//            }
//        }
//
//        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
//            return $articles->first();
//        } else {
//            if (isset($result["citeId"])) {
//                $articles = Article::whereCitesId($result["citeId"])->get();
//            }
//        }

        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
            return $articles->first();
        } else {
            if (isset($result["title"])) {
                $articles = Article::whereTitle($result["title"])->get();
            }
        }
//        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
//            return $articles->first();
//        } else {
//            if (isset($result["uri"])) {
//                $articles = Article::whereUri($result["uri"])->get();
//            }
//        }
//        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
//            return $articles->first();
//        } else {
//            if (isset($result["title"])) {
//                $articles = Article::whereTitle($result["title"])->get();
//            }
//        }
        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
            $article = $articles->first();
            return $article;
        } else {
            // not found article
            return null;
        }
    }

    /**
     * Tìm chuyên san
     * @param $journalname
     * @return array|\Illuminate\Database\Eloquent\Model|null|stdClass|static
     */
    private function findOrCreateJournal($journalname)
    {
        $journalname = trim($journalname);
        if ($journalname != "") {
            $journals = Journal::whereName($journalname);
            if ($journals != null && $journals->count() > 0) {
                // found journal by name
                $journal = $journals->first();
                return $journal;
            } else {
                // create journal
                $journal = Journal::create([
                    'name' => $journalname
                ]);
                return $journal;
            }
        } else {
            return $journals = Journal::whereName('Chưa phân loại')->first();
        }
    }

    /**
     * @param $author
     * @return mixed
     * "Thuat, Bui Quang and Ngoc, Bui Thi Bich and others" -> "Bui Quang Thuat, Bui Thi Bich Ngoc"
     */
    public function reFormatAuthorFromBibtex($author)
    {
        $author = str_replace('and others', 'and , others', $author); // clear and others
        $authorsSeparate = explode('and', $author);
        $authorsFullname = "";
        foreach ($authorsSeparate as $authorSeparate) {
            $authorMultipath = explode(',', $authorSeparate);
            if ($authorMultipath != null && count($authorMultipath) == 2) {
                $authorFullname = trim($authorMultipath[1] . ' ' . $authorMultipath[0]);
                if ($authorsFullname != "") {
                    $authorsFullname .= ', ' . $authorFullname;
                } else {
                    $authorsFullname .= $authorFullname;
                }
            }
        }
        return $this->removeWhiteSpace($authorsFullname);
    }

    private function removeWhiteSpace($text)
    {
        if ($text != null && $text != "") {
            $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
            $text = preg_replace('/([\s])\1+/', ' ', $text);
            $text = trim($text);
        }
        return $text;
    }
}
