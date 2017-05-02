<?php

use Illuminate\Database\Seeder;
use App\Models\Journal;
use App\Models\Organize;
use App\Models\Article;
use App\Models\Author;


class js_dump_db extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $filePath_vjol = 'resource_doc/dump/pubs.json';
//        $this->importArticleBatch($filePath_vjol);

//        $filePath_vjs_ac_vn = 'resource_doc/dump/vjs-total-final.json'; // old format before 18-02-2017
//        $filePath_vjs_ac_vn = 'resource_doc/dump/finalPubsVJS_18022017.json'; // cu
//        $filePath_vjs_ac_vn = 'resource_doc/dump/04042017/finalPubsVJS.json'; //moi
//        $filePath_vjs_ac_vn = 'resource_doc/dump/04042017/vjchem.json'; //moi
//        $filePath_vjs_ac_vn = 'resource_doc/dump/06042017/ussh_final.json'; //moi

//        $this->importArticleBatch('resource_doc/dump/1432017/khcn.vimaru.edu.vn-04-14.json');
//        $this->importArticleBatch('resource_doc/dump/1432017/other.json');
//        $this->importArticleBatch('resource_doc/dump/1432017/vafs.gov.vn-04-14.json');
//        $this->importArticleBatch('resource_doc/dump/16042017/articles.json');
//        $this->importArticleBatch('resource_doc/dump/18042017/vjchem.json');
//        $this->importArticleBatch('resource_doc/dump/18042017/amv.json', true);

//        $this->importArticleBatch('resource_doc/dump/18042017/bmrat.json', true);
//        $this->importArticleBatch('resource_doc/dump/19042017/link.springer.com-04-19.json', true);
//        $this->importArticleBatch('resource_doc/dump/19042017/tapchivatuyentap.tlu.edu.vn-04-19.json', true);
//        $this->importArticleBatch('resource_doc/dump/19042017/vnua.edu.vn-04-19.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/joshueuni.edu.vn-04-20.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/sajs.ntt.edu.vn-04-20.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/tcptkt.ueh.edu.vn-04-20.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/tdt.edu.vn-04-20.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/bmrat.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/stemcell.json', true);
//        $this->importArticleBatch('resource_doc/dump/20042017/vjol_nh.json', false);
//        $this->importArticleBatch('resource_doc/dump/21042017/amv_vol_31_37.json', false);
//        $this->importArticleBatch('resource_doc/dump/21042017/jst_2012_2013.json', false);
//        $this->importArticleBatch('resource_doc/dump/21042017/joshueuni.edu.vn-04-21.json', true);
//        $this->importArticleBatch('resource_doc/dump/21042017/tcptkt.ueh.edu.vn-04-21.json', true);


//        $this->importArticleBatch('resource_doc/dump/22042017/joshueuni.edu.vn-04-22.json', true);
//        $this->importArticleBatch('resource_doc/dump/22042017/tdt.edu.vn-04-22.json', true);

//        $this->importArticleBatch('resource_doc/dump/22042017/CongNgheNganHang_2015-2017.json', true);
//        $this->importArticleBatch('resource_doc/dump/24042017/eastwestmath.org-04-24.json', true);
//        $this->importArticleBatch('resource_doc/dump/24042017/ntu.edu.vn-04-24.json', true);
//        $this->importArticleBatch('resource_doc/dump/24042017/sj.ctu.edu.vn-04-24.json', true);
//        $this->importArticleBatch('resource_doc/dump/24042017/stdb.hnue.edu.vn-04-24.json', true);
//        $this->importArticleBatch('resource_doc/dump/24042017/tapchikhcn.udn.vn-04-24.json', true);
//        $this->importArticleBatch('resource_doc/dump/24042017/vnuf.edu.vn-04-24.json', true);

