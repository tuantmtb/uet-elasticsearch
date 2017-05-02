<?php

namespace App\Http\Controllers\Web;

use App\Models\Article;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Organize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Storage;


class FilmController extends Controller
{

    public function test()
    {
        $films = DB::select(DB::raw("
          SELECT title, imdb_index, production_year, info, movie_id 
          from film_index
            limit 10
         "));

        foreach ($films as $film) {
            Storage::disk('local')->put($film->movie_id . '.json', json_encode($film));
        }

        return "done";
    }

}
