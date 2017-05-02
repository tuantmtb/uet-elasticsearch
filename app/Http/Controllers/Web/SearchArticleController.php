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

    protected function advanceValidator(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'advance' => 'accepted',
            'terms' => 'array|required|min:1',
            'terms.0.connector' => [
                'nullable',
                Rule::in(array_keys(\VciConstants::SEARCH_CONNECTORS))
            ],
            'terms.*.connector' => [
                'required',
                Rule::in(array_keys(\VciConstants::SEARCH_CONNECTORS))
            ],
            'terms.*.field' => [
                'required',
                Rule::in(array_keys(\VciConstants::SEARCH_ARTICLE_FIELDS))
            ],
            'term.*.text' => 'string|required',
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

        return $validator;
    }

    protected function makeValidator(Request $request)
    {
        if ($request->has('advance')) {
            return $this->advanceValidator($request);
        } else {
            return $this->basicValidator($request);
        }
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

        $filter['authors'] = collect($elasticData->get('author', []))
            ->map(function ($author) {
                return [
                    'value' => $author['fullname'],
                    'text' => $author['fullname'],
                    'count' => $author['count'],
                ];
            })
            ->sortBy('count', 0, true)
            ->values();

        $filter['organizes'] = collect($elasticData->get('organizes', []))
            ->map(function ($organize) {
                $organize_sql = Organize::find($organize['id']);
                if ($organize_sql != null) {
                    return [
                        'value' => $organize_sql->name,
                        'text' => $organize_sql->name,
                        'count' => $organize['count'],
                    ];
                }
                return null;
            })
            ->filter(function ($organize) {
                return $organize != null;
            })
            ->sortBy('count', 0, true)
            ->values();

        $filter['journals'] = collect($elasticData->get('journals', []))
            ->map(function ($journal) {
                $journal_sql = Journal::find($journal['id']);
                if ($journal_sql != null) {
                    return [
                        'value' => $journal_sql->name,
                        'text' => $journal_sql->name,
                        'count' => $journal['count'],
                    ];
                }
                return null;
            })
            ->filter(function ($journal) {
                return $journal != null;
            })
            ->sortBy('count', 0, true)
            ->values();

        $filter['subjects'] = collect($elasticData->get('subjects', []))
            ->map(function ($subject) {
                $subject_sql = Subject::find($subject['id']);
                if ($subject_sql != null) {
                    return [
                        'value' => $subject_sql->name,
                        'text' => $subject_sql->name,
                        'count' => $subject['count']
                    ];
                }
                return null;
            })
            ->filter(function ($subject) {
                return $subject != null;
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

        if ($request->has('advance')) {
            $terms = collect($request->get('terms'))
                ->map(function($term) {
                    $text = $term['text'];
                    $term['match_phrase'] = starts_with($text, '"') && ends_with($text, '"');
                    return $term;
                })
                ->toArray();
            $default['search_advanced'] = $terms;
        } else {
            $default['field'] = $request->get('field');
            $default['text'] = $text = $request->get('text');
            $default['match_phrase'] = starts_with($text, '"') && ends_with($text, '"');
        }

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
                $article_sql = Article::find($article['id']);
                if ($article_sql) {
                    $article_sql->load('journal', 'authors');
                    if (isset($article['highlight'])) {
                        $highlight = collect($article['highlight']);

                        if ($highlight->has('title')) {
                            $article_sql->title = $highlight->get('title')[0];
                        }
                        if ($highlight->has('journal_data.name')) {
                            $article_sql->journal->name = $highlight->get('journal_data.name')[0];
                        }
                        if ($highlight->has('authors.name')) {
                            $authors_name = $highlight->get('authors.name');
                            foreach ($article_sql->authors as $index => $author) {
                                $author->name = $authors_name[$index];
                            }
                        }
                    }
                }
                return $article_sql;
            })
            ->filter(function ($article) {
                return $article != null;
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

        $total['count'] = '<i class="fa fa-newspaper-o"></i> ' . $elasticData->get('count') . ' bài báo';
        $total['citation'] = '<i class="fa fa-link"></i> ' . $elasticData->get('citation') . ' trích dẫn';
        $total['authors'] = '<i class="fa fa-user"></i> ' . $elasticData->get('authors_count') . ' tác giả';
        $total['journals'] = '<i class="fa fa-book"></i> ' . count($filter->get('journals', [])) . ' tạp chí';

        return $total;
    }

    protected function getElasticData($elasticContext, Request $request)
    {
        if ($request->has('advance')) {
            return $this->advanceElasticData($elasticContext, $request);
        } else {
            return $this->basicElasticData($elasticContext, $request);
        }
    }

    protected function basicElasticData($elasticContext, Request $request)
    {
        $elasticApi = new ElasticsearchApiController();
        return collect($elasticApi->serviceSearchArticleFromElasticSearch($elasticContext));
    }

    protected function advanceElasticData($elasticContext, Request $request)
    {
        $elasticApi = new ElasticsearchApiController();
        return collect($elasticApi->searchArticleAdvanced($elasticContext));
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
