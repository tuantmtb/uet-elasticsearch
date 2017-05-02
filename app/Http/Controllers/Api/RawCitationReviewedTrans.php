<?php

namespace App\Http\Controllers\Api;

use App\Facade\VciHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Log;

class RawCitationReviewedTrans extends Controller
{
    /**
     * @param string $citation_raw
     * @return string
     */
    public function get_reviewed($citation_raw)
    {
        if ($citation_raw != null && $citation_raw != "") {
//            dd($citation_raw);
            $citation_raw = json_decode($citation_raw);

            if ($citation_raw == null) {
                return null;
            }

            $citation_raw_reviewed = (object)[
                'title' => isset($citation_raw->title) ? $citation_raw->title : '',
                'journalName' => isset($citation_raw->journalName) ? VciHelper::bibtextNormalize($citation_raw->journalName) : '',
                'volume' => isset($citation_raw->volume) ? VciHelper::formatNumber($citation_raw->volume) : '',
                'number' => isset($citation_raw->number) ? VciHelper::formatNumber($citation_raw->number) : '',
                'year' => isset($citation_raw->year) ? VciHelper::formatNumber($citation_raw->year) : '',
                'citedNumber' => isset($citation_raw->citedNumber) ? VciHelper::formatNumber($citation_raw->citedNumber) : '',
                'modifiedIn' => isset($citation_raw->modifiedIn) ? $citation_raw->modifiedIn : '',
                'createdAt' => isset($citation_raw->modifiedIn) ? $citation_raw->createdAt : '',
                'citedList' => isset($citation_raw->citedList) ? collect($citation_raw->citedList)->map(function ($cite) {
//                    dd($cite);
                    return (object)[
                        'title' => isset($cite->title) ? $cite->title : '',
                        'uri' => isset($cite->uri) ? $cite->uri : '',
                        'journalName' => isset($cite->journalName) ? $cite->journalName : '',
                        'volume' => isset($cite->volume) ? VciHelper::formatNumber($cite->volume) : '',
                        'number' => isset($cite->number) ? VciHelper::formatNumber($cite->number) : '',
                        'year' => isset($cite->year) ? VciHelper::formatNumber($cite->year) : '',
                        'citedNumber' => isset($cite->citedNumber) ? VciHelper::formatNumber($cite->citedNumber) : '',
                        'modifiedIn' => isset($cite->modifiedIn) ? $cite->modifiedIn : '',
                        'createdAt' => isset($cite->createdAt) ? $cite->createdAt : '',
                        'authors' => isset($cite->authors) ? VciHelper::mapNamesToAuthors(VciHelper::bibtextNormalize(VciHelper::mapAuthorsToNames($cite->authors))) : ''
                    ];
                })->toArray() : []
            ];

            return $citation_raw_reviewed;
//            return json_encode($citation_raw_reviewed, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            return null;
        }

    }

    public function run()
    {
        /**
         * @var Collection $articles
         */
        $articles = Article::where('citation_raw', '<>', 'EMPTY')->get();

        $articles->each(function ($article) {
            /**
             * @var Article $article
             */
            $citation_raw = $article->citation_raw;
            $article->update(['citation_raw_reviewed' => $this->get_reviewed($citation_raw)]);
        });
    }
}
