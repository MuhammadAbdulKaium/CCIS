@extends('layouts.master')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-eye" aria-hidden="true"></i> View Employee |
                <small>{{$employeeInfo->title." ".$employeeInfo->first_name." ".$employeeInfo->last_name}}</small>
                <small>{{$employeeInfo->user()->username}}</small>
                    <small id="sidebar-toggle" class="p-1 border-1 bg-success" style="cursor:pointer; color: green">Hide
                        Sidebar</small>
            </h1>

            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/employee">Employee</a></li>
                <li><a href="/employee/manage">Manage Employee</a></li>
                <li class="active">{{$employeeInfo->title." ".$employeeInfo->first_name." ".$employeeInfo->last_name}}</li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-3 " id="profile_sidebar" >
                    @include('employee::pages.includes.profile-sidebar')
                </div>
                <div class="col-md-9" id="profile_content">
                    @if(Session::has('success'))
                        <script>
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: '{{ Session::get('success') }}',
                                showConfirmButton: false,
                                timer: 3500
                            })
                        </script>
                        <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in"
                             style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                        </div>
                    @elseif(Session::has('message'))
                        <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in"
                             style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('message') }} </h4>
                        </div>
                    @elseif(Session::has('warning'))
                        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in"
                             style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                        </div>
                    @elseif(Session::has('errorMessage'))
                        <div id="w0-success-0" class="alert-danger alert-auto-hide alert fade in"
                             style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-times"></i> {{ Session::get('errorMessage') }} </h4>
                        </div>
                    @elseif($errors->any())
                        <div id="w0-success-0" class="alert-danger alert-auto-hide alert fade in"
                             style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="emp-profile">
                                <ul id="w1" class="nav-tabs margin-bottom nav">
                                    @if (in_array('employee/profile', $pageAccessData))
                                        <li class="@if($page == 'personal')active @endif"><a
                                                    href="/employee/profile/personal/{{$employeeInfo->id}}">Personal</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/address', $pageAccessData))
                                        <li class="@if($page == 'address')active @endif"><a
                                                    href="/employee/profile/address/{{$employeeInfo->id}}">Address</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/family', $pageAccessData))
                                        <li class="@if($page == 'guardian')active @endif"><a
                                                    href="/employee/profile/guardian/{{$employeeInfo->id}}">Family</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/address', $pageAccessData))
                                        <li class="@if($page == 'others')active @endif"><a href="#">Other Info</a></li>
                                    @endif
                                    @if (in_array('employee/document', $pageAccessData))
                                        <li class="@if($page == 'document')active @endif"><a
                                                    href="/employee/profile/document/{{$employeeInfo->id}}">Documents</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/qualification', $pageAccessData))
                                        <li class="@if($page == 'qualification')active @endif"><a
                                                    href="/employee/profile/qualification/{{$employeeInfo->id}}">Qualification</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/training', $pageAccessData))
                                        <li class="@if($page == 'training')active @endif"><a
                                                href="/employee/profile/training/{{$employeeInfo->id}}">Training</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/transfer', $pageAccessData))
                                        <li class="@if($page == 'transfer')active @endif"><a
                                                href="{{ url('/employee/profile/transfer/'.$employeeInfo->id) }}"> Transfer</a></li>
                                    @endif
                                    @if (in_array('employee/experience', $pageAccessData))
                                        <li class="@if($page == 'experience')active @endif"><a
                                                    href="/employee/profile/experience/{{$employeeInfo->id}}">Experience</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/acr', $pageAccessData))
                                        <li class="@if($page == 'acr')active @endif"><a
                                                href="/employee/profile/acr/{{$employeeInfo->id}}">ACR</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/publication', $pageAccessData))
                                        <li class="@if($page == 'publication')active @endif"><a
                                                href="/employee/profile/publication/{{$employeeInfo->id}}">Publication</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/discipline', $pageAccessData))
                                        <li class="@if($page == 'discipline')active @endif"><a
                                                href="/employee/profile/discipline/{{$employeeInfo->id}}">Disciplinary</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/contribution-board-result', $pageAccessData))
                                        <li class="@if($page == 'contribution-board-result')active @endif"><a
                                                href="/employee/profile/contribution-board-result/{{$employeeInfo->id}}">Contribution Board Result</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/special-duty', $pageAccessData))
                                        <li class="@if($page == 'special-duty')active @endif"><a
                                                href="/employee/profile/special-duty/{{$employeeInfo->id}}">Special Duty</a>
                                        </li>
                                    @endif
                                    @if (in_array('employee/promotion', $pageAccessData))
                                        <li class="@if($page == 'promotion')active @endif"><a
                                            href="{{ url('/employee/profile/promotion/'.$employeeInfo->id) }}">Promotion</a></li>
                                    @endif
                                    @if (in_array('employee/award', $pageAccessData))
                                        <li class="@if($page == 'award')active @endif"><a
                                            href="{{ url('/employee/profile/award/'.$employeeInfo->id) }}">Award</a></li>
                                    @endif
                                    {{-- @if (in_array('employee/profile', $pageAccessData))
                                        <li class="@if($page == 'shifts')active @endif"><a href="">Shift</a></li>
                                    @endif
                                    @if (in_array('employee/profile', $pageAccessData))
                                        <li class="@if($page == 'leave')active @endif"><a href="#">Leave</a></li>
                                    @endif
                                    @if (in_array('employee/profile', $pageAccessData))
                                        <li class="@if($page == 'salary')active @endif"><a href="#">Salary</a></li>
                                    @endif --}}
                                    <li class="@if($page == 'history')active @endif"><a
                                            href="{{ url('/employee/profile/status/history/'.$employeeInfo->id) }}"> History</a></li>
                                </ul>
                                @yield('profile-content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
@endsection

@section('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
            var status=1;
            $("#sidebar-toggle").on("click",function (){
                $("#profile_sidebar").toggle();
                if(status===1) status =0;
                else  status =1;
                if(status===0){
                    $("#profile_content").removeClass('col-md-9');
                    $("#profile_content").addClass("col-md-12");
                    $("#sidebar-toggle").text('Show Sidebar')
                }else{
                    $("#profile_content").addClass('col-md-9');
                    $("#profile_content").removeClass("col-md-12");
                    $("#sidebar-toggle").text('Hide Sidebar')
                }
            })

        });
    </script>
    @yield('profile-scripts')
@endsection
