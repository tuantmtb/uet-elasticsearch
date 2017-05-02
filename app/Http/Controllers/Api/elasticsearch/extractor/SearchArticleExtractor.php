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

class SearchArticleExtractor
{
    /**
     * @param $response
     * @param $context
     * @return array
     */
    public function extractSearchArticle($response, $context)
    {
        $commonExtractor = new CommonExtractor();
        $output = [];
        $output["count"] = $response["hits"]["total"];

        if (isset($response["hits"]["hits"])) {
            foreach ($response["hits"]["hits"] as $hits) {
                $output["article_ids"][] = $hits["_source"]["id"];
                $article = [];
                $article = $hits["_source"];

                if (isset($hits["highlight"])) {

                    if (isset($hits["highlight"]["authors.name"])) {

                        $author_output = [];
                        $authors_matched_normalize = [];
                        foreach ($hits["highlight"]["authors.name"] as $author_matched) {
                            $author_matched_normalize = str_replace("<b>", "", $author_matched);
                            $author_matched_normalize = str_replace("</b>", "", $author_matched_normalize);
                            $authors_matched_normalize[] = $author_matched_normalize;
                        }


                        foreach ($hits["_source"]["authors"] as $author) {
                            if (in_array($author["name"], $authors_matched_normalize)) {
                                $key = array_keys($authors_matched_normalize, $author["name"]);

                                $author_output[] = $hits["highlight"]["authors.name"][$key[0]];
                            } else {
                                $author_output[] = $author["name"];
                            }
                        }

                        $article["highlight"]["authors.name"] = $author_output;

                    } else {
                        $article["highlight"] = $hits["highlight"];
                    }


                }

                $output["articles"][] = $article;
            }
        }

        if (isset($response["aggregations"]["sum_citation"])) {
            $output["citation"] = $response["aggregations"]["sum_citation"]["value"];
        }
        if (isset($response["aggregations"]["journals"])) {
            $output["journals"] = $commonExtractor->extractStatisticjournalFull($response["aggregations"]["journals"]);
        }
        if (isset($response["aggregations"]["organizes"])) {
            $output["organizes"] = $commonExtractor->extractStatisticOrganize($response["aggregations"]["organizes"]);
        }

        if (isset($response["aggregations"]["organize_count"])) {
            $output["organizes_count"] = $commonExtractor->extractStatisticAggsCount($response["aggregations"]["organize_count"]);
        }

//        if (isset($response["aggregations"]["years"])) {
//            $output["years"] = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);
//        }
//
//        if (isset($response["aggregations"]["citations"])) {
//            $output["citations"] = $commonExtractor->extractStatisticCitationPerYear($response["aggregations"]["citations"]);
//        }

        if (isset($response["aggregations"]["years"]) && isset($response["aggregations"]["citations"])) {
            $output["citation_year_unknown"] = null;
            $results = [];
            $statistic_years = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);
            $statistic_citations = $commonExtractor->extractStatisticCitationPerYear($response["aggregations"]["citations"]);

            if (isset($context["field"]) && $context["field"] == 'article_id') {

                for ($year = 1990; $year <= 2017; $year++) {
                    $result = [];
                    $result["count"] = 0;
                    $result["citation"] = 0;
                    $result["year"] = $year;
                    foreach ($statistic_years as $statistic_year) {
                        if ($statistic_year["year"] == $year) {
                            $result["count"] = $statistic_year["count"];
                        }
                    }

                    foreach ($statistic_citations as $statistic_citation) {
                        if (isset($statistic_citation["year"]) && $statistic_citation["year"] == $year) {
                            $result["citation"] = $statistic_citation["citation"];

                        }
                    }
                    if ($result["count"] != 0 || $result["citation"] != 0) {
                        $results[] = $result;
                    }
                }
                $output["years"] = $results;

            } else {
                // combine

                foreach ($statistic_years as $statistic_year) {
                    if ($statistic_year["year"] != 0) {
                        $result = [];
                        $result["count"] = $statistic_year["count"];
                        $result["year"] = $statistic_year["year"];
                        $result["citation"] = 0;
                        foreach ($statistic_citations as $statistic_citation) {
                            if (isset($statistic_citation["year"]) && $statistic_citation["year"] == $statistic_year["year"]) {
                                $result["citation"] = $statistic_citation["citation"];
                            }
                        }
                        $results[] = $result;
                    }
                }
                $output["years"] = $results;
            }

            foreach ($statistic_citations as $statistic_citation) {
                if ($statistic_citation["year"] == 0) {
                    $output["citation_year_unknown"] = $statistic_citation["citation"];
                }
            }
        }


        if (isset($response["aggregations"]["subjects"])) {
            $output["subjects"] = $commonExtractor->extractStatisticSubjects($response["aggregations"]["subjects"]);
        }

        if (isset($response["aggregations"]["authors"])) {
            $output["author"] = $commonExtractor->extractStatisticAuthor($response["aggregations"]["authors"]);
        }

        if (isset($response["aggregations"]["author_count"])) {
            $output["authors_count"] = $commonExtractor->extractStatisticAggsCount($response["aggregations"]["author_count"]);
        }

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


}