@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-10">
                <h1>Pending Posts</h1>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="myTable">
                    <thead>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Approve</th>
                    <th>Disapprove</th>
                    <th></th>
                    </thead>

                    <tbody>

                    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            $('#myTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: '{!! route('get.posts') !!}',
                                columns: [
                                    {data: 'id', name: 'id'},
                                    {data: 'title', name: 'title'},
                                    {
                                        "name": "image",
                                        "data": "image",
                                        "render": function (data, type, full, meta) {
                                            return "<img src=\"{{asset('images/thumb')}}" + data + "\" height=\"160\"/>";
                                        },
                                        "title": "Image",
                                        "orderable": true,
                                        "searchable": true,
                                    },

                                    {data: 'body', name: 'body'},
                                    {data: 'created_at', name: 'created_at'},
                                    {data: 'approve', name: 'approve'},
                                    {data: 'disapprove', name: 'disapprove'}
                                ]
                            });
                        });
                    </script>


                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
