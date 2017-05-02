<?php

namespace App\Facade;

use App\Models\Article;
use App\Http\Controllers\Api\RawCitationReviewedTrans;
use App\Models\Journal;
use App\Models\JournalInternational;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Log;

class VciCitationExtractor extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'VciCitationExtractor';
    }

    /**
     * @param Article $article
     */
    public static function getCitation($article)
    {
        $output = [];
//        $cites_count = 0;

        $citationExtractor = new RawCitationReviewedTrans();
        if ($article != null) {
            if ($article->citation_raw != null && $article->citation_raw != "") {
//            return $article->citation_raw;

                $citation_reviewed = $citationExtractor->get_reviewed($article->citation_raw);
//                dd($citation_reviewed);

                // extract citation_raw_reviewed
                if ($citation_reviewed != null && $citation_reviewed != "") {

                    foreach ($citation_reviewed->citedList as $cite) {
//                        $cites_count++;
                        $e_citation = [
                            'title' => $cite->title,
                            'hash' => ($cite->title != null && $cite->title != "") ? hash("crc32", $cite->title) : null,
                            'uri' => $cite->uri,
                            'journalName' => $cite->journalName,
                            'volume' => $cite->volume,
                            'number' => $cite->number,
                            'year' => ($cite->year != null && $cite->year != "") ? $cite->year : 0,
                            'authors' => $cite->authors,
                            'modifiedIn' => $cite->modifiedIn,
                            'createdAt' => $cite->createdAt,
                            'typeCitation' => VciCitationExtractor::getTypeCitationFromArticle($article, $cite)
                        ];
                        $output[] = $e_citation;
                    }
//                    $article->cites_count = $cites_count;
//                    $article->save();
                }
            }
        }
        return $output;

    }

    /**
     * HELPER FUNCTION
     * Return type citation: Self, VCI, Scopus or ISI
     * @param $article
     * @param $cite
     * @return array
     */
    public static function getTypeCitationFromArticle($article, $cite)
    {
        $journal_org = $article->journal->name;
        $journal_citation = $cite->journalName;


        $output = [];
//        log::debug($article);

        // todo: remove
        // vì ngày trước citation_self được hiểu như là citation_self_journal
        if ($journal_org != null && $journal_org != '' && strtolower($journal_org) == strtolower($journal_citation)) {
            $output["citation_self"] = 1;
        } else {
            $output["citation_self"] = 0;
        }

        if ($journal_org != null && $journal_org != '' && strtolower($journal_org) == strtolower($journal_citation)) {
            $output["citation_self_journal"] = 1;
        } else {
            $output["citation_self_journal"] = 0;
        }

        if ($journal_citation != null && $journal_citation != '' && Journal::whereName($journal_citation)->count() >= 1) {
            $output["citation_vci"] = 1;
        } else {
            $output["citation_vci"] = 0;
        }

        if ($journal_citation != null && $journal_citation != '' && JournalInternational::whereTitle($journal_citation)->count() >= 1) {
            $output["citation_scopus_isi"] = 1;
        } else {
            $output["citation_scopus_isi"] = 0;
        }

        // citation_self_article
        // trích dẫn từ các bài báo mà có chung ít nhất một tác giả
        $count_citation_self_article = 0;
        foreach ($cite->authors as $author_cite) {
            foreach ($article->authors as $author_article) {
                if (isset($author_cite->name) && $author_article->name == $author_cite->name) {
                    $count_citation_self_article = 1;
                }
            }
        }
        $output["citation_self_article"] = $count_citation_self_article;

        if ($output["citation_self_journal"] != 1 && $output["citation_vci"] != 1 && $output["citation_scopus_isi"] != 1) {
            $output["citation_other"] = 1;
        } else {
            $output["citation_other"] = 0;
        }

        return $output;
    }

    public static function getCitationForAuthor($article, $author)
    {
        $e_citations = self::getCitation($article);
        $self_citation_author = 0;
        foreach ($e_citations as $e_citation) {
            foreach ($e_citation["authors"] as $author_cite) {
                if ($author_cite->name == $author->name) {
                    $self_citation_author++;
                }
            }
        }
        return $self_citation_author;
    }

    /**
     * Trả về số citation theo từng năm
     * @param $citations
     * @return static
     */
    public static function getCitationsPerYear($citations)
    {

        $citations_collect = collect($citations);

        $years_non_key = $citations_collect->map(function ($citation) {
            $citation_colect = collect($citation);
            return ['year' => $citation_colect["year"]];
        })->sortBy('year', 0, true)->groupBy('year')->map(function ($year) {
            return count($year);
        });

        $citations_per_year = collect($years_non_key->keys())->map(function ($key) use ($years_non_key) {
            return ['year' => $key, 'cite_count' => $years_non_key[$key]];
        });


        return $citations_per_year->toArray();
    }
}