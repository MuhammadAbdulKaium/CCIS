<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;
use Modules\Employee\Entities\EmployeeAward;
use Modules\Employee\Entities\EmployeeInformation;

class EmployeeAwardController extends Controller
{
    
    use UserAccessHelper;
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);
        $employeeInfo = EmployeeInformation::with('singleUser')->findOrFail($id);
        $awards = EmployeeAward::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'employee_id' => $id
        ])->get();
        
        if (!isCurrentCampus($employeeInfo)) {
            return abort(404);
        }

        return view('employee::pages.profile.award', compact('pageAccessData','employeeInfo','awards'))->with('page', 'award');
    }

    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $employees = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => 1
        ])->get();
        return view('employee::pages.profile.modal.create-award', compact('employeeInfo', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'awarded_on' => 'required|date',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:500'
        ]);

        if ($request->awarded_by_employee || $request->awarded_by) {
            $attachmentName = null;
            if ($request->file('attachment')) {
                $image = $request->file('attachment');
                $attachmentName = 'award-' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/assets/Employee/awards/', $attachmentName);
            }
            EmployeeAward::create([
                "employee_id" => $request->employee_id,
                "name" => $request->name,
                "awarded_on" => Carbon::parse($request->awarded_on),
                "awarded_by_employee" => $request->awarded_by_employee,
                "awarded_by_name" => $request->awarded_by,
                "description" => $request->description,
                "attachment" => $attachmentName,
                "remarks" => $request->remarks,
                "created_by" => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);
        }else{
            Session::flash('errorMessage', 'Please Mention Awarded By!');
            return redirect()->back();
        }

        Session::flash('success', 'Successfully created award!');
        return redirect()->back();
    }

    public function show($id)
    {
        return view('employee::show');
    }

    public function edit($id)
    {
        $award = EmployeeAward::findOrFail($id);
        $employeeInfo = EmployeeInformation::findOrFail($award->employee_id);
        $employees = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => 1
        ])->get();
        return view('employee::pages.profile.modal.edit-award', compact('award', 'employeeInfo', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $award = EmployeeAward::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'awarded_on' => 'required|date',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:500'
        ]);

        if ($request->awarded_by_employee || $request->awarded_by) {
            $attachmentName = $award->attachment;
            if ($request->file('attachment')) {
                if ($attachmentName){
                    $file_path = public_path().'/assets/Employee/awards/'.$attachmentName;
                    unlink($file_path);
                }
                $image = $request->file('attachment');
                $attachmentName = 'award-' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/assets/Employee/awards/', $attachmentName);
            }
            $award->update([
                "name" => $request->name,
                "awarded_on" => Carbon::parse($request->awarded_on),
                "awarded_by_employee" => $request->awarded_by_employee,
                "awarded_by_name" => $request->awarded_by,
                "description" => $request->description,
                "attachment" => $attachmentName,
                "remarks" => $request->remarks,
                "updated_by" => Auth::id()
            ]);
        }else{
            Session::flash('errorMessage', 'Please Mention Awarded By!');
            return redirect()->back();
        }

        Session::flash('success', 'Successfully updated award!');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $award = EmployeeAward::findOrFail($id);
        $attachmentName = $award->attachment;
        if ($attachmentName){
            $file_path = public_path().'/assets/Employee/awards/'.$attachmentName;
            unlink($file_path);
        }
        $award->delete();

        Session::flash('message', 'Employee award deleted successfully.');
        return redirect()->back();
    }
}
