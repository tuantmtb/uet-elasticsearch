<?php

namespace App\DataTables;

use App\Models\Journal;
use App\Models\Subject;
use Yajra\Datatables\Services\DataTable;

class JournalIndexDataTable extends DataTable
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
            ->editColumn('name', function($journal) {
                /**
                 * @var Journal $journal
                 */
                return \Html::link(route('statistics.journal', $journal->id), $journal->name)->toHtml();
            })
            ->editColumn('subjects_count', function ($journal) {
                /**
                 * @var Journal $journal
                 */
                return $journal->subjects
                    ->pluck('name')
                    ->push(\Html::link(route('manage.journal.subjects', $journal->id), 'Quản lý', ['class' => 'btn btn-sm btn-default'])->toHtml())
                    ->implode('<br/>');
            })
            ->editColumn('website', function($journal) {
                /**
                 * @var Journal $journal
                 */
                return $journal->website ? \Html::link($journal->website)->toHtml() : '';
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
        $query = Journal::query()
            ->with('subjects')
            ->withCount('subjects');

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
            'name' => ['title' => 'Tên', 'class' => 'col-md-2'],
            'name_en' => ['title' => 'Tên tiếng Anh', 'class' => 'col-md-2'],
            'issn' => ['title' => 'ISSN', 'class' => 'col-md-2'],
            'website' => ['title' => 'Url', 'class' => 'col-md-2'],
            'proprietor' => ['title' => 'Cơ quan chủ quản', 'class' => 'col-md-2'],
            'subjects_count' => ['title' => 'Lĩnh vực', 'searchable' => false, 'class' => 'col-md-2'],
        ];
    }

    protected function getBuilderParameters()
    {
        return [
            'language' => [
                'searchPlaceholder' => 'Tìm kiếm'
            ]
        ];
    }
}
