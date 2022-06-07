@extends('layouts.master')

@section('styles')
    <style>
        body{
            padding-right: 0 !important;
        }

        .evaluation-table, .evaluation-table thead tr th, .evaluation-table tbody tr th{
            text-align: center;
            vertical-align: middle;
        }

        .nude-button{
            border: none;
            background: none;
        }

        .nude-button:hover{
            color: black;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> House |<small>Assign Students</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">House</a></li>
            <li>SOP Setup</li>
            <li class="active">Assign Students</li>
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
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Assign Students </h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control select-house">
                                    <option value="">--House--</option>
                                    @foreach ($houses as $house)
                                        <option value="{{$house->id}}">{{$house->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success search-house-table-btn">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 house-table-holder table-responsive">

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

        <div class="modal fade assign-students-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content assign-students-modal-content">
                    
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
        var houseId = null;
        var batchId = null;
        var sectionId = null;

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

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        function getHouseTable(hId, bId, sId, msg) {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/assign/students/search/house') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'houseId': hId,
                    'batchId': bId,
                    'sectionId': sId,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
                    $('.house-table-holder').html(data);
                    $('#bulk-students-field').select2({
                        placeholder: "All Cadets"
                    });
                    if (msg) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Cadet updated successfully!'
                        });
                    }
                },

                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        }

        $('.search-house-table-btn').click(function () {
            houseId = $('.select-house').val();

            if (houseId) {
                // show waiting dialog
                waitingDialog.show('Loading...');
                getHouseTable(houseId, batchId, sectionId, false);
            }else{
                swal('Error', 'Please Fill Up the required fields first!', 'error');
            }
        });

        $(document).on('click', '.assign-std-modal-btn', function () {
            var stdId = $(this).data('std-id');
            var roomId = $(this).data('room-id');
            var bedNo = $(this).data('bed-no');

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/assign/students/modal') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'stdId': stdId,
                    'roomId': roomId,
                    'bedNo': bedNo,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
                    $('.assign-students-modal-content').html(data);
                    $('.select-cadet').select2({
                        width: '100%',
                    });
                    $('.assign-students-modal').modal('show');
                    console.log(data);
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        function getSectionsFromBatch(batchId, callback) {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/find-sections/from-batch') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batchId': batchId,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                },
            
                success: function (data) {
                    var options = "";
                    data.forEach(item => {
                        options += '<option value="'+item.id+'">'+item.section_name+'</option>';
                    });
                    callback(options);
                },
            
                error: function (error) {
                    console.log(error);
                }
            });
            // Ajax Request End
        }

        $(document).on('change', '.select-batch', function () {
            getSectionsFromBatch($(this).val(), options => {
                var defaultOption = '<option value="">--Form--</option>';
                $('.select-section').html(defaultOption+options);            
            });
        });

        $(document).on('change', '#bulk-batch-field', function () {
            getSectionsFromBatch($(this).val(), options => {
                var defaultOption = '<option value="">--All Forms--</option>';
                $('#bulk-section-field').html(defaultOption+options);            
            });
        });

        function getStudentsFromSection(sectionId, callback) {
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/find-students/from-section') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'sectionId': sectionId
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '';
                    data.forEach(element => {

                        txt += '<option value="' + element.std_id + '">' + element
                            .first_name + ' '+element.last_name+' ('+ element.single_user.username +')</option>';
                    });
                    callback(txt);
                },

                error: function (error) {}
            });
        }

        $(document).on('change', '.select-section', function () {
            getStudentsFromSection($(this).val(), options => {
                var defaultOption = '<option value="">--Choose Cadet--</option>';
                $('.select-cadet').html(defaultOption+options);
            });
        });

        $(document).on('change', '#bulk-section-field', function () {
            getStudentsFromSection($(this).val(), options => {
                $('#bulk-students-field').html(options);
                $('#bulk-students-field').select2({
                    placeholder: "All Cadets"
                });
            });
        });

        $(document).on('click', '.assign-student-btn', function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/assign-student/to-bed') }}",
                type: 'GET',
                cache: false,
                data: $('form#student_assign_form').serialize(), //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    if (data==2) {
                        swal('Error','This student is house prefect can not change.',"error");
                    }
                    getHouseTable(houseId, batchId, sectionId, true);
                    $('.assign-students-modal').modal('hide')
                    console.log(data);
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        $(document).on('click', '.remove-student-btn', function () {
            console.log("Hello");
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/remove-student/from-bed') }}",
                type: 'GET',
                cache: false,
                data: $('form#student_assign_form').serialize(), //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    if (data==2) {
                        swal('Error','This student is house prefect can not remove.',"error");
                    }
                    getHouseTable(houseId, batchId, sectionId, true);
                    $('.assign-students-modal').modal('hide')
                    console.log(data);
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        $(document).on('click', '#bulk-student-assign-btn', function () {
            batchId = $('#bulk-batch-field').val();
            sectionId = $('#bulk-section-field').val();
            var allRoomCheck = $('.select-room');
            var noRoomChecked = true;

            allRoomCheck.each((index, item) => {
                if ($(item).is(':checked')) {
                    noRoomChecked = false;
                }
            });

            if (batchId) {
                if (!noRoomChecked) {
                    // Ajax Request Start
                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/house/bulk/assign-students/to-bed') }}",
                        type: 'GET',
                        cache: false,
                        data: $('form#bulk-student-assign-form').serialize(), //see the _token
                        datatype: 'application/json',
                    
                        beforeSend: function () {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },
                    
                        success: function (data) {
                            getHouseTable(houseId, batchId, sectionId, true);
                            console.log(data);
                        },
                    
                        error: function (error) {
                            // hide waiting dialog
                            waitingDialog.hide();
                    
                            console.log(error);
                        }
                    });
                    // Ajax Request End
                } else {
                    swal('Error!', 'You must select at least one room first!', 'error');
                }
            } else {
                swal('Error!', 'You must select a class first!', 'error');
            }
            
        });

        $(document).on('click', '#bulk-student-remove-btn', function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/bulk/remove-students/from-bed') }}",
                type: 'GET',
                cache: false,
                data: $('form#bulk-student-assign-form').serialize(), //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    getHouseTable(houseId, batchId, sectionId, true);
                    console.log(data);
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        $(document).on('change', '.select-floor', function () {
            var parent = $(this).parent().parent();
            var roomCheckboxes = parent.find('.select-room');

            if ($(this).is(":checked")) {
                roomCheckboxes.prop('checked', true);
            } else {
                roomCheckboxes.prop('checked', false);
            }
        });
    });
</script>
@stop