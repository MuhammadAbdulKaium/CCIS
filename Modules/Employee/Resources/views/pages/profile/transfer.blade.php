
@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if(Session::has('dependencies'))
        <div class="alert alert-danger">
            <h4>Dependency Found on following Menus! Can not transfer. Please resolve the dependencies first!</h4>
            <ul>
                @foreach (Session::get('dependencies') as $dependency)
                    <li><a href="{{ url($dependency['link']) }}" target="_blank" style="font-size: 14px">{{ $dependency['name'] }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (in_array('employee/transfer.make', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/make/transfer/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" 
            data-modal-size="modal-md">Transfer</a>
        </p>
    @endif
    <div class="table-responsive">
        <h4>Transfer History</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Institute</th>
                    <th>Designation</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employeeTransferHistories as $employeeTransferHistory)
                    @php
                        $from = Carbon\Carbon::parse($employeeTransferHistory->from);
                        $to = Carbon\Carbon::parse($employeeTransferHistory->to);
                    @endphp
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $employeeTransferHistory->institute->institute_name }}</td>
                        <td>
                            @if ($employeeTransferHistory->designation)
                                {{ $employeeTransferHistory->designation->alias }}
                            @endif
                        </td>
                        <td>@if ($employeeTransferHistory->from) {{ $from->format('d M Y') }} @endif</td> 
                        <td>@if ($employeeTransferHistory->to) {{ $to->format('d M Y') }} @endif</td> 
                        <td>@if ($employeeTransferHistory->from && $employeeTransferHistory->to){{ $from->diff($to)->format('%y years %m months %d days') }}@endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
