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
}
