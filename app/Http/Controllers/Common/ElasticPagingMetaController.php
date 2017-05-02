<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ElasticPagingMetaController extends Controller
{
    /**
     * @param Request $request
     * @param Collection $elasticData
     * @return array
     */
    public static function getPagingMeta(Request $request, $elasticData)
    {
        $pagingMeta = [];

        $pagingMeta['current_page'] = $current_page = $request->get('page', 1);
        $pagingMeta['page_size'] = $page_size = $request->get('page_size', 10);
        $pagingMeta['num_items'] = $num_items = $elasticData->get('count', 0);
        $pagingMeta['num_pages'] = $num_pages = $num_items != 0 ? ceil($num_items / $page_size) : 0;

        $pagingMeta['has_pages'] = $num_pages > 1;
        $pagingMeta['on_first_page'] = $current_page < 2;
        $pagingMeta['has_more_pages'] = $current_page < $num_pages;
        $pagingMeta['order_num'] = function($index) use ($page_size, $current_page) {
            return $index + 1 + ($current_page - 1) * $page_size;
        };

        return $pagingMeta;
    }
}
