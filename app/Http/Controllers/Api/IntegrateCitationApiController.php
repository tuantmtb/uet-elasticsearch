<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Organize;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kalnoy\Nestedset\NodeTrait;
use Log;

class IntegrateCitationApiController extends Controller
{
    /**
     * get article
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticleNeedUpdate(Request $request)
    {
        $article = Article::whereNull('citation_status')->newQuery()->orderBy('updated_at')->first();
        if ($request->input('queue')) {
            $article->citation_status = "pending";
            $article->save();
        };
        $article->journal;
        return response()->json($article);
    }

    public function getArticleNeedUpdateByJournal(Request $request, $journal_id)
    {
        /**
         * @var Article $article
         */
        $article = Article::whereNull('citation_status')->newQuery()->where('journal_id', '=', $journal_id)->orderBy('updated_at')->first();
        if ($request->input('queue')) {
            $article->citation_status = "pending";
            $article->save();
        };
        $article->journal;
        return response()->json($article);
    }

    /**
     * update citation
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCitationRaw($id, Request $request)
    {
        $output = [];
        $article = Article::findOrFail($id);
//        dd($requep ast->toArray());
        if ($request['status'] == 'success') {
            $article->citation_raw = $request['data'];

            $rawCitationTrans = new \App\Http\Controllers\Api\RawCitationReviewedTrans();


            $article->citation_status = "done";
            if ($request['citedNumber'] != null) {
                $article["cites_count"] = $request["citedNumber"];

                $citation = $rawCitationTrans->get_reviewed($article->citation_raw);
                if (isset($citation) && isset($citation->citedList)) {
                    $count = sizeof($citation->citedList);
                    if (isset($count) && $article->cites_count != $count) {
                        $article->cites_count = $count;
                    }
                }

            }

            $article->save();
            $output["status"] = "success";
            $output["data"] = Article::find($id);
//            Log::debug("Article id = " . $id . " data = " . $request['data']);
        } else {
            $output["status"] = "fail";
            $output["message"] = "can update because status != success";
        }
        return response()->json($output);

    }

}
