<style>
    .input {
        width: 190px !important;
    }

    table {
        margin-top: 20px;
    }

    .checkbox {
        width: 50px !important;
    }

    #allCheckbox {
        width: 20px !important;
        text-align: start !important;
        /* margin-left: 14px */
    }

    .employee_infomation {
        padding: 0;
    }

    .employee_infomation li {
        list-style: none;
        padding: 5px 0;
    }

    .family_infomation li {
        list-style: none;
        padding: 5px 0;
    }

    .childe_ul {
        margin-left: 147px;
        margin-top: -25px;
    }

</style>

<div class="box">
    <div class="box-body">
        <div class="box-body">
            <div class="row" style="border-bottom: 3px solid rgb(4, 49, 13); padding-bottom:5px;">
                <h1 class="text-center m-0 p-0">HR Details Report</h1>
                <div class="col-sm-10">
                    <h5 class="text-left">
                        <strong>Employee ID: </strong> {{ $employee->singleUser->username }}
                    </h5>
                    <h5 class="text-left">
                        <strong>Employee Name: </strong>
                        {{ $employee->title . ' ' . $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name }}
                    </h5>
                    <h5 class="text-left">
                        <strong>Department: </strong>
                        {{ $employee->singleDepartment ? $employee->singleDepartment->name : '' }} <span
                            style="margin-left: 10px;"><strong>Designation: </strong>
                            {{ $employee->singleDesignation ? $employee->singleDesignation->name : ' ' }}</span>
                    </h5>
                    <h5 class="text-left">
                        <strong>Position Serial: </strong> {{ $employee->position_serial }} <span
                            style="margin-left: 10px;"><strong>Central Position Serial: </strong>
                            {{ $employee->central_position_serial }}</span>
                    </h5>
                    <h5 class="text-left">
                        <strong>Institute: </strong>
                        {{ $employee->getSingleInstitute ? $employee->getSingleInstitute->institute_name : '' }}
                    </h5>

                </div>
                <div class="col-sm-2">

                    @if ($employee->singelAttachment('PROFILE_PHOTO'))
                        <img class="center-block img-thumbnail img-responsive user_img"
                            src="{{ URL::asset('assets/users/images/' . $employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name) }}"
                            alt="No Image">
                    @elseif($employee->category == 1)
                        <img class="center-block img-thumbnail img-responsive user_img"
                            src="{{ URL::asset('assets/users/images/user-teaching.png') }}" alt="No Image">
                    @elseif($employee->category == 2)
                        <img class="center-block img-thumbnail img-responsive user_img"
                            src="{{ URL::asset('assets/users/images/user-non-teaching.png') }}" alt="No Image">
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
                                <li style="padding-top:{{ count($promotions) ? '0px' : '20px' }};">
                                    <strong>{{ $loop->index + 1 }}. Current College:</strong>
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
                                    {{ $employee->phone }} </li>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. Educational Qualifications:</strong>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Sl</th>
                                        <th>Type</th>
                                        <th>Year</th>
                                        <th>Group/Division</th>
                                        <th>Name</th>
                                        <th>Board/University</th>
                                        <th>Institute Address</th>
                                        <th>Marks</th>
                                        <th>Attachment</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($qualifications as $qualification)
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
                                                <td>
                                                    @if (str_contains($qualification->qualification_attachment, 'jpeg') || str_contains($qualification->qualification_attachment, 'jpg') || str_contains($qualification->qualification_attachment, 'png'))
                                                        <a href="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($qualification->qualification_attachment, 'pdf'))
                                                        <a href="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $qualification->qualification_attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif (!$hide_blank)
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. Educational Qualifications:</strong>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Sl</th>
                                        <th>Type</th>
                                        <th>Year</th>
                                        <th>Group/Division</th>
                                        <th>Name</th>
                                        <th>Board/University</th>
                                        <th>Institute Address</th>
                                        <th>Marks</th>
                                        <th>Attachment</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($qualifications as $qualification)
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
                                                <td>
                                                    @if (str_contains($qualification->qualification_attachment, 'jpeg') || str_contains($qualification->qualification_attachment, 'jpg') || str_contains($qualification->qualification_attachment, 'png'))
                                                        <a href="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($qualification->qualification_attachment, 'pdf'))
                                                        <a href="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $qualification->qualification_attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($value == 12)
                    @if ($hide_blank && count($allTraining) > 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. Details of In-service Training:</strong>
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>

                                            <th rowspan="2">Sl</th>
                                            <th rowspan="2">Cadet College</th>
                                            <th rowspan="2">Training Name</th>
                                            <th rowspan="2">Training Institute</th>
                                            <th colspan="3" style="padding: 0;">Training Period</th>
                                            <th rowspan="2">Grading</th>
                                            <th rowspan="2">Remarks</th>
                                            <th rowspan="2">Attachment</th>
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
                                                </td>
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
                                                <td>
                                                    @if (str_contains($training->attachment, 'jpeg') || str_contains($training->attachment, 'jpg') || str_contains($training->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($training->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $training->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Data Not Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif (!$hide_blank)
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. Details of In-service Training:</strong>
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>

                                            <th rowspan="2">Sl</th>
                                            <th rowspan="2">Cadet College </th>
                                            <th rowspan="2">Training Name</th>
                                            <th rowspan="2">Training Institute</th>
                                            <th colspan="3" style="padding: 0;">Training Period</th>
                                            <th rowspan="2">Grading</th>
                                            <th rowspan="2">Remarks</th>
                                            <th rowspan="2">Attachment</th>
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
                                                </td>
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
                                                <td>
                                                    @if (str_contains($training->attachment, 'jpeg') || str_contains($training->attachment, 'jpg') || str_contains($training->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($training->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $training->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Data Not Found</td>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. Experience in Cadet College(s):</strong>
                                <table class="table table-bordered text-center">
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
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. Experience in Cadet College(s):</strong>
                                <table class="table table-bordered text-center">
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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Remaining Tenure:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <th>Current Designation</th>
                                    <th>Current Designation Job Duration</th>
                                    <th>Remaining tenure</th>

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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Family Details:</strong>
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
                @endif


                @if ($value == 16)
                    @if ($hide_blank && count($employee_acrs) > 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>{{ $loop->index + 1 }}. ACR Grading/Number:</strong>
                                <table class="table table-bordered text-center">
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
                                            <th rowspan="2">Attachment</th>
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
                                                </td>
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
                                                <td>
                                                    @if (str_contains($acr_data->attachment, 'jpeg') || str_contains($acr_data->attachment, 'jpg') || str_contains($acr_data->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($acr_data->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $acr_data->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">Data Not Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        @elseif (!$hide_blank)
                            <div class="row">
                                <div class="col-sm-12">
                                    <strong>{{ $loop->index + 1 }}. ACR Grading/Number:</strong>
                                    <table class="table table-bordered text-center">
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
                                                <th rowspan="2">Attachment</th>
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
                                                    </td>
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
                                                    <td>
                                                        @if (str_contains($acr_data->attachment, 'jpeg') || str_contains($acr_data->attachment, 'jpg') || str_contains($acr_data->attachment, 'png'))
                                                            <a href="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                                                    id="attach_img" width="40" alt="">
                                                            </a>
                                                        @elseif (str_contains($acr_data->attachment, 'pdf'))
                                                            <a href="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                                                target="_blank">
                                                                <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                    title="{{ $acr_data->attachment }}"
                                                                    aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    </td>

                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="12">Data Not Found</td>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Publication:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Publication Title</th>
                                        <th>
                                            Publication Description
                                        </th>
                                        <th>Publication Time</th>
                                        <th>Attachment</th>
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
                                                    @if (str_contains($edition->attachment, 'jpeg') || str_contains($edition->attachment, 'jpg') || str_contains($edition->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                                id="attach_img" width="30" alt="">
                                                        </a>
                                                    @elseif (str_contains($edition->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 20px;" class="fa fa-file-pdf-o"
                                                                title="{{ $edition->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @elseif (str_contains($edition->attachment, 'doc') || str_contains($edition->attachment, 'docx'))
                                                        <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 20px;" class="fa fa-file-text"
                                                                title="{{ $edition->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif

                                                </td>

                                                <td>
                                                    {{ $edition->remarks }} <br>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Publication:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Publication Title</th>
                                        <th>
                                            Publication Description
                                        </th>
                                        <th>Publication Time</th>
                                        <th>Attachment</th>
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
                                                    @if (str_contains($edition->attachment, 'jpeg') || str_contains($edition->attachment, 'jpg') || str_contains($edition->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                                id="attach_img" width="30" alt="">
                                                        </a>
                                                    @elseif (str_contains($edition->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 20px;" class="fa fa-file-pdf-o"
                                                                title="{{ $edition->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @elseif (str_contains($edition->attachment, 'doc') || str_contains($edition->attachment, 'docx'))
                                                        <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 20px;" class="fa fa-file-text"
                                                                title="{{ $edition->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif

                                                </td>

                                                <td>
                                                    {{ $edition->remarks }} <br>
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
            @if ($value == 18)
                @if ($hide_blank && count($allDisciplines) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Discipline:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <th>Sl</th>
                                    <th>Cadet College</th>
                                    <th>Occurrence Date</th>
                                    <th>Place/Location</th>
                                    <th>Description</th>
                                    <th>Punishment Category</th>
                                    <th>Punishment By</th>
                                    <th>Remarks</th>
                                    <th>Attachment</th>
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
                                            <td>{{ $discipline->punishment_category }}</td>
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
                                                {{ date('d/m/y', strtotime($discipline->punishment_date)) }}
                                            </td>
                                            <td>{{ $discipline->remarks }}</td>
                                            <td>
                                                @if (str_contains($discipline->attachment, 'jpeg') || str_contains($discipline->attachment, 'jpg') || str_contains($discipline->attachment, 'png'))
                                                    <a href="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                                            id="attach_img" width="40" alt="">
                                                    </a>
                                                @elseif (str_contains($discipline->attachment, 'pdf'))
                                                    <a href="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                                        target="_blank">
                                                        <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                            title="{{ $discipline->attachment }}"
                                                            aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>


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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Discipline:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <th>Sl</th>
                                    <th>Cadet College</th>
                                    <th>Occurrence Date</th>
                                    <th>Place/Location</th>
                                    <th>Description</th>
                                    <th>Punishment Category</th>
                                    <th>Punishment By</th>
                                    <th>Remarks</th>
                                    <th>Attachment</th>
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
                                            <td>{{ $discipline->punishment_category }}</td>
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
                                            <td>
                                                @if (str_contains($discipline->attachment, 'jpeg') || str_contains($discipline->attachment, 'jpg') || str_contains($discipline->attachment, 'png'))
                                                    <a href="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                                            id="attach_img" width="40" alt="">
                                                    </a>
                                                @elseif (str_contains($discipline->attachment, 'pdf'))
                                                    <a href="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                                        target="_blank">
                                                        <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                            title="{{ $discipline->attachment }}"
                                                            aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>


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
            @if ($value == 19)
                @if ($hide_blank && count($exam_years_groups) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Contribution to Board Result:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th><span>Years</span></th>
                                        <th><span>Cadet College</span></th>
                                        <th>Exam Name</th>
                                        <th>Total Cadet</th>
                                        <th>GPA-5</th>
                                        <th>Not GPA-5</th>
                                        <th>Remarks</th>
                                        <th>Attachment</th>
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
                                                <td>
                                                    @if (str_contains($group->attachment, 'jpeg') || str_contains($group->attachment, 'jpg') || str_contains($group->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($group->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $group->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Contribution to Board Result:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th><span>Years</span></th>
                                        <th><span>Cadet College</span></th>
                                        <th>Exam Name</th>
                                        <th>Total Cadet</th>
                                        <th>GPA-5</th>
                                        <th>Not GPA-5</th>
                                        <th>Remarks</th>
                                        <th>Attachment</th>
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
                                                <td>
                                                    @if (str_contains($group->attachment, 'jpeg') || str_contains($group->attachment, 'jpg') || str_contains($group->attachment, 'png'))
                                                        <a href="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                                                id="attach_img" width="40" alt="">
                                                        </a>
                                                    @elseif (str_contains($group->attachment, 'pdf'))
                                                        <a href="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                                            target="_blank">
                                                            <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                                title="{{ $group->attachment }}"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
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
            @if ($value == 20)
                @if ($hide_blank && count($specialDuties) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Special Duty:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2">Description</th>
                                        <th colspan="2">
                                            Duration
                                        </th>
                                        <th rowspan="2">Remarks</th>
                                        <th rowspan="2">Attachment</th>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To &nbsp;&nbsp;</th>
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
                                            <td>
                                                @if (str_contains($duty->attachment, 'jpeg') || str_contains($duty->attachment, 'jpg') || str_contains($duty->attachment, 'png'))
                                                    <a href="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                                            id="attach_img" width="40" alt="">
                                                    </a>
                                                @elseif (str_contains($duty->attachment, 'pdf'))
                                                    <a href="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                                        target="_blank">
                                                        <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                            title="{{ $duty->attachment }}"
                                                            aria-hidden="true"></i>
                                                    </a>
                                                @endif


                                            </td>



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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Special Duty:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Cadet College</th>
                                        <th rowspan="2">Description</th>
                                        <th colspan="2">
                                            Duration
                                        </th>
                                        <th rowspan="2">Remarks</th>
                                        <th rowspan="2">Attachment</th>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To &nbsp;&nbsp;</th>
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
                                            <td>
                                                @if (str_contains($duty->attachment, 'jpeg') || str_contains($duty->attachment, 'jpg') || str_contains($duty->attachment, 'png'))
                                                    <a href="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                                            id="attach_img" width="40" alt="">
                                                    </a>
                                                @elseif (str_contains($duty->attachment, 'pdf'))
                                                    <a href="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                                        target="_blank">
                                                        <i style="font-size: 30px;" class="fa fa-file-pdf-o"
                                                            title="{{ $duty->attachment }}"
                                                            aria-hidden="true"></i>
                                                    </a>
                                                @endif


                                            </td>



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
            @if ($value == 21)
                @if ($hide_blank && count($awards) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Awards:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Award Name</th>
                                        <th>Award Description</th>
                                        <th>Awarded On</th>
                                        <th>Awarded By</th>
                                        <th>Attachment</th>
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
                                            <td>
                                                @if ($award->attachment)
                                                    <a href="{{ asset('/assets/Employee/awards/' . $award->attachment) }}"
                                                        target="_blank">{{ $award->attachment }}</a>
                                                @endif
                                            </td>
                                            <td>{{ $award->remarks }}</td>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Awards:</strong>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Cadet College</th>
                                        <th>Award Name</th>
                                        <th>Award Description</th>
                                        <th>Awarded On</th>
                                        <th>Awarded By</th>
                                        <th>Attachment</th>
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
                                            <td>
                                                @if ($award->attachment)
                                                    <a href="{{ asset('/assets/Employee/awards/' . $award->attachment) }}"
                                                        target="_blank">{{ $award->attachment }}</a>
                                                @endif
                                            </td>
                                            <td>{{ $award->remarks }}</td>
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
            @if ($value == 22)
                @if ($hide_blank && count($employeePromotions) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Previous Promotion Remarks:</strong>
                            <div class="table-responsive">

                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">
                                                Sl
                                            </th>
                                            <th rowspan="1" colspan="3" class="text-center bg-warning">
                                                Previous
                                            </th>
                                            <th rowspan="1" colspan="3" class="text-center bg-success">
                                                Current
                                            </th>

                                            <th rowspan="2" class="align-middle">
                                                Promotion Board
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                Board Remarks
                                            </th>

                                            <th rowspan="2">
                                                Reasoning
                                            </th>
                                            <th rowspan="2">
                                                Last promotion Date
                                            </th>
                                            <th rowspan="2">
                                                Promoted on
                                            </th>
                                            <th rowspan="2">
                                                Authorized By
                                            </th>
                                            <th rowspan="2" style="vertical-align: middle">
                                                Status
                                            </th>

                                        </tr>
                                        <tr>
                                            <th class=" bg-warning">
                                                College
                                            </th>
                                            <th class=" bg-warning">
                                                Department
                                            </th>
                                            <th class=" bg-warning">
                                                Designation
                                            </th>
                                            <th class="bg-success">
                                                College
                                            </th>
                                            <th class="bg-success">
                                                Department
                                            </th>
                                            <th class="bg-success">
                                                Designation
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employeePromotions as $promotion)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>

                                                    @if ($allCampus && isset($allInstitute[$promotion->prev_institute]))
                                                        {{ $allInstitute[$promotion->prev_institute]->institute_alias }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDept && isset($allDept[$promotion->previous_department]))
                                                        {{ $allDept[$promotion->previous_department]->name }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDesignation && isset($allDesignation[$promotion->previous_designation]))
                                                        {{ $allDesignation[$promotion->previous_designation]->name }}
                                                    @endif
                                                </td>

                                                <td>

                                                    @if ($allCampus && isset($allInstitute[$promotion->prev_institute]))
                                                        {{ $allInstitute[$promotion->prev_institute]->institute_alias }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDept && isset($allDept[$promotion->department]))
                                                        {{ $allDept[$promotion->department]->name }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDesignation && isset($allDesignation[$promotion->designation]))
                                                        {{ $allDesignation[$promotion->designation]->name }}
                                                    @endif
                                                </td>
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
                                                    @if ($promotion->status == 'pending')
                                                        @if (in_array('employee/promotion.edit', $pageAccessData))
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ route('employee.promotion.edit', $promotion->id) }}"
                                                                oncontextmenu="return false;"
                                                                data-target="#globalModal" data-toggle="modal"
                                                                data-modal-size="modal-lg"><i
                                                                    class="fa fa-pencil-square-o"
                                                                    aria-hidden="true"></i> Edit</a>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="14">Data Not Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif (!$hide_blank)
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>{{ $loop->index + 1 }}. Previous Promotion Remarks:</strong>
                            <div class="table-responsive">

                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">
                                                Sl
                                            </th>
                                            <th rowspan="1" colspan="3" class="text-center bg-warning">
                                                Previous
                                            </th>
                                            <th rowspan="1" colspan="3" class="text-center bg-success">
                                                Current
                                            </th>

                                            <th rowspan="2" class="align-middle">
                                                Promotion Board
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                Board Remarks
                                            </th>

                                            <th rowspan="2">
                                                Reasoning
                                            </th>
                                            <th rowspan="2">
                                                Last promotion Date
                                            </th>
                                            <th rowspan="2">
                                                Promoted on
                                            </th>
                                            <th rowspan="2">
                                                Authorized By
                                            </th>
                                            <th rowspan="2" style="vertical-align: middle">
                                                Status
                                            </th>

                                        </tr>
                                        <tr>
                                            <th class=" bg-warning">
                                                College
                                            </th>
                                            <th class=" bg-warning">
                                                Department
                                            </th>
                                            <th class=" bg-warning">
                                                Designation
                                            </th>
                                            <th class="bg-success">
                                                College
                                            </th>
                                            <th class="bg-success">
                                                Department
                                            </th>
                                            <th class="bg-success">
                                                Designation
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employeePromotions as $promotion)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>

                                                    @if ($allCampus && isset($allInstitute[$promotion->prev_institute]))
                                                        {{ $allInstitute[$promotion->prev_institute]->institute_alias }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDept && isset($allDept[$promotion->previous_department]))
                                                        {{ $allDept[$promotion->previous_department]->name }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDesignation && isset($allDesignation[$promotion->previous_designation]))
                                                        {{ $allDesignation[$promotion->previous_designation]->name }}
                                                    @endif
                                                </td>

                                                <td>

                                                    @if ($allCampus && isset($allInstitute[$promotion->prev_institute]))
                                                        {{ $allInstitute[$promotion->prev_institute]->institute_alias }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDept && isset($allDept[$promotion->department]))
                                                        {{ $allDept[$promotion->department]->name }}
                                                    @endif
                                                </td>
                                                <td>

                                                    @if ($allDesignation && isset($allDesignation[$promotion->designation]))
                                                        {{ $allDesignation[$promotion->designation]->name }}
                                                    @endif
                                                </td>
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

                                                    {{date('d/m/Y', strtotime($promotion->last_promotion_date)) }}
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
                                                <td colspan="14">Data Not Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
                    @endforeach
        </div>
    </div>
</div>