// update info keyword
//        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/joshueuni.edu.vn-04-21.json', true);
//        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/khcn.vimaru.edu.vn-04-14.json', true);
//        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/link.springer.com-04-19.json', true);
//        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/other.json', true);
//        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/sajs.ntt.edu.vn-04-20.json', true);
        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/tapchivatuyentap.tlu.edu.vn-04-19.json', true);
        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/tcptkt.ueh.edu.vn-04-21.json', true);
        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/tdt.edu.vn-04-20.json', true);
        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/vafs.gov.vn-04-14.json', true);
        $this->importArticleBatch('resource_doc/dump/27042017-updateinfo/vnua.edu.vn-04-19.json', true);

    }

    private function importArticleBatch($filePath, $is_reviewed = false)
    {

        $json_path = base_path($filePath);
        $results = json_decode(file_get_contents($json_path), true);

        $number_article_added = 0;
        $number_article_skipped = 0;
        foreach ($results as $result) {
            isset($result["title"]) ? $result["title"] = \App\Facade\VciHelper::removeWhiteSpace($result["title"]) : null;
            $journal = $this->findOrCreateJournal($result["journalName"]);
            // search article
            if (App\Models\Article::where('title', '=', $result["title"])->count() > 0) {
                $number_article_skipped++;
                $article_existed = App\Models\Article::where('title', '=', $result["title"])->first();

                if (isset($result["abstracts"])) {
                    $article_existed->abstract = $result["abstracts"];
                }
                if (isset($result["volume"])) {
                    $article_existed->volume = $result["volume"];
                }
                if (isset($result["number"])) {
                    $article_existed->number = $result["number"];
                }
                if (isset($result["year"])) {
                    $article_existed->year = $result["year"];
                }
                if (isset($result["keyword"])) {
                    $article_existed->keyword = $result["keyword"];
                }

                if (isset($result["uri"])) {
                    $article_existed->uri = $result["uri"];
                }

                if (isset($result["references"])) {
                    $article_existed->reference = $result["references"];
                }

                if (isset($result["DOI"])) {
                    $article_existed->doi = $result["DOI"];
                }

                if (isset($result["source"])) {
                    $article_existed->source = $result["source"];
                }

                if (isset($result["citedNumber"])) {
                    $article_existed->cites_count = $result["citedNumber"];
                }

                if (isset($result["is_reviewed"]) && $result["is_reviewed"] == true) {
                    $article_existed->is_reviewed = 1;
                } else {
                    $article_existed->is_reviewed = null;
                }

//
//                // authors
//                // clear authors
                $article_existed->authors()->detach();
//
//                // import author
                foreach ($result["authors"] as $author) {
                    /**
                     * @var $author_create Author
                     */
                    $author_create = Author::create([
                        'name' => $author["name"],
                        'email' => isset($author["email"]) ? $author["email"] : null
                    ]);

                    // create relation
                    $article_existed->authors()->attach($author_create->id);

                    // findOrCreate Organize
                    if (isset($author["affiliation"]) && $author["affiliation"] != "") {
                        $organize = $this->findOrCreateOrganize($author["affiliation"]);
                        $author_create->organizes()->attach($organize->id);
                    }
                }

                $article_existed->save();
                continue;
            } else {
                $number_article_added++;
                $article = App\Models\Article::create([
                    'title' => $result["title"],
                    'abstract' => $result["abstracts"],
                    'uri' => $result["uri"],
                    'volume' => $result["volume"],
                    'number' => $result["number"],
                    'year' => $result["year"],
                    'source' => $result["source"],
                    'is_reviewed' => (isset($is_reviewed) && $is_reviewed == true) ? 1 : null,
                    'usable' => $result["usable"],
                    'journal_id' => $journal->id,
                    'reference' => isset($result["references"]) ? $result["references"] : null,
                    'keyword' => isset($result["keywords"]) ? $result["keywords"] : null,
                    'doi' => isset($result["DOI"]) ? $result["DOI"] : null,

                ]);
                $article->save();

                // import author
                foreach ($result["authors"] as $author) {
                    /**
                     * @var $author_create Author
                     */
                    $author_create = Author::create([
                        'name' => $author["name"],
                        'email' => isset($author["email"]) ? $author["email"] : null
                    ]);

                    // create relation
                    $article->authors()->attach($author_create->id);

                    // findOrCreate Organize
                    if (isset($author["affiliation"]) && $author["affiliation"] != "") {
                        $organize = $this->findOrCreateOrganize($author["affiliation"]);
                        $author_create->organizes()->attach($organize->id);
                    }
                }
            }
        }
        Log::debug($filePath . ' added: ' . $number_article_added);
        Log::debug($filePath . ' skipped: ' . $number_article_skipped);
    }

    private function findOrCreateJournal($journalname)
    {
        $journals = \App\Models\Journal::whereName($journalname);
        if ($journals != null && $journals->count() > 0) {
            // found journal by name
            $journal = $journals->first();
            return $journal;
        } else {
            // create journal
            $journal = \App\Models\Journal::create([
                'name' => $journalname
            ]);
            return $journal;
        }
    }

    private function findOrCreateOrganize($nameOrganize)
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
