<form method="" action="" id="student_assign_form">
    @csrf

    <input type="hidden" name="houseId" value="{{ $room->house_id }}">
    <input type="hidden" name="floorNo" value="{{ $room->floor_no }}">
    <input type="hidden" name="roomId" value="{{ $room->id }}">
    <input type="hidden" name="bedNo" value="{{ $bedNo }}">

    <div class="modal-header">
        <h5 class="modal-title">Assign Student to: <b>{{ $room->name }}</b>, Bed: <b>{{ $bedNo }}</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <label for="">Class*</label>
                <select class="form-control select-batch" name="batchId">
                    <option value="">--Batc
                        h--</option>
                    @foreach ($batches as $ba)
                        <option value="{{ $ba->id }}" @if($batch) @if($batch->id == $ba->id) selected @endif @endif>{{ $ba->batch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="">Form*</label>
                <select class="form-control select-section" name="sectionId">
                    <option value="">--Form--</option>
                    @if ($section)
                        @foreach ($sections as $sec)
                            <option value="{{ $sec->id }}" @if ($section->id == $sec->id) selected @endif>{{ $sec->section_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-12" style="margin-top: 20px">
                <label for="">Cadet*</label>
                <select class="form-control select-cadet" name="studentId">
                    <option value="">--Choose Cadet--</option>
                    @if ($student)
                        @foreach ($students as $stu)
                            <option value="{{ $stu->std_id }}" @if($stu->std_id == $student->std_id) selected @endif>
                                {{ $stu->first_name }} 
                                {{ $stu->last_name }} 
                                ({{ $stu->singleUser->username }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary assign-student-btn">Assign</button>
        @if ($student)
        <button type="button" class="btn btn-danger remove-student-btn">Remove</button>
        @endif
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</form>