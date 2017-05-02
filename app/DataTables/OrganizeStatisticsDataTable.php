<?php

namespace App\DataTables;

use App\Http\Controllers\Api\elasticsearch\ESStatisticOrganize;
use App\Http\Controllers\Common\ErrorHandler;
use App\Models\Journal;
use App\Models\Subject;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Support\Collection;
use Yajra\Datatables\Services\DataTable;

/**
 * Class OrganizeStatisticsDataTable
 * Thống kê một cơ quan
 * @package App\DataTables
 */
class OrganizeStatisticsDataTable extends DataTable
{
    private $data;

    public function setOrganize($organize)
    {
        $data = $this->data;

        $data['organize'] = $organize;

        try {
            $elasticApi = new ESStatisticOrganize();
            $data['elasticData'] = $elasticData = collect($elasticApi->statisticPerOrganize($organize->id));

            $data['years'] = $years = collect($elasticData['years'])->filter(function ($year) {
                return $year['year'] >= 2012;
            })->sortBy('year');

            $data['journals'] = collect($elasticData['journals'])
                ->map(function ($journal) use ($years) {
                    $journal_sql = Journal::find($journal['journal_id']);
                    if ($journal_sql != null) {
                        $journal_years = collect($journal['years'])
                            ->keyBy('year');
                        $years
                            ->filter(function ($year) use ($journal_years) {
                                return !$journal_years->has($year['year']);
                            })->map(function ($year) {
                                return ['year' => $year['year'], 'count' => 0, 'citation' => 0];
                            })
                            ->merge($journal_years)
                            ->filter(function ($year) {
                                return $year['year'] >= 2012;
                            })
                            ->each(function ($year) use ($journal_sql) {
                                $journal_sql->setAttribute('year_' . $year['year'] . '_count', $year['count']);
                            });
                    }
                    return $journal_sql;
                })
                ->filter(function ($journal) {
                    return $journal != null;
                });

            $statistics = [];
            $statistics['years'] = collect($elasticData['years'])->sortBy('year');
            $statistics['subjects'] = collect($elasticData['subjects'])
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
                });
            $statistics['total'] = collect($elasticData)->only(\VciConstants::ORGANIZE_STATISTICS_TOTAL_ONLY);
            $citation_year_unknown = $elasticData->get('citation_year_unknown', null);
            if ($citation_year_unknown) {
                $statistics['citation_year_unknown'] = $citation_year_unknown;
            }
            $data['statistics'] = $statistics;
        } catch (NoNodesAvailableException $e) {
            ErrorHandler::noConnectElastic($e);
        }

        $this->data = $data;
        return $this;
    }

    public function render($view, $data = [], $mergeData = [])
    {
        $data = array_merge($data, $this->data);
        \VciHelper::debug($this->request(), $data);
        return parent::render($view, $data, $mergeData);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->editColumn('name', function ($journal) {
                /**
                 * @var Journal $journal
                 */
                return \Html::link(route('statistics.journal', $journal->id), $journal->name)->toHtml();
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return Collection
     */
    public function query()
    {
        $data = collect($this->data);
        return $data->get('journals', collect());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'name' => ['title' => 'Tạp chí'],
        ];

        $data = $this->data;
        if (isset($data['years'])) {
            foreach ($data['years'] as $year) {
                $_year = $year['year'];
                $columns['year_' . $_year . '_count'] = ['title' => "$_year", 'searchable' => false];
            }
        }

        return $columns;
    }

    protected function getBuilderParameters()
    {
        return [
            'language' => [
                'searchPlaceholder' => 'Nhập tên tạp chí'
            ]
        ];
    }
}
