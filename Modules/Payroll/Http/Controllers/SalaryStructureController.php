<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Payroll\Entities\SalaryGrade;
use Modules\Payroll\Entities\SalaryHead;
use Modules\Payroll\Entities\SalaryScale;
use Modules\Payroll\Entities\salaryStructure;
use Modules\Payroll\Entities\SalaryStructureHistory;
use Modules\Payroll\Entities\StructureHead;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SalaryStructureController extends Controller
{
    private $academicHelper;
    private $type = array(
        0 => "Addition",
        1 => "Deduction"
    );

    private $calculation = array(
        0 => "Basic",
        1 => "Gross"
    );

    private $variable = array(
        0 => "Fixed",
        1 => "Variable",
        2 => "Percentage"
    );

    private $placement = array(
        1 => "Gross",
        2 => "Other",
        3 => "Extra",
        4 => "N/A"
    );

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function historyStructure($id)
    {
        $SalaryStructuresHistory=SalaryStructureHistory::with('headName','userName')->where('salary_scale_id',$id)->get();
        return view('payroll::pages.salaryStructure.history', compact('SalaryStructuresHistory'));

    }
    public function index()
    {
        $SalaryStructures = salaryStructure::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get()->groupBy('salary_scale_id');
        $salaryGrades = SalaryScale::with('gradeName')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $salaryHeads = SalaryHead:: where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        return view('payroll::pages.salaryStructure.salary-structure', compact('SalaryStructures', 'salaryGrades', 'salaryHeads'));
    }
    public function updateStructure(Request $request)
    {
        $stored_structure_data = SalaryStructure::where('salary_scale_id',$request->structure_id)->pluck('salary_head_id')->toArray();
        $requested_structure_data = [];
        DB::beginTransaction();
        try {
        for($i=0;$i<count($request->head_id);$i++)
        {
            array_push($requested_structure_data,(int)$request->head_id[$i]);
            $structures= SalaryStructure::where(['salary_scale_id'=>$request->structure_id,'salary_head_id'=>$request->head_id[$i]])->first();
            // Updates old data
            if($structures)
            {
                $data = array();
                $data['amount'] = $request->amount[$i];
                $data['min_amount'] = $request->minimum_amt[$i];
                $data['max_amount'] = $request->maximum_amt[$i];
                $data['remarks'] = $request->remarks[$i];
                $structures->update($data);
            }
            // Add New Records
            else{
                $scale_structure = new SalaryStructure();
                $scale_structure->salary_head_id = $request->head_id[$i];
                $scale_structure->salary_scale_id = $request->structure_id;
                $scale_structure->amount = $request->amount[$i];
                $scale_structure->min_amount = $request->minimum_amt[$i];
                $scale_structure->max_amount = $request->maximum_amt[$i];
                $scale_structure->remarks = $request->remarks[$i];
                $scale_structure->created_by = Auth::user()->id;
                $scale_structure->campus_id = $this->academicHelper->getCampus();
                $scale_structure->institute_id = $this->academicHelper->getInstitute();
                $scale_structure_store = $scale_structure->save();
                if ($scale_structure_store) {
                    $scale_structure_history = new SalaryStructureHistory();
                    $scale_structure_history->salary_head_id = $request->head_id[$i];
                    $scale_structure_history->salary_scale_id = $request->structure_id;
                    $scale_structure_history->amount = $request->amount[$i];
                    $scale_structure_history->min_amount = $request->minimum_amt[$i];
                    $scale_structure_history->max_amount = $request->maximum_amt[$i];
                    $scale_structure_history->remarks = $request->remarks[$i];
                    $scale_structure_history->created_by = Auth::user()->id;
                    $scale_structure_history->campus_id = $this->academicHelper->getCampus();
                    $scale_structure_history->institute_id = $this->academicHelper->getInstitute();
                    $scale_structure_history->save();
                }
            }
        }
        $removediff = array_diff($stored_structure_data,$requested_structure_data); // Remove Ids from DB
        foreach ($removediff as $remData)
        {
            $structuresDet= SalaryStructure::where(['salary_scale_id'=>$request->structure_id,'salary_head_id'=>$remData])->first();
            $structuresDet->delete();

        }
        DB::commit();
        return ['status'=>1,'message'=>'Update and Delete Structure'];
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('warning', 'Error! Error creating Structure.');
            return redirect()->back();
        }
    }

    public function editStructure($id)
    {
        $salaryStructure = SalaryStructure::with('headName')->where('salary_scale_id',$id)->get();
        $salaryStructureId = SalaryStructure::with('headName')->where('salary_scale_id',$id)->pluck('salary_head_id');
        return [
            'salaryStructureIds'=>$salaryStructureId,
            'view'=>view('payroll::pages.salaryStructure.salary-structure-edit-result', compact('salaryStructure','id'))->render()
        ];
    }
    public function getHeadStructure(Request $request)
    {
        $salaryHeads = SalaryHead:: where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get()->keyBy('id');
        $checkedHead = $request->head_id;
        $checkedScale = json_encode($request->structure_id);
        return view('payroll::pages.salaryStructure.salary-structure-view-result', compact('salaryHeads', 'checkedHead', 'checkedScale'));

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
        //
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

    public function generate(Request $request)
    {
        $data = [
            $this->type,
            $this->calculation,
            $this->placement,
            $this->variable
        ];
        $scaleData = $request->selectedScaleData;
        $headData = $request->selectedHeadData;
        $salaryHeads = SalaryHead:: where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        return view('payroll::pages.salaryStructure.salary-head-list', compact('headData', 'scaleData', 'salaryHeads', 'data'));
    }
    public function addStructure($id)
    {
        return $id;
    }

    public function storeSalaryStructure(Request $request)
    {
        DB::beginTransaction();
        try {
            $scale_id = json_decode($request->scale_id);
            foreach ($scale_id as $scale) {
                for ($i = 0; $i < count($request->head_id); $i++) {
                    $scale_structure = new SalaryStructure();
                    $scale_structure->salary_head_id = $request->head_id[$i];
                    $scale_structure->salary_scale_id = $scale;
                    $scale_structure->amount = $request->amount[$i];
                    $scale_structure->min_amount = $request->minimum_amt[$i];
                    $scale_structure->max_amount = $request->maximum_amt[$i];
                    $scale_structure->remarks = $request->remarks[$i];
                    $scale_structure->created_by = Auth::user()->id;
                    $scale_structure->campus_id = $this->academicHelper->getCampus();
                    $scale_structure->institute_id = $this->academicHelper->getInstitute();
                    $scale_structure_store = $scale_structure->save();

                    if ($scale_structure_store) {
                        $scale_structure_history = new SalaryStructureHistory();
                        $scale_structure_history->salary_head_id = $request->head_id[$i];
                        $scale_structure_history->salary_scale_id = $scale;
                        $scale_structure_history->amount = $request->amount[$i];
                        $scale_structure_history->min_amount = $request->minimum_amt[$i];
                        $scale_structure_history->max_amount = $request->maximum_amt[$i];
                        $scale_structure_history->remarks = $request->remarks[$i];
                        $scale_structure_history->created_by = Auth::user()->id;
                        $scale_structure_history->campus_id = $this->academicHelper->getCampus();
                        $scale_structure_history->institute_id = $this->academicHelper->getInstitute();
                        $scale_structure_history->save();
                    }
                }
            }
            if ($scale_structure_store) {
                DB::commit();
                Session::flash('success', 'Success! New Structure Created Successfully.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
//            return $e;
            Session::flash('warning', 'Error! Error creating Structure.');
            return redirect()->back();
        }
    }
}