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
            @if(in_array('student/warnings', $pageAccessData))
            <th>Action</th>
                @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($students as $student)
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
                @if(in_array('student/warnings', $pageAccessData))
                <td>


                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-md btn-danger" id="warn-btn" data-std-id="{{ $student->std_id }}"> Warn </button>

                </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="50" class="text-center"> <h3>Sorry!!! No Result Found</h3></td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    $(document).ready(function () {

    });
</script>