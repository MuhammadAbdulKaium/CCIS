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
                     <th>From</th>
                     <th>To</th>
                     <th>House/Position</th>
                     <th>Table</th>
                     <th>Seat No.</th>
                 </tr>
             </thead>
             <tbody>
                @forelse ($tableHistories as $tableHistory)
                    @if ($tableHistory->activity == 1)
                        <tr>
                            <td>{{ Carbon\Carbon::parse($tableHistory->created_at)->format('d/m/Y - g:i a') }}</td>
                            <td>
                                @isset($tableHistories[$loop->index-1])
                                    {{ Carbon\Carbon::parse($tableHistories[$loop->index-1]->created_at)->format('d/m/Y - g:i a') }}
                                @else
                                    Present
                                @endisset
                            </td>
                            <td>
                                @if ($tableHistory->table)
                                    @if ($tableHistory->table->house)
                                        {{ $tableHistory->table->house->name }}
                                    @elseif ($tableHistory->table->table_position)
                                        {{ Illuminate\Support\Str::title($tableHistory->table->table_position) }}
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($tableHistory->table)
                                    {{ $tableHistory->table->table_name }}
                                @endif
                            </td>
                            <td>{{ $tableHistory->seat_no }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="text-danger" style="text-align: center">Never assigned to any table!</div>
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


