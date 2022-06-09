@extends('layouts.master')

@section('styles')
<style>
    .select2-container .select2-selection--single {
        height: 34px;
        border-radius: 1px;
    }
    .select2-selection__arrow {
        height: 31px !important;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />

    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Report |<small>Vacancy Report (Department)</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Employee</a></li>
            <li>Reports</li>
            <li class="active">Vacancy Report (Department)</li>
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
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Vacancy Report (Department) </h3>
            </div>
            <form id="search-results-form" method="get" action="{{ url('/employee/vacancy-report-department/department-report') }}" target="_blank">
                {{-- @csrf --}}
                <input type="hidden" name="type" class="select-type" value="search">


                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="institute">Institute</label>
                            <select name="instituteId[]" id="" class="form-control select-institute" multiple required>
                                @if (($role->name == 'super-admin') && ($currentInstitute == null))
                                    <option value="all" selected>All</option>
                                    @foreach($allInstitute as $institute)
                                        <option value="{{$institute->id}}">{{$institute->institute_alias}}</option>
                                    @endforeach
                                @else <option value="{{$allInstitute->id}}" selected>{{$allInstitute->institute_alias}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="departmentCategory">Dept. Category</label>
                            <select name="deptCategory[]" id="" class="form-control select-department-category" multiple required>
                                <option value="all" selected>All</option>
                                <option value="1">Student Department</option>
                                <option value="2">Teaching Department</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="department">Department</label>
                            <select name="departmentId[]" id="" class="form-control select-department" multiple required>
                                <option value="all" selected>All</option>
                                @foreach($department as $department)
                                    <option value="{{$department->id}}">{{$department->name}}
                                        @if ($department->bengali_name)
                                            {{-- - {{ $department->bengali_name }} --}}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="designationGroup">Designation Group</label>
                            <select name="designationId[]" id="select-designation-group" class="form-control change" required>
                                <option value="all" selected>All</option>
                                <option value="1">Teaching Category</option>
                                <option value="2">Officer Category</option>
                                <option value="3">General Category</option>
                                <option value="4">Other Category</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="Class">Class</label>
                            <select name="class[]" id="select-class" class="form-control change" multiple required>
                                <option value="">Select Class*</option>                                
                                <option value="all" selected>All</option>
                                <option value="1">1st Class Officer</option>
                                <option value="2">2nd Class Employee</option>
                                <option value="3">3rd Class Employee</option>
                                <option value="4">4th Class Employee</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="designation">Designation</label>
                            <select name="desigId[]" id="" class="form-control select-designation" multiple required>
                                <option value="">Select Designation*</option> 
                                
                            </select>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label" style="display: block" for="academic_level">To Date</label>
                            <input type="date" value="{{ $toDate }}" name="toDate" class="form-control select-to-date" required>
                        </div>
                        <div class="col-sm-4" >
                            <button type="button" class="btn btn-success search-btn"  style="margin-top: 23px;"><i class="fa fa-search"></i> Search</button>
                            <button type="button" class="btn btn-primary print-btn" style="margin-top: 23px; margin-left: 20px"><i class="fa fa-print"></i> Print</button>
                            <button type="submit" class="print-submit-btn" style="display: none"></button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="marks-table-holder table-responsive box-body">
                    
            </div>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function (){
        var deptCategory;
        var sortedClasses = [];
        var classes;
        var desigGroup;
        
        var departmentIds = [];
        var designationIds = [];

        $('.select-institute').select2({
            
        });
        $('.select-department-category').select2({
            
        });
        $('.select-department').select2({
            placeholder: "Select Department*",
        });
        // $('.select-designation-group').select2({
            
        // });
        // $('.select-class').select2({
        //     placeholder: "Select Class*",
        // });
        $('.change').select2({
            
        });
        $('.select-designation').select2({
            placeholder: "Select Designation*",
        });

        $('.select-department-category').change(function () {
            departmentIds = [];
            deptCategory = $('.select-department-category').val();
           
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/employee/vacancy-report-department/search-department') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'data': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                    console.log('beforeSend');
                },
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log('success');

                    data.forEach((element, i) => {
                        departmentIds[i] = element.id
                    });
                    
                    var department = '<option value="all" selected>All</option>';
                    data.forEach(element => {
                        department += '<option value="'+element.id+'">'+element.name+'</option>'
                    });

                    $('.select-department').html(department);
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                    console.log('error');
                }
            });
            // Ajax Request End           
        });

        $('.change').change(function () {
            var desigGroup = $('#select-designation-group').val();
            var classes = $('#select-class').val();
            
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/employee/vacancy-report-department/search-designation') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'desigGroup': desigGroup,
                    'classes': classes,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                    console.log('beforeSend');
                },
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
                    

                    var designation = '<option value="all" selected>All</option>';
                    data.forEach(element => {
                        designation += '<option value="'+element.id+'">'+element.name+'</option>';
                    });
                    console.log(designation);

                    $('.select-designation').html(designation);
                    $('.select-designation').select2({  });
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                    console.log('error');
                }
            });
            // Ajax Request End
        });

        // $('.select-designation-group').change(function () {
        //     desigGroup = $('.select-designation-group').val();

        //     $_token = "{{ csrf_token() }}";
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-Token': $('meta[name=_token]').attr('content')
        //         },
        //         url: "{{ url('/employee/vacancy-report-department/search-class') }}",
        //         type: 'GET',
        //         cache: false,
        //         data: {
        //             '_token': $_token,
        //             'data': $(this).val(),
        //         }, //see the _token
        //         datatype: 'application/json',
            
        //         beforeSend: function () {
        //             // show waiting dialog
        //             waitingDialog.show('Loading...');
        //             console.log('beforeSend');
        //         },
            
        //         success: function (data) {
        //             // hide waiting dialog
        //             waitingDialog.hide();
            
        //             console.log('success');

        //             data.forEach((element, i) => {
        //                 sortedClasses[i] = element
        //             });
                    
        //             if (data.length !== 0) {                        
        //                 var classes = '<option value="all">All</option>';
        //                 data.forEach(element => {
        //                     if (element == 1) {
        //                         classes += '<option value="'+1+'">1st Class Officer</option>'
        //                     }
        //                     else if (element == 2) {
        //                         classes += '<option value="'+2+'">2nd Class Employee</option>'
        //                     }
        //                     else if (element == 3) {
        //                         classes += '<option value="'+3+'">3rd Class Employee</option>'
        //                     }
        //                     else if (element == 4) {
        //                         classes += '<option value="'+4+'">4th Class Employee</option>'
        //                     }
        //                 });
        //             }
        //             else {
        //                 classes = '<option value=""></option>';
        //             }    
                    
        //             $('.select-class').html(classes);
        //             $('.select-class').select2({
        //                 placeholder: "Select Class*",
        //             });
        //             $('.select-designation').html("");
        //             $('.select-designation').select2({
        //                 placeholder: "Select Designation*",
        //             });
        //         },
            
        //         error: function (error) {
        //             // hide waiting dialog
        //             waitingDialog.hide();
            
        //             console.log(error);
        //             console.log('error');
        //         }
        //     });
        //     // Ajax Request End
        // });
     

        // $('.select-class').change(function () {
        //     var designation = null;
        //     designationIds = [];
        //     desigGroup = $('.select-designation-group').val();
        //     if ($('.select-designation-group').val() == 'all') {
        //         sortedClasses = [1,2,3,4]
        //     }
            
        //     $_token = "{{ csrf_token() }}";
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-Token': $('meta[name=_token]').attr('content')
        //         },
        //         url: "{{ url('/employee/vacancy-report-department/search-designation') }}",
        //         type: 'GET',
        //         cache: false,
        //         data: {
        //             '_token': $_token,
        //             'selectedClass': $(this).val(),
        //             'sortedClasses': sortedClasses,
        //             'desigGroup': desigGroup,
        //         }, //see the _token
        //         datatype: 'application/json',
            
        //         beforeSend: function () {
        //             // show waiting dialog
        //             waitingDialog.show('Loading...');
        //             console.log('beforeSend');
        //         },
            
        //         success: function (data) {
        //             // hide waiting dialog
        //             waitingDialog.hide();
            
        //             console.log('success');


        //             data.forEach((element, i) => {
        //                 designationIds[i] = element.id
        //             });
                    

        //             designation = '<option value="all">All</option>';
        //             data.forEach(element => {
        //                 designation += '<option value="'+element.id+'">'+element.name+'</option>'
        //             });

        //             $('.select-designation').html(designation);
        //             $('.select-designation').select2({
        //                 placeholder: "Select Designation*",
        //             });
        //         },
            
        //         error: function (error) {
        //             // hide waiting dialog
        //             waitingDialog.hide();
            
        //             console.log(error);
        //             console.log('error');
        //         }
        //     });
        //     // Ajax Request End
        // });
        

        $('.search-btn').click(function() {
            departmentId = $('.select-department').val();
            deptCategoryId = $('.select-department-category').val();
            designationId = $('.select-designation').val();
            toDate = $('.select-to-date').val();

            var desigGroup = $('#select-designation-group').val();
            var classes = $('#select-class').val();

            if(departmentId && desigGroup && toDate && classes && designationId){
                $('.select-type').val('search');
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/employee/vacancy-report-department/department-report') }}",
                    type: 'get',
                    cache: false,
                    data: $('form#search-results-form').serialize() +"&desigGroup="+desigGroup+"&classes="+classes+"&departmentIds="+departmentIds,
                    
                    datatype: 'application/json',
                    
                    beforeSend: function () {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                        console.log('beforeSend');
                    },
                
                    success: function (data) {
                        // hide waiting dialog
                        waitingDialog.hide();
                        if(data) {
                            $('.marks-table-holder').html(data);   
                        }
                        else {
                            $('.marks-table-holder').html('');
                        }
                    },
                
                    error: function (error) {
                        // hide waiting dialog
                        waitingDialog.hide();
                        console.log(error);
                        if(error){
                            $('.marks-table-holder').html('');
                        }
                    }
                });
            } else {
                swal('Error!', 'Please Fill up all the required fields first.', 'error');
            }
        });

        $('.print-btn').click(function () {
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });
    });
</script>
@stop