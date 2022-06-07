@extends('layouts.master')

@section('styles')
    <style>
        .select2-selection--single {
            height: 33px !important;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Accounts |<small>Trial Balance</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="">Accounts</a></li>
                <li>Reports</li>
                <li class="active">Trial Balance</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> 
                    <a href="#" class="close"
                    style="text-decoration:none" data-dismiss="alert"
                    aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center"> 
                    <a href="#" class="close"
                    style="text-decoration:none" data-dismiss="alert"
                    aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center"> 
                    <a href="#" class="close"
                    style="text-decoration:none" data-dismiss="alert"
                    aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center"> 
                        <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ $error }}</p>
                @endforeach
            @endif

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Trial Balance</h3>
                </div>
                <div class="box-body">
                    <form id="search-results-form" method="POST" action="{{route('trail-balance.search')}}" target="_blank">
                        @csrf

                        <input type="hidden" name="type" class="select-type" value="search">

                        <div class="row"  style="margin-bottom: 50px">
                            <div class="col-sm-3">
                                <select name="report_type" id="" class="form-control select-term" required>
                                    <option value="details">Details</option>
                                    <option value="summary">Summary</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="start_date" id="startDate"
                                        placeholder="Start Date" required>
                            </div>
                            <div class="col-sm-2">
                                <input type="text"  class="form-control" name="end_date" id="endDate"
                                        placeholder="End Date" required>
                            </div>
                            <div class="col-sm-2">

                                <button type="button" class="btn btn-sm btn-primary search-btn" ><i class="fa fa-search"></i></button>
                                @if(in_array(34170, $pageAccessData))
                                <button class="btn btn-sm btn-primary print-btn" type="button"><i class="fa fa-print"></i></button>
                                @endif
                                    <button class="print-submit-btn" type="submit" style="display: none"></button>
                            </div>
                        </div>
                    </form>

                    <div class="marks-table-holder table-responsive" id="trial-balance-table">

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



{{-- Scripts --}}
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            const startDate=$('#startDate');
            const endDate=$('#endDate');
            const searchForm=$("#search-results-form");
            startDate.datepicker();
            endDate.datepicker();

            $('.search-btn').click(function (e){
                $('.select-type').val('search');
                let fd=searchForm.serialize();
                if(validateRange()){
                    $.ajax({
                        type: 'POST',
                            url:"{{route('trail-balance.search')}}" ,
                        cache: false,
                        datatype: 'application/json',
                        data:fd,
                        beforeSend: function() {
                            // show waiting dialog
                                waitingDialog.show('Loading...');
                        },
                        success:function (data) {
                            waitingDialog.hide();
                            $("#trial-balance-table").html(data.data);
                            console.log(data)
                        },
                        error: function(data) {
                            waitingDialog.hide();
                            alert(JSON.stringify(data));
                            console.log(data);
                        }
                    });
                }else {
                    Toast.fire({
                        icon: 'error',
                        title: 'End date must be grater than the start date!!'
                    });
                }
            });

            $('.print-btn').click(function () {
                $('.select-type').val('print');
                $('.print-submit-btn').click();
            });

            function validateRange(){
                let date1=new Date(startDate.val());
                let date2=new Date(endDate.val());
                return date2 >= date1;
            }

            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        });
    </script>
@stop