<?php

namespace App\DataTables;

use App\Models\Article;
use Yajra\Datatables\Services\DataTable;

class ReviewCitationDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', function($article) {
                return view('vci_views.review_citation.column_action', compact('article'))->render();
            })
            ->editColumn('journal.name', function($article) {
                return view('vci_views.review_citation.column_journal_name', compact('article'))->render();
            })
            ->editColumn('updated_at', function ($article){
                /**
                 * @var Article $article
                 */
                return $article->updated_at->format('H:i:s d/m/Y');
            })
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Article::query()->whereNotNull('citation_raw')->where('citation_raw', '<>', '');

        return $this->applyScopes($query);
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
                    ->addAction(['title' => 'Chi tiết'])
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
            'title' => ['title' => 'Tiêu đề'],
            'journal.name' => ['title' => 'Tạp chí'],
            'updated_at' => ['title' => 'Thời điểm', 'searchable' => false],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [3, 'desc'],
        ];
    }
}
