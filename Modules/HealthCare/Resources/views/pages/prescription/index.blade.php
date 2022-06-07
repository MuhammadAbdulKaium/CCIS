@extends('layouts.master')

@section('styles')
    <style>
        .select2-selection--single {
            height: 33px !important;
        }

        .select2,
        .select2-containe {
            width: 100% !important;
        }

        .cadetshowHide,
        .hrshowHide {
            display: none;
        }

    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Prescription</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Health Care</a></li>
                <li>SOP Setup</li>
                <li class="active">Prescription</li>
            </ul>
        </section>
        <section class="content">
            @if (Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    @if (in_array('healthcare/prescription.create', $pageAccessData))
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i>
                                    Create Prescription </h3>
                            </div>
                            <div class="box-body">
                                <form action="{{ url('/healthcare/create/prescription') }}" method="get">
                                    @csrf

                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label for="">User Type</label>
                                            <select name="userType" id="select-user-type" class="form-control">
                                                <option value="">--Prescription For--</option>
                                                <option value="1">Cadet</option>
                                                <option value="2">HR/FM</option>
                                            </select>
                                            @error('userType')
                                                <div class="text-danger" style="font-weight: bold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-2 cadetshowHide">
                                            <label for="">House</label>
                                            <select name="house_id" id="house_id" class="form-control">
                                                <option value="">--House--</option>
                                                @foreach ($houses as $house)
                                                    <option value="{{ $house->id }}">{{ $house->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2 cadetshowHide">
                                            <label for="">Class</label>
                                            <select name="batch" id="batch_id" class="form-control">
                                                <option value="">--Class--</option>
                                                @foreach ($batchs as $batch)
                                                    <option value="{{ $batch->id }}"> {{ $batch->batch_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2 cadetshowHide">
                                            <label for="">Form</label>
                                            <select name="section" id="section_id" class="form-control">
                                                <option value="">--Form--</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2 hrshowHide">
                                            <label for="">Department</label>
                                            <select name="department" id="department_id" class="form-control">
                                                <option value="">--Department--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"> {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2 hrshowHide">
                                            <label for="">Designation</label>
                                            <select name="designation" id="designation_id" class="form-control">
                                                <option value="">--Designation--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}"> {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="">Select User</label>
                                            <select name="userId" id="select-user" class="form-control">
                                                <option value="">--User--</option>
                                            </select>
                                            @error('userId')
                                                <div class="text-danger" style="font-weight: bold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="">Follow Up Prescription Id</label>
                                            <input type="text" name="followUpId" class="form-control"
                                                placeholder="Follow Up Prescription Id">
                                        </div>
                                        <div class="col-sm-1" style="margin-top: 25px;">
                                            <button class="btn btn-success">Create Prescription</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Prescription
                                List </h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered" id="prescriptionTable">
                                <thead>
                                    <tr>
                                        <th>Pr. ID</th>
                                        <th>Barcode</th>
                                        <th>Patient Type</th>
                                        <th>House</th>
                                        <th>Patient Name</th>
                                        <th>Patient ID</th>
                                        <th>Issued Time</th>
                                        <th>Status</th>
                                        <th>Creator</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prescriptions as $prescription)
                                        <tr>
                                            <td>{{ $prescription->barcode }}</td>
                                            <td>
                                               
                                                @if ($prescription->barcode)
                                                    {!! DNS1D::getBarcodeHTML($prescription->barcode, 'C39E', 1, 30) !!}
                                                @endif
                                             
                                            </td>
                                            @if ($prescription->patient_type == 1)
                                                <td>Cadet</td>
                                                <td>{{ ($prescription->singleHouse)?$prescription->singleHouse->name:"" }}</td>
                                                <td>{{ $prescription->cadet->first_name }}
                                                    {{ $prescription->cadet->last_name }}</td>
                                                <td>{{ $prescription->cadet->singleuser->username }}</td>
                                            @elseif($prescription->patient_type == 2)
                                                <td>HR/FM</td>
                                                <td>{{ $prescription->house }}</td>
                                                <td>{{ $prescription->employee->first_name }}
                                                    {{ $prescription->employee->last_name }}</td>
                                                <td>{{ $prescription->employee->singleuser->username }}</td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y - g:i a') }}
                                            </td>
                                            <td>
                                                @if ($prescription->status == 1)
                                                    Pending
                                                @elseif($prescription->status == 2)
                                                    Admitted
                                                @elseif($prescription->status == 3)
                                                    Closed
                                                @endif
                                            </td>
                                            <td>{{ $prescription->createdBy->name }}</td>
                                            <td>
                                                @if (in_array('healthcare/prescription.edit', $pageAccessData))
                                                    <a href="{{ url('/healthcare/edit/prescription/' . $prescription->id) }}"
                                                        class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                                @endif
                                                @if (in_array('healthcare/prescription.delete', $pageAccessData))
                                                    <a href="{{ url('/healthcare/delete/prescription/' . $prescription->id) }}"
                                                        class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Are you sure to Delete?')"
                                                        data-placement="top" data-content="delete"><i
                                                            class="fa fa-trash-o"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        </section>
    </div>
@endsection



{{-- Scripts --}}

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#prescriptionTable').DataTable();

            $('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
            $('#batch_id').change(function() {

                // Ajax Request Start
                $_token = "{{ csrf_token() }}";

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/healthcare/cadet/search-section') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'batch': $(this).val()
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success: function(data) {
                        var txt = '<option value="">Select Section*</option>';
                        data.forEach(element => {
                            txt += '<option value="' + element.id + '">' + element
                                .section_name + '</option>';
                        });

                        $('#section_id').empty();
                        $('#section_id').append(txt);


                    }
                });
                // Ajax Request End
            });
            $('#select-user').select2();
            $('#designation_id').select2();
            $('#department_id').select2();

            function getCadetUser(userType = " ", designation_id = "", department_id = "", section_id = "",
                batch_id = "", house_id = "") {
                var userType = $('#select-user-type').val();
                var designation_id = $("#designation_id").val();
                var department_id = $("#department_id").val();
                var section_id = $("#section_id").val();
                var batch_id = $("#batch_id").val();
                var house_id = $("#house_id").val();
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/healthcare/users/from/user-type') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'userType': userType,
                        'department': department_id,
                        'designation': designation_id,
                        'batch': batch_id,
                        'section': section_id,
                        'house_id': house_id
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success: function(data) {
                        if (userType == 1) {
                            txt = '<option value="">--Cadet--</option>';
                        } else if (userType == 2) {
                            txt = '<option value="">--HR/FM--</option>';
                        } else {
                            txt = '<option value="">--User--</option>';
                        }

                        data.forEach(element => {
                            txt += '<option value="' + element.id + '">ID: ' + element
                                .single_user.username + ' - ' + element.first_name +
                                ' ' + element.last_name + '</option>';
                        });
                        // console.log(data);
                        $('#select-user').html(txt);
                        $('#select-user').select2();
                    }
                });
                // Ajax Request End
            }

            $('#select-user-type').change(function() {
                var userType = $(this).val();
                getCadetUser();
                if (userType == 1) {
                    $('.cadetshowHide').show();
                    $('.hrshowHide').hide();
                    $("#house_id").val("");
                    $("#batch_id").val("");
                    $("#section_id").val("");
                } else if (userType == 2) {
                    $('.hrshowHide').show();
                    $('.cadetshowHide').hide();
                    $("#designation_id").val("");
                    $("#department_id").val("");
                    $('#designation_id').select2();
                    $('#department_id').select2();
                } else {
                    $('.cadetshowHide').hide();
                    $('.hrshowHide').hide();
                }

            });
            $("#designation_id").change(function() {

                getCadetUser();
            })
            $("#department_id").change(function() {
                getCadetUser();
            })
            $("#section_id").change(function() {
                getCadetUser();
            })
            $("#batch_id").change(function() {
                var userType = $("#select-user-type").val();
                var house_id = $("#house_id").val();
                var batch_id = $(this).val();
                getCadetUser(userType, designation_id = "", department_id = "", section_id = "", batch_id,
                    house_id);
            })
            $("#house_id").change(function() {
                getCadetUser();
            })

        });
    </script>
@stop
