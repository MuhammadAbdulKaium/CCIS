<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <p>
    </p><h4>
        <i class="fa fa-plus-square"></i> Create Leave Structure </h4>
    <p></p>
</div>
<form id="leave-structure-form">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Leave Type</label>
                <select name="leave_type" class="form-control">
                    @foreach($leaveType as $type)
                        <option value="{{$type->id}}">{{$type->leave_type_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="Enter Name" name="leave_name">
                <small class="form-text text-muted">Enter Leave Name.</small>
                <small class="form-text leave-name-error text-muted"></small>
            </div>
            <div class="form-group col-md-3">
                <label>Alias</label>
                <input type="text" class="form-control" placeholder="Enter Alias" name="leave_name_alias">
                <small class="form-text text-muted ">Enter Leave Alias.</small>
                <small class="form-text alias-error text-muted"></small>
            </div>
            <div class="form-group col-md-3">
                <label>Day's</label>
                <input type="number" class="form-control" placeholder="Enter Days" name="leave_duration">
                <small class="form-text text-muted">Enter Leave days.</small>
            </div>
        </div>
        <div class="row">
{{--            <div class="form-group col-md-3">--}}
{{--                <div class="form-check">--}}
{{--                    <input class="form-check-input" name="doj" type="checkbox" id="doj" value="1">--}}
{{--                    <label class="form-check-label" for="gridCheck">--}}
{{--                        DOJ Effect--}}
{{--                    </label>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="form-group col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cf" id="cf" value="1">
                    <label class="form-check-label" for="gridCheck">
                        Carry Forward
                    </label>
                </div>
            </div>
{{--            <div class="form-group col-md-3">--}}
{{--                <div class="form-check">--}}
{{--                    <input class="form-check-input" name="year_closing" type="checkbox" id="year_closing" value="1">--}}
{{--                    <label class="form-check-label" for="gridCheck">--}}
{{--                        Year Closing--}}
{{--                    </label>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="form-group col-md-3">--}}
{{--                <select id="month" name="year_closing_month" class="form-control" disabled>--}}
{{--                    @foreach($monthName as $key=>$month)--}}
{{--                    <option value="{{$key}}">{{$month}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <div class="form-check">
                    <input class="form-check-input" name="holidayEffect" type="checkbox" id="holidayEffect" value="1">
                    <label class="form-check-label" for="gridCheck">
                        Include in between Holiday
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <div class="form-check">
                    <input class="form-check-input" name="encash" type="checkbox" id="encash" value="1">
                    <label class="form-check-label" for="gridCheck">
                        Encashment
                    </label>
                </div>
            </div>
            <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="salaryType" id="gross" disabled value="1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Gross
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="salaryType" id="basic" checked disabled value="2">
                <label class="form-check-label" for="flexRadioDefault2">
                    Basic
                </label>
            </div>
            </div>
            <div class="form-group col-md-3">
                <input type="number" name="salary_type_percentage" class="form-control" id="percentage_value" placeholder="%" disabled>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-success">Create</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

    $('#year_closing').click(function () {
        if ($('#year_closing').is(":checked")) {
            // alert('Checked');
            $("#month").prop('disabled', false);
        } else {
            // alert('Not Checked')
            $("#month").prop('disabled', true);

        }
    })
    $('form#leave-structure-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/employee/store/leave/structure",
            type: 'POST',
            cache: false,
            data: $('form#leave-structure-form').serialize(),
            datatype: 'application/json',

            beforeSend: function() {
                // show waiting dialog
                // waitingDialog.show('Loading...');
            },

            success:function(data){
                console.log(data);
                $('#globalModal').modal('hide');
                location.reload();
            },

            error:function(data){
                var formError=data.responseJSON.errors;
                if(formError.leave_name)
                {
                    $(".leave-name-error").text(formError.leave_name);
                    $(".leave-name-error").addClass("text-danger");
                }
                if(formError.leave_name_alias)
                {
                    $(".alias-error").text(formError.leave_name_alias);
                    $(".alias-error").addClass("text-danger");
                }
            }
        });
    })
    $('#encash').click(function () {
        if ($('#encash').is(":checked")) {
            $("#basic").prop('disabled', false);
            $("#gross").prop('disabled', false);
            $("#percentage_value").prop('disabled', false);

        } else {
            $("#basic").prop('disabled', true);
            $("#gross").prop('disabled', true);
            $("#percentage_value").prop('disabled', true);

        }
    })


    $(document).ready(function () {
        $('#leave-structure-form').validate({ // initialize the plugin
            rules: {
                leave_type: {
                    required: true
                },
                leave_name: {
                    required: true
                },
                leave_duration: {
                    required: true
                },
                salary_type_percentage: {
                    required: true
                },
                leave_name_alias: {
                    required: true
                },
            }
        })
    })
</script>
