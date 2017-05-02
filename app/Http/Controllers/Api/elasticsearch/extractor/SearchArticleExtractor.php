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
                $output["id"][] = $hits["_source"]["movie_id"];
                $article = [];
                $article = $hits["_source"];

                if (isset($hits["highlight"])) {


                        $article["highlight"] = $hits["highlight"];

                }

                $output["articles"][] = $article;
            }
        }


        if (isset($response["aggregations"]["years"])) {
            $output["years"] = $commonExtractor->extractStatisticYears($response["aggregations"]["years"]);
        }


        return $output;
    }


}