@extends('layouts.app')

@section('content')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector: 'textarea'});</script>

    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Add New Post</h3>
                </div>
                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data" data-parsley-validate
                          action="{{route('posts.store')}}  ">
                        @csrf
                        Title:<br>
                        <input type="text" class="form-control" name="title">
                        <br>
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        Slug:<br>
                        <input type="text" class="form-control" name="slug">
                        <br>
                        @error('slug')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        Categories:<br>
                        <select class="form-control" name="category_id">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}"> {{$category->name}}</option>
                            @endforeach
                        </select><br>
                        Tags:<br>
                        <select multiple="multiple" class="form-control multiple" name="tags[]">
                            @foreach($tags as $tag)
                                <option value="{{$tag->id}}"> {{$tag->name}}</option>
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
                            <textarea name="body" class="form-control"></textarea>
                        </label>
                        @error('body')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <input type="submit" value="Submit" class="btn btn-success btn-lg btn-block">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
