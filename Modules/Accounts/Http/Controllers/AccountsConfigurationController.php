<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\ChartOfAccount;
use Modules\Accounts\Entities\ChartOfAccountsConfigModel;
use Modules\Accounts\Entities\AccountsConfigurationModel;
use App\Helpers\UserAccessHelper;


class AccountsConfigurationController extends Controller
{
    use UserAccessHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['pageAccessData'] = self::linkAccess($request);
        $acc_config = AccountsConfigurationModel::select('label_name', 'display_label_name', 'order_no')->orderBy('order_no', 'asc')->get();
        $accounts_config=[]; $check_array=[];
        foreach($acc_config as $v){
            if(!array_key_exists($v->label_name, $check_array)){
                $check_array[$v->label_name] = $v;
                $accounts_config[] = $v;
            }
        }
        $data['accounts_config'] = $accounts_config;
        return view('accounts::accounts-config.accounts-config', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('accounts::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('accounts::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($label_name)
    {
        $data['accountInfo'] = AccountsConfigurationModel::where('label_name',$label_name)->orderBy('order_no', 'asc')->get();
        $data['label_name'] = $label_name;
        $data['accounts'] = ChartOfAccount::get()->keyBy('id');
        $data['auto_permission'] = ['vendor_ledger','customer_ledger'];
        $coaConfig = ChartOfAccountsConfigModel::first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        return view('accounts::accounts-config.modal.edit-accounts-config', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $label_name)
    {
        // check all data 
        $flag=true; $msg = '';
        $particular_ids = $request->particular_id;
        $code_type = $request->code_type;
        $ids = $request->id; 
        foreach($particular_ids as $k=>$v){
            if(!empty($v)){
                $checkCode = DB::table('accounts_chart_of_accounts')->where('id', $v)->first();
                if(empty($checkCode)){
                    $msg = 'Invalid account code.';
                    $flag=false;
                    break;
                }
            }else{
                $msg = 'Fill all label account code.';
                $flag=false;
                break;
            }
        }
        if($flag){
            DB::beginTransaction();
            try {
                $particular_code_col = ($code_type=='Manual')?'manual_account_code':'account_code';
                foreach($particular_ids as $k=>$v){
                    $update_id = $ids[$k];
                    $checkCode = DB::table('accounts_chart_of_accounts')->where('id', $v)->first();
                    if(!empty($checkCode)){
                        AccountsConfigurationModel::where('id', $update_id)->update([
                            'particular_code'=>$checkCode->{$particular_code_col},
                            'particular_id'=>$checkCode->id,
                            'account_type'=>$checkCode->account_type,
                            'increase_by'=>$checkCode->increase_by,
                            'updated_by'=>Auth::user()->id
                        ]);
                    }
                }
                Session::flash('message', 'Accounts configuration updated successfully.');
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }  
        }else{
           Session::flash('errorMessage', $msg); 
        }
     
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
