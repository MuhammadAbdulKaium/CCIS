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
use Modules\Student\Entities\CadetWarning;
use Modules\Student\Entities\StudentProfileView;

class CadetWarningController extends Controller
{
    private $academicHelper;
    private $academicsLevel;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper, AcademicsLevel $academicsLevel)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;
    }


    public function index()
    {
        $academicLevels = $this->academicsLevel->get();
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('student::pages.cadet-warnings.cadet-warnings', compact('academicLevels', 'academicYears'));
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
            $insertData = CadetWarning::insert([
                'student_id' => $request->stdId,
                'employee_id' => $employee->id,
                'designation' => $designation->alias,
                'academic_year_id' => $request->yearId,
                'semester_id' => $request->termId,
                'comment' => $request->comment,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($insertData) {
                DB::commit();
                Session::flash('message', 'Warning successfull.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error giving warning.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error giving warning.');
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

    public function searchStudentsForWarning(Request $request)
    {
        $pageAccessData = self::linkAccess($request  , ['manualRoute'=>'student/warnings']);
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

        return view('student::pages.cadet-warnings.student-list', compact('students', 'pageAccessData','yearId', 'termId'))->render();
    }
}
