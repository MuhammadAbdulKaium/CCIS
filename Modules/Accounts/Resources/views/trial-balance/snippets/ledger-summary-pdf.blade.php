<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trial Balance Details</title>
    <style>
        .b-n {
            border: none;
        }
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
            padding: 3px;
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
            <h2>Ledger Statement, {{$fromDate}} to {{$toDate}}</h2>
            <div class="header-bottom-left-details">
                <p>{{$ledgerInfo->account_name}} - {{$ledgerInfo->account_code}}</p>
<!--                <p>Address: </p>
                <p>City: </p>-->
            </div>
        </div>
        <div class="header-bottom-right">
            <div class="header-bottom-right-details">
                <p>Branch:{{$campus->name}}</p>
            </div>
        </div>
    </div>



    @if($tableType==0)
        <table class="table table-bordered" style="table-layout: fixed">
            <thead>
            <tr>
                <th>Date</th>
                <th colspan="4" >Particulars </th>
                <th colspan="2">Ref.</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Dr/Cr</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td class="text-bold" colspan="4">Opening Balance</td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td  class="text-bold">{{abs($opening_balance['balance'])}}</td>
                <td class="text-bold"> @if($opening_balance['balance']>0)
                        Cr
                    @elseif($opening_balance['balance']<0)
                        Dr
                    @endif</td>
            </tr>
            @php
                $balance=$opening_balance['balance'];
                $totalDebit=0;
                $totalCredit=0
            @endphp
            @foreach($ledgers as $ledger)

                @php
                    $totalDebit+=$ledger->debit_amount;
                    $totalCredit+=$ledger->credit_amount;

                        if($ledger->transaction){
                        $allTxn=$ledger->transaction->transactionDetails;

                        }
                        $balance+=$ledger->credit_amount-$ledger->debit_amount

                @endphp
                <tr>
                    <td>{{\Carbon\Carbon::make($ledger->trans_date)->format('d-m-Y')}}</td>

                    <td colspan="4">

                        @if($allTxn)
                            @foreach($allTxn as $txn)
                                @if($txn->sub_ledger!=$ledger->sub_ledger)

                                    {{$allLedgers [$txn->sub_ledger]->account_name}} &nbsp; {{$allLedgers
                                                  [$txn->sub_ledger]->account_code}}
                                    <br>


                                @endif

                            @endforeach
                        @else
                            {{$allTxn}}
                        @endif
                    </td>
                    <td colspan="2">{{ ($ledger->transaction) ? $ledger->transaction->voucher_no : ''}} <br>
                        @if($ledger->cheque_no)
                            Cheque : {{$ledger->cheque_no}}
                        @endif
                    </td>
                    <td>{{$ledger->debit_amount}}</td>
                    <td>{{$ledger->credit_amount}}</td>
                    <td>{{abs($balance)}}</td>
                    <td>
                        @if($balance>0)
                            Cr
                        @elseif($balance<0)
                            Dr
                        @endif
                    </td>
                </tr>


            @endforeach


            </tbody>

        </table>
    @else
        <table class="table table-bordered" style="table-layout: fixed">
            <thead>
            <tr>
                <th>Date</th>
                <th colspan="4" >Particulars </th>
                <th colspan="2">Ref.</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Dr/Cr</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td class="text-bold" colspan="4">Opening Balance</td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td  class="text-bold">{{abs($opening_balance['balance'])}}</td>
                <td class="text-bold"> @if($opening_balance['balance']>0)
                        Cr
                    @elseif($opening_balance['balance']<0)
                        Dr
                    @endif</td>
            </tr>
            @php
                $balance=$opening_balance['balance'];
                $totalDebit=0;
                $totalCredit=0
            @endphp
            @foreach($ledgers as $ledger)

                @php
                    $totalDebit+=$ledger->debit_amount;
                    $totalCredit+=$ledger->credit_amount;

                        if($ledger->transaction){
                        $allTxn=$ledger->transaction->transactionDetails;

                        }
                        $balance+=$ledger->credit_amount-$ledger->debit_amount

                @endphp
                <tr>
                    <td>{{\Carbon\Carbon::make($ledger->trans_date)->format('d-m-Y')}}</td>
                    <td colspan="4"></td>

                    <td colspan="2">{{ ($ledger->transaction) ? $ledger->transaction->voucher_no : ''}} <br>
                        @if($ledger->cheque_no)
                            Cheque : {{$ledger->cheque_no}}
                        @endif
                    </td>
                    <td>{{$ledger->debit_amount}}</td>
                    <td>{{$ledger->credit_amount}}</td>
                    <td>{{abs($balance)}}</td>
                    <td>
                        @if($balance>0)
                            Cr
                        @elseif($balance<0)
                            Dr
                        @endif
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4">

                        @if($allTxn)
                            <div class="b-n">
                                <table class="b-n" style="table-layout: fixed" >
                                    <tbody class="b-n" >
                                    @foreach($allTxn as $txn)
                                        @if($txn->sub_ledger!=$ledger->sub_ledger)
                                            <tr  class="">
                                                <td class="b-n" colspan="3"> {{$allLedgers [$txn->sub_ledger]->account_name}}</td>

                                                <td class="b-n" style="text-align: right">@if($txn->debit_amount>0) {{$txn->debit_amount}}
                                                    @elseif($txn->credit_amount>0)
                                                        {{$txn->credit_amount}}
                                                    @endif</td>
                                                <td class="b-n" style="text-align: right">
                                                    @if($txn->increase_by=="debit")

                                                        Dr
                                                    @elseif($txn->increase_by=="credit")
                                                        Cr
                                                    @endif

                                                </td>


                                            </tr>
                                            <tr class="b-n">
                                                <td class="b-n" colspan="5">  {{$allLedgers
                                            [$txn->sub_ledger]->account_code}}</td>
                                            </tr>
                                            @if($txn->cheque_no!=null)

                                                <tr class="b-n" >

                                                    <td class="b-n" style="font-weight: bold" colspan="5">
                                                        Cheque #: {{$txn->cheque_no}}</td>

                                                </tr>

                                            @endif
                                        @endif

                                    @endforeach
                                    </tbody>

                                </table>
                            </div>


                        @endif
                    </td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            @endforeach


            </tbody>

        </table>

    @endif
    <div class="tabel-footer">
        <div class="tabel-footer1">
            <h3>Total Debit:</h3>
        </div>
        <div class="tabel-footer2">
            <h3>{{$totalDebit}}</h3>
        </div>
        <div class="tabel-footer3">
            <h3>Total Credit: </h3>
        </div>
        <div class="tabel-footer4">
            <h3>{{$totalCredit}}</h3>
        </div>
    </div>

</main>
</body>

</html>