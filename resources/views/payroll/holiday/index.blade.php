@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

@endsection

@section('title')
    Holiday
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <a href="{{route('payroll.holiday_add')}}" class="btn btn-primary">Add Holiday</a><br>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="table" style="width: 100% !important">
                        <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Year</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
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
                ajax: '{{ route('holiday.datatable') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'year', name: 'year'},
                    {data: 'from', name: 'from'},
                    {data: 'to', name: 'to'},
                    {data: 'total_days', name: 'total_days'},
                    {data: 'action', name: 'action'},
                ],
                "order": [[ 2, "desc" ]],
                'columnDefs': [
                    {
                        "targets": [0,1,2,3,4,5],
                        "className": "text-center",
                    }],
            });

        });




    </script>

@endsection
