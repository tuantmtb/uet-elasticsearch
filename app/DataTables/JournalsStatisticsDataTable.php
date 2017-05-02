<?php

namespace App\DataTables;

use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Http\Controllers\Common\ErrorHandler;
use App\Models\Journal;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Support\Collection;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Contracts\View\Factory;
use \Yajra\Datatables\Datatables;

/**
 * Class JournalsStatisticsDataTable
 * Thống kê tất cả các tạp chí
 * @package App\DataTables
 */
class JournalsStatisticsDataTable extends DataTable
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
        $data['request'] = $this->request();
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
        return $this->data['journals'];
    }

    private function preload()
    {
        $this->data['elasticContext'] = $elasticContext = $this->request()->only('start_year', 'end_year');

        try {
            $elasticApi = new ElasticsearchApiController();
            $this->data['elasticData'] = $elasticData = $elasticApi->serviceStatisticFromElasticSearch($elasticContext);

            $this->data['journals'] = collect($elasticData['journals'])->map(function($journal) {
                $journal = collect($journal);
                $journal_sql = Journal::find($journal->get('id', null));
                if ($journal_sql != null) {
                    $attrs = [
                        'count',
                        'citation',
                        'hindex',
                        'citation_scopus_isi',
                        'articles_citation_count',
                        'avg_citation',
                        'max_citation',
                        'citing_count',
                    ];
                    foreach ($attrs as $attr) {
                        $journal_sql->setAttribute($attr, $journal->get($attr, null));
                    }
                }
                return $journal_sql;
            })->filter(function($journal) {
                return $journal != null;
            });
        } catch (NoNodesAvailableException $e) {
            ErrorHandler::noConnectElastic($e);
            $this->data['journals'] = collect();
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addIndex(['title' => 'STT', 'class' => 'col-xs-1 text-center'])
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
            'name' => ['title' => 'Tên tạp chí', 'class' => 'col-xs-2'],
            'issn' => ['title' => 'ISSN', 'class' => 'col-xs-1 text-center'],
            'proprietor' => ['title' => 'Cơ quan<br> chủ quản', 'class' => 'col-xs-2'],
            'count' => ['title' => 'Tổng số bài', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'citation' => ['title' => 'Tổng số<br> trích dẫn', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'citation_scopus_isi' => ['title' => 'Trích dẫn từ các<br> tạp chí Scopus/ISI', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'hindex' => ['title' => 'H-index', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'articles_citation_count' => ['title' => 'Số bài được<br> trích dẫn', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'citing_count' => ['title' => 'Số bài<br> trích dẫn đến', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'avg_citation' => ['title' => 'Tỉ lệ<br> trích dẫn/bài', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
            'max_citation' => ['title' => 'Số trích dẫn<br> cao nhất', 'searchable' => false, 'class' => 'col-xs-1 text-center'],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'responsive' => false,
            'order' => [1, 'asc'],
            'language' => [
                'searchPlaceholder' => 'Tên tạp chí, ISSN, Cơ quan chủ quản'
            ],
            'pageLength' => 100,
        ];
    }
}
