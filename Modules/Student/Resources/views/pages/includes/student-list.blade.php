
{{-- @php  print_r($allEnrollments) @endphp --}}
@php
$start=microtime(true);


@endphp
<div class="col-md-12">
	@php

	$count=0;
	@endphp
	@foreach($student_id as $key=>$value)
		@php

			$data=$searchData[$value];
		@endphp
		@if($data->status==$status)
			@php
			if(isset($topCadet)){
    if($topCadet==$count) break;
}

				$count++;
			@endphp

			@endif
		@endforeach

	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Cadet List @if($searchData) ({{$count}})
					@endif</h3>
			</div>
		</div>
		<div class="card table-responsive">
			@if(isset($searchData))
				@if($searchData->count()>0)
					@php $i=1; @endphp
					<table class="table" id="std-table">
						<thead>
						<tr>
							<th>SL</th>
							<th>Photo</th>
							<th>Cadet Number</th>
							<th>Name</th>
							<th>Bengali Name</th>
							<th>House</th>
							<th>DOB</th>
							<th>Blood Group</th>
							<th>Admission Year</th>
							<th>Batch</th>
							<th>Academic Year</th>
							<th>Class</th>
							<th>Form</th>
							<th>Fees</th>
							<th>Guardian</th>
							<th>Parents</th>
							<th>Mobile</th>
							@if(in_array('student/report-summary', $pageAccessData) || in_array('student/class-top', $pageAccessData) || in_array('student/status', $pageAccessData))
								<th>Action</th>
							@endif
						</tr>

						</thead>
						<tbody>

						@foreach($student_id as $key=>$value)
							@php

							$data=$searchData[$value];

							@endphp
							@if($data->status==$status )
								@php
								if(isset($topCadet)){
    								if($topCadet<$i){
    								    break;
    								}
									}


								@endphp
							<tr>
								<td>{{$i}}</td>
								<td>
									{{--								<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$enroll->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">--}}

									@if($data->singelAttachment("PROFILE_PHOTO"))
										<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$data->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
									@else
										<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
									@endif
								</td>
								@if(in_array('student/profile/personal', $pageAccessData))
								<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->email}}</a></td>

								<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->first_name}} {{$data->last_name}}</a></td>
								<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->bn_fullname}}</a></td>
								@else
									<td>{{$data->email}}</td>
									<td>{{$data->first_name}} {{$data->last_name}}</td>
									<td>{{$data->bn_fullname}}</td>

								@endif
								<td>
									@if ($data->roomStudent)
									    @isset($houses[$data->roomStudent->house_id])
										    {{ $houses[$data->roomStudent->house_id]->name }}
									    @endisset
								    @endif
							    </td>

								<td>{{$data->singleStudent->dob}}</td>
								<td>@if($data->singleStudent)
									{{$data->singleStudent->blood_group}}
									@endif
								</td>
								<td> @if($data->enroll()->admissionYear) {{$data->enroll()->admissionYear->year_name}} @endif</td>
								<td>
									@if($data->singleStudent)
										{{$data->singleStudent->batch_no}}
									@endif
									</td>
								<td> @if($data->year()) {{$data->year()->year_name}} @endif</td>
								<td> @if($data->singleBatch) {{$data->singleBatch->batch_name}} {{($data->batch()->get_division())?'- '.$data->batch()->get_division()->name:''}} @endif</td>
								<td> @if($data->singleSection) {{$data->singleSection->section_name}} @endif</td>
								<td>
								@if($data->singleEnroll)
										{{$data->singleEnroll->tution_fees}}
									@endif
								</td>
								<td>
									@php
									$guardian=$data->guardian();
									@endphp
									@if($guardian)
										{{$guardian->first_name}} {{$guardian->last_name}}
									@endif
								</td>
								<td>
									@if($data && $data->studentParents )
										@foreach($data->studentParents as $parent)

											@if($parent->singleGuardian &&  $parent->singleGuardian->type==0)
											M:	{{$parent->singleGuardian->first_name}}
												{{$parent->singleGuardian->last_name}}<br>

											@elseif($parent->singleGuardian &&  $parent->singleGuardian->type==1)
												F: {{$parent->singleGuardian->first_name}}
												{{$parent->singleGuardian->last_name}}
												<br>

											@endif

										@endforeach

									@endif

								</td>
								<td>
									@if($guardian)
										{{$guardian->mobile}}
									@endif
								</td>
								<td>
									@if(in_array('student/status', $pageAccessData))
									<a  href="{{url('/student/status/'.$data->std_id)}}" title="Student Status" data-target="#globalModal" data-toggle="modal">
										<span id="status_{{$data->std_id}}" class="fa fa fa-user-o fa-lg {{$data->status==1?'text-green':'text-red'}}"></span>
									</a>
									@endif
									@if(in_array('student/class-top', $pageAccessData))
										@php $classTopper = $data->classTopper; @endphp
										<a href="{{url('/student/manage/class-top/'.$data->std_id)}}" title="Class Topper" data-target="#globalModal" data-toggle="modal">
											<span id="ct_{{$data->std_id}}" class="fa fa fa-hand-o-up fa-lg {{$classTopper?($classTopper->status==1?'text-red':'text-blue'):'text-blue'}}"></span>
										</a>
									@endif
									@if(in_array('student/report-summary', $pageAccessData))
										<a class="btn btn-xs btn-info" href="{{url('/student/report-summary-single/'.$data->std_id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">S</a>
									@endif
								</td>
							</tr>
							@php $i += 1; @endphp
							@endif
						@endforeach
						</tbody>
					</table>
{{--					<div class="text-center">--}}
{{--						{{ $searchData->appends(Request::only([--}}
{{--                        'search'=>'search',--}}
{{--                        'filter'=>'filter',--}}
{{--                        'academic_level'=>'academic_level',--}}
{{--                        'batch'=>'batch',--}}
{{--                        'section'=>'section',--}}
{{--                        'gr_no'=>'gr_no',--}}
{{--                        'email'=>'email',--}}
{{--                        '_token'=>'_token',--}}
{{--                        ]))->render() }}--}}
{{--					</div>--}}
				@else
					<h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
				@endif
			@endif
		</div>

	</div>
</div>

<script>
	$(function () {
		$("#std-table").DataTable();
		$('#example1').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false
		});

		// paginating
		// $('.pagination a').on('click', function (e) {
		// 	e.preventDefault();
		// 	var url = $(this).attr('href').replace('store', 'find');
		// 	loadRolePermissionList(url);
		// 	// window.history.pushState("", "", url);
		// 	// $(this).removeAttr('href');
		// });
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


		// downlaod student report

//		$('.download').click(function () {
//		    var download_type=$(this).attr("id");
////			alert(download_type);
//
//            $.ajax({
//
//                url: '/student/manage/download/excel/pdf',
//                type: 'POST',
//                cache: false,
//                data: $('form#downlaodStdExcelPDF').serialize()+ "&download_type=" + download_type,
//                datatype: 'json/application',
//
//                beforeSend: function () {
//                    // alert($('form#class_section_form').serialize());
//                    // show waiting dialog
////                    waitingDialog.show('Loading...');
//                },
//
//                success: function (data) {
//                    // hide waiting dialog
////                    waitingDialog.hide();
//					console.log(data);
//
//                },
//
//                error: function (data) {
//                    alert('error');
//                }
//            });
//
//
//        })




	});
</script>
