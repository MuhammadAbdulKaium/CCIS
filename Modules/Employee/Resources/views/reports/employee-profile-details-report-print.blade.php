<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HR Details Report</title>
    <style>
        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }



        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .header .header_title {
            text-align: center;
        }

        .logo {
            width: 20%;
            float: left;
            text-align: center;
        }

        .logo img {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        .headline {
            width: 80%;
            float: right;
            padding: 0 20px;
            text-align: left;
            margin-top: 25px;

        }

        .headline p {
            font-size: 30px;
        }

        td {
            font-size: 16px;
            padding: 3px 0;
        }

        a {
            text-decoration: none
        }





        .top-table {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .top-table table {
            table-layout: auto;
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .top-table table tr td,
        .top-table table tr th {
            border: 1px solid #000;
            text-align: center;
            word-break: break-all;
            padding: 3px !important;
            word-wrap: break-word;
            font-size: 14px;
        }

        /* .bottom-table {
            width: 100%;
            overflow: hidden;
        }

        */
        .bottom-table table {
            border-collapse: collapse;

        }



        .hidden_border {
            border-style: none !important;
        }

        .terms {
            border-bottom: 2px solid black;
            width: 175px;
        }

        ul {
            color: blue;
            list-style: none;
            margin: 0;
            padding: 0;
        }


        h1,
        h3,
        h4,
        p {
            margin: 5px 0;
        }

        h2 {
            margin: 10px 0;
        }

        .text-center {
            text-align: center;
        }

        .approved {
            text-transform: capitalize;
            color: rgba(3, 80, 3, 0.911);
            font-size: 18px
        }

        .pending {
            text-transform: capitalize;
            color: red;
            font-size: 18px;
        }

        .signature {
            width: 18%;
            /* float: left; */
            margin: 0 5.4px;
            display: inline-block;
            height: 250px;
            height: auto;
            vertical-align: top;
        }



        .signature p {
            /* border-top: 1px solid #f1f1f1; */
            margin: 0;
            font-size: 12px;
        }

        .signotry_img {
            height: 60px;
            widows: 100%;
            text-align: center;
        }

        .signotry_img img {
            height: 100%;
            widows: 100%;
            overflow: hidden;
        }

        /* .footer {

            margin-top: 30px;
            font-size: 40px;
        } */

        .cadet_Name {
            margin-top: 0;
            font-size: 60px;
            font-weight: 900;
        }

        .title {
            text-align: center;
            font-size: 42px;
            font-weight: 900;
            padding: 5px 0;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            font-size: 16px;
            background-color: #002d00;
            color: #fff !important;
            height: 40px;
        }
        

        .m-0 {
            margin: 0;
            padding: 3px 0;
        }

        .col-sm-10 {
            width: 80%;
            float: left;
        }

        .col-sm-2 {
            width: 20%;
            float: left;
            /* float: right; */
        }

        .img-thumbnail {
            width: 100%;
        }

        .row {
            margin-top: 20px;
        }

        .employee_infomation {
            padding: 0;
        }

        .employee_infomation li {
            list-style: none;
            padding: 5px 0;
            color: #000;
            font-size: 14px;
        }

        .family_infomation {
            margin-left: 20px;
        }

        .family_infomation li {
            list-style: none;
            padding: 5px 0;
            color: #000;
            font-size: 14px;
        }

        .family_infomation li ul {
            margin-left: 20px;
        }

        .col-sm-12 {
            width: 100%;
        }

        .text-right {
            text-align: left;
        }

        .header_middle {
            width: 80%;
            float: left;
        }

        .header_middle p {
            font-size: 14px;
        }

        .header_right {
            width: 20%;
            float: right;
            text-align: center;
        }
        .header_right img{
            width: auto;
            height: 150px;
        }
        .main_header {
            padding-bottom: 30px;
            border-bottom: 3px solid rgb(4, 49, 13);
            height: auto;
            width: 100%;
        }

        .childe_ul {
            margin-left: 26.3%;
            margin-top: -22px;
        }
        .institute_logo img{
            margin-top: 20px;
        }

    </style>
</head>

<body>
    <footer class="footer">
        <div style="padding:.5rem">
            <span class="footer_text">Printed from <b>{{ $institute->institute_alias }}</b> by {{ $user->name }} on <?php echo date('l jS \of F Y h:i:s A'); ?>
            </span>

        </div>
        <script type="text/php">if (isset($pdf)) {
                $x = 500;
                $y = 820;
                $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(255,255,255);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }</script>


    </footer>
    <div class="header clearfix">
        <h1 class="header_title">HR Details Report</h1>
    </div>
    <div class="content clearfix">

        <div class="row main_header clearfix">
            <div class="col-sm-2 institute_logo clearfix">
                <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" width="100" alt="logo">
            </div>
            <div class="header_middle clearfix">
                <p class="text-right m-0">
                    <strong>Employee ID: </strong> {{ $employee->singleUser->username }}
                </p>
                <p class="text-right m-0">
                    <strong>Employee Name: </strong>
                    {{ $employee->title . ' ' . $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name }}
                </p>
                <p class="text-right m-0">
                    <strong>Department: </strong>
                    {{ $employee->singleDepartment ? $employee->singleDepartment->name : '' }}
                </p>
                <p class="text-right m-0">
                    <strong>Designation: </strong>
                    {{ $employee->singleDesignation ? $employee->singleDesignation->name : ' ' }}
                </p>
                <p class="text-right m-0">
                    <strong>Position Serial: </strong> {{ $employee->position_serial }}
                </p>
                <p class="text-right m-0">
                    <strong>Central Position Serial: </strong> {{ $employee->central_position_serial }}
                </p>
                <p class="text-right m-0">
                    <strong>Institute: </strong>
                    {{ $employee->getSingleInstitute ? $employee->getSingleInstitute->institute_name : '' }}
                </p>

            </div>
            <div class="header_right">

                @if ($employee->singelAttachment('PROFILE_PHOTO'))
                    <img class="center-block img-thumbnail img-responsive user_img"
                        src="{{ public_path('assets/users/images/' . $employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name) }}"
                        alt="No Image">
                @elseif($employee->category == 1)
                    <img class="center-block img-thumbnail img-responsive user_img"
                        src="{{ public_path('assets/users/images/user-teaching.png') }}" alt="No Image">
                @elseif($employee->category == 2)
                    <img class="center-block img-thumbnail img-responsive user_img"
                        src="{{ public_path('assets/users/images/user-non-teaching.png') }}" alt="No Image">
                @endif
            </div>
        </div>
        @php
            $today = Carbon\Carbon::now();
            $dob = Carbon\Carbon::create($employee->dob);
            $doj = Carbon\Carbon::create($employee->doj);
            $dor = Carbon\Carbon::create($employee->dor);
        @endphp
        <div class="row">
            <div class="col-sm-12">
                <ul class="employee_infomation">
                    @foreach ($selectForm as $value)
                        @if ($value == 1)
                            <li><strong>{{ $loop->index + 1 }}. Date of Birth:</strong>
                                {{ $dob->format('d  F Y') }}
                                ({{ $dob->diff($today)->format('%y years %m months %d days') }})
                            </li>
                        @endif
                        @if ($value == 2)
                            <li><strong>{{ $loop->index + 1 }}. Joining Date:</strong>
                                {{ $doj->format('d  F Y') }} </li>
                        @endif
                        @if ($value == 3)
                            <li><strong>{{ $loop->index + 1 }}. Retirement Date:</strong>
                                {{ $dor->format('d F Y') }} </li>
                        @endif
                        @if ($value == 4)
                            <li><strong>{{ $loop->index + 1 }}. Cadet College Tenure in Job:</strong>
                                {{ $doj->diff($dor)->format('%y years %m months %d days') }} </li>
                        @endif
                        @if ($value == 5)
                            <li><strong>{{ $loop->index + 1 }}. Previous Promotion Status:</strong>
                                <ul class="childe_ul">
                                    @foreach ($promotions as $promotion)
                                        @php
                                            $promotion_date = Carbon\Carbon::create($promotion->promotion_date);
                                        @endphp
                                        <li>

                                            {{ $promotion->singleDesignation ? $promotion->singleDesignation->name : ' ' }},
                                            {{ $promotion_date->format('d  F Y') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        @if ($value == 6)
                            <li><strong>{{ $loop->index + 1 }}. Current College:</strong>
                                {{ $employee->getSingleInstitute ? $employee->getSingleInstitute->institute_name : '' }}
                            </li>
                        @endif
                        @if ($value == 7)
                            <li><strong>{{ $loop->index + 1 }}. Medical Category:</strong>
                                {{ $employee->medical_category }} </li>
                        @endif
                        @if ($value == 8)
                            <li><strong>{{ $loop->index + 1 }}. Blood Group:</strong>
                                {{ $employee->blood_group }} </li>
                        @endif
                        @if ($value == 9)
                            <li><strong>{{ $loop->index + 1 }}. Mobile Number:</strong>
                                {{ $employee->phone }}
                            </li>
                        @endif
                        @if ($value == 10)
                            <li><strong>{{ $loop->index + 1 }}. E-mail:</strong> {{ $employee->email }}
                            </li>
                        @endif
                    @endforeach
                </ul>

            </div>

        </div>
        @foreach ($selectForm as $value)
            @if ($value == 11)
                @if ($hide_blank && count($qualifications) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong class="mt-2">{{ $loop->index + 1 }}. Educational
                                Qualifications:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Type</th>
                                        <th>Year</th>
                                        <th>Group/Division</th>
                                        <th>Name</th>
                                        <th>Board/University</th>
                                        <th>Institute Address</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($qualifications as $qualification)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                @if ($qualification->qualification_type == 1)
                                                    General Qualification
                                                @elseif($qualification->qualification_type == 2)
                                                    Special Qualification
                                                @elseif($qualification->qualification_type == 3)
                                                    Last Academic Qualification
                                                @endif
                                            </td>
                                            <td>{{ $qualification->qualification_year }}</td>
                                            <td>{{ $qualification->qualification_group }}</td>
                                            <td>{{ $qualification->qualification_name }}</td>
                                            <td>{{ $qualification->qualification_institute }}</td>
                                            <td>{{ $qualification->qualification_institute_address }}</td>
                                            <td>{{ $qualification->qualification_marks }}</td>


                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong class="mt-2">{{ $loop->index + 1 }}. Educational
                                Qualifications:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Type</th>
                                        <th>Year</th>
                                        <th>Group/Division</th>
                                        <th>Name</th>
                                        <th>Board/University</th>
                                        <th>Institute Address</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($qualifications as $qualification)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                @if ($qualification->qualification_type == 1)
                                                    General Qualification
                                                @elseif($qualification->qualification_type == 2)
                                                    Special Qualification
                                                @elseif($qualification->qualification_type == 3)
                                                    Last Academic Qualification
                                                @endif
                                            </td>
                                            <td>{{ $qualification->qualification_year }}</td>
                                            <td>{{ $qualification->qualification_group }}</td>
                                            <td>{{ $qualification->qualification_name }}</td>
                                            <td>{{ $qualification->qualification_institute }}</td>
                                            <td>{{ $qualification->qualification_institute_address }}</td>
                                            <td>{{ $qualification->qualification_marks }}</td>


                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 12)
                @if ($hide_blank && count($allTraining) > 0)
                    <div class="clearfix">
                        <div class="top-table ">
                            <strong class="mt-2">{{ $loop->index + 1 }}. Details of In-service
                                Training:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>

                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2">Training Name</th>
                                        <th rowspan="2">Training Institute</th>
                                        <th colspan="3" style="padding: 0;">Training Period</th>
                                        <th rowspan="2">Grading</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allTraining as $training)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $training->singleInstitute ? $training->singleInstitute->institute_alias : ' ' }}
                                            <td>
                                                {{ $training->training_name }}
                                            </td>
                                            <td>{{ $training->training_institute }}</td>
                                            <td>{{ date('d/m/Y', strtotime($training->training_from)) }}
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($training->training_to)) }}
                                            </td>
                                            <td>{{ $training->training_duration }}</td>
                                            <td>{{ $training->training_grading }}</td>
                                            <td>{{ $training->remarks }}</td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table ">
                            <strong class="mt-2">{{ $loop->index + 1 }}. Details of In-service
                                Training:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>

                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2">Training Name</th>
                                        <th rowspan="2">Training Institute</th>
                                        <th colspan="3" style="padding: 0;">Training Period</th>
                                        <th rowspan="2">Grading</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allTraining as $training)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $training->singleInstitute ? $training->singleInstitute->institute_alias : ' ' }}
                                            <td>
                                                {{ $training->training_name }}
                                            </td>
                                            <td>{{ $training->training_institute }}</td>
                                            <td>{{ date('d/m/Y', strtotime($training->training_from)) }}
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($training->training_to)) }}
                                            </td>
                                            <td>{{ $training->training_duration }}</td>
                                            <td>{{ $training->training_grading }}</td>
                                            <td>{{ $training->remarks }}</td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 13)
                @if ($hide_blank && count($employeeTransferHistories) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Experience in Cadet College(s):</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Designation</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employeeTransferHistories as $employeeTransferHistory)
                                        @php
                                            $from = Carbon\Carbon::parse($employeeTransferHistory->from);
                                            $to = Carbon\Carbon::parse($employeeTransferHistory->to);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $employeeTransferHistory->institute->institute_name }}
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->designation)
                                                    {{ $employeeTransferHistory->designation->alias }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->from)
                                                    {{ $from->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->to)
                                                    {{ $to->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->from && $employeeTransferHistory->to)
                                                    {{ $from->diff($to)->format('%y years %m months %d days') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Experience in Cadet College(s):</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Designation</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employeeTransferHistories as $employeeTransferHistory)
                                        @php
                                            $from = Carbon\Carbon::parse($employeeTransferHistory->from);
                                            $to = Carbon\Carbon::parse($employeeTransferHistory->to);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $employeeTransferHistory->institute->institute_name }}
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->designation)
                                                    {{ $employeeTransferHistory->designation->alias }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->from)
                                                    {{ $from->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->to)
                                                    {{ $to->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employeeTransferHistory->from && $employeeTransferHistory->to)
                                                    {{ $from->diff($to)->format('%y years %m months %d days') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 14)
                <div class="clearfix">
                    <div class="top-table">
                        <strong>{{ $loop->index + 1 }}. Remaining Tenure:</strong>
                        <table id="myTable" class="table table-bordered table-striped  display" border="2">
                            <thead>
                                <tr>
                                    <th>Current Designation</th>
                                    <th>Current Designation Job Duration</th>
                                    <th>Remaining tenure</th>
    
                                </tr>

                            </thead>
                            <tbody>
                                @if ($remainingTenure)
                                    @php
                                        $today = Carbon\Carbon::now();
                                        $promotion_date = Carbon\Carbon::create($remainingTenure->promotion_date);
                                        $dor = Carbon\Carbon::create($employee->dor);
                                    @endphp

                                    <tr>
                                        <td>
                                            {{ $remainingTenure->singleDesignation ? $remainingTenure->singleDesignation->name : ' ' }}
                                        </td>
                                        <td>
                                            {{ $promotion_date->diff($today)->format('%y years %m months %d days') }}
                                        </td>
                                        <td>
                                            {{ $dor->diff($today)->format('%y years %m months %d days') }}
                                        </td>
                                    </tr>
                                @else
                                    @php
                                        $today = Carbon\Carbon::now();
                                        $doj = Carbon\Carbon::create($employee->doj);
                                        $dor = Carbon\Carbon::create($employee->dor);
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $employee->singleDesignation ? $employee->singleDesignation->name : ' ' }}
                                        </td>
                                        <td>
                                            {{ $doj->diff($today)->format('%y years %m months %d days') }}
                                        </td>
                                        <td>
                                            {{ $dor->diff($today)->format('%y years %m months %d days') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>


                </div>
            @endif
            @if ($value == 15)
                <div class="clearfix">
                    <div class="top-table">
                        <strong>{{ $loop->index + 1 }}. Family Details:</strong>
                        <div class="col-sm-12">
                            @php
                                $SpouseName = ' ';
                                $SpouseOccupation = ' ';
                                $ChildrenCount = 0;
                                $sonCount = 0;
                                $DaughterCount = 0;
                                $presentAddress = '';
                                $permanentAddress = '';
                            @endphp
                            @if ($employee->myGuardians())
                                @foreach ($employee->myGuardians() as $parent)
                                    @php
                                        $guardian = $parent->guardian();
                                    @endphp
                                    @if ($guardian->type == 6)
                                        @php
                                            $SpouseName = $guardian->title . ' ' . $guardian->first_name . ' ' . $guardian->last_name;
                                            $SpouseOccupation = $guardian->occupation;
                                        @endphp
                                    @endif
                                    @if ($guardian->type == 7)
                                        @php
                                            $ChildrenCount++;
                                            $sonCount++;
                                        @endphp
                                    @endif
                                    @if ($guardian->type == 8)
                                        @php
                                            $ChildrenCount++;
                                            $DaughterCount++;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif

                            @if ($employee->getEmployeAddress)
                                @foreach ($employee->getEmployeAddress as $key => $address)
                                    @if ($address->type == 'EMPLOYEE_PRESENT_ADDRESS')
                                        @php
                                            $presentAddress = $address->address;
                                        @endphp
                                    @endif
                                    @if ($address->type == 'EMPLOYEE_PERMANENT_ADDRESS')
                                        @php
                                            $permanentAddress = $address->address;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            <ul class="family_infomation">
                                <li><strong>a. Marital Status: </strong>
                                    @switch($employee->marital_status)
                                        @case('MARRIED')
                                            Married
                                        @break

                                        @case('UNMARRIED')
                                            UnMarried
                                        @break
                                        @case('DIVORCED')
                                            Divorced
                                        @break
                                        @case('Priest')
                                            Priest
                                        @break

                                        @default
                                            
                                    @endswitch
                                </li>
                                <li><strong>b. Spouse Name: </strong> {{ $SpouseName }}</li>
                                <li><strong>c. Spouse Occupation: </strong> {{ $SpouseOccupation }}
                                    ,{{ $guardian->office_address }}</li>
                                <li><strong>d. Children: </strong> {{ $ChildrenCount }} ({{ $sonCount }}
                                    Son
                                    {{ $DaughterCount }} Daughter)
                                    <ul>
                                        @if ($employee->myGuardians())
                                            @foreach ($employee->myGuardians() as $key => $parent)
                                                @php
                                                    $guardian = $parent->guardian();
                                                @endphp
                                                @if ($guardian->type == 7 || $guardian->type == 8)
                                                    <li><strong>({{ $key + 1 }})
                                                            @php
                                                                $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                                                                if ($key + (1 % 100) >= 11 && $key + (1 % 100) <= 13) {
                                                                    echo $key + 1 . 'th' . ' Children';
                                                                } else {
                                                                    echo $key + 1 . $ends[$key + (1 % 10)] . ' Children';
                                                                }
                                                            @endphp
                                                            : </strong>
                                                        {{ $guardian->title . ' ' . $guardian->first_name . ' ' . $guardian->last_name }}
                                                        @if ($guardian->occupation)
                                                            <strong>Occupation:</strong>{{ $guardian->occupation }},{{ $guardian->office_address }}
                                                        @endif
                                                        {{-- {{ $guardian }} --}}
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>
                                <li><strong>e. Present Address: </strong>{{ $presentAddress }}</li>
                                <li><strong>f. Permanent Address: </strong>{{ $permanentAddress }}</li>
                            </ul>
                        </div>
                    </div>


                </div>
            @endif
            @if ($value == 16)
                @if ($hide_blank && count($employee_acrs) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. ACR Grading/Number:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2"><span>Years</span></th>
                                        <th colspan="2">
                                            Grading
                                        </th>
                                        <th rowspan="2">Average Number</th>
                                        <th colspan="2">Recommendation</th>
                                        <th colspan="2">Name</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>Initiative Officer</th>
                                        <th>Higher Officer</th>
                                        <th>IO</th>
                                        <th>HO</th>
                                        <th>IO</th>
                                        <th>HO</th>
                                    </tr>

                                </thead>
                                <tbody>

                                    @forelse ($employee_acrs as $acr_data)
                                        @php
                                            $average_number = 0;
                                            $average_number += ($acr_data->initiative_officer + $acr_data->higher_officer) / 2;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $acr_data->singleInstitute ? $acr_data->singleInstitute->institute_alias : ' ' }}

                                            <td>
                                                {{ $acr_data->year }}
                                            </td>
                                            <td>
                                                {{ $acr_data->initiative_officer }} %
                                            </td>
                                            <td>
                                                {{ $acr_data->higher_officer }} %
                                            </td>
                                            <td>{{ $average_number }}</td>
                                            <td>
                                                @switch($acr_data->io)
                                                        @case(1)
                                                            <span class="text-success">Recommended</span>
                                                        @break

                                                        @case(2)
                                                            <span class="text-danger"> Not Recommended</span>
                                                        @break
                                                    @endswitch
                                            </td>
                                            <td>
                                                @switch($acr_data->ho)
                                                @case(1)
                                                    <span class="text-success">Recommended</span>
                                                @break

                                                @case(2)
                                                    <span class="text-danger"> Not Recommended</span>
                                                @break
                                            @endswitch
                                            </td>
                                            <td>
                                                {{ $acr_data->employeeIoName ? $acr_data->employeeIoName->title . ' ' . $acr_data->employeeIoName->first_name . ' ' . $acr_data->employeeIoName->last_name : ' ' }}
                                            </td>
                                            <td>
                                                {{ $acr_data->employeeHoName ? $acr_data->employeeHoName->title . ' ' . $acr_data->employeeHoName->first_name . ' ' . $acr_data->employeeHoName->last_name : ' ' }}
                                            </td>


                                            <td>
                                                {{ $acr_data->remarks }}
                                            </td>


                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. ACR Grading/Number:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2"><span>Years</span></th>
                                        <th colspan="2">
                                            Grading
                                        </th>
                                        <th rowspan="2">Average Number</th>
                                        <th colspan="2">Recommendation</th>
                                        <th colspan="2">Name</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>Initiative Officer</th>
                                        <th>Higher Officer</th>
                                        <th>IO</th>
                                        <th>HO</th>
                                        <th>IO</th>
                                        <th>HO</th>
                                    </tr>

                                </thead>
                                <tbody>

                                    @forelse ($employee_acrs as $acr_data)
                                        @php
                                            $average_number = 0;
                                            $average_number += ($acr_data->initiative_officer + $acr_data->higher_officer) / 2;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $acr_data->singleInstitute ? $acr_data->singleInstitute->institute_alias : ' ' }}

                                            <td>
                                                {{ $acr_data->year }}
                                            </td>
                                            <td>
                                                {{ $acr_data->initiative_officer }} %
                                            </td>
                                            <td>
                                                {{ $acr_data->higher_officer }} %
                                            </td>
                                            <td>{{ $average_number }}</td>
                                            <td>
                                                @switch($acr_data->io)
                                                    @case(1)
                                                        <span class="text-success">Recommended</span>
                                                    @break

                                                    @case(2)
                                                        <span class="text-danger"> Not Recommended</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($acr_data->ho)
                                                    @case(1)
                                                        <span class="text-success">Recommended</span>
                                                    @break

                                                    @case(2)
                                                        <span class="text-danger"> Not Recommended</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                {{ $acr_data->employeeIoName ? $acr_data->employeeIoName->title . ' ' . $acr_data->employeeIoName->first_name . ' ' . $acr_data->employeeIoName->last_name : ' ' }}
                                            </td>
                                            <td>
                                                {{ $acr_data->employeeHoName ? $acr_data->employeeHoName->title . ' ' . $acr_data->employeeHoName->first_name . ' ' . $acr_data->employeeHoName->last_name : ' ' }}
                                            </td>


                                            <td>
                                                {{ $acr_data->remarks }}
                                            </td>


                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 17)
                @if ($hide_blank && count($publications) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Publication:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Publication Title</th>
                                        <th>
                                            Publication Description
                                        </th>
                                        <th>Publication Time</th>
                                        <th>Remarks</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($publications as $pub_data)
                                        @php
                                            $flag = true;
                                        @endphp
                                        @foreach ($pub_data->publicationEditions as $edition)
                                            <tr>
                                                @if ($flag)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $loop->index + 1 }}</td>
                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $pub_data->singleInstitute ? $pub_data->singleInstitute->institute_alias : ' ' }}
                                                    </td>

                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $pub_data->publication_title }}
                                                    </td>
                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $pub_data->publication_description }}
                                                    </td>
                                                @endif
                                                <td>
                                                    <strong>{{ $edition->editions }}: </strong>
                                                    <span>{{ date('d/m/Y', strtotime($edition->date)) }}</span> 

                                                </td>

                                                <td>
                                                    {{ $edition->remarks }} <br>
                                                </td>

                                            </tr>
                                        @endforeach
                                        @empty
                                        <tr>
                                            <td colspan="6">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Publication:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Publication Title</th>
                                        <th>
                                            Publication Description
                                        </th>
                                        <th>Publication Time</th>
                                        <th>Remarks</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($publications as $pub_data)
                                        @php
                                            $flag = true;
                                        @endphp
                                        @foreach ($pub_data->publicationEditions as $edition)
                                            <tr>
                                                @if ($flag)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $loop->index + 1 }}</td>
                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $pub_data->singleInstitute ? $pub_data->singleInstitute->institute_alias : ' ' }}
                                                    </td>

                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $pub_data->publication_title }}
                                                    </td>
                                                    <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                                        {{ $pub_data->publication_description }}
                                                    </td>
                                                @endif
                                                <td>
                                                    <strong>{{ $edition->editions }}: </strong>
                                                    <span>{{ date('d/m/Y', strtotime($edition->date)) }}</span> 

                                                </td>

                                                <td>
                                                    {{ $edition->remarks }} 
                                                </td>

                                            </tr>
                                        @endforeach
                                        @empty
                                        <tr>
                                            <td colspan="6">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 18)
                @if ($hide_blank && count($allDisciplines) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Discipline:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Occurrence Date</th>
                                        <th>Place/Location</th>
                                        <th>Description</th>
                                        <th>Punishment Category</th>
                                        <th>Punishment By</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allDisciplines as $discipline)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $discipline->singleInstitute ? $discipline->singleInstitute->institute_alias : ' ' }}
                                            <td>
                                                {{ date('d/m/Y', strtotime($discipline->occurrence_date)) }}
                                            </td>
                                            <td>{{ $discipline->place }}</td>
                                            <td>{{ $discipline->description }}</td>
                                            <td>
                                                @php
                                                    $english_Category = explode('-', $discipline->punishment_category);
                                                @endphp
                                                @if (count($english_Category) > 1)
                                                    {{ $english_Category[1] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($discipline->punishment_by_select)
                                                    {{ $discipline->singlePunishmentBy->title . ' ' . $discipline->singlePunishmentBy->first_name . ' ' . $discipline->singlePunishmentBy->middle_name . ' ' . $discipline->singlePunishmentBy->last_name }}
                                                    <br>
                                                @endif
                                                @if ($discipline->punishment_by_write)
                                                    {{ $discipline->punishment_by_write }}
                                                    <br>
                                                @endif
                                                Date:
                                                {{ date('d/m/Y', strtotime($discipline->punishment_date)) }}
                                            </td>
                                            <td>{{ $discipline->remarks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Discipline:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Occurrence Date</th>
                                        <th>Place/Location</th>
                                        <th>Description</th>
                                        <th>Punishment Category</th>
                                        <th>Punishment By</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allDisciplines as $discipline)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $discipline->singleInstitute ? $discipline->singleInstitute->institute_alias : ' ' }}
                                            <td>
                                                {{ date('d/m/Y', strtotime($discipline->occurrence_date)) }}
                                            </td>
                                            <td>{{ $discipline->place }}</td>
                                            <td>{{ $discipline->description }}</td>
                                            <td>
                                                @php
                                                    $english_Category = explode('-', $discipline->punishment_category);
                                                @endphp
                                                @if (count($english_Category) > 1)
                                                    {{ $english_Category[1] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($discipline->punishment_by_select)
                                                    {{ $discipline->singlePunishmentBy->title . ' ' . $discipline->singlePunishmentBy->first_name . ' ' . $discipline->singlePunishmentBy->middle_name . ' ' . $discipline->singlePunishmentBy->last_name }}
                                                    <br>
                                                @endif
                                                @if ($discipline->punishment_by_write)
                                                    {{ $discipline->punishment_by_write }}
                                                    <br>
                                                @endif
                                                Date:
                                                {{ date('d/m/Y', strtotime($discipline->punishment_date)) }}
                                            </td>
                                            <td>{{ $discipline->remarks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 19)
                @if ($hide_blank && count($exam_years_groups) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Contribution To Board Result:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th><span>Years</span></th>
                                        <th><span>Cadet College</span></th>
                                        <th>Exam Name</th>
                                        <th>Total Cadet</th>
                                        <th>GPA-5</th>
                                        <th>Not GPA-5</th>
                                        <th>Remarks</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($exam_years_groups as $key => $groups)
                                        @php
                                            $flag = true;
                                        @endphp
                                        @foreach ($groups as $group)
                                            <tr>
                                                @if ($flag)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    <th rowspan="{{ count($groups) }}">{{ $key }}
                                                    </th>
                                                @endif
                                                <td>{{ $group->singleInstitute ? $group->singleInstitute->institute_alias : ' ' }}

                                                <td>{{ $group->exam_name }}</td>
                                                <td>{{ $group->total_cadet }}</td>
                                                <td>
                                                    @php
                                                        $total_gpa = json_decode($group->total_gpa);
                                                    @endphp
                                                    @if ($group->gpa_type == 0)
                                                        @foreach ($total_gpa as $gpa)
                                                            {{ $gpa->gpa }}
                                                        @endforeach
                                                    @endif
                                                    @if ($group->gpa_type == 1)
                                                        @foreach ($total_gpa as $gpa)
                                                            <strong>{{ $group->getSubject($gpa->subject) }}:
                                                            </strong>{{ $gpa->gpa }} <br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $not_total_gpa = json_decode($group->not_total_gpa);
                                                    @endphp
                                                    @if ($group->gpa_type == 0)
                                                        @foreach ($not_total_gpa as $gpa)
                                                            {{ $gpa->gpa }}
                                                        @endforeach
                                                    @endif
                                                    @if ($group->gpa_type == 1)
                                                        @foreach ($not_total_gpa as $gpa)
                                                            <strong>{{ $group->getSubject($gpa->subject) }}:
                                                            </strong>{{ $gpa->gpa }}
                                                            <br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $group->remarks }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @empty
                                        <tr>
                                            <td colspan="7">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Contribution To Board Result:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th><span>Years</span></th>
                                        <th><span>Cadet College</span></th>
                                        <th>Exam Name</th>
                                        <th>Total Cadet</th>
                                        <th>GPA-5</th>
                                        <th>Not GPA-5</th>
                                        <th>Remarks</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($exam_years_groups as $key => $groups)
                                        @php
                                            $flag = true;
                                        @endphp
                                        @foreach ($groups as $group)
                                            <tr>
                                                @if ($flag)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    <th rowspan="{{ count($groups) }}">{{ $key }}
                                                    </th>
                                                @endif
                                                <td>{{ $group->singleInstitute ? $group->singleInstitute->institute_alias : ' ' }}

                                                <td>{{ $group->exam_name }}</td>
                                                <td>{{ $group->total_cadet }}</td>
                                                <td>
                                                    @php
                                                        $total_gpa = json_decode($group->total_gpa);
                                                    @endphp
                                                    @if ($group->gpa_type == 0)
                                                        @foreach ($total_gpa as $gpa)
                                                            {{ $gpa->gpa }}
                                                        @endforeach
                                                    @endif
                                                    @if ($group->gpa_type == 1)
                                                        @foreach ($total_gpa as $gpa)
                                                            <strong>{{ $group->getSubject($gpa->subject) }}:
                                                            </strong>{{ $gpa->gpa }} <br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $not_total_gpa = json_decode($group->not_total_gpa);
                                                    @endphp
                                                    @if ($group->gpa_type == 0)
                                                        @foreach ($not_total_gpa as $gpa)
                                                            {{ $gpa->gpa }}
                                                        @endforeach
                                                    @endif
                                                    @if ($group->gpa_type == 1)
                                                        @foreach ($not_total_gpa as $gpa)
                                                            <strong>{{ $group->getSubject($gpa->subject) }}:
                                                            </strong>{{ $gpa->gpa }}
                                                            <br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $group->remarks }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @empty
                                        <tr>
                                            <td colspan="7">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 20)
                @if ($hide_blank && count($specialDuties) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Special Duty:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2">Description</th>
                                        <th colspan="2">
                                            Duration
                                        </th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>

                                        <th>From</th>
                                        <th>To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($specialDuties as $duty)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $duty->singleInstitute ? $duty->singleInstitute->institute_alias : ' ' }}
                                            <td>
                                                {{ $duty->description }}
                                            </td>
                                            <td>{{ $duty->start_date ? date('d/m/Y', strtotime($duty->start_date)) : ' ' }}
                                            </td>
                                            <td>{{ $duty->end_date ? date('d/m/Y', strtotime($duty->end_date)) : 'To This Day' }}
                                            </td>
                                            <td>{{ $duty->remarks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Special Duty:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2">Description</th>
                                        <th colspan="2">
                                            Duration
                                        </th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>

                                        <th>From</th>
                                        <th>To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($specialDuties as $duty)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $duty->singleInstitute ? $duty->singleInstitute->institute_alias : ' ' }}
                                            <td>
                                                {{ $duty->description }}
                                            </td>
                                            <td>{{ $duty->start_date ? date('d/m/Y', strtotime($duty->start_date)) : ' ' }}
                                            </td>
                                            <td>{{ $duty->end_date ? date('d/m/Y', strtotime($duty->end_date)) : 'To This Day' }}
                                            </td>
                                            <td>{{ $duty->remarks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 21)
                @if ($hide_blank && count($specialDuties) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Awards:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Award Name</th>
                                        <th>Award Description</th>
                                        <th>Awarded On</th>
                                        <th>Awarded By</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($awards as $award)
                                        @php
                                            $date = Carbon\Carbon::parse($award->awarded_on);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $award->institute->institute_alias }}</td>
                                            <td>{{ $award->name }}</td>
                                            <td>{{ $award->description }}</td>
                                            <td>
                                                @if ($award->awarded_on)
                                                    {{ $date->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($award->awarded_by_employee)
                                                    {{ $award->awardedBy->first_name }}
                                                    {{ $award->awardedBy->last_name }}
                                                @else
                                                    {{ $award->awarded_by_name }}
                                                @endif
                                            </td>
                                            <td>{{ $award->remarks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Awards:</strong>
                            <table id="myTable" class="table table-bordered table-striped  display" border="2">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Award Name</th>
                                        <th>Award Description</th>
                                        <th>Awarded On</th>
                                        <th>Awarded By</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($awards as $award)
                                        @php
                                            $date = Carbon\Carbon::parse($award->awarded_on);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $award->institute->institute_alias }}</td>
                                            <td>{{ $award->name }}</td>
                                            <td>{{ $award->description }}</td>
                                            <td>
                                                @if ($award->awarded_on)
                                                    {{ $date->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($award->awarded_by_employee)
                                                    {{ $award->awardedBy->first_name }}
                                                    {{ $award->awardedBy->last_name }}
                                                @else
                                                    {{ $award->awarded_by_name }}
                                                @endif
                                            </td>
                                            <td>{{ $award->remarks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @endif
            @endif
            @if ($value == 22)
                @if ($hide_blank && count($employeePromotions) > 0)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Previous Promotion Remarks:</strong>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Sl
                                        </th>
                                        <th class="align-middle">
                                            Promotion Board
                                        </th>
                                        <th class="align-middle">
                                            Board Remarks
                                        </th>

                                        <th>
                                            Reasoning
                                        </th>
                                        <th>
                                            Last promotion Date
                                        </th>
                                        <th>
                                            Promoted on
                                        </th>
                                        <th>
                                            Authorized By
                                        </th>
                                        <th>
                                            Status
                                        </th>

                                    </tr>
                                  
                                </thead>
                                <tbody>
                                    @forelse ($employeePromotions as $promotion)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            
                                            <td>

                                                {{ $promotion->promotion_board }}
                                            </td>
                                            <td>

                                                {{ $promotion->board_remarks }}
                                            </td>
                                            <td>

                                                {{ $promotion->reasoning }}
                                            </td>
                                            <td>

                                                {{  date('d/m/Y', strtotime($promotion->last_promotion_date)) }}
                                            </td>
                                            <td>
                                                @if ($promotion->status == 'approved')
                                                    {{ date('d/m/Y', strtotime($promotion->promotion_date)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($promotion->authorized)
                                                    {{ $promotion->authorized->name }}
                                                @endif

                                            </td>

                                            <td>


                                                @if ($promotion->status == 'pending')
                                                    <span class="text-warning">Pending</span>
                                                @elseif($promotion->status == 'approved')
                                                    <span class="text-success">Approved</span>
                                                @else
                                                    <span class="text-danger">Not Approved</span>
                                                @endif
                                               
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @elseif (!$hide_blank)
                    <div class="clearfix">
                        <div class="top-table">
                            <strong>{{ $loop->index + 1 }}. Previous Promotion Remarks:</strong>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Sl
                                        </th>
                                        <th class="align-middle">
                                            Promotion Board
                                        </th>
                                        <th class="align-middle">
                                            Board Remarks
                                        </th>

                                        <th>
                                            Reasoning
                                        </th>
                                        <th>
                                            Last promotion Date
                                        </th>
                                        <th>
                                            Promoted on
                                        </th>
                                        <th>
                                            Authorized By
                                        </th>
                                        <th>
                                            Status
                                        </th>

                                    </tr>
                                  
                                </thead>
                                <tbody>
                                    @forelse ($employeePromotions as $promotion)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            
                                            <td>

                                                {{ $promotion->promotion_board }}
                                            </td>
                                            <td>

                                                {{ $promotion->board_remarks }}
                                            </td>
                                            <td>

                                                {{ $promotion->reasoning }}
                                            </td>
                                            <td>

                                                {{  date('d/m/Y', strtotime($promotion->last_promotion_date)) }}
                                            </td>
                                            <td>
                                                @if ($promotion->status == 'approved')
                                                    {{ date('d/m/Y', strtotime($promotion->promotion_date)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($promotion->authorized)
                                                    {{ $promotion->authorized->name }}
                                                @endif

                                            </td>

                                            <td>


                                                @if ($promotion->status == 'pending')
                                                    <span class="text-warning">Pending</span>
                                                @elseif($promotion->status == 'approved')
                                                    <span class="text-success">Approved</span>
                                                @else
                                                    <span class="text-danger">Not Approved</span>
                                                @endif
                                               
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8">Data Not Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                @endif
            @endif
        @endforeach



        </div>


    </body>

    </html>
