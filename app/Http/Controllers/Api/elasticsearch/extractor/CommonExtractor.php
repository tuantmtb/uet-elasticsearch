<?php

namespace App\Http\Controllers\Api\elasticsearch\extractor;

use App\Models\Article;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Organize;
use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CommonExtractor
{

    public function extractStatisticCitationPerYear($citations)
    {
        $output = [];
        foreach ($citations["per_year"]["buckets"] as $perYear) {
            $citationInfo = [];
            $citationInfo["year"] = $perYear["key"];
            $citationInfo["count"] = $perYear["doc_count"];
            $citationInfo["citation"] = $perYear["total_cites"]["value"];
            $output[] = $citationInfo;
        }
        return $output;
    }

    /**
     * @param $years : json from elasticsearch: years["buckets"][ARRAY]
     * @return array: years
     */
    public function extractStatisticYears($years)
    {
        $output = [];
        foreach ($years["buckets"] as $perYear) {
            $yearInfo = [];
            $yearInfo["year"] = $perYear["key"];
            $yearInfo["count"] = $perYear["doc_count"];
            $output[] = $yearInfo;
        }

        return $output;
    }

    /**
     * @param $organizes : json from elasticsearch: organizes["buckets"][ARRAY]
     * @return array: organizes
     */
    public function extractStatisticOrganize($organizes)
    {
        $output = [];
        foreach ($organizes["buckets"] as $perOrganize) {
            $organizeInfo = [];
            $organizeInfo["id"] = $perOrganize["key"];
            $org = Organize::find($perOrganize["key"]);
            if ($org != null) {
                $organizeInfo["name"] = $org->name;
                $organizeInfo["name_en"] = $org->name_en;
            }
            $organizeInfo["count"] = $perOrganize["doc_count"];
            $organizeInfo["citation"] = $perOrganize["sum_citation"]["value"];
            $output[] = $organizeInfo;
        }
        return $output;
    }

    /**
     * @param $subjects : json from elasticsearch: subjects["buckets"][ARRAY]
     * @return array: years
     */
    public function extractStatisticSubjects($subjects)
    {
        $output = [];
        foreach ($subjects["buckets"] as $perSubject) {
            $subjectInfo = [];
            $subjectInfo["id"] = $perSubject["key"];
            if (Subject::find($perSubject["key"]) != null) {
                $subjectInfo["name"] = Subject::find($perSubject["key"])->name;
            }

            $subjectInfo["count"] = $perSubject["doc_count"];
            $subjectInfo["citation"] = $perSubject["sum_citation"]["value"];
            $output[] = $subjectInfo;
        }
        return $output;
    }

    public function extractStatisticAuthor($authors)
    {
        $output = [];
        foreach ($authors["buckets"] as $perAuthor) {
            $authorInfo = [];
            $authorInfo["fullname"] = $perAuthor["key"];
            $authorInfo["count"] = $perAuthor["doc_count"];
            $authorInfo["citation"] = $perAuthor["sum_citation"]["value"];
            $output[] = $authorInfo;
        }

        return $output;
    }

    public function extractStatisticCitations($citations)
    {
        return $citations["value"];
    }

    public function extractCitationSelfAuthor($author_analytic)
    {
        if (isset($author_analytic) && isset($author_analytic["authors_match"])) {
            return $author_analytic["authors_match"]["sum_citation"]["value"];
        }

        // bug if return line below
        return 0;
    }

    public function extractAuthorCount($author_count)
    {
        if (isset($author_count) && isset($author_count["value"])) {
//            dd($author_count["value"]);
            return $author_count["value"];
        }
    }

    public function extractValueCitation($response)
    {
        if (isset($response) && $response != null) {
            return round($response["value"], 2);
        }
        return 0;
    }


    public function filterDuplicate($array)
    {
        $output = [];
        foreach ($array as $item) {
            if (!in_array($item, $output)) {
                $output[] = $item;
            }
        }
        return $output;
    }

    // sumarize extract
    public function extractJournalStatisticToResult($response)
    {
        $commonExtractor = new CommonExtractor();
        $output = [];

        $output["id"] = $response["aggregations"]["journal"]["buckets"][0]["key"]; // id_jour
        $output["name"] = Journal::find($output["id"])->name;
        $output["name_en"] = Journal::find($output["id"])->name_en;

        $output["count"] = $response["aggregations"]["journal"]["buckets"][0]["doc_count"];
        $output["citation"] = $response["aggregations"]["journal"]["buckets"][0]["sum_citation"]["value"];

        $output["organizes"] = [];

        // thống kê các tạp chí được các tác giả tại cơ quan $output["id"] gửi bài
        foreach ($response["aggregations"]["journal"]["buckets"][0]["organizes"]["buckets"] as $e_organize) {
            $organize = [];
            $organize["organize_id"] = $e_organize["key"];
            $organize["organize_name"] = Organize::find($e_organize["key"])->name;
            $organize["organize_name_en"] = Organize::find($e_organize["key"])->name_en;

            $organize["count"] = $e_organize["doc_count"];
            $organize["citation"] = $e_organize["sum_citation"]["value"];

            // statistic year
            $organize["years"] = $commonExtractor->extractStatisticYears($e_organize["years"]);

            $output["organizes"][] = $organize;
        }


        // thống kê số bài báo, citation theo năm của các tác giả tại cơ quan $output["id"]
//        $output["years"] = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);

        // todo update query thống kê số citation
        if (isset($response["aggregations"]["citation_self"])) {
            $output["citation_self"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_self"]);
        }
        if (isset($response["aggregations"]["citation_vci"])) {
            $output["citation_vci"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_vci"]);
        }
        if (isset($response["aggregations"]["citation_scopus_isi"])) {
            $output["citation_scopus_isi"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_scopus_isi"]);
        }
        if (isset($response["aggregations"]["citation_other"])) {
            $output["citation_other"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_other"]);
        }

        if (isset($response["aggregations"]["hindex"])) {
            $output["hindex"] = $commonExtractor->extractHIndex($response["aggregations"]["hindex"]);
        }

        if (isset($response["aggregations"]["articles_citation_count"])) {
            $output["articles_citation_count"] = $commonExtractor->extractArticlesCitationCount($response["aggregations"]["articles_citation_count"]);
        }


        if (isset($response["aggregations"]["citations"])) {
            $output["citation_year_unknown"] = null;
            $statistic_citations = $commonExtractor->extractStatisticCitationPerYear($response["aggregations"]["citations"]);

            foreach ($statistic_citations as $statistic_citation) {
                if ($statistic_citation["year"] == 0) {
                    $output["citation_year_unknown"] = $statistic_citation["citation"];
                }
            }
        }


        // todo: update extract organizes_collaborate
        return $output;

    }

    /**
     * @param $journals : json from elasticsearch: journals["buckets"][ARRAY]
     * @return array: journals
     */
    public function extractStatisticjournalFull($journals)
    {
        $commonExtractor = new CommonExtractor();
        $output = [];
        foreach ($journals["buckets"] as $perJournal) {
            $journalInfo = [];
            $journalInfo["id"] = $perJournal["key"];
            $org = journal::find($perJournal["key"]);
            if ($org != null) {
                $journalInfo["name"] = $org->name;
                $journalInfo["name_en"] = $org->name_en;
            }
            if (isset($perJournal["years"])) {
                $journalInfo["years"] = $this->extractStatisticYears($perJournal["years"]);
            }
            $journalInfo["count"] = $perJournal["doc_count"];
            $journalInfo["citation"] = $perJournal["sum_citation"]["value"];
            $journalInfo["hindex"] = $commonExtractor->extractHIndex($perJournal["hindex"]);
            $journalInfo["citation_scopus_isi"] = $commonExtractor->extractStatisticCitations($perJournal["citation_scopus_isi"]);
            $journalInfo["articles_citation_count"] = $commonExtractor->extractArticlesCitationCount($perJournal["articles_citation_count"]);
            $journalInfo["avg_citation"] = $commonExtractor->extractValueCitation($perJournal["avg_citation"]);
            $journalInfo["max_citation"] = $commonExtractor->extractValueCitation($perJournal["max_citation"]);
            $journalInfo["citing_count"] = $commonExtractor->extractValueCitingCountValue($perJournal["citing_count"]);
            $output[] = $journalInfo;
        }
        return $output;
    }


    public function extractOrganizeStatisticToResult($response)
    {
        $commonExtractor = new CommonExtractor();
        $output = [];

        $output["id"] = $response["aggregations"]["organizes"]["buckets"][0]["key"]; // id_org
        $output["count"] = $response["aggregations"]["organizes"]["buckets"][0]["doc_count"];
        $output["citation"] = $response["aggregations"]["organizes"]["buckets"][0]["sum_citation"]["value"];
        $output["journals"] = [];

        // thống kê các tạp chí được các tác giả tại cơ quan $output["id"] gửi bài
        foreach ($response["aggregations"]["organizes"]["buckets"][0]["journals"]["buckets"] as $e_journal) {
            $journal = [];
            $journal["journal_id"] = $e_journal["key"];
            $journal["journal_name"] = $e_journal["journal"]["hits"]["hits"][0]["_source"]["journal_data"]["name"];
            $journal["journal_name_en"] = $e_journal["journal"]["hits"]["hits"][0]["_source"]["journal_data"]["name_en"];
            $journal["count"] = $e_journal["doc_count"];
            $journal["citation"] = $e_journal["sum_citation"]["value"];

            // statistic year
            $journal["years"] = $commonExtractor->extractStatisticYears($e_journal["years"]);

            $output["journals"][] = $journal;
        }

        // thống kê số bài báo, citation theo năm của các tác giả tại cơ quan $output["id"]
//        $output["years"] = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);

        // thống kê theo chủ đề
        $output["subjects"] = $commonExtractor->extractStatisticSubjects($response["aggregations"]["subjects"]);
        // thống kê số citation
        if (isset($response["aggregations"]["citation_self"])) {
            $output["citation_self"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_self"]);
        }
        if (isset($response["aggregations"]["citation_vci"])) {
            $output["citation_vci"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_vci"]);
        }
        if (isset($response["aggregations"]["citation_scopus_isi"])) {
            $output["citation_scopus_isi"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_scopus_isi"]);
        }
        if (isset($response["aggregations"]["citation_other"])) {
            $output["citation_other"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_other"]);
        }

        if (isset($response["aggregations"]["citations"])) {
            $output["citation_year_unknown"] = null;
            $statistic_citations = $commonExtractor->extractStatisticCitationPerYear($response["aggregations"]["citations"]);

            foreach ($statistic_citations as $statistic_citation) {
                if ($statistic_citation["year"] == 0) {
                    $output["citation_year_unknown"] = $statistic_citation["citation"];
                }
            }
        }

        return $output;

    }


    // extract phân tích kết quả tìm kiếm
    public function extractStatisticElasticSearchToResult($response)
    {
        $commonExtractor = new CommonExtractor();
        $output = [];

        $output["count"] = $response["hits"]["total"];
        $output["citation"] = $response["aggregations"]["sum_citation"]["value"];

        $output["organizes"] = [];


        // thống kê số bài báo, citation theo năm của các tác giả tại cơ quan $output["id"]
        $output["journals"] = $commonExtractor->extractStatisticjournalFull($response["aggregations"]["journals"]);
        $output["organizes"] = $commonExtractor->extractStatisticOrganize($response["aggregations"]["organizes"]);
        $output["years"] = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);
        $output["subjects"] = $commonExtractor->extractStatisticSubjects($response["aggregations"]["subjects"]);
        $output["author"] = $commonExtractor->extractStatisticAuthor($response["aggregations"]["authors"]);
        if (isset($response["aggregations"]["citation_self"])) {
            $output["citation_self"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_self"]);
        }
        if (isset($response["aggregations"]["citation_vci"])) {
            $output["citation_vci"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_vci"]);
        }
        if (isset($response["aggregations"]["citation_scopus_isi"])) {
            $output["citation_scopus_isi"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_scopus_isi"]);
        }
        if (isset($response["aggregations"]["citation_other"])) {
            $output["citation_other"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["citation_other"]);
        }

        return $output;

    }

    public function extractStatisticAggsCount($author_count)
    {
        return $author_count["value"];
    }

    public function extractHIndex($response)
    {
        $hindex = 0;
        $sum = 0; // số bài thỏa mãn
        foreach ($response["buckets"] as $citation) {
            $sum += $citation["doc_count"];
            if ($sum >= $citation["key"]) {
                $hindex = $citation["key"];
                return $hindex;
            }
        }

        return $hindex;
    }

    /**
     * Số bài báo được trích dẫn
     * @param $articles_citation_count
     */
    private function extractArticlesCitationCount($response)
    {
        return $response["buckets"][0]["doc_count"];
    }

    private function extractValueCitingCountValue($response)
    {
        return $response["citing_count_value"]["value"];
    }


}