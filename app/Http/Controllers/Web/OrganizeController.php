<?php

namespace App\Http\Controllers\Web;

use App\DataTables\OrganizeStatisticsDataTable;
use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Models\Article;
use App\Models\HistoryOrganization;
use App\Models\Organize;
use App\Models\Journal;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OrganizeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');

    }

    public function index()
    {
        $organizes = Organize::withCount('authors')->orderBy('authors_count', 'desc')->paginate(config('settings.per_page'));
        $journals = Journal::withCount('articles')->orderBy('articles_count', 'desc')->limit(5)->get();
        $years = Article::groupBy('year')->orderBy('year', 'desc')->pluck('year');
        return view('vci_views.organize.index', compact('organizes', 'journals', 'years'));
    }

    public function tree(Request $request)
    {
        $selected = $request->get('selected');
        $opened = [];
        if ($selected) {
            /**
             * @var Organize $organize
             */
            $organize = Organize::query()->find($selected);
            if ($organize) {
                while ($organize->parent_id) {
                    $opened[] = $organize->parent_id;
                    $organize = $organize->parent;
                }
            }
        }
        return view('vci_views.organize.tree', compact('selected', 'opened'));
    }

    public function create()
    {
        return view('vci_views.organize.create');
    }

    private function getFullName($organize)
    {
        $ancestors = $organize->ancestors()->get();
        $fullName = $organize->name;
        foreach ($ancestors as $an) {
            $fullName = $fullName . " - " . $an->name;
        }
        return $fullName;
    }

    private function getFullNameByID($id)
    {
        $organize = Organize::find($id);
        $ancestors = $organize->ancestors()->get();
        $fullName = $organize->name;
        foreach ($ancestors as $an) {
            $fullName = $fullName . " - " . $an->name;
        }
        return $fullName;
    }

    /**
     * Change all organizations to the standard organization
     * Move all authors and children from an organization to the standard org
     * @param Request $request
     */
    public function merge(Request $request)
    {
        $standardOrgID = $request->input('sto');
        /**
         * @var Organize $standardOrg
         */
        $orgIDs = $request->input('oids');
        $standardOrg = Organize::find($standardOrgID);
        if ($standardOrg != null) {
            foreach ($orgIDs as $orgID) {
                /**
                 * @var Organize $org
                 */
                $org = Organize::find(intval($orgID));
                if ($org != null) {
                    //merge authors
                    $authors = $org->authors;
                    $org->authors()->detach($authors);
                    $standardOrg->authors()->attach($authors);
                    //merge children
                    $children = $org->descendants();
                    if (sizeof($children) > 0) {
                        foreach ($children as $child) {
                            $child->setParentId($standardOrg->id);
                            $child->save();
                        }
                    }

                    $this->saveHistory($org, $standardOrg, $authors, $children);
                }
            }
        }
        return redirect()->route('org.list');
    }

    /**
     * View all organization (full name)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllView(Request $request)
    {
        $offset = intval($request->get('offset'));
        $orgs = Organize::all()->every(1, $offset);
        $orgsView = array();
        foreach ($orgs as $org) {
            $numOfAuthors = $org->authors()->count();
            if($numOfAuthors > 0)
                $orgsView[$org->id] = $this->getFullName($org) . "($numOfAuthors)";
        }
        return view('organizations', compact('orgsView'));

    }

    /**
     * Find all similar orgs proposed
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSimilarOrgsProposed(Request $request)
    {
        $orgID = intval($request->get('oid'));
        $org = Organize::find($orgID);
        $numOfAuthors = $org->authors()->count();
        $orgName = $this->getFullName($org)." ($numOfAuthors)";
        $orgId = $org->id;
        $similarOrgsProposed = array();
        if ($org != null && ($org->glink) != null && strlen($org->glink) > 0) {
            $similarOrgsProposed = $this->findSimilarOrgs($org);
        }
        return view('similarorgs', compact('similarOrgsProposed', 'orgName', 'orgId'));
    }

    /**
     * Find all similar orgs by comparing google link
     * @param $org
     * @return array
     */
    private function findSimilarOrgs($org)
    {
        $organizes = Organize::all();

        $similarOrgsProposed = array();
        $fname = $this->getFullName($org);
        foreach ($organizes as $otherorg) {
            $numOfAuthors = $otherorg->authors()->count();
            if ($numOfAuthors > 0) {
                $otherfname = $this->getFullName($otherorg);
                if ($this->isSimilarGlink($org->glink, $otherorg->glink) || $this->isSimilarFullName($fname, $otherfname)) {
                    $similarOrgsProposed[$otherorg->id] = $otherfname . "($numOfAuthors)";
                }
            }
        }
        return $similarOrgsProposed;
    }

    /**
     * @param $glink
     * @param $glink1
     * @return bool
     */
    private function isSimilarGlink($glink, $glink1)
    {
        if($glink == null || $glink1==null || strlen($glink1) == 0 || strlen($glink) == 0)
            return false;
        similar_text($glink, $glink1, $sim);
        if ($sim > 90) {
            return true;
        }
        return false;
    }

    private function isSimilarFullName($name1, $name2)
    {
        similar_text(strtolower($name1), strtolower($name2), $sim);
        if ($sim > 90) {
            return true;
        }
        return false;
    }

    /**
     * Save a merging action to database
     * @param Organize $org
     * @param Organize $standardOrg
     * @param $authors
     */
    private function saveHistory($org, $standardOrg, $authors, $children)
    {
        $history = new HistoryOrganization();
        $history->org_from = $org->id;
        $history->org_to = $standardOrg->id;
        $history->description = "<td>".$this->getFullNameByID($history->org_from)."</td><td>".$this->getFullNameByID($history->org_to)."</td>";

        $action = "";
        //Get authors
        foreach ($authors as $author) {
            $action = $action . "-" . $author->id;
        }

        $action = $action . "|";
        //get children
        foreach ($children as $child) {
            $action = $action . "-" . $child->id;
        }

        $history->action = $action;
        $history->save();
    }

    /**
     * Undo the previous action
     * @param Request $request
     */
    public function revert(Request $request)
    {
        $historyid = $request->input('hid');
        $history = HistoryOrganization::find($historyid);

        $authors = $this->getAuthorsHistory($history);
        $children = $this->getChildrenHistory($history);
        /***
         * @var Organize $org_to
         */
        $org_to = Organize::find(intval($history->org_to));
        /***
         * @var Organize $org_from
         */
        $org_from = Organize::find(intval($history->org_from));

        if ($org_to != null && $org_from != null) {
            //revert authors
            $org_to->authors()->detach($authors);
            $org_from->authors()->attach($authors);
            //revert children
            if ($children != null && sizeof($children) > 0) {
                foreach ($children as $child) {
                    /***
                     * @var Organize $child
                     */
                    $child->setParentId($org_from->id);
                    $child->save();
                }
            }
        }


        $history->delete();
        return redirect()->route('org.history');
    }

    /***
     * View all historial records
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history()
    {
        $records = HistoryOrganization::orderBy('id', 'DESC')->get();
        return view('historyrecords', compact('records'));

    }

    /**
     * Extract children from a historial record
     * @param $history
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    private function getChildrenHistory($history)
    {
        $organizations = array();
        $res = explode("|", $history->action);
        $childrenIds = array();
        if (sizeof($res) == 2) {
            if (strlen($res[1]) > 0) {
                $childrenText = explode("-", $res[1]);
                if (sizeof($childrenText) > 0) {
                    foreach ($childrenText as $childText) {
                        if (strlen(trim($childText)) > 0)
                            $childrenIds[] = intval($childText);
                    }
                }
            }
        }
        if (sizeof($childrenIds) > 0)
            $organizations = Organize::findMany($childrenIds);
        return $organizations;
    }

    /**
     * Extract all authors from a historial record
     * @param $history
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    private function getAuthorsHistory($history)
    {
        $authors = array();
        $childrenIds = array();
        $res = explode("|", $history->action);
        if (sizeof($res) == 2) {
            $authorsText = explode("-", $res[0]);
            if (sizeof($authorsText) > 0) {
                foreach ($authorsText as $authorText) {
                    if (strlen(trim($authorText)) > 0)
                        $childrenIds[] = intval($authorText);
                }
            }
        }
        if (sizeof($childrenIds) > 0)
            $authors = Author::findMany($childrenIds);
        return $authors;
    }
}
