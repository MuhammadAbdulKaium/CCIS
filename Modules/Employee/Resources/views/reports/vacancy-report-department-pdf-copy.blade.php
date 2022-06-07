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
            $x = 1550;
            $y = 1170;
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
        <div>
            @php
                $index1=0;
                $i=0;
                $j=0;
                $def=0;
                $totalAuth=0;
                $totalHeld=0;
                $totalDef=0;
                $totalSur=0;
                $defTill=0;
                $rowspan=0;
            @endphp
            <table class="table table-bordered">
                <thead style="background: #dee2e6;">
                    <tr>
                        @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                            @if (sizeOf($allDesignation)>0)
                                @php
                                    $rowspan=2;
                                @endphp
                            @endif                    
                            @else 
                                @php
                                    $rowspan=1;
                                @endphp
                        @endif
                        <th colspan="1" rowspan="{{ $rowspan }}">Department</th>
                        <th colspan="1" rowspan="{{ $rowspan }}">Auth.</th>
                        <th colspan="1" rowspan="{{ $rowspan }}">Held</th>
                        <th colspan="1" rowspan="{{ $rowspan }}">Def.</th>
                        @if ($today < $toDate)
                            <th colspan="1" rowspan="{{ $rowspan }}">Def. Till: {{ $toDate }}</th>
                            <th colspan="1" rowspan="{{ $rowspan }}">Total Def.</th>
                        @endif
                        <th colspan="1" rowspan="{{ $rowspan }}">Sur.</th>
                        @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                            @if (sizeOf($allDesignation)>0)
                                <th colspan="{{ count($allDesignation) }}" rowspan="1">Held by Designation</th>
                            @endif
                        @endif
                        <th colspan="1" rowspan="{{ $rowspan }}">Def. in Institute</th>
                    </tr>
                    @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                        @if (sizeOf($allDesignation)>0)
                            <tr>
                                @foreach ($allDesignation as $designation)
                                    <th rowspan="1">{{ $designation->name }}</th>
                                @endforeach
                            </tr>
                        @endif                     
                    @endif
                </thead>
                <tbody>
                    @foreach ($allDepartment as $department)
                        @php
                            $auth = $department->strength * count($allInstituteIds);
                            $held1 = $held[$i];
                            $def= $auth - $held[$i];
                        @endphp
                        <tr>
                            <td style="background: #dee2e6;">{{ $department->name }}</td>
                            
                            {{-- Auth. --}}
                            @if ($department->strength != null)
                                <td>{{ $auth }}</td>
                            @else <td></td>
                            @endif

                            <td>{{ $held1 }}</td>

                            {{-- Def. --}}
                            @if (($auth > $held[$i]) && ($department->strength))
                                <td style="color: red; font-weight: bold;">{{ $def }}</td>
                                @php
                                    $totalDef+=$def;
                                @endphp
                            @else <td></td>
                            @endif
                            
                            {{-- Def. Till:  --}}
                            @if ($today < $toDate)
                                @if ($heldBetween[$i] > 0)
                                    <td style="color: red; font-weight: bold;">{{ $heldBetween[$i] }}</td>
                                    @php
                                        $defTill+=$heldBetween[$i];
                                    @endphp
                                @else <td></td>
                                @endif
                                
                                @if ($def>0)
                                    <td style="color: red; font-weight: bold;">{{ $heldBetween[$i] + $def }}</td>
                                @else <td></td>
                                @endif
                            @endif
        
                            {{-- Sur. --}}
                            @if (($auth < $held[$i]) && ($auth != null))
                                <td style="color: green; font-weight: bold;">{{ $held[$i] - $auth - $heldBetween[$i] }}</td>
                                                    
                                @php
                                    $totalSur+=($held[$i] - $auth);
                                @endphp
                            @else <td></td>
                            @endif
        
                            @if ($designationIds[0] == 'teaching' || $designationIds[0] == 'officer' || $designationIds[0] == 'general' || $designationIds[0] == 'other')
                                @if (sizeOf($allDesignation)>0)
                                    @for ($j=0; $j<count($allDesignation); $j++)
                                        <td>{{ $teaching[$i][$j] }}</td>
                                    @endfor
                                @endif
                            @endif
                            <td>
                                @foreach ($defInstitute[$i] as $Institute) 
                                    @if ($loop->index != 0),@endif
                                    {{ $Institute->institute_alias }} 
                                @endforeach
                            </td>
                            @php
                                $totalAuth += $auth;
                                $totalHeld += ($held[$i] - $heldBetween[$i]);
                                $def=0;
                                $i++;
                            @endphp
                        </tr>
                    @endforeach
                    <tr style="background: #dee2e6; font-weight: bold;">
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
                            @if (sizeOf($allDesignation)>0)
                                @foreach ($grandTotal as $total)
                                    <td>{{ $total }}</td>
                                @endforeach
                            @endif   
                        @endif
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>