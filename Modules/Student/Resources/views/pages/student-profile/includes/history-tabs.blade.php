    <ul id="w2" class="nav-tabs margin-bottom nav">
    <li class="@if ($tab == 'house') active @endif"><a href="{{url('/student/profile/house/history/'.$personalInfo->id)}}">House History</a></li>
    <li class="@if ($tab == 'house-appoint') active @endif"><a href="{{url('/student/profile/house-appoint/history/'.$personalInfo->id)}}">House Appoint History</a></li>
    <li class="@if ($tab == 'pocket-money') active @endif"><a href="{{url('/student/profile/pocket-money/history/'.$personalInfo->id)}}">Pocket Money History</a></li>
    <li class="@if ($tab == 'mess-table') active @endif"><a href="{{url('/student/profile/mess-table/history/'.$personalInfo->id)}}">Mess Table History</a></li>
    <li class="@if ($tab == 'communication') active @endif"><a href="{{url('/student/profile/communication/history/'.$personalInfo->id)}}">Communication History</a></li>
    <li class="@if ($tab == 'medical') active @endif"><a href="{{url('/student/profile/medical/history/'.$personalInfo->id)}}">Medical History</a></li>
</ul>