<?php

namespace App\Http\Controllers\Api;

use App\Facade\VciHelper;
use App\Models\Article;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Organize;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class ReviewCitationApiController extends Controller
{
    /**
     * Api duyệt citation
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function review($id, Request $request)
    {
//        Log::debug($request);
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($id);
        $now = Carbon::now()->format('m-d-Y H:i:s');

        $journal = Journal::find($request->get('journal'));
        if ($journal) {
            $article->assignJournal($journal);
        } else {
            $article->removeJournal();
        }

        $cites_count = $request->get('cites_count');
        $authors_count = $request->get('authors_count');

        $map = [
            'title' => 'title',    // string
            'source' => 'uri',   // string
            'journal' => 'journalName',  // id
            'volume' => 'volume',   // int
            'number' => 'number',   // int
            'year' => 'year',     // int
            'cites_count' => 'citedNumber', // int
        ];

        $cites = [];
        foreach (range(0, $cites_count - 1) as $index) {
            $cite = [];
            foreach ($map as $key => $value) {
                $cite[$value] = $request->get('cite-' . $index . '-' . $key, '');
            }
            $cite['authors'] = VciHelper::mapNamesToAuthors($request->get('cite-' . $index . '-authors', ''))->toArray();
            $cite['modifiedIn'] = $now;
            $cite['createdAt'] = $now;
            $cites[] = (object)$cite;
        }

        $authors = [];
        $authors_sql_ids = [];
        foreach (range(0, $authors_count - 1) as $index) {
            $author = $request->get('authors-' . $index);
            $author = (object)$author;
            if (isset($author->id) && $author->id != null) {
                /**
                 * @var Author $author_sql
                 */
                $author_sql = Author::find($author->id);
                $author_sql->update([
                    'name' => $author->name,
                    'email' => $author->email,
                ]);
                if ($author->organize != null){
                    $author_sql->syncOrganizes([$author->organize]);
                } else {
                    $author_sql->syncOrganizes([]);
                }
                $authors_sql_ids[] = $author_sql->id;
            } else {
                $author_sql = Author::create([
                    'name' => $author->name,
                    'email' => $author->email,
                ]);
                if ($author->organize != null) {
                    $author_sql->syncOrganizes([$author->organize]);
                }
                $authors_sql_ids[] = $author_sql->id;
                $author->id = $author_sql->id;
            }
            $authors[] = $author;
        }
        $article->syncAuthors($authors_sql_ids);

        $json = (object)[
            'title' => $request->get('title'),
            'journalName' => $journal ? $journal->name : '',
            'volume' => $request->get('volume'),
            'number' => $request->get('number'),
            'citedNumber' => count($cites),
            'year' => $request->get('year'),
            'modifiedIn' => $now,
            'createdAt' => $article->created_at->format('m-d-Y H:i:s'),
            'citedList' => $cites,
            'authors' => $authors,
        ];

        $article->update([
            'title' => $request->get('title'),
            'cites_count' => count($cites),
            'citation_raw_reviewed' => json_encode($json),
            'num_citation_reviewed' => $article->num_citation_reviewed + 1,
        ]);

        return response("success", 200);
    }

    public function addCiteView(Request $request)
    {
        $stt = $request->get('stt');
        $map = collect([
            'title' => 'Tiêu đề',
            'source' => 'Link',
            'journal' => 'Tạp chí',
            'volume' => 'Volume',
            'number' => 'Số',
            'year' => 'Năm',
            'cites_count' => 'Số trích dẫn',
            //'updated_at' => 'Sửa lúc',
            //'created_at' => 'Tạo lúc',
            'authors' => 'Tác giả'
        ]);
        return view('vci_views.review_citation.add_cite', compact('stt', 'map'));
    }

    public function authorInputTpl() {
        $organizes = Organize::pluck('name', 'id')->reverse()->put('', '')->reverse();
        return view('vci_views.review_citation.author_input_tpl', compact('organizes'));
    }

    public function addAuthorView(Request $request) {
        $index = $request->get('index');
        return view('vci_views.review_citation.add_author', compact('index'));
    }
}
