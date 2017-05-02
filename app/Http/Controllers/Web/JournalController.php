<?php

namespace App\Http\Controllers\Web;

use App\DataTables\JournalNonReviewedArticleDatatable;
use App\DataTables\JournalReviewedArticleDatatable;
use App\DataTables\ManageJournalStatisticsDataTable;
use App\DataTables\JournalIndexDataTable;
use App\Http\Requests\CreateJournalRequest;
use App\Models\Journal;
use App\Http\Controllers\Controller;

class JournalController extends Controller
{
    /**
     * JournalController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:edit');
    }

    public function create() {
        return view('pages.journal.create');
    }

    public function store(CreateJournalRequest $request)
    {
        $journal = Journal::create($request->only('name', 'name_en', 'website', 'address', 'description'));

        \Session::flash('toastr', [
            [
                'level' => 'success',
                'title' => 'Tạo tạp chí thành công',
                'message' => "Đã tạo $journal->name",
            ]
        ]);
        return redirect()->route('journal.index');
    }

    public function index(JournalIndexDataTable $dataTable)
    {
        return $dataTable->render('pages.journal.index');
    }

    public function statistics(ManageJournalStatisticsDataTable $dataTable) {

        return $dataTable->render('pages.journal.statistics');
    }

    public function reviewedArticles($id, JournalReviewedArticleDatatable $datatable) {
        /**
         * @var Journal $journal
         */
        $journal = Journal::findOrFail($id);
        return $datatable->setJournal($journal)->render('pages.journal.reviewed_articles');
    }

    public function nonReviewedArticles($id, JournalNonReviewedArticleDatatable $datatable) {
        /**
         * @var Journal $journal
         */
        $journal = Journal::findOrFail($id);
        return $datatable->setJournal($journal)->render('pages.journal.non_reviewed_articles');
    }
}
