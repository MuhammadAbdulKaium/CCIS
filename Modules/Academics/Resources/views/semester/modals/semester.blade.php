{{--datepicker style --}}
<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
{{--form--}}
<form action="{{url('/academics/semester')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="semester_id" value="{{$semesterProfile?$semesterProfile->id:0}}">

	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> {{$semesterProfile?'Update':'Add'}} Semester
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="name">Name:</label>
					<input type="text" class="form-control" id="name" name="name" value="{{$semesterProfile?$semesterProfile->name:''}}" required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="start_day">Start Day:</label>
					<input type="number" max="31" min="1" class="form-control" id="start_day" name="start_day" value="{{$semesterProfile?$semesterProfile->start_day:''}}" required>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="start_month">Start Month:</label>
					<input type="number" max="12" min="1" class="form-control" id="start_month" name="start_month" value="{{$semesterProfile?$semesterProfile->start_month:''}}" required>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="end_day">End Day:</label>
					<input type="number" max="31" min="1" class="form-control" id="end_day" name="end_day" value="{{$semesterProfile?$semesterProfile->end_day:''}}" required>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="end_month">End Month:</label>
					<input type="number" max="12" min="1" class="form-control" id="end_month" name="end_month" value="{{$semesterProfile?$semesterProfile->end_month:''}}" required>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Submit</button>
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>
{{--datepicker script--}}
<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
{{--scripts--}}
<script type="text/javascript">
    $(function() { // document ready

        //From Date picker
        $('#start_date').datepicker({
            autoclose: true
        });

        // To Date picker
        $('#end_date').datepicker({
            autoclose: true
        });
    });
</script>