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
                        <label>Url:</label>
                        <p><a href="{{ route('post.single', $post->slug) }}">{{ route('post.single', $post->slug) }}</a>
                        </p>
                    </dl>

                    <dl class="dl-horizontal">
                        <label>Category:</label>
                        <p>{{ $post->category->name }}</p>
                    </dl>

                    <dl class="dl-horizontal">
                        <label>Created At:</label>
                        <p>{{ date('M j, Y h:ia', strtotime($post->created_at)) }}</p>
                    </dl>

                    <dl class="dl-horizontal">
                        <label>Last Updated:</label>
                        <p>{{ date('M j, Y h:ia', strtotime($post->updated_at)) }}</p>
                    </dl>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Html::linkRoute('posts.index', '<< See All Posts', array(), ['class' => 'btn btn-default btn-block btn-h1-spacing']) }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <hr>
        <div class="col-md-8 col-md-offset-2">

            <div id="backend-comments" style="margin-top: 50px;">
                <h3>Comments <small>{{ $post->comments->count() }} total</small></h3>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Comment</th>
                        <th width="70px"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($post->comments as $comment)
                        @if($comment->status == 'approved')
                        <tr>
                            <td>{{$comment->user->name}}</td>
                            <td>{{ $comment->comment }}</td>
                            <td>

                                @if($comment->user->id == Auth::user()->id || Auth::user()->role == 'admin')
                                    <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-xs btn-primary">Edit<span
                                            class="glyphicon glyphicon-pencil"></span></a>
                                @endif

                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div id="comment-form" class="col-md-8 col-md-offset-2" style="margin-top: 50px;">
                {{Form::open(['route'=>['comments.store',$post->id],'method'=>'POST'])}}

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::label('comment', "Comment:") }}
                        {{ Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '5']) }}

                        {{ Form::submit('Add Comment', ['class' => 'btn btn-success btn-block', 'style' => 'margin-top:15px;']) }}
                    </div>
                </div>

                {{ Form::close() }}
            </div>

        </div>
@endsection
