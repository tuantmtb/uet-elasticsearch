<?php

namespace App\DataTables;

use App\Http\Controllers\Api\elasticsearch\ESStatisticJournal;
use App\Http\Controllers\Common\ErrorHandler;
use App\Models\Organize;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Support\Collection;
use Yajra\Datatables\Services\DataTable;

/**
 * Class JournalStatisticsDataTable
 * Thống kê một tạp chí
 * @package App\DataTables
 */
class JournalStatisticsDataTable extends DataTable
{
    private $data = [];

    public function setJournal($journal)
    {
        $data = $this->data;

        $data['journal'] = $journal;

        try {
            $elasticApi = new ESStatisticJournal();
            $data['elasticData'] = $elasticData = collect($elasticApi->statisticPerJournal($journal->id));

            $data['years'] = $years = collect($elasticData->get('years', []))
                ->filter(function ($year) {
                    return $year['year'] >= 2012;
                })
                ->sortBy('year');

            $data['organizes'] = collect($elasticData->get('organizes', []))
                ->map(function ($organize) use ($years) {
                    $organize_sql = Organize::find($organize['organize_id']);
                    if ($organize_sql != null) {
                        $organize_years = collect($organize['years'])->keyBy('year');
                        $years
                            ->filter(function ($year) use ($organize_years) {
                                return !$organize_years->has($year['year']);
                            })
                            ->map(function ($year) {
                                return ['year' => $year['year'], 'count' => 0, 'citation' => 0];
                            })
                            ->merge($organize_years)
                            ->filter(function ($year) {
                                return $year['year'] >= 2012;
                            })
                            ->each(function ($year) use ($organize_sql) {
                                $organize_sql->setAttribute('year_' . $year['year'] . '_count', $year['count']);
                            });
                    }
                    return $organize_sql;
                })
                ->filter(function ($organize) {
                    return $organize != null;
                });

            $statistics = [
                'years' => collect($elasticData->get('years'))->sortBy('year'),
                'total' => collect($elasticData)->only(\VciConstants::JOURNAL_STATISTICS_TOTAL_ONLY),
            ];
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
            ->collection($this->query())
            ->editColumn('name', function ($organize) {
                return \VciHelper::organizeWithAncestors($organize, 'statistics.organize');
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
        return $data->get('organizes', collect());
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
            'name' => ['title' => 'Cơ quan'],
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
                'searchPlaceholder' => 'Nhập tên cơ quan'
            ]
        ];
    }
}
