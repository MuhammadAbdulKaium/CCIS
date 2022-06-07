@extends('layouts.master')

@section('styles')
    <style>
        .select2-selection--single {
            height: 33px !important;
        }

        .drug-tooltip {
            position: relative;
        }

        .drug-tooltip-details {
            background: #FBF7AA;
            padding: 6px;
            border-radius: 3px;
            position: absolute;
            right: 0;
            min-width: 300px;
            display: none;
            z-index: 5;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
        }

        .drug-tooltip-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .drug-tooltip-details table,
        .drug-tooltip-details td,
        .drug-tooltip-details th {
            border: 1px solid black;
            padding: 1px 2px;
        }

        .tooltip-open-text {
            cursor: pointer;
        }

        .drug-tooltip-cross-btn {
            float: right;
            color: red;
            cursor: pointer;
        }

        .tooltip-left-content {
            float: left;
            width: 70%;
        }

        .tooltip-right-content {
            float: right;
            width: 30%;
        }

    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Drug Reports</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Health Care</a></li>
                <li>SOP Setup</li>
                <li class="active">Drug Reports</li>
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
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Drug Reports
                            </h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered" id="prescriptionTable">
                                <thead>
                                    <tr>
                                        <th>Pr. Id</th>
                                        <th>BarCode</th>
                                        <th>Date Time</th>
                                        <th>Patient Type</th>
                                        <th>Patient Name</th>
                                        <th>Drug Name</th>
                                        <th>Required Qty</th>
                                        <th>Disbursed Qty</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drugReports as $drugReport)
                                        <tr>
                                            <td>{{ ($drugReport->prescription)?$drugReport->prescription->barcode:" " }}</td>
                                            <td>
                                                @if ($drugReport->prescription)
                                                    @if ($drugReport->prescription->barcode)
                                                        
                                                    {!! DNS1D::getBarcodeHTML($drugReport->prescription->barcode, 'C39E', 1, 30) !!}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ Carbon\Carbon::parse($drugReport->created_at)->format('d/m/Y, g:i a') }}
                                            </td>
                                            @if ($drugReport->patient_type == 1)
                                                <td>Cadet</td>
                                                <td>
                                                    <div class="drug-tooltip">
                                                        <span
                                                            class="tooltip-open-text">{{ $drugReport->cadet->first_name }}
                                                            {{ $drugReport->cadet->last_name }}
                                                            ({{ $drugReport->cadet->singleUser->username }})</span>
                                                        <div class="drug-tooltip-details">
                                                            <span class="drug-tooltip-cross-btn">X</span>
                                                            <h5><b>Cadet Details:</b></h5>
                                                            <div class="tooltip-content">
                                                                <div class="tooltip-left-content">
                                                                    <ul>
                                                                        <li><b>ID:
                                                                            </b>{{ $drugReport->cadet->singleUser->username }}
                                                                        </li>
                                                                        <li><b>Name:
                                                                            </b>{{ $drugReport->cadet->first_name }}
                                                                            {{ $drugReport->cadet->last_name }}</li>
                                                                        <li><b>Academic Level: </b>
                                                                            @if ($drugReport->cadetProfile->singleLevel)
                                                                                {{ $drugReport->cadetProfile->singleLevel->level_name }}
                                                                            @endif
                                                                        </li>
                                                                        <li><b>Class: </b>
                                                                            @if ($drugReport->cadetProfile->singleBatch)
                                                                                {{ $drugReport->cadetProfile->singleBatch->batch_name }}
                                                                            @endif
                                                                        </li>
                                                                        <li><b>Form: </b>
                                                                            @if ($drugReport->cadetProfile->singleSection)
                                                                                {{ $drugReport->cadetProfile->singleSection->section_name }}
                                                                            @endif
                                                                        </li>
                                                                        <li><b>Batch No:
                                                                            </b>{{ $drugReport->cadet->batch_no }}</li>
                                                                        <li><b>DOB: </b>{{ $drugReport->cadet->dob }}
                                                                        </li>
                                                                        <li><b>Religion:
                                                                            </b>{{ $drugReport->cadet->religion }}</li>
                                                                        <li><b>Blood Group:
                                                                            </b>{{ $drugReport->cadet->blood_group }}
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="tooltip-right-content">
                                                                    @if ($drugReport->cadetProfile->singelAttachment('PROFILE_PHOTO'))
                                                                        <img class="center-block img-thumbnail img-responsive"
                                                                            src="{{ URL::asset('assets/users/images/' . $drugReport->cadetProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name) }}"
                                                                            alt="No Image" style="width:50px;height:50px">
                                                                    @else
                                                                        <img class="center-block img-thumbnail img-responsive"
                                                                            src="{{ URL::asset('assets/users/images/user-default.png') }}"
                                                                            alt="No Image" style="width:50px;height:50px">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @elseif($drugReport->patient_type == 2)
                                                <td>HR/FM</td>
                                                <td>
                                                    <div class="drug-tooltip">
                                                        <span
                                                            class="tooltip-open-text">{{ $drugReport->employee->first_name }}
                                                            {{ $drugReport->employee->last_name }}
                                                            ({{ $drugReport->employee->singleUser->username }})</span>
                                                        <div class="drug-tooltip-details">
                                                            <span class="drug-tooltip-cross-btn">X</span>
                                                            <h5><b>Employee Details:</b></h5>
                                                            <div class="tooltip-content">
                                                                <div class="tooltip-left-content">
                                                                    <ul>
                                                                        <li><b>ID:
                                                                            </b>{{ $drugReport->employee->singleUser->username }}
                                                                        </li>
                                                                        <li><b>Name:
                                                                            </b>{{ $drugReport->employee->first_name }}
                                                                            {{ $drugReport->employee->last_name }}</li>
                                                                        <li><b>Department: </b>
                                                                            @if ($drugReport->employee->singleDepartment)
                                                                                {{ $drugReport->employee->singleDepartment->name }}
                                                                            @endif
                                                                        </li>
                                                                        <li><b>Designation: </b>
                                                                            @if ($drugReport->employee->singleDesignation)
                                                                                {{ $drugReport->employee->singleDesignation->name }}
                                                                            @endif
                                                                        </li>
                                                                        <li><b>DOB: </b>{{ $drugReport->employee->dob }}
                                                                        </li>
                                                                        <li><b>DOJ: </b>{{ $drugReport->employee->doj }}
                                                                        </li>
                                                                        <li><b>DOR: </b>{{ $drugReport->employee->dor }}
                                                                        </li>
                                                                        <li><b>Religion:
                                                                            </b>{{ $drugReport->employee->religion }}
                                                                        </li>
                                                                        <li><b>Blood:
                                                                            </b>{{ $drugReport->employee->blood_group }}
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="tooltip-right-content">
                                                                    @if ($drugReport->employee->singelAttachment('PROFILE_PHOTO'))
                                                                        <img class="center-block img-thumbnail img-responsive"
                                                                            src="{{ URL::asset('assets/users/images/' . $drugReport->employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name) }}"
                                                                            alt="No Image" style="width:60px;height:auto">
                                                                    @elseif($drugReport->employee->category == 1)
                                                                        <img class="center-block img-thumbnail img-responsive"
                                                                            src="{{ URL::asset('assets/users/images/user-teaching.png') }}"
                                                                            alt="No Image" style="width:60px;height:auto">
                                                                    @elseif($drugReport->employee->category == 2)
                                                                        <img class="center-block img-thumbnail img-responsive"
                                                                            src="{{ URL::asset('assets/users/images/user-non-teaching.png') }}"
                                                                            alt="No Image" style="width:60px;height:auto">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <div class="drug-tooltip">
                                                    <span
                                                        class="tooltip-open-text">{{ $drugReport->drug->product_name }}</span>
                                                    <div class="drug-tooltip-details">
                                                        <span class="drug-tooltip-cross-btn">X</span>
                                                        <h5><b>Product Details:</b></h5>
                                                        <ul>
                                                            <li><b>Stock Name: </b>{{ $drugReport->drug->product_name }}
                                                            </li>
                                                            <li><b>SKU: </b>{{ $drugReport->drug->sku }}</li>
                                                            <li><b>Barcode: </b><img
                                                                    src="data:image/png;base64,{{ DNS1D::getBarcodePNG($drugReport->drug->barcode, 'C39', 1, 33, [0, 0, 0], true) }}"
                                                                    alt="barcode" width="90" height="30" /></li>
                                                            <li><b>QR Code: </b><?php echo DNS2D::getBarcodeHTML($drugReport->drug->qrcode, 'QRCODE', 2, 2); ?></li>
                                                            <li><b>Alias: </b>{{ $drugReport->drug->alias }}</li>
                                                            <li><b>Group:
                                                                </b>{{ $drugReport->drug->stockGroup->stock_group_name }}
                                                            </li>
                                                            <li><b>Category:
                                                                </b>{{ $drugReport->drug->stockCategory->stock_category_name }}
                                                            </li>
                                                            <li><b>Item Type:
                                                                </b>{{ $drugReport->drug->item_type == 1 ? 'General Goods' : 'Finished Goods' }}
                                                            </li>
                                                            <li><b>Remarks:
                                                                </b>{{ $drugReport->drug->additional_remarks }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $drugReport->required_quantity }}</td>
                                            <td>
                                                @if ($drugReport->disbursed_quantity > 0)
                                                    <div class="drug-tooltip">
                                                        <span
                                                            class="tooltip-open-text">{{ $drugReport->disbursed_quantity }}</span>
                                                        <div class="drug-tooltip-details">
                                                            <span class="drug-tooltip-cross-btn">X</span>
                                                            <h5><b>Disbursed In:</b></h5>
                                                            <ul>
                                                                @foreach ($drugReport->details as $reportDetails)
                                                                    <li>{{ $reportDetails->disburse_qty }}pcs -
                                                                        {{ Carbon\Carbon::parse($reportDetails->created_at)->format('d/m/Y, g:i a') }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{ $drugReport->disbursed_quantity }}
                                                @endif
                                            </td>
                                            <td>{{ $drugReport->required_quantity - $drugReport->disbursed_quantity }}
                                            </td>
                                            <td>
                                                @if ($drugReport->status == 1)
                                                    Pending
                                                @elseif($drugReport->status == 2)
                                                    Delivered
                                                @elseif($drugReport->status == 3)
                                                    Partially Delivered
                                                @endif
                                            </td>
                                            <td>{{ $drugReport->createdBy->name }}</td>

                                            <td>
                                                @if (in_array('healthcare/drug.deliver', $pageAccessData))
                                                    <a class="btn btn-success btn-xs"
                                                        href="{{ url('/healthcare/drug/deliver/modal/' . $drugReport->id) }}"
                                                        data-target="#globalModal" data-toggle="modal"
                                                        data-modal-size="modal-md"><i class="fa fa-paper-plane"></i></a>
                                                    {{-- <a href="{{ url('/healthcare/drug/status/change/'.$drugReport->id.'/2') }}" class="btn btn-primary btn-xs"><i class="fa fa-paper-plane"></i></a> --}}
                                                @endif
                                                <a href="{{ url('/healthcare/edit/prescription/' . $drugReport->prescription->id) }}"
                                                    class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
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

            $('#select-user').select2();

            $('.tooltip-open-text').click(function() {
                $(this).next().css('display', 'block');
            });

            $('.drug-tooltip-cross-btn').click(function() {
                $(this).parent().css('display', 'none');
            });
        });
    </script>
@stop
