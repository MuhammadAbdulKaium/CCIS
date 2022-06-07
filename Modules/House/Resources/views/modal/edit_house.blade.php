<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit {{ $house->name }}</h4>
</div>
<div class="modal-body">
    <form action="{{url('/house/update-house/'.$house->id)}}" method="POST">
        @csrf

        <div class="row">
            <div class="col-sm-6">
                <label for="">House Name</label>
                <input type="text" class="form-control" name="name" value="{{ $house->name }}" required>
            </div>
            <div class="col-sm-6">
                <label for="">House Bengali Name</label>
                <input type="text" class="form-control" name="bengali_name" value="{{ $house->bengali_name }}">
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-5">
                <label for="">House Master</label>
                <select name="employeeId" id="" class="form-control" required>
                    <option value="">--House Master--</option>
                    @foreach ($employees as $employee)
                        <option value="{{$employee->id}}" {{($house->employee_id == $employee->id)?'selected':''}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-5">
                <label for="">House Prefect</label>
                <select name="studentId" id="" class="form-control">
                    <option value="">--House Prefect--</option>
                    @foreach ($students as $student)
                        <option value="{{$student->std_id}}" {{($house->student_id == $student->std_id)?'selected':''}}>{{$student->first_name}} {{$student->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <label for="">No of Floors</label>
                <input type="number" class="form-control" min="1" max="99" name="floors" value="{{ $house->no_of_floors }}" required>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-4">
                <input type="text" class="form-control" name="symbol_name" placeholder="House Symbol" value="{{ $house->symbol_name }}">
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="symbol" placeholder="Symbol icon" value="{{ $house->symbol }}">
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="color_name" placeholder="House Color" value="{{ $house->color_name }}">
            </div>
            <div class="col-sm-2">
                <input type="color" class="form-control" name="color" value="{{ $house->color }}">
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-4">
                <label for="">House Alias</label>
                <input type="text" class="form-control" name="alias" placeholder="House Alias" value="{{ $house->alias }}" required>
            </div>
            <div class="col-sm-4">
                <label for="">House Motto</label>
                <input type="text" class="form-control" name="motto" value="{{ $house->motto }}">
            </div>
            <div class="col-sm-4">
                <label for="">House Bengali Motto</label>
                <input type="text" class="form-control" name="bengali_motto" value="{{ $house->bengali_motto }}">
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-sm-12">
                <button class="btn btn-success">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        
    });
</script>