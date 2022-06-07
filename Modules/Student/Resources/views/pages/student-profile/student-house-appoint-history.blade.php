@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         
         <div id="user-profile">
            @include('student::pages.student-profile.includes.history-tabs')
         </div>

         <table class="table table-striped table-bordered" id="dataTable">
             <thead>
                <tr>
                    <th>SL</th>
                    <th>Activity</th>
                    <th>Time</th>
                 </tr>
             </thead>
             <tbody>
                @forelse ($houseAppointHistories as $houseAppointHistory)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
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
   </div>
   <!--/responsive-->

@endsection

@section('scripts')
   <script type="text/javascript">
        $(document).ready(function (){
            
        });
   </script>
@endsection


