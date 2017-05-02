<?php

namespace App\Http\Controllers\Web;

use App\Models\Journal;
use Illuminate\Http\Request;

class SearchArticleFromJournalController extends SearchArticleController
{
    /**
     * @var Journal $journal
     */
    private $journal;

    protected function makeValidator(Request $request)
    {
        $validator = parent::makeValidator($request);
        $validator->setRules(array_merge($validator->getRules(), [
            'volume' => 'string',
            'number' => 'string',
            'year' => 'string',
            'must_scopus' => 'boolean',
        ]));
        return $validator;
    }

    protected function getResultsTotal($elasticData, $filter)
    {
        return array_except(parent::getResultsTotal($elasticData, $filter), 'journals');
    }

    protected function getStatistics($elasticData)
    {
        $statistics = parent::getStatistics($elasticData);
        $total = collect([
            'name' => "Tên tạp chí: " . $this->journal->name,
            'issn' => "ISSN: " . $this->journal->issn,
        ]);

        $journals = $elasticData->get('journals', []);
        if (count($journals) > 0) {
            $journal = collect($journals[0]);
            if ($journal->has('hindex')) {
                $total = $total->put('hindex', "H-index: " . $journal->get('hindex'));
            }
        }

        $total = $total->merge($statistics->get('total'));

        $statistics = $statistics->merge(compact('total'))->except('journals');
        return $statistics;
    }

    protected function customContext($context)
    {
        /**
         * @var Request $request
         */
        $request = $context['request'];
        $year = $request->get('year', null);
        $volume = $request->get('volume', null);
        $number = $request->get('number', null);
        $context['page_title'] = \VciHelper::journalWithInfo($this->journal, $number, $volume, $year, true);
        if ($request->get('must_scopus') == true) {
            $context['page_title'] = 'Các bài có trích dẫn từ ISI, Scopus tạp chí ' . \VciHelper::journalWithInfo($this->journal, $number, $volume, $year, true);
        }

        return $context;
    }

    protected function viewName()
    {
        return 'pages.search.journal_articles';
    }

    public function searchFromJournal($id, Request $request)
    {
        /**
         * @var Journal $journal
         */
        $this->journal = $journal = Journal::findOrFail($id);
        $request->merge([
            'field' => 'journal',
            'text' => $journal->name,
        ]);
        $request->replace($request->except('advance'));
        return parent::search($request);
    }

    public function mapRequestToElastic($request)
    {

        $context = parent::mapRequestToElastic($request);

        if ($request->has('year')) {
            $years = $context->get('years', []);
            $years[] = $request->get('year');
            $context = $context->merge(compact('years'));
        }

        if ($request->has('number')) {
            $numbers = [$request->get('number')];
            $context = $context->merge(compact('numbers'));
        }

        if ($request->has('volume')) {
            $volumes = [$request->get('volume')];
            $context = $context->merge(compact('volumes'));
        }

        if ($request->has('must_scopus')) {
            $context = $context->merge($request->only('must_scopus'));
        }

        return $context;
    }
}
