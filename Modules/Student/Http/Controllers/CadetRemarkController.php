<?php

namespace Modules\Student\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Student\Entities\ReportRemark;
use Modules\Student\Entities\StudentProfileView;

class CadetRemarkController extends Controller
{
    private $academicHelper;
    private $academicsLevel;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper, AcademicsLevel $academicsLevel)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;
    }


    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $academicLevels = $this->academicsLevel->all();
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('student::pages.cadet-remarks.cadet-remarks', compact('pageAccessData','academicLevels', 'academicYears'));
    }


    public function create()
    {
        return view('student::create');
    }


    public function store(Request $request)
    {
        $employee = Auth::user()->employee();
        if ($employee) {
            $designation = $employee->designation();
        } else {
            Session::flash('errorMessage', 'Sorry you are not an employee.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            foreach ($request->studentIds as $studentId) {
                $previousRemarks = ReportRemark::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $request->yearId,
                    'semester_id' => $request->termId,
                    'student_id' => $studentId,
                    'created_by' => Auth::id(),
                ])->first();

                if ($previousRemarks) {
                    $previousRemarks->update([
                        'score' => $request->scores[$studentId],
                        'remarks' => $request->remarks[$studentId],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id()
                    ]);
                } else {
                    ReportRemark::insert([
                        'student_id' => $studentId,
                        'employee_id' => $employee->id,
                        'designation' => $designation->alias,
                        'academic_year_id' => $request->yearId,
                        'semester_id' => $request->termId,
                        'score' => $request->scores[$studentId],
                        'remarks' => $request->remarks[$studentId],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Student remarks saved successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error saving remarks.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('student::show');
    }


    public function edit($id)
    {
        return view('student::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function searchStudentsForRemark(Request $request)
    {
        $pageAccessData = self::linkAccess($request  , ['manualRoute'=>'student/remarks']);
        if ($request->sectionId) {
            $students = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->sectionId
            ])->get();
        } elseif ($request->batchId) {
            $students = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'batch' => $request->batchId
            ])->get();
        }

        $yearId = $request->yearId;
        $termId = $request->termId;

        $previousRemarks = ReportRemark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $termId,
            'created_by' => Auth::id(),
        ])->get();

        return view('student::pages.cadet-remarks.student-list', compact('students','pageAccessData', 'yearId', 'termId', 'previousRemarks'))->render();
    }
}
