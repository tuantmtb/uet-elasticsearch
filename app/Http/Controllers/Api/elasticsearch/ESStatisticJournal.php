<?php

namespace App\Http\Controllers\Api\elasticsearch;

use App\Facade\VciHelper;
use App\Facade\VciQueryES;
use App\Http\Controllers\Api\elasticsearch\extractor\CommonExtractor;
use App\Models\Author;
use App\Models\Article;
use App\Models\Journal;
use App\Models\JournalInternational;
use App\Models\Organize;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kalnoy\Nestedset\NodeTrait;
use Elasticsearch\ClientBuilder;
use Log;


class ESStatisticJournal extends Controller
{

    /**
     * Thống kê theo mỗi tạp chí
     * Số bài từng năm, các cơ quan nộp vào tạp chí đó
     * @param $journal_id
     * @return array
     */
    public function statisticPerJournal($journal_id)
    {
        $commonExtractor = new CommonExtractor();

        $params = [
            'index' => 'test',
            'type' => 'article',
            'body' => [
                'query' => [
                    'match_phrase' => [
                        'journal_id' => $journal_id
                    ]
                ],
                'aggs' => [
                    'journal' => [
                        'terms' => [
                            'field' => 'journal_id'
                        ],
                        'aggs' => [
                            'organizes' => [
                                'terms' => [
                                    'field' => 'organizes_data.id'
                                ],
                                'aggs' => [
                                    'sum_citation' => VciQueryES::param_sum_citation(),
                                    'years' => VciQueryES::param_years()
                                ]
                            ],
                            'sum_citation' => VciQueryES::param_sum_citation()
                        ]
                    ],
                    "years" => VciQueryES::param_years(),
                    "subjects" => VciQueryES::param_subjects(),
                    'citations' => VciQueryES::param_citations(),
                    'citation_self' => VciQueryES::param_citation_self(),
                    'citation_vci' => VciQueryES::param_citation_vci(),
                    "citation_scopus_isi" => VciQueryES::param_scopus_isi(),
                    "citation_other" => VciQueryES::param_citation_other(),
                    'organizes_collaborate' => VciQueryES::param_organizes_collaborate(),
                    'hindex' => VciQueryES::param_hindex(),
                    'articles_citation_count' => VciQueryES::param_articles_citation_count()
                ],
                'size' => 0

            ]
        ];

        $response = VciQueryES::getClientES()->search($params);
//        dd($response);
//        return $response;
        $output["citation_year_unknown"] = null;

        $results = [];
        $output = $commonExtractor->extractJournalStatisticToResult($response);


        $statistic_years = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);
        $statistic_citations = $commonExtractor->extractStatisticCitationPerYear($response["aggregations"]["citations"]);

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

        $output["info"] = "Statistic by journal_id = " . $output["id"];
        return $output;
    }
    

}


