<?php

namespace App\Http\Controllers;

use App\Jobs\happyBirthdayJob;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\Datatables\Datatables;
use Purifier;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(['auth' => 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->where('status', '=', 'approved')->paginate(6);

        $categories = Category::all();


        return view('home/index', compact('posts', 'categories'));
    }

}
