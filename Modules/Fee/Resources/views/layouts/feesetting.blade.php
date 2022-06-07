@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.css">
@endsection
<!-- page content -->
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i>Fee Setting</h1>

            {{--<ul class="breadcrumb">--}}
                {{--<li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>--}}
                {{--<li><a href="{{URL::to('finance')}}">Finance</a></li>--}}
                {{--<li><a href="{{URL::to('/fees')}}">Fees</a></li>--}}
            {{--</ul>--}}
        </section>
        <section class="content">
            {{--@if(Session::has('success'))--}}
            {{--<div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h4>--}}
            {{--</div>--}}
            {{--@elseif(Session::has('warning'))--}}
            {{--<div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>--}}
            {{--</div>--}}
            {{--@endif--}}
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul class="nav-tabs margin-bottom nav" id="">
                            <li @if($page == "feehead") class="active" @endif  id="#">
                                <a href="{{url('/fee/setting/feehead')}}">Fee Head</a>
                            </li>
                            <li @if($page == "waiver") class="active" @endif  id="#">
                                <a href="{{url('fee/setting/waiver')}}">Waiver</a>
                            </li>
                            {{--<li @if($page == "fund") class="active" @endif  id="#">--}}
                                {{--<a href="{{url('/fee/setting/fund')}}">Fund</a>--}}
                            {{--</li>--}}
                            {{--<li @if($page == "feefundcreate") class="active" @endif  id="#">--}}
                                {{--<a href="{{url('/fee/setting/feefundcreate')}}">Fee Fund Create</a>--}}
                            {{--</li>--}}

                            {{--<li @if($page == "feefundlist") class="active" @endif  id="#">--}}
                                {{--<a href="{{url('/fee/setting/feefundlist')}}">Fee Head Fund List</a>--}}
                            {{--</li>--}}

                            <li @if($page == "absentfine") class="active" @endif  id="#">
                                <a href="{{url('/fee/setting/absentfine')}}">Absent Fine</a>
                            </li>
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script src="{{URL::asset('js/tokenInput.js')}}"></script>
    <script src="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            @yield('page-script')
        });
    </script>

@endsection