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


class ESJournalSearch extends Controller
{


    /**
     * Search journal
     * @param $context
     * @return array
     */
    public
    function serviceSearchJournalFromElasticSearch($context)
    {
        $pageSize = isset($context["perPage"]) ? $context["perPage"] : 10;
        $from = isset($context["page"]) ? $context["page"] * $pageSize : 0;
        $params = [
            'index' => 'test',
            'type' => 'journal',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $context["name"],
                        'fields' => ["name", "name_en"],
                        'operator' => 'or',
                        'minimum_should_match' => '80%'
                    ]
                ],
                'from' => $from,
                'size' => $pageSize
            ]
        ];

        $response = VciQueryES::getClientES()->search($params);

        $output = [];
        $output["count"] = $response["hits"]["total"];
        $output["journals"] = [];
        $output["journals_id"] = [];

        $journals_elastic = $response["hits"]["hits"];

        foreach ($journals_elastic as $journal_elastic) {
            $output["journals"][] = $journal_elastic["_source"];
            $output["journals_id"][] = $journal_elastic["_id"];
        }

        return $output;
    }
}