
@extends('layouts.master')

@section('content')
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Employee</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/">Human Resource</a></li>
                <li><a href="/employee">Employee Management</a></li>
                <li class="active">Manage Employee</li>
            </ul>
        </section>
        <section class="content">
            {{--sesssion msg area--}}
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
            {{--teacher search area--}}
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        @if (in_array('employee/manage/search', $pageAccessData))
                         <h3 class="box-title"><i class="fa fa-search"></i> Search Employee</h3>
                        @endif
                        @if (in_array('employee/create', $pageAccessData))
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="{{url('/employee/create')}}"><i class="fa fa-plus-square"></i> Add</a>
                            </div>      
                        @endif
                    </div>
                </div>
                @if (in_array('employee/manage/search', $pageAccessData))
                    <form id="manage_employee_form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="designation">Designation</label>
                                        <select id="designation" multiple="multiple"
                                                class="js-example-basic-multiple form-control"
                                                name="designation[]">
                                            <option value="">--- Select Designation ---</option>
                                            @if($allDesignations)
                                                @foreach($allDesignations as $designation)
                                                    <option value="{{$designation->id}}">{{$designation->name}} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="department">Department</label>
                                        <select id="department" multiple="multiple"
                                                class="js-example-basic-multiple form-control"
                                                name="department[]">
                                            <option value="">--- Select Department ---</option>
                                            @if($allDepartments)
                                                @foreach($allDepartments as $department)
                                                    <option value="{{$department->id}}">{{$department->name}} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col-sm-2">
                                    <label class="control-label" for="email">Email</label>
                                    <div class="form-group">
                                        <input id="email" class="form-control" name="email"
                                               placeholder="Enter Email ID." type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="email">Name</label>
                                    <div class="form-group">
                                        <input id="text" class="form-control" name="employee_name"
                                               placeholder="Enter Name." type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Employee ID</label>
                                    <div class="form-group">
                                        <input id="emp_id" class="form-control" name="emp_id"
                                               placeholder="Enter Employee ID" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Contact No</label>
                                    <div class="form-group">
                                        <input id="email" class="form-control" name="contact_no"
                                               placeholder="Phone Number " type="number">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Category</label>
                                        <select id="category" class="form-control category" name="category">
                                            <option selected value="">--- Select Category ---</option>
                                            <option value="one">Teaching</option>
                                            <option value="zero">Non Teaching</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <!-- Maritial Status -->
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Marital status</label>
                                        <select id="married" class="form-control category"
                                                name="marital_status">
                                            <option selected value="">--- Select one ---</option>
                                            <option value="MARRIED">MARRIED</option>
                                            <option value="UNMARRIED">UNMARRIED</option>
                                            <option value="DIVORCED">DIVORCED</option>
                                            <option value="Priest">Priest</option>
                                            <option value="Nun">Nun</option>

                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <!-- Blood Group -->
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Blood Group</label>
                                        <select id="categoryActivity" class="form-control"
                                                name="blood_group">
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
                                <!-- Gender  -->
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Gender</label>
                                        <select id="categoryActivity" class="form-control" name="gender">
                                            <option value="" selected>--- Select ---</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>

                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <!-- Religion -->
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
                                    <label class="control-label" for="emp_id">Present Address</label>
                                    <div class="form-group">
                                        <input id="present_address" class="form-control"
                                               name="present_address"
                                               placeholder="Enter Present Address" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Permanent Address</label>
                                    <div class="form-group">
                                        <input id="permanent_address" class="form-control"
                                               name="permanent_address"
                                               placeholder="Enter Present Address" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="emp_id">Current Status</label>
                                    <div class="form-group">
                                        <select id="status" multiple="multiple"
                                                class="js-example-basic-multiple form-control"
                                                name="statuses[]">

                                            @foreach($statuses as $status)
                                                <option class=" @if($status->category==1)text-success
                                                                    @elseif($status->category==2)
                                                        text-warning  @elseif($status    ->category==3)
                                                        text-danger
