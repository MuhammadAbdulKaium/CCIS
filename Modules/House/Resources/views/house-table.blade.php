<h5><b>House Master:</b> {{$house->houseMaster->first_name}} {{$house->houseMaster->last_name}} ({{ $house->houseMaster->singleUser->username }})
    @if ($house->housePrefect)
    | <b>House Prefect:</b> {{$house->housePrefect->first_name}} {{$house->housePrefect->last_name}} ({{ $house->housePrefect->singleUser->username }})
    @endif
</h5>
<button class="pull-right btn btn-success print_btn" value="{{ url('house/print/'.$house->id) }}" style="margin-bottom: 15px;"">Print</button>
<ul>
    @foreach ($appointUsers as $appointUser)
        <li><b>{{ $appointUser->appoint->name }}:</b> <span style="color: {{ $appointUser->appoint->color }}"><i class="{{ $appointUser->appoint->symbol }}"></i> {{ $appointUser->user->name }} ({{ $appointUser->user->username }})</span></li>
    @endforeach
</ul>
<table class="table table-bordered evaluation-table" style="text-align: center">
    <tbody>       
        @for ($i = 1; $i <= $house->no_of_floors; $i++)
            <tr>
                <th>Floor {{$i}}</th>
                @foreach ($house->rooms as $room)
                    @if ($room->floor_no == $i)
                        <td>
                            <b>{{$room->name}}</b>

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
                                <div>Bed {{$j}}: 
                                    @if ($bed)
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
                                    @else
                                        <span class="text-danger">Unassigned</span>
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

  <script>
    $(document).ready(function () {
        // Print table
        $('.print_btn').click(function(){
            var uri = $(this).val();
            window.open(uri, '_blank');
        })
    });
</script>