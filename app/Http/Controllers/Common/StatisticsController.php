<?php

namespace App\Http\Controllers\Common;

use App\Models\Journal;
use App\Models\Organize;
use App\Models\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class StatisticsController extends Controller
{
    /**
     * @param Collection $elasticData
     * @return Collection
     */
    public static function extractStatistics($elasticData)
    {
        $statistics = collect();

        $years = collect($elasticData->get('years', []))
            ->sortBy('year');
        if ($years->isNotEmpty()) {
            $statistics = $statistics->merge(compact('years'));
        }

        return $statistics;
    }
}
