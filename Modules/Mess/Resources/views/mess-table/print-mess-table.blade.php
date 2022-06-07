<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mess Table</title>
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

        img {
            width: 100%;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 11%;
            float: left;
        }

        .headline {
            width: 85%;
            float: right;
            padding: 0 20px;
            text-align: left;
        }

        a {
            text-decoration: none
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .top-table {
            width: 100%;
        }

        .hidden_border {
            border-style: none !important;
            text-align: right !important;
            padding-right: 10px !important;
        }

        .terms {
            /* border-bottom: 2px solid black; */
            width: 149px;
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


        .footer {
            overflow: auto;
            margin-top: 0px;
        }

        .cadet_Name {
            margin-top: 0;
        }

        .footer-bottom {
            position: fixed;
            width: 100%;
            height: 100%;
            bottom: -100%;
        }

        .footer-bottom .left {
            width: 80%;
            float: left;
            color: rgb(145, 32, 32);
            font-size: 20px;
            font-weight: 500;
        }

        .footer-bottom .right {
            width: 20%;
            float: right;
            text-align: right;
            color: rgb(145, 32, 32);
            font-size: 20px;
            font-weight: 500;
        }

        .footer-bottom .right:after {
            content: counter(page);
        }

        .due {
            color: red;
        }

        .paid {
            color: green;
        }

        .mt_4 {
            margin-top: 24px;
        }


        .mess-table-holder {
            text-align: center;
        }

        .mess-table {
            /* display: inline-block; */
        }

        .mess-table-column {
            float: left;
        }

        .mess-table-seat {
            border: 1.99px solid;
            width: 50px;
            height: 39px;
            text-align: center;
            /* line-height: 50px; */
            padding: 2px;
            font-weight: bold;
        }

        .table-seat-username {
            /* vertical-align: middle; */
            display: inline-table;
        }

        .seat-no {
            font-size: 15px;
        }

        .username {
            font-size: 11px;
        }

        .mess-table-seat:hover {
            background: lightgray;
        }

        .mess-table-no-seat {
            width: 44px;
            height: 44px;
        }

        .searched-seat {
            background: yellow;
        }

        .select2-selection--single {
            height: 33px !important;
        }

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }

        .details {}

    </style>
</head>

<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
        </div>
        <div class="headline" style="margin-top: 40px;">
            <h2 class="cadet_Name">{{ $institute->institute_name }}({{ $campus->name }})</h2>
            <p>{{ $institute->address1 }} </p>
            <p>
                <strong>Phone:</strong>{{ $institute->phone }} <strong>Email:</strong> <a
                    href="{{ $institute->email }}" target="_blank">{{ $institute->email }}</a> <strong>Web:</strong>
                <a href=" {{ $institute->website }}" target="_blank"> {{ $institute->website }}</a>
            </p>
        </div>
    </div>
    <div class="content clearfix">

        <h1 class="text-center" style="margin-bottom: 0px; font-size:20px">Mess Table Report</h1>

        <div class="clearfix">

            <div class="bottom-table " style="margin-top: 40px">
                {{-- Top Tables --}}
                @foreach ($messTables->where('table_position', 'top') as $messTable)
                    <table class="clearfix" style="margin-top: 40px">
                        <tbody>
                            <tr>
                                <th>Table: {{ $messTable->table_name }}</th>

                            </tr>
                            <tr>
                                <td colspan="4">

                                    <div style="display: inline-block;">
                                        <div><b>Table Name: {{ $messTable->table_name }}</b></div>
                                        <div>Total Seats: {{ $messTable->total_seats }}</div>
                                        @if ($messTable->employee)
                                            <div>Concern HR: {{ $messTable->employee->first_name }}
                                                {{ $messTable->employee->last_name }}
                                                ({{ $messTable->employee->singleUser->username }})</div>
                                        @endif
                                        @php
                                            $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                                        @endphp
                                        <div>Empty Chairs: {{ $messTable->total_seats - $filledChairs }}</div>
                                    </div>
                                </td>
                                <td colspan="8">
                                    <div class="mess-table clearfix" >
                                        {{-- First High Chairs --}}
                                        <div class="mess-table-column " >
                                            <div class="mess-table-no-seat "></div>
                                            @for ($i = $messTable->total_high_seats / 2; $i > 0; $i--)
                                                @php
                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                    $previousSeat = $personData['previousSeat'];
                                                    $personTxt = $personData['personTxt'];
                                                    $user = $personData['user'];
                                                @endphp
                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                    data-toggle="tooltip" data-html="true"
                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                    data-table-id="{{ $messTable->id }}"
                                                    data-seat-no="{{ $i }}">
                                                    <div class="table-seat-username ">
                                                        <div class="seat-no">{{ $i }}</div>
                                                        @if ($user)
                                                            <div class="username">{{ $user->username }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endfor
                                            <div class="mess-table-no-seat "></div>
                                        </div>

                                        {{-- Normal Chairs --}}
                                        @for ($i = $messTable->total_high_seats / 2 + 1, $j = $messTable->total_seats; $i <= $messTable->total_seats / 2; $i++, $j--)
                                            <div class="mess-table-column" >
                                                @php
                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                    $previousSeat = $personData['previousSeat'];
                                                    $personTxt = $personData['personTxt'];
                                                    $user = $personData['user'];
                                                @endphp
                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                    data-toggle="tooltip" data-html="true"
                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                    data-table-id="{{ $messTable->id }}"
                                                    data-seat-no="{{ $i }}">
                                                    <div class="table-seat-username">
                                                        <div class="seat-no">{{ $i }}</div>
                                                        @if ($user)
                                                            <div class="username">{{ $user->username }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @for ($k = 0; $k < $messTable->total_high_seats / 2; $k++)
                                                    <div class="mess-table-no-seat"></div>
                                                @endfor
                                                @php
                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $j, $students, $employees);
                                                    $previousSeat = $personData['previousSeat'];
                                                    $personTxt = $personData['personTxt'];
                                                    $user = $personData['user'];
                                                @endphp
                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $j ? 'searched-seat' : '' }}"
                                                    data-toggle="tooltip" data-html="true"
                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                    data-table-id="{{ $messTable->id }}"
                                                    data-seat-no="{{ $j }}">
                                                    <div class="table-seat-username">
                                                        <div class="seat-no">{{ $j }}</div>
                                                        @if ($user)
                                                            <div class="username">{{ $user->username }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor

                                        {{-- Last High Chairs --}}
                                        <div class="mess-table-column">
                                            <div class="mess-table-no-seat"></div>
                                            @for ($k = 0; $k < $messTable->total_high_seats / 2; $k++)
                                                @php
                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                    $previousSeat = $personData['previousSeat'];
                                                    $personTxt = $personData['personTxt'];
                                                    $user = $personData['user'];
                                                @endphp
                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                    data-toggle="tooltip" data-html="true"
                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                    data-table-id="{{ $messTable->id }}"
                                                    data-seat-no="{{ $i }}">
                                                    <div class="table-seat-username">
                                                        <div class="seat-no">{{ $i++ }}</div>
                                                        @if ($user)
                                                            <div class="username">{{ $user->username }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endfor
                                            <div class="mess-table-no-seat"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
                @if (sizeof($houses)>0)
                    <table class="clearfix" style="margin: 20px 0;">
                        <thead>
                            <tr>
                                @foreach ($houses as $house)
                                    <th>{{ $house->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($houses as $house)
                                    <td>
                                        @foreach ($messTables->where('house_id', $house->id) as $messTable)
                                            <div class="clearfix"  style="padding: 20px 0;">


                                                <div class="clearfix" style="padding-bottom: 20px;">
                                                    <b>Table Name: {{ $messTable->table_name }}</b>
                                                    <br>
                                                    Total Seats: {{ $messTable->total_seats }}<br>
                                                    @if ($messTable->employee)
                                                        Concern HR: {{ $messTable->employee->first_name }}
                                                        {{ $messTable->employee->last_name }}
                                                        ({{ $messTable->employee->singleUser->username }})<br>
                                                    @endif
                                                    @php
                                                        $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                                                    @endphp
                                                    Empty Chairs: {{ $messTable->total_seats - $filledChairs }}
                                                </div>

                                                <div class="mess-table-holder ">
                                                    <div class="mess-table">
                                                        {{-- First High Chairs --}}
                                                        <div class="mess-table-column">
                                                            <div class="mess-table-no-seat"></div>
                                                            @for ($i = $messTable->total_high_seats / 2; $i > 0; $i--)
                                                                @php
                                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                                    $previousSeat = $personData['previousSeat'];
                                                                    $personTxt = $personData['personTxt'];
                                                                    $user = $personData['user'];
                                                                @endphp
                                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                                    data-toggle="tooltip" data-html="true"
                                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                                    data-table-id="{{ $messTable->id }}"
                                                                    data-seat-no="{{ $i }}">
                                                                    <div class="table-seat-username">
                                                                        <div class="seat-no">{{ $i }}
                                                                        </div>
                                                                        @if ($user)
                                                                            <div class="username">
                                                                                {{ $user->username }}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                            <div class="mess-table-no-seat"></div>
                                                        </div>

                                                        {{-- Normal Chairs --}}
                                                        @for ($i = $messTable->total_high_seats / 2 + 1, $j = $messTable->total_seats; $i <= $messTable->total_seats / 2; $i++, $j--)
                                                            <div class="mess-table-column">
                                                                @php
                                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                                    $previousSeat = $personData['previousSeat'];
                                                                    $personTxt = $personData['personTxt'];
                                                                    $user = $personData['user'];
                                                                @endphp
                                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                                    data-toggle="tooltip" data-html="true"
                                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                                    data-table-id="{{ $messTable->id }}"
                                                                    data-seat-no="{{ $i }}">
                                                                    <div class="table-seat-username">
                                                                        <div class="seat-no">{{ $i }}
                                                                        </div>
                                                                        @if ($user)
                                                                            <div class="username">
                                                                                {{ $user->username }}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @for ($k = 0; $k < $messTable->total_high_seats / 2; $k++)
                                                                    <div class="mess-table-no-seat"></div>
                                                                @endfor
                                                                @php
                                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $j, $students, $employees);
                                                                    $previousSeat = $personData['previousSeat'];
                                                                    $personTxt = $personData['personTxt'];
                                                                    $user = $personData['user'];
                                                                @endphp
                                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $j ? 'searched-seat' : '' }}"
                                                                    data-toggle="tooltip" data-html="true"
                                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                                    data-table-id="{{ $messTable->id }}"
                                                                    data-seat-no="{{ $j }}">
                                                                    <div class="table-seat-username">
                                                                        <div class="seat-no">{{ $j }}
                                                                        </div>
                                                                        @if ($user)
                                                                            <div class="username">
                                                                                {{ $user->username }}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor

                                                        {{-- Last High Chairs --}}
                                                        <div class="mess-table-column">
                                                            <div class="mess-table-no-seat"></div>
                                                            @for ($k = 0; $k < $messTable->total_high_seats / 2; $k++)
                                                                @php
                                                                    $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                                    $previousSeat = $personData['previousSeat'];
                                                                    $personTxt = $personData['personTxt'];
                                                                    $user = $personData['user'];
                                                                @endphp
                                                                <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                                    data-toggle="tooltip" data-html="true"
                                                                    title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                                    data-table-id="{{ $messTable->id }}"
                                                                    data-seat-no="{{ $i }}">
                                                                    <div class="table-seat-username">
                                                                        <div class="seat-no">{{ $i++ }}
                                                                        </div>
                                                                        @if ($user)
                                                                            <div class="username">
                                                                                {{ $user->username }}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                            <div class="mess-table-no-seat"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                @endif
                @foreach ($messTables->where('table_position', 'bottom') as $messTable)
                <table class="clearfix" style="margin-top: 40px">
                    <tbody>
                        <tr>
                            <th>Table: {{ $messTable->table_name }}</th>

                        </tr>
                        <tr>
                            <td colspan="4">

                                <div style="display: inline-block;">
                                    <div><b>Table Name: {{ $messTable->table_name }}</b></div>
                                    <div>Total Seats: {{ $messTable->total_seats }}</div>
                                    @if ($messTable->employee)
                                        <div>Concern HR: {{ $messTable->employee->first_name }}
                                            {{ $messTable->employee->last_name }}
                                            ({{ $messTable->employee->singleUser->username }})</div>
                                    @endif
                                    @php
                                        $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                                    @endphp
                                    <div>Empty Chairs: {{ $messTable->total_seats - $filledChairs }}</div>
                                </div>
                            </td>
                            <td colspan="8">
                                <div class="mess-table clearfix" >
                                    {{-- First High Chairs --}}
                                    <div class="mess-table-column " >
                                        <div class="mess-table-no-seat "></div>
                                        @for ($i = $messTable->total_high_seats / 2; $i > 0; $i--)
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                data-toggle="tooltip" data-html="true"
                                                title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                data-table-id="{{ $messTable->id }}"
                                                data-seat-no="{{ $i }}">
                                                <div class="table-seat-username ">
                                                    <div class="seat-no">{{ $i }}</div>
                                                    @if ($user)
                                                        <div class="username">{{ $user->username }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endfor
                                        <div class="mess-table-no-seat "></div>
                                    </div>

                                    {{-- Normal Chairs --}}
                                    @for ($i = $messTable->total_high_seats / 2 + 1, $j = $messTable->total_seats; $i <= $messTable->total_seats / 2; $i++, $j--)
                                        <div class="mess-table-column" >
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                data-toggle="tooltip" data-html="true"
                                                title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                data-table-id="{{ $messTable->id }}"
                                                data-seat-no="{{ $i }}">
                                                <div class="table-seat-username">
                                                    <div class="seat-no">{{ $i }}</div>
                                                    @if ($user)
                                                        <div class="username">{{ $user->username }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            @for ($k = 0; $k < $messTable->total_high_seats / 2; $k++)
                                                <div class="mess-table-no-seat"></div>
                                            @endfor
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $j, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $j ? 'searched-seat' : '' }}"
                                                data-toggle="tooltip" data-html="true"
                                                title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                data-table-id="{{ $messTable->id }}"
                                                data-seat-no="{{ $j }}">
                                                <div class="table-seat-username">
                                                    <div class="seat-no">{{ $j }}</div>
                                                    @if ($user)
                                                        <div class="username">{{ $user->username }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endfor

                                    {{-- Last High Chairs --}}
                                    <div class="mess-table-column">
                                        <div class="mess-table-no-seat"></div>
                                        @for ($k = 0; $k < $messTable->total_high_seats / 2; $k++)
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ $previousSeat ? 'text-success' : 'text-danger' }} {{ $personSeatNo == $i ? 'searched-seat' : '' }}"
                                                data-toggle="tooltip" data-html="true"
                                                title="{{ $personTxt ? $personTxt : 'Empty' }}"
                                                data-table-id="{{ $messTable->id }}"
                                                data-seat-no="{{ $i }}">
                                                <div class="table-seat-username">
                                                    <div class="seat-no">{{ $i++ }}</div>
                                                    @if ($user)
                                                        <div class="username">{{ $user->username }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endfor
                                        <div class="mess-table-no-seat"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endforeach


            </div>

        </div>
    </div>

    <div class="footer-bottom clearfix">
        <p class="left">
            Printed from {{ $institute->institute_alias }} ICT {{ Auth::user()->username }}, on
            {{ date('Y/m/d H:i:s') }}
        </p>
        <p class="right">
            Page 1 of
        </p>
    </div>
</body>

</html>
