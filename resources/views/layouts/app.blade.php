<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
{{--    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">--}}
<!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/toastr/toastr.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/backend/dist/css/adminlte.min.css') }}">
    <style>
        @media (min-width: 768px){
            .col-form-label{
                text-align: right;
            }
        }

        .form-group.has-error label {
            color: #378903;
        }
        .form-group.has-error .form-control, .form-group.has-error .input-group-addon {
            border-color: #378903;
            box-shadow: none;
        }
        .form-group.has-error .help-block {
            color: red;
        }
        .input-group{
            width: 98% !important;
        }
        .help-block {
            display: block;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .toast{
            min-width: 300px;
        }
        .select2{
            width: 98% !important;
        }
        .form-group.has-error .select2-container span.selection span.select2-selection.select2-selection--single {
            border-color: #378903;
            box-shadow: none;
        }
        .input-group.date-time.has-error .form-control {
            border-color: #378903;
            box-shadow: none;
        }

        .input-group.date-time.has-error > .help-block {
            color: red;
        }
        .content-header h1 {
            font-size: 1.5rem;
        }
        .content-header {
            padding: 5px .5rem;
        }
        .brand-link {
            line-height: 1.9;
        }

        .card-primary.card-outline {
            border-top: 3px solid #378903;
        }
        .btn-primary {
            background-color: #378903;
            border-color: #378903;
        }
        .btn-primary:hover{
            background-color: #378903;
            border-color: #378903;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #378903;
        }
        a {
            color: #378903;
        }
        .brand-link {
            line-height: 1.5;
        }
        .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
            background-color: #378903;
            border-color: #378903;
        }
        .navbar-light .navbar-nav .nav-link {
            color: rgb(0 159 75);
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: #378903;
        }
        .navbar-light .navbar-nav .nav-link:focus, .navbar-light .navbar-nav .nav-link:hover {
            color: rgb(0 159 75);
        }

        .bg-gradient-primary {
            background: #378903 linear-gradient(180deg,#378903,#378903) repeat-x!important;
            color: #fff;
        }
        .bg-gradient-primary.btn.active, .bg-gradient-primary.btn:active, .bg-gradient-primary.btn:not(:disabled):not(.disabled).active, .bg-gradient-primary.btn:not(:disabled):not(.disabled):active {
            background: #378903 linear-gradient(180deg,#378903,#378903) repeat-x!important;
            border-color: #378903;
        }
        .bg-gradient-primary.btn:hover {
            background: #378903 linear-gradient(180deg,#378903,#378903) repeat-x!important;
            border-color: #378903;
        }
        .btn-primary.focus, .btn-primary:focus {
            background-color: #378903;
            border-color: #378903;
            box-shadow: 0 0 0 0 rgb(0 159 75);
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected], .select2-container--default .select2-results__option--highlighted[aria-selected]:hover {
            background-color: #378903;
        }
        .datepicker table tr td.active, .datepicker table tr td.active.disabled, .datepicker table tr td.active.disabled:hover, .datepicker table tr td.active:hover {
            background-color: #378903;
            background-image: -moz-linear-gradient(to bottom,#378903,#378903);
            background-image: -ms-linear-gradient(to bottom,#378903,#378903);
            background-image: -webkit-gradient(linear,0 0,0 100%,from(#378903),to(#378903));
            background-image: -webkit-linear-gradient(to bottom,#378903,#378903);
            background-image: -o-linear-gradient(to bottom,#378903,#378903);
            background-image: linear-gradient(to bottom,#378903,#378903);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#08c', endColorstr='#0044cc', GradientType=0);
            border-color: #378903 #378903 #378903;
            border-color: rgb(0 159 75) rgb(2, 160, 76) rgb(2, 160, 76);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            color: #fff;
            text-shadow: 0 -1px 0 rgb(2, 160, 76);
        }

        .datepicker table tr td.active.active, .datepicker table tr td.active.disabled, .datepicker table tr td.active.disabled.active, .datepicker table tr td.active.disabled.disabled, .datepicker table tr td.active.disabled:active, .datepicker table tr td.active.disabled:hover, .datepicker table tr td.active.disabled:hover.active, .datepicker table tr td.active.disabled:hover.disabled, .datepicker table tr td.active.disabled:hover:active, .datepicker table tr td.active.disabled:hover:hover, .datepicker table tr td.active.disabled:hover[disabled], .datepicker table tr td.active.disabled[disabled], .datepicker table tr td.active:active, .datepicker table tr td.active:hover, .datepicker table tr td.active:hover.active, .datepicker table tr td.active:hover.disabled, .datepicker table tr td.active:hover:active, .datepicker table tr td.active:hover:hover, .datepicker table tr td.active:hover[disabled], .datepicker table tr td.active[disabled] {
            background-color: #378903;
        }

        fieldset {
            display: block;
            margin-inline-start: 2px;
            margin-inline-end: 2px;
            padding-block-start: 0.35em;
            padding-inline-start: 0.75em;
            padding-inline-end: 0.75em;
            padding-block-end: 0.625em;
            min-inline-size: min-content;
            border-width: 2px;
            border-style: groove;
            border-color: threedface;
            border-image: initial;
            padding-bottom: 0;
        }
        legend {
            width: auto;
            margin-bottom: 0;
        }
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            background-color: #378903;
        }
        [class*=sidebar-dark] .brand-link, [class*=sidebar-dark] .brand-link .pushmenu {
            color: rgb(0 159 75);
            background: #fff;
        }
        [class*=sidebar-dark] .brand-link .pushmenu:hover, [class*=sidebar-dark] .brand-link:hover {
            color: #378903;
        }
        .nav-link {
            padding: .5rem .5rem;
        }
        .layout-navbar-fixed .wrapper .sidebar-dark-primary .brand-link:not([class*=navbar]) {
            background-color: #ffffff;
        }
        body{
            overflow-x: hidden;
        }
        .img-circle {
            border-radius: 0%;
        }
        .navbar-light .navbar-nav .nav-link {
            color: #378903;
        }
        .navbar-light .navbar-nav .nav-link:focus, .navbar-light .navbar-nav .nav-link:hover {
            color: #378903;
        }
        [class*=sidebar-dark] .brand-link, [class*=sidebar-dark] .brand-link .pushmenu {
            color: #378903;
            background: #fff;
        }
        [class*=sidebar-dark] .brand-link .pushmenu:hover, [class*=sidebar-dark] .brand-link:hover {
            color: #378903;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #378903;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #378903;
        }
        .bg-gradient-primary {
            background: #378903 linear-gradient(
                180deg
                ,#378903,#378903) repeat-x!important;
            color: #fff;
        }
        .btn-primary {
            background-color: #378903;
            border-color: #378903;
        }
        .bg-gradient-primary.btn:hover {
            background: #378903 linear-gradient(
                180deg
                ,#378903,#378903) repeat-x!important;
            border-color: #378903;
        }
        .bg-gradient-primary.btn.active, .bg-gradient-primary.btn:active, .bg-gradient-primary.btn:not(:disabled):not(.disabled).active, .bg-gradient-primary.btn:not(:disabled):not(.disabled):active {
            background: #378903 linear-gradient(
                180deg
                ,#378903,#378903) repeat-x!important;
            border-color: #378903;
        }
        .btn-primary:hover {
            background-color: #378903;
            border-color: #378903;
        }
        .card-primary.card-outline {
            border-top: 3px solid #378903;
        }
        .btn-success {
            color: #fff;
            background-color: #378903;
            border-color: #378903;
            box-shadow: none;
        }
        .btn-success:hover {
            color: #fff;
            background-color: #378903;
            border-color: #378903;
        }
        .page-item.active .page-link {
            background-color: #378903!important;
            border-color: #378903!important;
        }
        a {
            color: #378903;
        }
        .bg-success {
            background-color: #378903!important;
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: #378903;
        }
        .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
            background-color: #378903!important;
            border-color: #378903!important;
        }
        .brand-text{
            margin-left: 25px;
        }

        .theme-loader {
            height:100%;
            width:100%;
            background-color:#fff;
            position:fixed;
            z-index:999999;
            top:0
        }
        .theme-loader .ball-scale {
            left:50%;
            top:50%;
            position:absolute;
            height:50px;
            width:50px;
            margin:-25px 0 0 -25px
        }
        .theme-loader .ball-scale .contain {
            height:100%;
            width:100%
        }
        .theme-loader .ball-scale .contain .ring {
            display:none
        }
        .theme-loader .ball-scale .contain .ring:first-child {
            display:block;
            height:100%;
            width:100%;
            border-radius:50%;
            padding:10px;
            border:3px solid transparent;
            border-left-color:#01a9ac;
            border-right-color:#01a9ac;
            -webkit-animation:round-rotate 1.5s ease-in-out infinite;
            animation:round-rotate 1.5s ease-in-out infinite
        }
        .theme-loader .ball-scale .contain .ring:first-child .frame {
            height:100%;
            width:100%;
            border-radius:50%;
            border:3px solid transparent;
            border-left-color:#0ac282;
            border-right-color:#0ac282;
            -webkit-animation:round-rotate 1.5s ease-in-out infinite;
            animation:round-rotate 1.5s ease-in-out infinite
        }
        @-webkit-keyframes round-rotate {
            100% {
                -webkit-transform:rotate(360deg);
                transform:rotate(360deg)
            }
        }
        @keyframes round-rotate {
            100% {
                -webkit-transform:rotate(360deg);
                transform:rotate(360deg)
            }
        }
        #table tr td,#table tr th{
            border: 1px solid black;
        }
        .table tr td,.table tr th{
            border: 1px solid black;
        }
        .table tr th,.table tr td{
            padding: 0px 2px;
            font-size: 12px;
            vertical-align: middle;
        }
        .btn-group-sm>.btn, .btn-sm {
            padding: 1px 0.5rem;
        }
        .nav .nav-item a{
            font-size: 12px;
        }
        /* New Css */
        [class*=sidebar-dark-] {
            background-color: #143257;
        }
        .main-header{
            height: 32px;
        }
        .brand-link{
            padding: 0 !important;
        }
        .nav .nav-link {
            padding: 2px 0.5rem !important;
        }
        .nav .nav-link i{
            font-size: 12px !important;
        }
        .small-box{
            margin-bottom: 10px;
        }
        .small-box .inner h5{
            padding-left: 5px !important;
        }
        .small-box .icon>i.fas, .small-box .icon>i.ion {
            font-size: 30px;
            top: 5px;
        }
        .info-box {
            margin-bottom: 5px;
            min-height: 45px;
            background-color: #097c94;
            color: #fff;
        }
        .info-box .info-box-content {
            line-height: 1;
        }
        .info-box .info-box-icon {
            font-size: 15px;
            height: 30px;
        }
        .modal-header {
            padding-top: 5px;
            padding-bottom: 0;
        }
        .modal-title {
            margin-bottom: 0;
            line-height: 1;
            font-size: 20px;
        }
        .modal-body{
            padding-top: 2px;
            padding-bottom: 2px;
        }
        .modal-footer {
            padding-top: 0;
            padding-bottom: 0;
        }
        .btn{
            font-size: 12px;
            padding: 0px 0.75rem;
        }
        .content-header {
            padding: 0px 0.5rem;
        }
        .content-header .row{
            margin-bottom: 2px!important;
        }
        .content-header h1 {
            font-size: 18px;
        }
        .card-header {
            padding: 2px 1.25rem;
        }
        .card-title{
            font-size: 14px;
        }
        .card-body{
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .card-body hr{
            margin-top: 5px;
            margin-bottom: 0px;
        }
        .dataTables_filter label,.dataTables_length label{
            font-size: 12px;
            margin-bottom: 0px !important;
        }
        .dataTables_length .custom-select-sm {
            height: 20px;
            font-size: 9px !important;
        }
        .dataTables_filter .form-control-sm{
            height: 20px;
            font-size: 12px;
        }
        .dataTables_paginate .page-link {
            padding: 0px 0.75rem;
            font-size: 12px;
        }
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0 !important;
            font-size: 12px;
        }
        .col-form-label {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            font-size: 12px !important;
        }
        .form-group {
            margin-bottom: 5px !important;
        }
        .form-control {
            height: 20px !important;
            font-size: 12px !important;
        }
        .card-footer {
            padding: 1px 1.25rem;
        }
        table.dataTable>thead .sorting:before, table.dataTable>thead .sorting:after, table.dataTable>thead .sorting_asc:before, table.dataTable>thead .sorting_asc:after, table.dataTable>thead .sorting_desc:before, table.dataTable>thead .sorting_desc:after, table.dataTable>thead .sorting_asc_disabled:before, table.dataTable>thead .sorting_asc_disabled:after, table.dataTable>thead .sorting_desc_disabled:before, table.dataTable>thead .sorting_desc_disabled:after {
            bottom: 0;
        }
        label{
            font-size: 12px;
            margin-bottom: 0px !important;
        }
        input[type=checkbox], input[type=radio] {
            height: 8px !important;
        }
        .select2-container--default .select2-selection--single {
            padding: 1px 0.2rem !important;
        }
        .select2-container .select2-selection--single {
            height: 25px !important;
            font-size: 12px !important;
        }
        #add_new_supplier_form{
            margin-top: 17px !important;
            padding: 0px 10px !important;
            font-size: 15px !important;
        }
        #excel_file{
            padding: 0px 0.75rem !important;
            font-size: 8px;
        }
        .main-footer {
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 12px;
        }
        .main-sidebar, .main-sidebar::before {
            width: 190px;
        }
        .sidebar-mini .main-sidebar .nav-link, .sidebar-mini-md .main-sidebar .nav-link, .sidebar-mini-xs .main-sidebar .nav-link {
            width: calc(190px - 0.5rem * 2);
        }
        .nav-sidebar .nav-link>.right, .nav-sidebar .nav-link>p>.right {
            top: 0.2rem;
        }
        .nav-top-item button{
            border-radius: 10px 0px;
            background-color: #0a0e14;
            color: #fff;
        }
        .nav-top-item button.next{
            border-radius: 0px 10px;
            background-color: #0a0e14;
            color: #fff;
        }
        @media (min-width: 768px){
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
                transition: margin-left .3s ease-in-out;
                margin-left: 190px;
            }
        }
        @media (min-width: 992px){
            .sidebar-mini.sidebar-collapse .main-sidebar.sidebar-focused, .sidebar-mini.sidebar-collapse .main-sidebar:hover {
                width: 190px;
            }
        }
        .sidebar-collapse.sidebar-mini .main-sidebar.sidebar-focused .nav-link, .sidebar-collapse.sidebar-mini .main-sidebar:hover .nav-link, .sidebar-collapse.sidebar-mini-md .main-sidebar.sidebar-focused .nav-link, .sidebar-collapse.sidebar-mini-md .main-sidebar:hover .nav-link, .sidebar-collapse.sidebar-mini-xs .main-sidebar.sidebar-focused .nav-link, .sidebar-collapse.sidebar-mini-xs .main-sidebar:hover .nav-link {
            width: calc(190px - 0.5rem * 2);
        }
    </style>
    @yield('style')
</head>
<body class="sidebar-mini">
<div class="theme-loader">
    <div class="ball-scale">
        <div class="contain">
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="padding: 0;">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" id="pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <h3  class="nav-link font-weight-bold active" style="color: #378903;font-size: 22px;margin-left: -22px;margin-top: -2px;">
                    Admin
                </h3>
            </li>
            <li class="nav-item nav-top-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">সেটিংস</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li><a href="{{ route('warehouse') }}" class="dropdown-item">ওয়্যারহাউস</a></li>
                    <li><a href="{{ route('unit') }}" class="dropdown-item">পণ্যের একক</a></li>
                    <li class="dropdown-submenu dropdown-hover">
                        <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">এস এম এস প্যানেল</a>
                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                            <li><a href="{{route('sms_panel')}}" class="dropdown-item">সেন্ট এস এম এস</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-submenu dropdown-hover">
                        <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">ইউজার  ম্যানেজমেন্ট</a>
                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                            <li><a href="{{ route('user.all') }}" class="dropdown-item">ইউজার</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-top-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">ব্যাংক & ক্যাশ</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li><a href="{{ route('bank') }}" class="dropdown-item">ব্যাংক</a></li>
                    <li><a href="{{ route('branch') }}" class="dropdown-item">ব্রাঞ্চ</a></li>
                    <li><a href="{{ route('bank_account') }}" class="dropdown-item">একাউন্ট</a></li>
                    <li><a href="{{ route('cash') }}" class="dropdown-item">ক্যাশ</a></li>
                </ul>
            </li>
            <li class="nav-item nav-top-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">  এইস আর & বেতন</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li class="dropdown-submenu dropdown-hover">
                        <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"> এইস আর</a>
                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                            <li><a href="{{route('department')}}" class="dropdown-item">ডিপার্টমেন্ট</a></li>
                            <li><a href="{{route('designation')}}" class="dropdown-item">উপাধি</a></li>
                            <li><a href="{{route('employee.all')}}" class="dropdown-item">কর্মচারী</a></li>
                            <li><a href="{{route('employee.attendance')}}" class="dropdown-item">কর্মচারী উপস্থিতি</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-submenu dropdown-hover">
                        <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">বেতন</a>
                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                            <li><a href="{{ route('payroll.salary_update.index') }}" class="dropdown-item">বেতন আপডেট</a></li>
                            <li><a href="{{ route('payroll.salary_process.index') }}" class="dropdown-item">বেতন প্রক্রিয়া</a></li>
                            <li><a href="{{ route('payroll.leave.index') }}" class="dropdown-item">ছুটি</a></li>
                            <li><a href="{{ route('payroll.holiday.index') }}" class="dropdown-item">হলিডে</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-top-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">ক্রয়</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li><a href="{{ route('supplier') }}" class="dropdown-item">সাপ্লাইয়ার</a></li>
                    <li><a href="{{ route('product_category') }}" class="dropdown-item">পণ্যের সাইজ</a></li>
                    <li><a href="{{ route('product_item') }}" class="dropdown-item">পণ্যের মডেল</a></li>
                    <li><a href="{{ route('purchase_order.create') }}" class="dropdown-item">ক্রয়</a></li>
                    <li><a href="{{ route('purchase_receipt.all') }}" class="dropdown-item">ক্রয় রিসিপ্ট</a></li>
                    <li><a href="{{ route('supplier_payment.all') }}" class="dropdown-item">সাপ্লাইয়ার পেমেন্ট</a></li>
                    <li><a href="{{ route('purchase_inventory.all') }}" class="dropdown-item">স্টক পণ্য</a></li>
                </ul>
            </li>
            <li class="nav-item nav-top-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">খুচরা বিক্রয়</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li><a href="{{ route('customer',['type'=>'retail_sale']) }}" class="dropdown-item">কাস্টমার</a></li>
                    <li><a href="{{ route('retail_sale_order_create') }}" class="dropdown-item">বিক্রয়</a></li>
                    <li><a href="{{ route('sale_receipt.customer.all',['type'=>'retail_sale']) }}" class="dropdown-item">বিক্রয় রিসিপ্ট</a></li>
                    <li><a href="{{ route('client_payment.customer.all',['type'=>'retail_sale']) }}" class="dropdown-item">কাস্টমার পেমেন্ট</a></li>
                </ul>
            </li>
            <li class="nav-item nav-top-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"> পাইকারি বিক্রয়</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li><a href="{{ route('customer',['type'=>'whole_sale']) }}" class="dropdown-item">কাস্টমার</a></li>
                    <li><a href="{{ route('whole_sale_order_create') }}" class="dropdown-item">বিক্রয়</a></li>
                    <li><a href="{{ route('sale_receipt.customer.all',['type'=>'whole_sale']) }}" class="dropdown-item">বিক্রয় রিসিপ্ট</a></li>
                    <li><a href="{{ route('client_payment.customer.all',['type'=>'whole_sale']) }}" class="dropdown-item">কাস্টমার পেমেন্ট</a></li>
                    <li><a href="{{ route('client_payment.all_pending_check',['type'=>'whole_sale']) }}" class="dropdown-item">সকল পেন্ডিং চেক</a></li>
                    <li><a href="{{ route('client_today_pending_check',['type'=>'whole_sale']) }}" class="dropdown-item">আজকের পেন্ডিং চেক</a></li>
                    <li><a href="{{ route('client_payment.all_pending_cash',['type'=>'whole_sale']) }}" class="dropdown-item">সকল পেন্ডিং ক্যাশ</a></li>
                    <li><a href="{{ route('client_today_pending_cash',['type'=>'whole_sale']) }}" class="dropdown-item">আজকের পেন্ডিং ক্যাশ</a></li>
                </ul>
            </li>
{{--            <li class="nav-item nav-top-item mt-2">--}}
{{--                <a href="{{route('purchase_order.create')}}"><button style="background-color: #0a86d8;" class="btn btn-warning">Purchase</button></a>--}}
{{--            </li>--}}
{{--            <li class="nav-item nav-top-item ml-2 mt-2">--}}
{{--                <a href="{{route('retail_sale_order_create')}}"><button style="background-color: #a82313;" class="btn btn-danger">Retail Sale</button></a>--}}
{{--            </li>--}}
{{--            <li class="nav-item nav-top-item ml-2 mt-2">--}}
{{--                <a href="{{route('whole_sale_order_create')}}"><button style="background-color: #ae0eb3;" class="btn btn-danger">Whole Sale</button></a>--}}
{{--            </li>--}}
{{--            <li class="nav-item nav-top-item ml-2 mt-2 dropdown">--}}
{{--                <button id="customerPaymentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #150cd4;" class="btn btn-info next dropdown-toggle">Stock</button>--}}
{{--                <div class="dropdown-menu" aria-labelledby="customerPaymentDropdown">--}}
{{--                    <a class="dropdown-item" href="{{ route('report_item_wise_stock') }}"><i class="nav-icon fas fa-list"></i> Item Wise Stock</a>--}}
{{--                    <a class="dropdown-item" href="{{ route('report_company_wise_stock') }}"><i class="nav-icon fas fa-list"></i> Company Wise Stock</a>--}}
{{--                    <a class="dropdown-item" href="{{ route('report_total_stock') }}"><i class="nav-icon fas fa-list"></i> Total Stock</a>--}}
{{--                    <a class="dropdown-item" href="{{ route('report.product_in_out') }}"><i class="nav-icon fas fa-list"></i> Product In Out</a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="nav-item nav-top-item ml-2 mt-2 dropdown">--}}
{{--                <button id="customerPaymentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #426c0a;" class="btn btn-info next dropdown-toggle">Customer Payment</button>--}}
{{--                <div class="dropdown-menu" aria-labelledby="customerPaymentDropdown">--}}
{{--                    <a class="dropdown-item" href="{{ route('client_payment.customer.all',['type'=>'retail_sale']) }}"><i class="fas fa-money-bill"></i> Retail Payment</a>--}}
{{--                    <a class="dropdown-item" href="{{ route('client_payment.customer.all',['type'=>'whole_sale']) }}"><i class="fas fa-money-bill"></i> Wholesale Payment</a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="nav-item nav-top-item ml-2 mt-2">--}}
{{--                <a href="{{route('report.daily',['start'=>date('Y-m-d'),'end'=>date('Y-m-d')])}}"><button style="background-color: #a82313;" class="btn btn-success next">Daily Report</button></a>--}}
{{--            </li>--}}
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">{{$layoutData['notificationCount']}} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{$layoutData['notificationCount']}} Notifications</span>
                    <div class="dropdown-divider"></div>
                    @foreach($layoutData['chequePaymentWarnings'] as $chequePaymentWarning)
                    <a href="{{ route('sale_receipt.details', ['order' => $chequePaymentWarning->id]) }}" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i>{{$chequePaymentWarning->order_no}} Cheque Payment Date
                    </a>
                    @endforeach
                    @foreach($layoutData['balanceTransfers'] as $balanceTransfers)
                    <a href="{{ route('report.transfer') }}" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i>B T From
                        @if($balanceTransfers->source_com_branch_id==0)
                          Admin
                        @else
                            {{$balanceTransfers->sourchBranch->name ?? ''}}
                        @endif

                    </a>
                    @endforeach

                </div>
            </li>
            <li class="nav-item dropdown" title="Today's pending cheque">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge">{{ todayPendingCheque() }} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ todayPendingCheque() }} Notifications</span>
                    <div class="dropdown-divider"></div>

                    <a href="{{ route('client_today_pending_check',['type'=>'whole_sale']) }}" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i>View {{ todayPendingCheque() }} Pending Cheque
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown" title="Today's pending cash">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge">{{ todayPendingCach() }} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ todayPendingCach() }} Notifications</span>
                    <div class="dropdown-divider"></div>

                    <a href="{{ route('client_today_pending_cash',['type'=>'whole_sale']) }}" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i>View {{ todayPendingCach() }} Pending Cash
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item"  onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                        </a>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="brand-link" style="padding: 8px 0px;">
            <img class="brand-text font-weight-light" src="{{ asset('img/company.png') }}" style="width: 50px;height: 50px;margin-left: 60px;"/>
            @if(auth()->user()->company_branch_id ==0)
{{--                <span class="brand-text font-weight-light"><b># Admin</b></span>--}}
            @else
                <?php
                $branch = \App\Model\CompanyBranch::where('id',auth()->user()->company_branch_id)->first();
                ?>
{{--                <span class="brand-text font-weight-light"><b>{{$branch->name}}</b></span>--}}
            @endif
        </a>

        <!-- Sidebar -->
        <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>ড্যাশবোর্ড</p>
                        </a>
                    </li>
                    <?php
                    $subMenu = [
                        'subboard','warehouse', 'warehouse.add', 'warehouse.edit','unit','unit.add','unit.edit','company','company.add','company.edit',
                        'user.all', 'user.edit', 'user.add','user.activity','sms_panel'
                    ];
                    ?>
                    @if(Auth::id() != 36)
                        @can('administrator')
                            <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        সেটিংস
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php
                                    $subSubMenu = [
                                        'warehouse', 'warehouse.add', 'warehouse.edit',
                                    ];
                                    ?>
                                    @can('warehouse')
                                        <li class="nav-item">
                                            <a href="{{ route('warehouse') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>ওয়্যারহাউস</p>
                                            </a>
                                        </li>
                                    @endcan
                                    <?php
                                    $subSubMenu = [
                                        'unit','unit.add','unit.edit'
                                    ];
                                    ?>
                                    @can('designation')
                                        <li class="nav-item">
                                            <a href="{{ route('unit') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>পণ্যের একক </p>
                                            </a>
                                        </li>
                                    @endcan
                                    <?php
                                    $subSubMenu = [
                                        'company','company.add','company.edit'
                                    ];
                                    ?>
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('company') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Company</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}

                                        <?php
                                        $subMenu = [
                                            'sms_panel',
                                        ];
                                        ?>
                                        <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-mail-bulk"></i>
                                                <p>
                                                    এস এম এস প্যানেল
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <?php
                                                $subSubMenu = [
                                                    'sms_panel', 'user.edit', 'user.add','user.activity'
                                                ];
                                                ?>
                                                <li class="nav-item">
                                                    <a href="{{route('sms_panel')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                        <p>সেন্ট এস এম এস</p>
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>
                                        <?php
                                        $subMenu = [
                                            'user.all', 'user.edit', 'user.add','user.activity'
                                        ];
                                        ?>
                                        <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-user"></i>
                                                <p>
                                                    ইউজার ম্যানেজমেন্ট
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <?php
                                                $subSubMenu = [
                                                    'user.all', 'user.edit', 'user.add','user.activity'
                                                ];
                                                ?>
                                                <li class="nav-item">
                                                    <a href="{{route('user.all')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                        <p>ইউজার</p>
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>
                                </ul>

                            </li>
                        @endcan
                    @endif
                    <?php
                    $subMenu = [
                        'bank', 'bank.add', 'bank.edit', 'branch', 'branch.add', 'branch.edit',
                        'bank_account', 'bank_account.add', 'bank_account.edit','cash','branch_cash'
                    ];
                    ?>
                    @can('bank_and_account')
                        <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    ব্যাংক & ক্যাশ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $subSubMenu = [
                                    'bank', 'bank.add', 'bank.edit',
                                ];
                                ?>
                                @can('bank')
                                    <li class="nav-item">
                                        <a href="{{ route('bank') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ব্যাংক</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'branch', 'branch.add', 'branch.edit',
                                ];
                                ?>
                                @can('branch')
                                    <li class="nav-item">
                                        <a href="{{ route('branch') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ব্রাঞ্চ</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'bank_account', 'bank_account.add', 'bank_account.edit'
                                ];
                                ?>
                                @can('account')
                                    <li class="nav-item">
                                        <a href="{{ route('bank_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>একাউন্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'cash'
                                ];
                                ?>
                                @if(auth()->user()->company_branch_id == 0 )
                                    @can('cash')
                                        <li class="nav-item">
                                            <a href="{{ route('cash') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>ক্যাশ</p>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                                <?php
                                $subSubMenu = [
                                    'branch_cash'
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('branch_cash') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Branch Cash</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                            </ul>
                        </li>
                    @endcan

                    <?php
                    $subMenu = [
                        'department','department.add','department.edit','designation','designation.add','designation.edit',
                        'employee.all', 'employee.add', 'employee.edit', 'employee.details','employee.attendance','report.employee_list'
                    ];
                    ?>

            @can('hr')
                <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            এইস আর
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <?php
                    $subSubMenu = [
                        'department','department.add','department.edit'
                    ];
                    ?>
                    @can('department')
                        <li class="nav-item">
                            <a href="{{ route('department') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                <p>ডিপার্টমেন্ট</p>
                            </a>
                        </li>
                    @endcan
                    <?php
                    $subSubMenu = [
                        'designation','designation.add','designation.edit'
                    ];
                    ?>
                    @can('designation')
                        <li class="nav-item">
                            <a href="{{ route('designation') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                <p>উপাধি</p>
                            </a>
                        </li>
                    @endcan
                    <?php
                    $subSubMenu = [
                        'employee.all', 'employee.add', 'employee.edit', 'employee.details'
                    ];
                    ?>
                    @can('employee')
                        <li class="nav-item">
                            <a href="{{ route('employee.all') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                <p>কর্মচারী</p>
                            </a>
                        </li>
                    @endcan
                    <?php
                    $subSubMenu = [
                        'report.employee_list'
                    ];
                    ?>
{{--                    @can('employee_list')--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('report.employee_list') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                <p>Employee List</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endcan--}}
                    <?php
                    $subSubMenu = [
                        'employee.attendance'
                    ];
                    ?>
                    @can('employee_attendance')
                        <li class="nav-item">
                            <a href="{{route('employee.attendance')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                <p>কর্মচারী উপস্থিতি</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan


                    <?php
                    $subMenu = [
                        'payroll.salary_update.index', 'payroll.salary_process.index',
                        'payroll.leave.index','payroll.holiday.index','payroll.holiday_add','payroll.holiday_edit'
                    ];
                    ?>
                @can('payroll')
                    <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                বেতন
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                    <?php
                    $subSubMenu = [
                        'payroll.salary_update.index'
                    ];
                    ?>
                        @can('salary_update')
                            <li class="nav-item">
                                <a href="{{ route('payroll.salary_update.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                    <p>বেতন আপডেট</p>
                                </a>
                            </li>
                        @endcan
                    <?php
                    $subSubMenu = [
                        'payroll.salary_process.index'
                    ];
                    ?>
                        @can('salary_process')
                            <li class="nav-item">
                                <a href="{{ route('payroll.salary_process.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                    <p>বেতন প্রক্রিয়া</p>
                                </a>
                            </li>
                        @endcan
                    <?php
                    $subSubMenu = [
                        'payroll.leave.index'
                    ];
                    ?>
                        @can('leave')
                            <li class="nav-item">
                                <a href="{{ route('payroll.leave.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                    <p>ছুটি</p>
                                </a>
                            </li>
                        @endcan
                    <?php
                    $subSubMenu = [
                        'payroll.holiday.index','payroll.holiday_add','payroll.holiday_edit'
                    ];
                    ?>
                        @can('holiday')
                            <li class="nav-item">
                                <a href="{{ route('payroll.holiday.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                    <p>হলিডে</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                    <?php
                    $subMenu = [
                        'supplier', 'supplier.add', 'supplier.edit', 'product_item', 'product_item.add',
                        'product_item.edit', 'purchase_order.create', 'purchase_receipt.all','product_descrition',
                        'purchase_receipt.details', 'purchase_receipt.qr_code', 'supplier_payment.all','supplier_payments',
                        'purchase_receipt.payment_details', 'purchase_inventory.all',
                        'purchase_inventory.details', 'purchase_inventory.qr_code',
                        'purchase_receipt.edit', 'product', 'product.add', 'product.edit',
                        'product_color', 'product_color.add', 'product_color.edit',
                        'product_size', 'product_size.add', 'product_size.edit',
                        'product_category', 'product_category.add', 'product_category.edit',
                        'product_stock', 'product_stock.add', 'product_stock.edit','purchase_stock_transfer',
                        'purchase_stock_transfer_details','stock_product_invoice.all','stock_product.invoice',
                        'stock_transfer.invoice','stock_product.barcode_print','stock_transfer_challan',
                        'transfer_challan.print','stock_product.barcode','stock_transfer_details','purchase_order.edit',
                        'purchase-inventory/edit','purchase_receipt.view_trash'
                    ];
                    ?>
                    @can('purchase')
                        @if(auth()->user()->company_branch_id==0)
                        <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    ক্রয়
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $subSubMenu = [
                                    'supplier', 'supplier.add', 'supplier.edit',
                                ];
                                ?>
                                @can('supplier')
                                    <li class="nav-item">
                                        <a href="{{route('supplier')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>সাপ্লাইয়ার</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'product_color', 'product_color.add', 'product_color.edit',
                                ];
                                ?>
                                {{--                                @can('product_item')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{route('product_color')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Product Color</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'product_size', 'product_size.add', 'product_size.edit',
                                ];
                                ?>
                                {{--                                @can('product_item')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{route('product_size')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Product Size</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}

                                <?php
                                $subSubMenu = [
                                    'product_category', 'product_category.add', 'product_category.edit',
                                ];
                                ?>
                                @can('product_item')
                                    <li class="nav-item">
                                        <a href="{{route('product_category')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>পণ্যের সাইজ</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'product_item', 'product_item.add', 'product_item.edit',
                                ];
                                ?>
                                @can('product_item')
                                    <li class="nav-item">
                                        <a href="{{route('product_item')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>পণ্যের মডেল</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'purchase_order.create',
                                ];
                                ?>
                                @can('purchase_order')
                                    <li class="nav-item">
                                        <a href="{{route('purchase_order.create')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ক্রয়</p>
                                        </a>
                                    </li>
                                @endcan

                                <?php
                                $subSubMenu = [
                                    'purchase_receipt.all','purchase_receipt.details','purchase_receipt.qr_code'
                                ];
                                ?>
                                @can('purchase_receipt')
                                    <li class="nav-item">
                                        <a href="{{route('purchase_receipt.all')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ক্রয় রিসিপ্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'product_stock','product_stock.add', 'product_stock.edit'
                                ];
                                ?>
{{--                                @can('purchase_inventory')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{route('purchase_order.create')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Manually Stock</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'supplier_payment.all','supplier_payments'
                                ];
                                ?>
                                @can('supplier_payment')
                                    <li class="nav-item">
                                        <a href="{{route('supplier_payment.all')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>সাপ্লাইয়ার পেমেন্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'stock_product_invoice.all',
                                ];
                                ?>
                                {{--                                @if(Auth::user()->id != 36)--}}
                                {{--                                    @can('purchase_order')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('stock_product_invoice.all')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                                <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                                <p>Stock Product Invoice</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                @endif--}}
                                <?php
                                $subSubMenu = [
                                    'purchase_inventory.all','purchase_inventory.details', 'purchase_inventory.qr_code',
                                ];
                                ?>
                                @can('purchase_inventory')
                                    <li class="nav-item">
                                        <a href="{{route('purchase_inventory.all')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>স্টক পণ্য</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'purchase_stock_transfer',
                                ];
                                ?>
                                {{--                                @can('purchase_inventory')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{route('purchase_stock_transfer')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Stock Transfer</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'stock_transfer.invoice',
                                ];
                                ?>
                                {{--                                @can('purchase_inventory')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{route('stock_transfer.invoice')}}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Stock Transfer Invoice</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}

                            </ul>
                        </li>
                        @endif
                    @endcan

                    <?php
                    $subMenu = [
                        'retail_sale_order_create', 'sale_receipt.all', 'sale_receipt.details',
                        'customer', 'customer.add', 'customer.edit','sale_receipt.payment_details',
                        'sale_information.index', 'customer_payment.all',
                        'sale_receipt.edit', 'sale_receipt.customer.all',
                        'client_payment.customer.all',
                        'client_payment.all_pending_check','manually_chequeIn',
                        'sales_return','sales_return.add','sales_return.create', 'sales_return.edit','client_today_pending_check'
                    ];
                    ?>
                    @can('sale')
                        <li class="nav-item @if(request()->get('type')) {{ in_array(Route::currentRouteName(), $subMenu) && request()->get('type')=='retail_sale' ? 'menu-open' : '' }} @else {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }} @endif">
                            <a href="#" class="nav-link @if(request()->get('type')) {{ in_array(Route::currentRouteName(), $subMenu) && request()->get('type')=='retail_sale' ? 'active' : '' }} @else {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }} @endif">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    খুচরা বিক্রয়
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $subSubMenu = [
                                    'customer', 'customer.add', 'customer.edit',
                                ];
                                ?>
                                @can('customer')
                                    <li class="nav-item">
                                        <a href="{{ route('customer',['type'=>'retail_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ক্রেতা</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'retail_sale_order_create',
                                ];
                                ?>
                                @can('sales_order')
                                    <li class="nav-item">
                                        <a href="{{ route('retail_sale_order_create') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>বিক্রয় </p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'sale_receipt.customer.all', 'sale_receipt.supplier.all', 'sale_receipt.details',
                                    'sale_receipt.payment_details','manually_chequeIn','cheque_status',
                                    'sale_payment.trash_view'
                                ];
                                ?>
                                @can('sale_receipt')
                                    <li class="nav-item">
                                        <a href="{{ route('sale_receipt.customer.all',['type'=>'retail_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>বিক্রয় রিসিপ্ট</p>
                                        </a>
                                    </li>
                                @endcan

                                <?php
                                $subSubMenu = [
                                    'sales_return','sales_return.add','sales_return.create', 'sales_return.edit',
                                ];
                                ?>
                                @can('customer')
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('sales_return') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Sales Return</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_payment.customer.all','customer_payments',
                                ];
                                ?>
                                @can('customer_payment')
                                    <li class="nav-item">
                                        <a href="{{ route('client_payment.customer.all',['type'=>'retail_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>কাস্টমার পেমেন্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_payment.all_pending_check',
                                ];
                                ?>
{{--                                @can('sales_order')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('client_payment.all_pending_check',['type'=>'retail_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>All Pending Cheque</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'client_today_pending_check',
                                ];
                                ?>
{{--                                @can('sales_order')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('client_today_pending_check',['type'=>'retail_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='retail_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Today Pending Cheque</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                            </ul>
                        </li>
                    @endcan

                    <?php
                    $subMenu = [
                        'whole_sale_order_create', 'sale_receipt.all', 'sale_receipt.details',
                        'customer', 'customer.add', 'customer.edit','sale_receipt.payment_details',
                        'customer_payment.all','client_payment.all_pending_check',
                        'sale_receipt.edit', 'sale_receipt.customer.all',
                        'client_payment.customer.all', 'client_payment.supplier.all',
                        'sales_return','sales_return.add','sales_return.create', 'sales_return.edit','client_today_pending_check',
                        'client_payment.all_pending_cash','client_today_pending_cash'
                    ];

                    ?>
                    @can('sale')
                        <li class="nav-item @if(request()->get('type')) {{ in_array(Route::currentRouteName(), $subMenu) && request()->get('type')=='whole_sale' ? 'menu-open' : '' }} @else {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }} @endif">
                            <a href="#" class="nav-link @if(request()->get('type')) {{ in_array(Route::currentRouteName(), $subMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }} @else {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }} @endif">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    পাইকারি বিক্রয়
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $subSubMenu = [
                                    'customer', 'customer.add', 'customer.edit',
                                ];
                                ?>

                                @can('customer')
                                    <li class="nav-item">
                                        <a href="{{ route('customer',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ক্রেতা</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'whole_sale_order_create',
                                ];
                                ?>
                                @can('sales_order')
                                    <li class="nav-item">
                                        <a href="{{ route('whole_sale_order_create') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>বিক্রয় </p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'sale_receipt.customer.all', 'sale_receipt.supplier.all', 'sale_receipt.details',
                                    'sale_receipt.payment_details','manually_chequeIn','cheque_status',
                                    'sale_payment.trash_view'
                                ];
                                ?>
                                @can('sale_receipt')
                                    <li class="nav-item">
                                        <a href="{{ route('sale_receipt.customer.all',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>বিক্রয় রিসিপ্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'sales_return','sales_return.add','sales_return.create', 'sales_return.edit',
                                ];
                                ?>
                                @can('customer')
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('sales_return') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Sales Return</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_payment.customer.all','customer_payments',
                                ];
                                ?>
                                @can('customer_payment')
                                    <li class="nav-item">
                                        <a href="{{ route('client_payment.customer.all',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>বিক্রয় পেমেন্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_payment.all_pending_check',
                                ];
                                ?>

                                @can('sales_order')
                                    <li class="nav-item">
                                        <a href="{{ route('client_payment.all_pending_check',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>সমস্ত পেন্ডিং চেক</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_today_pending_check',
                                ];
                                ?>
                                @can('sales_order')
                                    <li class="nav-item">
                                        <a href="{{ route('client_today_pending_check',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>আজকের পেন্ডিং চেক</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_payment.all_pending_cash',
                                ];
                                ?>

                                @can('sales_order')
                                    <li class="nav-item">
                                        <a href="{{ route('client_payment.all_pending_cash',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>সমস্ত পেন্ডিং ক্যাশ</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'client_today_pending_cash',
                                ];
                                ?>
                                @can('sales_order')
                                    <li class="nav-item">
                                        <a href="{{ route('client_today_pending_cash',['type'=>'whole_sale']) }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) && request()->get('type')=='whole_sale' ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>আজকের পেন্ডিং ক্যাশ</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    <?php
                    $subMenu = [
                        'sales_return','sales_return.add','sales_return.create', 'sales_return.edit',
                        'product_return_invoice.all','return_invoice.details','sale_return.trash_view'
                    ];
                    ?>
                    @can('administrator')
{{--                        <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">--}}
{{--                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">--}}
{{--                                <i class="nav-icon fas fa-list"></i>--}}
{{--                                <p>--}}
{{--                                    Sales Return--}}
{{--                                    <i class="right fas fa-angle-left"></i>--}}
{{--                                </p>--}}
{{--                            </a>--}}
{{--                            <ul class="nav nav-treeview">--}}
                               <?php
                                $subSubMenu = [
                                   'sales_return','sales_return.add','sales_return.create', 'sales_return.edit',
                                ];
                               ?>
{{--                                @can('customer')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('sales_return') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Sales Return</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'product_return_invoice.all'
                                ];
                                ?>
{{--                                @can('customer')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('product_return_invoice.all') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Return Invoice</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
{{--                            </ul>--}}
{{--                        </li>--}}
                    @endcan

                    <?php
                    $subMenu = [
                        'account_head.type', 'account_head.type.add', 'account_head.type.edit',
                        'account_head.sub_type', 'account_head.sub_type.add', 'account_head.sub_type.edit',
                        'transaction.all', 'transaction.add', 'transaction.details', 'balance_transfer.add'
                    ];
                    ?>
                    @can('accounts')
                        <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-columns"></i>
                                <p>
                                    একাউন্ট
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $subSubMenu = [
                                    'account_head.type', 'account_head.type.add', 'account_head.type.edit',
                                ];
                                ?>
                                @can('account_head_type')
                                    <li class="nav-item">
                                        <a href="{{ route('account_head.type') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>একাউন্ট হেড টাইপ</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'account_head.sub_type', 'account_head.sub_type.add', 'account_head.sub_type.edit',
                                ];
                                ?>
                                {{--                            @can('account_head_type')--}}
                                {{--                                <li class="nav-item">--}}
                                {{--                                    <a href="{{ route('account_head.sub_type') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                        <p>Account Head Sub Type</p>--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                                {{--                            @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'transaction.all', 'transaction.add', 'transaction.details',
                                ];
                                ?>
                                @can('project_wise_transaction')
                                    <li class="nav-item">
                                        <a href="{{ route('transaction.all') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>লেনদেন</p>
                                        </a>
                                    </li>
                                @endcan

                                <?php
                                $subSubMenu = [
                                    'balance_transfer.add',
                                ];
                                ?>
{{--                                @can('balance_transfer')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('balance_transfer.add') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Balance Transfer</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                            </ul>
                        </li>
                    @endcan
                    <?php
                    $menu = [
                        'report.salary.sheet','report.purchase', 'report.sale', 'report.balance_summary', 'report.sub_client_statement',
                        'report.profit_and_loss', 'report.ledger','report.purchase_stock', 'report.cashbook', 'report.monthly_expenditure',
                        'report.bank_statement','report.income_statement','report.client_statement','report.supplier_statement','report.monthly_crm',
                        'report.employee_attendance','report.sale_stock','report.price.with.stock','report.price.without.stock','report.receive_payment','report.trail_balance',
                        'report.product_in_out','report.cash_statement','report.party_ledger','report.bill_wise_profit_loss','report.transaction',
                        'report.branch_wise_client','report.branch_wise_sale_return','party_less_report','report.transfer','report_adjustment',
                        'report_item_wise_stock','report_company_wise_stock','report_total_stock','report_product_wise_sale','report_total_sale','report_party_wise_sale','report_purchase','report.supplier_ledger',
                        'report.daily'
                    ];
                    ?>
                    @can('report')
                        <li class="nav-item {{ in_array(Route::currentRouteName(), $menu) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $menu) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-columns"></i>
                                <p>
                                    রিপোর্ট
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $subMenu = [
                                    'report_item_wise_stock','report_company_wise_stock','report_total_stock','report.product_in_out'
                                ];
                                ?>
                                <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-mail-bulk"></i>
                                        <p>
                                            স্টক রিপোর্ট
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <?php
                                        $subSubMenu = [
                                            'report_item_wise_stock',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_item_wise_stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>আইটেম ওয়াইজ রিপোর্ট</p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report_company_wise_stock',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_company_wise_stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>কোম্পানি ওয়াইজ রিপোর্ট</p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report_total_stock',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_total_stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>মোট স্টক </p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report.product_in_out',
                                        ];
                                        ?>
                                        @can('price_with_stock')
                                            <li class="nav-item">
                                                <a href="{{ route('report.product_in_out') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                    <p>পণ্য ইন আউট রিপোর্ট</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                                <?php
                                $subMenu = [
                                    'report_product_wise_sale','report_total_sale','report_party_wise_sale','report.client_statement','report.party_ledger','party_less_report'
                                ];
                                ?>
                                <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-mail-bulk"></i>
                                        <p>
                                            বিক্রয় রিপোর্ট
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <?php
                                        $subSubMenu = [
                                            'report_product_wise_sale',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_product_wise_sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>পণ্য ওয়াইজ বিক্রয় রিপোর্ট</p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report_total_sale',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_total_sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>মোট বিক্রয় রিপোর্ট</p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report_party_wise_sale',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_party_wise_sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>পার্টি ওয়াইজ বিক্রয় রিপোর্ট</p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report.client_statement',
                                        ];
                                        ?>
                                        @can('client_summary')
                                            <li class="nav-item">
                                                <a href="{{ route('report.client_statement') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                    <p>পার্টি রিপোর্ট</p>
                                                </a>
                                            </li>
                                        @endcan
                                        <?php
                                        $subSubMenu = [
                                            'report.party_ledger',
                                        ];
                                        ?>
                                        @can('client_summary')
                                            <li class="nav-item">
                                                <a href="{{ route('report.party_ledger') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                    <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                    <p>পার্টি লেজার</p>
                                                </a>
                                            </li>
                                        @endcan
                                        <?php
                                        $subSubMenu = [
                                            'party_less_report',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('party_less_report') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>ডিসকাউন্ট রিপোর্ট</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php
                                $subMenu = [
                                    'report_purchase','report.supplier_ledger'
                                ];
                                ?>
                                <li class="nav-item {{ in_array(Route::currentRouteName(), $subMenu) ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-mail-bulk"></i>
                                        <p>
                                            ক্রয় রিপোর্ট
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <?php
                                        $subSubMenu = [
                                            'report_purchase',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report_purchase') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>ক্রয় রিপোর্ট</p>
                                            </a>
                                        </li>
                                        <?php
                                        $subSubMenu = [
                                            'report.supplier_ledger',
                                        ];
                                        ?>
                                        <li class="nav-item">
                                            <a href="{{ route('report.supplier_ledger') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                                <p>সাপ্লাইয়ার লেজার</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php
                                $subSubMenu = [
                                    'report_item_wise_stock',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_item_wise_stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Item Wise Stock</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report_company_wise_stock',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_company_wise_stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Company Wise Stock</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report_total_stock',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_total_stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Total Stock</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report_product_wise_sale',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_product_wise_sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Product Wise Sale Report</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report_total_sale',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_total_sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Total Sale Report</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report_party_wise_sale',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_party_wise_sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Party Wise Sale Report</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report_purchase',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report_purchase') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Purchase Report</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}


                                <?php
                                $subSubMenu = [
                                    'report.client_statement',
                                ];
                                ?>
{{--                                @can('client_summary')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report.client_statement') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Client Report</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'report.party_ledger',
                                ];
                                ?>
{{--                                @can('client_summary')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report.party_ledger') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Party Ledger</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                                 <?php
                                $subSubMenu = [
                                    'report.supplier_ledger',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('report.supplier_ledger') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Supplier Ledger</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report.supplier_statement',
                                ];
                                ?>
                                @if(auth()->user()->company_branch_id==0)
                                    @can('supplier_report')
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="{{ route('report.supplier_statement') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                                <p>Supplier Report</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
                                    @endcan

                                    <?php
                                    $subSubMenu = [
                                        'report.purchase',
                                    ];
                                    ?>
                                    @can('purchase_report')
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="{{ route('report.purchase') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                                <p>Purchase Report</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
                                    @endcan
                                @endif
                                <?php
                                $subSubMenu = [
                                    'report.branch_wise_sale_return',
                                ];
                                ?>
                                {{--                                @can('purchase_report')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{ route('report.branch_wise_sale_return') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Sale Return Report</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'report.sale',
                                ];
                                ?>
                                @can('purchase_report')
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report.sale') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Sale Report</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'party_less_report',
                                ];
                                ?>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="{{ route('party_less_report') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                        <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                        <p>Discount Report</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <?php
                                $subSubMenu = [
                                    'report.balance_summary',
                                ];
                                ?>
                                {{--                                @can('purchase_report')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{ route('report.balance_summary') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Balance Summary</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}


                                <?php
                                $subSubMenu = [
                                    'report.product_in_out',
                                ];
                                ?>
{{--                                @can('price_with_stock')--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report.product_in_out') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Product in Out Report</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'report.price.with.stock',
                                ];
                                ?>
                                @can('price_with_stock')
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report.price.with.stock') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Price With Stock</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                @endcan



                                <?php
                                $subSubMenu = [
                                    'report.bank_statement',
                                ];
                                ?>
                                @can('bank_statement')
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report.bank_statement') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Bank Statement</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'report.cash_statement',
                                ];
                                ?>
                                {{--                                @can('cashbook')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{ route('report.cash_statement') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Cash Statement</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'report.receive_payment',
                                ];
                                ?>
                                {{--                                @can('receive_and_payment')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{ route('report.receive_payment') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
                                {{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
                                {{--                                            <p>Receive & Payment</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                <?php
                                $subSubMenu = [
                                    'report.transaction',
                                ];
                                ?>
                                @can('bank_statement')
                                    <li class="nav-item">
                                        <a href="{{ route('report.transaction') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>লেনদেন রিপোর্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'report.cashbook',
                                ];
                                ?>
                                @can('cashbook')
                                    <li class="nav-item">
                                        <a href="{{ route('report.cashbook') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>ক্যাশবুক</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'report.profit_and_loss',
                                ];
                                ?>
                                @can('profit_and_loss')
                                    <li class="nav-item">
                                        <a href="{{ route('report.profit_and_loss') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>লাভ & লস</p>
                                        </a>
                                    </li>
                                @endcan
                                <?php
                                $subSubMenu = [
                                    'report.daily',
                                ];
                                ?>
                                @can('cashbook')
                                    <li class="nav-item">
                                        <a href="{{ route('report.daily') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>
                                            <p>দৈনিক রিপোর্ট</p>
                                        </a>
                                    </li>
                                @endcan
                                    <?php
                                    $subSubMenu = [
                                        'report.transfer',
                                    ];
                                    ?>
                                    @can('bank_statement')
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="{{ route('report.transfer') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                                <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                                <p>Transfer Report</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
                                    @endcan
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="{{ route('report_adjustment') }}" class="nav-link {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">--}}
{{--                                            <i class="far {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle' : 'fa-circle' }} nav-icon"></i>--}}
{{--                                            <p>Adjustment Report</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}

                            </ul>
                        </li>
                    @endcan
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper" @if(Route::currentRouteName()=='dashboard') style="background-image: url('{{ asset('img/dashboard_bg.jpg') }}');background-position: center center;" @endif>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> @yield('title') </h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Design & Developed By <a target="_blank" href="https://techandbyte.com">Tech@Byte</a>
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2022-{{ date('Y') }} <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('themes/backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('themes/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('themes/backend/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('themes/backend/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('themes/backend/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('themes/backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('themes/backend/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('themes/backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('themes/backend/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('themes/backend/plugins/toastr/toastr.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('themes/backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('themes/backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<!-- bootstrap datepicker -->
<script src="{{ asset('themes/backend/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>


<script src="{{ asset('themes/backend/dist/js/sweetalert2@9.js') }}"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var message = '{{ session('message') }}';
        var error = '{{ session('error') }}';

        if (!window.performance || window.performance.navigation.type != window.performance.navigation.TYPE_BACK_FORWARD) {
            if (message != '')
                $(document).Toasts('create', {
                    icon: 'fas fa-envelope fa-lg',
                    class: 'bg-success',
                    title: 'Success',
                    autohide: true,
                    delay: 10000,
                    body: message
                })

            if (error != '')
                $(document).Toasts('create', {
                    icon: 'fas fa-envelope fa-lg',
                    class: 'bg-danger',
                    title: 'Error',
                    autohide: true,
                    delay: 10000,
                    body: error
                })
        }
    });
    function jsNumberFormat(yourNumber) {
        //Seperates the components of the number
        var n= yourNumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    }
</script>

<script>
    $(function () {

        //Date picker
        $('.date-picker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm-dd-yyyy', { 'placeholder': 'mm-dd-yyyy' })
        //Money Euro
        $('[data-mask]').inputmask()

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });

        //Date and time picker
        $('.date-time').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            icons: { time: 'far fa-clock' }
        });
        //Date and time picker
        $('.date,.start_date,.end_date').datetimepicker({
            format: 'DD-MM-YYYY',
        });
        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM-DD-YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                    'Today'       : [moment(), moment()],
                    'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function(event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })

        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

    })
</script>
<script>
    $(function () {
        $('.theme-loader').fadeOut('slow', function() {
            $(this).remove();
        });
        //Date picker
        $( ".date-picker" ).datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });
        //Date picker

        // $('.month-picker').MonthPicker({
        //     Button: false,
        // });

        $("#financial_year").change(function(){
            let FYear = $(this).val();
            if (FYear !== ''){
                fiscalYearDateRange(FYear)
                $('.date-picker-fiscal-year').prop('readonly', false);
                $('.date-picker-fiscal-year').attr("placeholder", "Enter Date");
            }else{
                $('.date-picker-fiscal-year').prop('readonly', true);
                $('.date-picker-fiscal-year').val(" ");
                $('.date-picker-fiscal-year').attr("placeholder", "Enter Date");
            }
        })
        $("#financial_year").trigger('change');
        //Initialize Select2 Elements
        $('.select2').select2()
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })


        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()
        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()
        $('.my-colorpicker2').on('colorpickerChange', function (event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

    })
    function fiscalYearDateRange(year){

        $( ".date-picker-fiscal-year" ).datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            minDate: '01-07-'+year,
            maxDate: '30-06-'+(parseFloat(year) + 1)
        });
    }
    function jsNumberFormat(yourNumber) {
        //Seperates the components of the number
        var n= yourNumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    }
    function formSubmitConfirm(btnIdName){
        $('body').on('click', '#'+btnIdName, function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure to save?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#343a40',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save It!'

            }).then((result) => {
                if (result.isConfirmed) {
                    $('form').submit();
                }
            })

        });
    }
    function customDateInit(){
        $( ".date-picker" ).datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });
    }
    function customSelect2Init(){
        $('.select2').select2()
    }
</script>
@yield('script')
<!-- AdminLTE App -->
<script src="{{ asset('themes/backend/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
