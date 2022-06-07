@if($tableType==0)
    <!-- Summary Table Goes Here -->
    <table class="table table-striped table-bordered" id="accountsTable">
        <thead>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Ref.</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
            <th> Dr/Cr</th>


        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td class="text-bold">Opening Balance</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-bold">{{abs($opening_balance['balance'])}}</td>
            <td class="text-bold"> @if($opening_balance['balance']>0)
                    Cr
                @elseif($opening_balance['balance']<0)
                    Dr
                @endif</td>
        </tr>
        @php
            $balance=$opening_balance['balance'];
            $totalDebit=0;
            $totalCredit=0;
        @endphp
        @foreach($ledgers as $ledger)


            <tr>
                <td>{{\Carbon\Carbon::make($ledger->trans_date)->format('d-m-Y')}}</td>
                @php
                    $totalDebit+=$ledger->debit_amount;
                    $totalCredit+=$ledger->credit_amount;

                        if($ledger->transaction){
                        $allTxn=$ledger->transaction->transactionDetails;

                        }
                        $balance+=$ledger->credit_amount-$ledger->debit_amount

                @endphp
                <td>

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
                <td>{{ ($ledger->transaction) ? $ledger->transaction->voucher_no : ''}} <br>
                    @if($ledger->cheque_no)
                        Cheque : {{$ledger->cheque_no}}
                    @endif</td>
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
        <tr>
            <td colspan="2"></td>

            <td class="" style="font-weight: bolder">Total</td>
            <td>{{$totalDebit}}</td>
            <td>{{$totalCredit}}</td>


        </tr>
        </tbody>
    </table>

@else
    <!-- Details Table Goes Here -->
<table class="table table-striped table-bordered" id="accountsTable">
    <thead>
    <tr>
        <th>Date</th>
        <th colspan="4">Particulars</th>
        <th colspan="2">Ref.</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
        <th> Dr/Cr</th>


    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td colspan="4" class="text-bold">Opening Balance</td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td class="text-bold">{{abs($opening_balance['balance'])}}</td>
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
                @endif</td>
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
            <td colspan="4" class="p-0" style="padding: 0">

                @if($allTxn)
                    <style>
                        .b-n{
                            padding: 0;margin: 0;border: none!important;
                        }
                    </style>
                    <div class="b-n">
                        <table class="b-n table table-borderless p-0" style="padding: 0;margin: 0;border: none!important;"  >
                            <tbody class="b-n" >
                            @foreach($allTxn as $txn)
                                @if($txn->sub_ledger!=$ledger->sub_ledger)
                                    <tr style="padding: 0;margin: 0;border: none!important;"  class="">
                                        <td class="b-n" style="padding: 0;margin: 0;border: none!important;" colspan="3"> {{$allLedgers
                                        [$txn->sub_ledger]->account_name}}</td>

                                        <td style="padding: 0;margin: 0;border: none!important;" class="b-n" style="text-align: right">@if($txn->debit_amount>0)
                                                {{$txn->debit_amount}}
                                            @elseif($txn->credit_amount>0)
                                                {{$txn->credit_amount}}
                                            @endif
                                            @if($txn->increase_by=="debit")

                                                Dr
                                            @elseif($txn->increase_by=="credit")
                                                Cr
                                            @endif

                                        </td>


                                    </tr>
                                    <tr style="padding: 0;margin: 0;border: none!important;" class="b-n">
                                        <td style="padding: 0;margin: 0;border: none!important;" class="b-n" colspan="5">  {{$allLedgers
                                            [$txn->sub_ledger]->account_code}}</td>
                                    </tr>
                                    @if($txn->cheque_no!=null)

                                        <tr style="padding: 0;margin: 0;border: none!important;" class="b-n" >

                                            <td style="padding: 0;margin: 0;border: none!important;" class="b-n" style="font-weight: bold" colspan="5">
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
   <tr>




        <td colspan="7"  class="text-center" style="font-weight: bolder">Total</td>
        <td>{{$totalDebit}}</td>
        <td>{{$totalCredit}}</td>


    </tr>
    </tbody>
</table>
@endif