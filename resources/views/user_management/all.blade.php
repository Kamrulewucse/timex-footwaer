@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Users
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('user.add') }}">Add User</a>

                    <hr>
                    <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->companyBranch->name ?? 'Admin' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('user.edit', ['user' => $user->id]) }}">Edit</a>
{{--                                    @if ($user->company_branch_id == 0)--}}
{{--                                        <a class="btn btn-info btn-sm" disabled>Edit</a>--}}
{{--                                    @else--}}
{{--                                        <a class="btn btn-info btn-sm" href="{{ route('user.edit', ['user' => $user->id]) }}">Edit</a>--}}
{{--                                    @endif--}}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
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
            $('#table').DataTable();
        })
    </script>
@endsection
