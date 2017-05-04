<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Http\Controllers\Common\SearchTrait;
use App\Models\Article;
use App\Models\Journal;
use App\Models\Organize;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Common\StatisticsController;

class SearchArticleController extends Controller
{
    use SearchTrait;

    protected function basicValidator(Request $request)
    {
        return \Validator::make($request->all(), [
            'field' => [
                'required',
                Rule::in(array_keys(\VciConstants::SEARCH_ARTICLE_FIELDS))
            ],
            'text' => 'string|required',
            'sort_by' => [
                Rule::in(array_keys($this->getSortFields()))
            ],
            'sort_dir' => [
                Rule::in(array_keys(\VciConstants::SORT_DIRS))
            ],
            'page_size' => [
                Rule::in(array_keys(\VciConstants::PAGE_SIZES))
            ],
            'page' => 'integer|min:1',
            'filter_years' => 'array',
            'filter_authors' => 'array',
            'filter_organizes' => 'array',
            'filter_journals' => 'array',
            'filter_subjects' => 'array',
        ]);
    }

    protected function makeValidator(Request $request)
    {
        return $this->basicValidator($request);
    }

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected function getFilterData($elasticData)
    {
        $filter = [];

        $filter['years'] = collect($elasticData->get('years', []))
            ->map(function ($year) {
                return [
                    'value' => $year['year'],
                    'text' => $year['year'],
                    'count' => $year['count'],
                ];
            })
            ->sortBy('count', 0, true)
            ->values();

        return collect($filter)->filter(function ($collection) {
            /**
             * @var Collection $collection
             */
            return $collection->isNotEmpty();
        });
    }

    /**
     * @param Request $request
     * @return Collection
     */
    protected function mapRequestToElastic($request)
    {
        $default = [
            'page' => $request->get('page', 1) - 1,
            'perPage' => $request->get('page_size', 10),
            'sort-col' => $request->get('sort_by', 'relevance'),
            'sort-dir' => $request->get('sort_dir', 'desc'),
        ];

        $default['field'] = $request->get('field');
        $default['text'] = $text = $request->get('text');
        $default['match_phrase'] =

        $context = collect(\VciConstants::SEARCH_ARTICLE_FILTERS)
            ->filter(function ($key) use ($request) {
                return $request->has("filter_$key");
            })
            ->map(function ($key) use ($request) {
                return [$key => $request->get("filter_$key")];
            })
            ->collapse()
            ->merge($default);

        return $context;
    }

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected function getResults($elasticData)
    {
        $articles = collect($elasticData->get('articles', []))
            ->map(function ($article) {
                /**
                 * @var array $article
                 */
                if (isset($article['highlight'])) {
                    $highlight = collect($article['highlight']);

                    if ($highlight->has('title')) {
                        $article['title'] = $highlight->get('title')[0];
                    }
                }
                return (object)$article;
            });
        return $articles;
    }

    /**
     * @param Collection $elasticData
     * @param Collection $filter
     * @return array
     */
    protected function getResultsTotal($elasticData, $filter)
    {
        $total = [];

        $total['count'] = '<i class="fa fa-newspaper-o"></i> ' . $elasticData->get('count') . ' bộ phim';

        return $total;
    }

    protected function getElasticData($elasticContext, Request $request)
    {
        return $this->basicElasticData($elasticContext, $request);
    }

    protected function basicElasticData($elasticContext, Request $request)
    {
        $elasticApi = new ElasticsearchApiController();
        return collect($elasticApi->serviceSearchArticleFromElasticSearch($elasticContext));
    }

    protected function viewName()
    {
        return 'pages.search.article';
    }

    protected function resultsKey()
    {
        return 'articles';
    }

    protected function getSortFields()
    {
        return \VciConstants::SEARCH_ARTICLE_SORTS;
    }

    /**
     * @param Collection $elasticData
     * @return Collection
     */
    protected function getStatistics($elasticData)
    {
        return StatisticsController::extractStatistics($elasticData);
    }
}
