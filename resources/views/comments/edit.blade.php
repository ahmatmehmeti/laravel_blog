@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Edit Comment</h1>

                <form method="POST" action="{{route('comments.update',$comment->id)}}">
                    @csrf
                    @method('PUT')
                    Comment:<br>
                    <textarea type="text" rows="10" cols="100" name="comment">{{$comment->comment}}</textarea><br>

                    <input type="submit" value="Edit" class="btn btn-lg btn-block btn-success">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="POST" action="{{route('comments.destroy',$comment->id)}}">
                    @csrf
                    @method('DELETE')

                    <input type="submit" value="Delete" class="btn btn-lg btn-block btn-danger">
                </form>
            </div>
        </div>
    </div>
@endsection
