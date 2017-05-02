<?php

namespace App\DataTables;

use App\Models\Article;
use App\User;
use Yajra\Datatables\Services\DataTable;

class ArticleReviewedDatatable extends DataTable
{
    /**
     *
     * Display ajax response.
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
                return \Html::link(route('article.show', $article->id), $article->title)->toHtml();
            })
            ->editColumn('journal.name', function ($article) {
                /**
                 * @var Article $article
                 */
                return $article->journal ? \Html::link(route('journal.articles', $article->journal_id), $article->journal->name)->toHtml() : "";
            })
            ->addColumn('action', function ($article) {
                return view('partials.article.dt_action', compact('article'))->render();
            })
            ->editColumn('editor.name', function ($article) {
                /**
                 * @var Article $article
                 */
                return $article->editor ? $article->editor->name : '';
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
        $query = Article::query()
            ->where('is_reviewed', '=', true)
            ->with('editor', 'journal')
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
            ->addIndex(['title' => 'STT'])
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
            'journal.name' => ['title' => 'Tạp chí'],
            'editor.name' => ['title' => 'Người sửa'],
            'updated_at' => ['title' => 'Cập nhật', 'searchable' => false],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [4, 'desc'],
            'language' => [
                'searchPlaceholder' => 'Tiêu đề/Tạp chí/Người sửa'
            ],
        ];
    }
}
