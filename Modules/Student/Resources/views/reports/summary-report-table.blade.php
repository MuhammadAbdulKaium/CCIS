<style>
.repert-header{
    min-height: 185px;
}
.repert-header img{
    position: absolute;
}
.repert-header h2{
    line-height: 150px;
}
.report-table{
    font-size: 15px;
}
.report-table span{
    margin-right: 50px;
}
</style>

<div class="cadet-summary-report table-responsive">
    <div class="repert-header">
        @if($student->singelAttachment("PROFILE_PHOTO"))
            <img class="img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:150px; float: left;">
        @else
            <img class="img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:150px; float: left;">
        @endif
        <h2 style="text-align: center; width: 100%"><u>BIO-DATA</u></h2>
    </div>
    <table class="table table-bordered report-table" style="margin-top: 20px">
        <tbody>
            <tr>
                <td>Cadet No: {{ $student->singleUser->username }}</td>
            </tr>
            <tr>
                <td><div>Name: {{$student->first_name}} {{$student->last_name}}</div> <div>Cadet Name: {{$student->nickname}}</div></td>
            </tr>
            <tr>
                <td>
                    <span>Class: @if($student->singleBatch) {{$student->singleBatch->batch_name}} @endif</span> 
                    <span>House: @if ($student->roomStudent)
                        @isset($houses[$student->roomStudent->house_id])
                            {{ $houses[$student->roomStudent->house_id]->name }}
                        @endisset
                    @endif</span> 
                    <span>Batch No: {{$student->singleStudent->batch_no}}</span>
                </td>
            </tr>
            <tr>
                <td>Academic Group (If Applicable): </td>
            </tr>
            <tr>
                <td>Academic Performance: </td>
            </tr>
            <tr>
                <td><span>JSC:</span> <span>SSC: </span></td>
            </tr>
            <tr>
                <td>College Fees: @if($student->singleEnroll) {{$student->singleEnroll->tution_fees}} @endif</td>
            </tr>
            <tr>
                <td>Performance in Extra Curricular & Co Curricular Activities: </td>
            </tr>
            <tr>
                <td>Physical Fitness: </td>
            </tr>           
            <tr>
                <td>Discipline State: </td>
            </tr>
            <tr>
                <td>Hobby: </td>
            </tr>
            <tr>
                <td style="text-align: center"><u>Family Information</u></td>
            </tr>
            <tr>
                <td>Father's Info: </td>
            </tr>
            <tr>
                <td>
                    @if ($father)
                        <span>Name: {{ $father->first_name }} {{ $father->last_name }}</span>
                        <span>Occupation: {{ $father->occupation }}</span>
                        <span>Income: {{ $father->income }}</span>
                    @endif    
                </td>
            </tr>
            <tr>
                <td>Mother's Info: </td>
            </tr>
            <tr>
                <td>
                    @if ($mother)
                        <span>Name: {{ $mother->first_name }} {{ $mother->last_name }}</span>
                        <span>Occupation: {{ $mother->occupation }}</span>
                        <span>Income: {{ $mother->income }}</span>
                    @endif   
                </td>
            </tr>
            <tr>
                <td>Total Monthly Income: {{ $totalIncome }}</td>
            </tr>
            @foreach ($brothers as $brother)
                <tr>
                    <td>Brother - {{ $loop->index + 1 }}: </td>
                </tr>
                <tr>
                    <td>
                        @if ($brother)
                            <span>Name: {{ $brother->first_name }} {{ $brother->last_name }}</span>
                            <span>Occupation: {{ $brother->occupation }}</span>
                        @endif   
                    </td>
                </tr>
            @endforeach
            @foreach ($sisters as $sister)
                <tr>
                    <td>Sister - {{ $loop->index + 1 }}: </td>
                </tr>
                <tr>
                    <td>
                        @if ($sister)
                            <span>Name: {{ $sister->first_name }} {{ $sister->last_name }}</span>
                            <span>Occupation: {{ $sister->occupation }}</span>
                        @endif   
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>Present Address: @if($student->singleStudent->presentAddress()) {{$student->singleStudent->presentAddress()->address }} @endif</td>
            </tr>
            <tr>
                <td>Permanent Address: @if($student->singleStudent->permanentAddress()) {{ $student->singleStudent->permanentAddress()->address }} @endif</td>
            </tr>
        </tbody>
    </table>
</div>