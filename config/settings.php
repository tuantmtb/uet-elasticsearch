<?php

return [
    'per_page' => 10,
    'elastic_search_ips' => [env('ELASTIC_VNU_IP', '112.137.131.9:9200')],
    'elastic_index_ips' => [env('ELASTIC_INDEX_VNU_IP', null)],
    'max_result_search' => env('MAX_RESULT_SEARCH')
];