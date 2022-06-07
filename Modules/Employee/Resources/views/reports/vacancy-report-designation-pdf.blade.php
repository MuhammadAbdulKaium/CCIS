<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vacancy Report (Designation)</title>
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
            vertical-align: middle;
        }
        th, td {
            text-align: left;
            padding: 4px;
            border: 1px solid #ddd;
        }
        table.table-bordered{
            border:1px solid #000000;
            margin-top:20px;
            vertical-align: middle;
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
    
            <h1 style="text-align: center;float: left">Vacancy Report (Designation)</h1>
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
            <h3>To Date: {{ $toDate }}</h3>
        </div>
        <div>
            @php
                $index1=0;
                $i=0;
                $j=0;
                $totalAuth=0;
                $totalHeld=0;
                $totalDef=0;
                $totalSur=0;
            @endphp
            <table class="table table-bordered ">
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
                            <th>Au.</th>
                            <th>He.</th>
                            <th>Def.</th>
                            <th>Sur.</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allDesignation as $designation)
                        <tr>
                            <th style="background: #dee2e6;">{{ $designation->name }}</th>
                            @foreach ($allInstitute as $institute)
                                @if ($designation->strength != null)
                                    <td>{{ $designation->strength }}</td>
                                @else <td></td>
                                @endif
                                <td>{{ $held[$i][$j] }}</td>
                                
                                @if (($designation->strength > $held[$i][$j]) && ($designation->strength != null))
                                    <td style="color: red; font-weight: bold;">{{ $designation->strength - $held[$i][$j] }}</td>
                                    @php
                                        $totalDef+=($designation->strength - $held[$i][$j]);
                                    @endphp
                                @else <td></td>
                                @endif
        
                                @if (($designation->strength < $held[$i][$j]) && ($designation->strength != null))
                                <td style="color: green; font-weight: bold;">{{ $held[$i][$j] - $designation->strength }}</td>
                                    @php
                                        $totalSur+=($held[$i][$j] - $designation->strength);
                                    @endphp
                                @else <td></td>
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
    </main>
</body>
</html>