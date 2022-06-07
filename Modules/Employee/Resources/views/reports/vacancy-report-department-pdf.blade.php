<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vacancy Report (Department)</title>
    <style>
        .p-0 {
            padding: 0px !important;
        }

        .m-0 {
            margin: 0px !important;

        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        img {
            width: 100px;
            height: 100px;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 8%;
            float: left;
            margin-bottom: 10px;
        }

        .headline {
            float: left;
            padding: 1px 1px;
        }

        .headline-details {
            float: right;
        }
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
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            font-size: medium;
            background-color: #002d00;
            color: white;
            height: 50px;
        }
    </style>
</head>
<body>
    <footer>
        <div style="padding:.5rem">
            <span  >Printed from <b>CCIS</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>
        
        </div>
        <script type="text/php">
        if (isset($pdf)) {
            $x = 1070;
            $y = 1662;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = null;
            $size = 14;
            $color = array(255,255,255);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
        </script>
    </footer>
    <main>
        <div class="header clearfix">
            <div class="logo">
                @if (count($allInstituteIds) == 1)
                    <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" height="60px!important" alt="">
                @elseif (count($allInstituteIds) > 1)
                    <img src="{{ public_path('assets/users/images/cadet-logo.png') }}" height="60px!important" alt="">
                @endif                         
            </div>
            <div class="headline">                
                @if (count($allInstituteIds) == 1)
                    <h1>{{ $institute->institute_name }}</h1>
                    <p>{{ $institute->address2 }}</p>
                @elseif (count($allInstituteIds) > 1)
                    <h1>Cadet College ERP</h1>
                @endif
            </div>
            <div style="float: left;width: 14%;font-size: xx-small;padding: 0;margin: 0">
    
            </div>
    
            <h1 style="text-align: center;float: left">Vacancy Report (Department)</h1>
            <div style="float: left;width: 2%;font-size: xx-small;padding: 0;margin: 0">
    
            </div>
        </div>
        <div>
            @if ($instituteIds[0] == 'all')
                <h3>Institute: All</h3>
            @else 
                <h3>Institute: 
                    @foreach ($allInstitute as $institute)
                        @if ($loop->index != 0),@endif
                        {{ $institute->institute_alias }}
                    @endforeach
                </h3>
            @endif

            @if ($departmentIds[0] == 'all')
                <h3>Department: All</h3>
            @else 
                <h3>Department: 
                    @foreach ($allDepartment as $department)
                        @if ($loop->index != 0),@endif
                        {{ $department->alias }}
                    @endforeach
                </h3>
            @endif

            @if ($designationIds[0] == 'all')
                <h3>Designation: All</h3>
            @else 
                <h3>Designation: 
                    @foreach ($allDesignation as $designation)
                        @if ($loop->index != 0),@endif
                        {{ $designation->alias }}
                    @endforeach
                </h3>
            @endif

            <h3>To Date: {{ $toDate1 }}</h3>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead style="background: #dee2e6;">
                    @php
                        $isRowSpan = $designationIds[0] != 'all' && sizeof($allDesignation)>0;
                    @endphp
                    <tr>
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Sl</th>
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Department</th>
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Auth.</th>
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Held</th>
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Def.</th>
                        @if ($today < $toDate)
                            <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Def. Till: {{ $toDate }}</th>
                            <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Total Def.</th>
                        @endif
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Sur.</th>
                        @if ($designationIds[0] != 'all' && sizeof($allDesignation)>0)
                            <th colspan="{{ count($allDesignation) }}" rowspan="1">Held by Designation</th>
                        @endif
                        <th colspan="1" rowspan="@if ($isRowSpan)2 @endif" style="">Def. in Institute</th>
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
    </main>
</body>
</html>