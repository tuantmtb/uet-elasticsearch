<?php

namespace App\DataTables;

use App\Models\Article;
use App\Models\Journal;
use Yajra\Datatables\Services\DataTable;

class JournalReviewedArticleDatatable extends DataTable
{
    /**
     * @var Journal $journal
     */
    private $journal;

    /**
     * @param Journal $journal
     * @return $this
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
        return $this;
    }

    public function render($view, $data = [], $mergeData = [])
    {
        $data['journal'] = $this->journal;
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
            ->eloquent($this->query())
            ->editColumn('title', function ($article) {
                /**
                 * @var Article $article
                 */
                return \Html::link(route('article.show', $article->id), $article->title)->toHtml();
            })
            ->editColumn('editor.name', function ($article) {
                /**
                 * @var Article $article
                 */
                return $article->editor ? $article->editor->name : '';
            })
            ->addColumn('action', function ($article) {
                return view('partials.article.dt_action', compact('article'))->render();
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->journal->articles()
            ->getQuery()
            ->where('is_reviewed', '=',true)
            ->with('editor')
            ->select('articles.*');

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
            ->columns($this->getColumns())
            ->ajax('')
            ->addAction(['title' => 'Hành động'])
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
            'editor.name' => ['title' => 'Người sửa'],
            'updated_at' => ['title' => 'Cập nhật', 'searchable' => false],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [2, 'desc'],
            'language' => [
                'searchPlaceholder' => 'Tiêu đề/Người sửa'
            ]
        ];
    }
}
