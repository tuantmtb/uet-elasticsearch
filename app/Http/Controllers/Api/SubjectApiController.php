<?php

namespace App\Http\Controllers\Api;

use App\Models\Journal;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectApiController extends Controller
{
    private function toJstreeData($children, Request $request)
    {
        $result = [];
        foreach ($children as $child) {
            $json = (object)$this->show($child->id, $request);
            if (!empty($child->children->toArray())) $json->children = true;
            $result[] = $json;
        }
        return $result;
    }

    /**
     * @param Subject $subject
     * @return int
     */
    private function getJournalsCount($subject) {
        if ($subject->isRoot()) {
            return $subject->children()->getQuery()
                ->join('journals_subjects', 'journals_subjects.subject_id', '=', 'subjects.id')
                ->groupBy('journals_subjects.journal_id')
                ->get()->count();
        } else {
            return $subject->journals->count();
        }
    }

    public function show($id, Request $request)
    {
        $count_journals = $request->get('count_journals', false);

        /**
         * @var Subject $subject
         */
        $subject = Subject::query()->findOrFail($id);
        $result = (object)[
            'id' => $subject->id,
            'parent' => isset($subject->parent) ? $subject->parent->id : '#',
            'text' => $subject->name . ($count_journals ? ' (' . $this->getJournalsCount($subject) . ')' : ''),
//            'a_attr' => (object)['href' => route('api.subjects.show', ['id' => $subject->id])],
//            'state' => [
//                'opened' => true,
//            ]
        ];
        return $result;
    }

    public function roots(Request $request)
    {
        $subjects = Subject::query()->whereNull('parent_id')->get();
        return $this->toJstreeData($subjects, $request);
    }

    public function children($id, Request $request)
    {
        /**
         * @var Subject $subject
         */
        $subject = Subject::query()->findOrFail($id);
        return $this->toJstreeData($subject->children, $request);
    }
}
