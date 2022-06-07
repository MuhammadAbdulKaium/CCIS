<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadet Detail Transcript Report</title>
    <style>
        body{
            font-size: 12px;
        }
        .page-break {
            page-break-after: always;
        }
        .header-top{
            text-align: center;
        }
        .header-bottom{
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img{
            width: 100px;
            height: auto;
        }
        table{
            width: 100%;
        }
        .block{
            margin-top: 20px;
        }
        .table-bordered{
            border-collapse: collapse;
            text-align: center;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #ddd;
            padding: 4px;
        }
        .signature-area{
            margin-top: 30px;
        }
        .date{
            text-align: left;
        }
        .signature{
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="block">
        <div class="header clearfix">
            <div class="header-top">
                <div class="logo">
                    <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
                    {{-- <img src="{{ asset('assets/users/images/'.$institute->logo) }}" alt=""> --}}
                </div>
            </div>
            <div class="header-bottom">
                <h3>TRANSCRIPT OF RECORDS OF CADET COLLEGE PROGRAMMES</h3>
                <h3>{{ $institute->institute_name }}</h3>
            </div>
            <div class="cadet-info">
                <table>
                    <tbody>
                        <tr>
                            <td><b>Cadet Name: </b>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td><b>First Name: </b>{{ $student->first_name }}</td>
                            <td><b>Cadet No: </b>{{ $student->username }}</td>
                        </tr>
                        <tr>
                            <td><b>Sex: </b>{{ $student->gender }}</td>
                            <td><b>Date Of Birth: </b>{{ $studentInfo->dob }}</td>
                            <td><b>Place Of Birth: </b>{{ $studentInfo->birth_place }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Co-Curricular Performance --}}
    <div class="block">
        <h4>Co-Curricular Performance: </h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Event</th>
                    <th>Point</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coCurricularMarks as $coCurricularMark)
                    @php
                        $activityPoints = $coCurricularMark[0]->event->activity->activityPoint;
                        $avgMark = $coCurricularMark->avg('mark');
                        $value = $activityPoints->firstWhere('point', round($avgMark));
                    @endphp
                    <tr>
                        <td>{{ $loop->index+1  }}</td>
                        <td>{{ $coCurricularMark[0]->event->event_name  }}</td>
                        <td>{{ $avgMark }}</td>
                        <td>{{ $value->value }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No Co-Curricular Performance found!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Extra-Curricular Performance --}}
    <div class="block">
        <h4>Extra-Curricular Performance: </h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Event</th>
                    <th>Point</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($extraCurricularMarks as $extraCurricularMark)
                    @php
                        $activityPoints = $extraCurricularMark[0]->event->activity->activityPoint;
                        $avgMark = $extraCurricularMark->avg('mark');
                        $value = $activityPoints->firstWhere('point', round($avgMark));
                    @endphp
                    <tr>
                        <td>{{ $loop->index+1  }}</td>
                        <td>{{ $extraCurricularMark[0]->event->event_name  }}</td>
                        <td>{{ $avgMark }}</td>
                        <td>{{ $value->value }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No Extra-Curricular Performance found!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Psychology --}}
    <div class="block">
        <h4>Psychology</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Category</th>
                    <th>Mark</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($psychologyMarks as $psychologyMark)
                    @php
                        $totalMark = 0;
                        $i = 0;

                        if ($psychologyMark[0]->total_point) {
                            $avgMark = $psychologyMark->avg('total_point');
                            $value = null;
                        } else{
                            foreach ($psychologyMark as $key => $mark) {
                                $totalMark += $mark->activity_point()->point;
                                $i++;
                            }
                            $avgMark = ($totalMark)?$totalMark/$i:0;
                            $activityPoints = $psychologyMark[0]->performance_activity()->activityPoint;
                            $value = $activityPoints->firstWhere('point', round($avgMark));
                        }
                    @endphp
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{ $psychologyMark[0]->performance_category()->category_name }}</td>
                        <td>{{ round($avgMark) }}</td>
                        <td>{{ ($value)?$value->value:'' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No psychology marks found!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Discipline --}}
    <div class="block">
        <h4>Discipline: </h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Activity</th>
                    <th>Mark</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($disciplineMarks as $disciplineMark)
                    @php
                        $totalMark = 0;
                        $i = 0;
                        foreach ($disciplineMark as $key => $mark) {
                            $totalMark += $mark->activity_point()->point;
                            $i++;
                        }
                        $avgMark = ($totalMark)?$totalMark/$i:0;
                        $activityPoints = $disciplineMark[0]->performance_activity()->activityPoint;
                        $value = $activityPoints->firstWhere('point', round($avgMark));
                    @endphp

                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $disciplineMark[0]->performance_activity()->activity_name }}</td>
                        <td>{{ round($avgMark) }}</td>
                        <td>{{ $value->value }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No discipline marks found!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Physical Health Records --}}
    <div class="block">
        <h4>Physical Health Records</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Details</th>
                    <th>Mark</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($healthMarks as $healthMark)
                    <tr>
                        <td>{{ $healthMark->remarks }}</td>
                        <td>{{ $healthMark->score }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center">No health reocrds found!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <div class="signature-area">
        <table>
            <tbody>
                <tr>
                    <td class="date">
                        Date: .......................................
                    </td>
                    <td class="signature">
                        .......................................<br>
                        Signature of Principal
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="block" style="margin-top: 80px">
        <p style="text-align: center">Stamp of Institution</p>
        <h3 style="text-align: center">Grading System of Cadet Colleges</h3>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Grade Letter</th>
                    <th>% of Students to normally get the grade</th>
                    <th>Definition</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>A</td>
                    <td>20</td>
                    <td>EXCELLENT - outstanding performance with only minor errors</td>
                </tr>
                <tr>
                    <td>B</td>
                    <td>40</td>
                    <td>GOOD - generally sound work with a number of notable errors</td>
                </tr>
                <tr>
                    <td>C</td>
                    <td>30</td>
                    <td>SATISFACTORY - fair but with significant shortcomings</td>
                </tr>
                <tr>
                    <td>D</td>
                    <td>10</td>
                    <td>SUFFICIENT - performance meets the minimum criteria</td>
                </tr>
                <tr>
                    <td>F</td>
                    <td>--</td>
                    <td>FAIL - considerable further work is required</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>