@php
    $index1=0;
    $i=0;
    $j=0;
    $totalAuth=0;
    $totalHeld=0;
    $totalDef=0;
    $totalSur=0;
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
        vertical-align: middle;
    }
    th, td {
        text-align: left;
        padding: 4px;
        border: 1px solid #ddd;
        text-align: center !important;
        vertical-align: middle;
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
    <h3>Vacancy Report (Designation)</h3>
    <table class="table table-bordered table-striped">
        <thead style="background: #dee2e6;">
            <tr>
                <th colspan="1" rowspan="2" style="">Designation</th>
                @foreach ($allInstitute as $institute)
                    <th colspan="4">{{ $institute->institute_alias }}</th>
                @endforeach
                <th colspan="1" rowspan="2" style="">Total Auth.:</th>
                <th colspan="1" rowspan="2" style="">Total Held:</th>
                <th colspan="1" rowspan="2" style="">Total Def.:</th>
                <th colspan="1" rowspan="2" style="">Total Sur.:</th>
            </tr>
            <tr>
                @foreach ($allInstitute as $institute)
                    <th>Auth.</th>
                    <th>Held</th>
                    <th>Def.</th>
                    <th>Sur.</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($allDesignation as $designation)
                <tr>
                    <th style="background: #dee2e6;">
                        {{ $designation->name }} @if ($designation->bengali_name) - {{ $designation->bengali_name }} @endif
                    </th>
                    @foreach ($allInstitute as $institute)
                        @if ($designation->strength != null)
                            <td>{{ $designation->strength }}</td>
                        @else 
                            <td></td>
                        @endif
                        <td>{{ $held[$i][$j] }}</td>
                        @if (($designation->strength > $held[$i][$j]) && ($designation->strength != null))
                            <td style="color: red; font-weight: bold;">{{ $designation->strength - $held[$i][$j] }}</td>
                            @php
                                $totalDef+=($designation->strength - $held[$i][$j]);
                            @endphp
                        @else 
                            <td></td>
                        @endif
                        @if (($designation->strength < $held[$i][$j]) && ($designation->strength != null))
                            <td style="color: green; font-weight: bold;">{{ $held[$i][$j] - $designation->strength }}</td>
                            @php
                                $totalSur+=($held[$i][$j] - $designation->strength);
                            @endphp
                        @else 
                            <td></td>
                        @endif
                        
                        @php
                            $totalAuth+=$designation->strength;
                            $totalHeld+=$held[$i][$j];
                            $j++;
                        @endphp
                    @endforeach
                    <td style="background: #dee2e6; font-weight: bold;">{{ $totalAuth }}</td>
                    <td style="background: #dee2e6; font-weight: bold;">{{ $totalHeld }}</td>
                    <td style="background: #dee2e6; font-weight: bold;">{{ $totalDef }}</td>
                    <td style="background: #dee2e6; font-weight: bold;">{{ $totalSur }}</td>
                </tr>
                @php
                    $j=0;
                    $i++;
                    $totalAuth=0;
                    $totalHeld=0;
                    $totalDef=0;
                    $totalSur=0;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>