<?php

namespace App\DataTables;

use App\Models\Article;
use Html;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Services\DataTable;

class ArticleNonReviewedDatatable extends DataTable
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
            ->addIndexColumn()
            ->editColumn('title', function ($article) {
                /**
                 * @var Article $article
                 */
                return Html::link(route('article.show', $article->id), $article->title)->toHtml();
            })
            ->editColumn('journal.name', function ($article) {
                /**
                 * @var Article $article
                 */
                return $article->journal ? Html::link(route('journal.articles', $article->journal_id), $article->journal->name)->toHtml() : "";
            })
            ->addColumn('action', function ($article) {
                return view('partials.article.dt_action', compact('article'))->render();
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // todo: remove query < year 2006
        $articles = Article::query()
            ->whereNull('is_reviewed')
            ->where('year','>=','2006')
            ->with('journal')
            ->select('articles.*');

        return $this->applyScopes($articles);
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
            ->parameters($this->getBuilderParameters())
            ->ajax('')
            ->addAction(['title' => 'Chi tiết']);
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [3, 'desc'],
            'language' => [
                'searchPlaceholder' => 'Tiêu đề/Tạp chí'
            ]
        ];
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
            'updated_at' => ['title' => 'Cập nhật', 'searchable' => false],
        ];
    }
}
