<?php

namespace App\DataTables;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Yajra\Datatables\Services\DataTable;

class EditorStatisticDatatable extends DataTable
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
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Role::findByName('editor')->users()->getQuery()->withCount([
            'edited_articles',
            'edited_articles AS reviewed_articles' => function($query) {
                /**
                 * @var Builder $query
                 */
                $query->where('is_reviewed', '=', true);
            },
            'edited_articles AS no_reviewed_articles' => function($query) {
                /**
                 * @var Builder $query
                 */
                $query->where('is_reviewed', '=', false);
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
            'name' => ['title' => 'Tên', 'class' => 'col-md-3'],
            'edited_articles_count' => ['title' => 'Số bài đã sửa', 'class' => 'col-md-3', 'searchable' => false],
            'reviewed_articles_count' => ['title' => 'Số bài đã duyệt', 'class' => 'col-md-3', 'searchable' => false],
            'no_reviewed_articles_count' => ['title' => 'Số bài đã loại', 'class' => 'col-md-3', 'searchable' => false],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'order' => [1, 'desc'],
        ];
    }
}
