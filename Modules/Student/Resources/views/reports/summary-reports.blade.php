@extends('layouts.master')

@section('styles')
    <style>
        .m-t {
            margin-top: 25px;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000 !important;
        }

        .select2-selection{
            min-height: 34px;
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
                <i class="fa fa-th-list"></i>  |<small>Cadet Summary Report</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{ url('/student/summary-reports') }}"> Cadet</a></li>
                <li><a href="{{ url('/student/summary-reports') }}"> Report</a></li>
                <li class="active">Cadet Summary Report</li>
            </ul>
        </section>

        <section class="content">
            <div>
                <div class="panel ">
                    <div class="admin-chart" style="padding: 9px;">
                        <form method="get" id="std_manage_search_form" action="{{route('find-report-summary')}}"
                              target="_blank">
                            <div class="row">
                                <input type="hidden" name="type" class="select-type" value="search">
                                <div class="col-sm-2" @if($existInstitute) style="display: none" @endif>
                                    <div class="form-group">
                                        <label for="institute_id">Select Institute</label>
                                        <select name="institute_id" id="institute_id" class="form-control">
                                            <option value="">--Select Institute--</option>
                                            @foreach ($allInstitute as $institute)
                                                <option
                                                        value="{{ $institute->id }}">
                                                    {{ $institute->institute_alias }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="department_id">Select Class</label>
                                        <select name="department_id" id="batch_id" class="form-control">
                                            <option value="">--Select Class--</option>
                                            @foreach($allBatch as $batch)
                                                <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="department_id">Select Form</label>
                                    <select name="sectionId" id="sectionId" class="form-control">
                                        <option value="">Select Form*</option>

                                    </select>
                                </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="employee_id">Select Student <sub style="color: red;">*</sub>
                                        </label>
                                        <select name="student_id" id="student_id" class="form-control" required>
                                            <option value=""> --Select Student --</option>
                                            @foreach($students as $student)
                                            <option value="{{$student->std_id}}">{{$student->first_name.' '
                                            .$student->last_name
                                             .' '
                                            .$student->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                    @php
                                        $chooseForms=[];


                                    @endphp

                                        <label for="">Choose </label>
                                        <br>
                                        <select id="designation1" class="form-control select-form" name="selectForm[]" multiple="multiple">
                                            <option value="">--Select--</option>
                                            <option selected value="cadet_no">Cadet No</option>
                                            <option selected value="name">Name</option>
                                            <option selected value="class">Class</option>
                                            <option selected value="house">House</option>
                                            <option selected value="batch_no">Batch No</option>
                                            <option selected value="academic_group">Academic Group</option>
                                            <option selected value="academic_performance">Academic Performance</option>
                                            <option selected value="survey_test">Survey Test</option>
                                            <option selected value="exam_marks">Exam Marks</option>
                                            <option selected value="jsc">JSC</option>
                                            <option selected value="ssc">SSC</option>
                                            <option selected value="college_fee">College Fee</option>
                                            <option selected value="co_curricular_activities">Co-curricular Activities</option>
                                            <option selected value="physical_fitness">Physical Fitness</option>
                                            <option selected value="discipline">Discipline State</option>
                                            <option selected value="mother_info">Father Info</option>
                                            <option selected value="father_info">Mother Info</option>
                                            <option selected value="other_family_member">Other Family Member Info</option>
                                            <option selected value="present_address">Present Address</option>
                                            <option selected value="present_address">Permanent Address</option>
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="col-sm-6">
                                {{-- <div class="col-sm-6 p0 m-t"> --}}
                                    {{-- <input type="checkbox" name="hide_blank" id="hide_blank"> <label for="hide_blank">Hide
                                        Blank</label> --}}
                                    <button class="btn btn-sm btn-primary search-btn" type="button"><i
                                                class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-primary print-btn" type="button"><i
                                                class="fa fa-print"></i></button>
                                    <button class="print-submit-btn" type="submit" style="display: none"></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row" style="padding: 9px">
                        <div id="std_list_container_row" class="col-md-12">
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
        $('#student_id').select2({
            placeholder: "Select Cadet"
        });
        $('.select-form').select2({
            placeholder: "Select Multi Fields"
        });

        var host = window.location.origin;

        function searchCadet() {

            $.ajax({
                url: "{{route('find-report-summary')}}",
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
                    std_list_container_row.append(data.data);
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
                    var std_id = $("#student_id").val();
                    if (std_id) {

                        searchCadet();
                    } else {
                        alert("please Select Student");
                    }
                });


            });
        });
        $('.print-btn').click(function() {
            // console.log('Hi');
            $('.select-type').val('print');
            var std_id = $("#student_id").val();
            if (std_id) {
                $('.print-submit-btn').click();
                // searchEmployee();
            } else {
                alert("please Select Student");
            }
        });
        // institute id search by employeee
        function searchStudents(inst_id = null, academicYear = null,batch=null, section = null) {
            $('#student_id').empty();
            $.ajax({
                url: "{{ url('/student/summary-reports/search-students') }}",
                type: 'GET',
                cache: false,
                data: {
                    id: inst_id,
                   academicYear,batch,section
                },
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },

                success: function(data) {
                    console.log(data);
                    var txt = '<option value="">--Select Students--</option>';
                    data.forEach(element => {
                        var first_name = (element?.first_name) ? (element?.first_name) : '';
                        var middle_name = (element?.middle_name) ? (element?.middle_name) : '';
                        var last_name = (element?.last_name) ? (element?.last_name) : '';
                        var title = (element?.title) ? (element?.title) : '';
                        var username = (element?.username) ? (element?.username) : '';

                        txt += '<option value="' + element.std_id + '">'  + title + ' ' + first_name + ' '
                            +middle_name + ' ' + last_name +
                        ' - '+username+'</option>';
                    });
                    $('#student_id').select2({
                        placeholder: "--Select Student--"
                    });
                    $('#student_id').empty();
                    $('#student_id').append(txt);
                },
                error: function(data) {
                    alert(JSON.stringify(data));
                }
            });
        }
        const studentsId=$("#student_id");

        $('#institute_id').on('change', function() {
            $('#batch_id option[value=""]').attr("selected", "selected");
            var value = $(this).val();
            searchStudents(value,null);


        })
        $('#batch_id').on('change', function() {
            var value = $(this).val();
            $('#sectionId').empty();
            var institute_id = $('#institute_id').val();

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-forms') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batch': $(this).val()
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {},

                success: function (data) {
                    var txt = '<option value="">Select Form*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.section_name+'</option>';
                    });

                    $('#sectionId').empty();
                    $('#sectionId').append(txt);

                }
        })
            searchStudents(institute_id,null,value,null);
        })
        $('#sectionId').on('change', function() {
            var value = $(this).val();
            var institute_id = $('#institute_id').val();
           var batch=$('#batch_id').val();
            searchStudents(institute_id,null,batch,value);
        })
    </script>
@endsection
