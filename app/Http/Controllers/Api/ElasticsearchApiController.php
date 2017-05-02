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

    private function getFilter($context)
    {
        $years_bool_query = null;
        if (isset($context["years"])) {
            $years = $context["years"];
            $years_query = [];
            foreach ($years as $year) {
                $year_query = ['term' => [
                    "year" => $year
                ]];
                $years_query[] = $year_query;
            }
            $years_bool_query = ['should' => $years_query];
        }


        $organizes_bool_query = null;
        if (isset($context["organizes"])) {
            $organizes = $context["organizes"];
            $organizes_query = [];
            foreach ($organizes as $organize) {
                $organize_query = ['match_phrase' => [
                    "organizes_data.name" => $organize
                ]];
                $organizes_query[] = $organize_query;
            }
            $organizes_bool_query = ['should' => $organizes_query];
        }

        $journals_bool_query = null;
        if (isset($context["journals"])) {
            $journals = $context["journals"];
            $journals_query = [];
            foreach ($journals as $journal) {
                $journal_query =
                    ['match_phrase' => [
                        "journal_data.name" => $journal
                    ]];
                $journals_query[] = $journal_query;
            }
            $journals_bool_query = ['should' => $journals_query];
        }

        $authors_bool_query = null;
        if (isset($context["authors"])) {
            $authors = $context["authors"];
            $authors_query = [];
            foreach ($authors as $author) {
                $author_query =
                    ['nested' => [
                        'path' => 'authors',
                        'query' => [
                            'match_phrase' => [
                                'authors.name' => $author
                            ]
                        ]
                    ]];
                $authors_query[] = $author_query;
            }
            $authors_bool_query = ['should' => $authors_query];
        }

        $subjects_bool_query = null;
        if (isset($context["subjects"])) {
            $subjects = $context["subjects"];
            $subjects_query = [];
            foreach ($subjects as $subject) {
                $subject_query =
                    ['match_phrase' => [
                        "subjects.name" => $subject
                    ]];
                $subjects_query[] = $subject_query;
            }
            $subjects_bool_query = ['should' => $subjects_query];
        }

        $numbers_bool_query = null;
        if (isset($context["numbers"]) && $context["numbers"] != "" && $context["numbers"][0] != null) {
            $numbers = $context["numbers"];
            $numbers_query = [];
            foreach ($numbers as $number) {
                $number_query =
                    ['query_string' => [
                        'default_field' => 'number',
                        "query" => $number
                    ]];
                $numbers_query[] = $number_query;
            }
            $numbers_bool_query = ['should' => $numbers_query];
        }

        $volumes_bool_query = null;
        if (isset($context["volumes"]) && $context["volumes"] != "" && $context["volumes"][0] != null) {
            $volumes = $context["volumes"];
            $volumes_query = [];
            foreach ($volumes as $volume) {
                $volume_query =
                    ['query_string' => [
                        'default_field' => 'volume',
                        "query" => $volume
                    ]];
                $volumes_query[] = $volume_query;
            }
            $volumes_bool_query = ['should' => $volumes_query];
        }


        // article must citation scopus
        $scopus_bool_query = null;
        if (isset($context["must_scopus"]) && $context["must_scopus"] == true) {

            $scopus_query = (object)array(
                'range' => ["citations.typeCitation.citation_scopus_isi" => [
                    "gte" => 1
                ]]);

            $scopus_bool_query = ['should' => $scopus_query];
        }

        $must_filter = [];
        if (isset($years_bool_query)) {
            $must_filter[] = ['bool' => $years_bool_query];
        }

        if (isset($organizes_bool_query)) {
            $must_filter[] = ['bool' => $organizes_bool_query];
        }

        if (isset($journals_bool_query)) {
            $must_filter[] = ['bool' => $journals_bool_query];
        }

        if (isset($authors_bool_query)) {
            $must_filter[] = ['bool' => $authors_bool_query];
        }
        if (isset($subjects_bool_query)) {
            $must_filter[] = ['bool' => $subjects_bool_query];
        }
        if (isset($numbers_bool_query)) {
            $must_filter[] = ['bool' => $numbers_bool_query];
        }
        if (isset($volumes_bool_query)) {
            $must_filter[] = ['bool' => $volumes_bool_query];
        }

        // articles must bool query
        if (isset($scopus_bool_query)) {
            $must_filter[] = ['bool' => $scopus_bool_query];
        }
        return $must_filter;
    }

    private function buildAndExecuteQuery($context, $field_search_query)
    {

        //filter
        //$context[years, organizes,journals]
        $filter = [
            'bool' => [
                'must' => $this->getFilter($context)
            ]
        ];

        // build query filter + search
        $bool = [
            'must' => $field_search_query,
            'filter' => $filter
        ];
        $query = ['bool' => $bool];


        // aggs
        $aggs = [
            'journals' => VciQueryES::param_journals(),
            'organizes' => VciQueryES::param_organizes(),
            'organize_count' => VciQueryES::param_organize_count(),
            'years' => VciQueryES::param_years(),
            'authors' => VciQueryES::param_authors(),
            'author_count' => VciQueryES::param_author_count(),
            'subjects' => VciQueryES::param_subjects(),
            'citation_vci' => VciQueryES::param_citation_vci(),
            'citation_scopus_isi' => VciQueryES::param_scopus_isi(),
            'citation_other' => VciQueryES::param_citation_other(),
            'sum_citation' => VciQueryES::param_sum_citation(),
            'citations' => VciQueryES::param_citations(),
        ];

        if (isset($context["field"]) && $context["field"] == 'author' && isset($context["text"]) && $context != '') {
            $aggs['author_analytic'] = VciQueryES::param_sum_self_citation_author($context["text"]);
        }

        if (isset($context["field"]) && $context["field"] == 'journal' && isset($context["text"]) && $context != '') {
            $aggs['journal_analytic'] = VciQueryES::param_citation_self_journal();
//            $aggs['hindex'] = VciQueryES::param_hindex();
        }

        if (isset($context["field"]) && $context["field"] == 'article_id' && isset($context["text"]) && $context != '') {
            $aggs['article_analytic'] = VciQueryES::param_citation_self_article();
        }


        // sort
        $mapping_sort_with_es = [
            'relevance' => '_score',
            'title' => 'title',
            'cites_count' => 'cites_count',
            'year' => 'year'
        ];

        $field_sort = isset($context["sort-col"]) ? $mapping_sort_with_es[$context["sort-col"]] : $mapping_sort_with_es['relevance'];
        $sort = [
            $field_sort => [
                'order' => isset($context["sort-dir"]) ? $context["sort-dir"] : 'desc'
            ]
        ];

        $_source = ["id",
            "title",
            "year",
            "journal_data.name",
            "journal_data.id",
            "volume",
            "number",
            "authors", "abstract", "cites_count"];
        // highlight
        $highlight = ["fields" => [
            "title" => new \stdClass(),
            "journal_data.name" => new \stdClass(),
            "authors.name" => new \stdClass(),
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
            'index' => 'test',
            'type' => 'article',
            'body' => [
                'query' => $query,
                'sort' => $sort,
                '_source' => $_source,
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
        // dd($output);
        // xử lý statistic từng trường hợp khác nhau
        $commonExtractor = new CommonExtractor();
        if (isset($context["field"]) && $context["field"] == 'author' && isset($context["text"]) && $context != '') {
            $output["citation_self_author"] = $commonExtractor->extractCitationSelfAuthor($response["aggregations"]["author_analytic"]);
        }

        if (isset($context["field"]) && $context["field"] == 'journal' && isset($context["text"]) && $context != '') {
            $output["citation_self_journal"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["journal_analytic"]);
//            $output["hindex"] = $commonExtractor->extractHIndex($response["aggregations"]["hindex"]);
        }

        if (isset($context["field"]) && $context["field"] == 'article_id' && isset($context["text"]) && $context != '') {
            $output["citation_self_article"] = $commonExtractor->extractStatisticCitations($response["aggregations"]["article_analytic"]);
        }
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


    public function searchArticleAdvanced($context)
    {
        $field_search_query = QueryHelper::buildQuery($context["search_advanced"]);

        $output = $this->buildAndExecuteQuery($context, $field_search_query);

        return $output;
    }


    private function testModeSearchAdvance()
    {
        $context["sort-col"] = "title"; //sort
        $context["sort-dir"] = "desc"; // sort
        $context["page"] = 0; // paginate offset
        $context["perPage"] = 10; // paginate
        //        $context["years"] = [2015]; // filter
        //        $context["numbers"] = ["2", "3"]; // filter
        //        $context["volumes"] = ["6"]; // filter
        //        $context["authors"] = ["Pham Hong Cong"]; // filter
        //        $context["organizes"] = ["Đại học quốc gia"]; // filter
        //        $context["journals"] = ["VNU"]; // filter
        //        $context["subjects"] = ["Khoa học tự nhiên khác"]; // filter
        // todo: add: $context["search_advanced"] , không truyền text + field như ở basic search
        $context["search_advanced"] = [['and', 'author', 'Nguyen Dinh Duc'], ['and', 'author', 'Hoang Van Tung']];
//        $context["search_advanced"] = [['and', 'title', 'Thanh niên', true], ['or', 'title', 'đa dạng sinh học', true], ['or', 'title', 'đời sống xã hội', false]];

        return $this->searchArticleAdvanced($context);
    }

    private function testModeSearch()
    {
        $context["field"] = "journal";
        $context["text"] = "Acta Mathematica Vietnamica";
        $context["match_phrase"] = true;

        $context["must_scopus"] = true; // filter các bài viết có citation_scopus_isi

        $context["sort-col"] = "cites_count"; //sort
        $context["sort-dir"] = "desc"; // sort
        $context["page"] = 0; // paginate offset
        $context["perPage"] = 10; // paginate
        $context["years"] = [2015]; // filter
//        $context["numbers"] = ["2", "3"]; // filter
//        $context["volumes"] = ["6"]; // filter
//        $context["authors"] = ["Pham Hong Cong"]; // filter
//        $context["organizes"] = ["Đại học quốc gia"]; // filter
//        $context["journals"] = ["VNU"]; // filter
//        $context["subjects"] = ["Khoa học tự nhiên khác"]; // filter
        $output = $this->serviceSearchArticleFromElasticSearch($context);
        return $output;

    }


    // test mode
    // GET /api/elasticsearch/test
    public function test(Request $request)
    {

//        return $this->testModeSearch();
//        return $this->testModeSearchAdvance();
//        $context["start"] = 2014;
//        $context["end"] = 2015;
        $context = null;
        return $this->serviceStatisticFromElasticSearch($context);

    }

    /**
     * Danh sách thống kê tổng cite, count của các cơ quan, tạp chí
     * @return array
     *
     */
    public function serviceStatisticFromElasticSearch($context = null)
    {
//        Log::debug($context["start"]);
//        Log::debug($context["end"]);

        $year_start = (isset($context) && isset($context["start_year"]) && $context["start_year"] != null) ? $context["start_year"] : null;
        $year_end = (isset($context) && isset($context["end_year"]) && $context["end_year"] != null) ? $context["end_year"] : null;
        $year_query = new \stdClass();
        if (isset($year_start)) {
            $year_query->gte = $year_start;
        }
        if (isset($year_end)) {
            $year_query->lte = $year_end;
        }

        if (isset($year_start) || isset($year_end)) {
            $query = (object)array(
                'bool' => [
                    'filter' => [
                        'range' => [
                            'year_num' => $year_query
                        ]
                    ]
                ]
            );
        }

        $commonExtractor = new CommonExtractor();
        $params = [
            'index' => 'test',
            'type' => 'article',
            'body' => [
                'query' => isset($query) ? $query : (object)[],
                '_source' => [''],
                'aggs' => [
                    'journals' => VciQueryES::param_journals(),
                    'organizes' => VciQueryES::param_organizes(),
                ],
                'size' => 0

            ]
        ];
//        Log::debug("cal search: ", $params);
//        return $params;
        $response = VciQueryES::getClientES()->search($params);
        $output = [];
        if (isset($response["aggregations"]["journals"])) {
            $output["journals"] = $commonExtractor->extractStatisticjournalFull($response["aggregations"]["journals"]);
        }
        if (isset($response["aggregations"]["organizes"])) {
            $output["organizes"] = $commonExtractor->extractStatisticOrganize($response["aggregations"]["organizes"]);
        }

        return $output;
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

        if ($field == 'author') {
            $query = ['nested' => [
                'path' => 'authors',
                'query' => ['match_phrase' => ['authors.name' => $value]]]];
        } elseif ($field == 'organize') {
            $query = ['bool' => ['should' => [
                ['match_phrase' => ['organizes_data.name' => $value]],
                ['match_phrase' => ['organizes_data.name_en' => $value]]]]];
        } elseif ($field == 'article_id') {
            $query = [['match_phrase' => [
                'id' => $value]]];
        } elseif ($field == 'journal') {
            $query = ['bool' => ['should' => [
                ['match_phrase' => ['journal_data.name' => $value]],
                ['match_phrase' => ['journal_data.name_en' => $value]]]]];
        } else {
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
