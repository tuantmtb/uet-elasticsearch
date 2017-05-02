<?php

namespace App\Http\Controllers\Web;

use App\Models\Article;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Organize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class AnalyticController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Cập nhật chỉ số trích dẫn dựa trên các quan hệ đã có
     */
    public function updateCitation()
    {
        $articles = Article::all();
        foreach ($articles as $article) {
            $article->cites_count = $article->cites->count();
            $article->save();
        }
    }

    public function testMode(Request $request)
    {

//        $organizes = Organize::join('authors_organizes', 'authors_organizes.organize_id', '=', 'organizes.id')
//            ->join('authors', 'authors_organizes.author_id', '=', 'authors.id')
//            ->join('articles_authors', 'articles_authors.author_id', '=', 'authors.id')
//            ->join('articles', 'articles.id', '=', 'articles_authors.article_id')
//            ->join('journals', 'articles.journal_id', '=', 'journals.id')
//            ->newQuery();
//
//        if ($request['title'] != null) {
//            $title = $request['title'];
//            $organizes->where('articles.title', 'LIKE', "%$title%");
//        }
//
//        if ($request['year'] != null) {
//            $year = $request['year'];
//            $organizes->where('articles.year', '=', $year);
//        }
//
//        if ($request['keyword'] != null) {
//            $keyword = $request['keyword'];
//            $organizes->where('articles.keyword', '=', $keyword);
//        }
//
//        if ($request['journal'] != null) {
//            $keyword = $request['journal'];
//            $organizes->where('journals.name', 'LIKE', "%$keyword%")
//                ->orWhere('journals.name_en', 'LIKE', "%$keyword%");
//        }
//
//        if ($request['author'] != null) {
//            $author = $request['author'];
//            $organizes->where('authors.name', 'LIKE', "%$author%");
//        }
//
//        if ($request['organize'] != null) {
//            $organize = $request['organize'];
//            $organizes->where('organizes.name', 'LIKE', "%$organize%")
//                ->orWhere('organizes.name_en', 'LIKE', "%$organize%");
//        }
//
//        $ids = $organizes->select('articles.id as article_id', 'articles.title as article_title', 'articles.year as years', 'articles.keyword as article_keyword',
//            'journals.id as journal_id',
//            'authors.id as author_id',
//            'organizes.id as organize_id'
//        );
//
//        $articles = Article::whereIn('id', $ids->get('article_id'));
//        $authors = Author::whereIn('id', $ids->get('author_id'));
//        $organizes = Organize::whereIn('id', $ids->get('organize_id'));

        $articles = Article::take(10);
        return response()->json($articles->get());

    }

}
