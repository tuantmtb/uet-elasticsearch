<?php

namespace App\Http\Controllers\Common;

use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use \Illuminate\Validation\Validator;
use Log;
use VciHelper;

trait SearchTrait
{
    /**
     * @param Request $request
     * @return Validator
     */
    protected abstract function makeValidator(Request $request);

    /**
     * @param Validator $validator
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function onValidateFailed(Validator $validator)
    {
        $messages = collect($validator->messages()->messages())->map(function ($message) {
            return [
                'level' => 'error',
                'title' => '',
                'message' => collect($message)->implode(' '),
            ];
        })->toArray();
        \Session::flash('toastr', $messages);
        return redirect()->route('search')->withErrors($validator)->withInput();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    protected abstract function mapRequestToElastic(Request $request);

    /**
     * @param Collection $elasticContext
     * @param Request $request
     * @return Collection
     */
    protected abstract function getElasticData($elasticContext, Request $request);

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected abstract function getResults($elasticData);

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected abstract function getFilterData($elasticData);

    /**
     * @param Collection $elasticData
     * @param Collection $filter
     * @return array
     */
    protected abstract function getResultsTotal($elasticData, $filter);

    /**
     * @return array
     */
    protected abstract function getSortFields();

    /**
     * @return string
     */
    protected abstract function viewName();

    /**
     * @return string
     */
    protected abstract function resultsKey();

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected abstract function getStatistics($elasticData);

    /**
     * Call before debug
     * @param array $context
     * @return array
     */
    protected function customContext($context)
    {
        return $context;
    }

    public function search(Request $request)
    {
        $validator = $this->makeValidator($request);
        if ($validator->fails()) {
            return $this->onValidateFailed($validator);
        }

        $elasticContext = $this->mapRequestToElastic($request);

        try {
            $elasticData = $this->getElasticData($elasticContext, $request);
        } catch (NoNodesAvailableException $exception) {
            Log::debug($exception);
            \Session::flash('toastr', [
                [
                    'level' => 'error',
                    'title' => 'Lỗi kết nối',
                    'message' => 'Không thể kết nối tới elastic search',
                ]
            ]);
            return redirect()->route('search');
        }

        $results = $this->getResults($elasticData);
        $filter = $this->getFilterData($elasticData);
        $statistics = $this->getStatistics($elasticData);
        $pagingMeta = ElasticPagingMetaController::getPagingMeta($request, $elasticData);
        $total = $this->getResultsTotal($elasticData, $filter);
        $sortFields = $this->getSortFields();
        $context = compact('request', 'elasticContext', 'elasticData', 'statistics', 'filter', 'pagingMeta', 'total', 'sortFields');
        $context[$this->resultsKey()] = $results;
        $context = $this->customContext($context);
        VciHelper::debug($request, $context);
        return view($this->viewName(), $context);
    }
}