<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Vacancy Report (Department)</h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead style="background: #dee2e6;">
                <tr>
                    <th colspan="1" rowspan="2" style="">Sl</th>
                    <th colspan="1" rowspan="2" style="">Department</th>
                    <th colspan="1" rowspan="2" style="">Auth.</th>
                    <th colspan="1" rowspan="2" style="">Held</th>
                    <th colspan="1" rowspan="2" style="">Des. wise Held</th>
                    <th colspan="1" rowspan="2" style="">Def.</th>
                    @if ($today < $toDate)
                        <th colspan="1" rowspan="2" style="">Def. Till: {{ $toDate }}</th>
                        <th colspan="1" rowspan="2" style="">Total Def.</th>
                    @endif
                    <th colspan="1" rowspan="2" style="">Sur.</th>
                    @if ($designationIds[0] != 'all' && sizeof($allDesignation)>0)
                        <th colspan="{{ count($allDesignation) }}" rowspan="1">Held by Designation</th>
                    @endif
                    <th colspan="1" rowspan="2" style="">Def. in Institute</th>
                </tr>
                @if ($designationIds[0] != 'all' && sizeof($allDesignation)>0)
                    <tr>
                        @foreach ($allDesignation as $designation)
                            <th>{{ $designation->name }}</th>
                        @endforeach
                    </tr>
                @endif
            </thead>
            <tbody>
                @php
                    $totalAuth = 0;
                @endphp
                @foreach ($allDepartment as $department)
                    @php
                        $auth = $department->strength * count($allInstituteIds);
                        $totalAuth += $auth;
                    @endphp
                    <tr>
                        <th>{{ $loop->index+1 }}</th>
                        <th style="background: #dee2e6;">{{ $department->name }}
                            {{-- @if ($department->bengali_name) - {{ $department->bengali_name }}@endif --}}
                        </th>
                        <td>{{ $auth }}</td>
                        <td>{{ $held[$department->id] }}</td>
                        <td>
                            <ul>
                                @foreach ($desWiseData[$department->id] as $desWiseVal)
                                    <li>{{ $desWiseVal['name'] }}: {{ $desWiseVal['held'] }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>@if ($def[$department->id]>=0) {{ $def[$department->id] }} @endif</td>
                        @if ($today < $toDate)
                            @if ($dateWiseDiff[$department->id])
                                <td style="color: red; font-weight: bold;">{{ $dateWiseDiff[$department->id] }}</td>
                                <td>
                                    @if (($dateWiseDiff[$department->id] + $def[$department->id])>=0)
                                        {{ $dateWiseDiff[$department->id] + $def[$department->id] }}
                                    @endif
                                </td>
                                <td>
                                    @if (($surp[$department->id] - $dateWiseDiff[$department->id])>=0)
                                        {{ $surp[$department->id] - $dateWiseDiff[$department->id] }}
                                    @endif
                                </td>
                            @else 
                                <td></td>
                                <td></td>
                                <td>@if ($surp[$department->id]>=0) {{ $surp[$department->id] }} @endif</td>
                            @endif
                        @endif
                        @if ($today >= $toDate)
                            <td>@if ($surp[$department->id]>=0) {{ $surp[$department->id] }} @endif</td>
                        @endif
                        @if ($designationIds[0] != 'all' && sizeof($allDesignation)>0)
                            @foreach ($allDesignation as $designation)
                                <td>{{ $fixedDesWiseData[$department->id][$designation->id] }}</td>
                            @endforeach
                        @endif
                        <td>
                            @foreach ($insWiseData[$department->id] as $insData)
                                @if ($insData['def'] != 0)
                                    {{ $insData['name'] }}: {{ $insData['def'] }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><b>Grand Total:</b></td>
                    <td>{{ $totalAuth }}</td>
                    <td>{{ $totalHeld }}</td>
                    <td></td>
                    <td>@if ($totalDef>=0) {{ $totalDef }} @endif</td>
                    @if ($today < $toDate)
                        <td>{{ $totalDateWiseDiff }}</td>
                        <td>
                            @if (($totalDef + $totalDateWiseDiff)>=0)
                                {{ $totalDef + $totalDateWiseDiff }}
                            @endif
                        </td>
                    @endif
                    <td>
                        @if (($totalSurp - $totalDateWiseDiff)>=0)
                            {{ $totalSurp - $totalDateWiseDiff }}
                        @endif
                    </td>
                    @if ($designationIds[0] != 'all' && sizeof($allDesignation)>0)
                        @foreach ($allDesignation as $designation)
                            <td>{{ $totalFixedDesWiseData[$designation->id] }}</td>
                        @endforeach
                    @endif
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>