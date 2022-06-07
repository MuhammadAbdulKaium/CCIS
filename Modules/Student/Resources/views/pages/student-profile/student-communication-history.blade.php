@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         
         <div id="user-profile">
            <ul id="w2" class="nav-tabs margin-bottom nav">
                @include('student::pages.student-profile.includes.history-tabs')
            </ul>
         </div>

         <table class="table table-striped table-bordered" id="dataTable">
             <thead>
                 <tr>
                     <th>SL</th>
                     <th>Academic Year</th>
                     <th>Date</th>
                     <th>From</th>
                     <th>To</th>
                     <th>Mode</th>
                     <th>Call Duration</th>
                     <th>Comments</th>
                 </tr>
             </thead>
             <tbody>
                @forelse ($communicationRecords as $communicationRecord)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $communicationRecord->academicYear->year_name }}</td>
                        <td>{{ Carbon\Carbon::parse($communicationRecord->date)->format('d/m/Y, D') }}</td>
                        <td>{{ Carbon\Carbon::parse($communicationRecord->from_time)->format('h:i A') }}</td>
                        <td>{{ Carbon\Carbon::parse($communicationRecord->to_time)->format('h:i A') }}</td>
                        <td>
                            @if ($communicationRecord->mode == 1)
                                Audio
                            @elseif ($communicationRecord->mode == 2)
                                Video
                            @elseif ($communicationRecord->mode == 3)
                                Letter
                            @endif
                        </td>
                        <td>{{ (strtotime($communicationRecord->to_time) - strtotime($communicationRecord->from_time))/60 }} minutes</td>
                        <td>{{ $communicationRecord->communication_topics }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="text-danger" style="text-align: center">Never communicated with anyone!</div>
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


