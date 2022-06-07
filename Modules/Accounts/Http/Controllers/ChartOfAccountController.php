<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\ChartOfAccount;
use Modules\Accounts\Entities\SubsidiaryCalculationModel;
use Modules\Accounts\Entities\ChartOfAccountsConfigModel;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use Modules\Accounts\Entities\AccountsConfigurationModel;
use Modules\Inventory\Entities\VendorModel;
use App\Helpers\UserAccessHelper;

class ChartOfAccountController extends Controller
{
    use UserAccessHelper;
    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $accounts = ChartOfAccount::all()->keyBy('id');
        return view('accounts::chart-of-accounts.index', compact('accounts', 'pageAccessData'));
    }

    public function create()
    {
        $data['accounts'] = ChartOfAccount::where('account_type','!=','ledger')->get()->keyBy('id');
        $coaConfig = ChartOfAccountsConfigModel::first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        return view('accounts::chart-of-accounts.modal.create-group',$data);
    }

    public function chartOfAccountsConfig(){
        $data['coaConfig'] = ChartOfAccountsConfigModel::first();
        $data['code_type'] = (!empty($data['coaConfig']))?$data['coaConfig']->code_type:'Auto';
        return view('accounts::chart-of-accounts.modal.chart-of-acc-config', $data);
    }

    public function chartOfAccountsConfigUpdate(Request $request){

        $id = $request->id;
        $code_type = $request->code_type;
        $data = [
            'code_type'=>$code_type
        ];
        if(!empty($id)){
            ChartOfAccountsConfigModel::find($id)->update($data);
        }else{
            ChartOfAccountsConfigModel::create($data);
        }
        Session::flash('message', 'Chart of account config updated successfully.');
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $code_type = $request->code_type;
        $rules = [
            'account_type' => 'required',
            'account' => 'required',
            'name' => 'required|unique:accounts_chart_of_accounts,account_name,,id,deleted_at,NULL',
        ];
        if($code_type=='Manual'){
            $rules['manual_account_code'] = 'required|unique:accounts_chart_of_accounts,manual_account_code,,id,deleted_at,NULL';
            $manual_account_code = $request->manual_account_code;
        }else{
            $manual_account_code = NULL;
        }
        $request->validate($rules);

        $parentAccount = ChartOfAccount::findOrFail($request->account);

        //Siblings check with different type Start
        $existingSiblings = ChartOfAccount::where([
            ['parent_id', $parentAccount->id],
            ['account_type', '!=', $request['account_type']]
        ])->first();
        if ($existingSiblings) {
            Session::flash('errorMessage', 'Can not save dfferent account type at same layer!');
            return redirect()->back();
        }
        if (($parentAccount->layer + 1) > 6) {
            Session::flash('errorMessage', 'Can not make more than 6 layers!');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $ChartOfAccount = ChartOfAccount::create([
                'account_code' => 0,
                'manual_account_code' => $manual_account_code,
                'account_name' => $request->name,
                'parent_id' => 0,
                'account_type' => $request->account_type,
                'increase_by' => $parentAccount->increase_by,
                'layer' => 0,
                'uid' => 0,
            ]);
            $sundry_creditors_config = AccountsConfigurationModel::where('particular', 'sundry_creditors')->first();
            if(!empty($sundry_creditors_config)){
                $sundry_creditors_id = $sundry_creditors_config->particular_id;
            }else{
                $sundry_creditors_id = 0; 
            }
            if ($ChartOfAccount) {
                $accountCodeCredentials = $this->accountCodeGenerate($parentAccount, $request->account_type, null);
                $existingAccount = ChartOfAccount::where('account_code', $accountCodeCredentials['accountCode'])->first();

                if (!$existingAccount) {
                    // auto vendor insert
                    if($sundry_creditors_id==$request->account && $request->account_type=='ledger'){
                        $vendorData = [
                            'category_id'=>1,
                            'type'=>1,
                            'name'=>$request->name,
                            'gl_code'=>($code_type=='Manual')?$manual_account_code:$accountCodeCredentials['accountCode']
                        ];
                        VendorModel::create($vendorData);
                    }
                    $ChartOfAccount->update([
                        'account_code' => $accountCodeCredentials['accountCode'],
                        'parent_id' => $request->account,
                        'layer' => ($parentAccount->layer + 1),
                        'uid' => $accountCodeCredentials['uid'],
                    ]);
                } else {
                    DB::rollback();
                    Session::flash('errorMessage', 'Auto Generated code duplicated!');
                    return redirect()->back();
                }
            }

            DB::commit();
            Session::flash('message', 'Chart of account created successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating chart of accounts.');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        return view('accounts::show');
    }

    public function edit($id)
    {

        $data['account'] = ChartOfAccount::findOrFail($id);
        $coaConfig = ChartOfAccountsConfigModel::first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        return view('accounts::chart-of-accounts.modal.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $code_type = $request->code_type;
        $rules = [
            'name' => "required|unique:accounts_chart_of_accounts,account_name,{$id},id,deleted_at,NULL",
            'type' => 'required',
        ];
        if($code_type=='Manual'){
            $rules['manual_account_code'] = "required|unique:accounts_chart_of_accounts,manual_account_code,{$id},id,deleted_at,NULL";
            $manual_account_code = $request->manual_account_code;
        }else{
            $manual_account_code = NULL;
        }
        $request->validate($rules);

        $account = ChartOfAccount::findOrFail($id);
        $auto_account_code_db = $account->account_code;
        $manual_account_code_db = $account->manual_account_code;
        $subsidaryCalculation = SubsidiaryCalculationModel::where('sub_ledger', $id)->first();
        if(!empty($subsidaryCalculation) && $account->account_type != $request->type){
            Session::flash('errorMessage', 'Dependencies accounts transaction found, can not change this account type.');
            return redirect()->back();
        }

        $parentAccount = ChartOfAccount::findOrFail($account->parent_id);
        $accountCodeCredentials = $this->accountCodeGenerate($parentAccount, $request->type, $account->id);

        //Siblings check with different type Start
        $existingSiblings = ChartOfAccount::where([
            ['id', '!=', $account->id],
            ['parent_id', $parentAccount->id],
            ['account_type', '!=', $request['type']]
        ])->first();
        if ($existingSiblings) {
            Session::flash('errorMessage', 'Can not save dfferent account type at same layer!');
            return redirect()->back();
        }
        //Layer Check
        if (($account->layer + 1) > 6) {
            Session::flash('errorMessage', 'Can not make more than 6 layers!');
            return redirect()->back();
        }

        if ($request->type == 'ledger') {
            $existingAccount = ChartOfAccount::where('parent_id', $id)->first();
            if ($existingAccount) {
                Session::flash('errorMessage', 'This account has childs can not change the type to ledger.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $existingAccount = ChartOfAccount::where('account_code', $accountCodeCredentials['accountCode'])->where('id', '!=', $account->id)->first();

            if (!$existingAccount) {
                // check and update vendor gl code start
                $sundry_creditors_config = AccountsConfigurationModel::where('particular', 'sundry_creditors')->first();
                if(!empty($sundry_creditors_config)){
                    $sundry_creditors_id = $sundry_creditors_config->particular_id;
                }else{
                    $sundry_creditors_id = 0; 
                }
                if($sundry_creditors_id==$account->parent_id && $request->type=='ledger'){
                    VendorModel::where(function($query)use($manual_account_code_db,$auto_account_code_db){
                        $query->where('gl_code',$manual_account_code_db)->orWhere('gl_code', $auto_account_code_db);
                    })->update(['gl_code'=>($code_type=='Manual')?$manual_account_code:$accountCodeCredentials['accountCode']]);
                }
                // check and update vendor gl code end
                $account->update([
                    'account_name' => $request->name,
                    'account_type' => $request->type,
                    'account_code' => $accountCodeCredentials['accountCode'],
                    'manual_account_code'=>($code_type=='Manual')?$manual_account_code:$manual_account_code_db,
                    'layer' => ($parentAccount->layer + 1),
                    'uid' => $accountCodeCredentials['uid'],
                ]);
            } else {
                Session::flash('errorMessage', 'Error updating chart of accounts.');
                return redirect()->back();
            }
            DB::commit();
            Session::flash('message', 'Chart of account updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating chart of accounts.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $child = ChartOfAccount::where('parent_id', $id)->first();
        $subsidaryCalculation = SubsidiaryCalculationModel::where('sub_ledger', $account->id)->first();

        if ($child) {
            Session::flash('errorMessage', 'It has child account, can not delete this account.');
            return redirect()->back();
        } else if ($subsidaryCalculation) {
            Session::flash('errorMessage', 'Dependencies accounts transaction found, can not delete this account.');
            return redirect()->back();
        } else {
            $account->delete();
            Session::flash('message', 'Account deleted successfully.');
            return redirect()->back();
        }
    }

    public function createLedger()
    {
        $data['accounts'] = ChartOfAccount::where('account_type','!=','ledger')->get()->keyBy('id');
        $coaConfig = ChartOfAccountsConfigModel::first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        return view('accounts::chart-of-accounts.modal.create-ledger',$data);
    }

    public function accountCodeGenerate($parentAccount, $newAccountType, $id)
    {
        $accountCode = '';
        $parentAccountCode = explode("--", $parentAccount->account_code);
        if ($id) {
            $account = ChartOfAccount::where('parent_id', $parentAccount->id)->where('id', '!=', $id)->latest()->first();
            $uid = ($account) ? $account->uid : 0;
        } else {
            $account = ChartOfAccount::where('parent_id', $parentAccount->id)->latest()->first();
            $uid = ($account) ? $account->uid : 0;
        }
        $uid++;

        if ($newAccountType == 'group') {
            $accountCode = $parentAccountCode[0] . "-" . $uid;
        } elseif ($newAccountType == 'ledger') {
            $accountCode = $parentAccountCode[0] . "--" . $uid;
        }

        return ['accountCode' => $accountCode, 'uid' => $uid];
    }
}
