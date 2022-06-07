<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Payroll\Entities\BankDetails;
use Modules\Payroll\Entities\SalaryDeduct;
use Modules\Payroll\Entities\SalaryHead;
use Modules\Payroll\Entities\SalaryScale;

class SalaryDeductionController extends Controller
{
    private $academicHelper;
    private $department;

    public function __construct(AcademicHelper $academicHelper, EmployeeDepartment $department)
    {
        $this->academicHelper = $academicHelper;
        $this->department = $department;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $allDepartments = $this->department->get();
        $allDesignation = EmployeeDesignation::all();
        $bankName = BankDetails::where(['campus_id'=>$this->academicHelper->getCampus(),'institute_id'=>$this->academicHelper->getInstitute()])->get();
        $salaryScale = SalaryScale::with('gradeName')->where(['institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()])->get();
        $salaryDeductionHead = SalaryHead::where(['type'=>1,'institute_id'=>$this->academicHelper->getInstitute(),
                'campus_id'=>$this->academicHelper->getCampus()])->get();
        return view('payroll::pages.salaryDeduction.salary-deduction',compact('salaryDeductionHead','bankName','salaryScale','allDepartments','allDesignation'));

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
        if($request->checkbox)
        {
            DB::beginTransaction();
            try {
                foreach ($request->checkbox as $checks) {
                    for ($i = 0; $i < count($request->emp_id); $i++) {
                        if ($checks == $request->emp_id[$i]) {
                            $salaryDeductCheck = SalaryDeduct::where('emp_id',$request->emp_id[$i])
                                ->where('deduct_head_id',$request->head_id)
                                ->where('month_name',$request->month_name)
                                ->first();
                            if($salaryDeductCheck){
                                $data =[];
                                $data['amount']=$request->amount[$i];
                                $data['remarks']=$request->remarks[$i];
                                $salaryDeductCheck->update($data);
                            }
                            else{
                                $salaryDeduct = new SalaryDeduct();
                                $salaryDeduct->emp_id = $request->emp_id[$i];
                                $salaryDeduct->scale_id = $request->scale_id[$i];
                                $salaryDeduct->deduct_head_id = $request->head_id;
                                $salaryDeduct->amount = $request->amount[$i];
                                $salaryDeduct->remarks = $request->remarks[$i];
                                $salaryDeduct->month_name = $request->month_name;
                                $salaryDeduct->year = Carbon::now()->format('Y');
                                $salaryDeduct->created_by = Auth::user()->id;
                                $salaryDeduct->save();
                            }
                        }
                    }
                }
                DB::commit();
                return ['status'=>1,'message'=>'Deduct Successfully'];
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return $e;
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
