
@extends('layouts.master')

@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Report  |<small>Leave Status Report</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Human Resource</a></li>
                    <li><a href="#">Reports</a></li>
                    <li class="active">Leave Status Report</li>
                </ul>
            </section>

            <section class="content">
            <div>
                <div class="panel ">
                    <div  class="admin-chart" style="padding: 9px;">
                        <div class="row">
                            <form method="POST" id="std_manage_search_form" action="/employee/leave/status/search/">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="type" class="select-type" value="search">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Department</label>
                                        <select name="dept_id" id="department" class="form-control" required>
                                            <option value="">--Department--</option>
                                            @foreach($allDepartments as $department)
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Designation</label>
                                        <select name="designation_id" id="designation1" class="form-control">
                                            <option value="">--Designation--</option>
                                            @foreach($allDesignaitons as $designation)
                                                <option value="{{$designation->id}}">{{$designation->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Start Date</label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">End Date</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="margin-top: 23px">
                                    <label for=""></label>
                                    <input type="submit" name="search" class="btn btn-primary main-search-bar">
                                    <button class="btn btn-sm btn-primary search-btn" type="button"><i class="fa fa-search"></i></button>

                                    <button class="btn btn-sm btn-primary print-btn" type="button"><i class="fa fa-print"></i></button>
                                    <button class="print-submit-btn" type="submit" style="display: none"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="std_list_container_row" class="row">
                        @if(Session::has('success'))
                            <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">??</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                            </div>
                        @elseif(Session::has('alert'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">??</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                            </div>
                        @elseif(Session::has('warning'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">??</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection

@section('scripts')
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
    <script src="{{URL::asset('js/alokito-Chart.js')}}"></script>

    <script type="text/javascript">
        $('.print-btn').click(function () {
            console.log('Hi');
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });
        var host = window.location.origin;
        function searchEmployee()
        {
            $('.select-type').val('search');
            $.ajax({
                url: "/employee/leave/status/search/",
                type: 'POST',
                cache: false,
                data: $('form#std_manage_search_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    console.log(data);
                    // alert(data);
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        // alert(data);
                        var std_list_container_row = $('#std_list_container_row');
                        std_list_container_row.html('');
                        std_list_container_row.append(data.html);
                    }else{
                        alert(data.msg)
                    }
                },
                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        }
        $( document ).ready(function() {
            $(function () {
                // request for parent list using batch section id
                // $('form#std_manage_search_form').on('submit', function (e) {
                $('.search-btn').on('click', function () {
                    // ajax request
                    searchEmployee();
                });

                $(document).on('submit', 'form#emp_assign_submit_form', function (e) {
                    e.preventDefault();
                    var leaveStructureTotal=[];
                    $.each($("input[class='leave_id']:checked"),function (){
                        leaveStructureTotal.push($(this).val());
                    })
                    if(leaveStructureTotal.length>0)
                    {
                        // ajax request
                        $.ajax({
                            url: "/employee/assign/form/submit/",
                            type: 'POST',
                            cache: false,
                            data: $('form#emp_assign_submit_form').serialize(),
                            datatype: 'application/json',


                            beforeSend: function() {
                                // show waiting dialog
                                // waitingDialog.show('Loading...');
                            },

                            success:function(data){
                                $('.main-search-bar').click();
                                console.log(data);
                            },

                            error:function(data){
                                alert(JSON.stringify(data));
                            }
                        });
                    }
                    else{
                        alert('Please Check a leave days before Submit');
                    }
                })

            });
        });
    </script>

@endsection
