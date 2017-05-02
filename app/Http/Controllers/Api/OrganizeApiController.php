<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\elasticsearch\ESOrganizeSearch;
use App\Models\Article;
use App\Models\Organize;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kalnoy\Nestedset\NodeTrait;

class OrganizeApiController extends Controller
{
    private function toJstreeData($children, Request $request)
    {
        $result = [];
        foreach ($children as $child) {
            $json = (object)$this->show($child->id, $request);
            if (!empty($child->children->toArray())) $json->children = true;
            $result[] = $json;
        }
        return $result;
    }

    /**
     * @param Organize $organize
     * @return string
     */
    private function getOrganizeName($organize)
    {
        return $organize->name_en != '' ? $organize->name . ' (' . $organize->name_en . ')' : $organize->name;
    }

    /**
     * @param Organize $organize
     * @return string
     */
    private function getStatistics($organize)
    {
        $articles = $organize->articles()->withCount('cites')->get();
        $articles_count = $articles->count();
        $cites_count = $articles
            ->map(function ($article) {
                return $article->cites_count;
            })
            ->reduce(function ($total, $count) {
                return $total + $count;
            });
        return ' (' . $articles_count . ' bài báo, ' . $cites_count . ' trích dẫn)';
    }

    public function show($id, Request $request)
    {
        $statistics = $request->get('statistics', false);
        /**
         * @var Organize $organize
         */
        $organize = Organize::query()->findOrFail($id);
        $result = (object)[
            'id' => $organize->id,
            'parent' => isset($organize->parent) ? $organize->parent->id : '#',
            'text' => $this->getOrganizeName($organize) . ($statistics ? $this->getStatistics($organize) : ''),
//            'a_attr' => (object)['href' => route('api.organizes.show', ['id' => $organize->id])],
        ];
        return $result;
    }

    public function roots(Request $request)
    {
        $organizes = Organize::query()->whereNull('parent_id')->orderBy('updated_at', 'desc')->get();
        return $this->toJstreeData($organizes, $request);
    }

    public function children($id, Request $request)
    {
        /**
         * @var Organize $organize
         */
        $organize = Organize::query()->findOrFail($id);
        return $this->toJstreeData($organize->children, $request);
    }

    public function rename($id, Request $request)
    {
        /**
         * @var Organize $organize
         */
        $organize = Organize::query()->findOrFail($id);
        $organize->update($request->only('name'));
        return response($organize, 200);
    }

    public function destroy($id)
    {
        /**
         * @var Organize $organize
         */
        $organize = Organize::query()->findOrFail($id);
        $organize->delete();
        return response('success', 200);
    }

    public function store(Request $request)
    {
        $organize = Organize::create($request->only('name', 'parent_id'));
        return response($organize, 200);
    }

    public function move($id, Request $request)
    {
        /**
         * @var Organize $organize
         */
        $organize = Organize::query()->findOrFail($id);
        if ($request->get('parent_id') === '#') {
            $organize->makeRoot()->save();
        } else {
            $organize->update($request->only('parent_id'));
        }
        return response($organize, 200);
    }

    /**
     * Merge organizes
     * How to work: detach authors from organize_removed, and set attach to organize_saved
     */
    public function mergeOrganizes(Request $request)
    {
        $organize_saved = Organize::findOrFail($request->ids[0]);
        foreach ($request->ids as $key => $id) {
            if ($key != 0) {
                /**
                 * @var $organize_removed Organize
                 * @var $organize_saved Organize
                 */
                $organize_removed = Organize::findOrFail($request->ids[$key]);
                $authors = $organize_removed->authors;
                foreach ($authors as $author) {
                    $organize_saved->authors()->attach($author->id);
                }
                $organize_removed->authors()->detach(); //detach and remove
                $organize_saved->children()->saveMany($organize_removed->children);
                $organize_removed->delete();
            }
        }
        $opened = [];
        $organize = $organize_saved;
        while ($organize->parent_id) {
            $opened[] = $organize->parent_id;
            $organize = $organize->parent;
        }
        return response(compact('opened'), 200);
    }

