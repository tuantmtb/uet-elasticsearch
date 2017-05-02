<?php

namespace App\DataTables;

use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Http\Controllers\Common\ErrorHandler;
use App\Models\Organize;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Contracts\View\Factory;
use \Yajra\Datatables\Datatables;

/**
 * Class OrganizesStatisticsDataTable
 * Thống kê tất cả các cơ quan
 * @package App\DataTables
 */
class OrganizesStatisticsDataTable extends DataTable
{
    private $data = [];

    /**
     * DataTable constructor.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @param \Illuminate\Contracts\View\Factory $viewFactory
     */
    public function __construct(Datatables $datatables, Factory $viewFactory)
    {
        parent::__construct($datatables, $viewFactory);
        $this->preload();
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
            ->addIndexColumn()
            ->editColumn('name', function ($organize) {
                return \VciHelper::organizeWithAncestors($organize, 'statistics.organize');
            })
            ->make(true);
    }

    private function preload() {
        try {
            $elasticApi = new ElasticsearchApiController();
            $elasticData = $elasticApi->serviceStatisticFromElasticSearch();

            $this->data['organizes'] = collect($elasticData['organizes'])
                ->map(function ($organize) {
                    $organize_sql = Organize::find($organize['id']);
                    if ($organize_sql != null) {
                        $organize_sql->count = $organize['count'];
                        $organize_sql->citation = $organize['citation'];
                    }
                    return $organize_sql;
                })->filter(function ($organize) {
                    return $organize != null;
                });
        } catch (NoNodesAvailableException $e) {
            ErrorHandler::noConnectElastic($e);
            $this->data['organizes'] = collect();
        }
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return $this->data['organizes'];
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addIndex(['title' => 'STT'])
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
        return [
            'name' => ['title' => 'Tên cơ quan'],
            'count' => ['title' => 'Tổng số bài', 'searchable' => false],
            'citation' => ['title' => 'Tổng số trích dẫn', 'searchable' => false],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [2, 'desc'],
            'language' => [
                'searchPlaceholder' => 'Nhập tên cơ quan'
            ]
        ];
    }
}
