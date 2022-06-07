<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Payroll\Entities\BankDetails;
use Modules\Payroll\Entities\EmployeeSalaryAssignHead;
use Modules\Payroll\Entities\SalaryAssign;
use Modules\Payroll\Entities\SalaryAssignHistory;
use Modules\Payroll\Entities\SalaryGrade;
use Modules\Payroll\Entities\SalaryHead;
use Modules\Payroll\Entities\SalaryScale;
use DB;
use Modules\Payroll\Entities\salaryStructure;
use Modules\Payroll\Entities\StructureHead;


class SalaryAssignController extends Controller
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
        $allDepartments = $this->department->get();
        $allDesignation = EmployeeDesignation::all();
        $bankName = BankDetails::where(['campus_id'=>$this->academicHelper->getCampus(),'institute_id'=>$this->academicHelper->getInstitute()])->get();
        $salaryScale = SalaryScale::with('gradeName')->where(['institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()])->get();
        return view('payroll::pages.salaryAssign.salary-assign',compact('bankName','salaryScale','allDepartments','allDesignation'));
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
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            for ($i = 0; $i < count($request->emp_id); $i++) {
                $salaryAssignData = SalaryAssign::where('emp_id', $request->emp_id[$i])->first();
                if ($salaryAssignData) {
                    $salaryAssignDataUpdate = $salaryAssignData->update([
                        'salary_scale' => $request->scale_id[$i],
                        'bank_details_id' => $request->bankId[$i],
                        'bank_branch_details_id' => $request->branchId[$i],
                        'bank_acc_number' => $request->bank_acc_no[$i],
                        'salary_amount' => $request->amount[$i],
                        'updated_by' => Auth::user()->id
                    ]);
                    if ($salaryAssignDataUpdate) {
                        $salaryAssignHistory = new SalaryAssignHistory();
                        $salaryAssignHistory->emp_id = $request->emp_id[$i];
                        $salaryAssignHistory->salary_scale = $request->scale_id[$i];
                        $salaryAssignHistory->bank_details_id = $request->bankId[$i];
                        $salaryAssignHistory->bank_branch_details_id = $request->branchId[$i];
                        $salaryAssignHistory->bank_acc_number = $request->bank_acc_no[$i];
                        $salaryAssignHistory->salary_amount = $request->amount[$i];
                        $salaryAssignHistory->created_by = Auth::user()->id;
                        $salaryAssignHistoryData = $salaryAssignHistory->save();
                    }
                } else {
                    $salaryAssign = new SalaryAssign();
                    $salaryAssign->emp_id = $request->emp_id[$i];
                    $salaryAssign->salary_scale = $request->scale_id[$i];
                    $salaryAssign->bank_details_id = $request->bankId[$i];
                    $salaryAssign->bank_branch_details_id = $request->branchId[$i];
                    $salaryAssign->bank_acc_number = $request->bank_acc_no[$i];
                    $salaryAssign->salary_amount = $request->amount[$i];
                    $salaryAssign->created_by = Auth::user()->id;
                    $salaryAssignData = $salaryAssign->save();
                    if ($salaryAssignData) {
                        $salaryAssignHistory = new SalaryAssignHistory();
                        $salaryAssignHistory->emp_id = $request->emp_id[$i];
                        $salaryAssignHistory->salary_scale = $request->scale_id[$i];
                        $salaryAssignHistory->bank_details_id = $request->bankId[$i];
                        $salaryAssignHistory->bank_branch_details_id = $request->branchId[$i];
                        $salaryAssignHistory->bank_acc_number = $request->bank_acc_no[$i];
                        $salaryAssignHistory->salary_amount = $request->amount[$i];
                        $salaryAssignHistory->created_by = Auth::user()->id;
                        $salaryAssignHistoryData = $salaryAssignHistory->save();
                    }
                }
            }
            DB::commit();
            return (['status' => 1, 'message' => 'Successfully Stored Sallary Assign']);
        }
        catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
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
    public function editDetails($id)
    {
        $salaryGrades=SalaryGrade::join('cadet_salary_scale','pay_salary_grade.id','cadet_salary_scale.grade_id')->
        where([
            'pay_salary_grade.campus_id' => $this->academicHelper->getCampus(),
            'pay_salary_grade.institute_id' => $this->academicHelper->getInstitute()
        ])
            ->select('pay_salary_grade.grade_name','cadet_salary_scale.*')->get();
        $salaryHeads=SalaryHead:: where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        return $employeeSalaryDetails=SalaryAssign::where('emp_id','=',$id)->first();
        return view('payroll::pages.salaryAssign.employee-assign-update',compact('employeeSalaryDetails','salaryGrades','salaryHeads'));
    }
    public function addDetails($id)
    {
        $salaryGrades=SalaryGrade::join('cadet_salary_scale','pay_salary_grade.id','cadet_salary_scale.grade_id')->
        where([
            'pay_salary_grade.campus_id' => $this->academicHelper->getCampus(),
            'pay_salary_grade.institute_id' => $this->academicHelper->getInstitute()
        ])
            ->select('pay_salary_grade.grade_name','cadet_salary_scale.*')->get();
        $salaryHeads=SalaryHead:: where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        return $employeeSalaryDetails=SalaryAssign::where('emp_id','=',$id)->first();
        return view('payroll::pages.salaryAssign.employee-assign-add',compact('employeeSalaryDetails','salaryGrades','salaryHeads'));
    }

    public function storeDetails(Request $request)
    {
        $salaryScaleDetails = \Illuminate\Support\Facades\DB::table('cadet_salary_structure')->where('cadet_salary_structure.id',$request->data['salaryGrade'])->first();
        $totalSalary= \Illuminate\Support\Facades\DB::table('cadet_salary_structure_head_details')
            ->where('cadet_salary_structure_head_details.structure_id',$request->data['salaryGrade'])->sum('amount');
        $employeeDeatials = EmployeeInformation::where('user_id',$request->data['empId'])->first();
        $employeeSalaryAssign =new SalaryAssign;
        $employeeSalaryAssign->emp_id = $request->data['empId'];
        $employeeSalaryAssign->salary_scale = $salaryScaleDetails->scale_id;
        $employeeSalaryAssign->salary_grade = $salaryScaleDetails->grade_id;
        $employeeSalaryAssign->salary_amount = $totalSalary;
        $employeeSalaryAssign->bank_details = $request->data['bankDetails'];
        $employeeSalaryAssign->department_id = $employeeDeatials->department;
        $employeeSalaryAssign->designation_id = $employeeDeatials->designation;
        $employeeSalaryAssign->created_by = Auth::user()->id;
        $assignStore = $employeeSalaryAssign->save();

        if($assignStore)
        {
            $assignHeads = StructureHead::where('structure_id',$request->data['salaryGrade'])->get();
            foreach ($assignHeads as $heads)
            {
                $assignSalaryHead = new EmployeeSalaryAssignHead();
                $assignSalaryHead->structure_id = $request->data['salaryGrade'];
                $assignSalaryHead->emp_id = $request->data['empId'];
                $assignSalaryHead->head_id = $heads->head_id;
                $assignSalaryHead->amount = $heads->amount;
                $assignSalaryHead->created_by = Auth::user()->id;
                $headStore=$assignSalaryHead->save();

            }
            if($headStore)
            {
                return ['status'=>true, 'emp_id'=>$request->data['empId'], 'msg'=>'Employee Salary Added'];
            }
            else{
                // return success msg
                return ['status'=>false, 'msg'=>'Unable to Added Employee Salary'];
            }
        }
    }

    function findScaleDetails($id)
    {
        return $salaryDetails = SalaryAssign::where('emp_id',$id)->first();
    }

    function findScaleHeadDetails($id)
    {
        return $assignHead = EmployeeSalaryAssignHead::where('emp_id',$id)->first();
    }
}