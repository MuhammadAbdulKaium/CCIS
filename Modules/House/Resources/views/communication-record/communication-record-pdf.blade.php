<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Communication Record</title>
    <style>
        table, td, th {
        border: 1px solid black;
        text-align: center;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        }

        .clearfix {
            overflow: auto;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
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
        }
        .headline{
            width: 40%;
            float: left;
            padding: 0 20px;
        }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
        </div>
        <div class="headline">
            <h3>{{ $institute->institute_name }}</h3>
            <p>{{ $institute->address2 }}</p>
        </div>
    </div>
    <table>
        <caption><h3>Communication Records of {{ $house->name }}</h3></caption>
        <thead>
            <tr>
                <th>Cadet</th>
                <th>Cadet ID</th>
                <th>Admission Year</th>
                <th>Academic Year</th>
                <th>Class</th>
                <th>Form</th>
                <th>No Of Calls</th>
                <th>Mode</th>
                <th>Date-Times</th>
                <th>Call Duration</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedCommunicationRecords as $groupedCommunicationRecord)
                @php
                    $totalTime = 0;
                    foreach($groupedCommunicationRecord as $CommunicationRecord){
                        $totalTime += (strtotime($CommunicationRecord->to_time) - strtotime($CommunicationRecord->from_time))/60;
                    }
                @endphp
                <tr>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->student->first_name }} {{ $groupedCommunicationRecord[0]->student->last_name }}</td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->student_id }}</td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->admissionYear->year_name }}</td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->academicYear->year_name }}</td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->student->batch()->batch_name }}</td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->student->section()->section_name }}</td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ sizeof($groupedCommunicationRecord) }}</td>
                    <td>
                        @if ($groupedCommunicationRecord[0]->mode == 1)
                            Audio
                        @elseif ($groupedCommunicationRecord[0]->mode == 2)
                            Video
                        @elseif ($groupedCommunicationRecord[0]->mode == 3)
                            Letter
                        @endif
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($groupedCommunicationRecord[0]->date)->format('d/m/Y, D') }}, 
                        {{ Carbon\Carbon::parse($groupedCommunicationRecord[0]->from_time)->format('h:i A') }} - 
                        {{ Carbon\Carbon::parse($groupedCommunicationRecord[0]->to_time)->format('h:i A') }}
                    </td>
                    <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">
                        {{ $totalTime }} minutes
                    </td>
                    <td>{{ $groupedCommunicationRecord[0]->communication_topics }}</td>
                </tr>
                @foreach ($groupedCommunicationRecord as $communicationRecord)
                    @if($loop->index != 0)
                        <tr>
                            <td>
                                @if ($communicationRecord->mode == 1)
                                    Audio
                                @elseif ($communicationRecord->mode == 2)
                                    Video
                                @elseif ($communicationRecord->mode == 3)
                                    Letter
                                @endif    
                            </td>
                            <td>
                                {{ Carbon\Carbon::parse($communicationRecord->date)->format('d/m/Y, D') }}, 
                                {{ Carbon\Carbon::parse($communicationRecord->from_time)->format('h:i A') }} - 
                                {{ Carbon\Carbon::parse($communicationRecord->to_time)->format('h:i A') }}
                            </td>
                            <td>{{ $communicationRecord->communication_topics }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
            <tr></tr>
        </tbody>
    </table>
</body>
</html>