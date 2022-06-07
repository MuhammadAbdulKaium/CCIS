<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\EmployeeAcr;
use Modules\Employee\Entities\EmployeeContributionBoardResult;
use Modules\Employee\Entities\EmployeeDiscipline;
use Modules\Employee\Entities\EmployeeDocument;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeePublication;
use Modules\Employee\Entities\EmployeeSpecialDuty;
use Modules\Employee\Entities\EmployeeTraining;
use App;
use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Entities\EmployeeAward;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeePromotion;
use Modules\Employee\Entities\EmployeeTransferHistory;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use MPDFGEN;

class EmployeeProfileDetailsReportController extends Controller
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
    public function index(Request $request)
    {
        // check Institute
        $allEmployee = [];
        $allInstitute = [];
        $isInstitute = false;
        $allDepartments =EmployeeDepartment::all();
        $allDesignations = EmployeeDesignation::all(); 
        if ($this->academicHelper->getInstitute()) {
            $allEmployee = EmployeeInformation::with('singleUser')->where([
                'status' => 1,
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->select('id', 'user_id', 'title', 'first_name', 'middle_name', 'last_name')->get();
            $allInstitute = Institute::where('id', $this->academicHelper->getInstitute())->select('id', 'institute_alias')->get();
            $isInstitute = true;
        } else {
            $allEmployee = EmployeeInformation::with('singleUser')->where([
                'status' => 1,
            ])->select('id', 'user_id', 'title', 'first_name', 'middle_name', 'last_name')->get();
            $allInstitute = Institute::select('id', 'institute_alias')->get();
        }
        // return $allInstitute;

        $pageAccessData = self::linkAccess($request, ['manualRoute' => "employee/manage"]);
        return view('employee::reports.employee-profile-report', compact('allEmployee','allDepartments','allDesignations', 'isInstitute', 'allInstitute', 'pageAccessData'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function searchEmployeeReport(Request $request)
    {
        // return $request->all();
        $hide_blank = $request->hide_blank;
        $employee = EmployeeInformation::with('getEmployeAddress', 'singleUser', 'employeeAttachment', 'singleDepartment', 'singleDesignation')->findOrFail($request->employee_id);
        $user_name = $employee->singleUser?$employee->singleUser->username:' ';
        $employee_name = $employee->title.' '.$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;
         $pdf_name= $user_name.' - '.$employee_name;

        $qualifications = EmployeeDocument::where([
            'employee_id' => $request->employee_id,
            'document_type' => 1,
        ])->get();
        $allTraining = EmployeeTraining::with('singleInstitute')->where([
            'employee_id' => $request->employee_id,
        ])->latest()->get();
        $employee_acrs = EmployeeAcr::with('employeeIoName', 'employeeHoName', 'singleInstitute')->where([
            'employee_id' => $request->employee_id,
        ])->latest()->get();
        $publications = EmployeePublication::with('publicationEditions', 'singleInstitute')->where([
            'employee_id' => $request->employee_id,
        ])->latest()->get();
        $allDisciplines = EmployeeDiscipline::with('singlePunishmentBy', 'singleInstitute')->where([
            'employee_id' => $request->employee_id,
        ])->get();
        $employeeCBRs = EmployeeContributionBoardResult::with('singleInstitute')->where([
            'employee_id' => $request->employee_id,
        ])->get();
        $exam_years_groups = $employeeCBRs->groupBy('exam_years');
        $specialDuties = EmployeeSpecialDuty::where([
            'employee_id' => $request->employee_id,
        ])->latest()->get();
        $employeeTransferHistories = EmployeeTransferHistory::with('institute')->where('employee_id', $request->employee_id)->orderBy('id', 'DESC')->get();
        $remainingTenure = EmployeePromotion::with('singleDesignation')->where([
            'employee_id' => $request->employee_id,
            'status' => "approved",
        ])->latest()->first();
        $promotions = EmployeePromotion::with('singleDesignation')->where([
            'employee_id' => $request->employee_id,
            'status' => "approved",
        ])->latest()->get();
        //    employee Promotion
        $allCampus=Campus::all()->keyBy('id');
        $allDept = EmployeeDepartment::all()->keyBy('id');
        $allDesignation = EmployeeDesignation::all()->keyBy('id');
        $allInstitute = Institute::all()->keyBy('id');
        $employeePromotions = EmployeePromotion::where('employee_id', $request->employee_id)->get()->sortByDesc('created_at');
        $newPromotion = $employeePromotions->where('status', 'pending');
        $awards = EmployeeAward::where([
            'employee_id' => $request->employee_id,
        ])->get();

        $selectForm = $request->selectForm;
        if (!$selectForm) {
            $selectForm = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22];
        }

        if ($request->type == "search") {
            return view('employee::reports.employee-profile-details-report', compact('employeeTransferHistories', 'allDept','allCampus','newPromotion','employeePromotions','allInstitute','allDesignation', 'promotions', 'remainingTenure', 'hide_blank', 'selectForm', 'employee', 'qualifications', 'allTraining', 'employee_acrs', 'publications', 'allDisciplines', 'exam_years_groups', 'specialDuties', 'awards'));
        } else if ($request->type == "print") {
            $user = Auth::user();
            $institute = null;
            if ($this->academicHelper->getInstitute()) {

                $institute = Institute::find($this->academicHelper->getInstitute());
            } else {
                $institute = Institute::find($employee->institute_id);
            }
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadView(
                'employee::reports.employee-profile-details-report-print',
                compact('employeeTransferHistories', 'allDept','allCampus','newPromotion','employeePromotions','allInstitute','allDesignation', 'promotions', 'remainingTenure', 'hide_blank', 'user', 'institute', 'selectForm', 'employee', 'qualifications', 'allTraining', 'employee_acrs', 'publications', 'allDisciplines', 'exam_years_groups', 'specialDuties', 'awards')
            )
                ->setPaper('a4');
             return $pdf->stream('hR-details-report.pdf');
            //    $pdf=MPDFGEN::loadView('employee::reports.employee-profile-details-report-print', 
            //    compact('employeeTransferHistories','promotions','remainingTenure','hide_blank','user','institute','selectForm','employee','qualifications','allTraining','employee_acrs','publications','allDisciplines','exam_years_groups','specialDuties'), [], [
            //     'title' => 'Another Title',
            //     // 'margin_top' => 0
            //     'format'=> 'A4',

            // ]);
            // return $pdf->stream('document.pdf');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function searchEmployee(Request $request)
    {
        $searchInput =[];
        $searchInput['status'] = 1;
        if($request->id){
            $searchInput['institute_id'] = $request->id;
        }else{
            if($this->academicHelper->getInstitute()){
                $searchInput['institute_id'] =$this->academicHelper->getInstitute();
            }
        }
        if($request->dep_id){
            $searchInput['department'] = $request->dep_id;
        }
        if($request->deg_id){
            $searchInput['designation'] = $request->deg_id;
        }
        
        $allEmployee = EmployeeInformation::with('singleUser')->where($searchInput)->select('id', 'user_id', 'title', 'first_name', 'middle_name', 'last_name')->get();
        
        return $allEmployee;
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
        return view('employee::edit');
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
