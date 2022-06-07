@extends('layouts.master')

@section('styles')
    <style>
        .m-t {
            margin-top: 25px;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000 !important;
        }

        .p0 {
            padding: 0 !important;
        }

    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>HR Details Report</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{ url('/employee/profile/details/report') }}"> Human Resource</a></li>
                <li><a href="{{ url('/employee/profile/details/report') }}"> Reports</a></li>
                <li class="active">HR Details Report</li>
            </ul>
        </section>

        <section class="content">
            <div>
                <div class="panel ">
                    <div class="admin-chart" style="padding: 9px;">
                        <form method="get" id="std_manage_search_form" action="/employee/profile/details/report/search"
                            target="_blank">
                            <div class="row">
                                <input type="hidden" name="type" class="select-type" value="search">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="institute_id">Select Institute</label>
                                        <select name="institute_id" id="institute_id" class="form-control">
                                            <option value="">--Select Institute--</option>
                                            @foreach ($allInstitute as $institute)
                                                <option {{ $isInstitute ? 'selected' : ' ' }}
                                                    value="{{ $institute->id }}">
                                                    {{ $institute->institute_alias }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="department_id">Select Department</label>
                                        <select name="department_id" id="department_id" class="form-control">
                                            <option value="">--Select Department--</option>
                                            @foreach ($allDepartments as $department)
                                                <option value="{{ $department->id }}">
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="designation_id">Select Designation</label>
                                        <select name="designation_id" id="designation_id" class="form-control">
                                            <option value="">--Select Designation--</option>
                                            @foreach ($allDesignations as $designation)
                                                <option value="{{ $designation->id }}">
                                                    {{ $designation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="employee_id">Select Employee <sub style="color: red;">*</sub> </label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            <option value="">--Select Employee--</option>
                                            @foreach ($allEmployee as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->singleUser ? $employee->singleUser->username : ' ' }} -
                                                    {{ $employee->title . ' ' . $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                              
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">

                                        @php
                                            $chooseForms = ['Date of Birth', 'Joining Date', 'Retirement Date', 'Cadet College Tenure in Job', 'Previous Promotion Status', 'Current College', 'Medical Category', 'Blood Group', 'Mobile Number', 'E-mail'];
                                            if (in_array('employee/qualification', $pageAccessData)) {
                                                array_push($chooseForms, 'Educational Qualifications');
                                            }
                                            if (in_array('employee/training', $pageAccessData)) {
                                                array_push($chooseForms, 'Details of in-service training');
                                            }
                                            if (in_array('employee/transfer', $pageAccessData)) {
                                                array_push($chooseForms, 'Experience');
                                            }
                                            array_push($chooseForms, 'Remaining Tenure');
                                            array_push($chooseForms, 'Family Details');
                                            if (in_array('employee/acr', $pageAccessData)) {
                                                array_push($chooseForms, 'ACR Grading/Number');
                                            }
                                            if (in_array('employee/publication', $pageAccessData)) {
                                                array_push($chooseForms, 'Publication');
                                            }
                                            if (in_array('employee/discipline', $pageAccessData)) {
                                                array_push($chooseForms, 'Discipline');
                                            }
                                            if (in_array('employee/contribution-board-result', $pageAccessData)) {
                                                array_push($chooseForms, 'Contribution Board Result');
                                            }
                                            if (in_array('employee/special-duty', $pageAccessData)) {
                                                array_push($chooseForms, 'Special Duty');
                                            }
                                            if (in_array('employee/award', $pageAccessData)) {
                                                array_push($chooseForms, 'Honors/Awards');
                                            }
                                            if (in_array('employee/promotion', $pageAccessData)) {
                                                array_push($chooseForms, 'previous promotion remarks');
                                            }
                                        @endphp
                                        <label for="">Choose </label>
                                        <br>
                                        <select id="designation1" class="form-control select-form" name="selectForm[]"
                                            multiple="multiple">
                                            <option value="">--Select--</option>
                                            @foreach ($chooseForms as $key => $form)
                                                <option value="{{ $key + 1 }}">{{ $form }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 p0 m-t">
                                    <input type="checkbox" name="hide_blank" id="hide_blank"> <label for="hide_blank">Hide
                                        Blank</label>
                                    <button class="btn btn-sm btn-primary search-btn" type="button"><i
                                            class="fa fa-search"></i></button>

                                    <button class="btn btn-sm btn-primary print-btn" type="button"><i
                                            class="fa fa-print"></i></button>
                                    <button class="print-submit-btn" type="submit" style="display: none"></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="std_list_container_row" class="row">
                        @if (Session::has('success'))
                            <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in"
                                style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                            </div>
                        @elseif(Session::has('alert'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in"
                                style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                            </div>
                        @elseif(Session::has('warning'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in"
                                style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">×</button>
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
    {{-- <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet" />
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{ asset('js/jquery.bar.chart.min.js') }}"></script>
    <script src="{{ URL::asset('js/alokito-Chart.js') }}"></script> --}}

    <script type="text/javascript">
        $('#employee_id').select2();
        $('#department_id').select2();
        $('#designation_id').select2();
        $('.select-form').select2({
            placeholder: "Select Multi Form"
        });

        var host = window.location.origin;

        function searchEmployee() {

            $.ajax({
                url: "/employee/profile/details/report/search",
                type: 'get',
                cache: false,
                data: $('form#std_manage_search_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function(data) {
                    console.log(data);
                    // alert(data);
                    // hide waiting dialog
                    waitingDialog.hide();
                    var std_list_container_row = $('#std_list_container_row');
                    std_list_container_row.html('');
                    std_list_container_row.append(data);
                    // // checking
                    // if (data.status == 'success') {
                    //     // alert(data);
                    // } else {
                    //     alert(data.msg)
                    // }
                },
                error: function(data) {
                    alert(JSON.stringify(data));
                    waitingDialog.hide();
                }
            });
        }

        $(document).ready(function() {
            $(function() {
                // request for parent list using batch section id
                // $('form#std_manage_search_form').on('submit', function (e) {
                $('.search-btn').on('click', function() {
                    $('.select-type').val('search');
                    var employee_id = $("#employee_id").val();
                    if (employee_id) {

                        searchEmployee();
                    } else {
                        alert("plese Select Employee");
                    }
                });


            });
        });
        $('.print-btn').click(function() {
            // console.log('Hi');
            $('.select-type').val('print');
            var employee_id = $("#employee_id").val();
            if (employee_id) {
                $('.print-submit-btn').click();
                // searchEmployee();
            } else {
                alert("plese Select Employee");
            }
        });
        // institute id search by employeee
        function searchEmployees(inst_id = null, dep_id = null, deg_id = null) {
            $('#employee_id').empty();
            $.ajax({
                url: "{{ url('/employee/profile/details/report/search-employee') }}",
                type: 'GET',
                cache: false,
                data: {
                    id: inst_id,
                    dep_id: dep_id,
                    deg_id: deg_id,
                },
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },

                success: function(data) {
                    // console.log(data);
                    var txt = '<option value="">--Select Employee--</option>';
                    data.forEach(element => {
                        var first_name = (element?.first_name) ? (element?.first_name) : '';
                        var middle_name = (element?.middle_name) ? (element?.middle_name) : '';
                        var last_name = (element?.last_name) ? (element?.last_name) : '';
                        var title = (element?.title) ? (element?.title) : '';
                        var username = (element?.single_user) ? (element?.single_user.username) : '';
                        txt += '<option value="' + element.id + '">' + element?.single_user.username +
                            ' - ' + title + ' ' + first_name + ' ' +middle_name + ' ' + last_name + '</option>';
                    });
                    $('#employee_id').select2({
                        placeholder: "--Select Employee--"
                    });
                    $('#employee_id').empty();
                    $('#employee_id').append(txt);
                },
                error: function(data) {
                    alert(JSON.stringify(data));
                }
            });
        }
        $('#institute_id').on('change', function() {
            var value = $(this).val();
            var department_id = $('#department_id').val();
            var designation_id = $('#designation_id').val();
            searchEmployees(value, department_id, designation_id);
        })
        $('#department_id').on('change', function() {
            var value = $(this).val();
            var institute_id = $('#institute_id').val();
            searchEmployees(institute_id, value);
        })
        $('#designation_id').on('change', function() {
            var value = $(this).val();
            var institute_id = $('#institute_id').val();
            var department_id = $('#department_id').val();
            searchEmployees(institute_id, department_id, value);
        })
    </script>
@endsection
