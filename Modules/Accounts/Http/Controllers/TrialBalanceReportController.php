<?php

namespace Modules\Accounts\Http\Controllers;

use App\Exports\LedgerExcel;
use App\Helpers\UserAccessHelper;
use App\Jobs\PasswordChangeJob;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Accounts\Entities\ChartOfAccount;
use App\Helpers\AccountsHelper;
use Illuminate\Support\Facades\App;
use Modules\Accounts\Entities\SubsidiaryCalculationModel;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;

class TrialBalanceReportController extends Controller
{
    use AccountsHelper;
    use UserAccessHelper;
    // DEV5 START
    protected $trialBalanceDatas = [];

    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        return view('accounts::trial-balance.index',compact('pageAccessData'));
    }

    protected function calculateTrialBalance($accountId, $fromDate, $toDate, $accounts)
    {
        $account = $accounts[$accountId];
        $childs = $accounts->where('parent_id', $accountId);

        $data = [
            'opening_balance' => 0,
            'opening_balance_type' => '',
            'credit' => 0,
            'debit' => 0,
            'closing_balance' => 0,
            'closing_balance_type' => '',
        ];

        foreach ($childs as $child) {
            $balanceData = $this->calculateTrialBalance($child->id, $fromDate, $toDate, $accounts);

            $data['opening_balance'] += $balanceData['opening_balance'];
            if ($data['opening_balance'] < 0) {
                $data['opening_balance_type'] = 'Dr';
            } else if ($data['opening_balance'] > 0) {
                $data['opening_balance_type'] = 'Cr';
            } else {
                $data['opening_balance_type'] = '';
            }
            $data['credit'] += $balanceData['credit'];
            $data['debit'] += $balanceData['debit'];
            $data['closing_balance'] = $data['credit'] - $data['debit'] + $data['opening_balance'];
            if ($data['closing_balance'] < 0) {
                $data['closing_balance_type'] = 'Dr';
            } else if ($data['closing_balance'] > 0) {
                $data['closing_balance_type'] = 'Cr';
            } else {
                $data['closing_balance_type'] = '';
            }
        }

        if ($account->account_type == 'ledger') {
            $data = $this->getLedgerStatement($fromDate, $toDate, $accountId);
        }

        $this->trialBalanceDatas[$account->id] = $data;
        return $data;
    }

    public function search(Request $request)
    {

        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'accounts/reports/trial-balance']);
        $user_id = Auth::user()->id;
        $created_at = date('Y-m-d H:i:s');
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $selectType = $request->type;
        $reportType = $request->report_type;
        $fromDate = $request->start_date;
        $toDate = $request->end_date;

        $checkCharOfAccounts = ChartOfAccount::all();
        if (empty($checkCharOfAccounts)) {
            $chartOfAccStaticData = DB::table('accounts_chart_of_accounts_static_data')->get();
            DB::beginTransaction();
            try {
                $coa_data = [];
                foreach ($chartOfAccStaticData as $v) {
                    $coa_data[] = ['account_code' => $v->account_code, 'account_name' => $v->account_name, 'parent_id' => $v->parent_id, 'account_type' => (!empty($v->account_type)) ? $v->account_type : NULL, 'increase_by' => $v->increase_by, 'layer' => $v->layer, 'uid' => $v->uid, 'valid' => $v->valid, 'created_by' => $user_id, 'created_at' => $created_at];
                }
                ChartOfAccount::insert($coa_data);
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }
        }

        $nature = ChartOfAccount::where('parent_id', 0)->orderBy('id', 'asc')->get();
        $accounts = ChartOfAccount::all()->keyBy('id');
        // Trial Balance Data Processing Start
        $opening = 0;
        $debit = 0;
        $credit = 0;
        foreach ($nature as $na) {
            $data = $this->calculateTrialBalance($na->id, $fromDate, $toDate, $accounts);
            $opening += $data['opening_balance'];
            $debit += $data['debit'];
            $credit += $data['credit'];
        }
        $trialBalanceDatas = $this->trialBalanceDatas;
        // Trial Balance Data Processing End

        // return $trialBalanceDatas;

        if ($selectType == 'search') {
            $tableView = view('accounts::trial-balance.snippets.trial-balance-table', compact('pageAccessData','nature', 'accounts', 'trialBalanceDatas', 'reportType', 'fromDate', 'toDate', 'opening', 'debit', 'credit'))->render();
            return ['status' => true, 'data' => $tableView];
        } else {
            $institute = Institute::findOrFail(self::getInstituteId());
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('accounts::trial-balance.snippets.trial-balance-pdf', compact('institute', 'user', 'nature', 'accounts', 'trialBalanceDatas', 'reportType', 'fromDate', 'toDate', 'opening', 'debit', 'credit'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
    }

    // DEV5 END

    public function ledgerDetails($id, $type, $fromDate, $toDate,Request  $request)
    {

       $pageAccessData = self::linkAccess($request, ['manualRoute' => 'accounts/ledger-report']);

        $ledgerInfo = ChartOfAccount::findOrfail($id);
        //return $ledgerInfo;
        //return $this->getLedgerStatement('2022-01-12','2022-10-12',$id);
        $allLedgers = ChartOfAccount::all()->keyBy('id');
        // return $allLedgers;
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $startDate = Carbon::make($fromDate)->toDateTimeString();
        $endData = Carbon::make($toDate)->toDateTimeString();
        $ledgerData = SubsidiaryCalculationModel::where(
            [
                'campus_id' => $campus_id,
                'institute_id' => $institute_id,
                'sub_ledger' => $id,'status' => 1
            ]
        )->where('trans_date', '<', $startDate)->get();

        $credited = 0;
        $debited = 0;
        foreach ($ledgerData as $data) {
            $credited += $data->credit_amount;
            $debited += $data->debit_amount;
        }
        $opening_balance = [];
        $balance = $credited - $debited;
        if ($balance < 0) {
            $opening_balance['type'] = 'Dr';
        } else if ($balance > 0) {
            $opening_balance['type'] = 'Cr';
        } else {
            $opening_balance['type'] = '';
        }
        $opening_balance['balance'] = $balance;
        // return $debited-$credited
        $ledgerData = SubsidiaryCalculationModel::where(
            [
                'campus_id' => $campus_id,
                'institute_id' => $institute_id,
                'sub_ledger' => $id,'status' => 1
            ]
        )->whereBetween('trans_date', [$startDate, $endData])->get()->sortBy('trans_date');
        ($type=='summary') ? $tableType=0 : $tableType=1;

        return view('accounts::trial-balance.ledger-details', ['allLedgers' => $allLedgers, 'ledgers' => $ledgerData,
            'opening_balance' => $opening_balance], compact('pageAccessData','tableType','ledgerInfo', 'fromDate', 'toDate'));



    }

    public function ledgerSearch(Request $request)
    {

        $fromDate = $request->input('start_date');
        $toDate = $request->input('end_date');
        $id = $request->input('id');
        $ledgerInfo = ChartOfAccount::findOrfail($id);

        $allLedgers = ChartOfAccount::all()->keyBy('id');

        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $startDate = Carbon::make($fromDate)->toDateTimeString();
        $endData = Carbon::make($toDate)->toDateTimeString();
        $ledgerData = SubsidiaryCalculationModel::where(
            [
                'campus_id' => $campus_id,
                'institute_id' => $institute_id,
                'sub_ledger' => $id,'status' => 1
            ]
        )->where('trans_date', '<', $startDate)->get();
        $credited = 0;
        $debited = 0;
        foreach ($ledgerData as $data) {
            $credited += $data->credit_amount;
            $debited += $data->debit_amount;
        }
        $opening_balance = [];
        $balance = $credited - $debited;
        if ($balance < 0) {
            $opening_balance['type'] = 'Dr';
        } else if ($balance > 0) {
            $opening_balance['type'] = 'Cr';
        } else {
            $opening_balance['type'] = '';
        }
        $opening_balance['balance'] = $balance;
        // return $debited-$credited
        $ledgerData = SubsidiaryCalculationModel::where(
            [
                'campus_id' => $campus_id,
                'institute_id' => $institute_id,
                'sub_ledger' => $id,'status' => 1
            ]
        )->whereBetween('trans_date', [$startDate, $endData])->get()->sortBy('trans_date');
        if ($request->report_type == "summary") {
            $tableType=0;
        }else {
            $tableType=1;
        }
        if($request->type=='print'){
            // return Excelldownload(new LedgerExcel,'users.xlsx');
            $institute = Institute::findOrFail(self::getInstituteId());
            $campus = Campus::findOrFail(self::getCampusId());
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('accounts::trial-balance.snippets.ledger-summary-pdf',
                ['allLedgers' => $allLedgers,
                    'ledgers' => $ledgerData,
                    'opening_balance' => $opening_balance], compact('campus', 'tableType','ledgerInfo', 'institute', 'user',
                    'fromDate', 'toDate'))->setPaper('a4', 'portrait');
            return $pdf->stream('ledger-statement.pdf');
        }else{
            $tableView = view('accounts::trial-balance.snippets.ledger-details-table',  ['allLedgers' => $allLedgers, 'ledgers' => $ledgerData,
                'opening_balance' => $opening_balance], compact('tableType','ledgerInfo', 'fromDate', 'toDate'))->render();


            return ['status' => 'success', 'data' => $tableView];
        }




    }

    public function show($id)
    {
        return view('accounts::show');
    }

    public function edit($id)
    {
        return view('accounts::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getLedgerStatement($from, $to, $ledgerId)
    {
        $data = [];
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $startDate = Carbon::make($from)->toDateTimeString();
        $endData = Carbon::make($to)->toDateTimeString();
        $ledgerData = SubsidiaryCalculationModel::with('transaction')->where(
            [
                'campus_id' => $campus_id,
                'institute_id' => $institute_id,
                'sub_ledger' => $ledgerId,
                'status' => 1
            ]
        )->get();
        //return $ledgerData;
        if (!$ledgerData) {
            $data['opening_balance'] = 0;
            $data['opening_balance_type'] = '';
            $data['debit'] = 0;
            $data['credit'] = 0;
            $data['closing_balance'] = 0;
            $data['closing_balance_type'] = '';
        } else {
            $fromOpeningLedger = $ledgerData->where('trans_date_time', '<', $startDate);
            $credited = 0;
            $debited = 0;
            foreach ($fromOpeningLedger as $single_ledger) {
                $credited += $single_ledger->credit_amount;
                $debited += $single_ledger->debit_amount;
            }
            $balance = $credited - $debited;
            if ($balance < 0) {
                $type = 'Dr';
            } else if ($balance > 0) {
                $type = 'Cr';
            } else {
                $type = '';
            }
            $data['opening_balance'] = $balance;
            $data['opening_balance_type'] = $type;
            $credited = 0;
            $debited = 0;
            $dateRangedLedgerDatas = $ledgerData->whereBetween('trans_date', [$startDate, $endData]);
            foreach ($dateRangedLedgerDatas as $singleLedger) {
                $credited += $singleLedger->credit_amount;
                $debited += $singleLedger->debit_amount;
            }
            $data['debit'] = $debited;
            $data['credit'] = $credited;
            $closing_balance = $credited - $debited + $balance;
            if ($closing_balance < 0) {
                $type = 'Dr';
            } else if ($closing_balance > 0) {
                $type = 'Cr';
            } else {
                $type = '';
            }
            $data['closing_balance'] = $closing_balance;
            $data['closing_balance_type'] = $type;


        }
        return $data;


    }
}
