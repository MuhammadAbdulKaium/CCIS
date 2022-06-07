@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .list-group{
            margin-bottom: 0 !important;
        }
        .menu-label{
            cursor: pointer;
        }
    </style>
@stop


{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> User Role Management |<small>Roll Permissions</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/">User Role Management</a></li>
                <li><a href="/">SOP Setup</a></li>
                <li class="active">Role Wise Permission</li>
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
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>Role List</h3>
                        <div class="box-tools">
{{--                            <a class="btn btn-success btn-sm" href="" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a></div>--}}
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
                            <table id="myTable" class="table table-striped table-bordered text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_alias">Name</a></th>
                                    <th><a  data-sort="sub_master_alias">Description</a></th>
                                    <th><a>Access Details</a></th>
                                    <th><a>History</a></th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$role->display_name}}</td>
                                        <td>{{$role->description}}</td>
                                        <td>
                                            <a href="{{url('/userrolemanagement/menu-accessibility/role/'.$role->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Accessibility</a>
                                        </td>
                                        <td><a href="#">History</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>		</div>    </div><!-- /.box-body -->
            </div><!-- /.box-->

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

@stop

{{-- Scripts --}}

@section('scripts')
    <script>
        $(document).ready(function () {
            // $('#exampleTable').DataTable();

            $('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });
    </script>
@stop