
{{-- @php  print_r($allEnrollments) @endphp --}}

<div class="col-md-12">
	<div class="box box-solid">
		@if(count($searchData)>0)
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Employee List</h3>
			</div>
		</div>
		<div class="card">
			<form method="POST" id="emp_assign_submit_form">
			<table class="table">
				<thead>
				<tr>
					<th>SL</th>
					<th>Name</th>
					<th>Emp ID</th>
					<th>Department</th>
					<th>Designation</th>
					<th>DOJ</th>
					<th>Career Age</th>
					@foreach($leaveStructure as $leaves)
					<th>
						{{$leaves->leave_name_alias}}
					</th>
					@endforeach
					<th>Action</th>
				</tr>
				</thead>
				<tbody>

				@if($searchData)
					<input type="hidden" value="{{$searchData}}" class="searchResult" id="searchResult">
				@foreach($searchData as $key =>$data)
					<tr>
						<td>
							{{$key+1}}
						</td>
						<td>{{$data->first_name}} {{$data->last_name}}</td>
						<td>{{$data->user_id}}</td>
						<td>{{$data->department()->name}}</td>
						<td>@if($data->designation()) {{$data->designation()->name}} @else N/A @endif</td>
						<td>{{$data->doj}}</td>
						<td>
							@php
								$date1=date_create(date('y-m-d'));
								$date2=date_create($data->doj);
								$diff=date_diff($date2,$date1);
								echo $diff->format('%y Year %m Month %d Day');
							@endphp
						</td>
						@foreach($leaveStructure as $leaves)
							@php
							$leaveData=isset($leaveAssignData[$data->user_id])?$leaveAssignData[$data->user_id]->firstWhere('leave_structure_id',$leaves->id):null;
							$leaveHistoryData=isset($leaveAssignHistoryData[$data->user_id])?$leaveAssignHistoryData[$data->user_id]->where('leave_structure_id',$leaves->id)->last():null;
							$enjoyedLeave=isset($leaveApplications[$data->user_id])?$leaveApplications[$data->user_id]->where('leave_structure_id',$leaves->id)->sum('approve_for_date'):null;
									@endphp
								<td>
									T: @if(isset($leaveHistoryData)) <span class="text-success"><b>{{$leaveHistoryData->leave_remain}}</b></span> @endif
									E: @if(isset($enjoyedLeave)) <span class="text-success"> <b>{{$enjoyedLeave}} </b></span> @endif
									R: @if(isset($leaveData)) <span class="text-success"> <b>{{$leaveData->leave_remain}} </b></span> @endif
								</td>
						@endforeach
						<td>
							<a href="/employee/single/leave/status/{{$data->user_id}}" class="btn btn-primary">View</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			</form>
			<button id="printData" class="btn btn-primary">Print</button>
				@else
					<h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
				@endif
		</div>
		@else
			<div class="container">
				<h2 class="text-center text-danger">No Employee Found In this Department! Search Again with correct Department/ Designation</h2>
			</div>
		@endif
	</div>
</div>
<style>
	input#leave_duration {
		width: 70px;
	}
</style>
<script>
	$(function (){
		$(".leave_id").click(function (){
			if ($('.leave_id').is(":checked")) {
				$("#leave_duration").prop('disabled', false);
			} else {
				$("#leave_duration").prop('disabled', true);

			}
		})
	})

	$(function () {
		$("#selectAll").click(function() {
			$("input[class=userCheck]").prop("checked", $(this).prop("checked"));
		});

		$("input[type=checkbox]").click(function() {
			if (!$(this).prop("checked")) {
				$("#selectAll").prop("checked", false);
			}
		});
	});
	$(function () {
		$("#printData").click(function() {
			var searchValue = $('#searchResult').val();
			// console.log(searchValue);
			$.ajax({
				url: "{{ url('/employee/leave/status/report/pdf') }}",
				type: 'GET',
				cache: false,
				data: {'searchValue': searchValue }, //see the $_token
				datatype: 'application/json',

				beforeSend: function() {
					// clear std list container
				},

				success:function(data){
					console.log(data);
				},

				error:function(){
					alert(JSON.stringify(data));
				}
			});
		});
	});
</script>