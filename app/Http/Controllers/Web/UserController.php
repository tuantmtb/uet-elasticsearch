<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;

class UserController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except' => 'getAuth']);
		$this->stt = 1;
		if(isset($_GET['page'])) {
			$this->stt =  (int) config('settings.per_page') * ((int) $_GET['page'] - 1)  + 1;
		}
	}
	public function show($id) {
		$user  = User::with(['articles'=>function($query) use ($id) {$query->where('user_id', $id);}])->where('id', $id)->first();
		$articles = $user->articles->load('cites', 'authors', 'users', 'journal');
		$articles = new \Illuminate\Pagination\LengthAwarePaginator($articles, count($articles), config('settings.per_page'));
		return view('vci_views.users.show')->with(['user' => $user, 'articles' => $articles, 'stt' => $this->stt]);
	}
}
