<?php

namespace App\Facade;


use Illuminate\Support\Facades\Facade;

class VciConstants extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'VciConstants';
    }

    // --------------------------- DÀNH RIÊNG CHO SEARCH ARTICLE ---------------------
    const SEARCH_ARTICLE_FIELDS = [
        'title' => 'Tiêu đề',
        'abstract' => 'Tóm tắt',
        'keyword' => 'Từ khoá',
        'year' => 'Năm',
        'author' => 'Tác giả',
        'journal' => 'Tạp chí',
        'organize' => 'Cơ quan',
    ];

    const SEARCH_ARTICLE_SORTS = [
        'relevance' => 'Độ liên quan',
        'title' => 'Tiêu đề',
        'cites_count' => 'Số trích dẫn',
        'year' => 'Năm',
    ];

    const SEARCH_ARTICLE_FILTERS = [
        'years',
        'authors',
        'journals',
        'organizes',
        'subjects',
    ];

    // -------------------------- SHOW ARTICLE ----------------------------------
    const SHOW_ARTICLE_STATISTICS_ONLY = ['total', 'years'];

    const SHOW_ARTICLE_STATISTICS_TOTAL_ONLY = ['citation', 'citation_vci', 'citation_scopus_isi', 'citation_other', 'citation_self_article'];

    // -------------------------- STATISTICS ----------------------------
    const JOURNAL_STATISTICS_TOTAL_ONLY = ['count', 'citation', 'articles_citation_count', 'citation_self', 'citation_vci', 'citation_scopus_isi', 'citation_other', 'hindex', 'proprietor'];

    const ORGANIZE_STATISTICS_TOTAL_ONLY = ['count', 'citation', 'citation_self', 'citation_vci', 'citation_scopus_isi', 'citation_other'];

    // -------------------------- DÙNG CHUNG --------------------------------------
    const SEARCH_CONNECTORS = [
        'and' => 'Và',
        'or' => 'Hoặc',
        'not' => 'Không'
    ];

    const FILTER_NAMES = [
        'years' => 'năm',
        'authors' => 'tác giả',
        'journals' => 'tạp chí',
        'organizes' => 'cơ quan',
        'subjects' => 'lĩnh vực',
    ];

    const STATISTICS_CHARTS = [
        'years' => 'Theo năm',
        'authors' => 'Theo tác giả',
        'journals' => 'Theo tạp chí',
        'organizes' => 'Theo cơ quan',
        'subjects' => 'Theo lĩnh vực',
    ];

    /**
     * Các dữ liệu thống kê xuất hiện trong $elasticData sẽ auto được extract ra $statistics['total']
     */
    const STATISTICS_TOTAL = [
        'count' => 'Tổng số bài báo',
        'citation' => 'Tổng số trích dẫn',
        'articles_citation_count' => 'Số bài báo được trích dẫn',
        'citation_vci' => 'Trích dẫn từ tạp chí trong nước',
        'citation_scopus_isi' => 'Trích dẫn từ các tạp chí Scopus/ISI',
        'citation_other' => 'Trích dẫn từ các nguồn khác',
        'citation_self' => 'Trích dẫn từ cùng tạp chí',
        'citation_self_article' => 'Trích dẫn từ chính các tác giả',
        'citation_self_journal' => 'Trích dẫn từ cùng tạp chí',
        'citation_self_author' => 'Trích dẫn từ cùng tác giả',
        'proprietor' => 'Đơn vị chủ quản',
        'hindex' => 'H-index',
    ];

    const SORT_DIRS = [
        'desc' => 'Giảm dần',
        'asc' => 'Tăng dần',
    ];

    const PAGE_SIZES = [
        10 => 10,
        25 => 25,
        50 => 50,
        75 => 75,
        100 => 100,
    ];

    const LOCALIZE = [
        'vi' => 'Tiếng Việt',
        'en' => 'Tiếng Anh',
        'cn' => 'Tiếng Trung',
        'fr' => 'Tiếng Pháp',
        'ru' => 'Tiếng Nga',
        'ge' => 'Tiếng Đức',
        'jo' => 'Tiếng Nhật',
    ];
}