@extends('layouts.master')

@section('styles')
    <style>
        .select2{
            width: 100% !important;
        }
        .mess-table-holder{
            text-align: center;
        }
        .mess-table{
            display: inline-block;
        }
        .mess-table-column{
            float: left;
        }
        .mess-table-seat{
            border: 1.99px solid;
            /* width: 40px; */
            height: 44px;
            text-align: center;
            /* line-height: 40px; */
            cursor: pointer;
            padding: 2px;
            font-weight: bold;
        }
        .table-seat-username{
            vertical-align: middle;
            display: inline-table;
        }
        .seat-no{
            font-size: 15px;
        }
        .username{
            font-size: 11px;
        }
        .mess-table-seat:hover{
            background: lightgray;
        }
        .mess-table-no-seat{
            width: 44px;
            height: 44px;
        }
        .searched-seat{
            background: yellow;
        }
        .select2-selection--single{
            height: 33px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Mess Management |<small>Table Set Up</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="">Mess</a></li>
            <li><a href="">SOP Setup</a></li>
            <li class="active">Table</li>
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
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Table List </h3>
                        @if(in_array('mess/table.create', $pageAccessData))
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('mess/create/table')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Create New Table</a>
                        </div>
                        @endif
                    </div>
                    <div class="box-body table-responsive">
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="">Search Person:</label>
                            </div>
                            <div class="col-sm-3">
                                <select name="" class="form-control person-type-search-field">
                                    <option value="">--Person Type--</option>
                                    <option value="1">Cadet</option>
                                    <option value="2">HR/FM</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select name="" class="form-control person-search-field">
                                    <option value="">--Select Person--</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-success table-search-btn">Search</button>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-info table-reset-btn" style="display: none">Reset</button>
                            </div>
                            <div class="col-sm-1">
                                <button class="pull-right btn btn-success print_btn"
                                    value="{{ url('mess/table/print/') }}">Print</button>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px">
                            <div class="col-sm-12 mess-tables-holder table-responsive">
                                {!! $messTableView !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        {{-- Table seat person management modal --}}
        <button style="display: none" type="button" data-toggle="modal" data-target="#messTableSeatModal" id="modal-launch-btn" data-modal-size="modal-md"></button>
        <div class="modal fade" id="messTableSeatModal" tabindex="-1" role="dialog" aria-labelledby="messTableSeatModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="messTableSeatModalLabel" style="float: left">Assign Person to Seat No <b id="modal-seat-no"></b></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <select name="" class="form-control person-type-field">
                                <option value="">--Person Type--</option>
                                <option value="1">Cadet</option>
                                <option value="2">HR/FM</option>
                            </select>
                        </div>
                        <div class="col-sm-7">
                            <select name="" class="form-control person-field">
                                <option value="">--Select Person--</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger person-remove-btn">Remove</button>
                  <button type="button" class="btn btn-primary person-assign-btn">Assign</button>
                </div>
              </div>
            </div>
          </div>
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
           // Print table
           $('.print_btn').click(function() {
                var uri = $(this).val();
                window.open(uri, '_blank');
            })
        $('#prescriptionTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('#select-user').select2();
        $('.person-search-field').select2();
        $('.person-field').select2({
            width: 100
        });

        $(document).on('change', '#create-select-house', function () {
            if ($(this).val()) {
                $('#create-select-position').attr('disabled', 'disabled');
            } else {
                $('#create-select-position').removeAttr('disabled');
            }
        });

        $(document).on('change', '#edit-select-house', function () {
            if ($(this).val()) {
                $('#edit-select-position').attr('disabled', 'disabled');
            } else {
                $('#edit-select-position').removeAttr('disabled');
            }
        });

        var selectedTableId = null;
        var selectedSeatNo = null;
        var messTableSeats = {!! $messTableSeats !!}

        function makeAssignSeatFieldsNull(){
            $('.person-type-field').val('');
            $('.person-field').html('<option value="">--Select Person--</option>');
            $('.person-field').select2();
        }

        function getMessTableSeats() {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/mess/table/seats') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    messTableSeats = data;
                }
            });
            // Ajax Request End
        }

        function getPersonsFromType(type) {
            var batch = '';
            var section = '';
            var personId = 0;
            
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/persons/from/personType') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'type': type,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Select Person--</option>';

                    data.forEach(element => {
                        if (type == 1) {
                            if(element.status===0){
                                console.log(element)
                            }
                            personId = element.std_id;
                            batch = (element.single_batch)?element.single_batch.batch_name:'';
                            section = (element.single_section)?element.single_section.section_name:'';
                        } else if (type == 2) {
                            personId = element.id;
                            batch = (element.single_department)?element.single_department.name:'';
                            section = (element.single_designation)?element.single_designation.name:'';
                        }
                        txt += '<option value="'+personId+'">'+element.first_name+' '+element.last_name+' ('+element.single_user.username+'), '+batch+', '+section+'</option>';
                    });

                    $('.person-field').html(txt);
                    $('.person-field').select2();
                }
            });
            // Ajax Request End
        }

        function setAssignSeatFieldsData(){
            var flag = null;

            messTableSeats.forEach(element => {
                if (element.mess_table_id == selectedTableId && element.seat_no == selectedSeatNo) {
                    flag = element;

                    $('.person-type-field').val(element.person_type);
                    getPersonsFromType(element.person_type);

                    var batch = '';
                    var section = '';
                    var personId = 0;

                    // Ajax Request Start
                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/mess/get/person/details') }}",
                        type: 'GET',
                        cache: false,
                        data: {
                            '_token': $_token,
                            'type': element.person_type,
                            'personId': element.person_id
                        }, //see the _token
                        datatype: 'application/json',
                    
                        beforeSend: function () {},
                    
                        success: function (data) {
                            if (element.person_type == 1) {
                                personId = data.std_id;
                                batch = (data.single_batch)?data.single_batch.batch_name:'';
                                section = (data.single_section)?data.single_section.section_name:'';
                            } else if (element.person_type == 2) {
                                personId = data.id;
                                batch = (data.single_department)?data.single_department.name:'';
                                section = (data.single_designation)?data.single_designation.name:'';
                            }
                            $('.person-field').append('<option value="'+personId+'" selected>'+data.first_name+' '+data.last_name+' ('+data.single_user.username+'), '+batch+', '+section+'</option>');
                            $('.person-field').select2();
                        }
                    });
                    // Ajax Request End

                }
            });

            return flag;
        }

        function refreshMessTables() {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/mess/table/view') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    $('.mess-tables-holder').html(data);
                    $('.person-type-search-field').val('');
                    $('.person-search-field').html('<option value="">--Select Person--</option>');
                    $('.table-reset-btn').css('display', 'none');
                }
            });
            // Ajax Request End
        }

        $(document).on('click', '.mess-table-seat', function () {
            selectedTableId = $(this).data('table-id');
            selectedSeatNo = $(this).data('seat-no');

            makeAssignSeatFieldsNull();
            var person = setAssignSeatFieldsData();

            if (person) {
                $('.person-remove-btn').css('display', 'inline-block');
            } else{
                $('.person-remove-btn').css('display', 'none');                
            }

            $('#modal-seat-no').text(selectedSeatNo);
            $('#modal-launch-btn').click();
        });

        $('.person-assign-btn').click(function () {
            var personType = $('.person-type-field').val();
            var personId = $('.person-field').val();

            if (personType && personId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/mess/assign/person/to/seat') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'tableId': selectedTableId,
                        'seatNo': selectedSeatNo,
                        'personType': personType,
                        'personId': personId
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        if (data == 1) {
                            $('#messTableSeatModal').modal('hide');
                            makeAssignSeatFieldsNull();
                            refreshMessTables();
                            getMessTableSeats();
                        } else{
                            swal('Error!', "Error assigning person.", 'error');
                        }
                    }
                });
                // Ajax Request End
            }else{
                swal('Error!', 'Fill up all the fields first.', 'error');
            }
        });

        $('.person-remove-btn').click(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/remove/person/from/seat') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'tableId': selectedTableId,
                    'seatNo': selectedSeatNo
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    if (data == 1) {
                        $('#messTableSeatModal').modal('hide');
                        makeAssignSeatFieldsNull();
                        refreshMessTables();
                        getMessTableSeats();
                    } else{
                        swal('Error!', "Error removing person.", 'error');
                    }
                }
            });
            // Ajax Request End
        });

        $('.person-type-field').click(function () {
            getPersonsFromType($(this).val());
        });

        $('.person-type-search-field').click(function () {
            var type = $(this).val();
            var batch = '';
            var section = '';
            var personId = 0;
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/all/persons/from/personType') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'type': type,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Select Person--</option>';

                    data.forEach(element => {
                        if (type == 1) {
                           if(element.status==1){

                               personId = element.std_id;
                               batch = (element.single_batch)?element.single_batch.batch_name:'';
                               section = (element.single_section)?element.single_section.section_name:'';
                           }

                        } else if (type == 2) {
                            personId = element.id;
                            batch = (element.single_department)?element.single_department.name:'';
                            section = (element.single_designation)?element.single_designation.name:'';
                        }
                        txt += '<option value="'+personId+'">'+element.first_name+' '+element.last_name+' ('+element.single_user.username+'), '+batch+', '+section+'</option>';
                    });

                    $('.person-search-field').html(txt);
                }
            });
            // Ajax Request End
        });

        $('.table-search-btn').click(function () {
            var type = $('.person-type-search-field').val();
            var personId = $('.person-search-field').val();

            if (type && personId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/mess/search/table/by/person') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'type': type,
                        'personId': personId
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        $('.mess-tables-holder').html(data);
                        $('.table-reset-btn').css('display', 'inline-block');
                    }
                });
                // Ajax Request End
            }else{
                swal('Error!', 'Select person type and person first.', 'error');
            }            
        });

        $('.table-reset-btn').click(function () {
            refreshMessTables();
        });
    });
</script>
@stop