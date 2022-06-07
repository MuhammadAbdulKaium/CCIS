<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadet Detail Report</title>
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
            width: 150px;
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
    {{-- 1st Page --}}
    <div class="page-break">
        <div class="header clearfix">
            <div class="header-top">
                <div class="logo">
                    <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
                    {{-- <img src="{{ asset('assets/users/images/'.$institute->logo) }}" alt=""> --}}
                </div>
            </div>
            <div class="header-bottom">
                <h3>{{ $institute->institute_name }}</h3>
                <p>Assessment Report</p>
                <p>{{ ($term)?$term->name:'' }} {{ ($exam)?'| '.$exam->exam_name:'' }} {{ ($academicYear)?'| '.$academicYear->year_name:'' }}</p>
            </div>
            <div class="cadet-info">
                <table>
                    <tbody>
                        <tr>
                            <td>Cadet No</td>
                            <td>{{ $student->username }}</td>
                        </tr>
                        <tr>
                            <td>Cadet Name</td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        </tr>
                        <tr>
                            <td>Class: {{ $student->singleBatch->batch_name }}</td>
                            <td>Form: {{ $student->singleSection->section_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 80px">
                <h3 style="text-align: center">Attention : Parents/Guardians</h3>
                <p>The responsibility for the all round development of the cadet is jointly shared
                    by guardians and the college. If parents/guardians have any queries to make
                    about the progress of their child of if they have any information that would give
                    the college a better understanding of their child, the Principal and the staff will
                    appreciate their co-operation in this regard.</p>
            </div>
        </div>
    </div>

    {{-- 2nd Page --}}
    {{-- Academic Transcript --}}
    @isset ($sheetData[$student->std_id])
    <div class="block">
        <table class="table-bordered">
            <caption><h3>Academic Transcript ({{ $exam->exam_name }})</h3></caption>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Subject</th>
                    @foreach($totalExamCatIds as $examCatId => $fullMark)
                        <th>
                            {{ $examCategories[$examCatId]->exam_category_name }} <br>
                            ({{ $fullMark }})
                        </th>
                    @endforeach
                    @foreach($totalCriteriaIds as $criteriaId => $fullMark)
                        <th>
                            {{ $examMarkParameters[$criteriaId]->name }} <br>
                            ({{ $fullMark }})
                        </th>
                    @endforeach
                    <th>Total Marks</th>
                    <th>%</th>
                    <th>Grade Point</th>
                    <th>LG</th>
                    <th>Highest Mark</th>
                    <th>Lowest Mark</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=1;
                @endphp
                @foreach ($subjects as $key => $subjectGroup)
                    @foreach ($subjectGroup as $subject)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $subject['subject_alias'] }}</td>
                            @foreach ($examCategories as $examCategory)
                                @if($termFinalExamCategory->id == $examCategory->id)
                                    @isset($sheetData[$student->std_id][$key][$subject['id']])
                                        @php
                                            $marks = json_decode($subjectMarksExamWise[$subject['id']]->marks, 1);
                                        @endphp
                                        @foreach ($marks['fullMarks'] as $criteriaId => $mark)
                                            @isset($sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['details'][$criteriaId])
                                                <td rowspan="1"  ><span style="color: {{ $sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['details'][$criteriaId]['color'] }}">{{ $sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['details'][$criteriaId]['mark'] }}</span></td>
                                            @else
                                                <td></td>
                                            @endisset
                                        @endforeach
                                    @endisset
                                @else
                                    <td style="color: {{ ($sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['isFail'])?'red':'black' }}">{{ $sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['mark'] }}</td>
                                @endif
                            @endforeach
                            @if ($key)
                                @if ($loop->index == 0)    
                                    @php
                                        $rowspan = sizeof($subjectGroup);
                                    @endphp
                                    @endphp
                                    <td rowspan="{{$rowspan}}" style="color: {{ ($sheetData[$student->std_id][$key]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key]['totalMark'] }}</td>
                                    <td rowspan="{{$rowspan}}" style="color: {{ ($sheetData[$student->std_id][$key]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key]['avgMark'] }}</td>
                                    <td rowspan="{{$rowspan}}" style="color: {{ ($sheetData[$student->std_id][$key]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key]['gradePoint'] }}</td>
                                    <td rowspan="{{$rowspan}}" style="color: {{ ($sheetData[$student->std_id][$key]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key]['grade'] }}</td>
                                    @isset($minMaxSubjectMarks['subjectGroupMarks'][$key])
                                        <td rowspan="{{$rowspan}}">{{$minMaxSubjectMarks['subjectGroupMarks'][$key]['highestMark']}}</td>
                                        <td rowspan="{{$rowspan}}">{{$minMaxSubjectMarks['subjectGroupMarks'][$key]['lowestMark']}}</td>
                                    @endisset
                                @endif
                            @else
                                <td style="color: {{ ($sheetData[$student->std_id][$key][$subject['id']]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key][$subject['id']]['totalMark'] }}</td>
                                <td style="color: {{ ($sheetData[$student->std_id][$key][$subject['id']]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key][$subject['id']]['avgMark'] }}</td>
                                <td style="color: {{ ($sheetData[$student->std_id][$key][$subject['id']]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key][$subject['id']]['gradePoint'] }}</td>
                                <td style="color: {{ ($sheetData[$student->std_id][$key][$subject['id']]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$key][$subject['id']]['grade'] }}</td>
                                @isset($minMaxSubjectMarks['subjectMarks'][$subject['id']])
                                    <td>{{$minMaxSubjectMarks['subjectMarks'][$subject['id']]['highestMark']}}</td>
                                    <td>{{$minMaxSubjectMarks['subjectMarks'][$subject['id']]['lowestMark']}}</td>
                                @endisset
                            @endif
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="{{ sizeof($totalExamCatIds)+sizeof($totalCriteriaIds)+2 }}">Grand Total</td>
                    <td style="color: {{ ($sheetData[$student->std_id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id]['avg'] }}</td>
                    <td style="color: {{ ($sheetData[$student->std_id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id]['gradePoint'] }}</td>
                    <td style="color: {{ ($sheetData[$student->std_id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id]['grade'] }}</td>
                    <td style="color: {{ ($sheetData[$student->std_id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id]['grandTotal'] }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2" colspan="{{ sizeof($totalExamCatIds)+sizeof($totalCriteriaIds)+2 }}">Total Cadets in the class</td>
                    <td colspan="6">Present</td>
                </tr>
                <tr>
                    <td colspan="2">GPA</td>
                    <td colspan="2">Position</td>
                    <td>H.GPA</td>
                    <td>L.GPA</td>
                </tr>
                <tr>
                    <td colspan="{{ sizeof($totalExamCatIds)+sizeof($totalCriteriaIds)+2 }}">{{ sizeof($sheetData) }}</td>
                    <td colspan="2">{{ $sheetData[$student->std_id]['grade'] }}</td>
                    <td colspan="2">{{ $sheetData[$student->std_id]['position'] }}</td>
                    <td>{{ $sheetData[$firstLastStudents['first']]['grade'] }}</td>
                    <td>{{ $sheetData[$firstLastStudents['last']]['grade'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endisset


    {{-- <div class="block">
        <table class="table-bordered">
            <caption><h3>Academic Transcript</h3></caption>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Subject</th>
                    @foreach ($examMarkParameters as $examMarkParameter)
                    @if ($loop->index<3)
                    <th>{{ $examMarkParameter->name }}</th>
                    @endif
                    @endforeach
                    <th>Total Marks</th>
                    <th>%</th>
                    <th>Grade Point</th>
                    <th>LG</th>
                    <th>Highest Mark</th>
                    <th>Lowest Mark</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalMark = 0;
                    $avgMark = 0;
                    $grandTotal = 0;
                @endphp
                @foreach ($examMarks as $subjectKey => $examMark)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $examMark[0]->subject->subject_name }}</td>

                        @foreach ($examMarkParameters as $examMarkParameter)
                            @php
                                $totalMark = 0;
                                $i = 0;

                                foreach($examMark as $eachExamMark){
                                    $breakdownMark = json_decode($eachExamMark->breakdown_mark, 1);
                                    foreach ($breakdownMark as $key => $mark) {
                                        if ($key == $examMarkParameter->id) {
                                            $totalMark += $mark;
                                            $i++;
                                        }
                                    }
                                }
                            @endphp
                            @if($loop->index<3)
                                <td>{{ ($i)?$totalMark/$i:'' }}</td>
                            @endif
                        @endforeach
                        @php
                            $totalMark = $examMark->avg('total_mark');
                            $avgMark = $examMark->avg('on_100');
                            $grandTotal += $totalMark;
                            $grade = $grades->where('min_per', '<=', $avgMark)->where('max_per', '>=', $avgMark)->first();

                            $allStdExamMarks = $allExamMarks[$subjectKey]->groupBy('student_id')->all();

                            $allMarks = [];

                            foreach ($allStdExamMarks as $key => $allStdExamMark) {
                                array_push($allMarks, $allStdExamMark->avg('total_mark'));
                            }
                        @endphp

                        <td>{{ $totalMark }}</td>
                        <td>{{ $avgMark }}</td>
                        <td>@if ($grade) {{ $grade->points }} @endif</td>
                        <td>@if ($grade) {{ $grade->name }} @endif</td>
                        <td>{{ (sizeof($allMarks)>0)?max($allMarks):'' }}</td>
                        <td>{{ (sizeof($allMarks)>0)?min($allMarks):'' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="{{ 3+2 }}">Grand Total</td>
                    <td colspan="2">{{ $grandTotal }}</td>
                    @php
                        $avgGrandTotal = (sizeof($examMarks))?$grandTotal/sizeof($examMarks):0;
                        $grade = $grades->where('min_per', '<=', $avgGrandTotal)->where('max_per', '>=', $avgGrandTotal)->first();
                    @endphp
                    <td>@if ($grade) {{ $grade->points }} @endif</td>
                    <td>@if ($grade) {{ $grade->name }} @endif</td>
                    <td></td>
                    <td></td>
                </tr>

                @php
                    $stdAvgMarks = [];
                    $position = 0;

                    foreach ($allExamMarksGroupByStd as $stdKey => $examMarks) {
                        $stdAvgMarks[$stdKey] = $examMarks->avg('total_mark');
                    }

                    if (sizeof($stdAvgMarks) > 0) {
                        $hGPA = $grades->where('min_per', '<=', max($stdAvgMarks))->where('max_per', '>=', max($stdAvgMarks))->first();
                        $lGPA = $grades->where('min_per', '<=', min($stdAvgMarks))->where('max_per', '>=', min($stdAvgMarks))->first();
                        ksort($stdAvgMarks);
                    }else{
                        $hGPA = null;
                        $lGPA = null;
                    }                 
                    
                    foreach ($stdAvgMarks as $key => $stdAvgMark) {
                        $position++;
                        if ($key == $student->std_id) {
                            break;
                        }
                    }
                @endphp

                <tr>
                    <td rowspan="2" colspan="{{ 3+2 }}">Total Cadets in the class</td>
                    <td colspan="6">Present</td>
                </tr>
                <tr>
                    <td colspan="2">GPA</td>
                    <td colspan="2">Position</td>
                    <td>H.GPA</td>
                    <td>L.GPA</td>
                </tr>
                <tr>
                    <td colspan="{{ 3+2 }}">{{ sizeof($allStudentIds) }}</td>
                    <td colspan="2">@if ($grade) {{ $grade->name }} @endif</td>
                    <td colspan="2">{{ $position }}</td>
                    <td>{{ ($hGPA)?$hGPA->name:'' }}</td>
                    <td>{{ ($lGPA)?$lGPA->name:'' }}</td>
                </tr>
            </tbody>
        </table>
    </div> --}}

    {{-- Co-Curricular Performance --}}
    <div class="block">
        <h4>Co-Curricular Performance</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Mark</th>
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
                        <td>{{ $coCurricularMark[0]->event->event_name }}</td>
                        <td>{{ $avgMark }}</td>
                        <td>{{ $value->value }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Not participated in Co-Curricular Events</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Extra-Curricular Performance --}}
    <div class="block">
        <h4>Extra-Curricular Performance</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Mark</th>
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
                        <td>{{ $extraCurricularMark[0]->event->event_name }}</td>
                        <td>{{ $avgMark }}</td>
                        <td>{{ $value->value }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Not participated in Extra-Curricular Events</td></tr>
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
        <h4>Discipline</h4>
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

    {{-- Cadet Warnings --}}
    <div class="block">
        <h4>Warnings</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>House Master</th>
                    <th>Adjutant</th>
                    <th>Vice Principal</th>
                    <th>Principal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $warnings->where('designation', 'House Master')->count() }}</td>
                    <td>{{ $warnings->where('designation', 'Adjutant')->count() }}</td>
                    <td>{{ $warnings->where('designation', 'Vice Principal')->count() }}</td>
                    <td>{{ $warnings->where('designation', 'Principal')->count() }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Form Master Remarks --}}
    <div class="block">
        <h4>Form Master's Remarks: Academics</h4>
        <table>
            @php
                $remarks = $allRemarks->where('designation', 'frmst');
            @endphp
            <tbody>
                @forelse ($remarks as $remark)
                    <tr>
                        <td><b>Score: </b>{{ $remark->score }}</td>
                        <td><b>Remarks: </b>{{ $remark->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No remarks given by form master.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="signature-area">
            <table>
                <tbody>
                    <tr>
                        <td class="date">
                            Date: .......................................
                        </td>
                        <td class="signature">
                            .......................................<br>
                            Form Master's Signature
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- House Master Remarks --}}
    <div class="block">
        <h4>House Master's Remarks: Social Attributes</h4>
        <table>
            @php
                $remarks = $allRemarks->where('designation', 'howr');
            @endphp
            <tbody>
                @forelse ($remarks as $remark)
                    <tr>
                        <td><b>Score: </b>{{ $remark->score }}</td>
                        <td><b>Remarks: </b>{{ $remark->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No remarks given by House master.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="signature-area">
            <table>
                <tbody>
                    <tr>
                        <td class="date">
                            Date: .......................................
                        </td>
                        <td class="signature">
                            .......................................<br>
                            House Master's Signature
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Medical Officer Remarks --}}
    <div class="block">
        <h4>Medical Officer's Remarks:</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Days in hospital</th>
                    <th>Causes</th>
                    <th>Score</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($healthMarks as $healthMark)
                    @php
                        $diagnosis = json_decode($healthMark->content, 1)['diagnosis'];
                    @endphp
                    <tr>
                        <td>{{ $loop->index+1  }}</td>
                        <td>{{ $healthMark->days_in_hospital  }}</td>
                        <td>
                            @if ($diagnosis)
                                @foreach ($diagnosis as $item)
                                    {{$item}}
                                @endforeach
                            @endif    
                        </td>
                        <td>{{  $healthMark->score }}</td>
                        <td>{{  $healthMark->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No remarks given by Medical officer.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="signature-area">
            <table>
                <tbody>
                    <tr>
                        <td class="date">
                            Date: .......................................
                        </td>
                        <td class="signature">
                            .......................................<br>
                            Medical officer's Signature
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Adjutant's Remarks --}}
    <div class="block">
        <h4>Adjutant's Remarks: Discipline, Games & Sports</h4>
        <table>
            @php
                $remarks = $allRemarks->where('designation', 'adj');
            @endphp
            <tbody>
                @forelse ($remarks as $remark)
                    <tr>
                        <td><b>Score: </b>{{ $remark->score }}</td>
                        <td><b>Remarks: </b>{{ $remark->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No remarks given by Adjutant.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="signature-area">
            <table>
                <tbody>
                    <tr>
                        <td class="date">
                            Date: .......................................
                        </td>
                        <td class="signature">
                            .......................................<br>
                            Adjutant's Signature
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Vice Principal's Remarks --}}
    <div class="block">
        <h4>Vice Principal's Advice: Academics & House</h4>
        <table>
            @php
                $remarks = $allRemarks->where('designation', 'vcpr');
            @endphp
            <tbody>
                @forelse ($remarks as $remark)
                    <tr>
                        <td><b>Score: </b>{{ $remark->score }}</td>
                        <td><b>Remarks: </b>{{ $remark->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No advice given by Vice Principal.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="signature-area">
            <table>
                <tbody>
                    <tr>
                        <td class="date">
                            Date: .......................................
                        </td>
                        <td class="signature">
                            .......................................<br>
                            Vice Principal's Signature
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Principal's Remarks --}}
    <div class="block page-break">
        <h4>Principal's Advice:</h4>
        <table>
            @php
                $remarks = $allRemarks->where('designation', 'pr');
            @endphp
            <tbody>
                @forelse ($remarks as $remark)
                    <tr>
                        <td><b>Score: </b>{{ $remark->score }}</td>
                        <td><b>Remarks: </b>{{ $remark->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No advice given by Principal.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="signature-area">
            <table>
                <tbody>
                    <tr>
                        <td class="date">
                            Date: .......................................
                        </td>
                        <td class="signature">
                            .......................................<br>
                            Principal's Signature
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    {{-- 3rd Page --}}
    <h3 style="text-align: center">Grading System of Cadet Colleges</h3>
    {{-- Grading Index --}}
    <div class="block">
        <h4>Grading Index:</h4>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>A+</th>
                    <th>A</th>
                    <th>A-</th>
                    <th>B</th>
                    <th>C</th>
                    <th>E</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Interpretation</th>
                    <td>Outstanding</td>
                    <td>Good</td>
                    <td>Average</td>
                    <td>Low Average</td>
                    <td>Below Average</td>
                    <td>Poor</td>
                </tr>
            </tbody>
        </table>
    </div>

    @foreach ($layouts as $layout)
        <div class="block">
            <h4>{{ $layout->title  }}:</h4>
            <h4>{!! $layout->description !!}</h4>
        </div>
    @endforeach

    <div class="signature-area">
        <table>
            <tbody>
                <tr>
                    <td class="date">
                        Date: .......................................
                    </td>
                    <td class="signature">
                        .......................................<br>
                        Parent's / Guardian's Signature
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>