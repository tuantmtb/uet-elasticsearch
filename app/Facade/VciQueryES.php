<?php
/**
 * Created by PhpStorm.
 * User: nguye
 * Date: 3/29/2017
 * Time: 11:28 AM
 */

namespace App\Facade;

use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

class VciQueryES extends Facade
{


    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'VciQueryES';
    }

    /**
     * Client Elasticsearch
     * @return \Elasticsearch\Client
     */
    public static function getClientES()
    {
        $hosts = config('settings.elastic_search_ips');

        $client = ClientBuilder::create()
            ->setHosts($hosts)// Set the hosts
            ->build();
        return $client;
    }

    /**
     * Client dùng cho index, trong môi trường test thì set là 127.0.0.1
     * Client Elasticsearch
     * @return \Elasticsearch\Client
     */
    public static function getClientESIndex()
    {
        $hosts = config('settings.elastic_index_ips');
        if ($hosts == null) {
            return null;
        }
        $client = ClientBuilder::create()
            ->setHosts($hosts)// Set the hosts
            ->build();
        return $client;
    }

    public static function param_articles($articles_size = 1000)
    {
        $articles_ids = [
            'terms' => [
                'field' => 'id',
                'size' => $articles_size
            ]
        ];

        return $articles_ids;
    }

    public static function param_journals($journal_size = 100)
    {
        $journals_ids = [
            'terms' => [
                'field' => 'journal_id',
                'size' => $journal_size
            ],
            'aggs' => [
                'sum_citation' => [
                    'sum' => [
                        'field' => 'cites_count'
                    ]
                ],
                'years' => [
                    'terms' => [
                        'field' => 'year',
                        'size' => 30,
                    ],
                    'aggs' => [
                        'sum_citation' => [
                            'sum' => [
                                'field' => 'cites_count'
                            ]
                        ]
                    ]
                ],
                'hindex' => VciQueryES::param_hindex(),
                "citation_scopus_isi" => VciQueryES::param_scopus_isi(),
                'articles_citation_count' => VciQueryES::param_articles_citation_count(),
                'avg_citation' => VciQueryES::param_avg_citation(),
                'max_citation' => VciQueryES::param_max_citation(),
                'citing_count'=> VciQueryES::param_article_citing_count()
            ]
        ];

        return $journals_ids;
    }

    public static function param_organizes($organizes_size = 30)
    {
        $organizes_id = [
            'terms' => [
                'field' => 'organizes_id',
                'size' => $organizes_size
            ],
            'aggs' => [
                'sum_citation' => [
                    'sum' => [
                        'field' => 'cites_count'
                    ]
                ]
            ]
        ];
        return $organizes_id;
    }

    public static function param_organize_count()
    {
        $organize_count = [
            'cardinality' => [
                'field' => 'organizes_data.name.keyword'
            ]
        ];

        return $organize_count;
    }

    public static function param_years($years_size = 30)
    {
        $years = [
            'terms' => [
                'field' => 'year',
                'order' => [
                    '_term' => 'asc'
                ],
                'size' => $years_size
            ],
            'aggs' => [
                'sum_citation' => [
                    'sum' => [
                        'field' => 'cites_count'
                    ]
                ]
            ]
        ];
        return $years;
    }


    public static function param_citations()
    {
        $citations = [
            "nested" => [
                "path" => "citations_per_year"
            ],
            "aggs" => [
                "per_year" => [
                    "terms" => [
                        "field" => "citations_per_year.year"
                    ],
                    "aggs" => [
                        "total_cites" => [
                            "sum" => [
                                "field" => "citations_per_year.cite_count"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $citations;
    }

    public static function param_hindex()
    {
        $hindex = [
            "terms" => [
                "field" => "cites_count",
                "size" => 500,
                "order" => [
                    "_term" => "desc"
                ]
            ]
        ];
        return $hindex;
    }

    public static function param_authors()
    {
        $authors = [
            "terms" => [
                "field" => "articles_authors_data.name.keyword",
                "order" => [
                    '_count' => 'desc'
                ],
                'size' => '150'
            ],
            "aggs" => [
                "sum_citation" => [
                    "sum" => [
                        "field" => "cites_count"
                    ]
                ]
            ]
        ];
        return $authors;
    }

    public static function param_author_count()
    {
        $author_count = [
            "cardinality" => [
                "field" => "articles_authors_data.name.keyword"
            ]
        ];
        return $author_count;
    }

    public static function param_subjects()
    {
        $subjects = [
            'terms' => [
                'field' => 'subjects.id',
                'order' => [
                    '_count' => 'desc'
                ]
            ],
            'aggs' => [
                'sum_citation' => [
                    'sum' => [
                        'field' => 'cites_count'
                    ]
                ]
            ]
        ];
        return $subjects;
    }

    public static function param_citation_self()
    {
        $citation_self = [
            "sum" => [
                "field" => "citations.typeCitation.citation_self"
            ]
        ];
        return $citation_self;
    }

    public static function param_citation_self_journal()
    {
        $citation_self = [
            "sum" => [
                "field" => "citations.typeCitation.citation_self_journal"
            ]
        ];
        return $citation_self;
    }

    public static function param_citation_self_article()
    {
        $citation_self = [
            "sum" => [
                "field" => "citations.typeCitation.citation_self_article"
            ]
        ];
        return $citation_self;
    }

    public static function param_citation_vci()
    {
        $citation_vci = [
            "sum" => [
                "field" => "citations.typeCitation.citation_vci"
            ]
        ];
        return $citation_vci;
    }

    public static function param_scopus_isi()
    {
        $citation_scopus_isi = [
            "sum" => [
                "field" => "citations.typeCitation.citation_scopus_isi"
            ]
        ];
        return $citation_scopus_isi;
    }

    public static function param_citation_other()
    {
        $citation_other = [
            "sum" => [
                "field" => "citations.typeCitation.citation_other"
            ]
        ];
        return $citation_other;
    }

    public static function param_sum_citation()
    {
        $sum_citation = [
            "sum" => [
                "field" => "cites_count"
            ]
        ];
        return $sum_citation;
    }

    public static function param_organizes_collaborate()
    {
        $organizes_collaborate = [
            'terms' => [
                'field' => 'organizes_data.id',
                'size' => 15,
                'order' => [
                    '_count' => 'desc'
                ]
            ]
        ];
        return $organizes_collaborate;
    }

    /**
     * Tính citation self author by name author
     * @param $author_name
     * @return array
     */
    public static function param_sum_self_citation_author($author_name)
    {
        $self_citation_author = [
            "nested" => [
                "path" => "authors"
            ],
            "aggs" => [
                "authors_match" => [
                    "filter" => [
                        "match_phrase" => [
                            "authors.name" => $author_name
                        ]
                    ],
                    "aggs" => [
                        "sum_citation" => [
                            "sum" => [
                                "field" => "authors.citation_self_author"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $self_citation_author;
    }

    public static function param_articles_citation_count()
    {
        $range = new \stdClass();
        $range->from = 1;
        $articles_citation_count = [
            "range" => [
                "field" => "cites_count",
                "ranges" => [
                    $range
                ]
            ]
        ];
        return $articles_citation_count;
    }

    public static function param_avg_citation()
    {

        $avg_citation = (object)[
            "avg" => [
                "field" => "cites_count"
            ]
        ];
        return $avg_citation;
    }

    public static function param_max_citation()
    {

        $avg_citation = (object)[
            "max" => [
                "field" => "cites_count"
            ]
        ];
        return $avg_citation;
    }

    public static function param_article_citing_count()
    {

        $citing_count = (object)[
            "nested" => [
                "path" => "citations_new"
            ],
            "aggs" => [
                "citing_count_value" => [
                    "cardinality" => [
                        "field" => "citations_new.hash"
                    ]
                ]
            ]
        ];
        return $citing_count;
    }


}