<?php

namespace App\Http\Controllers\Api;


use App\Facade\VciCitationExtractor;
use App\Models\Author;
use App\Models\Article;
use App\Models\Journal;
use App\Models\JournalInternational;
use App\Models\Organize;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kalnoy\Nestedset\NodeTrait;
use Elasticsearch\ClientBuilder;
use Log;


class IndexingElasticsearchApiController extends Controller
{

    public function getIdArticles()
    {
        $ids = Article::all('id');


        return $ids;

    }

    public function getInfoArticle($article_id)
    {
        $data = [];
        /**
         * @var $article Article
         */
        $article = Article::with(["journal", "authors", "citeds", "cites"])->findOrFail($article_id);
//        dd($article->year < 2016);
        if (isset($article->year) && $article->year < 2006) {

            return response('', 404);
        }

        $data["id"] = $article->id;
        $data["title"] = $article->title;
        $data["abstract"] = $article->abstract;
        $data["volume"] = $article->volume;
        $data["number"] = $article->number;
        $data["year"] = $article->year;
        $data["year_num"] = intval($article->year);
        $data["reference"] = $article->reference;
        $data["cites_count"] = $article->cites_count;
        $data["journal_id"] = $article->journal_id;
        $data["language"] = $article->language;
        $data["keyword"] = $article->keyword;
//        $data["citation_raw"] = $article->citation_raw; // remove field because have citation_reviewed


        // TODO: edit citaion extractor

        $data["citations"] = VciCitationExtractor::getCitation($article);
        $data["citations_new"] = VciCitationExtractor::getCitation($article);
        $data["citations_per_year"] = VciCitationExtractor::getCitationsPerYear($data["citations"]);
        $authors = $article->authors;

        $articles_authors = [];
        $organizes_data = [];
        $organizes_id = [];


        $authors_full = collect($authors)->map(function ($author) {
            return $author['name'];
        })->implode(', ');

        $data["authors_full"] = $authors_full;

        $e_authors = [];
        /**
         * @var $author Author
         */
        foreach ($authors as $author) {
            $articles_authors[] = $author->id;
            $authorOutput = [];
            $authorOutput["id"] = $author->id;
            $authorOutput["name"] = $author->name;
            $authorOutput["email"] = $author->email;
            $authorOutput["citation_self_author"] = VciCitationExtractor::getCitationForAuthor($article, $author);

            if (isset($author->organizes) && $author->organizes->first() != null) {
                $authorOutput["organize_id"] = $author->organizes->first()->id;
                $organizes_id[] = $author->organizes->first()->id;
                foreach ($author->organizes->first()->getAncestors() as $org_child) {
                    $organizes_id[] = $org_child->id;
                };
            }
            $data["authors"][] = $authorOutput;
            $data["articles_authors_data"][] = $authorOutput; //todo deprecated when new mapping elasticsearch
        }


        foreach ($article->authors as $author) {
            $e_author = [];
//            dd($author->toArray());
            $e_author["name"] = $author->name;

            $authors[] = $e_author;
//            $data["citations"] = VciCitationExtractor::getCitationForAuthor($article, $author);
        }
//        dd($authors);


        $data["articles_authors"] = $articles_authors;
        $data["organizes_id"] = $this->filterDuplicate($organizes_id);

//        dd($organizes_id);

        foreach ($data["organizes_id"] as $organize_id) {
            $_organize = Organize::find($organize_id);
            $organize["id"] = $_organize->id;
            $organize["name"] = $_organize->name;
            $organize["name_en"] = $_organize->name_en;
            $organize["address"] = $_organize->address;
            $organize["_lft"] = $_organize->_lft;
            $organize["_rgt"] = $_organize->_rgt;
            $organize["parent_id"] = $_organize->parent_id;
            $organizes_data[] = $organize;
        }

        $data["organizes_data"] = $organizes_data;

        $data["journal_data"]["id"] = $article->journal->id;
        $data["journal_data"]["name"] = $article->journal->name;
        $data["journal_data"]["name_en"] = $article->journal->name_en;
        $data["journal_data"]["description"] = $article->journal->description;
        $subjects = [];
        $subjects_id = [];

        // get all subject_id
        foreach ($article->journal->subjects as $subject) {
            $subjects_id[] = $subject->id;
            foreach ($subject->getAncestors() as $sub_child) {
                $subjects_id[] = $sub_child->id;
            };
        }
        // remove duplicate
        $subjects_id = $this->filterDuplicate($subjects_id);
        foreach ($subjects_id as $subject_id) {
            $sub = [];
            $subject_find = Subject::find($subject_id);
            if ($subject_find != null) {
                $sub["id"] = $subject_find->id;
                $sub["name"] = $subject_find->name;
            }
            $subjects[] = $sub;
        }

        $data["subjects"] = $subjects;
        $data["subjects_id"] = $subjects_id;
        return $data;
    }

