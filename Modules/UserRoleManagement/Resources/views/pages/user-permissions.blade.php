@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .list-group{
            margin-bottom: 0 !important;
        }
        .student-field{
            display: none;
        }
        .select2-selection--single{
            height: 33px !important;
        }
        .menu-label{
            cursor: pointer;
        }
    </style>
@stop


{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> User Role Management |<small>User Permissions</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/">User Role Management</a></li>
                <li><a href="/">SOP Setup</a></li>
                <li class="active">User Wise Permission</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                  style="text-decoration:none" data-dismiss="alert"
                  aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif

                {{--teacher search area--}}
                <div class="box box-solid">
                    <div class="et">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> Search Employee</h3>
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="{{url('/employee/create')}}"><i class="fa fa-plus-square"></i> Add</a>
                            </div>
                        </div>
                    </div>
                    <form id="manage_employee_form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
{{--                                <div class="col-sm-2">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="control-label" for="role">Role</label>--}}
{{--                                        <select id="role" class="form-control role" name="role">--}}
{{--                                            <option selected value="">--- Select Role ---</option>--}}
{{--                                            @foreach($roles as $role)--}}
{{--                                                <option value="{{$role->id}}">{{$role->display_name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        <div class="help-block"></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Category</label>
                                        <select id="category" class="form-control category" name="category" required>
                                            <option selected value="">--- Select Category ---</option>
                                            <option value="1">Teaching</option>
                                            <option value="0">Non Teaching</option>
                                            <option value="2">Student</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="employee-field">
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
                                    <div class="student-field">
                                        <div class="form-group">
                                            <label class="control-label" for="batch">Class</label>
                                            <select id="batch" class="form-control batch" name="batch">
                                                <option value="">--- Select Batch ---</option>
                                                @if($batches)
                                                    @foreach($batches as $key => $batch)
                                                        <option value="{{$key}}">{{$batch}} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="employee-field">
                                        <div class="form-group">
                                            <label class="control-label" for="designation">Designation</label>
                                            <select id="designation" class="form-control designation" name="designation">
                                                <option value="">--- Select Designation ---</option>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="student-field">
                                        <div class="form-group">
                                            <label class="control-label" for="section">Form</label>
                                            <select id="section" class="form-control section" name="section">
                                                <option value="">--- Select Form ---</option>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label" for="user">Name/ID</label>
                                        <select name="userIds[]" id="select-users" class="form-control" multiple>
                                        </select>
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
                </div>

                {{--employee_list_container--}}
                <div id="employee_list_container">
                    {{--teacer list will be here--}}
                </div>

            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
                 aria-hidden="true">
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

{{-- Scripts --}}

@section('scripts')
    <script>
        $(document).ready(function () {
            // $('#exampleTable').DataTable();

            $('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });

            $('#select-users').select2();

            // request for parent list using batch section id
            $('form#manage_employee_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: '/userrolemanagement/search-user',
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
                        console.log(data);
                        // hide waiting dialog
                        waitingDialog.hide();
                        // refresh attendance container row
                        $('#employee_list_container').html('');
                        $('#employee_list_container').append(data);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });

            // request for section list using batch id
            jQuery(document).on('change','.department',function(){
                // get academic level id
                var dept_id = $(this).val();
                var op="";

                $.ajax({
                    url: '/employee/find/designation/list/'+dept_id,
                    type: 'GET',
                    cache: false,
                    datatype: 'application/json',

                    beforeSend: function() {
                        //
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Designation ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }
                        // refresh attendance container row
                        $('#employee_list_container').html('');
                        // set value to the academic batch
                        $('.designation').html("");
                        $('.designation').append(op);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });

            // request for section list using batch id
            jQuery(document).on('change','.category',function(){
                // get academic level id
                // refresh attendance container row
                $('#employee_list_container').html('');
                $('.department').val("");
                $('.designation').html('<option value="" selected disabled>--- Select Designation ---</option>');
                $('.batch').val("");
                $('.section').html('<option value="" selected>--- Select Form ---</option>');
                $('#select-users').empty();

                var value = $(this).val();

                if (value == 2){
                    $('.student-field').show();
                    $('.employee-field').hide();
                } else{
                    $('.student-field').hide();
                    $('.employee-field').show();
                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','.batch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#employee_list_container').html('');
                },

                success:function(data){
                    op+='<option value="" selected>--- Select Form ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.section').html("");
                    $('.section').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                },
            });
        });

        $('.designation').change(function (){
            $.ajax({
                url: "{{ url('/userrolemanagement/get-employees/from-designation') }}",
                type: 'GET',
                cache: false,
                data: {
                    'designation': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    $('#employee_list_container').html('');
                },

                success:function(data){
                    var op = '';
                    data.forEach(element => {
                        op+='<option value="'+element.id+'">ID: '+element.single_user.username+', '+element.first_name+' '+element.last_name+'</option>';
                    });
                    $('#select-users').html(op);
                }
            });
        });

        $('.section').change(function (){
            $.ajax({
                url: "{{ url('/userrolemanagement/get-students/from-section') }}",
                type: 'GET',
                cache: false,
                data: {
                    'section': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    $('#employee_list_container').html('');
                },

                success:function(data){
                    var op = '';
                    data.forEach(element => {
                        op+='<option value="'+element.std_id+'">ID: '+element.single_user.username+', '+element.first_name+' '+element.last_name+'</option>';
                    });
                    $('#select-users').html(op);
                }
            });
        });
    </script>
@stop