@endif" value="{{$status->id}}">{{$status->status}}</option>
                                            @endforeach
                                        </select>

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="">Current Job Duration</label>
                                    <div class="form-group row">

                                        <input id="current_duration_year" class="form-control col-sm-6"
                                               name="job_duration[0]"
                                               placeholder="Enter year" type="number">
                                        <input id="current_duration_month" class="form-control col-sm-6"
                                               name="job_duration[1]"
                                               placeholder="Enter Month" type="number">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="">Previous Job Duration</label>
                                    <div class=" row " style="padding:0 1rem">

                                        <input id="previous_exp" class="form-control col-sm-6"
                                               name="previous_exp[0]"
                                               placeholder="Enter year" type="number">
                                        <input id="previous_exp" class="form-control col-sm-6"
                                               name="previous_exp[1]"
                                               placeholder="Enter Month" type="number">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Date of Join</label>
                                    <div class="row " style="padding:0 1rem">
                                        <input id="doj_start" class="date_picker col-sm-6 form-control"
                                               name="doj_start"
                                               placeholder="Enter " type="text">

                                        <input id="doj_end" class="date_picker col-sm-6
                                                    form-control" name="doj_end"
                                               placeholder="Enter Date of join" type="text">
                                        <div class="help-block"></div>
                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Date of Retirement</label>
                                    <div class="row " style="padding:0 1rem">
                                        <input id="dor_start" class="date_picker col-sm-6 form-control"
                                               name="dor_start"
                                               placeholder="Enter Year" type="text">

                                        <input id="dor_end" class="date_picker col-sm-6
                                                    form-control" name="dor_end"
                                               placeholder="Enter Year" type="text">
                                        <div class="help-block"></div>
                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Birth Day</label>
                                    <div class="row " style="padding:0 1rem">
                                        <input id="bd_start" class="date_picker col-sm-6 form-control"
                                               name="bd_start"
                                               placeholder="Birth-year-from" type="text">

                                        <input id="bd_end" class="date_picker col-sm-6
                                                    form-control" name="bd_end"
                                               placeholder="Birth Year " type="text">
                                        <div class="help-block"></div>
                                    </div>


                                </div>

                                <div class="col-sm-2">
                                    <label class="control-label" for="">Children Count</label>
                                    <div class="row " style="padding:0 1rem">
                                        <input id="" class=" col-sm-6 form-control"
                                               name="child_count"
                                               placeholder="" type="number">


                                        <div class="help-block"></div>
                                    </div>


                                </div>
                                <!-- Central Position and Medical Category -->
                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Central Position </label>
                                    <div class="form-group">
                                        <input id="emp_id" class="form-control" name="central_position_serial"
                                               placeholder="Enter Center Position" type="number">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label class="control-label" for="emp_id">Medical Category</label>
                                    <div class="form-group">
                                        <input id="emp_id" class="form-control" name="medical_category"
                                               placeholder="Medical Category" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <!-- Central Position and Medical Category -->

                            </div>
                        </div>


                        <div class="box-footer">
                            <button id="teacher_list_search_btn" type="submit"
                                    class="btn btn-primary pull-right">Search
                            </button>
                            <button type="reset" class="btn btn-default pull-left">Reset</button>
                        </div>
                    </form>
                @endif
            </div>

            {{--employee_list_container--}}
            <div id="employee_list_container">
                {{--teacer list will be here--}}
            </div>

            <!-- global modal -->
            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="loader">
                                <div class="es-spinner">
                                    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@stop

@section('scripts')
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

    <script>
        jQuery(document).ready(function ()
        {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
            $(function () {
                $(".date_picker").datepicker(

                );
            });
            $('.js-example-basic-multiple').select2();


            function chekRequired() {
                var flag = 1;
                let bd_start = $('#bd_start').val();
                let bd_end = $('#bd_end').val();
                let doj_start = $('#doj_start').val();
                let doj_end = $('#doj_end').val();
                let dor_start = $('#dor_start').val();
                let dor_end = $('#dor_end').val();
                if (dor_start || dor_end) {
                    if (dor_start && dor_end) {
                        let x = new Date(dor_start);
                        let y = new Date(dor_end);
                        if (x > y) {
                            alert("The date of retairment range is not valid")
                            return false;
                        } else {
                            flag = 1;

                        }

                    } else {
                        flag = 0;
                        alert("Both Date of retirement field required");
                        return false;
                    }
                }
                if (bd_start || bd_end) {
                    if (bd_start && bd_end) {
                        let x = new Date(bd_start);
                        let y = new Date(bd_end);
                        if (x > y) {
                            alert("BirthDay range date should be greater than birthday start range");
                            return false;
                        } else {
                            flag = 1;
                        }
                    } else {
                        flag = 0;
                        alert("Both Date of retirement field required");
                        return false;
                    }

                }
                if (doj_start || doj_end) {
                    if (doj_start && doj_end) {
                        let x = new Date(doj_start);
                        let y = new Date(doj_end);
                        if (x > y) {
                            alert("The date of Join  range is not valid")
                            return false;
                        } else {
                            flag = 1;

                        }
                    } else {
                        alert("Both Date of join field is required");
                        return false;
                    }

                }
                if (flag === 1) {
                    return true;
                }

            }


            // request for parent list using batch section id
            $('form#manage_employee_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                let dateValidation = chekRequired();
                // ajax request
                if (dateValidation) {
                    $.ajax({
                        url: '/employee/manage/searchNew',
                        type: 'POST',
                        cache: false,
                        data: $('form#manage_employee_form').serialize(),
                        datatype: 'html',
                        // datatype: 'application/json',

                        beforeSend: function () {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function (data) {
                            // hide waiting dialog
                            waitingDialog.hide();
                            // refresh attendance container row
                            $('#employee_list_container').html('');
                            $('#employee_list_container').append(data);
                            $('#example2').DataTable({
                                "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]]
                            });
                        },
                        error: function () {
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                        }
                    });
                }
            });

            // request for section list using batch id
            jQuery(document).on('change','.department',function(){
                // refresh attendance container row
                $('#employee_list_container').html('');
            });
            // request for section list using batch id
            jQuery(document).on('change','.designation',function(){
                // get academic level id
                // refresh attendance container row
                $('#employee_list_container').html('');
            });
            // request for section list using batch id

        });

    </script>
@stop