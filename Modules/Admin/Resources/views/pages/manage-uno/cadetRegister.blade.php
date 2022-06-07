
@extends('admin::layouts.master')
@section('styles')
    <!-- DataTables -->
   @endsection
@section('styles')
    <style>
        select[multiple]:focus option:checked {
            background: red linear-gradient(0deg, red 0%, red 100%);
        }
        #admin-chart {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .anychart-credits {
            display: none;
        }
        .chart-box {
            height: 300px;
        }
        div#admin-chart,div#admin-chart2,div#admin-chart3,div#admin-chart4,div#admin-chart5,div#admin-chart6,div#admin-chart7,div#admin-chart8,div#admin-chart9,div#admin-chart10,div#admin-chart11,div#admin-chart12 {
            height: 300px;
        }
        #Welcome {
            position: absolute;
            margin: 0px;
            display: inline-block;
            top: 50%;
            transform: translate(0%, -50%);
        }
        #Header {
            position: absolute;
            margin: 0px;
            display: inline-block;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        #LogOut {
            position: absolute;
            right: 0;
            margin-right: 10px;
            display: inline-block;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            top: 50%;
            transform: translate(0%, -50%);
        }
        #LogOut:hover {
            color: white;
        }
        #top-bar {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 5%;
            max-height: 45px;
            background-color: black;
            color: white;
        }
        .container {
            display: inline-block;
            cursor: pointer;
            margin-left: 10px;
            margin-right: 10px;
        }
        .bar1, .bar2, .bar3 {
            width: 35px;
            height: 5px;
            background-color: white;
            margin: 6px 0;
            transition: 0.4s;
        }
        #left-menu {
            display: none;
            position: absolute;
            background-color: black;
            color: white;
            left: 0;
            top:4.8%;
            height:100%;
            width:25%;
            max-width:270px;
        }
        .change .bar1 {
            -webkit-transform: rotate(-45deg) translate(-9px, 6px);
            transform: rotate(-45deg) translate(-9px, 6px);
        }
        .change .bar2 {opacity: 0;}
        .change .bar3 {
            -webkit-transform: rotate(45deg) translate(-8px, -8px);
            transform: rotate(45deg) translate(-8px, -8px);
        }
        #left-menu h1{
            border-bottom-style: solid;
        }
        #left-menu .inactive {
            font-size: 25px;
            color: white;
            text-decoration: none;
        }
        #left-menu .active {
            font-size: 25px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
        }
        #left-menu .active:hover {
            color: white;
        }
        #myCanvas {
            position: relative;
            width:100%;
            height:100%;
        }
        #main-content {
            position: absolute;
            color: black;
            left: 0;
            top:4.8%;
            height:95.2%;
            width:100%;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="cadet-baner" style="margin-bottom: 10px;">
                <img src="{{asset('template-2/img/all-cadet.jpg')}}" width="100%">
            </div>
            <div>
                <div class="panel ">
                    <div class="panel-body">
                        <div id="user-profile">
                            <ul id="w2" class="nav-tabs margin-bottom nav">
                                <li class="@if($page == 'institute')active @endif"><a href="/admin/dashboard/statics">All Institute</a></li>
                                <li class="@if($page == 'cadet')active @endif"><a href="/admin/dashboard/cadet/register">Cadet Register</a></li>
                                <li class="@if($page == 'hr')active @endif"><a
                                            href="/admin/dashboard/hr/register">HR Register</a></li>

                            </ul>
                        </div>
                    </div>
                    <div  class="box-body" >
                        <div class="row">
                            <form method="POST" id="std_manage_search_form">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Institute</label>
                                        <select id="inst" name="inst[]" multiple="multiple" class="js-example-basic-multiple form-control" >
                                            <option value=""  >--- Select ---</option>
                                            @foreach($allInst as $inst)
                                                <option
                                                        value="{{$inst->id}}">{{$inst->institute_alias}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Campus</label>
                                        <select id="campusId" class="form-control" name="campusId">
                                            <option selected  value="">--- All ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="house">House</label>
                                        <select id="house" class="form-control house" name="house">
                                            <option value="">--- Select House ---</option>

                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Academic Level</label>
                                        <select id="levelID" class="form-control" name="academic_level">
                                            <option value="" selected disabled>--- Select ---</option>
                                          @foreach($academicLevel as $level)
                                            <option value="{{$level->id}}"> {{$level->level_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Class</label>
                                        <select id="classID" class="form-control" name="batch">
                                            <option value="" selected disabled>--- Select ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Form</label>
                                        <select id="sectionID" class="form-control" name="section">
                                            <option value="" selected disabled>---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="show-for">Show for</label>
                                        <select id="cattype" class="form-control" name="cattype">
                                            <option value="" selected disabled>--- Select ---</option>
                                            @foreach($type as $item)
                                                <option value="{{$item->id}}">{{$item->performance_type}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Category</label>
                                        <select id="categoryID" class="form-control" name="categoryID">
                                            <option value="" selected disabled>--- Select Category ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Entity</label>
                                        <select id="categoryActivity" class="form-control" name="categoryActivity">
                                            <option value="" selected disabled>--- Select ---</option>
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


                                <div class="col-sm-2" >
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Cadet Number</label>

                                        <input id="std_username" class="form-control" name="std_username"
                                               placeholder="Cadet Number" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2" >
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Merit Position</label>

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
                                        <input type="text" class="form-control" id="batch_no" name="batch_no">
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

                                        <input type="number" class="form-control" id="tution" name="tuition_fees">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2" >
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Skills  </label>

                                        <input  type="text" placeholder="skill name" class="form-control" id="skill"
                                                name="skill">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class=" row p-4 mt-3 col-sm-12  " style="padding: 10px;color: white;
                                background:gray" >
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
                                    <div class="col-sm-1 ">
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
                                    <div class="col-sm-2 ">
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
                                    <div class="col-sm-1" style="margin-top: 25px" >

                                        <input style="border: 1px solid green;color: black" class="p-3 button bg-success
                                        btn "
                                               type="submit"
                                               name="search">
                                    </div>

                                </div>








                                {{--                                <div class="col-sm-1" style="margin-top:30px;">--}}
                                {{--                                    <a href="javascript:void(0)" id="all-search">--}}
                                {{--                                        <i class="fa fa-search fa-2x"></i>--}}
                                {{--                                    </a>--}}
                                {{--                                </div>--}}
                            </form>
                        </div>
                    </div>

                    <div id="std_list_container_row" class="row">
                        @if(Session::has('success'))
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
                </div>
            </div>
        </section>

    </div>



@endsection

@section('scripts')
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />



    <script src="{{URL::asset('js/alokito-Chart.js')}}"></script>
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        var host = window.location.origin;

        $( document ).ready(function() {
            $('.js-example-basic-multiple').select2();
            function disableMarks(){
                $(".result-search").attr('disabled','disabled');
                $(".result-search").val('');
            }
            function disableCampusHostel(){
                $('#campusId').attr('disabled','disabled');
                $("#house").attr('disabled','disabled');
            }
            function enableHostel(){
                $('#campusId').removeAttr('disabled');
                $("#house").removeAttr('disabled');
            }

            $("#all-search").click(function (){
                show_bar_chart();
            })

            $("#inst").change(function(){
                var institute=$(this).val();
                if(institute){
                    if(institute.length>1){
                        disableCampusHostel();
                    } else { enableHostel() }

                }


                if($(this).val() != "")
                {

                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/campusInstitute/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            console.log(
                                response
                            )
                            $("#campusId").html(response['campus']);
                            $("#house").html(response['house']);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#campusId").html('<option value="">-- Select --</option>');
                    $("#house").html('<option value="">-- Select --</option>');
                    $("#levelID").html('<option value="">-- Select --</option>');
                    $("#classID").html('<option value="">-- Select --</option>');
                }
            });

            $("#levelID").change(function(){
                disableMarks();
                $("#classID").html("");
                var batch_id = $("#classID").val();
                if(batch_id){
                    $(".result-search").removeAttr('disabled');
                }else{
                    $(".result-search").attr('disabled','disabled');
                    $(".result-search").val('');
                }

                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/academicBatch/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#classID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#classID").html("");
                }
            });

            $("#campusId").change(function(){

                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/academicLevel/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#levelID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    console.log(" Changing campus");
                    $("#levelID").html("");
                }
            });
            $(".result-search").attr('disabled','disabled');
            $("#classID").change(function(){
                var batch_id = $(this).val();
                if(batch_id){
                    $(".result-search").removeAttr('disabled');
                }else{
                    $(".result-search").attr('disabled','disabled');
                    $(".result-search").val('');
                }
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/academicSection/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#sectionID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#sectionID").html("");
                }
            });

            $("#cattype").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/type/category/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#categoryID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#categoryID").html("");
                }
            });

            $("#categoryID").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/category/activity/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            // return response;
                            $("#categoryActivity").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }else
                {
                    $("#categoryActivity").html("");
                }
            });

            $(function () {
                $('form#std_manage_search_form').on('submit', function(e) {
                    e.preventDefault();
                    // ajax request
                    var academicBatch = $('#classID').val();
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
                                url: "{{route('central.hr.submitSearch')}}",
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
                            url: "/admin/dashboard/searchcadetData/",
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
                // request for parent list using batch section id
              /*  $('form#std_manage_search_form').on('submit', function (e) {
                    e.preventDefault();
                    // ajax request
                    $.ajax({
                        url: "/admin/dashboard/searchcadetData/",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // console.log(data);
                            // hide waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.status=='success'){
                                console.log(data)
                                var std_list_container_row = $('#std_list_container_row');
                                std_list_container_row.html('');
                                std_list_container_row.append(data.html);
                            }else{
//                            alert(data.msg)
                            }
                        },

                        error:function(data){
//                        alert(JSON.stringify(data));
                        }
                    });
                });*/


            });

            $("#examId").change(function() {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/student/exam/search-subject-global') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'examId': $(this).val(),
                        'institute':$("#inst").val(),
                           'campus':$("#campusId").val(),
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success: function(data) {
                         console.log(data);
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
                    url: "{{ url('/student/exam/search-criteria-global') }}",
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
        });
    </script>

@endsection