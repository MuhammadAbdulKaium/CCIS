
@extends('layouts.master')

@section('styles')
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage | <small>Leave Application</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Human Resource</a></li>
				<li><a href="#">Leave Management</a></li>
				<li class="active">Leave Application</li>
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
				@if($employeeId==null)
					<div class="box box-solid">
						<div class="box-body">
							<h3 class="text-danger">Sorry !!! you are not an employee .No Leave of Application for you</h3>
						</div>
					</div>
				@else
					<div class="box box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">
								<i class="fa fa-list"></i> Your Profile
							</h3>
							<a class="btn btn-success" href="{{url('/employee/leave/application/apply')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Apply Leave</a>
						</div>
						<div class="box-body">
							<div class="col-md-4">
								<table class="table table-borderede table-striped">
									<tbody>
									<tr>
										<th>Name</th>
										<td>{{$employeeProfile->first_name}} {{$employeeProfile->last_name}}</td>
									</tr>
									<tr>
										<th>Emp ID</th>
										<td>{{$employeeProfile->singleUser->username}}</td>
									</tr>
									<tr>
										<th>Department</th>
										<td>{{$employeeProfile->singleDepartment->name}}</td>
									</tr>
									@if($employeeProfile->designation>0)
										<tr>
											<th>Designation</th>
											<td>{{$employeeProfile->singleDesignation->name}}</td>
										</tr>
									@endif
									</tbody>
								</table>
							</div>
							<div class="col-md-2">
							</div>
							<div class="col-md-4">
								<table class="table table-striped">
									<thead>
									<th>Leave Name</th>
									<th>Leave Alias</th>
									<th>Available Leave</th>
									</thead>
									<tbody>
									@foreach($leaveAssign as $leave)
										<tr>
											<td>{{$leave->leaveStructureDetail->leave_name}}</td>
											<td>{{$leave->leaveStructureDetail->leave_name_alias}}</td>
											<td>{{$leave->leave_remain}}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
							@php $i=1; @endphp
							<div class="col-md-12">
								<table class="table table-striped">
									<thead>
									<th>SL</th>
									<th>Date</th>
									<th>Application ID</th>
									<th>Leave Name</th>
									<th>Requested Date</th>
									<th>Requested Days</th>
									<th>Approval Days</th>
									<th>Approval Date</th>
									<th>Enjoyed</th>
									<th>Status</th>
									</thead>
									<tbody>
									@foreach($allLeaveApplications as $leaveApplication)
										<tr>
											<td>{{$i}}</td>
											<td>{{$leaveApplication->applied_date}}</td>
											<td>{{$leaveApplication->id}}</td>
											<td>{{$leaveApplication->leaveStructureName->leave_name}}</td>
											<td>{{$leaveApplication->req_start_date}} - {{$leaveApplication->req_end_date}}</td>
											<td>{{$leaveApplication->req_for_date}}</td>
											<td>{{$leaveApplication->approve_start_date}} - {{$leaveApplication->approve_end_date}}</td>
											<td>{{$leaveApplication->approve_date}}</td>
											<td>{{$leaveApplication->approve_for_date}}</td>
											<td>{{$leaveApplication->status==0?'Pending':($leaveApplication->status==1?'Approved':($leaveApplication->status==2?'Partially Approve':'Reject'))}}</td>
										</tr>
										@php $i++; @endphp
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				@endif

			</section>

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
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
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
