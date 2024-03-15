@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Customer
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
{{--{{ dd(Route::currentRouteName()) }}--}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('customer.add',['type'=>request()->get('type')]) }}">Add Customer</a>

                    <hr>
                    <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                        <thead>
                        <tr>
                            <th> ID </th>
                            <th> Name </th>
                            <th> Address </th>
                            <th> Mobile </th>
{{--                            <th> Branch </th>--}}
                            <th> Opening Due </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                        </thead>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('customer.datatable',['type'=>request()->get('type')]) }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'address', name: 'address'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    // {data: 'branch', name: 'branch'},
                    {data: 'opening_due', name: 'opening_due'},
                    {data: 'status', name: 'status', searchable:false},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection
