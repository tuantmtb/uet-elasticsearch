<?php

namespace App\Http\Controllers\Web;

use App\DataTables\EditorStatisticDatatable;
use App\DataTables\JournalNonReviewedArticleDatatable;
use App\DataTables\JournalReviewedArticleDatatable;
use App\Models\Article;
use App\Models\Journal;
use App\Models\Organize;
use App\Models\Subject;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Validator;

class ManageController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:edit');
    }

    public function dashboard() {
        $journals_count = Journal::count();
        $articles_count = Article::count();
        $members_count = User::count();
        $articles_accepted_count = Article::whereIsReviewed('1')->count();
        $articles_rejected_count = Article::whereIsReviewed('0')->count();
        $articles_non_reviewed_count = Article::whereNull('is_reviewed')->newQuery()->count();

        $context = compact(
            'journals_count',
            'articles_count',
            'members_count',
            'articles_accepted_count',
            'articles_rejected_count',
            'articles_non_reviewed_count'
        );
        return view('pages.manage.dashboard', $context);
    }

    public function journal_subjects($id)
    {
        /**
         * @var Journal $journal
         */
        $journal = Journal::findOrFail($id);
        $subject_ids = $journal->subjects->pluck('id')->toArray();
        $opened = [];
        foreach ($subject_ids as $id) {
            $subject = Subject::find($id);
            if ($subject) {
                $opened = array_merge($opened, $subject->ancestors()->getQuery()->pluck('id')->toArray());
            }
        }
        return view('vci_views.journal.subjects', compact('journal', 'subject_ids', 'opened'));
    }

    /**
     * GET: manage/organize/create
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_organize($id)
    {
        $organize = Organize::findOrFail($id);
        $organizes = [null => ''] + Organize::pluck('name', 'id')->toArray();
        return view('vci_views.manage.organize_edit', compact('organizes', 'organize'));
    }

    public function update_organize($id, Request $request)
    {
        $organizes = array_merge(Organize::pluck('id')->toArray(), [null]);
        $validator = Validator::make($request->all(), [
            'name' => [
                'string', 'required', 'max:255',
                Rule::unique('organizes')->ignore($id),
            ],
            'parent_id' => [
                'nullable', 'integer',
                Rule::in($organizes)
            ]
        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // find journal if exist
        /**
         * @var Organize $organize_find
         */
        $organize_find = Organize::findOrFail($id);
        $organize_find->update($request->only('name', 'name_en', 'address', 'description'));

        /**
         * @var mixed $parent
         */
        $parent = Organize::find($request->get('parent_id'));
        if ($parent) {
            $organize_find->appendToNode($parent)->save();
        } else {
            $organize_find->makeRoot()->save();
        }

        \Session::flash('toastr', [
            [
                'title' => 'Sửa cơ quan',
                'message' => 'Đã cập nhật ' . $organize_find->name,
            ]
        ]);

        return redirect(route('manage.organizes.tree') . '?selected=' . $organize_find->id);
    }

    /**
     * GET: manage/organize/create
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_organize(Request $request)
    {
        $organizes = [null => ''] + Organize::pluck('name', 'id')->toArray();
        $selected = null;
        if ($request->has('parent_id')) {
            /**
             * @var Organize $organize
             */
            $organize = Organize::find($request->get('parent_id'));
            if ($organize) {
                $selected = [$organize->id];
            }
        }
        return view('vci_views.manage.organize_create', compact('organizes', 'selected'));
    }

    public function store_organize(Request $request)
    {
        $organizes = array_merge(Organize::query()->pluck('id')->toArray(), [null]);
        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
            'parent_id' => [
                'nullable', 'integer',
                Rule::in($organizes)
            ]
        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // find journal if exist
//        $organize_find = Organize::whereName($request->name)->get();
//        if ($organize_find->count() != null && $organize_find->count() > 0) {
//
//            \Session::flash('toastr', [
//                [
//                    'title' => 'Không tạo thành công',
//                    'message' => 'Tên cơ quan đã tồn tại',
//                    'level' => 'error'
//                ]
//            ]);
//            return back();
//        }

        // create journal and return new route
        // smt
        /**
         * @var Organize $organize_new
         */
        $organize_new = Organize::create($request->only('name', 'name_en', 'address', 'description'));
        /**
         * @var mixed $parent
         */
        $parent = Organize::find($request->get('parent_id'));
        if ($parent) {
            $organize_new->appendToNode($parent)->save();
        }

        \Session::flash('toastr', [
            [
                'title' => 'Tạo cơ quan',
                'message' => 'Đã tạo ' . $organize_new->name,
            ]
        ]);

        return redirect(route('manage.organizes.tree') . '?selected=' . $organize_new->id);
    }

    public function editorStatistic(EditorStatisticDatatable $datatable)
    {
        return $datatable->render('pages.manage.editor_statistics');
    }
}
