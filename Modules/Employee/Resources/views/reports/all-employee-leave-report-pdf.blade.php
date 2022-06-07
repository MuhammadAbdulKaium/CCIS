<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .clearfix {
            overflow: auto;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .container{
            width: 90%;
            margin: 0 auto;
        }
        img{
            width: 100%;
        }
        .header{
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }
        .logo{
            width: 16%;
            float: left;
            height: 90px;
        }
        .headline{
            float: left;
            padding: 0 20px;
        }
        .info{
            margin: 15px 0;
        }
        .patient-info{
            margin: 15px 0;
            font-size: 17px;
        }
        .patient-info span{
            margin-right: 15px;
        }
        .content{
            margin: 50px 0;
        }
        table#FeesInvoiceTables a {
            color: #fff;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 5px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;

        }

        .footer{
            text-align: center;
            border-top: 1px solid #f1f1f1;
            padding: 20px 0;
        }
        img.stamp {
            width: 150px;
            position: absolute;
            top: 9px;
            left: 82px;
        }
        .invoice-type {
            padding: 8px;
            border-radius: 5px;
            background: #4caf50;
            color: #fff;
        }
        .border {
            border: 1px dotted;
            overflow: hidden;
        }
        h3.invoice-number {
            background: #4caf50;
            padding:5px;


            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="">
    <div class=" clearfix" style="padding: 0;margin: 0;width: 100%;">

        <div class="row">
            <div class="row prescription-rows" style="position: relative">
                <div class="col-sm-1 logo">
                    <img src="{{ public_path('/assets/users/images/'.$institute->logo) }}" alt="" style="width:70px;object-fit: cover" height="80px">
                </div>
                <div class="col-sm-6"  style="position: absolute;top: 55px">
                    <h4><b>{{ $institute->institute_name }}</b></h4>
                    <h5 style="margin-top: 0px;">{{ $institute->address2 }}</h5>
                    <br>
                </div>

                <div class="col-md-12">
                    <div class="card">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Emp ID</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>DOJ</th>
                                    <th>Career Age</th>
                                    @foreach($leaveStructure as $leaves)
                                        <th>
                                            {{$leaves->leave_name_alias}}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>

                                @if($searchData)
                                    <input type="hidden" value="{{$searchData}}" class="searchResult" id="searchResult">
                                    @foreach($searchData as $key =>$data)
                                        <tr>
                                            <td>
                                                {{$key+1}}
                                            </td>
                                            <td>{{$data->first_name}} {{$data->last_name}}</td>
                                            <td>{{$data->user_id}}</td>
                                            <td>{{$data->department()->name}}</td>
                                            <td>@if($data->designation()) {{$data->designation()->name}} @else N/A @endif</td>
                                            <td>{{$data->doj}}</td>
                                            <td>
                                                @php
                                                    $date1=date_create(date('y-m-d'));
                                                    $date2=date_create($data->doj);
                                                    $diff=date_diff($date2,$date1);
                                                    echo $diff->format('%y Year %m Month %d Day');
                                                @endphp
                                            </td>
                                            @foreach($leaveStructure as $leaves)
                                                @php
                                                    $leaveData=isset($leaveAssignData[$data->user_id])?$leaveAssignData[$data->user_id]->firstWhere('leave_structure_id',$leaves->id):null;
                                                    $leaveHistoryData=isset($leaveAssignHistoryData[$data->user_id])?$leaveAssignHistoryData[$data->user_id]->where('leave_structure_id',$leaves->id)->last():null;
                                                    $enjoyedLeave=isset($leaveApplications[$data->user_id])?$leaveApplications[$data->user_id]->where('leave_structure_id',$leaves->id)->sum('approve_for_date'):null;
                                                @endphp
                                                <td>
                                                    T: @if(isset($leaveHistoryData)) <span class="text-success"><b>{{$leaveHistoryData->leave_remain}}</b></span> @endif
                                                    E: @if(isset($enjoyedLeave)) <span class="text-success"> <b>{{$enjoyedLeave}} </b></span> @endif
                                                    R: @if(isset($leaveData)) <span class="text-success"> <b>{{$leaveData->leave_remain}} </b></span> @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
                        @endif
                    </div>
                </div>
        </div>


    </div>
</div>

</body>
</html>

