@extends('layouts.app')
@section('title','Administator')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        .heading{
            text-align: center;
            margin-top: 50px;
        }
        .wrapper2{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pie-wrap{
            border: 2px solid lightgrey;
            width: 400px;
            height: 400px;
            margin: 2% 50px;
            position: relative;
            border-radius: 50%;
            color: black;
            overflow: hidden;
        }
        .pie-wrap .entry{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* *the individual entries* */

        .sky-blue{
            background-color: lightskyblue;
            height:50%;
            width: 50%;
            display: block;
        }

        .light-yellow{
            background-color: lightyellow;
            height:50%;
            width: 50%;
        }
        .pink{
            background-color: pink;
            height:50%;
            position: absolute;
            top: 0px;
            right: 0;
            width: 50%;
            /*clip-path: polygon(0 0, 100% 0%, 0% 100%);*/
        }

        .purple{
            background-color: purple;
            height:50%;
            width: 50%;
            right: 0;
            bottom: 0;
            position: absolute;
            /*clip-path:polygon(0% 100%, 100% 0%, 100% 100%);*/
        }

        .green{
            background-color: limegreen;
            height:50%;
            width: 50%;
            right: 0;
            top: 50%;
            position: absolute;
            clip-path:polygon(0% 0%, 100% 0%, 100% 100%);
        }

        .wheat{
            background-color: wheat;
            height:50%;
            width: 50%;
            right: 0;
            top: 50%;
            position: absolute;
            clip-path:polygon(0% 0%, 100% 100%, 0% 100%);
        }
        .pie-wrap .purple p{
            position: absolute;
            top: 140px;
            color: white;
        }
        .pie-wrap .purple p:first-child{
            top: 120px;
        }
        .pie-wrap .green p{
            position: absolute;
            top: 20px;
        }
        .pie-wrap .green p:first-child{
            top: 0px;
        }
        .pie-wrap .pink p, .pie-wrap .wheat p{
            position: absolute;
            left: 20px;
            top: 80px;
        }
        .pie-wrap .pink, .pie-wrap .wheat{
            justify-content: flex-start;
        }
        .pie-wrap .pink p:first-child, .pie-wrap .wheat p:first-child{
            top: 100px;
        }
        /*.entry .entry-value{*/
        /*    display: none;*/
        /*    transition: all 500ms linear;*/
        /*}*/
        /*.entry:hover .entry-value{*/
        /*    display: block;*/
        /*}*/
    </style>
@endsection

@section('content')
{{--    <h1 class="heading">Administator</h1>--}}
    <div class="wrapper2">
        <div class="pie-wrap">
            <div class="light-yellow entry">
                <p></p>
                <h4 class="entry-value"><a href="{{ route('warehouse') }}">Warehouse</a></h4>
            </div>

            <div class="sky-blue entry">
                <h4 class="entry-value"><a href="{{ route('unit') }}">Unit</a></h4>
            </div>

            <div class="pink entry">
                <p class="entry-value"><a href="{{ route('branch') }}">Company Branch</a></p>
            </div>

            <div class="purple entry">
{{--                <p> 12.5%</p>--}}
{{--                <p class="entry-value">Plantain</p>--}}
            </div>

{{--            <div class="green entry">--}}
{{--                <p> 12.5%</p>--}}
{{--                <p class="entry-value">Potato</p>--}}
{{--            </div>--}}

{{--            <div class="wheat entry">--}}
{{--                <p> 12.5%</p>--}}
{{--                <p class="entry-value">Yam</p>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@section('script')
    <script>

    </script>
@endsection