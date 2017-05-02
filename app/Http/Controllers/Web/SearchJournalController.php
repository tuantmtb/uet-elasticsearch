<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\elasticsearch\ESJournalSearch;
use App\Http\Controllers\Common\SearchTrait;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Validator;

class SearchJournalController extends Controller
{
    use SearchTrait;

    /**
     * @param Request $request
     * @return Validator
     */
    protected function makeValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'text' => 'string',
            'page_size' => 'integer',
            'page' => 'integer|min:1',
        ]);
    }

    /**
     * @param Request $request
     * @return Collection
     */
    protected function mapRequestToElastic(Request $request)
    {
        return collect([
            'name' => $request->get('text'),
            'page' => $request->get('page', 1) - 1,
            'perPage' => $request->get('page_size', 10),
        ]);
    }

    /**
     * @param Collection $elasticContext
     * @return Collection
     */
    protected function getElasticData($elasticContext)
    {
        $elasticApi = new ESJournalSearch();
        return collect($elasticApi->serviceSearchJournalFromElasticSearch($elasticContext));
    }

    /**
     * @param Collection $elasticData
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function getResults($elasticData)
    {
        $journals = Journal::query()
            ->whereIn('journals.id', $elasticData->get('journals_id', []));
        if (count($elasticData->get('journals_id', [])) > 0) {
            $order = collect($elasticData->get('journals_id'))
                ->implode(', ');
            $journals = $journals->orderByRaw("field(journals.id, $order)");
        }
        return $journals->get();
    }

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected function getFilterData($elasticData)
    {
        return null;
    }

    /**
     * @param Collection $elasticData
     * @param Collection $filter
     * @return array
     */
    protected function getResultsTotal($elasticData, $filter)
    {
        $total = [];
        $total['count'] = '<i class="fa fa-book"></i> ' . $elasticData['count'] . ' tạp chí';
        return $total;
    }

    /**
     * @return array
     */
    protected function getSortFields()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function viewName()
    {
        return 'pages.search.journal';
    }

    /**
     * @return string
     */
    protected function resultsKey()
    {
        return 'journals';
    }

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected function getStatistics($elasticData)
    {
        return null;
    }
}
