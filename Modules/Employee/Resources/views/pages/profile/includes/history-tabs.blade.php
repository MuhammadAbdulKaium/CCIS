    <ul id="w2" class="nav-tabs margin-bottom nav">
        <li class="@if ($tab == 'status-assign') active @endif">
            <a href="{{url('/employee/profile/status/history/'.$employeeInfo->id)}}">Status Assign History</a></li>
        <li class="@if($tab == 'house_appoint_history') active @endif">
            <a href="{{ url('/employee/profile/house-appoint/history/'.$employeeInfo->id) }}">House Appoint History</a></li>
    </ul>