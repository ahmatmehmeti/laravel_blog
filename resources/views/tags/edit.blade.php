@extends('layouts.app')

@section('content')

    <div class="container">

        <form method="POST" action="{{route('tags.update',$tag->id)}}">
            @csrf
            @method('PUT')
            Title:<br>
            <input type="text" name="name" class="form-control" value="{{$tag->name}}"><br>

            <input type="submit" value="Save Changes" class="btn btn-success">
        </form>
        <form method="POST" action="{{route('tags.destroy',$tag->id)}}">
            @csrf
            @method('DELETE')

            <input type="submit" value="Delete" class="btn btn-lg btn-danger">
        </form>
    </div>
@endsection
