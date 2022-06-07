<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title"> <i class="fa fa-info-circle"></i> Leave Application</h4>
</div>

<form id="leave-application-form" method="POST" action="{{url('/employee/leave/application/store')}}">
	<input name="_token" value="{{csrf_token()}}" type="hidden">
{{--	@if(!empty($employeeProfile))--}}
{{--		<input name="emp_id" value="{{$employeeProfile->user_id}}" type="hidden">--}}
{{--	@endif--}}
{{--{{$allEmployee}}--}}
	<div class="modal-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group field-libraryborrowtransaction-name required">
					<label class="control-label" for="libraryborrowtransaction-name">Employee Name</label>
					<input class="form-control" id="employee" name="employee_name" type="text" value="" placeholder="Type Employee Name">
					<input id="emp_id" name="emp_id" type="hidden" value="" required>
					<div class="help-block" id="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="leave_type">Leave Type</label>
					<select id="leave_type" class="form-control" name="leave_structure_id" required>
						<option value="" selected disabled>--- Select Leave Type ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-5">
				<div class="form-group">
					<label class="control-label" for="req_start_date">Start Date</label>
					<input id="start_date" class="form-control date-picker" name="req_start_date" type="text" readonly>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label class="control-label" for="req_end_date">End Date</label>
					<input id="end_date" class="form-control date-picker" name="req_end_date" type="text" readonly>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label class="control-label" for="req_for_date">Leave Days</label>
					<input id="leave_days" class="form-control" name="req_for_date" type="text" required readonly>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="leave_reason">Reason for leave</label>
					<textarea id="leave_reason" class="form-control" name="leave_reason" maxlength="255"></textarea>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button data-dismiss="modal" class="btn btn-default pull-left" type="button">Close</button>
		<button type="submit" class="btn btn-success">Submit</button>
	</div>
</form>

<script type="text/javascript">
	// $("form#leave-application-form").on('submit',function (e){
	// 	e.preventDefault();
	// 	$.ajax({
	// 		url:'/employee/leave/application/store',
	//
	// 	})
	// })
	$('#employee').keyup(function() {
		$("#emp_id").val('');
		$("#leave_type").empty();
		console.log($("#emp_id"));
		$(this).autocomplete({
			source: loadFromAjax,
			minLength: 1,

			select: function(event, ui) {
				// Prevent value from being put in the input:
				this.value = ui.item.label;
				// Set the next input's value to the "value" of the item.
				$(this).next("input").val(ui.item.id);
				event.preventDefault();
			}
		});

		/// load student name form
		function loadFromAjax(request, response) {
			var term = $("#employee").val();
			$.ajax({
				url: '/employee/find/employee',
				dataType: 'json',
				data: {
					'term': term
				},
				success: function(data) {
					// you can format data here if necessary
					var text='';
					// alert(data);
					data.forEach(el=>{
						text+='<li class="selectedEmployee" data-id="'+el.id+'">'+el.name+'</li>';
					})
					$("#help-block").html(text);
					if(!$("#employee").val())
					{
						$("#help-block").empty();
					}
				}
			});
		}
	});

	$(document).on('click','.selectedEmployee',function (){
		var id=$(this).data('id');
		var name=$(this).text()
		$("#emp_id").val(id);
		$("#employee").val(name)
		$("#help-block").empty();

		$.ajax({
			url: '/employee/find/leave/structure'+'/'+id,
			dataType: 'json',
			success: function(data) {
				console.log(data)
				// you can format data here if necessary
				var text='<option>'+'---Select---'+'</option>';
				// // alert(data);
				data.forEach(el=>{
					text+='<option value="'+el.leave_structure_id+'">'+el.leave_structure_detail.leave_name+'('+el.leave_remain+')'+'</option>';
				})
				$("#leave_type").html(text);
				if(!$("#employee").val())
				{
					$("#leave_type").empty();
				}
			}
		});
	})
    $(document).ready(function(){

        //Initialize Select2 Elements
        $(".select2").select2();

        //Date picker
        $('.date-picker').datepicker({
            autoclose: true
        });

        // leave days counter
        $('.date-picker').change(function(){
            var start_date = $('#start_date').datepicker('getDate');
            var end_date = $('#end_date').datepicker('getDate');
            // checking
            if($('#start_date').val() && $('#end_date').val()){
                var diffDays = (end_date.getDate()-start_date.getDate());
                // checking
                if(diffDays>0 && diffDays<25){
                    $('#leave_days').val(diffDays+1);
                }else{
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#leave_days').val('');
                    alert('Please enter a valid date');
                }

//                var start = $('#start_date').val();
//                var end = $('#end_date').val();

//                var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
//                var firstDate = new Date(start);
//                var secondDate = new Date(end);
//
//                var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
//                alert(diffDays);
            }
        });


        // validate signup form on key up and submit
        var validator = $("#leave-application-form").validate({
            // Specify validation rules
            rules: {
                employee: {
                    required: true
                },
                leave_type: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true,
                },
                leave_days: {
                    required: true,
                    number:true,
                },

                leave_reason: {
                    required: true,
                    minlength:10,
                    maxlength:100,
                },
            },

            // Specify validation error messages
            messages: {
            },

            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },

            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').addClass('has-success');
            },

            debug: true,
            success: "valid",
            errorElement: 'span',
            errorClass: 'help-block',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {
                form.submit();
            }
        });


        // request for section list using batch and section id
        // jQuery(document).on('change','#employee',function(){
        //     var employee_id = $(this).find('option:selected').val();
        //     // replace employee id
        //     var op = '';
		//
        //     // ajax request
        //     $.ajax({
        //         url: '/employee/find/leave/types/'+employee_id,
        //         type: 'GET',
        //         cache: false,
        //         datatype: 'application/json',
		//
        //         beforeSend: function() {
        //             // statements
        //         },
		//
        //         success:function(data){
		//
        //             op+='<option value="" selected disabled>--- Select Leave Type ---</option>';
        //             for(var i=0;i<data.length;i++){
        //                 op+='<option value="'+data[i].id+'">'+data[i].name+' ('+data[i].days+')</option>';
        //             }
        //             // set value to the academic batch
        //             $('#leave_type').html("");
        //             $('#leave_type').append(op);
        //         },
		//
        //         error:function(){
        //             // statements
        //         },
        //     });
		//
		//
		//
        // });
    });
</script>