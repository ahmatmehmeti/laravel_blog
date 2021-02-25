@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <div class="container">
        <div class="row col-md-11"
             style="position: relative;color:slategrey; left: 12%;margin-bottom: 20px;margin-top: -1px;">
            <form class="form-inline" method="GET" action="{{route('search')}}">
                <div class="form-group" style="margin-right: 10px;">
                    <input type="text" class="form-control" name="title" placeholder="Search">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="pwd">From: </label>
                    <input type="date" class="form-control" name="Fromdate" id="date" placeholder="Search">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="pwd">To: </label>
                    <input type="date" class="form-control" name="Todate" id="date" placeholder="Search">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="pwd">Category: </label>
                    <select class="form-control" name="category_id">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}"> {{$category->name}}</option>
                        @endforeach
                    </select><br>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
        <div class="row">
            <div class='col-md-2'>
                <div class="list-group">
                    <a href="{{ url('home')}}" class="list-group-item list-group-item-action active">
                        <i class="fa fa-home"></i> Categories
                    </a>


                    @foreach($categories as $category)

                        <a href="{{ url('categories/'.$category->name) }}"
                           class="list-group-item list-group-item-action">
                            <i class="fa fa-home"></i> {{$category->name}}
                        </a>
                    @endforeach
                </div>

            </div>


            <div class="row col-md-10">
                @foreach($posts as $post)
                    <div id="myTable" class='col-md-4' style="margin-bottom: 20px;">
                        <div class='panel panel-info'>
                            <div class='panel-heading'><h4>{{$post->title}}</h4></div>
                            <div class='panel-body'>
                                <img src="{{asset('images/thumb' . $post->image)}}" style="width: 250px;height: 300px">
                                <p>{{ substr(strip_tags($post->body), 0, 100) }}{{ strlen(strip_tags($post->body)) > 100 ? "..." : "" }}</p>
                                <a href="{{ url('post/'.$post->slug) }}" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div style="position: relative;left: 50%;">
                {!! $posts->links(); !!}
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $('#date').datepicker();
        });
    </script>




@endsection

