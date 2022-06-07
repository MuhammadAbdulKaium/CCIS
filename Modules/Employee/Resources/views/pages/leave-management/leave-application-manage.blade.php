
@extends('layouts.master')

@section('styles')
	<style>
		.custom-modal {
			width: 90%;
		}
	</style>
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage | <small>Leave Application </small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Human Resource</a></li>
				<li><a href="#">Leave Management</a></li>
				<li class="active">Leave Entitlement</li>
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

			<section class="content">
				<div class="box box-solid">
					<div class="extraDiv">
						<div class="box-header">
							<h3 class="box-title">
								<i class="fa fa-list-alt"></i> Leave Application
							</h3>
{{--							@if (in_array('employee/leave.application.create', $pageAccessData))--}}
							<div class="box-tools">
								<a class="btn btn-success" href="{{url('/employee/leave/application/create/from/admin')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Create Leave Application</a>
							</div>
{{--							@endif--}}
						</div>
					</div>
					<div class="box-body">
						<div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
							<div id="w1" class="grid-view">
								<table id="example1" class="table table-striped table-bordered text-center">
									<thead>
										<tr>
											<th>SL</th>
											<th>Date</th>
											<th>Employee Name</th>
											<th>Application ID</th>
											<th>Leave Name</th>
											<th>Available Day</th>
											<th>Requested Date</th>
											<th>Requested Days</th>
											<th>Approval Days</th>
											<th>Approval Date</th>
											<th>Enjoyed</th>
											<th>Remains</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									@php $i=1; @endphp
									@foreach($allLeaveApplications as $leaveApplication)
										@if(isset($leaveAssign))
										@php
											$leaveDeatails=$leaveAssign->where('leave_structure_id',$leaveApplication->leave_structure_id)->where('emp_id',$leaveApplication->employee_id)->first();
										@endphp
										@endif
										<tr>
											<td>{{$i}}</td>
											<td>{{$leaveApplication->applied_date}}</td>
											<td>{{$leaveApplication->user->name}}</td>
											<td>{{$leaveApplication->id}}</td>
											<td>{{$leaveApplication->leaveStructureName->leave_name}}</td>
											<td>{{$leaveApplication->available_day}}</td>
											<td>{{$leaveApplication->req_start_date}} - {{$leaveApplication->req_end_date}}</td>
											<td>{{$leaveApplication->req_for_date}}</td>
											<td>{{$leaveApplication->approve_start_date}} - {{$leaveApplication->approve_end_date}}</td>
											<td>{{$leaveApplication->approve_date}}</td>
											<td>{{$leaveApplication->approve_for_date}}</td>
											<td>@if(isset($leaveDeatails)){{$leaveDeatails->leave_remain}}@endif</td>
											<td>{{$leaveApplication->status==0?'Pending':($leaveApplication->status==1?'Approved':($leaveApplication->status==2?'Partially Approve':'Reject'))}}</td>
											<td>
												@isset($leaveListHasApproval[$leaveApplication->id])
													@if($leaveApplication->status==0)
														<a class="btn btn-success" href="{{url('/employee/applied/leave/application/edit/')}}/{{$leaveApplication->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> Edit</a>
													@endif
												@endisset
											</td>
										</tr>
										@php $i++; @endphp
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>



			<div id="leave-entitlement-list-container">
				{{-- leave-entitlement-list will be here --}}
			</div>


			<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
				<div class="modal-dialog custom-modal">
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
		</section>
	</div>
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<!-- datatable script -->
	<script>

        jQuery(document).ready(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": false,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true
            });

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicChange',function(){
                $('#leave-entitlement-list-container').html('');
            });


            // request for section list using batch and section id
            $('form#leave-entitlement-list-search-form').on('submit', function (e) {

                e.preventDefault();

                // ajax request
                $.ajax({
                    url: '/employee/manage/leave/entitlement/search',
                    type: 'GET',
                    cache: false,
                    data: $('form#leave-entitlement-list-search-form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        var list_container =  $('#leave-entitlement-list-container');
                        list_container.html('');
                        list_container.append(data);
                    },

                    error:function(){
                        // statements
                    }
                });
            });
        });

	</script>
@endsection

