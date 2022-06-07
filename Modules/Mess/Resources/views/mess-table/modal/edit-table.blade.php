<form action="{{url('/mess/update/table/'.$messTable->id)}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Edit {{ $messTable->table_name }}
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <label for="">Table Name</label>
                <input type="text" name="tableName" class="form-control" value="{{ $messTable->table_name }}" required>
            </div>
            <div class="col-sm-6">
                <label for="">Concerned HR</label>
                <select name="employeeId" class="form-control select-employee">
                    <option value="">--Concerned HR--</option>
                    @foreach ($employees as $emoployee)
                        <option value="{{$emoployee->id}}" {{ ($messTable->employee_id == $emoployee->id)?'selected':'' }}>{{$emoployee->first_name}} {{$emoployee->last_name}} ({{ $emoployee->singleUser->username }}), 
                            @if ($emoployee->singleDepartment) {{ $emoployee->singleDepartment->name }} @endif,
                            @if ($emoployee->singleDesignation) {{ $emoployee->singleDesignation->name }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-3">
                <label for="">Total seats</label>
                <input type="number" name="totalSeats" min="4" class="form-control" value="{{ $messTable->total_seats }}" required>
                <span class="text-warning">Must be even!</span>
            </div>
            <div class="col-sm-3">
                <label for="">High seats</label>
                <input type="number" name="totalHighSeats" min="2" class="form-control" value="{{ $messTable->total_high_seats }}" required>
                <span class="text-warning">Must be even!</span>
            </div>
            <div class="col-sm-4">
                <label for="">House</label>
                <select name="houseId" class="form-control" id="edit-select-house">
                    <option value="">--Choose House--</option>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}" @if ($messTable->house_id == $house->id) selected @endif>{{ $house->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <label for="">Position</label>
                <select name="position" class="form-control" id="edit-select-position" required @if ($messTable->house_id) disabled @endif>
                    <option value="top" @if ($messTable->table_position == 'top') selected @endif>Top</option>
                    <option value="bottom" @if ($messTable->table_position == 'bottom') selected @endif>Bottom</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left">Update</button>
        <a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('.select-employee').select2();
    });
</script>
