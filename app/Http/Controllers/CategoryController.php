<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
//        $this->middleware('auth');//only logged in
    }

    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(5);

        return view('categories.index', compact('categories'));

        //return all categories
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {

        $category = new Category();

        $category->name = $request->name;
        $category->save();

        Session::flash('success', 'Category created successfully');

        return redirect()->route('categories.index');

        //only admin can store new category
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        return view('categories.edit', compact('category'));

        //shows the category that is clicked to edit, (admin only).
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        $category->name = $request->name;

        $category->save();

        Session::flash('success', 'Updated created successfully');

        return redirect(route('categories.index'));

        // update te category that comes of form (admin only).
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        $category->delete();

        Session::flash('success', 'Category deleted successfully');

        return redirect(route('categories.index'));

        //category delete (admin only).
    }

    public function categoriesName($name)
    {
        $categories = Category::all();

        $id = DB::table('categories')->where('name', $name)->pluck('id');

        $posts = Post::orderBy('created_at', 'desc')
            ->where([['category_id', '=', $id], ['status', '=', 'approved']])->paginate(3);

        return view('home/index', compact('posts', 'categories'));

        //shows posts of the category that is clicked
    }

    public function getCategories()
    {
        return Datatables::of(Category::query())
            ->setRowClass(function ($category) {
                return $category->id % 2 == 0 ? 'alert-success' : 'alert-warning';
            })
            ->editColumn('created_at', function (Category $category) {
                return $category->created_at->diffForHumans();
            })
            ->addColumn('edit', function (Category $category) {
                return "<a href='categories/$category->id/edit'>edit";
            })
            ->rawColumns(['edit'])
            ->toJson();

        // datatable of categories in admin panel
    }
}
