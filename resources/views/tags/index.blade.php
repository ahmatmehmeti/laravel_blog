@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-10">
                <h1>Tags</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <table class="table" id="myTable">
                    <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Edit</th>
                    </thead>

                    <tbody>
                    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            $('#myTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: '{!! route('get.tags') !!}',
                                columns: [
                                    {data: 'id', name: 'id'},
                                    {data: 'name', name: 'name'},
                                    {data: 'created_at', name: 'created_at'},
                                    {data: 'edit', name: 'edit'},
                                ]
                            });
                        });
                    </script>

                    </tbody>
                </table>
            </div>
            <div class="col-md-4" style="position: relative;bottom:50px;">
                <div class="well">
                    <h2>New Tag</h2>
                    <form method="POST" action="{{route('tags.store')}}">
                        @csrf
                        Name:<br>
                        <label>
                            <input type="text" name="name" class="form-control">
                        </label><br>

                        <input type="submit" value="Create new Tag" class="btn btn-primary btn-block">
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
