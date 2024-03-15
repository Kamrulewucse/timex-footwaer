@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Branch Cash
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('branch_cash_add') }}">Add Branch</a>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Branch Name</th>
                            <th>Amount</th>
                            <th>Opening Balance</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($branchCashes as $branchCash)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $branchCash->companyBranch->name ?? '' }}</td>
                                <td>{{ number_format($branchCash->amount) }}</td>
                                <td>{{ number_format($branchCash->opening_balance) }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('branch_cash_edit', ['branchCash' => $branchCash->id]) }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#table').DataTable();
        })
    </script>
@endsection
