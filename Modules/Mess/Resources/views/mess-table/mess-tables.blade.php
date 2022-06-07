{{-- Top Tables --}}
@foreach ($messTables->where('table_position', 'top') as $messTable)
<table class="table table-bordered">
    <tbody>
        <tr>
            <th>Table: {{ $messTable->table_name }}</th>
            @if(in_array('mess/table.history', $pageAccessData))
            <th><a href="{{url('/mess/table/history/'.$messTable->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">History</a></th>
            @endif
        </tr>
        <tr>
            <td>
                <div style="text-align: right">
                    @if(in_array('mess/table.edit', $pageAccessData))
                    <a class="text-success" href="{{url('mess/edit/table/'.$messTable->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-pencil"></i></a>
                    @endif
                        @if(in_array('mess/table.delete', $pageAccessData))
                        <a href="{{ url('/mess/delete/table/'.$messTable->id) }}"
                        class="text-danger" style="margin-left: 5px"
                        onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                        data-content="delete"><i class="fa fa-trash"></i></a>
                            @endif
                </div>
                <div>Table Name: {{ $messTable->table_name }}</div>
                <div>Total Seats: {{ $messTable->total_seats }}</div>
                @if ($messTable->employee)
                <div>Concern HR: {{ $messTable->employee->first_name }} {{ $messTable->employee->last_name }} ({{ $messTable->employee->singleUser->username }})</div>
                @endif
                @php
                    $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                @endphp
                <div>Empty Chairs: {{ $messTable->total_seats - $filledChairs }}</div>
            </td>
            <td>
                <div class="mess-table">
                    {{-- First High Chairs --}}
                    <div class="mess-table-column">
                        <div class="mess-table-no-seat"></div>
                        @for ($i = $messTable->total_high_seats/2; $i > 0; $i--)
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
                                <div class="table-seat-username">
                                    <div class="seat-no">{{ $i }}</div>
                                    @if ($user)
                                        <div class="username">{{ $user->username }}</div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                        <div class="mess-table-no-seat"></div>
                    </div>

                    {{-- Normal Chairs --}}
                    @for ($i = ($messTable->total_high_seats/2)+1, $j = $messTable->total_seats; $i <= $messTable->total_seats/2; $i++, $j--)
                        <div class="mess-table-column">
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
                                <div class="table-seat-username">
                                    <div class="seat-no">{{ $i }}</div>
                                    @if ($user)
                                        <div class="username">{{ $user->username }}</div>
                                    @endif
                                </div>    
                            </div>
                            @for ($k = 0; $k< ($messTable->total_high_seats/2); $k++)
                                <div class="mess-table-no-seat"></div>
                            @endfor
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $j, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $j)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $j }}">
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
                        @for ($k = 0; $k < ($messTable->total_high_seats/2); $k++)
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
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


<table class="table table-bordered">
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
                        @if ($loop->index != 0) <hr> @endif
                        <div>
                            <span style="float: right">
                                @if(in_array('mess/table.history', $pageAccessData))
                                    <a href="{{url('/mess/table/history/'.$messTable->id)}}" 
                                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
                                        <i class="fa fa-history"></i></a>
                                @endif
                                @if(in_array('mess/table.edit', $pageAccessData))
                                    <a class="text-success" href="{{url('mess/edit/table/'.$messTable->id)}}" 
                                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                        <i class="fa fa-pencil"></i></a>
                                @endif
                                @if(in_array('mess/table.delete', $pageAccessData))
                                    <a href="{{ url('/mess/delete/table/'.$messTable->id) }}"
                                    class="text-danger" style="margin-left: 5px"
                                    onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                    data-content="delete"><i class="fa fa-trash"></i></a>
                                @endif
                            </span>

                            <div>
                                Table Name: {{ $messTable->table_name }}<br>
                                Total Seats: {{ $messTable->total_seats }}<br>
                                @if ($messTable->employee)
                                    Concern HR: {{ $messTable->employee->first_name }} {{ $messTable->employee->last_name }} ({{ $messTable->employee->singleUser->username }})<br>
                                @endif
                                @php
                                    $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                                @endphp
                                Empty Chairs: {{ $messTable->total_seats - $filledChairs }}
                            </div>

                            <div class="mess-table-holder">
                                <div class="mess-table">
                                    {{-- First High Chairs --}}
                                    <div class="mess-table-column">
                                        <div class="mess-table-no-seat"></div>
                                        @for ($i = $messTable->total_high_seats/2; $i > 0; $i--)
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
                                                <div class="table-seat-username">
                                                    <div class="seat-no">{{ $i }}</div>
                                                    @if ($user)
                                                        <div class="username">{{ $user->username }}</div>
                                                    @endif
                                                </div>    
                                            </div>
                                        @endfor
                                        <div class="mess-table-no-seat"></div>
                                    </div>
                
                                    {{-- Normal Chairs --}}
                                    @for ($i = ($messTable->total_high_seats/2)+1, $j = $messTable->total_seats; $i <= $messTable->total_seats/2; $i++, $j--)
                                        <div class="mess-table-column">
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
                                                <div class="table-seat-username">
                                                    <div class="seat-no">{{ $i }}</div>
                                                    @if ($user)
                                                        <div class="username">{{ $user->username }}</div>
                                                    @endif
                                                </div>    
                                            </div>
                                            @for ($k = 0; $k< ($messTable->total_high_seats/2); $k++)
                                                <div class="mess-table-no-seat"></div>
                                            @endfor
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $j, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $j)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $j }}">
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
                                        @for ($k = 0; $k < ($messTable->total_high_seats/2); $k++)
                                            @php
                                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                                $previousSeat = $personData['previousSeat'];
                                                $personTxt = $personData['personTxt'];
                                                $user = $personData['user'];
                                            @endphp
                                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
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
                            </div>
                        </div>
                    @endforeach
                </td>
            @endforeach
        </tr>
    </tbody>
