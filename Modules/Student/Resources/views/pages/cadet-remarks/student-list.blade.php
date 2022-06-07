<form action="{{ url('/student/give/remarks') }}" method="POST">
    @csrf

    <input type="hidden" name="yearId" value="{{ $yearId }}">
    <input type="hidden" name="termId" value="{{ $termId }}">

    <table class="table" id="student-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Photo</th>
                <th>Cadet Number</th>
                <th>Name</th>
                <th>Blood Group</th>
                <th>Admission Year</th>
                <th>Class</th>
                <th>Form</th>
                <th>Roll</th>
                <th>Score</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                @php
                    $prevRemark = $previousRemarks->firstWhere('student_id', $student->std_id);
                @endphp

                <input type="hidden" name="studentIds[]" value="{{$student->std_id}}">

                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>
                        @if($student->singelAttachment("PROFILE_PHOTO"))
                            <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                        @else
                            <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                        @endif
                    </td>
                    <td><a href="/student/profile/personal/{{$student->std_id}}" target="_blank">{{$student->email}}</a></td>
                    <td><a href="/student/profile/personal/{{$student->std_id}}" target="_blank">{{$student->first_name}} {{$student->last_name}}</a></td>
                    <td>{{$student->student()->blood_group}}</td>
                    <td>{{$student->year()->year_name}}</td>
                    <td>{{$student->batch()->batch_name}} @if(isset($student->batch()->get_division()->name)) - {{$student->batch()->get_division()->name}}@endif</td>
                    <td>{{$student->section()->section_name}}</td>
                    <td>{{$student->gr_no}}</td>
                    <td>
                        <input type="number" class="form-control" name="scores[{{ $student->std_id }}]" value="{{ ($prevRemark)?$prevRemark->score:'' }}" required>
                    </td>
                    <td>
                        <textarea name="remarks[{{ $student->std_id }}]" class="form-control" rows="1" style="min-width: 400px" required>{{ ($prevRemark)?$prevRemark->remarks:'' }}</textarea>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="50" class="text-center"> <h3>Sorry!!! No Result Found</h3></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(in_array('student/give/remarks', $pageAccessData))
    <button class="btn btn-success" style="float: right">Save</button>
    @endif
</form>

<script>
    $(document).ready(function () {

    });
</script>