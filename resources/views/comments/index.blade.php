@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-10">
                <h1>Pending Comments</h1>
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
                    <th>Comment</th>
                    <th>Post</th>
                    <th>Created At</th>
                    <th>Approved</th>
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
                                ajax: '{!! route('get.comments') !!}',
                                columns: [
                                    {data: 'id', name: 'id'},
                                    {data: 'comment', name: 'comment'},
                                    {data: 'post', name: 'post'},
                                    {data: 'created_at', name: 'created_at'},
                                    {data: 'approve', name: 'approve'},
                                    {data: 'disapprove', name: 'disapprove'},
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
