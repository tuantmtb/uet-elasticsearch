<?php

namespace App\Http\Controllers\Web;

use App\DataTables\ReviewCitationDataTable;
use App\Facade\VciHelper;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Organize;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewCitationController extends Controller
{

    /**
     * ReviewCitationController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:edit');
    }

    public function index(ReviewCitationDataTable $dataTable) {
        $title = 'Xét duyệt citation';
        return $dataTable->render('vci_views.manage.datatable_base_review', compact('title'));
    }

    public function show($id, Request $request) {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);

        if (config('app.debug') && $request->has('debug')) {
            dd($article->citation_raw);
        }
        if ($article->citation_raw == null || $article->citation_raw === '') {
            \Session::flash('toastr', [
                [
                    'level' => 'warning',
                    'title' => 'Không có thông tin để duyệt',
                    'message' => 'Bài báo ' . $article->title . ' không có thông tin để duyệt',
                ]
            ]);
            return redirect()->route('manage.review_citation.index');
        }
        $raw = self::decode($article->citation_raw);
        $raw_cites = collect($raw['cites']);

        if($article->citation_raw_reviewed == null) {
            $cites = collect();
        } else {
            $raw_reviewed = self::decode($article->citation_raw_reviewed, false);
            $cites = collect($raw_reviewed['cites']);
        }

        $map = collect([
            'title' => 'Tiêu đề',
            'source' => 'Link',
            'journal' => 'Tạp chí',
            'volume' => 'Volume',
            'number' => 'Số',
            'year' => 'Năm',
            'cites_count' => 'Số trích dẫn',
            'updated_at' => 'Sửa lúc',
            'created_at' => 'Tạo lúc',
            'authors' => 'Tác giả'
        ]);
        $max_cites_count = max($article->cites_count, $raw_cites->count());

        $journals = Journal::pluck('name', 'id');
        $article_authors = $article->authors->map(function($author) {
            /**
             * @var Author $author
             */
            return [
                'id' => $author->id,
                'name' => $author->name,
                'email' => $author->email,
                'organize' => $author->organizes->isEmpty() ? null : $author->organizes->first()->id,
            ];
        });
        return view('vci_views.review_citation.show', compact('article', 'raw', 'max_cites_count', 'raw_cites', 'cites', 'map', 'journals', 'article_authors'));
    }

    /**
     * GET manage/articles/id/reviewcitation
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function raw($id) {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);
        return self::decode($article->citation_raw);
    }

    private static function decode($json, $htmlLink = true)
    {
        $output = collect();
        $citation_raw = json_decode($json);
        $output->put("title", $citation_raw->title);
        //$output->put("source", \Html::link($citation_raw->uri));
        $output->put("journal", VciHelper::bibtextNormalize($citation_raw->journalName));
        $output->put("volume", VciHelper::formatNumber($citation_raw->volume));
        $output->put("number", VciHelper::formatNumber($citation_raw->number));
        $output->put("year", VciHelper::formatNumber($citation_raw->year));
        $output->put("cites_count", VciHelper::formatNumber($citation_raw->citedNumber));
        $output->put("updated_at", VciHelper::formatDateTime($citation_raw->modifiedIn));
        $output->put("created_at", VciHelper::formatDateTime($citation_raw->createdAt));
        //$output->put("clusterId", $citation_raw->clusterId);
        //$output->put("citeId", $citation_raw->citeId);

        $output->put("authors", VciHelper::bibtextNormalize(VciHelper::mapAuthorsToNames($citation_raw->authors)));
        $citesArray = [];
        if (isset($citation_raw->citedList)) {
            foreach ($citation_raw->citedList as $cites) {
                $citeOutput = collect();
                $citeOutput->put("title", $cites->title);
                $citeOutput->put("source", $htmlLink ? \Html::link($cites->uri) : $cites->uri);
                $citeOutput->put("journal", $cites->journalName);
                $citeOutput->put("volume", VciHelper::formatNumber($cites->volume));
                $citeOutput->put("number", VciHelper::formatNumber($cites->number));
                $citeOutput->put("year", VciHelper::formatNumber($cites->year));
                $citeOutput->put("cites_count", VciHelper::formatNumber($cites->citedNumber));
                $citeOutput->put("updated_at", VciHelper::formatDateTime($cites->modifiedIn));
                $citeOutput->put("created_at", VciHelper::formatDateTime($cites->createdAt));
                //$citeOutput->put("clusterId", $cites->clusterId);
                $citeOutput->put("authors", VciHelper::bibtextNormalize(VciHelper::mapAuthorsToNames($cites->authors)));
                $citesArray[] = $citeOutput->toArray();
            }
        }
        $output->put("cites", $citesArray);
        $output->put('cites_count', count($citesArray));

        return $output;
    }
}
