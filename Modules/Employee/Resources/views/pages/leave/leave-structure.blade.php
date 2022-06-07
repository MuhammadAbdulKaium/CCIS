@extends('layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="glyphicon glyphicon-th-list"></i> Manage |<small>Leave Structure</small></h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/default/index">Human Resource</a></li>
                <li class="active">Leave Management</li>
                <li class="active">Leave Structure</li>
            </ul>
        </section>
        <section class="content">


            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> Leave Structure </h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm full-width-modal" href="/employee/add/leave/structure"
                               data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square "></i> ADD</a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <div class="thead">
                            <tr>
                                <th>#</th>
                                <th>Leave Name</th>
                                <th>Alias</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
{{--                                <th>DOJ Effect</th>--}}
                                <th>Carry Forward</th>
{{--                                <th>Year Closing</th>--}}
{{--                                <th>Year Closing Month</th>--}}
                                <th>Holiday Effect</th>
                                <th>Encashment</th>
                                <th>Encash Type</th>
                                <th>Encash %</th>
                                <th>Action</th>
                            </tr>
                        </div>
                        <tbody>
                        <?php $i=1; ?>
                        @foreach($leaveStructure as $leave)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$leave->leave_name}}</td>
                                <td>{{$leave->leave_name_alias}}</td>
                                <td>
                                    @isset($leave->leave_type)
                                        @foreach($leaveType as $type)
                                            @if($type->id == $leave->leave_type)
                                                {{$type->leave_type_name }}
                                            @endif
                                        @endforeach
                                    @endisset
                                </td>
                                <td>{{$leave->leave_duration }}</td>
{{--                                <td>{{$leave->doj==1?'Yes':'No'}}</td>--}}
                                <td>{{$leave->cf==1?'Yes':'No' }}</td>
{{--                                <td>{{$leave->year_closing==1?'Yes':'No' }}</td>--}}
{{--                                <td>--}}
{{--                                    @if($leave->year_closing_month !=0)--}}
{{--                                        @foreach($monthName as $key=>$month)--}}
{{--                                            @if($key==$leave->year_closing_month)--}}
{{--                                                {{$month}}--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    @else--}}
{{--                                        N/A--}}
{{--                                    @endif--}}
{{--                                </td>--}}
                                <td>{{$leave->holidayEffect==1?'Yes':'No' }}</td>
                                <td>{{$leave->encash==1?'Yes':'No' }}</td>
                                <td>{{$leave->salaryType==1?'Gross':($leave->salaryType==2?'Basic':'N/A') }}</td>
                                <td>{{$leave->salary_type_percentage}}</td>
                                <td>
                                    <a class="btn btn-success btn-sm full-width-modal" href="/employee/edit/leave/structure/{{$leave->id}}"
                                       data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square full-width-modal"></i> Edit</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
{{--    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>--}}
{{--    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>--}}
    <!-- datatable script -->
    <script>
        // $(function () {
        //     $("#example1").DataTable();
        //     $('#example2').DataTable({
        //         "paging": true,
        //         "lengthChange": false,
        //         "searching": false,
        //         "ordering": true,
        //         "info": true,
        //         "autoWidth": false
        //     });
        // });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });

    </script>
@endsection
