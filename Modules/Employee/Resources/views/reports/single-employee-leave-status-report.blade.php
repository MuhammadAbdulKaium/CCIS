
@extends('layouts.master')

@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Employee Leave Assign</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Employee</a></li>
                    <li><a href="#">Leave</a></li>
                    <li class="active">Employee Leave Assign</li>
                </ul>
            </section>
            <div class="content clearfix">
                <h1 class="text-center"  style="margin-bottom: 10px; font-size:20px">Leave Status Details</h1>
                <div class="clearfix">
                    <div class="row table">
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th rowspan="7">
                                        <div class="image">
                                            <img src="{{ public_path('assets/users/images/emsBCC-2190-1635646008.jpg') }}" alt="logo">
                                        </div>

                                    </th>
                                </tr>
                                <tr>
                                    <td><b>Employee ID</b>:</b></td>
                                    <td>{{$employeeData->singleuser->username}}</td>
                                </tr>
                                <tr>
                                    <td><b>Employee Name</b>:</b></td>
                                    <td>{{$employeeData->first_name}} {{$employeeData->last_name}}</td>
                                </tr>
                                <tr>
                                    <td><b>Designation</b>:</b></td>
                                    <td>@isset($employeeData->singleDesignation){{$employeeData->singleDesignation->name}} @endisset</td>
                                </tr>
                                <tr>
                                    <td><b>Department</b>:</b></td>
                                    <td>@isset($employeeData->singleDepartment){{$employeeData->singleDepartment->name}}@endisset</td>
                                </tr>
                                <tr>
                                    <td><b>Date of Joining</b>:</b></td>
                                    <td>{{$employeeData->doj}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Leave Name</th>
                                    <th>Total Leave</th>
                                    <th>Enjoyed Leave</th>
                                    <th>Remain Leave</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($employeeLeaveAssign as $leaveAssign)
                                <tr>
                                    <td>
                                        @isset($leaveAssign->leaveStructureDetail)
                                            {{$leaveAssign->leaveStructureDetail->leave_name}}
                                        @endisset
                                    </td>
                                    <td>{{$employeeLeaveAssignHistory[$leaveAssign->leave_structure_id]->leave_remain}}</td>
                                    <td>{{$employeeLeaveAssignHistory[$leaveAssign->leave_structure_id]->leave_remain - $leaveAssign->leave_remain}}</td>
                                    <td>{{$leaveAssign->leave_remain}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table">
                        <p style="margin:13px 0; font-size:14px">Details:</p>
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 5%">SL</th>
                                <th style="width: 10%">Leave Name</th>
                                <th style="width: 10%">Available Days</th>
                                <th style="width: 15%">Requested Date</th>
                                <th style="width: 15%">Requested for</th>
                                <th style="width: 15%">Approved Date</th>
                                <th style="width: 15%">Approved for</th>
                                <th style="width: 5%">Balance</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($employeeLeaveApplication as $application)
                                <tr>
                                <td>01</td>
                                <td>{{$application->leaveStructureName->leave_name}}</td>
                                <td>{{$application->available_day}}</td>
                                <td>{{$application->applied_date}}</td>
                                <td>{{$application->req_for_date}} Days,({{$application->req_start_date}}-{{$application->req_end_date}})</td>
                                <td>{{$application->approve_date}}</td>
                                <td>{{$application->approve_for_date}} Days,({{$application->approve_start_date}}-{{$application->approve_end_date}})</td>
                                <td>{{$application->remains}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
    <script src="{{URL::asset('js/alokito-Chart.js')}}"></script>

    <script type="text/javascript">
        var host = window.location.origin;
        function searchEmployee()
        {
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
                $('form#std_manage_search_form').on('submit', function (e) {
                    e.preventDefault();
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
