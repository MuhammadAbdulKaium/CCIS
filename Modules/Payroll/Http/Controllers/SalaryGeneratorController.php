<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Payroll\Entities\BankDetails;
use Modules\Payroll\Entities\SalaryGenerateHistoryList;
use Modules\Payroll\Entities\SalaryGenerateList;
use Modules\Payroll\Entities\SalaryGenerateListDetails;
use Modules\Payroll\Entities\SalaryHead;
use Modules\Payroll\Entities\SalaryProcessHistoryList;
use Modules\Payroll\Entities\SalaryScale;

class SalaryGeneratorController extends Controller
{
    private $academicHelper;
    private $department;

    public function __construct(AcademicHelper $academicHelper, EmployeeDepartment $department)
    {
        $this->academicHelper = $academicHelper;
        $this->department = $department;
    }
    public function index()
    {
        $year = Carbon::now()->format('Y');
        $allDepartments = $this->department->get();
        $allDesignation = EmployeeDesignation::all();
        $bankName = BankDetails::where(['campus_id'=>$this->academicHelper->getCampus(),'institute_id'=>$this->academicHelper->getInstitute()])->get();
        $salaryScale = SalaryScale::with('gradeName')->where(['institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()])->get();
        $salaryDeductionHead = SalaryHead::where(['type'=>1,'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()])->get();
        return view('payroll::pages.salaryGenerate.salary-generate',compact('year','allDepartments','allDesignation','bankName','salaryScale','salaryDeductionHead'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($request->checkbox) {
            if ($request->generate_narration == null) {
                return ['status' => 0, 'message' => 'Sorry Please Give a Narration for Generate Salary'];
            } else {
                    $d=cal_days_in_month(CAL_GREGORIAN,$request->month_name,$request->year);
                    $existEmp=[];
                    $noAssignEmp=[];
                    $narration = new SalaryGenerateHistoryList();
                    $narration->narration = $request->generate_narration;
                    $narration->created_by = Auth::user()->id;
                    $narration->start_date = $request->start_date ? $request->start_date : null;
                    $narration->end_date = $request->end_date ? $request->end_date : null;
                    $narration->month = $request->month_name;
                    $narration->year = $request->year;
                    $narration->save();
                    foreach ($request->checkbox as $checks) {
                        for ($i = 0; $i < count($request->emp_id); $i++) {
                            if ($checks == $request->emp_id[$i]) {
                                 if(!isset($request->addition_head[$checks])){
                                     array_push($noAssignEmp,$checks);
                                     DB::rollback();
                                    return ['status'=>2,'message'=>'Not Assigned','emp_id'=>$checks];
                                }
                                 else {
                                     if($request->start_date!=null &&  $request->end_date!=null)
                                     {
                                         $salaryGenerate = SalaryGenerateList::where(['emp_id' => $request->emp_id[$i], 'start_date' => $request->start_date, 'end_date' => $request->end_date])
                                             ->first();
                                     }
                                      if($request->start_date==null ||  $request->end_date==null)
                                     {
                                         $salaryGenerate = SalaryGenerateList::where(['emp_id' => $request->emp_id[$i], 'month' => $request->month_name, 'year' => $request->year,'start_date'=>null,'end_date'=>null])
                                             ->first();
                                     }

                                     if ($salaryGenerate) {
                                         array_push($existEmp, $request->emp_id[$i]);
                                     } else {
                                         $salaryGenerate = new SalaryGenerateList();
                                         $salaryGenerate->emp_id = $request->emp_id[$i];
                                         $salaryGenerate->generate_list_id = $narration->id;
                                         $salaryGenerate->scale_id = $request->scale[$i];
                                         $salaryGenerate->total_deduction_amount = $request->deductTotal[$i];
                                         $salaryGenerate->total_addition_amount = $request->additionTotal[$i];
                                         $salaryGenerate->total_payable = $request->net_pay[$i];
                                         $salaryGenerate->year = $request->year;
                                         $salaryGenerate->start_date = $request->start_date ? $request->start_date : null;
                                         $salaryGenerate->end_date = $request->end_date ? $request->end_date : null;
                                         $salaryGenerate->durations = $request->salary_days ? $request->salary_days : null;
                                         $salaryGenerate->processed = 0;
                                         $salaryGenerate->month = $request->month_name;
                                         $salaryGenerate->created_by = Auth::user()->id;
                                         $salaryGenerate->save();
                                         foreach ($request->addition_head[$checks] as $key => $amount) {
                                             $salaryGenerateList = new SalaryGenerateListDetails();
                                             $salaryGenerateList->generate_id = $salaryGenerate->id;
                                             $salaryGenerateList->head_id = $key;
                                             $salaryGenerateList->amount = $amount;
                                             $salaryGenerateList->created_by = Auth::user()->id;
                                             $salaryGenerateList->save();
                                         }
                                         foreach ($request->deduct_head[$checks] as $key => $amount) {
                                             $salaryGenerateList = new SalaryGenerateListDetails();
                                             $salaryGenerateList->generate_id = $salaryGenerate->id;
                                             $salaryGenerateList->head_id = $key;
                                             $salaryGenerateList->amount = $amount;
                                             $salaryGenerateList->created_by = Auth::user()->id;
                                             $salaryGenerateList->save();
                                         }
                                     }
                                 }
                            }
                        }
                    }
                    if(count($existEmp)>0)
                    {
                        DB::rollback();
                        return ['status'=>3,'message'=>'Generate Successfully','emp_id'=>$existEmp];
                    }
                    else{
                        DB::commit();
                        return ['status'=>1,'message'=>'Generate Successfully'];
                    }

            }
        }
        else{
            return ['status'=>0,'message'=>'Sorry Please Enable checkbox'];
        }
    }
    public function processStore(Request $request)
    {
        if($request->checkbox) {
            if ($request->process_sheet_id == null) {
                return ['status' => 0, 'message' => 'Sorry Please Give a Sheet ID for Process Salary'];
            } else {
                $existEmp=[];
                $sheetId = new SalaryProcessHistoryList();
                $sheetId->sheet_id = $request->process_sheet_id;
                $sheetId->remarks = $request->remarks;
                $sheetId->created_by = Auth::user()->id;
                $sheetId->start_date = $request->start_date ? $request->start_date : null;
                $sheetId->end_date = $request->end_date ? $request->end_date : null;
                $sheetId->month = $request->month_name;
                $sheetId->year = $request->year;
                $sheetId->save();
                $sheetNo=$sheetId->id;
                foreach ($request->checkbox as $checks) {
                    for ($i = 0; $i < count($request->emp_id); $i++) {
                        if ($checks == $request->emp_id[$i]) {
                            if($request->start_date!=null &&  $request->end_date!=null)
                            {
                                $salaryProcess = SalaryGenerateList::where(['emp_id' => $request->emp_id[$i], 'start_date' => $request->start_date, 'end_date' => $request->end_date])
                                    ->first();
                            }
                            if($request->start_date==null ||  $request->end_date==null)
                            {
                                $salaryProcess = SalaryGenerateList::where(['emp_id' => $request->emp_id[$i], 'month' => $request->month_name, 'year' => $request->year,'start_date'=>null,'end_date'=>null])
                                    ->first();
                            }
                            if(!$salaryProcess)
                            {
                                echo "Not Generated";
                                array_push($existEmp,$request->emp_id[$i]);
                            }
                            if($salaryProcess)
                            {
                                if($salaryProcess->processed==0){
                                    $data=array();
                                    $data['processed']=1;
                                    $data['sheet_id']=$sheetNo;
                                    $salaryProcess->update($data);
                                }
                            }
                        }
                    }
                }
                if(count($existEmp)>0)
                {
                    return ['status'=>5,'message'=>'Not generated,Generate First','emp_id'=>$existEmp];
                }
                else{
                    return ['status'=>4,'message'=>'Process Successfully'];
                }
            }
        }
        else{
            return ['status'=>0,'message'=>'Sorry Please Enable checkbox'];
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payroll::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
