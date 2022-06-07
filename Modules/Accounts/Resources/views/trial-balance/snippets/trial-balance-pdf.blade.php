<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trial Balance {{ Illuminate\Support\Str::title($reportType) }}</title>
    <style>
        .p-0 {
            padding: 0px !important;
        }

        .m-0 {
            margin: 0px !important;

        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        img {
            width: 100px;
            height: 100px;
        }

        .header {
            /* border-bottom: 1px solid #f1f1f1; */
            padding: 10px 0;
        }

        .logo {
            width: 8%;
            float: left;
            margin-bottom: 10px;
        }

        .headline {
            width: 90%;
            float: left;
            padding: 1px 1px;
            text-align: right;
        }

        .headline h1 {
           font-size: 26px;
           font-weight: 800;
          margin: 0;
          padding: 0;
        }
        .headline p {
           font-size: 14px;
           font-weight: 400;
           margin: 0;
           padding: 0;
           margin-top: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #423e3e;
            text-align: center !important;
            page-break-inside: auto;
        }

        th, td {
            text-align: left;
            padding: 1px;
            border: 1px solid #a49494;
        }

        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            font-size: 13px;
            background-color: #002d00;
            color: white;
            height: 25px;
        }

        /* p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        } */

        /* header-bottom style */
        .header-bottom-left{
            width: 60%;
            float: left;
            padding: 1px 1px;
        }
        .header-bottom-left h2{
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }
        .header-bottom-left-details p,
        .header-bottom-right-details p{
            margin:5px 0;
            font-size: 14px;
            font-weight: 500;
        }
        .header-bottom-right{
            width: 38%;
            float: left;
            padding: 1px 1px;
        }
        .header-bottom-right-details{
            margin-top: 30px;
        }
        .tabel-footer1{
            width: 25%;
            float: left;
            text-align: center;
        }
        .tabel-footer2{
            width: 25%;
            float: left;
            text-align: center;

        }
        .tabel-footer3{
            width: 25%;
            float: left;
            text-align: center;

        }
        .tabel-footer4{
            width: 25%;
            float: left;
            text-align: center;

        }
        body{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

    </style>
</head>
<body style="font-size: x-small">

<footer>
    <div style="padding:.3rem">
        <span>Printed from <b>Alokito ERP</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>

    </div>
    <script type="text/php">
    if (isset($pdf)) {
        $x = 483;
        $y = 827;
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = null;
        $size = 10;
        $color = array(255,255,255);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }

    </script>


</footer>


<main>
    <div class="header clearfix" >
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" height="60px!important" alt="">
        </div>
        <div class="headline">
            <h1>{{ $institute->institute_name }}</h1>
            <p>{{ $institute->address1 }}</p>
        </div>
      
    </div>
    <div class="header-bottom clearfix">
        <div class="header-bottom-left">
            <h2>Trial Balance {{ Illuminate\Support\Str::title($reportType) }}, {{ $fromDate }} to {{ $toDate }}</h2>
        </div>
    </div>
  


    
    <table style="margin-top: 10px">
        <thead>
        <tr>
            <th>Group/Ledger</th>
            <th>Auto Code</th>
            <th>Opening</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Closing</th>
        </tr>
        </thead>
        <tbody>
            @php
                function chartOfAccounts($accountId, $accounts, $margin, $trialBalanceDatas, $fromDate, $toDate, $reportType){
                    $account = $accounts[$accountId];
                    if ($reportType == 'summary' && $account->account_type == 'ledger') {
                        return;
                    }
                    $color = '';
                    $fontWeight = 'normal';
                    $fontSize = 12;
                    if ($account->account_type != 'ledger' || $reportType == 'summary'){
                        $buttons = '';
                    } else{
                        $buttons = '<a class="btn btn-info btn-xs" target="_blank"
                            href="/accounts/report/ledger-details/'.$account->id.'/details/'.$fromDate.'/'.$toDate.'">Details</a>
                            <a class="btn btn-primary btn-xs" target="_blank"
                            href="/accounts/report/ledger-details/'.$account->id.'/summary/'.$fromDate.'/'.$toDate.'">Summary</a>';
                    }
                    if ($account->account_type == 'group') {
                        $color = '#7C0000';
                    } elseif ($account->account_type == 'ledger') {
                        $color = '#00a65a';
                        $fontWeight = 'bold';
                    } elseif($account->account_type == '') {
                        $fontSize = 13;
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
                        <td><span style="">'.abs($closingBalance).' '.$closingBalanceType.'</span></td></tr>';
    
                    $childs = $accounts->where('parent_id', $accountId);
                    foreach ($childs as $child){
                        $margin += 30;
                        chartOfAccounts($child->id, $accounts, $margin, $trialBalanceDatas, $fromDate, $toDate, $reportType);
                        $margin -= 30;
                    }
                }
    
                $formatedFromDate = Carbon\Carbon::parse($fromDate)->format('d-m-Y');
                $formatedToDate = Carbon\Carbon::parse($toDate)->format('d-m-Y');
            @endphp
            <tr style="font-weight: bold; font-size: 13px">
                <td>Grand Total</td>
                <td></td>
                <td>{{ $opening }}</td>
                <td>{{ $debit }}</td>
                <td>{{ $credit }}</td>
                <td>{{ $credit - $debit }}</td>
            </tr>
            @foreach($nature as $na)
                {{chartOfAccounts($na->id, $accounts, 0, $trialBalanceDatas, $formatedFromDate, $formatedToDate, $reportType)}}
            @endforeach
            <tr style="font-weight: bold; font-size: 13px">
                <td>Grand Total</td>
                <td></td>
                <td>{{ $opening }}</td>
                <td>{{ $debit }}</td>
                <td>{{ $credit }}</td>
                <td>{{ $credit - $debit }}</td>
            </tr>
        </tbody>
    </table>

</main>
</body>

</html>