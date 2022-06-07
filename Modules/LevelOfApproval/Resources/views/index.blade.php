@extends('layouts.master')

@section('styles')
    <style>
        .select2-container--default{
            width: 320px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Level Of Approval</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li>Level Of Approval</li>
            <li>SOP Setup</li>
            <li class="active">Level Of Approval</li>
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
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Level Of Approvals </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="level-of-approval-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Module Name</th>
                                    <th>Sub Module Name</th>
                                    <th>Menu Name</th>
                                    <th>Total Steps</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($levelOfApprovals as $levelOfApproval)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $levelOfApproval->module_name }}</td>
                                        <td>{{ $levelOfApproval->sub_module_name }}</td>
                                        <td>{{ $levelOfApproval->menu_name }}</td>
                                        <td>
                                            @isset($approvalLayers[$levelOfApproval->unique_name])
                                                {{ $approvalLayers[$levelOfApproval->unique_name]->count() }}</td>
                                            @else
                                                0
                                            @endisset
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                                href="{{url('/levelofapproval/edit/approval-layers/'.$levelOfApproval->id)}}"
                                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                                    class="fa fa-edit"></i></a>
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
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
        $('#level-of-approval-table').DataTable();
    });
</script>
@stop