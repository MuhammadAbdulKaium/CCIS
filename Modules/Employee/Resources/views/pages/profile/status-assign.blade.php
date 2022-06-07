
@extends('employee::layouts.profile-layout')

@section('profile-content')
    <div id="user-profile">
        <ul id="w2" class="nav-tabs margin-bottom nav">
            @include('employee::pages.profile.includes.history-tabs')
        </ul>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Status Name
                </th>
                <th>
                    Category
                </th>
                <th>
                    Effective From
                </th>
                <th>
                    Assigned On
                </th>
                <th>
                    Assigned By
                </th>

            </tr>
            </thead>
            <tbody>
            @foreach($employeeInfo->employeeStatus as $status)
                <tr class="@if($status->status->category==1) bg-success @elseif($status->status->category==2) bg-warning
@elseif ($status->status->category==3) bg-danger @endif">
                    <td>{{$loop->index+1}}</td>

                    <td>{{$status->status->status}}</td>
                    <td>@if($status->status->category==1) Active @elseif($status->status->category==2) Inactive  @elseif($status->status->category==3)
                            Closed
                        @endif</td>
                    <td>{{\Carbon\Carbon::parse($status->effective_from)->format('d M Y') }}</td>
                    <td>{{\Carbon\Carbon::parse($status->created_at)->format('d M Y') }}</td>


                    <td>{{$status->assignedBy->name}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
