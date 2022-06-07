@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    {{-- batch string --}}
    @php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Cadet  | <small>Register</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i>Home</a></li>
                <li class="active">Manage Cadet</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Cadet</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/student/profile/create"><i
                                        class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>
                <form id="std_manage_search_form" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="house">House</label>
                                    <select id="house" class="form-control house" name="house">
                                        <option value="">--- Select House ---</option>
                                        @foreach ($houses as $house)
                                            <option value="{{ $house->id }}">{{ $house->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                        <option value="">--- Select Level ---</option>
                                        @foreach ($academicLevels as $level)
                                            <option value="{{ $level->id }}">{{ $level->level_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="batch">{{ $batchString }}</label>
                                    <select id="batch" class="form-control academicBatch" name="batch">
                                        <option value="" selected>--- Select {{ $batchString }} ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">Form</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Form ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="show-for">Show for</label>
                                    <select id="cattype" class="form-control" name="cattype">
                                        <option value="" selected>--- Select ---</option>
                                        @foreach ($type as $item)
                                            <option value="{{ $item->id }}">{{ $item->performance_type }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="category">Category</label>
                                    <select id="categoryID" class="form-control" name="categoryID">
                                        <option value="" selected>--- Select Category ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="activity">Entity</label>
                                    <select id="categoryActivity" class="form-control" name="categoryActivity">
                                        <option value="" selected>--- Select ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1" style="margin-top: 25px;">
                                <div class="form-group">
                                    <input id="std_username" class="form-control" name="std_username"
                                           placeholder="Cadet Number" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1" style="margin-top: 25px;">
                                <div class="form-group">
                                    <input id="gr_no" class="form-control" name="gr_no" placeholder="Merit Position"
                                           type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="activity">Blood Group</label>
                                    <select id="categoryActivity" class="form-control" name="blood_group">
                                        <option value="" selected>--- Select ---</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="Unknown">Unknown</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="activity">Religion</label>
                                    <select id="categoryActivity" class="form-control" name="religion">
                                        <option value="" selected>--- Select ---</option>
                                        <option value="1">Islam</option>
                                        <option value="2">Hinduism</option>
                                        <option value="3">Christianity</option>
                                        <option value="4">Buddhism</option>
                                        <option value="5">Others</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-2" >
                                <div class="form-group">
                                    <label class="control-label" for="activity">First Name</label>

                                    <input id="std_username" class="form-control" name="first_name"
                                           placeholder="First Name" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-2" >
                                <div class="form-group">
                                    <label class="control-label" for="activity">Last Name</label>

                                    <input id="std_username" class="form-control" name="last_name"
                                           placeholder="Last Name" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="pressentAddress">Present Address</label>
                                    <input type="text" class="form-control" id="pressentAddress" name="pressentAddress">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Permanent Address</label>
                                    <input type="text" class="form-control" id="permanentAddress" name="permanentAddress">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="mobile">Mobile(Family)</label>
                                    <input type="text" class="form-control" id="mobile" name="phone">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="batch">Batch</label>
                                    <input type="text" class="form-control" id="batch" name="batch_no">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1" style="">
                                <div class="form-group">
                                    <label class="control-label" for="activity">Status</label>
                                    <select name="status" id="status_select" class="form-control">

                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1" >
                                <div class="form-group">
                                    <label class="control-label" for="activity">Tuition  Fees</label>

                                    <input type="number" class="form-control" id="batch" name="tuition_fees">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2" >
                                <div class="form-group">
                                    <label class="control-label" for="activity">Skills  </label>

                                    <input  type="text" placeholder="skill name" class="form-control" id="batch"
                                            name="skill">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <!-- New Work 25 April  -->
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Father Occupation</label>
                                    <input type="text" class="form-control" id="fatherOccupation"
                                           name="father_occupation">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Mother Occupation</label>
                                    <input type="text" class="form-control" id="motherOccupation"
                                           name="mother_occupation">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Father first Name</label>
                                    <input type="text" class="form-control" id="fatherOccupation"
                                           name="father_name">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Father last Name</label>
                                    <input type="text" class="form-control" id="fatherOccupation"
                                           name="father_last_name">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Mother First Name</label>
                                    <input type="text" class="form-control" id="motherName"
                                           name="mother_name">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="permanentAddress">Mother Last Name</label>
                                    <input type="text" class="form-control" id="motherName"
                                           name="mother_last_name">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <!-- New Work 25 April  -->
<!--                            <div class="col-sm-1" >
                                <div class="form-group">
                                    <label class="control-label" for="activity">Canteen Dues  </label>

                                    <input  type="number" class="form-control" id="batch" name="dues">

                                    <div class="help-block"></div>
                                </div>
                            </div>-->


                            <div class=" row p-4 mt-3 col-sm-11  bg-success" >
                                <div class="col-sm-1 ">
                                    <div class="form-group">
                                        <label class="control-label" for="aYear">Year</label>
                                        <select name="academic_year" id="aYear" class="result-search form-control academic_year">
                                            <option value="">--Select--</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group">
                                        <label class="control-label" for="trem">Term</label>
                                        <select name="tremId" id="trem" class="form-control tremId  result-search" >
                                            <option value="">--Select--</option>
                                            @foreach ($semesters as $semester)
                                                <option value="{{ $semester->id }}"> {{ $semester->name }} </option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1 ">
                                    <div class="form-group">
                                        <label class="control-label" for="examId">Exam</label>
                                        <select name="examId" id="examId" class="form-control examId  result-search">
                                            <option value="">--Select--</option>
                                            @foreach ($examNames as $exam)
                                                <option value="{{ $exam->id }}"> {{ $exam->exam_name }} </option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1 ">
                                    <div class="form-group">
                                        <label class="control-label" for="subject">Subject</label>
                                        <select name="subjectId" id="subject" class="form-control select_subject  result-search">
                                            <option value="">--Select--</option>

                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1 ">
                                    <div class="form-group">
                                        <label class="control-label" for="criteria">Criteria</label>
                                        <select name="criteriaId" id="criteria" class="form-control select_criteria result-search">
                                            <option value="">--Select--</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1 ">
                                    <div class="form-group">
                                        <label class="control-label" for="marks">Marks</label>
                                        <input name="marks" id="marks" class="form-control marks  result-search" />
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group">
                                        <label class="control-label" for="checkingParamter">Checking Parameter</label>
                                        <input name="checkingParamter" id="checkingParamter" class="form-control result-search"
                                               placeholder=">/</=" />
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group">

                                        <label class="control-label" for="topCadet">Top (Cadets)</label>
                                        <input name="topCadet" id="topCadet" class="result-search form-control" />
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
            {{-- std list container --}}
            <div id="std_list_container_row" class="row">
                @if (Session::has('success'))
                    <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                    </div>
                @elseif(Session::has('alert'))
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                    </div>
                @elseif(Session::has('warning'))
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog"
         tabindex="-1">
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
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var host = window.location.origin;
        $(function() {
            // request for parent list using batch section id

            $('form#std_manage_search_form').on('submit', function(e) {
                e.preventDefault();
                // ajax request
                var academicBatch = $('.academicBatch').val();
                var select_subject = $('.select_subject').val();
                var examId = $('.examId').val();
                var tremId = $('.tremId').val();
                var marks = $('.marks').val();
                var checkingParam = $('#checkingParamter').val();
                var topCadet=$('#topCadet').val();
                var academic_year = $('.academic_year').val();
                if (select_subject && examId && tremId || academic_year) {
                    if (!academicBatch) {
                        Swal.fire('Error!', 'Select Class field first!', 'error');

                    } else if (!select_subject) {
                        Swal.fire('Error!', 'Select Subject field first!', 'error');
                    }else if((marks && !checkingParam) || (!marks &&  checkingParam )){
                        Swal.fire('Error!', 'Select  marks and Checking Parameter field first!', 'error');
                    }else if(!marks && !checkingParam && !topCadet){
                        Swal.fire('Error!', 'Select  marks and Checking Parameter Or topcadet !', 'error');

                    }
                    else {
                        $.ajax({
                            url: "/student/manage/search",
                            type: 'POST',
                            cache: false,
                            data: $('form#std_manage_search_form').serialize(),
                            datatype: 'application/json',

                            beforeSend: function() {

                                // show waiting dialog
                                waitingDialog.show('Loading...');
                            },

                            success: function(data) {
                                console.log(data);
                                // hide waiting dialog
                                waitingDialog.hide();
                                // checking
                                if (data.status == 'success') {
                                    var std_list_container_row = $('#std_list_container_row');
                                    std_list_container_row.html('');
                                    std_list_container_row.append(data.html);
                                } else {
                                    waitingDialog.hide();
                                    //                            alert(data.msg)
                                }
                            },

                            error: function(data) {
                                waitingDialog.hide();                        alert(JSON.stringify(data));
                            }
                        });
                    }
                } else {
                    $.ajax({
                        url: "/student/manage/search",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function(data) {
                            console.log(data);
                            // hide waiting dialog
                            waitingDialog.hide();
                            // checking
                            if (data.status == 'success') {
                                var std_list_container_row = $('#std_list_container_row');
                                std_list_container_row.html('');
                                std_list_container_row.append(data.html);
                            } else {
                                waitingDialog.hide();                          alert(data.msg)
                            }
                        },

                        error: function(data) {
                            //                        alert(JSON.stringify(data));
                        }
                    });
                }
            });


        });

        jQuery(document).ready(function() {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });


        // request for batch list using level id
        jQuery(document).on('change', '.academicLevel', function() {
            // console.log("hmm its change");

            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op = "";
            $("#batch").html('');
            var batch_id = $("#batch").val();
            console.log(batch_id)
            if(batch_id){
                $(".result-search").removeAttr('disabled');
            }else{
                $(".result-search").attr('disabled','disabled');
                $(".result-search").val('');
            }

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {
                    'id': level_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#std_list_container_row').html('');
                },

                success: function(data) {
                    console.log('success');

                    //console.log(data.length);
                    op += '<option value="" selected>--- Select {{ $batchString }} ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i].batch_name + '</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append(
                        '<option value="0" selected>--- Select Section ---</option>');
                },

                error: function() {
                    alert(JSON.stringify(data));
                }
            });
        });
        $(".result-search").attr('disabled','disabled');
        // request for section list using batch id
        jQuery(document).on('change', '.academicBatch', function() {
            console.log("hmm its change");

            // get academic level id
            var batch_id = $(this).val();
            if(batch_id){
                $(".result-search").removeAttr('disabled');
            }else{
                $(".result-search").attr('disabled','disabled');
                $(".result-search").val('');
            }
            var div = $(this).parent();
            var op = "";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {
                    'id': batch_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#std_list_container_row').html('');
                },

                success: function(data) {
                    console.log('success');

                    //console.log(data.length);
                    op += '<option value="" selected>--- Select Section ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i].section_name +
                            '</option>';
                    }

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error: function() {
                    alert(JSON.stringify(data));
                },
            });
        });
        $("#cattype").change(function() {
            if ($(this).val() != "") {
                $.ajax({
                    type: "get",
                    url: host + '/admin/dashboard/type/category/' + $(this).val(),
                    data: "",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function(response) {
                        $("#categoryID").html(response);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                        console.log(errorThrown);
                    }
                });
            } else {
                $("#categoryID").html("");
            }
        });

        $("#categoryID").change(function() {
            if ($(this).val() != "") {
                $.ajax({
                    type: "get",
                    url: host + '/admin/dashboard/category/activity/' + $(this).val(),
                    data: "",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function(response) {
                        // return response;
                        $("#categoryActivity").html(response);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                        console.log(errorThrown);
                    }
                });
            } else {
                $("#categoryActivity").html("");
            }
        });

        $("#examId").change(function() {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/student/exam/search-subject') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'examId': $(this).val()
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function() {},

                success: function(data) {
                    // console.log(data);
                    var txt = '<option value="">Select Subject*</option>';
                    data.forEach(element => {
                        txt += '<option value="' + element.id + '">' + element.subject_name +
                            '</option>';
                    });

                    $('.select_subject').empty();
                    $('.select_subject').append(txt);
                    $('.select_criteria').empty();
                    $('.select_criteria').append('<option value="">Select Criteria*</option>');
                    // $('.select-subject').empty();
                    // $('.select-subject').append('<option value="">Select Subject*</option>');
                }
            });
            // Ajax Request End
        });
        $(".select_subject").change(function() {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/student/exam/search-criteria') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'subjectId': $(this).val()
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function() {},

                success: function(data) {
                    console.log(data);
                    var txt = '<option value="">Select Criteria*</option>';
                    data.criteria.forEach(element => {
                        txt += '<option value="' + element.id + '">' + element.name +
                            '</option>';
                        // console.log(element);
                    });

                    $('.select_criteria').empty();
                    $('.select_criteria').append(txt);
                    // $('.select-form').empty();
                    // $('.select-form').append('<option value="">Select Form*</option>');
                    // $('.select-subject').empty();
                    // $('.select-subject').append('<option value="">Select Subject*</option>');
                }
            });
            // Ajax Request End
        })
    </script>
@endsection
