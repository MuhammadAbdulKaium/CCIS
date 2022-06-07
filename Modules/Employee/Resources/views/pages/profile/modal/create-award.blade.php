
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Award
    </h4>
</div>
<form action="{{url('/employee/profile/create/award/'.$employeeInfo->id)}}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{$employeeInfo->id}}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-7">
                <label for="">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-sm-5">
                <label for="">Date</label>
                <input name="awarded_on" class="form-control select-date" required placeholder="Select Date" size="10" type="text">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <label for="">Awarded By</label>
                <select name="awarded_by_employee" class="awarded-by form-control">
                    <option value="">--Select--</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->first_name }} 
                            {{ $employee->last_name }}
                            ({{ $employee->singleUser->username }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="">Awarded By Custom</label>
                <input type="text" name="awarded_by" class="form-control">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-12">
                <label for="">Awarded Description</label>
                <textarea name="description" id="" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-5">
                <label for="">Attachment</label>
                <input type="file" name="attachment" class="form-control">
            </div>
            <div class="col-sm-7">
                <label for="">Remarks</label>
                <input type="text" name="remarks" class="form-control">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right">Create</button>
    </div>
</form>

<script type="text/javascript">
    $('.select-date').datepicker();
    $('.awarded-by').select2();
</script>