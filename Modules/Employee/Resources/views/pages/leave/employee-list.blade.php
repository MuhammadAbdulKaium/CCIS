
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
					<th><input id="selectAll" type="checkbox"></th>
					<th>Name</th>
					<th>Emp ID</th>
					<th>Department</th>
					<th>Designation</th>
					<th>DOJ</th>
					<th>Career Age</th>
					@foreach($leaveStructure as $leaves)
					<th>
						{{$leaves->leave_name_alias}} ({{$leaves->leave_duration}})
					</th>
					@endforeach
				</tr>
				</thead>
				<tbody>
				@if($searchData)
				@foreach($searchData as $key =>$data)
					<tr>
						<td>
{{--							<input type="checkbox"  @isset($leaveAssignData[$data->user_id]) checked @endisset class="userCheck" name="userCheckbox[]" id="selectEmp_{{$data->user_id}}" value="{{$data->user_id}}">--}}
							<input type="checkbox" class="userCheck" name="userCheckbox[]" id="selectEmp_{{$data->user_id}}" value="{{$data->user_id}}">
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
									@endphp
								<td>
{{--									<input type="checkbox" @if($leaveData) @if($leaveData->leave_structure_id==$leaves->id) checked @endif @endif id="leave_id_{{$leaves->id}}" class="leave_id" value="{{$leaves->id}}" name="leave_id[{{$data->user_id}}][]">--}}
									<input type="checkbox" id="leave_id_{{$leaves->id}}" class="leave_id" value="{{$leaves->id}}" name="leave_id[{{$data->user_id}}][]">
									<input type="number" value="{{$leaves->leave_duration}}"
										   name="leave_duration[{{$data->user_id}}][{{$leaves->id}}]" class="leave_duration @if(isset($leaveData)) @if ($leaveData->leave_structure_id==$leaves->id) bg-success @endif @endif" id="leave_duration">
									@if(isset($leaveData)) <span class="text-success"> <b>{{$leaveData->leave_remain}} </b></span> @endif
								</td>
						@endforeach
						<td>
							@csrf
							<input type="hidden" name="leaveYear" value="{{$leaveYear}}">
							<input type="hidden" name="emp_id[]" value="{{$data->user_id}}">
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
				<input type="submit" id="assignData" class="btn btn-primary">
			</form>
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
</script>