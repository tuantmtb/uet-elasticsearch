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

        $citation_year_unknown = $elasticData->get('citation_year_unknown', null);
        if ($citation_year_unknown) {
            $statistics = $statistics->merge(compact('citation_year_unknown'));
        }

        $journal_years = collect($elasticData->get('journals', []))
            ->map(function ($journal) {
                return $journal['years'];
            })
            ->collapse()
            ->unique('year')
            ->sortBy('year')
            ->keyBy('year');

        $journals = collect($elasticData->get('journals', []))
            ->map(function ($journal) use ($journal_years) {
                $journal_sql = Journal::find($journal['id']);
                if ($journal_sql) {
                    $journal_sql->count = $journal['count'];
                    $journal_sql->citation = $journal['citation'];
                    $journal_sql->years = collect($journal['years'])
                        ->merge(
                            $journal_years
                                ->diffKeys(collect($journal['years'])->keyBy('year'))
                                ->map(function ($year) {
                                    return [
                                        'year' => $year['year'],
                                        'count' => 0,
                                        'citation' => 0,
                                    ];
                                })
                        )
                        ->sortBy('year')
                        ->values();
                }
                return $journal_sql;
            })
            ->filter(function ($journal) {
                return $journal != null;
            })
            ->sortBy('count', 0, true)
            ->take(5);
        if ($journals->isNotEmpty()) {
            $statistics = $statistics->merge(compact('journals', 'journal_years'));
        }

        $organizes = collect($elasticData->get('organizes', []))
            ->map(function ($organize) {
                $organize_sql = Organize::find($organize['id']);
                if ($organize_sql) {
                    $organize_sql->count = $organize['count'];
                    $organize_sql->citation = $organize['citation'];
                }
                return $organize_sql;
            })
            ->filter(function ($organize) {
                /**
                 * @var Organize $organize
                 */
                return $organize != null && $organize->isRoot();
            })
            ->sortBy('count', 0, true)
            ->take(10);
        if ($organizes->isNotEmpty()) {
            $statistics = $statistics->merge(compact('organizes'));
        }

        $authors = collect($elasticData->get('author', []))
            ->sortBy('count', 0, 'desc')
            ->take(10);
        if ($authors->isNotEmpty()) {
            $statistics = $statistics->merge(compact('authors'));
        }

        $subjects = collect($elasticData->get('subjects', []))
            ->map(function ($subject) {
                $subject_sql = Subject::find($subject['id']);
                if ($subject_sql) {
                    $subject_sql->count = $subject['count'];
                    $subject_sql->citation = $subject['citation'];
                }
                return $subject_sql;
            })
            ->filter(function ($subject) {
                /**
                 * @var Subject $subject
                 */
                return $subject != null && $subject->isRoot();
            })
            ->sortBy('count', 0, true)
            ->take(10);
        if ($subjects->isNotEmpty()) {
            $statistics = $statistics->merge(compact('subjects'));
        }

        $total = collect(\VciConstants::STATISTICS_TOTAL)
            ->keys()
            ->filter(function ($key) use ($elasticData) {
                return $elasticData->has($key);
            })
            ->mapWithKeys(function ($key) use ($elasticData) {
                return [$key => \VciConstants::STATISTICS_TOTAL[$key] . ": " . $elasticData->get($key)];
            });
        $statistics = $statistics->merge(compact('total'));

        return $statistics;
    }
}
