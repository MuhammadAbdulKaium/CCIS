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
use Modules\Academics\Entities\Subject;
use Modules\Employee\Entities\EmployeeContributionBoardResult;
use Modules\Employee\Entities\EmployeeInformation;

class EmployeeContributionBoardResultController extends Controller
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
        $employeeCBRs = EmployeeContributionBoardResult::with('singleInstitute')->where([
            'employee_id' => $id,
        ])->get();
         $exam_years_groups = $employeeCBRs->groupBy('exam_years');
        //  $counts = $employeeCBRs->groupBy('exam_years')->count();
        return view('employee::pages.profile.contribution-board-result', compact('employeeInfo','exam_years_groups', 'pageAccessData'))->with('page', 'contribution-board-result');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $allSubjects = Subject::all();
        return view('employee::pages.modals.contribution-board-result-create', compact('employeeInfo', 'allSubjects'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'exam_years' => 'required',
            'exam_name' => 'required',
            'total_cadet' => 'required',
            'gpa_type' => 'required',
            'without_gpa' => 'required_if:gpa_type,0',
            'gpa_subject.*' => 'required_if:gpa_type,1',
            'gpa.*' => 'required_if:gpa_type,1',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $assign_GPA = [];
                $not_Assign_GPA = [];

                if ($request->gpa_type == 1) {

                    foreach ($request->gpa_subject as $key => $value) {
                        array_push($assign_GPA, [
                            'subject' => $request->gpa_subject[$key],
                            'gpa' => $request->gpa[$key],
                        ]);
                        array_push($not_Assign_GPA,[
                            'subject' => $request->gpa_subject[$key],
                            'gpa' => $request->total_cadet - $request->gpa[$key],
                        ]);
                    }
                  
                } else {
                    array_push($assign_GPA,[
                        'gpa' => $request->without_gpa,
                    ]);
                    array_push($not_Assign_GPA,[
                        'gpa' => $request->total_cadet - $request->without_gpa,
                    ]);
                }
                $attachmentName = null;
                if ($request->hasFile('attachment')) {
                    $attachmentName = 'CBR-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/CBR/'), $attachmentName);
                }
                EmployeeContributionBoardResult::create([
                    'employee_id' => $request->employee_id,
                    'exam_years' => $request->exam_years,
                    'exam_name' => $request->exam_name,
                    'total_cadet' => $request->total_cadet,
                    'gpa_type' => $request->gpa_type,
                    'total_gpa' => json_encode($assign_GPA),
                    'not_total_gpa' => json_encode($not_Assign_GPA),
                    'remarks' => $request->remarks,
                    'attachment' => $attachmentName,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee CBR added successfully.');
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
        $cbr = EmployeeContributionBoardResult::findOrFail($id);
        $allSubjects = Subject::all();
        return view('employee::pages.modals.contribution-board-result-update', compact('cbr', 'allSubjects'));
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
            'exam_years' => 'required',
            'exam_name' => 'required',
            'total_cadet' => 'required',
            'gpa_type' => 'required',
            'without_gpa' => 'required_if:gpa_type,0',
            'gpa_subject.*' => 'required_if:gpa_type,1',
            'gpa.*' => 'required_if:gpa_type,1',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf|max:200'
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $cbr = EmployeeContributionBoardResult::findOrFail($id);
                $assign_GPA = [];
                $not_Assign_GPA = [];

                if ($request->gpa_type == 1) {

                    foreach ($request->gpa_subject as $key => $value) {
                        array_push($assign_GPA, [
                            'subject' => $request->gpa_subject[$key],
                            'gpa' => $request->gpa[$key],
                        ]);
                        array_push($not_Assign_GPA,[
                            'subject' => $request->gpa_subject[$key],
                            'gpa' => $request->total_cadet - $request->gpa[$key],
                        ]);
                    }
                  
                } else {
                    array_push($assign_GPA,[
                        'gpa' => $request->without_gpa,
                    ]);
                    array_push($not_Assign_GPA,[
                        'gpa' => $request->total_cadet - $request->without_gpa,
                    ]);
                }
               
                $attachmentName = $cbr->attachment;
                if($request->hasFile('attachment')){
                    if ($attachmentName){
                        $file_path = public_path().'/assets/Employee/CBR/'.$attachmentName;
                        unlink($file_path);
                    }
                    $attachmentName = 'CBR-' . time() . '.' . $request->attachment->extension();
                    $request->attachment->move(public_path('assets/Employee/CBR/'), $attachmentName);
                }
                $exam_years = $request->exam_years?$request->exam_years:$cbr->exam_years;
                $exam_name = $request->exam_name?$request->exam_name:$cbr->exam_name;
                $total_cadet = $request->total_cadet?$request->total_cadet:$cbr->total_cadet;
                $remarks = $request->remarks?$request->remarks: " ";

               $cbr->update([
                    'employee_id' => $request->employee_id,
                    'exam_years' => $exam_years,
                    'exam_name' => $exam_name,
                    'total_cadet' => $total_cadet,
                    'gpa_type' => $request->gpa_type,
                    'total_gpa' => json_encode($assign_GPA),
                    'not_total_gpa' => json_encode($not_Assign_GPA),
                    'remarks' => $remarks,
                    'attachment' => $attachmentName,
                    'updated_by' => Auth::id(),
                ]);
                DB::commit();
                Session::flash('message', 'Employee CBR Updated successfully.');
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
        $cbr = EmployeeContributionBoardResult::findOrFail($id);
        $attachmentName = $cbr->attachment;
        $file_path = public_path() . '/assets/Employee/CBR/' . $attachmentName;
        if ($attachmentName && file_exists($file_path)) {
            unlink($file_path);
        }
        $cbr->delete();

        Session::flash('message', 'Employee CBR deleted successfully.');
        return redirect()->back();
    }
}
