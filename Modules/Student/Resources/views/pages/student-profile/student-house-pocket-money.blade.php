@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel">
      <div class="panel-body">
         
         <div id="user-profile">
            @include('student::pages.student-profile.includes.history-tabs')
         </div>

         <table class="table table-striped" id="dataTable">
             <thead>
                <tr>
                    <th>SL</th>
                    <th>Account No</th>
                    <th>Bank - Branch</th>
                    <th>Account Balance</th>
                    <th>New Account Balance</th>
                    <th>Money In</th>
                    <th>New Money In</th>
                    <th>Total Allotment</th>
                    <th>New Allotment</th>
                    <th>Previous Expenses</th>
                    <th>New Expense</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Time</th>
                </tr>
             </thead>
             <tbody>
                @forelse ($pocketMoneyHistories as $pocketMoneyHistory)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $pocketMoneyHistory->account_no }}</td>
                        <td>
                            @if ($pocketMoneyHistory->bankBranch)    
                                @if ($pocketMoneyHistory->bankBranch->bankName)
                                    {{ $pocketMoneyHistory->bankBranch->bankName->bank_name }} - 
                                    {{ $pocketMoneyHistory->bankBranch->branch_name }}
                                @endif   
                            @endif
                        </td>
                        <td>{{ $pocketMoneyHistory->account_balance }}</td>
                        <td>{{ $pocketMoneyHistory->new_account_balance }}</td>
                        <td>{{ $pocketMoneyHistory->money_in }}</td>
                        <td>{{ $pocketMoneyHistory->new_money_in }}</td>
                        <td>{{ $pocketMoneyHistory->total_allotment }}</td>
                        <td>{{ $pocketMoneyHistory->new_allotment }}</td>
                        <td>{{ $pocketMoneyHistory->total_expense }}</td>
                        <td>{{ $pocketMoneyHistory->new_expense }}</td>
                        <td>
                            @if ($pocketMoneyHistory->status == 1)
                                Active
                            @elseif($pocketMoneyHistory->status == 0)
                                Inactive
                            @endif
                        </td>
                        <td>
                            @if ($pocketMoneyHistory->action_param == 'account_no')
                                <b>Account No</b> changed by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @elseif ($pocketMoneyHistory->action_param == 'bank_branch')
                                <b>Bank Branch</b> changed by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @elseif ($pocketMoneyHistory->action_param == 'status')
                                <b>Status</b> changed by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @elseif ($pocketMoneyHistory->action_param == 'account_balance')
                                <b>Account Balance</b> added by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @elseif ($pocketMoneyHistory->action_param == 'money_in')
                                <b>Money In</b> added by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @elseif ($pocketMoneyHistory->action_param == 'allotment')
                                <b>Money Alloted</b> by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @elseif ($pocketMoneyHistory->action_param == 'expense')
                                <b>Money Deducted</b> by {{ $pocketMoneyHistory->user->name }} ({{ $pocketMoneyHistory->user->username }})
                            @endif
                        </td>
                        <td>{{ Carbon\Carbon::parse($pocketMoneyHistory->created_at)->format('d M, Y g:i a') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="50">
                            <div class="text-danger" style="text-align: center">No Pocket Money History Found!</div>
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
            $('#dataTable').DataTable();
        });
   </script>
@endsection