</table>


{{-- Bottom Tables --}}
@foreach ($messTables->where('table_position', 'bottom') as $messTable)
<table class="table table-bordered">
    <tbody>
        <tr>
            <th>Table: {{ $messTable->table_name }}</th>
            @if(in_array('mess/table.history', $pageAccessData))
            <th><a href="{{url('/mess/table/history/'.$messTable->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">History</a></th>
            @endif
        </tr>
        <tr>
            <td>
                <div style="text-align: right">
                    @if(in_array('mess/table.edit', $pageAccessData))
                    <a class="text-success" href="{{url('mess/edit/table/'.$messTable->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-pencil"></i></a>
                    @endif
                        @if(in_array('mess/table.delete', $pageAccessData))
                        <a href="{{ url('/mess/delete/table/'.$messTable->id) }}"
                        class="text-danger" style="margin-left: 5px"
                        onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                        data-content="delete"><i class="fa fa-trash"></i></a>
                            @endif
                </div>
                <div>Table Name: {{ $messTable->table_name }}</div>
                <div>Total Seats: {{ $messTable->total_seats }}</div>
                @if ($messTable->employee)
                <div>Concern HR: {{ $messTable->employee->first_name }} {{ $messTable->employee->last_name }} ({{ $messTable->employee->singleUser->username }})</div>
                @endif
                @php
                    $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                @endphp
                <div>Empty Chairs: {{ $messTable->total_seats - $filledChairs }}</div>
            </td>
            <td>
                <div class="mess-table">
                    {{-- First High Chairs --}}
                    <div class="mess-table-column">
                        <div class="mess-table-no-seat"></div>
                        @for ($i = $messTable->total_high_seats/2; $i > 0; $i--)
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
                                <div class="table-seat-username">
                                    <div class="seat-no">{{ $i }}</div>
                                    @if ($user)
                                        <div class="username">{{ $user->username }}</div>
                                    @endif
                                </div>    
                            </div>
                        @endfor
                        <div class="mess-table-no-seat"></div>
                    </div>

                    {{-- Normal Chairs --}}
                    @for ($i = ($messTable->total_high_seats/2)+1, $j = $messTable->total_seats; $i <= $messTable->total_seats/2; $i++, $j--)
                        <div class="mess-table-column">
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
                                <div class="table-seat-username">
                                    <div class="seat-no">{{ $i }}</div>
                                    @if ($user)
                                        <div class="username">{{ $user->username }}</div>
                                    @endif
                                </div>    
                            </div>
                            @for ($k = 0; $k< ($messTable->total_high_seats/2); $k++)
                                <div class="mess-table-no-seat"></div>
                            @endfor
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $j, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $j)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $j }}">
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
                        @for ($k = 0; $k < ($messTable->total_high_seats/2); $k++)
                            @php
                                $personData = getMessTableInfo($messTableSeats, $messTable, $i, $students, $employees);
                                $previousSeat = $personData['previousSeat'];
                                $personTxt = $personData['personTxt'];
                                $user = $personData['user'];
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">
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