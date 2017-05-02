<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\elasticsearch\ESJournalSearch;
use App\Http\Controllers\Api\elasticsearch\ESOrganizeSearch;
use App\Http\Controllers\Common\SearchTrait;
use App\Models\Journal;
use App\Models\Organize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Validator;

class SearchOrganizeController extends Controller
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
        $elasticApi = new ESOrganizeSearch();
        return collect($elasticApi->serviceSearchOrganizeFromElasticSearch($elasticContext));
    }

    /**
     * @param Collection $elasticData
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function getResults($elasticData)
    {
        $organizes = Organize::query()
            ->whereIn('organizes.id', $elasticData->get('organizes_id', []));
        if (count($elasticData->get('organizes_id', [])) > 0) {
            $order = collect($elasticData->get('organizes_id'))
                ->implode(', ');
            $organizes = $organizes->orderByRaw("field(organizes.id, $order)");
        }
        return $organizes->get();
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
        $total['count'] = '<i class="fa fa-sitemap"></i> ' . $elasticData['count'] . ' cơ quan';
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
        return 'pages.search.organize';
    }

    /**
     * @return string
     */
    protected function resultsKey()
    {
        return 'organizes';
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
