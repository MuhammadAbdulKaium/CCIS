@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
@stop


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-upload"></i> Upload Routes</h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/">User Role Management</a></li>
                <li><a href="/">SOP Setup</a></li>
                <li class="active">Upload Routes</li>
            </ul>
        </section>

        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                                                                                          style="text-decoration:none" data-dismiss="alert"
                                                                                          aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        <section class="content">

            <form id="import-student" action="{{url('/userrolemanagement/upload-routes/post')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-excel-o"></i> Select File</h3>
                    </div><!--./box-header-->

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="file" id="employee_list" name="routeList" title="Browse Excel File" required>
                                    <div class="hint-block">[<b>NOTE</b> : Only upload <b>.xlsx</b> file format.]</div>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="callout callout-info">
                                    <h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
                                    <ol>
                                        <li>Max upload records limit is <strong>300</strong>.</li>
                                        <li>Route import data must match with current application language.</li>
                                    </ol>
                                    <h4><strong><a href="{{url('/download/employee_import.xlsx')}}" download>Click here to download</a></strong>
                                        sample format of import data in <b>XLSX</b> format.
                                    </h4>
                                </div><!--./callout-->
                            </div><!--./col-->
                        </div><!--./row-->
                    </div><!--./box-body-->
                    <div class="box-footer">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                </div><!--./box-->
            </form>
        </section>
    </div>

    <div id="slideToTop" ><i class="fa fa-chevron-up"></i></div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true" style="width:100%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="preview" style="padding-left: 40%;">
                            <i id="icon_msg" class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            <h3 id="msg_up">Uploading...</h3>
                        </div>
                        <div class="progress" style="display:none">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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