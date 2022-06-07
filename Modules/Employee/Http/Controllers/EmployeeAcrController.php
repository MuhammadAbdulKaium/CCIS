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
use Modules\Employee\Entities\EmployeeAcr;
use Modules\Employee\Entities\EmployeeInformation;

class EmployeeAcrController extends Controller
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
        $pageAccessData = self::linkAccess($request, ['manualRoute' => "employee/manage"]);
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $employee_acrs = EmployeeAcr::with('employeeIoName', 'employeeHoName')->where([
            'employee_id' => $id,
        ])->get()->sortByDesc('year');
        return view('employee::pages.profile.acr', compact('employeeInfo', 'employee_acrs', 'pageAccessData'))->with('page', 'acr');
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

        return view('employee::pages.modals.acr-create', compact('employeeInfo', 'allEmployee'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required',
            'initiative_officer' => 'required',
            'higher_officer' => 'required',
            'io' => 'required',
            'ho' => 'required',
            'io_name' => 'required',
            'ho_name' => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $attachmentName = null;
                if ($request->hasFile('attachment')) {
                    $attachmentName = 'ACR-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/ACR/'), $attachmentName);
                }
                EmployeeAcr::create($request->except('attachment') + [
                    'attachment' => $attachmentName,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee ACR added successfully.');
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
        $acr = EmployeeAcr::findOrFail($id);
        $allEmployee = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->select('id','user_id', 'title', 'first_name', 'last_name')->get();

        return view('employee::pages.modals.acr-update', compact('acr', 'allEmployee'));
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
            'year' => 'required',
            'initiative_officer' => 'required',
            'higher_officer' => 'required',
            'io' => 'required',
            'ho' => 'required',
            'io_name' => 'required',
            'ho_name' => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        $acr = EmployeeAcr::findOrFail($id);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $attachmentName = $acr->attachment;
                if ($request->hasFile('attachment')) {
                    if ($attachmentName) {
                        $file_path = public_path() . '/assets/Employee/ACR/' . $attachmentName;
                        unlink($file_path);
                    }
                    $attachmentName = 'ACR-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/ACR/'), $attachmentName);
                }

                $acr->update($request->except('attachment', 'updated_by') + [
                    'attachment' => $attachmentName,
                    'updated_by' => Auth::id(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee ACR updated successfully.');
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
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete($id)
    {
        $acr = EmployeeAcr::findOrFail($id);
        $attachmentName = $acr->attachment;
        $file_path = public_path() . '/assets/Employee/ACR/' . $attachmentName;
        if ($attachmentName && file_exists($file_path)) {
            unlink($file_path);
        }
        $acr->delete();

        Session::flash('message', 'Employee ACR deleted successfully.');
        return redirect()->back();
    }
}
