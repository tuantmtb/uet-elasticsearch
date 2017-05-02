<?php

namespace App\Http\Controllers\Api\job;

use App\Http\Controllers\Api\IndexingElasticsearchApiController;
use App\Http\Controllers\Controller;
use App\Jobs\IndexElastic;
use App\Models\Article;
use VciQueryES;

class IndexElasticsearch extends Controller
{

    public function test()
    {

        $job = (new IndexElastic())->onQueue('indexing');
        $this->dispatch($job);
        return "done test";
    }

}