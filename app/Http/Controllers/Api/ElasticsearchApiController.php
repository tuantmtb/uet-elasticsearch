<?php

namespace App\Http\Controllers\Api;

use App\Facade\VciHelper;
use App\Facade\VciQueryES;
use App\Http\Controllers\Api\elasticsearch\ESJournalSearch;
use App\Http\Controllers\Api\elasticsearch\extractor\CommonExtractor;
use App\Http\Controllers\Api\elasticsearch\extractor\SearchArticleExtractor;
use App\Models\Author;
use App\Models\Article;
use App\Models\Journal;
use App\Models\JournalInternational;
use App\Models\Organize;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kalnoy\Nestedset\NodeTrait;
use Elasticsearch\ClientBuilder;
use Log;


class ElasticsearchApiController extends Controller
{

    private function buildAndExecuteQuery($context, $field_search_query)
    {

        // build query filter + search
        $bool = [
            'must' => $field_search_query
        ];
        $query = ['bool' => $bool];


        // aggs
        $aggs = [
            'years' => [
                'terms' => [
                    'field' => 'production_year',
                    'order' => [
                        '_term' => 'asc'
                    ]
                ]
            ]
        ];

        // sort
        $mapping_sort_with_es = [
            'relevance' => '_score',
            'title' => 'title',
            'year' => 'production_year',
            'imdb' => 'imdb_index'
        ];

        $field_sort = isset($context["sort-col"]) ? $mapping_sort_with_es[$context["sort-col"]] : $mapping_sort_with_es['relevance'];

        $sort = [
            $field_sort => [
                'order' => isset($context["sort-dir"]) ? $context["sort-dir"] : 'desc'
            ]
        ];

        // highlight
        $highlight = ["fields" => [
            "title" => new \stdClass(),
            "info" => new \stdClass(),
        ],
            "pre_tags" => "<b>",
            "post_tags" => "</b>"
        ];


        $pageSize = isset($context["perPage"]) ? $context["perPage"] : 10;
        $from = isset($context["page"]) ? $context["page"] * $pageSize : 0;

        /**
         * Query
         */
        $params = [
            'index' => 'imdb',
            'type' => 'film',
            'body' => [
                'query' => $query,
                'sort' => $sort,
                'highlight' => $highlight,
                'aggs' => $aggs,
                'from' => $from,
                'size' => $pageSize

            ]
        ];

//        return $params;
//        log::debug(response()->json($params));
        $response = VciQueryES::getClientES()->search($params);
//        return $response;
        $searchArticleExtractor = new SearchArticleExtractor();
        $output = $searchArticleExtractor->extractSearchArticle($response, $context);

        return $output;
    }

    /**
     * Search bài báo
     * Input:
     * $context["field","text", "sort-col","sort-dir","perPage","page"]
     * filter: $context[years, organizes,journals]
     *
     * Nếu input search by author: có thêm: $output["citation_self_author"]
     * Nếu input search by journal: có thêm: $output["citation_self_journal"]
     * @param $context
     * @return array
     */
    public function serviceSearchArticleFromElasticSearch($context)
    {
        if (isset($context["field"]) && isset($context["text"]) && $context["text"] != "" && $context["field"] != "") {
            $field_search_query = isset($context["match_phrase"]) ? QueryHelper::makeQueryByField($context['field'], $context['text'], $context["match_phrase"]) : QueryHelper::makeQueryByField($context['field'], $context['text']);
        }

        $output = $this->buildAndExecuteQuery($context, $field_search_query);


        return $output;
    }

    private function testModeSearch()
    {
        $context["field"] = "title";
        $context["text"] = "harry potter";
        $context["match_phrase"] = false;

        $context["sort-col"] = "relevance"; //sort
        $context["sort-dir"] = "desc"; // sort
        $context["page"] = 0; // paginate offset
        $context["perPage"] = 10; // paginate
        $output = $this->serviceSearchArticleFromElasticSearch($context);
        return $output;

    }


    // test mode
    // GET /api/elasticsearch/test
    public function test(Request $request)
    {
        return $this->testModeSearch();
    }

}

class QueryHelper
{

    static private function filterNot(&$context)
    {
        $nots = [];

        foreach ($context as $i => $c) {
            if (strtolower($c['connector']) === 'not') {
                $nots[] = $c;

                unset($context[$i]);
            }
        }

        $context = array_values($context);

        return $nots;
    }

