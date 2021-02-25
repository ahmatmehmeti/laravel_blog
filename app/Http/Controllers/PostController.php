<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Purifier;
use Image;
use App\Policies;
use Session;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5)->where('user_id', '==', auth()->user()->id);

        return view('posts.index', compact('posts'));

        //return all posts of logged user
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('posts/create', compact(
            'categories',
            'tags'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StorePostRequest $request)
    {
        $post = new Post();

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->user_id = Auth::user()->id;

        $this->authorize('view', $post);

        $post->body = Purifier::clean($request->body);

        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();

        $location = public_path('images/' . $filename);
        $locationThumb = public_path('images/thumb' . $filename);

        Image::make($image)->resize(300, 300)->save($location);

        Image::make($image)->resize(450, 400)->save($locationThumb);

        $this->compressImage($location, $location, 50);

        $post->image = $filename;


        $post->save();

        $post->tags()->sync($request->tags, false);

        Session::flash('success', 'Post created successfully');

        activity()
            ->causedBy(Auth::user()->id)
            ->performedOn($post)
            ->withProperties(['User' => Auth::user()->name, 'post' => $post->id])
            ->log('post has been created');

        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $post = Post::find($id);

        $this->authorize('view', $post);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {

        $post = Post::with('tags')->find($id);

        $this->authorize('view', $post);

        foreach ($post->tags as $tag) {
            $tags_ids[] = $tag->id;
        }


        $categories = Category::select('id', 'name')->get();
        $tags = Tag::select('id', 'name')->get();

        return view('posts.edit', compact(
            'post',
            'categories',
            'tags',
            'tags_ids'
        ));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $slug = $request->slug;
        $post = Post::find($id);

        $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => ($slug == $post->slug ? '' : 'required|alpha_dash|min:5|max:255|unique:posts,slug'),
            'category_id' => 'required|numeric',
            'body' => 'required',
            'tags' => 'required',
        ));

        $post = Post::find($id);
        $this->authorize('view', $post);

        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->category_id = $request->input('category_id');
        $post->body = Purifier::clean($request->input('body'));

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $filename = time() . '.' . $image->getClientOriginalExtension();

            $location = public_path('images/' . $filename);
            $locationThumb = public_path('images/thumb' . $filename);

            Image::make($image)->resize(300, 300)->save($location);

            Image::make($image)->resize(450, 400)->save($locationThumb);

            $this->compressImage($location, $location, 50);

            $post->image = $filename;
        }

        $post->save();

        $post->tags()->sync($request->tags);


        Session::flash('success', 'Post edited successfully');

        activity()
            ->causedBy(Auth::user()->id)
            ->performedOn($post)
            ->withProperties(['User' => Auth::user()->name, 'post' => $post->id])
            ->log('post has been edited');

        return redirect()->route('posts.show', $post->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $this->authorize('view', $post);

        $post->tags()->detach();


        $post->delete();

        Session::flash('success', 'Post deleted successfully');

        activity()
            ->causedBy(Auth::user()->id)
            ->performedOn($post)
            ->withProperties(['User' => Auth::user()->name, 'post' => $post->id])
            ->log('post has been deleted');

        return redirect()->route('posts.index');

    }

    public function getSingle($slug)
    {
        $post = Post::with(['comments' => function ($query) {
            $query->where('status', '=', 'approved');
        }])->where('slug', '=', $slug)->first();

        return view('posts.single', compact('post'));
    }

    public function unApproved()
    {

        $posts = Post::orderBy('created_at', 'desc')->where('status', '=', 'pending')->paginate(5);

        return view('posts.unapproved', compact('posts'));
    }

    public function approve($id)
    {
        $post = Post::find($id);

        $post->status = 'approved';

        $post->save();

        Session::flash('success', 'Post approved successfully');

        return redirect()->route('posts.unapproved');
    }

    public function disapprove($id)
    {
        $post = Post::find($id);

        $post->status = 'disapproved';

        $post->save();

        Session::flash('success', 'Post disapproved successfully');

        return redirect()->route('posts.unapproved');
    }

    public function compressImage($source, $destination, $quality)
    {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);

    }

    public function getPosts()
    {
        return Datatables::of(Post::query()->where('status', '=', 'pending'))
            ->setRowClass(function ($post) {
                return $post->id % 2 == 0 ? 'alert-success' : 'alert-warning';
            })
            ->editColumn('created_at', function (Post $post) {
                return $post->created_at->diffForHumans();
            })
            ->addColumn('approve', function (Post $post) {
                return "<a href='../post/approve/$post->id'>approve";
            })
            ->addColumn('disapprove', function (Post $post) {
                return "<a href='../post/disapprove/$post->id'>disapprove";
            })
            ->rawColumns(['body', 'approve', 'disapprove'])
            ->toJson();

        // datatable of posts in admin panel
    }

    public function search(Request $request)
    {
        $from = $request->Fromdate;
        $to = $request->Todate;
        $title = $request->title;
        $category_id = $request->category_id;

        $posts = DB::table('posts')
            ->when($category_id, function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })->when($title, function ($query) use ($title) {
                $query->where('title', 'LIKE', '%' . $title . '%');
            })->when($from, function ($query) use ($from) {
                $query->where('created_at', '>', $from);
            })->when($to, function ($query) use ($to) {
                $query->where('created_at', '<', $to);
            })->paginate(1);

        $categories = Category::all();

        return view('home/index', compact('posts', 'categories'));

    }
}
