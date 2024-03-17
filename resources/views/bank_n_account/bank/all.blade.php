@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    ব্যাংক
@endsection

@section('content')
{{--    @if(Session::has('message'))--}}
{{--        <div class="alert alert-success alert-dismissible">--}}
{{--            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}
{{--            {{ Session::get('message') }}--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('bank.add') }}">ব্যাংক যুক্ত করুন</a>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>নাম</th>
                            <th>স্ট্যাটাস</th>
                            <th>একশন</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($banks as $bank)
                            <tr>
                                <td>{{ $bank->name }}</td>
                                <td>
                                    @if ($bank->status == 1)
                                        <span class="badge badge-success">সক্রিয়</span>
                                    @else
                                        <span class="badge badge-danger">নিষ্ক্রিয়</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('bank.edit', ['bank' => $bank->id]) }}">এডিট</a>
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
