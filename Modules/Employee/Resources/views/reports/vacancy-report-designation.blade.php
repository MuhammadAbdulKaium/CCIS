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
            <i class="fa fa-th-list"></i> Report |<small>Vacancy Report (Designation)</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Employee</a></li>
            <li>Reports</li>
            <li class="active">Vacancy Report (Designation)</li>
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
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Vacancy Report (Designation) </h3>
            </div>
            <form id="search-results-form" method="get" action="{{ url('/employee/vacancy-report-designation/designation-report') }}" target="_blank">
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
                                    @else 
                                        <option value="{{$allInstitute->id}}" selected>{{$allInstitute->institute_alias}}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" style="display: block" for="designationGroup">Designation Group</label>
                                <select name="designationGroupId[]" id="" class="form-control select-designation-group" required>
                                    <option value="all" selected>All</option>
                                    <option value="1">Teaching Category</option>
                                    <option value="2">Officer Category</option>
                                    <option value="3">General Category</option>
                                    <option value="4">Other Category</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" style="display: block" for="Class">Class</label>
                                <select name="classes[]" id="abc" class="form-control select-class" multiple required>
                                    <option value="">Select Class*</option>                                
                                    <option value="all">All</option>
                                    <option value="1">1st Class Officer</option>
                                    <option value="2">2nd Class Employee</option>
                                    <option value="3">3rd Class Employee</option>
                                    <option value="4">4th Class Employee</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" style="display: block" for="designation">Designation</label>
                                <select name="designationId[]" id="" class="form-control select-designation" multiple required>
                                    <option value="all" selected>All</option>
                                    @foreach($designations as $designation)
                                        <option value="{{$designation->id}}">{{$designation->name}}
                                            @if (($designation->bengali_name) || ($designation->bengali_name != null))
                                                - {{ $designation->bengali_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" style="display: block" for="academic_level">To Date</label>
                                <input type="date" value="{{ $toDate }}" name="toDate" class="form-control select-to-date" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-3" >
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
        var desigGroup;
        var sortedClasses = [];
        var desigGroup;

        var designationIds = [];

        $('.select-institute').select2({

        });
        $('.select-designation-group').select2({
            
        });
        $('.select-class').select2({
            placeholder: "Select Class*",
        });
        $('.select-designation').select2({
            
        });

        $('.select-designation-group').change(function () {
            desigGroup = $('.select-designation-group').val();

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/employee/vacancy-report-department/search-class') }}",
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
                        sortedClasses[i] = element
                    });
                    

                    if (data.length !== 0) {                        
                        var classes = '<option value="all">All</option>';
                        data.forEach(element => {
                            if (element == 1) {
                                classes += '<option value="'+1+'">1st Class Officer</option>'
                            }
                            else if (element == 2) {
                                classes += '<option value="'+2+'">2nd Class Employee</option>'
                            }
                            else if (element == 3) {
                                classes += '<option value="'+3+'">3rd Class Employee</option>'
                            }
                            else if (element == 4) {
                                classes += '<option value="'+4+'">4th Class Employee</option>'
                            }
                        });
                    }
                    else {
                        var classes = '<option value=""></option>';
                    }    
                    
                    $('.select-class').html(classes);
                    $('.select-class').select2({
                        placeholder: "Select Class*",
                    });
                    $('.select-designation').html("");
                    $('.select-designation').select2({
                        placeholder: "Select Designation*",
                    });
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

        $('.select-class').change(function () {
            var designation = null;
            designationIds = [];
            desigGroup = $('.select-designation-group').val();
            if ($('.select-designation-group').val() == 'all') {
                sortedClasses = [1,2,3,4]
            }
            
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
                    'selectedClass': $(this).val(),
                    'sortedClasses': sortedClasses,
                    'desigGroup': desigGroup,
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
                        designationIds[i] = element.id
                    });
                    

                    designation = '<option value="all">All</option>';
                    data.forEach(element => {
                        designation += '<option value="'+element.id+'">'+element.name+'</option>'
                    });

                    $('.select-designation').html(designation);
                    $('.select-designation').select2({
                        placeholder: "Select Designation*",
                    });
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

        $('.search-btn').click(function() {
            instituteId = $('.select-institute').val();
            designationId = $('.select-designation').val();
            classId = $('.select-class').val();

            if(instituteId && designationId && classId){
                $('.select-type').val('search');
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/employee/vacancy-report-designation/designation-report') }}",
                    type: 'get',
                    cache: false,
                    data: $('form#search-results-form').serialize(),
                    
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
                            console.log(data);
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
                        console.log('error');
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