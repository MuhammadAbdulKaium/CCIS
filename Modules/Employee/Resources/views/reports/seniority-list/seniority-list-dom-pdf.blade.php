<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tabulation Sheet Report (Exam Wise)</title>
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

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #423e3e;
            text-align: center !important;
            page-break-inside: auto;
        }

        th, td {
            text-align: left;
            padding: 1px;
            border: 1px solid #a49494;
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

        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }

    </style>
</head>
<body style="font-size: x-small">

<footer>
    <div style="padding:.5rem">
        <span>Printed from <b>CCIS</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>

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
    <div class="header clearfix" >
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" height="60px!important" alt="">
        </div>
        <div class="headline">
            <h1>{{ $institute->institute_name }}</h1>
            <p>{{ $institute->address2 }}</p>
        </div>
    </div>
    <div class="box box-solid bg-warning"  >

        @foreach($designationWiseEmployee as $key=>$singleDesignationEmployee)
            <div class="  p-2" style="background: white;text-align: center">
                <h2>{{$reportName}}</h2>
                <h3>{{$allDesignation[$key]->name}}</h3>


            </div>
            <div class=" table-responsive">
                <table id="myTable"  class="table table-bordered table-striped  display" border="2">


                    <thead>
                    <tr>
                        <!-- #1 -->
                        <th class="text-center" rowspan="2">Si</th>

                        <th class="text-center" rowspan="2">College</th>
                        @if(in_array('full_name',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Full Name</th>
                        @endif
                        @if(in_array('employee_no',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Employee No</th>
                        @endif
                        @if(in_array('position_serial',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Position Serial</th>
                        @endif
                        @if(in_array('alias',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Name Alias</th>
                        @endif
                        @if(in_array('gender',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Gender</th>
                        @endif
                        @if(in_array('dob',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">DOB</th>
                        @endif
                        @if(in_array('doj',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">DOJ</th>
                        @endif
                        @if(in_array('dor',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">DOR</th>
                        @endif
                        @if(in_array('department',$selectForm) || $all_form)
                        <!-- #11 -->
                            <th class="text-center" rowspan="2">Department</th>
                        @endif
                        @if(in_array('designation',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Designation</th>
                        @endif
                        @if(in_array('email',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Email</th>
                        @endif
                        @if(in_array('phone',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Phone</th>
                        @endif
                        @if(in_array('alt_mobile',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Alt Mobile</th>
                        @endif
                        @if(in_array('blood_group',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Blood Group</th>
                        @endif
                        @if(in_array('religion',$selectForm) || $all_form)

                            <th class="text-center" rowspan="2">Religion</th>
                        @endif
                        @if(in_array('marital_status',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Marital Status</th>
                        @endif
                        @if(in_array('experience_year',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Experience Year</th>
                        @endif
                        @if(in_array('experience_month',$selectForm) || $all_form)
                        <!-- #21 -->
                            <th class="text-center" rowspan="2">Experience Month</th>
                        @endif
                        @if(in_array('present_address',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Present Address</th>
                        @endif
                        @if(in_array('permanent_address',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Permanent Address</th>
                        @endif
                        @if(in_array('promotions',$selectForm) || $all_form)
                        <!-- New Column -->
                            <th class="text-center" rowspan="2">Promotions</th>
                        @endif
                        @if(in_array('disciplines',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Discipline</th>
                        @endif
                        @if(in_array('qualifications',$selectForm) || $all_form)

                            <th class="text-center" rowspan="2">Qualifications</th>
                        @endif
                        @if(in_array('trainings',$selectForm) || $all_form)
                        <!-- 30 -->
                            <th class="text-center" rowspan="2">Trainings</th>
                        @endif
                        @if(in_array('family',$selectForm) || $all_form)
                            <th class="text-center" rowspan="2">Family</th>
                        @endif
                        @if(in_array('transfers',$selectForm) || $all_form)
                            <th class="text-center" rowspan="1" colspan="3">Transfers Histories</th>
                        @endif


                    </tr>
                    @if(in_array('transfers',$selectForm) || $all_form)
                        <tr>
                            <th>College</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    @endif

                    </thead>


                    <tbody>
                    @foreach($singleDesignationEmployee as $employee)
                        <tr>
                            @php
                                $trans_count=1;
                                if($employee->transfers){
                                    $trans_count=$employee->transfers->count();

                                }
                                if($trans_count==0)
                                 $trans_count=1;
                                if(!in_array('transfers',$selectForm) && $all_form==false ){
                                    $trans_count=1;
                                }


                            @endphp

                            <td rowspan="{{$trans_count}}">
                                {{$loop->index+1}}
                            </td>



                            <td rowspan="{{$trans_count}}">
                                @if($employee->getSingleInstitute)
                                    {{$employee->getSingleInstitute->institute_alias}}
                                @endif
                            </td>
                            @if(in_array('full_name',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->first_name}} {{$employee->middle_name}} {{$employee->last_name}}
                                </td>
                            @endif
                            @if(in_array('employee_no',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->employee_no}}
                                </td>
                            @endif
                            @if(in_array('position_serial',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->position_serial}}
                                </td>
                            @endif
                            @if(in_array('alias',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->alias}}
                                </td>
                            @endif
                            @if(in_array('gender',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->gender}}
                                </td>
                            @endif
                            @if(in_array('dob',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->dob}}
                                </td>
                            @endif
                            @if(in_array('doj',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->doj}}
                                </td>
                            @endif
                            @if(in_array('dor',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->dor}}
                                </td>
                            @endif
                            @if(in_array('department',$selectForm) || $all_form)

                                <td rowspan="{{$trans_count}}">
                                    @if($employee->singleDepartment)
                                        {{$employee->singleDepartment->name}}
                                    @endif
                                </td>
                            @endif
                            @if(in_array('designation',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @if($employee->singleDesignation)
                                        {{$employee->singleDesignation->name}}
                                    @endif

                                </td>
                            @endif
                            @if(in_array('email',$selectForm) || $all_form)
                            <!--    start 13  -->
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->email}}
                                </td>
                            @endif
                            @if(in_array('phone',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->phone}}
                                </td>
                            @endif
                            @if(in_array('alt_mobile',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->alt_mobile}}
                                </td>
                            @endif
                            @if(in_array('blood_group',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->blood_group}}
                                </td>
                            @endif


                            @php
                                $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others']
                            @endphp
                            @if(in_array('religion',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @foreach ($religions as $key => $value)
                                        @if ($employee->religion == $key + 1)
                                            {{ $value }}
                                        @endif
                                    @endforeach
                                </td>
                            @endif
                            @if(in_array('marital_status',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{$employee->marital_status}}
                                </td>
                            @endif

                            @if(in_array('experience_year',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{ $employee->experience_year }}

                                </td>
                            @endif
                            @if(in_array('experience_month',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{ $employee->experience_month }}

                                </td>
                            @endif

                            @php
                                $presentAddress = '';
                                $permanentAddress = ''
                            @endphp
                            @if ($employee->getEmployeAddress)
                                @foreach ($employee->getEmployeAddress as $key => $address)
                                    @if ($address->type == 'EMPLOYEE_PRESENT_ADDRESS')
                                        @php
                                            $presentAddress = $address->address
                                        @endphp
                                    @endif
                                    @if ($address->type == 'EMPLOYEE_PERMANENT_ADDRESS')
                                        @php
                                            $permanentAddress = $address->address
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if(in_array('present_address',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">

                                    {{ $presentAddress }}
                                </td>
                            @endif

                            @if(in_array('permanent_address',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    {{ $permanentAddress }}

                                </td>
                            @endif
                        <!--  #23 New Column -->
                            @if(in_array('promotions',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @if($employee->promotions)
                                        @foreach($employee->promotions as $promotion)
                                            @if(isset($allDesignation[$promotion->designation]))
                                                {{$allDesignation[$promotion->designation]->name}} <br>
                                                {{$promotion->promotion_date}}<br>
                                            @endif
                                        @endforeach

                                    @endif
                                </td>
                            @endif
                            @if(in_array('disciplines',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @if($employee->discipline)
                                        {{count($employee->discipline)}}
                                    @else
                                        0

                                    @endif

                                </td>
                            @endif
                            @if(in_array('qualifications',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @if($employee->qualifications )
                                        @foreach($employee->qualifications as $qualification )
                                            {{$qualification->qualification_name}} -
                                            {{$qualification->qualification_marks}} <br>

                                        @endforeach
                                    @endif
                                </td>
                            @endif
                            @if(in_array('trainings',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @if($employee->trainings )
                                        @foreach($employee->trainings as $training )
                                            {{$training->training_name}}
                                            @if($training->training_from)
                                                {{\Carbon\Carbon::parse($training->training_from)->format('Y')}}
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                </td>
                            @endif
                            @if(in_array('family',$selectForm) || $all_form)
                                <td rowspan="{{$trans_count}}">
                                    @php
                                        $spouse=null;
                                        $sons=[];
                                        $daugters=[];

                                    @endphp
                                    @foreach ($employee->myGuardians() as $key => $parent)
                                        @php
                                            $guardian = $parent->guardian();
                                            if($guardian->type == 6){
                                                $spouse=$guardian;
                                            }else if($guardian->type==7){
                                                array_push($sons,$guardian);
                                            }else if($guardian->type==8){
                                                array_push($daugters,$guardian);
                                            }
                                        @endphp


                                    @endforeach
                                    @if($spouse)
                                        Spouse- {{$spouse->first_name}}{{",".$spouse->occupation}}
                                    @endif
                                    @foreach($sons as $key=>$son)

                                        {{$son->first_name}} ,son ( {{$son->occupation}}
                                        @if($son->date_of_birth)

                                            @php $age=\Carbon\Carbon::parse($son->date_of_birth)->age;@endphp
                                            {{$age}} Years)
                                        @endif
                                    @endforeach
                                    @foreach($daugters as $key=>$daugter)

                                        {{$daugter->first_name}} ,daughter ( {{$daugter->occupation}}
                                        @if($daugter->date_of_birth)

                                            @php $age=\Carbon\Carbon::parse($daugter->date_of_birth)->age;@endphp
                                            {{$age}} Y
                                        @endif
                                        )
                                    @endforeach

                                </td>
                            @endif
                            @if(in_array('transfers',$selectForm) || $all_form)
                                @if($employee->transfers )
                                    @if(count($employee->transfers )>0)
                                        @foreach($employee->transfers as $transfer)
                                            <td>
                                                {{$transfer->institute->institute_alias}}
                                            </td>
                                            <td>
                                                {{$transfer->from}}
                                            </td>
                                            <td>
                                                {{$transfer->to}}
                                            </td>
                                            @break
                                        @endforeach
                                    @else

                                        <td>{{$employee->getSingleInstitute->institute_alias}}</td>
                                        <td>{{$employee->doj}}</td>
                                        <td></td>
                                    @endif

                                @endif
                            @endif

                        </tr>
                        @if(in_array('transfers',$selectForm) || $all_form)
                            @if($employee->transfers )
                                @php   $count=0 @endphp
                                @foreach($employee->transfers as $transfer)
                                    @php
                                        $count++
                                    @endphp
                                    @if($count>1)
                                        <tr>
                                            <td>
                                                {{$transfer->institute->institute_alias}}
                                            </td>
                                            <td>
                                                {{$transfer->from}}
                                            </td>
                                            <td>
                                                {{$transfer->to}}
                                            </td>
                                        </tr>

                                    @endif

                                @endforeach


                            @endif
                        @endif
                    @endforeach
                    </tbody>


                </table>
            </div>
        @endforeach

        <table class="table table-bordered table-responsive">
            <thead>

            </thead>

        </table>
    </div>

</main>
</body>

</html>