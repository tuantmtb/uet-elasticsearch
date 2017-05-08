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
}