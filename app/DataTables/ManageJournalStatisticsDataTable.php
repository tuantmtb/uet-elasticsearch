<?php

namespace App\DataTables;

use App\Models\Journal;
use Illuminate\Database\Query\Builder;
use Yajra\Datatables\Services\DataTable;

class ManageJournalStatisticsDataTable extends DataTable
{
    /**
     * year > 2005
     */
    const YEAR_AFTER = 2005;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('name', function($journal) {
                /**
                 * @var Journal $journal
                 */
                return \Html::link(route('journal.articles', $journal->id), $journal->name)->toHtml();
            })
            ->editColumn('articles_count', function($journal) {
                /**
                 * @var Journal|mixed $journal
                 */
                return \Html::link(route('journal.articles', $journal->id), $journal->articles_count)->toHtml();
            })
            ->editColumn('reviewed_articles_count', function($journal) {
                /**
                 * @var Journal|mixed $journal
                 */
                return \Html::link(route('journal.articles.reviewed', $journal->id), $journal->reviewed_articles_count)->toHtml();
            })
            ->editColumn('non_reviewed_articles_count', function($journal) {
                /**
                 * @var Journal|mixed $journal
                 */
                return \Html::link(route('journal.articles.non_reviewed', $journal->id), $journal->non_reviewed_articles_count)->toHtml();
            })
            ->editColumn("non_reviewed_articles_after_" . self::YEAR_AFTER . "_count", function($journal) {
                /**
                 * @var Journal $journal
                 */
                return \Html::link(
                    route('journal.articles.non_reviewed',
                        ['id' => $journal->id, 'year_after' => self::YEAR_AFTER]
                    ),
                    $journal->getAttribute("non_reviewed_articles_after_" . self::YEAR_AFTER . "_count")
                );
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
        $query = Journal::query()
            ->withCount([
                'articles',
                'articles AS reviewed_articles' => function ($query) {
                    /**
                     * @var Builder $query
                     */
                    $query->where('is_reviewed', '=', true);
                },
                'articles AS non_reviewed_articles' => function ($query) {
                    /**
                     * @var Builder $query
                     */
                    $query->whereNull('is_reviewed');
                },
                "articles AS non_reviewed_articles_after_" . self::YEAR_AFTER => function($query) {
                    /**
                     * @var Builder $query
                     */
                    $query->whereNull('is_reviewed')->where('year', '>', self::YEAR_AFTER);
                }
            ]);

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
            'name' => ['title' => 'Tên'],
            'articles_count' => ['title' => 'Số bài báo', 'searchable' => false],
            'reviewed_articles_count' => ['title' => 'Bài đã duyệt', 'searchable' => false],
            'non_reviewed_articles_count' => ['title' => 'Bài chưa duyệt', 'searchable' => false],
            'non_reviewed_articles_after_' . self::YEAR_AFTER . '_count' => ['title' => 'Bài chưa duyệt sau ' . self::YEAR_AFTER, 'searchable' => false],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [1, 'asc'],
            'language' => [
                'searchPlaceholder' => 'Nhập tên tạp chí'
            ]
        ];
    }
}
