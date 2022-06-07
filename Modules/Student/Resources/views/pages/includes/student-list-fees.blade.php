
{{-- @php  print_r($allEnrollments) @endphp --}}
<div class="col-md-12">
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>
			</div>
		</div>
		<div class="card">
			<form method="post" action="/cadetfees/assign/cadet/fees/">
				@csrf
				@if(isset($searchData))
					@if($searchData->count()>0)
						@php $i=1; @endphp
						<table class="table">
							<thead>
							<tr>
								<th>SL</th>
								<th>Student ID</th>
								<th>Name</th>
								<th>Academic Year</th>
								<th>Academic Level</th>
								<th>Class</th>
								<th>Form</th>
								<th>Roll</th>
								<th>Fees Structure</th>
								@foreach($feesStructureDetailsList as $list)
									<th>{{$list->fees_head}} <a onclick="syncData( {{$list->id}} )" data-toggle="tooltip" data-placement="top" title="Load from Cadet Profile"> <i class="fa fa-refresh fa-2x"> </i> </a></th>
								@endforeach
							</tr>

							</thead>

							<tbody>
							@foreach($searchData as $key=>$data)
								<tr>
									<td>{{$i++}}</td>
									<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->email}}</a></td>
									<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->first_name}} {{$data->last_name}}</a></td>
									<td>@if($data->year()) {{$data->year()->year_name}} @endif</td>
									<td>{{$data->level()->level_name}}</td>
									<td>{{$data->batch()->batch_name}} @if(isset($data->batch()->get_division()->name)) - {{$data->batch()->get_division()->name}}@endif</td>
									<td>{{$data->section()->section_name}}</td>
									<td>{{$data->gr_no}}</td>
									<td>
										<input type="hidden" id="cad_{{$data->std_id}}" name="std_id[]" value="{{$data->std_id}}">
										<input type="hidden" name="structure_id[]" value="{{$feesStructure}}">
										<input type="hidden" id="batch_{{$data->batch()->id}}" name="batch[]" value="{{$data->batch()->id}}">
										<input type="hidden" id="section_{{$data->section()->id}}" name="section[]" value="{{$data->section()->id}}">
										<input type="hidden" id="academic_level_{{$data->academic_level}}" name="academic_level[]" value="{{$data->academic_level}}">
										<input type="hidden" name="academic_year[]" value="{{$academicYearProfile->id}}">
										@foreach($feesStructureList as $structure)
											@if($structure->id == $feesStructure)
												{{$structure->structure_name}}
											@endif
										@endforeach
									</td>
									@foreach($feesStructureDetailsList as $list)
										@isset($checkFeesAssign[$data->std_id])
											@php
												$fees_details=json_decode($checkFeesAssign[$data->std_id]->fees_details,1);
											@endphp
											@isset($fees_details[$list->head_id])
												<td><input type="number" value= "{{$fees_details[$list->head_id]}}" name="head_amount_id[{{$list->head_id}}][]" class="form-control" required disabled></td>
											@else
												<td><input type="number" value= "{{$list->head_amount}}" name="head_amount_id[{{$list->head_id}}][]" class="form-control" required></td>
											@endisset
										@else
											<td><input type="number" data-fees-data="{{$data->singleEnroll->tution_fees}}" value= "{{$list->head_amount}}" name="head_amount_id[{{$list->head_id}}][]" class="form-control tution-fees{{$list->id}}" required></td>
										@endisset
									@endforeach
								</tr>
							@endforeach
							</tbody>
						</table>
						@php $i++; @endphp
					@else
						<h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
					@endif
				@endif
				<button type="submit" class="btn btn-primary" id="assignData">Submit</button>
			</form>
		</div>
	</div>
</div>

<script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
	function syncData(id){
		var tution_fees=$(".tution-fees"+id);
		tution_fees.each((index,fees)=>{
			var fees_value=$(fees).data('fees-data');
			$(fees).val(fees_value);
		})

	}
	$(function () {
		$("#selectAll").click(function () {
			$("input[type=checkbox]").prop("checked", $(this).prop("checked"));

		});

		$("input[type=checkbox]").click(function () {
			if (!$(this).prop("checked")) {
				$("#selectAll").prop("checked", false);
			}
		});

		$('form#cad_assign_submit_form').on('submit', function (e) {
			e.preventDefault();
			// ajax request
			$.ajax({
				url: "/cadetfees/assign/cadet/fees/",
				type: 'POST',
				cache: false,
				data: $('form#cad_assign_submit_form').serialize(),
				datatype: 'application/json',


				beforeSend: function() {
					// show waiting dialog
					waitingDialog.show('Loading...');
				},

				success: function (data) {
					waitingDialog.hide();
					console.log(data);
				},

				error: function (data) {
					alert(JSON.stringify(data));
				}
			});
		});
	})

	$(function () {
		$("#example2").DataTable();
		$('#example1').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false
		});

		// paginating
		$('.pagination a').on('click', function (e) {
			e.preventDefault();
			var url = $(this).attr('href').replace('store', 'find');
			loadRolePermissionList(url);
			// window.history.pushState("", "", url);
			// $(this).removeAttr('href');
		});
		// loadRole-PermissionList
		function loadRolePermissionList(url) {
			$.ajax({
				url: url,
				type: 'POST',
				cache: false,
				beforeSend: function() {
					// show waiting dialog
					waitingDialog.show('Loading...');
				},
				success:function(data){
					// hide waiting dialog
					waitingDialog.hide();
					// checking
					if(data.status=='success'){
						var std_list_container_row = $('#std_list_container_row');
						std_list_container_row.html('');
						std_list_container_row.append(data.html);
					}else{
						alert(data.msg)
					}
				},
				error:function(data){
					alert(JSON.stringify(data));
				}
			});
		}
	});
</script>