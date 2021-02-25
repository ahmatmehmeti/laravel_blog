<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Jobs\happyBirthdayJob;
use Illuminate\Http\Request;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Policies\CommentPolicy;
use Session;
use Yajra\DataTables\DataTables;


class CommentController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

    public function index()
    {
        $comments = Comment::orderBy('created_at', 'desc')->where('status', '=', 'pending')->paginate(5);

        return view('comments.index', compact('comments'));

        // returns all unapproved comments , that only admin can approve

    }

    public function store(StoreCommentRequest $request, $post_id)
    {

        $post = Post::find($post_id);

        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->status;
        $comment->user_id = Auth::user()->id;

        $comment->post()->associate($post);

        $comment->save();

        Session::flash('success', 'Comment created successfully');

        return redirect()->route('post.single', [$post->slug]);
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

        $comment = Comment::find($id);

        $this->authorize('view', $comment);

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCommentRequest $request, $id)
    {
        $comment = Comment::find($id);

        $comment->comment = $request->comment;
        $comment->status = 'pending';

        $comment->save();

        Session::flash('success', 'Comment edited successfully');

        return redirect()->route('post.single', $comment->post->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        Session::flash('success', 'Comment deleted successfully');

        return redirect()->route('post.single', $comment->post->slug);
    }


    public function approve($id)
    {
        $comment = Comment::find($id);

        $comment->status = 'approved';

        $comment->save();

        Session::flash('success', 'Comment approved successfully');

        return redirect()->route('comments.index');

        // approve comments only by admin
    }

    public function disapprove($id)
    {
        $comment = Comment::find($id);

        $comment->status = 'disapproved';

        $comment->save();

        Session::flash('success', 'Comment disapproved successfully');

        return redirect()->route('comments.index');

        // disapprove comments only by admin
    }

    public function getComments()
    {
        return Datatables::of(Comment::query()->where('status', '=', 'pending'))
            ->setRowClass(function ($comment) {
                return $comment->id % 2 == 0 ? 'alert-success' : 'alert-warning';
            })
            ->editColumn('created_at', function (Comment $comment) {
                return $comment->created_at->diffForHumans();
            })
            ->addColumn('post', function (Comment $comment) {
                return "<a href='post/{$comment->post->slug} '>{$comment->post->slug}";
            })
            ->addColumn('approve', function (Comment $comment) {
                return "<a href='comments/approve/$comment->id'>approve";
            })
            ->addColumn('disapprove', function (Comment $comment) {
                return "<a href='comments/disapprove/$comment->id'>disapprove";
            })
            ->rawColumns(['approve', 'disapprove', 'post'])
            ->toJson();

        // datatable of comments in admin panel

    }

}
