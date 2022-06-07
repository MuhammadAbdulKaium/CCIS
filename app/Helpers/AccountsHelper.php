<?php 
  	namespace App\Helpers;

use App\Models\Role;
use DB;
	use App\User;
	use App\UserInfo;
	use App\UserVoucherApprovalModel;
	use Modules\Accounts\Entities\AccountsConfigurationModel;
	use Modules\Accounts\Entities\ChartOfAccount;
	use Modules\Accounts\Entities\AccountsVoucherConfigModel;
	use Modules\Accounts\Entities\AccountsTransactionModel;
	use Modules\Accounts\Entities\SubsidiaryCalculationModel;
	use Modules\Accounts\Entities\ChartOfAccountsConfigModel;
	use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Accounts\Entities\AccountsVoucherApprovalLogModel;
use Modules\LevelOfApproval\Entities\ApprovalLayer;

trait AccountsHelper {
		public static function getInstituteId(){
			return session()->get('institute');
		}
		public static function getCampusId(){
			return session()->get('campus');
		}
		public static function getAccCodeType(){
			$coaConfig = ChartOfAccountsConfigModel::first();
	        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
	           $code_type = $coaConfig->code_type;
	        }else{
	           $code_type = 'Auto'; 
	        }
	        return $code_type;
		}
		public static function cashBankLedgerId(){
			$acc_config = AccountsConfigurationModel::where('particular', 'cash_hand')->orWhere('particular', 'cash_bank')->get();
			$acc_config_keyBy = $acc_config->keyBy('particular')->all();
			$cashBankIds = [];
			if(isset($acc_config_keyBy['cash_bank'])){
				$cash_bank_config = $acc_config_keyBy['cash_bank'];
				if($cash_bank_config->account_type=='group'){
					$cashBankAccounts  = ChartOfAccount::where('parent_id', $cash_bank_config->particular_id)->get();
					if(count($cashBankAccounts)>0){
						$cashBankIds = $cashBankAccounts->pluck('id')->all();
					}
				}else{
					$cashBankIds = [$cash_bank_config->particular_id];
				}
			}
			if(isset($acc_config_keyBy['cash_hand'])){
				$cash_hand_config = $acc_config_keyBy['cash_hand'];
				if($cash_hand_config->account_type=='group'){
					$cashHandAccounts  = ChartOfAccount::where('parent_id', $cash_hand_config->particular_id)->get();
					if(count($cashHandAccounts)>0){
						$mergeArray = array_merge($cashBankIds,$cashHandAccounts->pluck('id')->all());
						$cashBankIds = $mergeArray;
					}
				}else{
					$mergeArray = array_merge($cashBankIds,[$cash_hand_config->particular_id]);
					$cashBankIds = $mergeArray;
				}
			}
			
			return $cashBankIds;

		}

		public static function bankLedgerIds(){
			$bank_ledger_id = AccountsConfigurationModel::where('particular', 'cash_bank')->first()->particular_id;
			$bankAccount = ChartOfAccount::where('parent_id', $bank_ledger_id)->get();
			$bankIds = (count($bankAccount)>0)?$bankAccount->pluck('id')->all():[];
			return $bankIds;
		}

		public static function cashBankGroupLedger(){
			$acc_config = AccountsConfigurationModel::where('particular', 'cash_hand')->orWhere('particular', 'cash_bank')->get()->keyBy('particular')->all();
			return $acc_config;
		}

		public static function mergeEmtyArryObj($collection, $emptyArray=[]){
   			$newCollection = $collection->all();
            array_unshift($newCollection,$emptyArray);   			
   			return $newCollection;
   		}

   		public static function ledgerClosingDataFormate($ledger,$ledgerClosingBalanceData, $emptyArray=[]){
   			$ledgerData = [$emptyArray];
	        foreach($ledger as $v){
	        	$accountCode = $v->accountCode;
	            if(array_key_exists($v->id, $ledgerClosingBalanceData)){
	                $closingBalance = $ledgerClosingBalanceData[$v->id]->closingBalance;
	                if($closingBalance==0){
	                	$accountCodeView = $v->accountCode;
	                }else{
		                if($closingBalance>0){
		                    $balanceType = ($v->increase_by=='debit')?'Dr':'Cr';
		                }else{
		                    $closingBalance = abs($closingBalance);
		                    $balanceType = ($v->increase_by=='debit')?'Cr':'Dr';
		                }
		                $accountCodeView = $v->accountCode.' --- '.$closingBalance.' '.$balanceType;
		            }
	            }else{
	                $accountCodeView = $v->accountCode;
	            }
	            $rowData = ['id'=>$v->id, 'accountCode'=>$accountCode,'accountCodeView'=>$accountCodeView];
	            array_push($ledgerData, $rowData);
	        }
	        return $ledgerData;
   		}

   		public static function ledgerClosingBalance(){
   			$ledgerClosingBalance = SubsidiaryCalculationModel::selectRaw('sub_ledger, 
   				CASE increase_by 
   					WHEN "debit" THEN sum(debit_amount)-sum(credit_amount)
   					WHEN "credit" THEN sum(credit_amount)-sum(debit_amount)
   					ELSE "0"
   				END as closingBalance
   				')
   			->module()
   			->approved()
   			->groupBy('sub_ledger','increase_by')->get()->keyBy('sub_ledger')->all();
   			return $ledgerClosingBalance;
   		}

   		public static function getAccLedger($acc_code_type, $whereIn=array(), $whereNotIn=array()){
   			if($acc_code_type=='Manual'){
   				$accountCode = "concat('[',IFNULL(accounts_chart_of_accounts.manual_account_code,''), '] ', accounts_chart_of_accounts.account_name) as accountCode";
   			}else{
   				$accountCode = "concat('[',accounts_chart_of_accounts.account_code, '] ', accounts_chart_of_accounts.account_name) as accountCode";
   			}
   			$ledgerQuery  = ChartOfAccount::select('accounts_chart_of_accounts.id','accounts_chart_of_accounts.increase_by',DB::raw($accountCode));
   			if(count($whereIn)>0){
   				$ledgerQuery->whereIn('id',$whereIn);
   			}
   			if(count($whereNotIn)>0){
   				$ledgerQuery->whereNotIn('id',$whereNotIn);
   			}
            $ledger = $ledgerQuery->where('account_type', 'ledger')
                ->orderBy('account_code', 'asc')
                ->get();
            return $ledger;

   		}

   		public static function checkAccVoucher($type){
   			$voucherConfig = AccountsVoucherConfigModel::module()->where('type_of_voucher', $type)->where('status',1)->first();
   			if(!empty($voucherConfig)){
   				$voucher_config_id = $voucherConfig->id;
   				$voucher_conf = true;
   				if($voucherConfig->numbering=='auto'){
   					$voucher_no='Auto';
   					$auto_voucher=true;
   				}else{
   					$voucher_no='';
   					$auto_voucher=false;
   				}
   			}else{
   				$voucher_conf = false;
   				$voucher_no = '';
   				$auto_voucher = false;
   				$voucher_config_id = 0;
   			}
   			return [
				'voucher_no' => $voucher_no,
				'auto_voucher' =>$auto_voucher,
				'voucher_conf' =>$voucher_conf,
				'voucher_config_id'=>$voucher_config_id
			];
   		}

		public static function getAccVoucher($type, $payment_receive_by=""){
			$voucher_no = false; $voucher_int = false; $voucher_config_id=0; $msg='';
			if(($type==1||2) && !empty($payment_receive_by)){ 
				if($type==1){
					$type_of_voucher = ($payment_receive_by=='Bank')?1:2;
				}else{
					$type_of_voucher = ($payment_receive_by=='Bank')?3:4;
				}
			}else{
				$type_of_voucher = ($type==3)?5:6;
			}
			$voucherConfig = AccountsVoucherConfigModel::module()->where('type_of_voucher',$type_of_voucher)->where('status', 1)->first();
			
			if(!empty($voucherConfig)){
				$voucher_config_id = $voucherConfig->id;
				if(($type==1||2) && !empty($payment_receive_by)){
					$checkVoucher = AccountsTransactionModel::module()->where('voucher_type', $type)->where('payment_receive_by',$payment_receive_by)->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int_no', 'desc')->first();
				}else{
					$checkVoucher = AccountsTransactionModel::module()->where('voucher_type', $type)->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int_no', 'desc')->first();
				}
				if(!empty($checkVoucher)){
					$voucher_int = $checkVoucher->voucher_int_no+1;
				}else{
					$voucher_int = $voucherConfig->starting_number;
				}
				$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
				$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
				if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;

			}else{
				$msg = 'Account voucher not configure';
			}
			return [
				'voucher_no' => $voucher_no,
				'voucher_int_no' =>$voucher_int,
				'voucher_config_id'=>$voucher_config_id,
				'msg'=>$msg
			];
		}
		public static function isFloat($number){ // check float and decimal places is greater then 0
			if(gettype($number)=='string'){
				if(Str::contains($number,'.')){
					$explode = explode('.', $number);
					return (@$explode[1]>0)?true:false;
				}else{
					return false;
				}
			}else{
				if(is_float($number)){
					$explode = explode('.', $number);
					return (@$explode[1]>0)?true:false;
				}else{
					return false;
				}
			}
		}

		public static function oldGetApprovalInfo($approval_name){
   			$auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalModel::module()->valid()->where('approval_name', $approval_name)->get();
            $step=1; $approval_access=true; $last_step = 1; // step and approval access
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
                $UserVoucherApprovalLastLayer = UserVoucherApprovalModel::module()->valid()->where('approval_name', $approval_name)->orderBy('step', 'desc')->first();
                $last_step = $UserVoucherApprovalLastLayer->step;
            }
            return [
            	'step'=>$step,
            	'approval_access'=>$approval_access,
            	'last_step'=>$last_step
            ];
   		}

		public static function voucherTypeNumber($voucher_type){
			if ($voucher_type == 'payment_voucher') {
				return 1;
			} else if ($voucher_type == 'receive_voucher'){
				return 2;
			} else if ($voucher_type == 'journal_voucher'){
				return 3;
			} else if ($voucher_type == 'contra_voucher'){
				return 4;
			} else {
				return null;
			}
		}

		public static function alreadyApproved($voucher, $voucher_type, $auth_user_id){
			$voucherApprovalLog = AccountsVoucherApprovalLogModel::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'voucher_id' => $voucher->id,
				'voucher_type' => self::voucherTypeNumber($voucher_type),
				'approval_id' => $auth_user_id,
				'approval_layer' => $voucher->approval_level,
			])->first();

			if ($voucherApprovalLog) {
				if($voucherApprovalLog->action_status == 0){
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}

		public static function someOneApproved($approval_type, $voucherId)
		{
			$approvalLog = AccountsVoucherApprovalLogModel::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'voucher_type' => self::voucherTypeNumber($approval_type),
				'voucher_id' => $voucherId
			])->first();

			if ($approvalLog) {
				return true;
			}
			return false;
		}

   		public static function getApprovalInfo($approval_type, $approvalData){
			$auth_user = Auth::user();
			$allApprovalLayers = ApprovalLayer::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'level_of_approval_unique_name' => $approval_type,
			]);
			$approvalLayer = $allApprovalLayers->get()->firstWhere('layer', $approvalData->approval_level);
			$approval_access=false;
			$last_step = $allApprovalLayers->max('layer');
			
			if ($approvalLayer) {
				if ($approvalLayer->user_ids) {
					$userIds = json_decode($approvalLayer->user_ids);
					if (in_array($auth_user->id, $userIds)) {
						if (!self::alreadyApproved($approvalData, $approval_type, $auth_user->id)) {
							$approval_access = true;
						}
					}
				} else if ($approvalLayer->role_id){
					if ($auth_user->role()->id == $approvalLayer->role_id) {
						if (!self::alreadyApproved($approvalData, $approval_type, $auth_user->id)) {
							$approval_access = true;
						}
					}
				} else {
					$approval_access = false;
				}
			} else {
				$approval_access = false;
			}
			
			return [
				'approval_access'=>$approval_access,
				'last_step'=>$last_step
			];
		}

		public static function allUserApproved($voucher_type, $voucher, $userIds){
			$voucherApprovalLogs = AccountsVoucherApprovalLogModel::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'voucher_id' => $voucher->id,
				'voucher_type' => self::voucherTypeNumber($voucher_type),
				'approval_layer' => $voucher->approval_level,
			])->get()->keyBy('approval_id');

			foreach ($userIds as $userId) {
				if (isset($voucherApprovalLogs[$userId])) {
					if ($voucherApprovalLogs[$userId]->action_status != 1) {
						return false;
					}
				} else {
					return false;
				}
			}

			if (sizeof($userIds)>0) {
				return true;
			} else {
				return false;
			}
		}

		public static function anyUserApproved($voucher_type, $voucher, $userIds){
			$voucherApprovalLogs = AccountsVoucherApprovalLogModel::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'voucher_id' => $voucher->id,
				'voucher_type' => self::voucherTypeNumber($voucher_type),
				'approval_layer' => $voucher->approval_level,
			])->get()->keyBy('approval_id');

			foreach ($userIds as $userId) {
				if (isset($voucherApprovalLogs[$userId])) {
					if ($voucherApprovalLogs[$userId]->action_status == 1) {
						return true;
					}
				}
			}

			return false;
		}

		public static function getUserIdsFromApprovalLayer($approval_type, $approvalLevel)
		{
			$approvalLayer = ApprovalLayer::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'level_of_approval_unique_name' => $approval_type,
				'layer' => $approvalLevel
			])->first();
			
			if ($approvalLayer) {
				if($approvalLayer->user_ids){
					$userIds = json_decode($approvalLayer->user_ids);
				}else if($approvalLayer->role_id){
					$userIds = Role::findOrFail($approvalLayer->role_id)->roleUsers->pluck('id');
				}else{
					$userIds = [];
				}
			} else {
				$userIds = [];
			}
			return $userIds;
		}

		public static function approvalLayerPassed($approval_type, $approvalData, $ignCurUser){
			$auth_user = Auth::user();
			$approvalLayer = ApprovalLayer::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'level_of_approval_unique_name' => $approval_type,
				'layer' => $approvalData->approval_level
			])->first();

			if($approvalLayer->user_ids){
				$userIds = json_decode($approvalLayer->user_ids);
			}else if($approvalLayer->role_id){
				$userIds = Role::findOrFail($approvalLayer->role_id)->roleUsers->pluck('id');
			}else{
				$userIds = [];
			}

			if ($approvalLayer->all_members == 'yes') {
				if ($ignCurUser && (($key = array_search($auth_user->id, $userIds)) !== false)) {
					unset($userIds[$key]);
					if (sizeof($userIds)<1) {
						return true;
					}
				}
				return self::allUserApproved($approval_type, $approvalData, $userIds);
			} else {
				if ($ignCurUser) {
					return true;
				}
				return self::anyUserApproved($approval_type, $approvalData, $userIds);
			}

			return false;
		}

		public static function generateApprovalHistoryInfo($voucher_type, $voucher){
			$auth_user_id = Auth::user()->id;
			$allApprovalLayers = ApprovalLayer::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'level_of_approval_unique_name' => $voucher_type,
			])->orderBy('layer')->get()->keyBy('layer');
			$voucherApprovalLogs = AccountsVoucherApprovalLogModel::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
				'voucher_id' => $voucher->id,
				'voucher_type' => self::voucherTypeNumber($voucher_type)
			])->get()->groupBy('approval_layer');
			$approvalInfo = [];

			foreach ($allApprovalLayers as $key => $approvalLayer) {
				$approvedUsersInfo = [];
				if (isset($voucherApprovalLogs[$key])) {
					foreach ($voucherApprovalLogs[$key] as $approvalLog) {
						$approvedUsersInfo[$approvalLog->approval_id] = [
							'user_id' => $approvalLog->approval_id,
							'approved_at' => $approvalLog->date,
						];
					}
				}
				if ($key == $voucher->approval_level) {
					$approvedUsersInfo[$auth_user_id] = [
						'user_id' => $auth_user_id,
						'approved_at' => Carbon::now(),
					];
				}
				$approvalInfo[$key] = [
					'step' => $key,
					'allMembers' => $approvalLayer->all_members,
					'role_id' => $approvalLayer->role_id,
					'user_ids' => ($approvalLayer->user_ids)?json_decode($approvalLayer->user_ids, 1):null,
					'users_approved' => $approvedUsersInfo
				];
			}

			return $approvalInfo;
		}

		public static function getAllUsers()
		{
			$allUserIds = UserInfo::where([
				'campus_id' => self::getCampusId(),
				'institute_id' => self::getInstituteId(),
			])->pluck('user_id')->toArray();
			return User::whereIn('id', $allUserIds)->orWhere('username', 'superadmin')->get()->keyBy('id');
		}
	}