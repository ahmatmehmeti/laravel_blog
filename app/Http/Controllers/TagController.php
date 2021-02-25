<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use Session;
use Illuminate\Http\Request;
use App\Models\Tag;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return view('tags.index',compact('tags'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request)
    {

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->save();

        Session::flash('success', 'Tag created successfully');

        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);

        return view('tags.show',compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);

        return view('tags.edit',compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTagRequest $request, $id)
    {
        $tag = Tag::find($id);

        $tag->name = $request->name;

        $tag->save();

        Session::flash('success', 'Tag updated successfully');

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->posts()->detach();

        $tag->delete();

        Session::flash('success', 'Tag deleted successfully');

        return redirect()->route('tags.index');
    }

    public function getTags()
    {
        return Datatables::of(Tag::query())
            ->setRowClass(function ($tag) {
                return $tag->id % 2 == 0 ? 'alert-success' : 'alert-warning';
            })
            ->editColumn('created_at', function (Tag $tag) {
                return $tag->created_at->diffForHumans();
            })
            ->addColumn('edit', function (Tag $tag) {
                return "<a href='tags/$tag->id/edit'>edit";
            })
            ->rawColumns(['edit'])
            ->toJson();


        // datatable of tags in admin panel
    }
}
