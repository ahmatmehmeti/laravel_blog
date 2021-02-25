@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Edit Category</h1>

                <form method="POST" action="{{route('categories.update',$category->id)}}">
                    @csrf
                    @method('PUT')
                    Title:<br>
                    <input type="text" name="name" value="{{$category->name}}" class="form-control"><br>

                    <input type="submit" value="Save Changes" class="btn btn-success">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="POST" action="{{route('categories.destroy',$category->id)}}">
                    @csrf
                    @method('DELETE')

                    <input type="submit" value="Delete" class="btn btn-lg btn-danger">
                </form>
            </div>
        </div>
    </div>
@endsection
