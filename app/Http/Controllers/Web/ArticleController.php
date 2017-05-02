<?php

namespace App\Http\Controllers\Web;

use App\DataTables\ArticleNonReviewedDatatable;
use App\DataTables\ArticleReviewedDatatable;
use App\Facade\VciCitationExtractor;
use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Http\Controllers\Common\ErrorHandler;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\ReviewArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Organize;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Html;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use Auth;

class ArticleController extends Controller
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->middleware('permission:edit')->except('show');
    }

    public function show($id, Request $request)
    {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);
        $authors = $article
            ->authors
            ->map(function ($author) {
                /**
                 * @var Author $author
                 */
                return $author
                    ->getOrganizeAndAncestors()
                    ->map(function ($organize) {
                        /**
                         * @var Organize $organize
                         */
                        return Html::link(route('organize.articles', $organize->id), $organize->name)->toHtml();
                    })
                    ->push(Html::link(route('author.articles', $author->id), $author->name)->toHtml())
                    ->reverse()
                    ->implode(', ');
            });

        $citations = collect(VciCitationExtractor::getCitation($article))
            ->map(function (array $citation) {
                $journalInfo = [];
                if ($citation['volume']) $journalInfo[] = 'Vol. ' . $citation['volume'];
                if ($citation['number']) $journalInfo[] = 'No. ' . $citation['number'];
                if ($citation['year']) $journalInfo[] = $citation['year'];
                $journalInfo = implode(', ', $journalInfo);

                $citation['journalInfo'] = $journalInfo;
                return $citation;
            });

        $elasticApi = null;
        $statistics = null;
        try {
            $elasticApi = new ElasticsearchApiController();
            $elasticData = collect($elasticApi->serviceSearchArticleFromElasticSearch(['field' => 'article_id', 'text' => $id]));
            $statistics = \App\Http\Controllers\Common\StatisticsController::extractStatistics($elasticData)->only(\VciConstants::SHOW_ARTICLE_STATISTICS_ONLY);
            $statistics['total'] = $statistics->get('total')->only(\VciConstants::SHOW_ARTICLE_STATISTICS_TOTAL_ONLY);
            if ($statistics->has('years')) {
                $statistics['years'] = $statistics->get('years')->map(function ($year) {
                    unset($year['count']);
                    return $year;
                });
            }
        } catch (NoNodesAvailableException $e) {
            ErrorHandler::noConnectElastic($e);
        }

        $context = compact('article', 'authors', 'citations', 'elasticData', 'statistics');
        \VciHelper::debug($request, $context);
        return view('pages.article.show', $context);
    }

    public function create()
    {
        return view('pages.article.create');
    }

    public function store(CreateArticleRequest $request)
    {
        $article = Article::create(array_merge($request->only([
            'title',
            'abstract',
            'keyword',
            'volume',
            'number',
            'year',
            'source',
            'uri',
            'journal_id',
            'language',
            'reference',
        ]), [
            'is_reviewed' => true,
            'editor_id' => Auth::user()->id,
        ]));

        $author_ids = collect($request->get('authors', []))
            ->map(function ($author) {
                $author_sql = Author::create(array_only($author, ['name', 'email']));

                if ($author['organize_name']) {
                    /**
                     * @var Organize $organize
                     */
                    $organize = Organize::firstOrCreate([
                        'name' => $author['organize_name']
                    ]);

                    $author_sql->syncOrganizes([$organize->id]);
                }

                return $author_sql;
            })
            ->pluck('id');
        $article->syncAuthors($author_ids);

        \Session::flash('toastr', [
            [
                'level' => 'success',
                'title' => 'Tạo bài thành công',
                'message' => "Đã tạo $article->title",
            ]
        ]);
        return redirect()->route('article.show', $article->id);
    }

    public function reviewed(ArticleReviewedDatatable $datatable)
    {
        return $datatable->render('pages.article.reviewed');
    }

    public function non_reviewed(ArticleNonReviewedDatatable $datatable)
    {
        return $datatable->render('pages.article.non_reviewed');
    }

    public function edit($id)
    {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);
        $article = $article->load('journal', 'authors');
        $journals = $article->journal ? [$article->journal_id => $article->journal->name] : [];
        return view('pages.article.edit', compact('article', 'journals'));
    }

    public function update($id, UpdateArticleRequest $request)
    {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);

        $article->update(
            array_merge($request->only([
                'title',
                'abstract',
                'keyword',
                'volume',
                'number',
                'year',
                'source',
                'uri',
                'journal_id',
                'language',
                'reference',
            ]), [
                'is_reviewed' => true,
                'editor_id' => Auth::user()->id,
            ])
        );

        $article->authors()->getQuery()->delete();

        $author_ids = collect($request->get('authors', []))
            ->map(function ($author) {
                $author_sql = Author::create(array_only($author, ['name', 'email']));

                if ($author['organize_name']) {
                    /**
                     * @var Organize $organize
                     */
                    $organize = Organize::firstOrCreate([
                        'name' => $author['organize_name']
                    ]);

                    $author_sql->syncOrganizes([$organize->id]);
                }

                return $author_sql;
            })
            ->pluck('id');
        $article->syncAuthors($author_ids);

        \Session::flash('toastr', [
            [
                'level' => 'success',
                'title' => 'Sửa bài thành công',
                'message' => "Đã sửa $article->title",
            ]
        ]);
        return redirect()->route('article.show', $article->id);
    }

    public function review($id, ReviewArticleRequest $request)
    {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);
        $editor_id = Auth::user()->id;
        $is_reviewed = $request->get('is_reviewed');

        $article->update(compact('is_reviewed', 'editor_id'));
        \Session::flash('toastr', [
            [
                'level' => 'info',
                'title' => ($is_reviewed ? 'Duyệt' : 'Loại') . ' bài viết',
                'message' => 'Đã ' . ($is_reviewed ? 'duyệt ' : 'loại ') . $article->title,
            ]
        ]);
        return back();
    }
}