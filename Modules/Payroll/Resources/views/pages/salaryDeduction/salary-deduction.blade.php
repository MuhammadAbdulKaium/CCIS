
@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Payroll | <small>Employee Salary Deduction</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/">Human Resource</a></li>
                <li><a href="/employee">Payroll Management</a></li>
                <li class="active">Manage Salary Deduction</li>
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
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Employee</h3>
                    </div>
                </div>
                <form id="manage_employee_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
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
                                        @if($allDesignation)
                                            @foreach($allDesignation as $designation)
                                                <option value="{{$designation->id}}">{{$designation->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="department">Scale - Grade</label>
                                    <select id="department" class="form-control department" name="salaryScale">
                                        <option value="">--- Select Scale ---</option>
                                        @if($salaryScale)
                                            @foreach($salaryScale as $scale)
                                                <option value="{{$scale->id}}">{{$scale->scale_name}} - {{$scale->gradeName->grade_name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="bank">Bank</label>
                                    <select id="bank_id" class="form-control" name="bank_id">
                                        <option value="">--- Select Bank ---</option>
                                        @if($bankName)
                                            @foreach($bankName as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="bank">Bank Branch</label>
                                    <select class="form-control" name="branch_id" id="branch_id">
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="bank">Head</label>
                                    <select id="head_id" class="form-control" name="head_id" required>
                                        <option value="">--- Select Head ---</option>
                                        @if($salaryDeductionHead)
                                            @foreach($salaryDeductionHead as $deductHead)
                                                <option value="{{$deductHead->id}}">{{$deductHead->custom_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" for="email">Amount</label>
                                <div class="form-group">
                                    <input class="form-control" name="amount" type="number" required>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Month</label>
                                    <select name="month_name" class="form-control">
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
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
        </section>
    </div>

@stop

@section('scripts')
    <script>
        jQuery(document).ready(function () {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
            $('#bank_id').on('change',function (){
                let op="";
                let bank_id = $(this).val();
                // alert(bank_id);
                $.ajax({
                    url: '/payroll/bank/branch/search/'+bank_id,
                    type:'GET',

                    success: function (data)
                    {
                        op+='<option value="">--- Select Branch ---</option>';
                        for(let i=0;i<data.length;i++)
                        {
                            // console.log(data[i].branch_name);
                            op+='<option value="'+data[i].id+'">'+data[i].branch_name+'</option>';
                        }
                        $("#employee_list_container").html("");
                        $("#branch_id").html("");
                        $("#branch_id").append(op);
                    }
                })
            })
            $(document).on('submit',"#salary_deduct_form",function (e){
                let checkbox=$('.deduct-checkbox').val()
                e.preventDefault();
                $.ajax({
                    url:'/payroll/salary/deduct/store',
                    type:'POST',
                    cache: false,
                    data:$('form#salary_deduct_form').serialize(),
                    datatype: 'html',

                    success:function (data)
                    {
                        if(data.status==1)
                        {
                            swal("success", data.message);
                            searchPayroll();
                        }
                        if(data.status==0)
                        {
                            swal("error", data.message);
                            // searchPayroll();
                        }

                    }
                })
            })
            function searchPayroll()
            {
                $.ajax({
                    url: '/employee/manage/search/payroll/assign',
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
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            }
            $('form#manage_employee_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                searchPayroll();
            });
        });

    </script>
@stop