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


}