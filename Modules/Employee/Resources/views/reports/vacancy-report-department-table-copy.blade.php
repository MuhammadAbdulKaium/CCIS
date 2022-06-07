@php
    $index1=0;
    $i=0;
    $j=0;
    // $def=0;
    $totalAuth=0;
    $totalHeld=0;
    $totalDef=0;
    $totalSur=0;
    $defTill=0;
@endphp

<style>
    h4{
        font-weight: bold;
        font-size: 18px;
    }
    h5{
        font-weight: bold;
        font-size: 16px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
        text-align: center !important;
    }
    th, td {
        text-align: left;
        padding: 4px;
        border: 1px solid #ddd;
        text-align: center !important;
    }
    table.table-bordered{
        border:1px solid #000000;
        margin-top:20px;
    }
    table.table-bordered > thead > tr > th{
        border:1px solid #000000;
        text-align: center;
        vertical-align: middle;
    }
    table.table-bordered > tbody > tr > th{
        border:1px solid #000000;
        text-align: center;
        vertical-align: middle;
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid #000000;
        text-align: center;
        vertical-align: middle;
        font-size: 16px;
    }
    .select2-selection--single{
        height: 33px !important;
    }
</style>

<div>
    <h3>Vacancy Report (Department)</h3>
    <table class="table table-bordered table-striped">
        <thead style="background: #dee2e6;">
            <tr>
                <th colspan="1" rowspan="2" style="">Department</th>
                <th colspan="1" rowspan="2" style="">Auth.</th>
                <th colspan="1" rowspan="2" style="">Held</th>
                <th colspan="1" rowspan="2" style="">Def.</th>
                @if ($today < $toDate)
                    <th colspan="1" rowspan="2" style="">Def. Till: {{ $toDate }}</th>
                    <th colspan="1" rowspan="2" style="">Total Def.</th>
                @endif
                <th colspan="1" rowspan="2" style="">Sur.</th>
                @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                    <th colspan="{{ count($allDesignation) }}" rowspan="1">Held by Designation</th>
                    {{-- {{ dd($allDesignation) }} --}}
                @endif
                <th colspan="1" rowspan="2" style="">Def. in Institute</th>
            </tr>
            @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                <tr>
                    @foreach ($allDesignation as $designation)
                        <th>{{ $designation->name }}</th>
                    @endforeach
                </tr>
            @endif
        </thead>
        <tbody>
            @foreach ($allDepartment as $department)
                @php
                    $auth = $department->strength * count($allInstituteIds);
                    $held1 = $held[$i];
                    // $def= $auth - $held[$i];
                @endphp
                <tr>
                    <th style="background: #dee2e6;">{{ $department->name }}@if ($department->bengali_name)
                        - {{ $department->bengali_name }}
                    @endif</th>

                    {{-- Auth. --}}
                    @if ($department->strength != null)
                        <td>{{ $auth }}</td>
                    @else <td></td>
                    @endif
                    
                    <td>{{ $held1 }}</td>

                    {{-- Def. --}}
                    <td>{{ $def[$i] }}</td>
                    
                    {{-- Def. Till:  --}}
                    @if ($today < $toDate)
                        @if (($heldBetween[$i] > 0))
                            <td style="color: red; font-weight: bold;">{{ $heldBetween[$i] }}</td>
                            @php
                                $defTill+=$heldBetween[$i];
                            @endphp
                        @else <td></td>
                        @endif
                        
                        @if ($def[$i]>0)
                            <td style="color: red; font-weight: bold;">{{ $heldBetween[$i] + $def[$i] }}</td>
                        @else <td></td>
                        @endif
                    @endif

                    {{-- Sur. --}}
                    <td>{{ $sur[$i] }}</td>

                    @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                        @if (count($allDesignation) > 0)
                            @for ($j=0; $j<count($allDesignation); $j++)
                                <td>{{ $teaching[$i][$j] }}</td>
                            @endfor
                        @else <td></td>
                        @endif
                    @endif
                    <td>
                        @foreach ($instituteDef[$i] as $insData)
                            {{ $insData['alias'] }}: {{ $department->strength - $insData['def'] }}
                        @endforeach
                        
                        @foreach ($defInstitute[$i] as $Institute) 
                            @isset($instituteDef[$i])
                                @isset($instituteDef[$i][$Institute->id])
                                
                                    {{ $department->strength - $instituteDef[$i][$Institute->id]['def'] }}
                                        
                                    {{-- {{ $Institute['alias'][0]->institute_alias }}-
                                    {{ $department->strength - $Institute['count'] }} --}}
                                @else
                                    {{ $Institute->institute_alias }}
                                    {{-- {{ $Institute->institute_alias }} --}}
                                @endisset
                            @endisset
                            {{-- @if ($loop->index != 0),@endif
                            {{ $Institute->institute_alias }}  --}}
                        @endforeach
                        
                        {{-- @foreach ($instituteDef[$i] as $Institute) 
                             
                            @if ($department->strength > $Institute['count'])
                                @if ($loop->index != 0),@endif
                                {{ $Institute['alias'][0]->institute_alias }}-
                                {{ $department->strength - $Institute['count'] }}
                            @endif 
                        @endforeach --}}
                    </td>
                    @php
                        $totalAuth += $auth;
                        $totalHeld += ($held[$i] - $heldBetween[$i]);
                        // $def=0;
                        $i++;
                    @endphp
                </tr>
            @endforeach
            {{-- <tr style="background: #dee2e6; font-weight: bold;">
                <th>Grand Total:</th>
                <td>{{ $totalAuth }}</td>
                <td>{{ $totalHeld }}</td>
                <td>{{ $totalDef }}</td>
                @if ($today < $toDate)
                    <td>{{ $defTill }}</td>
                    <td>{{ $totalDef + $defTill }}</td>
                @endif
                <td>{{ $totalSur }}</td>
                @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                    @if ($grandTotal)
                        @foreach ($grandTotal as $total)
                            <td>{{ $total }}</td>
                        @endforeach
                    @else <td></td>
                    @endif       
                @endif
                <td></td>
            </tr> --}}
        </tbody>
    </table>
</div>