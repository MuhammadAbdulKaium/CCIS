<h5><b>House Master:</b> {{$house->houseMaster->first_name}} {{$house->houseMaster->last_name}} ({{ $house->houseMaster->singleUser->username }})
    @php
        $housePrefectId = 0;
    @endphp
    @if ($house->housePrefect)
    @php
        $housePrefectId = $house->housePrefect->std_id;
    @endphp
    | <b>House Prefect:</b> {{$house->housePrefect->first_name}} {{$house->housePrefect->last_name}} ({{ $house->housePrefect->singleUser->username }})
    @endif
</h5>
<ul>
    @foreach ($appointUsers as $appointUser)
        <li><b>{{ $appointUser->appoint->name }}:</b> <span style="color: {{ $appointUser->appoint->color }}"><i class="{{ $appointUser->appoint->symbol }}"></i> {{ $appointUser->user->name }} ({{ $appointUser->user->username }})</span></li>
    @endforeach
</ul>

<form action="" id="bulk-student-assign-form">
    @csrf

    <input type="hidden" name="houseId" value="{{ $house->id }}">

    <div class="row bg-info" style="margin-bottom: 10px; padding: 10px 0">
        <div class="col-sm-2">
            <label style="margin: 0; line-height: 58px">Students to Assign: </label>
        </div>
        <div class="col-sm-2">
            <label for="">Class*</label>
            <select name="batchId" class="form-control" id="bulk-batch-field">
                <option value="">--Choose Class--</option>
                @foreach ($batches as $ba)
                    <option value="{{ $ba->id }}" @if ($batch) @if ($ba->id == $batch->id) selected @endif @endif>{{ $ba->batch_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <label for="">Form</label>
            <select name="sectionId" class="form-control" id="bulk-section-field">
                <option value="">--All Forms--</option>
                @foreach ($sections as $sec)
                    <option value="{{ $sec->id }}" @if ($section) @if ($sec->id == $section->id) selected @endif @endif>{{ $sec->section_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4">
            <label for="">Cadets</label>
            <select name="studentIds[]" class="form-control" id="bulk-students-field" multiple>
                @foreach ($students as $student)
                    <option value="{{ $student->std_id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->singleUser->username }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <button type="button" id="bulk-student-assign-btn" class="btn btn-primary" style="margin-top: 24px">Assign</button>
            <button type="button" id="bulk-student-remove-btn" class="btn btn-danger" style="margin-top: 24px">Remove</button>
        </div>
    </div>

    <table class="table table-bordered evaluation-table" style="text-align: center">
        <tbody>       
            @for ($i = 1; $i <= $house->no_of_floors; $i++)
                <tr>
                    <th>
                        <input type="checkbox" class="select-floor" name="floorIds[{{ $i }}]"><br>
                        Floor {{$i}}
                    </th>
                    @foreach ($house->rooms as $room)
                        @if ($room->floor_no == $i)
                            <td>
                                <input type="checkbox" class="select-room" name="roomIds[{{ $i }}][{{ $room->id }}]" value="{{ $room->id }}"> <b> {{$room->name}}</b>

                                @php
                                    $beds = $roomStudents->where('floor_no', $i)->where('room_id', $room->id);
                                @endphp
                                @for ($j = 1; $j <= $room->no_of_beds; $j++)
                                    @php
                                        $bed = $beds->firstWhere('bed_no', $j);
                                        $symbol = null;
                                        $color = null;
                                        if ($bed) {
                                            if ($bed->student->singleUser->appointUser) {
                                                if ($bed->student->singleUser->appointUser->appoint) {
                                                    $symbol = $bed->student->singleUser->appointUser->appoint->symbol;
                                                    $color = $bed->student->singleUser->appointUser->appoint->color;
                                                }
                                            }
                                        }
                                    @endphp
                                    <div style="text-align: left">Bed {{$j}}: 
                                        @if ($bed)
                                            <button type="button" data-std-id="{{ $bed->student->std_id }}" data-room-id="{{ $room->id }}" data-bed-no="{{ $j }}" class="nude-button @if ($housePrefectId == $bed->student->std_id) text-success @else text-primary @endif assign-std-modal-btn">
                                                <span style="color: @if ($color) {{ $color }} @endif">
                                                    @if ($symbol)
                                                        <i class="{{ $symbol }}"></i>
                                                    @endif
                                                    {{$bed->student->first_name}}
                                                    {{$bed->student->last_name}}
                                                    ({{ $bed->student->singleUser->username }})
                                                </span>,
                                                {{ $bed->student->singleBatch->batch_name }} - 
                                                {{ $bed->student->singleSection->section_name }}
                                            </button>
                                        @else
                                            <button type="button" data-stdId="" data-room-id="{{ $room->id }}" data-bed-no="{{ $j }}" class="nude-button text-danger assign-std-modal-btn">
                                                Unassigned
                                            </button>
                                        @endif
                                    </div>
                                @endfor
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endfor
        </tbody>
      </table>
</form>