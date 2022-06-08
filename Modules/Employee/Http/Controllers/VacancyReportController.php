<?php

namespace  Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeDepartment;

use DateTime;
use Illuminate\Support\Carbon;

// use App;
use App\Helpers\ExamHelper;
use PDF;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use App\Helpers\UserAccessHelper;
use Modules\Employee\Entities\Imports\EmployeeImport;
use Mpdf\Tag\Em;
use phpDocumentor\Reflection\Types\Null_;

class VacancyReportController extends Controller
{
    private $academicHelper;
    
    public function __construct(AcademicHelper $academicHelper){
        $this->academicHelper = $academicHelper;
    }


    public function getVacancyReportDesignation(){
        $currentInstitute = Institute::find($this->academicHelper->getInstitute());
        $user = Auth::user();
        $designations = EmployeeDesignation::all();
        $role = $user->role();
        $toDate = date("Y-m-d");
        if(($role->name == 'super-admin') && ($currentInstitute == null)){
            $allInstitute = Institute::all();
        }
        else {
            $allInstitute = $currentInstitute;
        }
        return view('employee::reports.vacancy-report-designation', compact('toDate', 'currentInstitute', 'designations', 'allInstitute', 'user', 'role'));
    }

    public function searchvacancyByDesignation(Request $request){
        $institute = Institute::find($this->academicHelper->getInstitute());
        $instituteIds = $request->instituteId;
        $designationIds = $request->designationId;
        $today = date("Y-m-d");
        $toDate = $request->toDate;
        $allInstituteIds = array();
        $allDesignationIds = array();

        if($instituteIds[0] == 'all'){            
            $allInstituteIds = Institute::pluck('id')->toArray();
            $allInstitute = Institute::all();  
        }
        else{
            $allInstituteIds = $instituteIds;
            $allInstitute = Institute::whereIn('id', $instituteIds)->get();     
        }

        if($designationIds[0] == 'all'){            
            $allDesignationIds = EmployeeDesignation::pluck('id')->toArray();
            $allDesignation = EmployeeDesignation::all();            
        }
        else{
            $allDesignationIds = $designationIds;
            $allDesignation = EmployeeDesignation::whereIn('id', $designationIds)->get();
        }
        
        $held = array(0 => array());

        for($i=0; $i<count($allDesignation); $i++){
            for($j=0; $j<count($allInstitute); $j++){
                $total = EmployeeInformation::where(['designation' => $allDesignation[$i]->id, 'institute_id' => $allInstitute[$j]->id, 'status' => 1])->whereDate('dor', '>=', $toDate)->get();
                
                $held[$i][$j] = count($total);
            }
        }

        $toDate = DateTime::createFromFormat('Y-m-d', $toDate)->format('d-m-Y');

        if ($request->type == "print") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();

            $pdf->loadView('employee::reports.vacancy-report-designation-pdf', compact('instituteIds', 'designationIds', 'toDate', 'allInstituteIds', 'user', 'institute', 'instituteIds', 'allInstitute', 'allDesignation', 'held'))->setPaper('a2', 'landscape');
            return $pdf->stream('vacancy-report(designation).pdf');
        }
        else{
            return view('employee::reports.vacancy-report-designation-table', compact('instituteIds', 'designationIds', 'toDate', 'allInstituteIds', 'instituteIds', 'allInstitute', 'allDesignation', 'held'))->render();
        }        
    }


    public function getVacancyReportDepartment(){
        $currentInstitute = Institute::find($this->academicHelper->getInstitute());
        $user = Auth::user();
        $role = $user->role();
        $department = EmployeeDepartment::whereIn('dept_type', [1,2])->get();
        $toDate = date("Y-m-d");
        
        if(($role->name == 'super-admin') && ($currentInstitute == null)){
            $allInstitute = Institute::all();
        }
        else {
            $allInstitute = $currentInstitute;
        }
        
        return view('employee::reports.vacancy-report-department', compact('department', 'currentInstitute', 'allInstitute', 'role', 'toDate'));
    }

    public function searchDepartment(Request $request){
        if ($request->data[0] !== 'all') {
            return EmployeeDepartment::whereIn('dept_type', $request->data)->get();
        }
        else {
            return EmployeeDepartment::whereIn('dept_type', [1,2])->get();
        }
    }

    public function searchClass(Request $request){
        if ($request->data !== 'all') {
            $allDesignationIds = EmployeeDesignation::where('make_as', $request->data)->get()->pluck('id')->toArray();
            $allDesignation = EmployeeDesignation::whereIn('id', $allDesignationIds)->get()->groupBy('class');
        
            if (count($allDesignation) != 0) {
                foreach ($allDesignation as $key => $value) {
                    $abc[$key] = $key;
                }
                
                sort($abc);
                return (array_values($abc));
            }
            else {
                return [];
            }
        }
        else {
            $allDesignationIds = EmployeeDesignation::whereIn('make_as', [1,2,3,4])->get()->pluck('id')->toArray();
            $allDesignation = EmployeeDesignation::whereIn('id', $allDesignationIds)->get()->groupBy('class');
            if (count($allDesignation) != 0) {
                foreach ($allDesignation as $key => $value) {
                    $abc[$key] = $key;
                }

                return array_values($abc);
            }
            else {
                return [];
            }
        }
    }

    public function searchDesignation(Request $request){
        if ($request->selectedClass[0] === 'all' && count($request->sortedClasses) !== 0 && $request->desigGroup !== 'all') {
            
            return EmployeeDesignation::whereIn('class', $request->sortedClasses)->where('make_as', $request->desigGroup)->get();
        }
        elseif ($request->selectedClass[0] !== 'all' && $request->desigGroup !== 'all') {
            return EmployeeDesignation::whereIn('class', $request->selectedClass)->whereIn('class', $request->sortedClasses)->where('make_as', $request->desigGroup)->get();
        }
        elseif ($request->selectedClass[0] === 'all' && count($request->sortedClasses) !== 0 && $request->desigGroup === 'all') {
            return EmployeeDesignation::whereIn('class', $request->sortedClasses)->whereIn('make_as', [1,2,3,4])->get();
        }
        elseif ($request->selectedClass[0] !== 'all' && count($request->sortedClasses) !== 0 && $request->desigGroup === 'all') {
            return EmployeeDesignation::whereIn('class', $request->selectedClass)->whereIn('make_as', [1,2,3,4])->get();
        }
        else {
            return [];
        }
    }

    public function searchvacancyByDepartmentCurrentInstitute(Request $request){
        $institute = Institute::find($this->academicHelper->getInstitute());
        $instituteIds = $request->instituteId;
        $deptCategory = $request->deptCategory;
        $departmentIds = $request->departmentId;
        $designationIds = $request->designationId;
        $today = date("Y-m-d");
        $toDate = $request->toDate;
        $allInstituteIds = array();
        $allDepartmentIds = array();
        $keyByDesignation = EmployeeDesignation::get()->keyBy('id');
        $keyByInstitute = Institute::get()->keyBy('id');

        if($instituteIds[0] == 'all'){            
            $allInstituteIds = Institute::pluck('id')->toArray();
            $allInstitute = Institute::all();  
        }
        else{
            $allInstituteIds = $instituteIds;
            $allInstitute = Institute::whereIn('id', $instituteIds)->get();     
        }
        
        error_log(is_array($departmentIds[0]));
        if($departmentIds[0] == 'all'){            
            $allDepartmentIds = EmployeeDepartment::pluck('id')->toArray();
            $allDepartment = EmployeeDepartment::all();            
        }
        elseif (is_array($departmentIds) && $departmentIds[0] != 'all') {
            return $departmentIds[0];
            $allDepartmentIds = EmployeeDepartment::whereIn('id', $departmentIds)->pluck('id')->toArray();
            $allDepartment = EmployeeDepartment::whereIn('id', $departmentIds)->get();
        }
        else{
            $allDepartmentIds = $departmentIds;
            $allDepartment = EmployeeDepartment::whereIn('id', $departmentIds)->get();
        }

        if($designationIds[0] == 'all'){            
            $allDesignationIds = EmployeeDesignation::pluck('id')->toArray();
            $allDesignation = EmployeeDesignation::all(); 
            
        }else{
            $allDesignationIds = EmployeeDesignation::where('make_as', $designationIds[0])->get()->pluck('id')->toArray();
            $allDesignation = EmployeeDesignation::whereIn('id', $allDesignationIds)->get();
        }

        $strength = array();
        $held = array();
        $totalHeld = 0;
        $def = array();
        $totalDef = 0;
        $surp = array();
        $totalSurp = 0;
        $desWiseData = array();
        $fixedDesWiseData = array();
        $totalFixedDesWiseData = array();
        $insWiseData = array();
        $dateWiseDiff = array();
        $totalDateWiseDiff = 0;

        foreach ($allDepartment as $department) {
            $totalEmployees = EmployeeInformation::where(['department' => $department->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)->get();
            $designationWiseEmployees = EmployeeInformation::where(['department' => $department->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)->get()->groupBy('designation');
            $instituteWiseEmployees = EmployeeInformation::where(['department' => $department->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)->get()->groupBy('institute_id');

            $strength[$department->id] = $department->strength;
            $held[$department->id] = count($totalEmployees);
            $def[$department->id] = ($department->strength * sizeof($allInstituteIds)) - count($totalEmployees);
            $surp[$department->id] = count($totalEmployees) - ($department->strength * sizeof($allInstituteIds));
            $totalHeld += count($totalEmployees);
            $totalDef += $def[$department->id];
            $totalSurp += $surp[$department->id];

            $desWiseData[$department->id] = [];
            $insWiseData[$department->id] = [];
            $fixedDesWiseData[$department->id] = [];

            foreach ($designationWiseEmployees as $desId => $employeeGroup) {
                $desWiseData[$department->id][$desId] = [
                    'name' => (isset($keyByDesignation[$desId]))?$keyByDesignation[$desId]->name:'Blank',
                    'held' => count($employeeGroup),
                    'def' => $department->strength - count($employeeGroup),
                    'sur' => count($employeeGroup) - $department->strength
                ];
            }

            $tempInsIds = $allInstituteIds;
            foreach ($instituteWiseEmployees as $insId => $employeeGroup) {
                $insWiseData[$department->id][$insId] = [
                    'name' => $keyByInstitute[$insId]->institute_alias,
                    'held' => count($employeeGroup),
                    'def' => $department->strength - count($employeeGroup),
                    'sur' => count($employeeGroup) - $department->strength
                ];
                if (($key = array_search($insId, $tempInsIds)) !== false) {
                    unset($tempInsIds[$key]);
                }
            }
            foreach ($tempInsIds as $insId) {
                $insWiseData[$department->id][$insId] = [
                    'name' => $keyByInstitute[$insId]->institute_alias,
                    'held' => 0,
                    'def' => $department->strength,
                    'sur' => null
                ];
            }

            if($toDate > $today){
                $dateWiseDiff[$department->id] = (EmployeeInformation::where(['department' => $department->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)
                                                ->whereBetween('dor', [$today, $toDate])->count());
                $totalDateWiseDiff += $dateWiseDiff[$department->id];

                foreach ($instituteWiseEmployees as $insId => $employeeGroup) {
                    $dateWiseInsDiff = EmployeeInformation::where([
                                            'department' => $department->id, 
                                            'status' => 1,
                                            'institute_id' => $insId,
                                        ])->whereBetween('dor', [$today, $toDate])->count();
                    $insWiseData[$department->id][$insId]['def'] += $dateWiseInsDiff;
                    $insWiseData[$department->id][$insId]['sur'] -= $dateWiseInsDiff;
                }
            }

            if($designationIds[0] != 'all'){
                foreach ($allDesignation as $designation) {
                    if($toDate > $today){
                        $fixedDesWiseData[$department->id][$designation->id] = (EmployeeInformation::
                            where(['department' => $department->id, 'designation' => $designation->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)->count() - EmployeeInformation::
                            where(['department' => $department->id, 'designation' => $designation->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)->whereBetween('dor', [$today, $toDate])->count());
                    }
                    else {
                        $fixedDesWiseData[$department->id][$designation->id] = EmployeeInformation::
                            where(['department' => $department->id, 'designation' => $designation->id, 'status' => 1])->whereIn('institute_id', $allInstituteIds)->count();
                    }
                    
                    if (isset($totalFixedDesWiseData[$designation->id])) {
                        $totalFixedDesWiseData[$designation->id] += $fixedDesWiseData[$department->id][$designation->id];
                    }else {
                        $totalFixedDesWiseData[$designation->id] = $fixedDesWiseData[$department->id][$designation->id];
                    }
                }
            }
        }
        $toDate1 = DateTime::createFromFormat('Y-m-d', $toDate)->format('d-m-Y');

        if ($request->type == "print") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('employee::reports.vacancy-report-department-pdf', compact('allDepartment', 'departmentIds', 'allDesignation', 
            'designationIds', 'allInstitute', 'instituteIds', 'toDate1', 'allInstituteIds', 'user', 'today', 'designationIds', 'toDate', 
            'institute', 'allDepartment', 'allDesignation', 'held', 'totalHeld', 'def', 'totalDef', 'surp', 'totalSurp', 'desWiseData', 
            'insWiseData', 'dateWiseDiff', 'fixedDesWiseData', 'totalDateWiseDiff', 'totalFixedDesWiseData'))->setPaper('a2', 'potrait');
            return $pdf->stream('vacancy-report(department).pdf');
        }
        else{
            return view('employee::reports.vacancy-report-department-table', compact('allDepartment', 'departmentIds', 'allDesignation', 
            'designationIds', 'allInstitute', 'instituteIds', 'toDate1', 'allInstituteIds', 'today', 'designationIds', 'toDate', 'institute', 
            'allDepartment', 'allDesignation', 'held', 'totalHeld', 'def', 'totalDef', 'surp', 'totalSurp', 'desWiseData', 'insWiseData', 'dateWiseDiff', 
            'fixedDesWiseData', 'totalDateWiseDiff', 'totalFixedDesWiseData'))->render();
        }
    }
}