<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadet Summary Report</title>
    <style>
        body{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 2px 6px;
            border: 1px solid #ddd;
        }
        .repert-header{
            min-height: 140px;
        }
        .repert-header img{
            position: absolute;
        }
        .repert-header h2{
            line-height: 70px;
        }
        .report-table span{
            margin-right: 50px;
        }
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            font-size: small;
            background-color: #002d00;
            color: white;
            height: 35px;
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
            $x = 500;
            $y = 824;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = null;
            $size = 11;
            $color = array(255,255,255);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
        </script>
    </footer>
    <main>
        <div class="repert-header">
            @if($student->singelAttachment("PROFILE_PHOTO"))
                <img class="img-thumbnail img-responsive" src="{{public_path('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:110px; float: left;">
            @else
                <img class="img-thumbnail img-responsive" src="{{public_path('assets/users/images/user-default.png')}}" alt="No Image" style="width:110px; float: left;">
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
    </main>
</body>
</html>