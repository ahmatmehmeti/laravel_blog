@extends('layouts.app')

@section('content')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector:'textarea'});</script>

    <div class="container">
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector:'textarea'});</script>

        <div class="row">
            <div class="col-md-8">
                <form method="POST" enctype="multipart/form-data" data-parsley-validate
                      action="{{route('posts.update',$post->id)}}">
                    @csrf
                    @method('PUT')
                    Title:<br>
                    <input type="text" class="form-control" name="title" value="{{$post->title}}">
                    <br>
                    @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    Slug:<br>
                    <input type="text" class="form-control" name="slug" value="{{$post->slug}}">
                    <br>
                    @error('slug')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    Categories:<br>
                    <select class="form-control" name="category_id">
                        @foreach($categories as $category)
                            @if($category->id == $post->category_id)
                                <option value="{{ $category->id }}" selected> {{ $category->name }}</option>
                            @else
                                <option value="{{ $category->id }}"> {{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select><br>
                    Tags:<br>
                    <select multiple="multiple" class="form-control multiple" name="tags[]">
                        @foreach($tags as $tag)
                            @if(in_array($tag->id,$tags_ids))
                                <option value="{{ $tag->id }}" selected> {{ $tag->name }}</option>
                            @else
                                <option value="{{ $tag->id }}"> {{ $tag->name }}</option>
                            @endif
                        @endforeach
                    </select><br>
                    @error('tags')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    Image:<br>
                    <label>
                        <input name="image" type="file" class="form-control-file" id="image">
                    </label><br>
                    @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    Description:<br>
                    <label>
                        <textarea name="body" class="form-control">{{$post->body}}</textarea>
                    </label>
                    @error('body')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <input type="submit" value="Submit" class="btn btn-success btn-lg btn-block">


                <div class="col-md-4">
                    <div class="well">
                        <dl class="dl-horizontal">
                            <dt>Create At:</dt>
                            <dd>{{ date('M j, Y h:ia', strtotime($post->created_at)) }}</dd>
                        </dl>
                        {!! Form::close() !!}
                        <dl class="dl-horizontal">
                            <dt>Last Updated:</dt>
                            <dd>{{ date('M j, Y h:ia', strtotime($post->updated_at)) }}</dd>
                        </dl>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
