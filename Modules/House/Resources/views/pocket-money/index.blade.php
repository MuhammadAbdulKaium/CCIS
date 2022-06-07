@extends('layouts.master')

@section('styles')
    <style>
        body {
            padding-right: 0 !important;
        }

        .select2-container{
            width: 100% !important;
        }
        .select2-selection{
            min-height: 33px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Cadet Pocket Money</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Cadets</a></li>
            <li>Operations</li>
            <li class="active">Pocket Money</li>
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
        
        
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Search Cadets </h3>
                    </div>
                    <div class="box-body">
                        <form id="cadet-search-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">House</label>
                                    <select name="houseId" class="form-control">
                                        <option value="">--Select House--</option>
                                        @foreach ($houses as $house)
                                            <option value="{{ $house->id }}">{{ $house->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Class</label>
                                    <select name="batchId" class="form-control" id="select-batch">
                                        <option value="">--Select Class--</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}">{{ $batch->batch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Form</label>
                                    <select name="sectionId" class="form-control" id="select-section">
                                        <option value="">--Select Form--</option>
                                    </select>
                                </div>
                                <div class="col-md-1" style="margin-top: 23px">
                                    <button type="button" class="btn btn-info" id="search-cadets-btn"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="std_list_container">
            
        </div>
    </section>
</div>

<div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 90%">
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
@endsection



{{-- Scripts --}}

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
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

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        function searchCadets(data, successCallback, errorCallback) {
            // Ajax Request Start
            $.ajax({
                url: "{{ url('/house/pocket-money/search-cadets') }}",
                type: 'GET',
                cache: false,
                data: data, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    
                },
            
                success: function (res) {
                    successCallback(res);
                },
            
                error: function (error) {
                    errorCallback(error);
                }
            });
            // Ajax Request End
        }

        // request for section list using batch id
        $(document).on('change','#select-batch',function(){
            var batch_id = $(this).val();

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },

                success:function(data){
                    // hide waiting dialog
                    // waitingDialog.hide();

                    let op = '<option value="" selected>--Select Form--</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('#select-section').html(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                },
            });
        });
        
        $(document).on('click', '#search-cadets-btn', function(){
            var data = $('form#cadet-search-form').serialize();
            // show waiting dialog
            waitingDialog.show('Loading...');
            searchCadets(data, (res) => {
                // hide waiting dialog
                waitingDialog.hide();
                console.log(res);
                $('#std_list_container').html(res);
            }, (error) => {
                // hide waiting dialog
                waitingDialog.hide();
                console.log(error);
            });
        });
    });
</script>
@stop