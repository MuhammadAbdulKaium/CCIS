<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$house->name}}</title>
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
            width: 10%;
            float: left;
        }

        .headline {
            width: 82%;
            float: right;
            padding: 0 20px;
            text-align: left;
            margin-top: 30px;
        }

        td {
            font-size: 14px;
            padding: 3px 0;
        }

        th {
            font-size: 13px !important;
        }

        a {
            text-decoration: none
        }



        table {
            width: 100%;
            border-collapse: collapse;
        }

        .bottom-table table {
            border-collapse: collapse;

        }
      
        .bottom-table table th,
        .bottom-table table td {
            text-align: start;
            padding: 20px 0;
        }

        .hidden_border {
            border-style: none !important;
            text-align: right !important;
            padding-right: 10px !important;
        }

        .terms {
            border-bottom: 2px solid black;
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

        .col-width {
            width: 160px !important;
            display: inline-block;
        }
        .text-danger{
            color: red;
        }
        .house_table tr,
    .house_table td,
    .house_table th{
        border: 1px solid gray;
        text-align: center;
    }

    </style>
</head>

<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
        </div>
        <div class="headline">
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

        <h1 class="text-center" style="margin-bottom: 0px; font-size:20px">{{$house->name}} Report</h1>

        <div class="clearfix">
            <h5><b>House Master:</b> {{$house->houseMaster->first_name}} {{$house->houseMaster->last_name}} ({{ $house->houseMaster->singleUser->username }})
                @if ($house->housePrefect)
                | <b>House Prefect:</b> {{$house->housePrefect->first_name}} {{$house->housePrefect->last_name}} ({{ $house->housePrefect->singleUser->username }})
                @endif
            </h5>
            <div class="bottom-table clearfix" style="margin-top: 40px">
                <table class="house_table">
                    <tbody>  
                        @for ($i = 1; $i <= $house->no_of_floors; $i++)
                        @isset($rooms[$i])
                            <tr>
                                <th >Floor {{$i}}</th>
                                @foreach ($rooms[$i] as $room)
                                    <td>
                                        <b>{{$room->name}}</b>
                                        @php
                                            $beds = $roomStudents->where('floor_no', $i)->where('room_id', $room->id);
                                        @endphp
                                        @for ($j = 1; $j <= $room->no_of_beds; $j++)
                                            @php
                                                $bed = $beds->firstWhere('bed_no', $j);
                                            @endphp
                                            <div class="clearfix">Bed {{$j}}: 
                                                @if ($bed)
                                                    {{$bed->student->first_name}}
                                                    {{$bed->student->last_name}}
                                                    ({{ $bed->student->singleUser->username }}),
                                                    {{ $bed->student->singleBatch->batch_name }} - 
                                                    {{ $bed->student->singleSection->section_name }}
                                                @else
                                                    <span class="text-danger">Unassigned</span>
                                                @endif
                                            </div>
                                        @endfor
                                    </td>
                                @endforeach
                                @for ($j=sizeof($rooms[$i]); $j<=$maxTd; $j++)
                                    <td></td>
                                @endfor
                            </tr>
                             @endisset
                        @endfor
                    </tbody>
                </table>
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
