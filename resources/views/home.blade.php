@extends('layouts.app')
@section('title','')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
      .info-box-content{
            font-size: 0.8rem !important;
            font-weight:1000 !important;
        }
        img{
            border-radius: 25px;
            margin-top: -20px;
            margin-bottom: 26px;
        }
        [class*=sidebar-dark] .brand-link, [class*=sidebar-dark] .brand-link .pushmenu {
          height: 45px !important;
          padding-top: 16px !important;
        }
        /*.payment{*/
        /*    margin-bottom: 25px;*/
        /*    margin-top: -26px;*/
        /*    float: right;*/
        /*    margin-right: 41px;*/
        /*}*/
    </style>
@endsection

@section('content')
    <div class="row">
        <img style="width: 100%;height: 100vh;" src="{{ asset('img/home_bg.jpg') }}">
    </div>
@endsection

@section('script')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable({
                ordering: false
            });

            $('#p_table').DataTable({ordering: false});
        } );
    </script>

@endsection
