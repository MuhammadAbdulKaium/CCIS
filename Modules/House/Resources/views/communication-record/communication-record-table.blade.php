<table class="table table-bordered">
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
            {{-- <th>Action</th> --}}
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
                <td rowspan="{{ sizeof($groupedCommunicationRecord) }}">{{ $groupedCommunicationRecord[0]->student->singleUser->username }}</td>
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
                {{-- <td>
                    <a class="btn btn-xs btn-primary"
                        href="" data-target="#globalModal" data-toggle="modal"
                        data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                    <a href=""
                        class="btn btn-danger btn-xs"
                        onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                        data-content="delete"><i class="fa fa-trash-o"></i></a>
                </td> --}}
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
                        <td>
                            <a class="btn btn-xs btn-primary"
                                href="" data-target="#globalModal" data-toggle="modal"
                                data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                            <a href=""
                                class="btn btn-danger btn-xs"
                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
        <tr></tr>
    </tbody>
</table>