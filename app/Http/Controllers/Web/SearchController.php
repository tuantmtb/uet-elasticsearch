<?php

namespace App\Http\Controllers\Web;


use App\Models\Organize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SearchController extends Controller
{
    public function view()
    {
        return view('pages.search.search');
    }

    public function organizes_advance(Request $request)
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
        return view('vci_views.search.organizes_advance', compact('selected', 'opened'));
    }
}
