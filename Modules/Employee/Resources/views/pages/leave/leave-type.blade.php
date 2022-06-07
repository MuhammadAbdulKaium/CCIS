
@extends('layouts.master')

@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="glyphicon glyphicon-th-list"></i> Manage |<small>Leave Type</small>        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/default/index">Human Resource</a></li>
                <li class="active">Leave Management</li>
                <li class="active">Leave Type</li>
            </ul>    </section>
        <section class="content">


        <div class="box box-solid">
                <div class="extraDiv">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> Leave Type
                        </h3>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">    <div id="w1" class="grid-view"><div class="summary">Showing <b>1-1</b> of <b>1</b> item.</div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Leave Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; ?>
                                @foreach($leaveType as $type)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$type->leave_type_name}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
@endsection

@section('scripts')
  <!-- DataTables -->
  <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- datatable script -->
  <script>
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
