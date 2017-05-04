<?php

namespace App\Http\Controllers\Web;

use App\Models\Organize;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SoapBox\Formatter\Formatter;
use App\Models\Journal;
use App\Models\Article;
use App\Models\Author;

class HomeController extends Controller
{

    public function main() {
        return redirect()->route('search');
    }
}
