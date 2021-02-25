@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>{{ $post->title }}</h1>
                <img src="{{asset('images/' . $post->image)}}" style="width: 450px;height: 400px;">
                <p class="lead">{!! $post->body!!}</p>
                <hr>
                <p>Category: {{$post->category->name}}</p>
                <hr>
                Tags:
                @foreach($post->tags as $tag)
                    <span class="badge badge-secondary">{{$tag->name}}</span>
                @endforeach
            </div>

            <div class="col-md-4">
                <div class="well">
                    <dl class="dl-horizontal">
                        <dt>Slug:</dt>
                        <dd><a href="{{route('post.single',$post->slug)}} "> {{route('post.single',$post->slug)}}</dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Create At:</dt>
                        <dd>{{ date('M j, Y h:ia', strtotime($post->created_at)) }}</dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Last Updated:</dt>
                        <dd>{{ date('M j, Y h:ia', strtotime($post->updated_at)) }}</dd>
                    </dl>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <a class="btn btn-primary btn-block" href="{{route('posts.edit',$post->id)}}"> Edit</a>
                        </div>
                        <div class="col-sm-6">
                            <form method="POST" action="{{route('posts.destroy',$post->id)}}">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-primary btn-danger">
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                @foreach($post->comments as $comment)
                    <p style="position: relative;float: right;">{{ date('M j, Y h:ia', strtotime($comment->created_at)) }}</p>
                    <p><strong>Edmond: </strong>{{$comment->comment}}</p>

                    <hr>
                @endforeach
            </div>
        </div>
    </div>
@endsection
