<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeSpecialDuty;

class EmployeeSpecialDutyController extends Controller
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
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $specialDuties = EmployeeSpecialDuty::with('singleInstitute')->where([
            'employee_id' => $id,
        ])->latest()->get();
        return view('employee::pages.profile.special-duty', compact('employeeInfo','specialDuties','pageAccessData'))->with('page', 'special-duty');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);

        return view('employee::pages.modals.special-duty-create', compact('employeeInfo'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'start_date' => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        if ($validator->passes()) {
            
            DB::beginTransaction();
            try {
                $attachmentName = null;
                if ($request->hasFile('attachment')) {
                    $attachmentName = 'Special-duty-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/SpecialDuty/'), $attachmentName);
                }
                EmployeeSpecialDuty::create($request->except('attachment') + [
                    'attachment' => $attachmentName,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee Special-duty added successfully.');
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
        $duty = EmployeeSpecialDuty::findOrFail($id);
       
        return view('employee::pages.modals.special-duty-update', compact('duty'));
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
            'description' => 'required',
            'start_date' => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        $duty = EmployeeSpecialDuty::findOrFail($id);
        if ($validator->passes()) {
            
            DB::beginTransaction();
            try {
                $attachmentName = $duty->attachment;
                if($request->hasFile('attachment')){
                    if ($attachmentName){
                        $file_path = public_path().'/assets/Employee/SpecialDuty/'.$attachmentName;
                        unlink($file_path);
                    }
                    $attachmentName = 'Special-duty-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/SpecialDuty/'), $attachmentName);
                }
    
                $duty->update($request->except('attachment', 'updated_by') + [
                    'attachment' => $attachmentName,
                    'updated_by' => Auth::id(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee Special Duty updated successfully.');
                return redirect()->back();
               
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Data Updated Fail');
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
        $duty = EmployeeSpecialDuty::findOrFail($id);
        $attachmentName = $duty->attachment;
        if ($attachmentName){
            $file_path = public_path().'/assets/Employee/SpecialDuty/'.$attachmentName;
            unlink($file_path);
        }
        $duty->delete();

        Session::flash('message', 'Employee Special Duty deleted successfully.');
        return redirect()->back();
    }
}
