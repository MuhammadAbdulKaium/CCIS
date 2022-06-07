
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
               <th>SL</th>
               <th>Institute</th>
               <th>Activity</th>
               <th>House</th>
               <th>Time</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($houseAppointHistories as $houseAppointHistory)
               <tr>
                  <td>{{ $loop->index+1 }}</td>
                  <td>{{ $houseAppointHistory->institute->institute_alias }}</td>
                  <td>
                     @if ($houseAppointHistory->action == 'assign')
                        <span class="text-success">Assigned</span> to
                     @elseif ($houseAppointHistory->action == 'remove')
                        <span class="text-danger">Removed</span> from
                     @endif
                     @if ($houseAppointHistory->appoint)
                        <span style="color: {{ $houseAppointHistory->appoint->color }}">{{ $houseAppointHistory->appoint->name }}</span>
                     @endif
                  </td>
                  <td>
                     @if ($houseAppointHistory->house)
                        <span>{{ $houseAppointHistory->house->name }}</span>
                     @endif
                  </td>
                  <td>{{ Carbon\Carbon::parse($houseAppointHistory->created_at)->diffForHumans() }}</td>
               </tr>
            @empty
               <tr>
                  <td colspan="10">
                     <div class="text-danger" style="text-align: center">Never assigned to any house appointment!</div>
                  </td>
               </tr>
            @endforelse
         </tbody>
      </table>
   </div>
@endsection
