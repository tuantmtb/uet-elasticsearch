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


class ESOrganizeSearch extends Controller
{

    /**
     * INPUT:  $context["name"]
     * @param $context
     * @return array
     */
    public
    function serviceSearchOrganizeFromElasticSearch($context)
    {
        $pageSize = isset($context["perPage"]) ? $context["perPage"] : 10;
        $from = isset($context["page"]) ? $context["page"] * $pageSize : 0;

        $params = [
            'index' => 'test',
            'type' => 'organize',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $context["name"],
                        'fields' => ["name", "name_en", "fullname"],
                        'operator' => 'or',
                        'minimum_should_match' => '80%'
                    ]

                ],
                'from' => $from,
                'size' => $pageSize
            ]
        ];

        $response = VciQueryES::getClientES()->search($params);
//        dd($response);
        $output = [];
        $output["count"] = $response["hits"]["total"];
        $output["organizes"] = [];
        $output["organizes_id"] = [];

        $organizes_elastic = $response["hits"]["hits"];

        foreach ($organizes_elastic as $organize_elastic) {
            $output["organizes"][] = $organize_elastic["_source"];
            $output["organizes_id"][] = $organize_elastic["_source"]["id"];
        }
//        dd($output);
        return $output;
    }
}