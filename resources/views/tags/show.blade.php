@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h4><strong>{{ $tag->name }} Tag</strong> <small>{{ $tag->posts()->count() }} Posts</small></h4>
            </div>
            <div class="col-md-2 col-md-offset-2">
                <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-primary pull-right btn-block"
                   style="margin-top:20px;">Edit Tag</a>
            </div>
            <div class="col-md-2 col-md-offset-2">
                {{Form::open(['route'=>['tags.destroy',$tag->id],'method'=>'DELETE'])}}
                    {{Form::submit('Delete',['class'=>'btn btn-danger btn-block','style'=>'margin-top:20px;'])}}
                {{Form::close()}}
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Post Title</th>
                        <th>Tags</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($tag->posts as $post)
                        <tr>
                            <th>{{ $post->id }}</th>
                            <td>{{ $post->title }}</td>
                            <td>@foreach ($post->tags as $tag)
                                    <span class="badge badge-secondary">{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td><a href="{{ route('posts.show', $post->id ) }}" class="btn btn-default btn-xs">View
                                    Post</a></td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
