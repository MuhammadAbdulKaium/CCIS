
@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Fees Collection | <small>Student</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{URL::to('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li class="active">Fees</li>
                <li class="active">Collection</li>
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
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Student</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/student/profile/create"><i class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>
                <form id="std_manage_search_form" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                        <option value="">--- Select Level ---</option>
                                        @foreach($academicLevels as $level)
                                            <option value="{{$level->id}}">{{$level->level_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="batch">{{$batchString}}</label>
                                    <select id="batch" class="form-control academicBatch" name="batch">
                                        <option value="" selected>--- Select {{$batchString}} ---</option>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Year</label>
                                    <select name="year" class="form-control">
                                        <option value="{{$years}}">{{$years}}</option>
                                        <option value="{{$years-1}}">{{$years-1}}</option>
                                        <option value="{{$years+1}}">{{$years+1}}</option>
                                    </select>
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

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fees Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Paid</option>
                                        <option value="2">Partially Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right main-search-bar">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
            {{--std list container--}}
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
        </section>
    </div>

    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
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
    <script>
        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
        var host = window.location.origin;
        function searchFeesTable(){
            $.ajax({
                url: "/student/manage/search/fees/details",
                type: 'POST',
                cache: false,
                data: $('form#std_manage_search_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },

                success:function(data){
                    console.log('Console Log');
                    // hide waiting dialog
                    // waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        var std_list_container_row = $('#std_list_container_row');
                        std_list_container_row.html('');
                        std_list_container_row.append(data.html);
                    }else{
//                            alert(data.msg)
                    }
                },

                error:function(data){
                    // waitingDialog.hide();
                    alert(JSON.stringify(data));
                }
            });
        }
        $(function () {
            // request for parent list using batch section id
            $('form#std_manage_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                searchFeesTable();
            });
            $(document).on('submit', 'form#fees-collection-form', function (e) {
            e.preventDefault();

            // ajax request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/cadetfees/fees/collection/store/",
                type: 'POST',
                cache: false,
                data: $('form#fees-collection-form').serialize(),
                datatype: 'application/json',


                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    waitingDialog.hide();
                    $('.main-search-bar').click();
                },

                error: function (data) {
                    waitingDialog.hide();
                    alert(JSON.stringify(data));
                }
            });
        });

        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });


        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // console.log("hmm its change");

            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch/') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#std_list_container_row').html('');
                },

                success:function(data){
                    console.log('data');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select {{$batchString}} ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected>--- Select Section ---</option>');
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            console.log("hmm its change");

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
                    $('#std_list_container_row').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                },
            });
        });


    </script>
@endsection