    public function getInfoOrganize($organize_id)
    {
        /**
         * @var $organize Organize
         */
        $organize = Organize::findOrFail($organize_id);
        $data = [];
        $data["id"] = $organize->id;
        $data["name"] = $organize->name;
        $data["name_en"] = $organize->name_en;
        $data["address"] = $organize->address;
        $data["_lft"] = $organize->_lft;
        $data["_rgt"] = $organize->_rgt;
        $data["parent_id"] = $organize->parent_id;

        $name = $organize->name;
        foreach ($organize->ancestors()->get() as $org_parent) {
            $name .= ', ' . $org_parent->name;
        }
        $data["fullname"] = $name;
        return $data;
    }

    public function getInfoOrganizes()
    {
        $organizes = Organize::query()->whereNull('glink')->get();

        foreach ($organizes as $organize) {
            $organizeOutput = [];
            $organizeOutput["id"] = $organize->id;
            $organizeOutput["name_en"] = $organize->name_en;
            $organizeOutput["name"] = $organize->name;
            $fullname = $organize->name;
            foreach ($organize->ancestors()->get() as $org_parent) {
                $fullname .= ', ' . $org_parent->name;
            }
            $organizeOutput["fullname"] = $fullname;
            $data[] = $organizeOutput;
        }
        return $data;

    }

    public function getInfoJournal($journal_id)
    {

        /**
         * @var $journal Journal
         */
        $journal = Journal::findOrFail($journal_id);
        $data = [];
        $data["name"] = $journal->name;
        $data["name_en"] = $journal->name_en;
        $data["description"] = $journal->description;

        return $data;
    }

    public function getInfoArticleFromSQL($article_id)
    {

        $data = $this->getInfoArticle($article_id);
        return response()->json($data);
    }

    private function filterDuplicate($array)
    {
        $output = [];
        foreach ($array as $item) {
            if (!in_array($item, $output)) {
                $output[] = $item;
            }
        }
        return $output;
    }

    public function getInfoAuthorFromSQL($author_id)
    {
        /**
         * @var $author Author
         */
        $author = Author::findOrFail($author_id);
        $data["name"] = $author->name;
        $data["email"] = $author->email;
        $data["description"] = $author->description;
        $organizes_id = [];
        $organizes_data = [];

        if (isset($author->organizes) && $author->organizes->first() != null) {
            $organizes_id[] = $author->organizes->first()->id;
            foreach ($author->organizes->first()->getAncestors() as $org_child) {
                $organizes_id[] = $org_child->id;
            };
        }

        foreach ($organizes_id as $organize_id) {
            $_organize = Organize::find($organize_id);
            $organize["id"] = $_organize->id;
            $organize["name"] = $_organize->name;
            $organize["name_en"] = $_organize->name_en;
            $organize["address"] = $_organize->address;
            $organize["_lft"] = $_organize->_lft;
            $organize["_rgt"] = $_organize->_rgt;
            $organize["parent_id"] = $_organize->parent_id;
            $organizes_data[] = $organize;
        }
        $data["organizes_id"] = $organizes_id;
        $data["organizes_data"] = $organizes_data;

        return response()->json($data);
    }

    public function getInfoOrganizeFromSQL($organize_id)
    {
        $data = $this->getInfoOrganize($organize_id);
        return response()->json($data);
    }

    public function getInfoJournalFromSQL($journal_id)
    {
        $data = $this->getInfoJournal($journal_id);

        return response()->json($data);
    }


}
