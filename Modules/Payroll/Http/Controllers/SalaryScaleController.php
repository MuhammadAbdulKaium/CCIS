<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Payroll\Entities\SalaryGrade;
use Modules\Payroll\Entities\SalaryScale;
use Illuminate\Support\Facades\Session;

class SalaryScaleController extends Controller
{
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('payroll::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $salaryGrade = SalaryGrade::where(['institute_id' => $this->academicHelper->getInstitute()],
            ['campus_id' => $this->academicHelper->getCampus()])->get();
        return view('payroll::pages.salaryScale.create', compact('salaryGrade'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'scale_name' => ['required'],
//            'scale_name' => ['required',
//                Rule::unique('salary_scales')->where(function($query) {
//                    $query->where('institute_id','=',$this->academicHelper->getInstitute());})],
        ]);
        if($validator->passes()) {
            DB::beginTransaction();
            try {
                $salaryScale = new SalaryScale();
                $salaryScale->scale_name = $request->scale_name;
                $salaryScale->grade_id = $request->grade_id;
                $salaryScale->minimum_amt = $request->minimum_amount;
                $salaryScale->maximum_amt = $request->maximum_amount;
                $salaryScale->campus_id = $this->academicHelper->getCampus();
                $salaryScale->institute_id = $this->academicHelper->getInstitute();
                $salaryScale->created_by = Auth::user()->id;
                $salaryScaleStore = $salaryScale->save();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                Session::flash('error', $e->getMessage());
                return redirect()->back();
            }
            DB::commit();
            Session::flash('success', 'Salary Scale created');
            return redirect()->back();
        }
        else
        {
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
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
        $salaryGrades = SalaryGrade::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $salaryScale=SalaryScale::where('id','=',$id)->first();
        return view('payroll::pages.salaryScale.edit',compact('salaryScale','salaryGrades'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $salaryScale=SalaryScale::where('id','=',$request->id)->first();
        DB::beginTransaction();
        try {
        $salaryScaleUpdate = $salaryScale->update(['scale_name'=>$request->scale_name,
            'grade_id'=>$request->grade_id,
            'minimum_amt'=>$request->minimum_amt,
            'maximum_amt'=>$request->maximum_amt,
            'created_by'=>Auth::user()->id
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
        DB::commit();
        Session::flash('success', 'Salary Scale Updated');
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
