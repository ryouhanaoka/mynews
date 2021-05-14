<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\HTML;
use App\News;
use App\Place;

class NewsController extends Controller
{
    public function index(Request $request)
    {
       $posts = News::with('place')->orderBy('updated_at','DESC')->get();
        // $posts = News::all()->sortByDesc('updated_at');
        
        return view('news.index', ['posts' => $posts]);
    }
}
