<?php

use Illuminate\Database\Seeder;
use SoapBox\Formatter\Formatter;
use App\Models\Journal;
use App\Models\Article;
use App\Models\Author;
use App\Models\Organize;


class dumpOJSExport extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $filePath = "resource_doc/dump/dump_jsvnu";
        $filePath = "resource_doc/dump/09042017/jsvnu-articles_dump_2017-04-09";
        $this->dumpPerDirectory($filePath);
    }

    private function dumpPerDirectory($pathFolder)
    {
        $filesInFolder = File::allFiles($pathFolder);
        foreach ($filesInFolder as $path) {
            try {
                $pathLink = pathinfo($path);
                $link = $pathLink["dirname"] . "/" . $pathLink["basename"];
                $this->execute($link);
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }


    public function execute($filePath)
    {
        $results = file_get_contents($filePath);
        $formatter = Formatter::make($results, Formatter::XML);
        $serialized = $formatter->toArray();
        $results = $serialized["record"];
//        $duplicate_count = 0;
//        $create_count = 0;

        foreach ($results as $result) {
            $article = $this->createOrUpdateArticle($result);

        }

//        Log::debug("create_count = " . $create_count);
//        Log::debug("duplicate_count = " . $duplicate_count);

        return response()->json($formatter->toArray());

    }

    private
    function createOrUpdateArticle($result)
    {
        $createrModel = new CreaterModelHelper();
        $journal = $createrModel->findOrCreateJournal($result["journalTitle"]);

        $articleFind = $this->searchArticle($result);
        $article = null;
        if ($articleFind) {
            // found an update
//            dd($articleFind);
            $article = $articleFind;

            // update
//            $article = $this->updateArticle($result, $article);
//            bỏ qua nếu trùng
            return null;
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

//    private function findOrCreateJournal($journalname)
//    {
//        $journalname = trim($journalname);
//        if ($journalname != "") {
//            $journals = Journal::whereName($journalname);
//            if ($journals != null && $journals->count() > 0) {
//                // found journal by name
//                $journal = $journals->first();
//                return $journal;
//            } else {
//                // create journal
//                $journal = Journal::create([
//                    'name' => $journalname
//                ]);
//                return $journal;
//            }
//        } else {
//            return $journals = Journal::whereName('Chưa phân loại')->first();
//        }
//    }


    private
    function searchArticle($result)
    {
        $article = null;
        $articles = null;

        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
            return $articles->first();
        } else {
            if (isset($result["title"])) {
                $articles = Article::whereTitle($result["title"])->get();
            }
        }
        if ($articles != null && $articles->count() > 0 && $articles->first() != null) {
            $article = $articles->first();
            return $article;
        } else {
            // not found article
            return null;
        }
    }

    private
    function updateArticle($result, $article)
    {
        if ($article) {

        } else {
            // can not found article by id
            $article = new Article();
        }

        $createModel = new CreaterModelHelper();
        $journal = $createModel->findOrCreateJournal($result["journalTitle"]);
        // update article đã có trong csdl
        // found article by id
//        dd($result);

        if (count($result["title"]) > 1) {
//            dd($result["title"][0]);
            //            $x = gettext($result["title"][0]);
            $title = $result["title"][0];

        } else {
            $title = preg_replace("/(\r\n){3,}/", "\r\n\r\n", trim($result["title"]));
        }
        if (count($result["abstract"]) > 1) {
            $abstracts = $result["abstract"][0];
        } else {
            $abstracts = $result["abstract"];
        }

        $uri = $result["fullTextUrl"];
//        $authors = $result["authors"]["author"]; // deprecated because have table authors
        $volume = $result["volume"];
        $number = $result["issue"];
        $year = substr($result["publicationDate"], 0, 4);
        $source = $result["fullTextUrl"];
        $journal_id = $journal->id;

        if (isset($title) && $title != "") {
            $article->title = \App\Facade\VciHelper::removeWhiteSpace($title);
        }
        if ($abstracts) {
            $article->abstract = $abstracts;
        }
        if ($uri) {
            $article->uri = $uri;
        }
//        if ($authors) {
        // normalize
//            $article->author = $this->extractAuthorsArray($authors);
//        }

        if ($volume) {
            $article->volume = $volume;
        }
        if ($year) {
            $article->year = $year;
        }
        if ($number) {
            $article->number = $number;
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

        // create author
        $authors_data = $result["authors"];

        $affiliationsList = null;
        if (isset($result["affiliationsList"])) {
            $affiliationsList = $result["affiliationsList"];
            // createListAffiliationList
            $this->createListAffiliationList($affiliationsList);
        }

        $this->createAuthor($article, $authors_data, $affiliationsList);
        return $article;
    }

    private
    function extractAuthorsArray($authors)
    {

        $output = "";
        foreach ($authors as $author) {
            if (isset($author["name"])) {
                if ($output == "") {
                    $output .= $author["name"];
                } else {
                    $output .= ', ' . $author["name"];
                }
            }

        }
        return $output;
    }

    /**
     * @param $article Article
     * @param $authors_data
     * @param $affiliationsList
     */
    private
    function createAuthor($article, $authors_data, $affiliationsList)
    {
        if ($article == null || $authors_data == null) return;


        // tạm thời tạo mới author
        // todo: find and match author: follow name and organize
        if (isset($authors_data["author"]["name"]) && $authors_data["author"]["name"] != null) {
            // have only 1 author
            /**
             * @var $author_create Author
             */
            $author_create = Author::create([
                'name' => $authors_data["author"]["name"],
                'email' => $authors_data["author"]["email"]
            ]);
            // create relation author_article
            $article->authors()->attach($author_create->id);
            if (isset($authors_data["author"]["affiliationId"])) {
                if (isset($affiliationsList)) {
                    if (gettype($authors_data["author"]["affiliationId"]) == "string") {

                        $org_name = $affiliationsList["affiliationName"];
                        /**
                         * @var $organize Organize
                         */
                        $organize = $this->findOrCreateOrganize($org_name);
//          unset organize for author
                        if ($organize != null && $organize->id) {
                            $author_create->organizes()->attach($organize->id);
                        }

                    }
                }
            }

        } else {

            // multiple author
            foreach ($authors_data["author"] as $author) {
                if (isset($author["name"])) {
                    /**
                     * @var $author_create Author
                     */
                    $author_create = Author::create([
                        'name' => $author["name"],
                        'email' => $author["email"]
                    ]);

                    // create relation author_article
                    $article->authors()->attach($author_create->id);

                    // check have infomation about affiliation
                    if (isset($author["affiliationId"])) {
                        if (isset($affiliationsList)) {
                            if (gettype($author["affiliationId"]) == "string") {
                                $org_name = $affiliationsList["affiliationName"][$author["affiliationId"]];
                                /**
                                 * @var $organize Organize
                                 */
                                // unset organize
//                                $organize = $this->findOrCreateOrganize($org_name);
//
//                                if ($organize != null && $organize->id) {
//                                    $author_create->organizes()->attach($organize->id);
//                                }


//                        if ($affiliationsList != null && is_array($affiliationsList) && count($affiliationsList) > 1) {
//                            dd("sad");
                                //                        dd($affiliationsList);
//                        dd($author["affiliationId"]);
//                        dd($affiliationsList[$author["affiliationId"]]);
//                        } else {
                                // article have only 1 author
//                        dd($affiliationsList["affiliationName"]);
//                        }

                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Tạo array list aff theo từng bài
     * @param $affiliationsList
     */
    private
    function createListAffiliationList($affiliationsList)
    {
        if ($affiliationsList == null) return;
        try {

            if (is_array($affiliationsList)
                && is_array($affiliationsList["affiliationName"])
                && count($affiliationsList["affiliationName"]) > 1
            ) {
                // multipal aff
                foreach ($affiliationsList["affiliationName"] as $aff) {
                    if ($aff != null && gettype($aff) == "string" && $aff != "") {
                        // fixed empty string affiliationName
//                        $org = Organize::create([
//                            'name' => $aff
//                        ]);
//                        $org = $this->findOrCreateOrganize($aff);
                    }
                }

            } else {
                if ($affiliationsList["affiliationName"] != null && $affiliationsList["affiliationName"] != "") {
//                    $this->findOrCreateOrganize($affiliationsList["affiliationName"]);
                    //                    $org = Organize::create([
//                        'name' => $affiliationsList["affiliationName"]
//                    ]);
                }
            }
        } catch (\Exception $e) {

        }

    }

    private
    function findOrCreateOrganize($nameOrganize)
    {
        if ($nameOrganize == null || gettype($nameOrganize) != "string") {
            return $organizes = Organize::whereName('Chưa phân loại')->first();
        }

        $nameOrganize = trim($nameOrganize);
        if ($nameOrganize != "") {
            $organizes = Organize::whereName($nameOrganize);
            if ($organizes != null && $organizes->count() > 0) {
                // found journal by name
                $organize = $organizes->first();
                return $organize;
            } else {
                // create journal
                $organize = Organize::create([
                    'name' => $nameOrganize
                ]);
                return $organize;
            }
        } else {
            return $organizes = Organize::whereName('Chưa phân loại')->first();
        }
    }


}
