<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/11/17
 * Time: 6:03 PM
 */?>
@extends('layouts.master')
@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Salary Structure</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Salary Structure</li>
            </ul>
        </section>

        <section class="content">
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

            <div class="row">
                <form id="salary_structure_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box box-solid" style="overflow: hidden">
                        <div class="col-md-3">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-search"></i> View Salary Structure </h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="box-header"></div>
                                    <div class="box-body">
                                        <?php $i = 1;?>
                                        <table id="example1" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Structure</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($salaryGrades as $data)
                                                <tr>
                                                    @if($SalaryStructures)
                                                        @isset($SalaryStructures[$data->id])
                                                            <td><input type="checkbox" disabled name="structure_id[]"
                                                                       value="{{$data->id}}"></td>
                                                        @else
                                                            <td><input type="checkbox" name="structure_id[]"
                                                                       value="{{$data->id}}"></td>
                                                        @endisset
                                                    @endif
                                                    <td>{{$data->scale_name}} - {{$data->gradeName->grade_name}} </td>
                                                    @if($SalaryStructures)
                                                        @isset($SalaryStructures[$data->id])
                                                            <td>
                                                                <a onclick="editStructure({{$data->id}})"
                                                                   class="btn btn-primary">Edit</a>
                                                                <a href="/payroll/salary/structure/history/{{$data->id}}" class="btn btn-info btn-sm"
                                                                   data-toggle="modal" data-target="#globalModal">History</a>
                                                            </td>
                                                        @endisset
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-search"></i> View Head </h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="box-header"></div>
                                    <div class="box-body">
                                        <?php $i = 1;?>
                                        <table id="example1" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Structure</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($salaryHeads as $data)
                                                <tr>
                                                    <td><input type="checkbox" name="head_id[]" value="{{$data->id}}"
                                                               class="head_structure_name"
                                                               data-id="{{$data->id}}"
                                                               data-custom_name="{{$data->custom_name}}"
                                                               data-type="{{$data->type}}"
                                                               data-calculation="{{$data->calculation}}"
                                                               data-fixed_type="{{$data->fixed_type}}"
                                                               data-placement="{{$data->placement}}"
                                                        >
                                                    </td>
                                                    <td>{{$data->custom_name}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="teacher_list_search_btn" type="submit" class="btn btn-primary">Search</button>
                        <button type="reset" class="btn btn-default pull-left">Reset</button>
                    </div>
                </form>
            </div>
        </section>
        <section class="content">
            <div id="employee_list_container"></div>
            <div id="employee_list_edit_container"></div>
        </section>
        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="modal-content">
                    <div class="modal-body" id="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
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
        let is_edit = false;
        let checkbox = false;
        // request for parent list using batch section id
        $('form#salary_structure_form').on('submit', function (e) {
            $('#employee_list_edit_container').html('');
            e.preventDefault();
            // ajax request
            $.ajax({
                url: '/payroll/manage/head/structure',
                type: 'POST',
                cache: false,
                data: $('form#salary_structure_form').serialize(),
                datatype: 'html',
                // datatype: 'application/json',

                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
                    // refresh attendance container row
                    $('#employee_list_container').html('');
                    $('#employee_list_container').append(data);
                    // $('#example2').DataTable();
                },
                error: function () {
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        });
        $(".head_structure_name").click(function () {
            if (is_edit) {
                if ($(this).is(":checked")) {
                    let head_id = $(this).val();
                    let custom_name = $(this).data("custom_name");
                    let type = $(this).data("type") == 0 ? 'Addition' : 'Deduction';
                    let calculation = $(this).data("calculation") == 1 ? 'Gross' : 'Extra';
                    let fixed_type = $(this).data("fixed_type") == 1 ? 'Fixed' : 'Variable';
                    let placement = $(this).data("placement") == 0 ? 'Automatic' : 'Manual';
                    let newRow = "<tr class='structureRow'><td>" + custom_name + "<input type='hidden' class='head_id' name='head_id[]' value=" + head_id + "></td>+" +
                        "<td><input type='number' name='amount[]' class='form-control' required></td>+" +
                        "<td><input type='text' class='form-control' value=" + type + " readonly></td>+" +
                        "<td><input type='text' class='form-control' value=" + calculation + " readonly></td>+" +
                        "<td><input type='text' class='form-control' value=" + fixed_type + " readonly></td><td><input type='text' class='form-control' value=" + placement + " readonly></td>+" +
                        "<td><input type='number' name='maximum_amt[]' class='form-control' required></td><td><input type='number' name='minimum_amt[]' class='form-control' required></td>" +
                        "<td><input type='text' name='remarks[]' class='form-control'></td></tr>"
                    $(".edit-table tbody").append(newRow);
                }
                if (!$(this).is(":checked")) {
                    console.log('Uncheck');
                    let head_id = $(this).val();
                    removeUncheckedRow(head_id);
                }
            }
        })
        function removeUncheckedRow(id) {
            let tr = $(".edit-table .structureRow");
            tr.each((i, ele) => {
                let head_id = $(ele).find('.head_id').val();
                if (id == head_id) {
                    ele.remove();
                }
            })
        }
        function refreshCheckboxes(checkIds) {
            console.log(checkIds);
            let checkId = $(".head_structure_name");
            checkId.each((i, ele) => {
                let inputId = parseInt($(ele).val());
                if (checkIds.includes(inputId)) {
                    $(ele).prop('checked', true);
                } else {
                    $(ele).prop('checked', false);
                }
            })
        }
        function editStructure(id) {
            $('#employee_list_container').html('');
            is_edit = true;
            checkbox = true;
            $.ajax({
                url: '/payroll/salary/structure/edit/' + id,
                type: 'GET',
                cache: false,
                datatype: 'html',
                // datatype: 'application/json',

                beforeSend: function () {
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },

                success: function (data) {
                    $('#employee_list_edit_container').html('');
                    $('#employee_list_edit_container').append(data.view);
                    refreshCheckboxes(data.salaryStructureIds);
                },
                error: function () {
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        }

        // request for parent list using batch section id

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            $(document).on('submit', 'form#salary_structure_edit_form', function (e) {
                e.preventDefault();
                // // ajax request
                $.ajax({
                    url: '/payroll/salary/structure/update',
                    type: 'POST',
                    cache: false,
                    data: $('form#salary_structure_edit_form').serialize(),
                    datatype: 'html',
                    // datatype: 'application/json',

                    beforeSend: function () {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success: function (data) {
                        console.log(data);
                        swal("success",data.message);
                        location.reload();

                    },
                    error: function (data) {
                        console.log(data);
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });

            $(document).on('submit', 'form#salary_structure_assign_form', function (e) {
                e.preventDefault();
                // // ajax request
                $.ajax({
                    url: '/payroll/salary/structure/store',
                    type: 'POST',
                    cache: false,
                    data: $('form#salary_structure_assign_form').serialize(),
                    datatype: 'html',
                    // datatype: 'application/json',

                    beforeSend: function () {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success: function (data) {
                        console.log(data);
                        // hide waiting dialog
                        // waitingDialog.hide();
                        swal("success",'Successfully Store Structure');
                        location.reload();


                    },
                    error: function (data) {
                        console.log(data);
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });

        });
    </script>
@endsection
