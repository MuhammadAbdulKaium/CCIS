
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Transfer {{ $employeeInfo->singleUser->username }} To Another Campus
    </h4>
</div>
<form action="{{url('/employee/profile/make/transfer')}}" method="post">
    @csrf

    <input type="hidden" name="employee_id" value="{{$employeeInfo->id}}">

    <div class="modal-body">
        <h4>Transfer To:</h4>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-7">
                <label for="">Institute</label>
                <select name="institute_id" class="form-control select-institute" required>
                    <option value="">--Select--</option>
                    @foreach ($institutes as $institute)
                        <option value="{{ $institute->id }}">{{ $institute->institute_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-5">
                <label for="">Campus</label>
                <select name="campus_id" class="form-control select-campus" required>
                    <option value="">--Select--</option>
                </select>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <label for="">Transfer Date</label>
                <input name="transfer_date" class="form-control select-transfer-date" required placeholder="Select Date" size="10" type="text">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right">Transfer</button>
    </div>
</form>

<script type="text/javascript">
    $('.select-transfer-date').datepicker();
    $('.select-institute').change(function () {
        // Ajax Request Start
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            url: "{{ url('employee/get/campus/from/institute') }}",
            type: 'GET',
            cache: false,
            data: {
                '_token': $_token,
                'employee_id': $(this).val(),
            }, //see the _token
            datatype: 'application/json',
        
            success: function (data) {
                console.log(data);
                var txt = '<option value="">--Select--</option>';
                data.forEach(ele => {
                    txt += '<option value="'+ele.id+'">'+ele.name+'</option>';
                });
                $('.select-campus').html(txt);
            },
        
            error: function (error) {
                console.log(error);
            }
        });
        // Ajax Request End
    });
</script>