    static private function getRootNode($tree)
    {
        foreach ($tree as $node) {
            if ($node['parent'] === -1)
                return $node;
        }

        return null; // should not happen
    }

    static private function getChildrenNodes($tree, $parentNode)
    {
        $children = [];

        foreach ($tree as $node) {
            if ($node['parent'] === $parentNode['id']) {
                $children[] = $node;
            }
        }

        return $children;
    }

    static private function makeValueNode($id, $field, $value, $parent_id, $match_phrase = false)
    {
        return [
            'id' => $id,
            'type' => 'value',
            'field' => $field,
            'match_phrase' => $match_phrase,
            'value' => $value,
            'parent' => $parent_id
        ];
    }

    static private function makeCondNode($id, $value, $parent_id)
    {
        return [
            'id' => $id,
            'type' => 'cond',
            'value' => $value,
            'parent' => $parent_id
        ];
    }

    static private function buildTree($context)
    {
        $tree = [];

        if (count($context) === 0)
            return tree;
        else if (count($context) === 1) {
            $tree[] = self::makeCondNode(0, 'and', -1);
            $tree[] = self::makeValueNode(1, $context[0]['field'], $context[0]['text'], 0, $context[0]['match_phrase']);

            return $tree;
        }


        $current_cond = null;
        if (strtolower($context[1]['connector']) === 'or') {
            $tree[] = self::makeCondNode(0, 'or', -1);
            $current_cond = 'or';
        } else {
            $tree[] = self::makeCondNode(0, 'and', -1);
            $current_cond = 'and';
        }

        $root_index = 0;

        foreach ($context as $i => $c) {
            $cond = strtolower($c['connector']);
            if ($cond !== null && $cond !== '' && $cond !== $current_cond) {
                $tree[] = self::makeCondNode(count($tree), $cond, -1);
                $new_root_index = count($tree) - 1;
                $tree[$root_index]['parent'] = $new_root_index;
                $root_index = $new_root_index;
                $current_cond = $cond;
            }

            $tree[] = isset($c[3]) ? self::makeValueNode(count($tree), $c['field'], $c['text'], $root_index, $c['match_phrase']) : self::makeValueNode(count($tree), $c['field'], $c['text'], $root_index);
        }

        return $tree;

    }

    static private function getMatchingBoolQueryType($condition)
    {
        if ($condition === 'and') {
            return 'must';
        } else if ($condition === 'or') {
            return 'should';
        } else {
            return 'must_not';
        }
    }

    static public function makeQueryByField($field, $value, $match_phrase = false)
    {
        $query = [];

        if ($match_phrase == null || !$match_phrase) {
            $query = ['multi_match' => [
                'query' => $value,
                'fields' => $field,
                'minimum_should_match' => '50%']];
        } else {
            $query = ['multi_match' => [
                'query' => $value,
                'fields' => $field,
                'minimum_should_match' => '100%']];
        }


        return $query;
    }

    static private function helpDigIn($current_node, $tree)
    {
        $query = null;
        $children = self::getChildrenNodes($tree, $current_node);

        if ($current_node['type'] === 'cond') {
            $subquery = [];

            foreach ($children as $c) {
                $subquery[] = self::helpDigIn($c, $tree);
            }

            $query = ['bool' => [self::getMatchingBoolQueryType($current_node['value']) => $subquery]];
        } else {
            $query = self::makeQueryByField($current_node['field'], $current_node['value'], $current_node['match_phrase']);
        }

        return $query;
    }

    static private function addNotQuery($query, $not_conditions)
    {
        $query['bool']['must_not'] = [];


        foreach ($not_conditions as $n) {
            $query['bool']['must_not'][] = self::makeQueryByField($n['field'], $n['text'], $n['match_phrase']);
        }

        return $query;
    }

    static private function compileQuery($tree, $not_conditions)
    {
        // dd($tree);
        // dd(self::getRootNode($tree));
        // dd(self::helpDigIn(self::getRootNode($tree), $tree));

        return self::addNotQuery(self::helpDigIn(self::getRootNode($tree), $tree), $not_conditions);
    }

    // context should be [
    //      ['connector' => 'and/or/not', 'field' => 'field1', 'text' => 'value1'],
    //      ['connector' => 'and/or/not', 'field' => 'field2', 'text' => 'value2'],
    //      ...
    //]
    static function buildQuery($context)
    {
        $not_conditions = self::filterNot($context);
        // dd($not_conditions);

        $tree = self::buildTree($context);
        // dd($tree);

        return self::compileQuery($tree, $not_conditions);
    }
}
