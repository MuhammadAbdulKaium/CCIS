
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
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Category</label>
                                        <select id="category" class="form-control category" name="category">
                                            <option selected value="">--- Select Category ---</option>
                                            <option value="1">Teaching</option>
                                            <option value="0">Non Teaching</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="department">Department</label>
                                        <select id="department" class="form-control department" name="department">
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
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="designation">Designation</label>
                                        <select id="designation" class="form-control designation" name="designation">
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
                                <div class="col-sm-3">
                                    <label class="control-label" for="email">Email</label>
                                    <div class="form-group">
                                        <input id="email" class="form-control" name="email" placeholder="Enter Email ID." type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="emp_id">Employee ID</label>
                                    <div class="form-group">
                                        <input id="emp_id" class="form-control" name="emp_id" placeholder="Enter Employee ID" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button id="teacher_list_search_btn" type="submit" class="btn btn-primary pull-right">Search</button>
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


            // request for parent list using batch section id
            $('form#manage_employee_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: '/employee/manage/search',
                    type: 'POST',
                    cache: false,
                    data: $('form#manage_employee_form').serialize(),
                    datatype: 'html',
                    // datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // refresh attendance container row
                        $('#employee_list_container').html('');
                        $('#employee_list_container').append(data);
                        $('#example2').DataTable();
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
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
            jQuery(document).on('change','.category',function(){
                // get academic level id
                // refresh attendance container row
                $('#employee_list_container').html('');
            });
        });

    </script>
@stop