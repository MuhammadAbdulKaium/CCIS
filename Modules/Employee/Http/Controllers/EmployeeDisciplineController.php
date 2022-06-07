<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Employee\Entities\EmployeeDiscipline;
use Modules\Employee\Entities\EmployeeInformation;

class EmployeeDisciplineController extends Controller
{
    use UserAccessHelper;

    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id, Request $request)
    {
        // return $id; institute_alias
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $allDisciplines = EmployeeDiscipline::with('singlePunishmentBy','singleInstitute')->where([
            'employee_id' => $id,
            ])->get();
        return view('employee::pages.profile.discipline', compact('employeeInfo', 'allDisciplines', 'pageAccessData'))->with('page', 'discipline');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $allEmployee = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->select('id','user_id', 'title', 'first_name', 'last_name')->get();
        return view('employee::pages.modals.discipline-create', compact('employeeInfo', 'allEmployee'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'occurrence_date' => 'required',
            'place' => 'required',
            'description' => 'required',
            'punishment_category' => 'required',
            'punishment_date' => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        if ($validator->passes()) {

            DB::beginTransaction();
            try {
                $attachmentName = null;
                if ($request->hasFile('attachment')) {
                    $attachmentName = 'Discipline-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/Discipline/'), $attachmentName);
                }
                EmployeeDiscipline::create($request->except('attachment') + [
                    'attachment' => $attachmentName,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee Disciplinary added successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Data added Fail');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Fill the fields with valid data.');
            return redirect()->back();
        }

       
        // return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $discipline = EmployeeDiscipline::with('singlePunishmentBy')->findOrFail($id);
        $allEmployee = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->select('id','user_id', 'title', 'first_name', 'last_name')->get();
        return view('employee::pages.modals.discipline-update', compact('discipline', 'allEmployee'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'occurrence_date' => 'required',
            'place' => 'required',
            'description' => 'required',
            'punishment_category' => 'required',
            'punishment_date' => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        $employeeDiscipline =  EmployeeDiscipline::findOrFail($id);
        if ($validator->passes()) {

            DB::beginTransaction();
            try {
                $attachmentName = $employeeDiscipline->attachment;
                if($request->hasFile('attachment')){
                    if ($attachmentName){
                        $file_path = public_path().'/assets/Employee/Discipline/'.$attachmentName;
                        unlink($file_path);
                    }
                    $attachmentName = 'Discipline-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/Discipline/'), $attachmentName);
                }
    
                $employeeDiscipline->update($request->except('attachment', 'updated_by') + [
                    'attachment' => $attachmentName,
                    'updated_by' => Auth::id(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee Disciplinary updated successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Data updated Fail');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Fill the fields with valid data.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete($id)
    {
        $employeeDiscipline =  EmployeeDiscipline::findOrFail($id);
        $attachmentName = $employeeDiscipline->attachment;
        if ($attachmentName){
            $file_path = public_path().'/assets/Employee/Discipline/'.$attachmentName;
            unlink($file_path);
        }
        $employeeDiscipline->delete();
        Session::flash('message', 'Employee Disciplinary deleted successfully.');
        return redirect()->back();
    }
}
