<form action="{{ url('/house/store/communication-record') }}" method="POST">
    @csrf

    <input type="hidden" name="houseId" value="{{ $house->id }}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Add new record
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-5">
                <select name="studentId" class="form-control" id="select-student-modal" required>
                    <option value="">--Cadet--</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->std_id }}">Id:{{ $student->singleUser->username }} - {{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select name="yearId" class="form-control" required>
                    <option value="">--Year--</option>
                    @foreach ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}">{{ $academicYear->year_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select name="mode" class="form-control" required>
                    <option value="">--Mode--</option>
                    <option value="1">Audio</option>
                    <option value="2">Video</option>
                    <option value="3">Letter</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" id="date" class="form-control hasDatepicker"
                        name="date" maxlength="10" placeholder="Date" aria-required="true"
                        size="10" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <label for="">From</label>
                <input type="time" class="form-control" name="fromTime" required>
            </div>
            <div class="col-sm-3">
                <label for="">To</label>
                <input type="time" class="form-control" name="toTime" required>
            </div>
            <div class="col-sm-6">
                <label for="">Communication Topics</label>
                <textarea name="communicationTopics" rows="1" class="form-control" required></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-success pull-right">Add</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#date').datepicker();
        $('#select-student-modal').select2();
    });
</script>