    private function toSearchResult($organizes)
    {
        $all = Organize::pluck('id')->toArray();
        $opened = [];
        $result = [];
        foreach ($organizes as $organize) {
            /**
             * @var Organize $organize
             */
            $result[] = $organize->id;
            $opened = array_unique(array_merge($opened, $organize->getAncestors()->pluck('id')->toArray()));
        }
        $hidden = array_divide(array_diff($all, $opened, $result))[1];
        return response(compact('opened', 'result', 'hidden'), 200);
    }

    private function baseSearch($jstree = false)
    {
        $name = Input::get('q', '');
        if ($name != null && $name != "") {
            if (is_numeric($name)) {
                $organizes = Organize::find($name);
                if ($jstree) {
                    return $this->toSearchResult($organizes);
                } else {
                    $output = array($organizes);
                    return response()->json(['status' => 'success', 'data' => $output]);
                }
            } else {
                $elasticsearchservice = new ElasticsearchApiController();
                $esOrganizeSearch = new ESOrganizeSearch();
                $orgs_searched = $esOrganizeSearch->serviceSearchOrganizeFromElasticSearch(["name" => $name]);
                $organize_ids_imp = collect($orgs_searched["organizes_id"])->implode(',');
                $organizes = Organize::query();
                $organizes = $organizes->whereIn('organizes.id', $orgs_searched["organizes_id"])->orderByRaw("field(organizes.id, $organize_ids_imp)")->get();

                if ($organizes->count() == 0) {
                    return response()->json(['status' => 'empty', 'data' => []]);
                }
                foreach ($organizes as $organize) {
                    $name = $organize->name;
                    foreach ($organize->ancestors()->get() as $org_parent) {
                        $name .= ' - ' . $org_parent->name;
                    }
                    $organize->name = $name;
                }
//                $organizes = Organize::where('name', 'LIKE', "%$name%")->newQuery()->orWhere('name_en', 'LIKE', "%$name%")->get();
            }
        } else {
            // null query or init
            $organizes = Organize::take(30)->get();
        }
        if ($organizes->count() > 0) {
            if ($jstree) {
                return $this->toSearchResult($organizes);
            } else {
                return response()->json(['status' => 'success', 'data' => $organizes]);
            }
        } else {
            if ($jstree) {
                return $this->toSearchResult([]);
            } else {
                return response()->json(['status' => 'empty', 'data' => []]);
            }
        }
    }

    /**
     * /organizes/search/{name}
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        return $this->baseSearch();
    }

    /**
     * /organizes/search/{name}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jstreeSearch(Request $request)
    {
//        $name = $request->get('name');
        $name = $request->get('q');
        $elastic = new ElasticsearchApiController();
        $esOrganizesSearch = new ESOrganizeSearch();
        $elasticOutput = $esOrganizesSearch->serviceSearchOrganizeFromElasticSearch(compact('name'));
        if ($request->has('debug')) {
            dd($elasticOutput);
        }
        $organizes = collect($elasticOutput['organizes_id'])->map(function ($id) {
            return Organize::find($id);
        })->filter(function ($organize) {
            return $organize != null;
        });
        return $this->toSearchResult($organizes);
    }

    /**
     * Update glink
     * @param $id
     */
    public function update_gLink($id, Request $request)
    {
        $output = [];
        $org = Organize::findOrFail($id);
        $org->glink = $request->input('glink');
        $org->save();
        $output["status"] = "success";
        $output["data"] = $org;
        $fullname = $org->name;
        foreach ($org->ancestors()->get() as $org_parent) {
            $fullname .= ' - ' . $org_parent->name;
        }

        $output["data"]["full_name"] = $fullname;

        return $output;
    }
}