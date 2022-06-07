<?php

namespace Modules\House\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\Batch;
use Modules\House\Entities\House;
use Modules\House\Entities\PocketMoney;
use Modules\House\Entities\PocketMoneyHistory;
use Modules\Payroll\Entities\BankBranchDetails;
use Modules\Payroll\Entities\BankDetails;
use Modules\Student\Entities\StudentProfileView;
use Svg\Tag\Rect;

class PocketMoneyController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    
    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::all();

        return view('house::pocket-money.index', compact('houses', 'batches'));
    }

    public function create()
    {
        return view('house::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('house::show');
    }

    public function edit($id)
    {
        return view('house::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function searchCadets(Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute'=>'house/pocket-money']);

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');
        $students = StudentProfileView::with('singleUser', 'singleStudent', 'roomStudent', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'status' => 1
        ])->get();
        $banks = BankDetails::with('branches')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $pocketMoneyRows = PocketMoney::with('bankBranch')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('std_id');
        if($request->houseId){
            $house = House::findOrFail($request->houseId);
            $stdIds = $house->roomStudents->pluck('student_id')->toArray();
            $students = $students->whereIn('std_id', $stdIds);
        }
        if ($request->batchId) {
            $students = $students->where('batch', $request->batchId);
        }
        if ($request->sectionId) {
            $students = $students->where('section', $request->sectionId);
        }

        return view('house::pocket-money.cadet-list', compact('pageAccessData', 'houses', 'students', 'banks', 'pocketMoneyRows'))->render();
    }

    protected function makePocketMoneyHistoryData($pocketMoney)
    {
        return [
            'pocket_money_id' => $pocketMoney->id,
            'std_id' => $pocketMoney->std_id,
            'account_no' => $pocketMoney->account_no,
            'bank_branch_id' => $pocketMoney->bank_branch_id,
            'account_balance' => $pocketMoney->account_balance,
            'money_in' => $pocketMoney->money_in,
            'last_allotment' => $pocketMoney->last_allotment,
            'total_allotment' => $pocketMoney->total_allotment,
            'last_expense' => $pocketMoney->last_expense,
            'total_expense' => $pocketMoney->total_expense,
            'status' => ($pocketMoney->status)?$pocketMoney->status:1,
            'campus_id' => $pocketMoney->campus_id,
            'institute_id' => $pocketMoney->institute_id,
            'created_by' => Auth::id(),
        ];
    }

    public function editInfo(Request $request)
    {
        $authId = Auth::id();
        $data = [];
        if ($request->account_no) {
            $data['account_no'] = $request->account_no;
        }
        if ($request->bank_branch_id) {
            $data['bank_branch_id'] = $request->bank_branch_id;
        }
        if ($request->status) {
            $data['status'] = $request->status;
        }
        
        if ($request->std_ids) {
            DB::beginTransaction();
            try {
                $pocketMoneyRows = PocketMoney::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->whereIn('std_id', $request->std_ids)->get()->keyBy('std_id');

                foreach ($request->std_ids as $stdId) {
                    if (isset($pocketMoneyRows[$stdId])) {
                        $data['updated_by'] = $authId;
                        $pocketMoneyRows[$stdId]->update($data);
                        $pocketMoney = $pocketMoneyRows[$stdId];
                    }else{
                        $data['std_id'] = $stdId;
                        $data['created_by'] = $authId;
                        $data['campus_id'] = $this->academicHelper->getCampus();
                        $data['institute_id'] = $this->academicHelper->getInstitute();
                        $pocketMoney = PocketMoney::create($data);
                    }
                    $historyData = $this->makePocketMoneyHistoryData($pocketMoney);
                    if ($request->account_no) {
                        PocketMoneyHistory::create($historyData+[
                            'action_param' => 'account_no'
                        ]);
                    }
                    if ($request->bank_branch_id) {
                        PocketMoneyHistory::create($historyData+[
                            'action_param' => 'bank_branch'
                        ]);
                    }
                    if ($request->status) {
                        PocketMoneyHistory::create($historyData+[
                            'action_param' => 'status'
                        ]);
                    }
                }

                DB::commit();
                return [
                    'status' => 1,
                    'msg' => 'Cadet Info updated successfully!'
                ];
            } catch (\Throwable $th) {
                DB::rollBack();
                return [
                    'status' => 0,
                    'msg' => $th->errorInfo[2]
                ];
            }
        } else {
            return [
                'status' => 0,
                'msg' => 'No Cadet\'s are selected!'
            ];
        }
    }

    public function pocketMoneyHistories($id)
    {
        $pocketMoney = PocketMoney::findOrFail($id);
        $student = StudentProfileView::with('singleUser')->where('std_id', $pocketMoney->std_id)->first();
        $pocketMoneyHistories = PocketMoneyHistory::with('bankBranch', 'user', 'student')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'pocket_money_id' => $id
        ])->latest()->get();

        return view('house::pocket-money.history-list', compact('pocketMoneyHistories', 'student'));
    }

    public function addBalance(Request $request)
    {
        $authId = Auth::id();
        $data = [];
        
        if ($request->std_ids) {
            DB::beginTransaction();
            try {
                $pocketMoneyRows = PocketMoney::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->whereIn('std_id', $request->std_ids)->get()->keyBy('std_id');

                foreach ($request->std_ids as $stdId) {
                    if (isset($pocketMoneyRows[$stdId])) {
                        if ($request->money_in) {
                            $data = [
                                'money_in' => $pocketMoneyRows[$stdId]->money_in + $request->money_in,
                                'last_allotment' => null,
                                'total_allotment' => null,
                                'last_expense' => null,
                                'total_expense' => null,
                            ];
                        }
                        if ($request->account_balance) {
                            $data['account_balance'] = $pocketMoneyRows[$stdId]->account_balance + $request->account_balance;
                        }
                        $data['updated_by'] = $authId;
                        $pocketMoneyRows[$stdId]->update($data);
                        $pocketMoney = $pocketMoneyRows[$stdId];
                    }else{
                        if ($request->money_in) {
                            $data['money_in'] = $request->money_in;
                        }
                        if ($request->account_balance) {
                            $data['account_balance'] = $request->account_balance;
                        }
                        $data['std_id'] = $stdId;
                        $data['created_by'] = $authId;
                        $data['campus_id'] = $this->academicHelper->getCampus();
                        $data['institute_id'] = $this->academicHelper->getInstitute();
                        $pocketMoney = PocketMoney::create($data);
                    }
                    $historyData = $this->makePocketMoneyHistoryData($pocketMoney);
                    if ($request->account_balance) {
                        PocketMoneyHistory::create($historyData+[
                            'new_account_balance' => $request->account_balance,
                            'action_param' => 'account_balance'
                        ]);
                    }
                    if ($request->money_in) {
                        PocketMoneyHistory::create($historyData+[
                            'new_money_in' => $request->money_in,
                            'action_param' => 'money_in'
                        ]);
                    }
                }

                DB::commit();
                return [
                    'status' => 1,
                    'msg' => 'New balance added successfully!'
                ];
            } catch (\Throwable $th) {
                DB::rollBack();
                return [
                    'status' => 0,
                    'msg' => $th->errorInfo[2]
                ];
            }
        } else {
            return [
                'status' => 0,
                'msg' => 'No Cadet\'s are selected!'
            ];
        }
    }

    public function allotMoney(Request $request)
    {
        $authId = Auth::id();
        $data = [];

        
        if ($request->std_ids) {
            $students = StudentProfileView::with('singleUser')->whereIn('std_id', $request->std_ids)->get()->keyBy('std_id');
            DB::beginTransaction();
            try {
                $pocketMoneyRows = PocketMoney::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->whereIn('std_id', $request->std_ids)->get()->keyBy('std_id');

                foreach ($request->std_ids as $stdId) {
                    if (isset($pocketMoneyRows[$stdId])) {
                        $totalBalance = $pocketMoneyRows[$stdId]->account_balance + $pocketMoneyRows[$stdId]->money_in;
                        $totalAllotment = $request->allotment_amount + $pocketMoneyRows[$stdId]->total_allotment;
                        if ($totalBalance >= $totalAllotment) {
                            $pocketMoneyRows[$stdId]->update([
                                'updated_by' => $authId,
                                'last_allotment' => $request->allotment_amount,
                                'total_allotment' => $totalAllotment
                            ]);
                            $historyData = $this->makePocketMoneyHistoryData($pocketMoneyRows[$stdId]);
                            PocketMoneyHistory::create($historyData+[
                                'new_allotment' => $request->allotment_amount,
                                'action_param' => 'allotment'
                            ]);
                        } else{
                            DB::rollBack();
                            return [
                                'status' => 0,
                                'msg' => 'Allotment Balance exceeded for '.$students[$stdId]->singleUser->username.'!'
                            ];
                        }
                    }else{
                        DB::rollBack();
                        return [
                            'status' => 0,
                            'msg' => 'No Balance in '.$students[$stdId]->singleUser->username.'\'s account!'
                        ];
                    }
                }

                DB::commit();
                return [
                    'status' => 1,
                    'msg' => 'Money alloted successfully!'
                ];
            } catch (\Throwable $th) {
                DB::rollBack();
                return [
                    'status' => 0,
                    'msg' => $th->errorInfo[2]
                ];
            }
        } else {
            return [
                'status' => 0,
                'msg' => 'No Cadet\'s are selected!'
            ];
        }
    }

    public function expense(Request $request)
    {
        $authId = Auth::id();
        $data = [];
        
        if ($request->std_ids) {
            $students = StudentProfileView::with('singleUser')->whereIn('std_id', $request->std_ids)->get()->keyBy('std_id');
            DB::beginTransaction();
            try {
                $pocketMoneyRows = PocketMoney::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->whereIn('std_id', $request->std_ids)->get()->keyBy('std_id');

                foreach ($request->std_ids as $stdId) {
                    if (isset($pocketMoneyRows[$stdId])) {
                        if ($pocketMoneyRows[$stdId]->total_allotment) {
                            $totalBalance = $pocketMoneyRows[$stdId]->account_balance + $pocketMoneyRows[$stdId]->money_in;
                            $totalExpense = $request->expense_amount + $pocketMoneyRows[$stdId]->total_expense;
                            if ($totalBalance >= $request->expense_amount) {
                                $data = [
                                    'updated_by' => $authId,
                                    'last_expense' => $request->expense_amount,
                                    'total_expense' => $totalExpense
                                ];
                                if ($pocketMoneyRows[$stdId]->money_in >= $request->expense_amount) {
                                    $data['money_in'] = $pocketMoneyRows[$stdId]->money_in - $request->expense_amount;
                                }else{
                                    $data['account_balance'] = $pocketMoneyRows[$stdId]->account_balance - ($request->expense_amount-$pocketMoneyRows[$stdId]->money_in);
                                    $data['money_in'] = 0;
                                }
                                $pocketMoneyRows[$stdId]->update($data);
                                $historyData = $this->makePocketMoneyHistoryData($pocketMoneyRows[$stdId]);
                                PocketMoneyHistory::create($historyData+[
                                    'new_expense' => $request->expense_amount,
                                    'action_param' => 'expense'
                                ]);
                            } else{
                                DB::rollBack();
                                return [
                                    'status' => 0,
                                    'msg' => 'Not have enough balance at '.$students[$stdId]->singleUser->username.'\'s account!'
                                ];
                            }
                        }else{
                            DB::rollBack();
                            return [
                                'status' => 0,
                                'msg' => 'Allot money first at '.$students[$stdId]->singleUser->username.'\'s account!'
                            ];
                        }
                    }else{
                        DB::rollBack();
                        return [
                            'status' => 0,
                            'msg' => 'No balance at '.$students[$stdId]->singleUser->username.'\'s account!'
                        ];
                    }
                }

                DB::commit();
                return [
                    'status' => 1,
                    'msg' => 'Money deducted successfully!'
                ];
            } catch (\Throwable $th) {
                DB::rollBack();
                return [
                    'status' => 0,
                    'msg' => $th->errorInfo[2]
                ];
            }
        } else {
            return [
                'status' => 0,
                'msg' => 'No Cadet\'s are selected!'
            ];
        }
    }
}
