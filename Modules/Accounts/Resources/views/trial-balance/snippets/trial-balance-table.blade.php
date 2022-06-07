<table class="table table-striped table-bordered" id="accountsTable">
    <thead>
    <tr>
        <th>Group/Ledger</th>
        <th>Auto Code</th>
        <th>Opening</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Closing</th>
        @if(in_array('accounts/ledger-report',$pageAccessData))
        <th>Action</th>
            @endif
    </tr>
    </thead>
    <tbody>
        @php
            function chartOfAccounts($accountId, $accounts, $margin, $trialBalanceDatas, $fromDate, $toDate,
            $reportType,$pageAccessData){
                $account = $accounts[$accountId];
                if ($reportType == 'summary' && $account->account_type == 'ledger') {
                    return;
                }
                $color = '';
                $fontWeight = 'normal';
                $fontSize = '';
                 if(in_array('accounts/ledger-report',$pageAccessData)){
                   if ($account->account_type != 'ledger' || $reportType == 'summary'){
                    $buttons = '';
                } else {
                    $buttons = '<a class="btn btn-info btn-xs" target="_blank"
                        href="/accounts/report/ledger-details/'.$account->id.'/details/'.$fromDate.'/'.$toDate.'">Details</a>
                        <a class="btn btn-primary btn-xs" target="_blank"
                        href="/accounts/report/ledger-details/'.$account->id.'/summary/'.$fromDate.'/'.$toDate.'">Summary</a>';
                }
                 }

                if ($account->account_type == 'group') {
                    $color = '#7C0000';
                } elseif ($account->account_type == 'ledger') {
                    $color = '#00a65a';
                    $fontWeight = 'bold';
                } elseif($account->account_type == '') {
                    $fontSize = 16;
                    $fontWeight = 'bold';
                }
                $openingBalance = 0;
                $openingBalanceType = '';
                $debit = 0;
                $credit = 0;
                $closingBalance = 0;
                $closingBalanceType = '';
                if (isset($trialBalanceDatas[$account->id])) {
                    $openingBalance = $trialBalanceDatas[$account->id]['opening_balance'];
                    $openingBalanceType = $trialBalanceDatas[$account->id]['opening_balance_type'];
                    $debit = $trialBalanceDatas[$account->id]['debit'];
                    $credit = $trialBalanceDatas[$account->id]['credit'];
                    $closingBalance = $trialBalanceDatas[$account->id]['closing_balance'];
                    $closingBalanceType = $trialBalanceDatas[$account->id]['closing_balance_type'];
                }
                echo '<tr style="font-weight: '.$fontWeight.'; font-size: '.$fontSize.'px">
                    <td><span style="margin-left: '.$margin.'px; color: '.$color.'">'.$account->account_name.'</span></td>
                    <td><span style="color: '.$color.'">'.$account->account_code.'</span></td>
                    <td><span style="">'.abs($openingBalance).' '.$openingBalanceType.'</span></td>
                    <td><span style="">'.$debit.'</span></td>
                    <td><span style="">'.$credit.'</span></td>
                    <td><span style="">'.abs($closingBalance).' '.$closingBalanceType.'</span></td>
                   ';
                                 if(in_array('accounts/ledger-report',$pageAccessData)){
                                     echo  '<td>'.$buttons.'</td></tr>';
                                 }else{
                                     echo  '</tr>';
                                 }




                $childs = $accounts->where('parent_id', $accountId);
                foreach ($childs as $child){
                    $margin += 30;
                    chartOfAccounts($child->id, $accounts, $margin, $trialBalanceDatas, $fromDate, $toDate,
                    $reportType,$pageAccessData);
                    $margin -= 30;
                }
            }

            $formatedFromDate = Carbon\Carbon::parse($fromDate)->format('d-m-Y');
            $formatedToDate = Carbon\Carbon::parse($toDate)->format('d-m-Y');
        @endphp
        <tr style="font-weight: bold; font-size: 16px">
            <td>Grand Total</td>
            <td></td>
            <td>{{ $opening }}</td>
            <td>{{ $debit }}</td>
            <td>{{ $credit }}</td>
            <td>{{ $credit - $debit }}</td>
            <td></td>
        </tr>
        @foreach($nature as $na)
            {{chartOfAccounts($na->id, $accounts, 0, $trialBalanceDatas, $formatedFromDate, $formatedToDate,
            $reportType,$pageAccessData)}}
        @endforeach
        <tr style="font-weight: bold; font-size: 16px">
            <td>Grand Total</td>
            <td></td>
            <td>{{ $opening }}</td>
            <td>{{ $debit }}</td>
            <td>{{ $credit }}</td>
            <td>{{ $credit - $debit }}</td>
            <td></td>
        </tr>
    </tbody>
</table>