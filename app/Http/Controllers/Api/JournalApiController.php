<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\elasticsearch\ESJournalSearch;
use App\Models\Journal;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class JournalApiController extends BaseController
{
    /**
     * /journals/search?q={query}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $name = $request->get('q', '');

        if ($name != null && $name != "") {
            $journals = Journal::where('name', 'LIKE', "%$name%")->newQuery()->orWhere('name_en', 'LIKE', "%$name%")->get();
        } else {
            // null query or init
            $journals = Journal::take(30)->get();
        }

        if ($journals->count() > 0) {
            return response()->json(['status' => 'success', 'data' => $journals]);
        } else {
            return response()->json(['status' => 'empty', 'data' => []]);
        }
    }

    public function searchBySubject(Request $request)
    {
        $journals = Journal::query()->where('id', '<>', 1);

        if ($request->has('text')) {
            $elastic = new ElasticsearchApiController();
            $esJournalSearch = new ESJournalSearch();
            $name = $request->get('text');
            $output = $esJournalSearch->serviceSearchJournalFromElasticSearch(compact('name'));
            $output = new Collection($output);
            $journal_ids = $output->get('journals_id', []);
            $journal_ids_imp = implode(',', $journal_ids);
            $journals->whereIn('journals.id', $journal_ids)->orderByRaw("field(journals.id, $journal_ids_imp)");
        }

        if ($request->has('subject_ids')) {
            $ids = new Collection($request->get('subject_ids'));
            $subject_ids = $ids->map(function ($id) {
                return Subject::find($id);
            })->filter(function ($subject) {
                return $subject != null;
            })->map(function ($subject) {
                /**
                 * @var Subject $subject
                 */
                return $subject->descendants_with_this_ids();
            })->reduce(function ($total, $collection) {
                if ($total == null) {
                    return $collection;
                } else {
                    /**
                     * @var Collection $total
                     */
                    return $total->merge($collection);
                }
            });

            $journal_ids = Journal::query()
                ->join('journals_subjects', 'journals_subjects.journal_id', '=', 'journals.id')
                ->whereIn('journals_subjects.subject_id', $subject_ids)
                ->groupBy('journals.id')
                ->pluck('journals.id');
            $journals = $journals->whereIn('id', $journal_ids);
        }

        /**
         * @var Builder $journals
         */
        $journals = $journals->get();

        return view('vci_views.search.journals_result', compact('journals'));
    }

    public function updateSubjects($id, Request $request)
    {
        /**
         * @var Journal $journal
         */
        $journal = Journal::findOrFail($id);
        $subject_ids = $request->get('subject_ids', []);
        $subjects = Subject::findMany($subject_ids)->filter(function ($subject) {
            /**
             * @var Subject $subject
             */
            return !$subject->isRoot();
        });
        $journal->syncSubjects($subjects);
        return response('done', 200);
    }
}
