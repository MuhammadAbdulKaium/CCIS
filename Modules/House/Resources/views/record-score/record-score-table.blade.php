@if (sizeof($recordScores) > 0)
<form action="{{ url('/house/update/record-score') }}" method="POST">
    @csrf

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL</th>
                <th>Admission Year</th>
                <th>Academic Year</th>
                <th>Photo</th>
                <th>Cadet ID</th>
                <th>Cadet Name</th>
                <th>Bengali Name</th>
                <th>Blood</th>
                <th>Class</th>
                <th>Form</th>
                <th>Term</th>
                <th>Cateogry</th>
                <th>Date</th>
                <th>Add Score</th>
                <th>Remarks</th>
                @if(in_array('house/record-score.history', $pageAccessData))
                <th>History</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($recordScores as $recordScore)
                @if ($loop->index == 0)
                    <input type="hidden" name="houseId" value="{{ $recordScore->house_id }}">
                    <input type="hidden" name="yearId" value="{{ $recordScore->academic_year_id }}">
                    <input type="hidden" name="semesterId" value="{{ $recordScore->semester_id }}">
                @endif
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $recordScore->admissionYear->year_name }}</td>
                    <td>{{ $recordScore->academicYear->year_name }}</td>
                    <td>
                        @if($recordScore->student->singelAttachment("PROFILE_PHOTO"))
                            <img src="{{URL::asset('assets/users/images/'.$recordScore->student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width: 50px">
                        @else
                            <img src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px">
                        @endif
                    </td>
                    <td>
                        {{ $recordScore->student->singleUser->username }}
                        <input type="hidden" name="studentIds[]" value="{{ $recordScore->student->std_id }}">
                    </td>
                    <td>{{ $recordScore->student->first_name }} {{ $recordScore->student->last_name }}</td>
                    <td>{{ $recordScore->student->bn_fullname }}</td>
                    <td></td>
                    <td>{{ $recordScore->student->singleBatch->batch_name }}</td>
                    <td>{{ $recordScore->student->singleSection->section_name }}</td>
                    <td>{{ $recordScore->term->name }}</td>
                    <td>
                        <select name="categoryIds[{{ $recordScore->student->std_id }}]" class="form-control" required>
                            <option value="">--Category--</option>
                            <option value="0" {{ ($recordScore->category_id == 0)?'selected':'' }}>Overall</option>
                            @foreach ($performanceTypes as $performanceType)
                                <option value="{{ $performanceType->id }}" {{ ($recordScore->category_id == $performanceType->id)?'selected':'' }}>{{ $performanceType->performance_type }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>{{ $recordScore->date }}</td>
                    <td>
                        <input type="number" name="scores[{{ $recordScore->student->std_id }}]" class="form-control" value="{{ $recordScore->score }}">
                    </td>
                    <td>
                        <textarea name="remarks[{{ $recordScore->student->std_id }}]" class="form-control" rows="1">{{ $recordScore->remarks }}</textarea>
                    </td>
                    @if(in_array('house/record-score.history', $pageAccessData))
                    <td>
                        <a class="btn btn-xs btn-primary" href="{{ url('/house/record-score/history/'.$recordScore->student->std_id) }}" 
                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">History</a>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @if(in_array('house/update/record-score', $pageAccessData))
    <button class="btn btn-success" style="float: right">Save</button>
    @endif
</form>
@else
    <div style="text-align: center">No Cadets Found</div>
@endif


