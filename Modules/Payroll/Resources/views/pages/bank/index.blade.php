@extends('layouts.master')
@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Bank</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Salary Head</li>
            </ul>
        </section>

        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('error'))
                <div id="w0-success-0" class="alert-danger alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-times"></i> {{ Session::get('error') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> View Bank</h3>
                        <a class="btn btn-success btn-sm pull-right" href="/payroll/bank/create"
                           oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal"
                           data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i>
                            Add Bank</a>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="box-header"></div>
                        <div class="box-body">
                            <table id="example1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Bank Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @foreach($bankNames as $item)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->bank_name}}</td>
                                        <td>
                                            <a href="/payroll/bank/edit/{{$item->id}}" class="btn btn-info btn-sm"
                                               data-toggle="modal" data-target="#globalModal"><i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> View Bank Branch</h3>
                        <a class="btn btn-success btn-sm pull-right" href="/payroll/bank/branch/create"
                           oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal"
                           data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i>
                            Add Bank Branch</a>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="box-header"></div>
                        <div class="box-body">
                            <table id="example1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Branch Name</th>
                                    <th>Bank Name</th>
                                    <th>Branch Location</th>
                                    <th>Branch Mobile</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @foreach($branchName as $item)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->branch_name}}</td>
                                        <td>{{$item->bankName->bank_name}}</td>
                                        <td>{{$item->branch_location}}</td>
                                        <td>{{$item->branch_phone}}</td>
                                        <td>
                                            <a href="/payroll/bank/branch/edit/{{$item->id}}" class="btn btn-info btn-sm"
                                               data-toggle="modal" data-target="#globalModal"><i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>

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
        function salaryHeadDelete(id) {
            var c = confirm("Are You sure, you want to delete?")
            if (c) {
                var token = "{{ csrf_token() }}";
                var dataSet = '_token=' + token + '&id=' + id;
                $.ajax({
                    url: "{{ url('payroll/salary/delete')}}",
                    type: 'post',
                    data: dataSet,
                    beforeSend: function () {
                    }, success: function (data) {
                        // console.log(data);
                        location.reload();
                    }
                });
            }
        }

        function showData(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token=' + token + '&id=' + rowId;
            $.ajax({
                url: "{{ url('payroll/emp-salary-assign/show')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#globalModal').html(data);
                }
            });
        }

        function editData(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token=' + token + '&id=' + rowId;
            $.ajax({
                url: "{{ url('payroll/emp-salary-assign/edit')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#globalModal').html(data);
                }
            });
        }

        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });
    </script>
@endsection