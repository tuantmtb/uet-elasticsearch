<?php

namespace App\Http\Controllers\Web;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchArticleFromAuthorController extends SearchArticleController
{
    /**
     * @var Author $author
     */
    private $author;

    protected function getStatistics($elasticData)
    {
        $statistics = parent::getStatistics($elasticData);
        $total = collect([
            'name' => "Họ và tên: " . $this->author->name,
        ])->merge($statistics->get('total'));

        /**
         * @var Collection $authors
         */
        $authors = $statistics->get('authors');
        $authors = $authors->where('fullname', "!=", $this->author->name);

        /**
         * @var Collection $organizes
         */
        $organizes = $statistics->get('organizes');
        if ($this->author->organizes->isNotEmpty()) {
            $organizes = $organizes->where('id', "!=", $this->author->organizes->first()->id);
        }

        $statistics = $statistics->merge(compact('total', 'authors', 'organizes'))->except('subjects');

        return $statistics;
    }

    protected function customContext($context)
    {
        $context['page_title'] = $this->author->name;
        return $context;
    }

    protected function viewName()
    {
        return 'pages.search.author_articles';
    }

    public function searchFromAuthor($id, Request $request)
    {
        /**
         * @var Author $author
         */
        $this->author = $author = Author::findOrFail($id);
        $request->merge([
            'field' => 'author',
            'text' => $author->name,
        ]);
        $request->replace($request->except('advance'));
        return parent::search($request);
    }
}
