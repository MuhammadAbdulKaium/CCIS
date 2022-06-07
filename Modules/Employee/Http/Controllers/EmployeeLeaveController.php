<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeLeaveApplication;
use Modules\Employee\Entities\EmployeeLeaveAssign;
use Modules\Employee\Entities\EmployeeLeaveAssignHistory;
use Modules\Employee\Entities\EmployeeLeaveStructure;
use Modules\Employee\Entities\EmployeeLeaveType;
use Modules\RoleManagement\Entities\Role;
use Modules\Student\Entities\StudentProfileView;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;

class EmployeeLeaveController extends Controller
{
    private  $academicHelper;
    private  $academicsYear;
    private $studentProfileView;
    private $department;
    private $designation;
    private $employeeInformation;
    private $employeeLeaveType;
    private $employeeLeaveApplication;

    public function __construct(EmployeeLeaveApplication $employeeLeaveApplication,EmployeeLeaveType $employeeLeaveType,AcademicHelper $academicHelper, AcademicsYear $academicsYear, StudentProfileView $studentProfileView, EmployeeDesignation $designation, EmployeeDepartment $department,EmployeeInformation $employeeInformation)
    {
        $this->academicHelper                 = $academicHelper;
        $this->academicsYear                 = $academicsYear;
        $this->studentProfileView                 = $studentProfileView;
        $this->department = $department;
        $this->designation = $designation;
        $this->employeeInformation = $employeeInformation;
        $this->employeeLeaveType = $employeeLeaveType;
        $this->employeeLeaveApplication           = $employeeLeaveApplication;
    }


    public function index()
    {
        $leaveType=EmployeeLeaveType::all();
        return view('employee::pages.leave.leave-type',compact('leaveType'));
    }

    public function leaveStructure()
    {
        $leaveType=EmployeeLeaveType::all();
        $leaveStructure = EmployeeLeaveStructure::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $monthName=['1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'];
        return view('employee::pages.leave.leave-structure',compact('leaveStructure','monthName','leaveType'));
    }


    public function addLeaveStructure()
    {
        $monthName=['1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'];
        $leaveType=EmployeeLeaveType::all();
        return view('employee::pages.leave.modals.leave-structure-add',compact('leaveType','monthName'));
    }
    public function editLeaveStructure($id)
    {
        $leaveStructure=EmployeeLeaveStructure::where('id',$id)->first();
        $monthName=['1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'];
        $leaveType=EmployeeLeaveType::all();
        return view('employee::pages.leave.modals.leave-structure-edit',compact('leaveType','monthName','leaveStructure'));
    }

    public function leave_entitile()
    {
        return view('employee::pages.leave.leave-entitle');
    }
    public function storeLeaveStructure(Request $request)
    {
        $validated = $request->validate([
            'leave_name' => ['required',
                Rule::unique('employee_leave_structures')->where(function($query) {
                    $query->where('institute_id','=',$this->academicHelper->getInstitute());})],
            'leave_name_alias' => ['required',
                Rule::unique('employee_leave_structures')->where(function($query) {
                    $query->where('institute_id','=',$this->academicHelper->getInstitute());})],
        ]);

        $storeLeaveStructure=new EmployeeLeaveStructure();
        $storeLeaveStructure->leave_type=$request->leave_type;
        $storeLeaveStructure->leave_name=$request->leave_name;
        $storeLeaveStructure->leave_name_alias=$request->leave_name_alias;
        $storeLeaveStructure->leave_duration=$request->leave_duration;
        $storeLeaveStructure->doj=$request->doj?$request->doj:0;
        $storeLeaveStructure->cf=$request->cf?$request->cf:0;
        $storeLeaveStructure->year_closing=$request->year_closing?$request->year_closing:0;
        $storeLeaveStructure->year_closing_month=$request->year_closing_month?$request->year_closing_month:0;
        $storeLeaveStructure->holidayEffect=$request->holidayEffect?$request->holidayEffect:0;
        $storeLeaveStructure->encash=$request->encash?$request->encash:0;
        $storeLeaveStructure->salaryType=$request->salaryType?$request->salaryType:0;
        $storeLeaveStructure->salary_type_percentage=$request->salary_type_percentage?$request->salary_type_percentage:0;
        $storeLeaveStructure->campus_id=$this->academicHelper->getCampus();
        $storeLeaveStructure->institute_id=$this->academicHelper->getInstitute();
        $storeLeaveStructure->created_by=Auth::user()->id;
        $storeLeaveStructure->save();
    }
    public function updateLeaveStructure(Request $request)
    {
        $leaveStructure=EmployeeLeaveStructure::where('id',$request->id)->first();
        $validated = $request->validate([
            'leave_name'   =>  [
                'required',
                'max:255',
                 Rule::unique('employee_leave_structures')->ignore($request->id),
            ],
            'leave_name_alias'   =>  [
                'required',
                'max:255',
                Rule::unique('employee_leave_structures')->ignore($request->id),
            ]
        ]);

        $leaveStructure->update([
            'leave_type'=>$request->leave_type,
            'doj'=>$request->doj?$request->doj:0,
            'leave_name'=>$request->leave_name,
            'leave_name_alias'=>$request->leave_name_alias,
            'leave_duration'=>$request->leave_duration,
            'cf'=>$request->cf?$request->cf:0,
            'year_closing'=>$request->year_closing?$request->year_closing:0,
            'year_closing_month'=>$request->year_closing_month?$request->year_closing_month:0,
            'holidayEffect'=>$request->holidayEffect?$request->holidayEffect:0,
            'encash'=>$request->encash?$request->encash:0,
            'salaryType'=>$request->salaryType?$request->salaryType:0,
            'salary_type_percentage'=>$request->salary_type_percentage?$request->salary_type_percentage:0,
        ]);
    }

    public function store(Request $request)
    {
    }


    public function show()
    {
        return view('employee::show');
    }


    public function edit()
    {
        return view('employee::edit');
    }


    public function update(Request $request)
    {
    }


    public function destroy()
    {
    }

    public function LeaveAssign()
    {
        $currentYear=$now = Carbon::now()->year;
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->orderBy('name', 'ASC')->get();
        // employee Leave Type
        $allLeaveType=$this->employeeLeaveType->orderBy('leave_type_name', 'ASC')->get();
        return view('employee::pages.leave.user-leave-assign',compact('allDesignaitons','allDepartments','allLeaveType','campusId','currentYear'));
    }

    public function userLeaveAssign()
    {
        $leave_type=EmployeeLeaveType::all();
        return view('employee::pages.leave.modals.user-leave-assign-add',compact('leave_type'));

    }

    public function roleLeaveAssign()
    {
        $roles=Role::all();
        $leave_type=EmployeeLeaveType::all();
        return view('employee::pages.leave.modals.role-leave-assign-add',compact('roles','leave_type'));

    }

    public function getAjaxDepartmentDesignation($id)
    {
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->where('dept_id',$id)->orderBy('name', 'ASC')->get();
            $data = [];
            if($allDesignaitons->count() > 0) {
                array_push($data, '<option value="">-- Select --</option>');
                foreach ($allDesignaitons as $item) {
                    array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->name . '</option>');
                }
            }
        return json_encode($data) ;
    }

    public function searchEmployee(Request $request)
    {

        $dept_id  = $request->input('dept_id');
        $designation_id  = $request->input('designation_id');
        $emp_id  = $request->input('emp_id');
        $emp_name  = $request->input('emp_name');
        $duration= $request ->input('duration');
        $leave_type_id = $request ->input('leave_type_id');
        $leave_process_procedure = $request ->input('leave_process_procedure');
        $leaveYear = $request ->input('leaveYear');

        $searchData = [];
        $allSearchInputs = array();
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        // check data
        if ($dept_id) $allSearchInputs['department'] = $dept_id ;
        if ($designation_id) $allSearchInputs['designation'] = $designation_id ;
        $leaveStructure = EmployeeLeaveStructure::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $leaveAssignData=EmployeeLeaveAssign::all()->groupBy('emp_id');
        $searchData=EmployeeInformation::where($allSearchInputs)->get();
        $stdListView = view('employee::pages.leave.employee-list', compact('leaveAssignData','leaveStructure','searchData','duration','leave_type_id','dept_id','designation_id','leave_process_procedure','leaveYear'))->render();
        return ['status'=>'success', 'msg'=>'Employee List found', 'html'=>$stdListView];

    }
    public function searchEmployeeLeaveStatus(Request $request)
    {
        $dept_id  = $request->input('dept_id');
        $designation_id  = $request->input('designation_id');
        $emp_id  = $request->input('emp_id');
        $emp_name  = $request->input('emp_name');
        $duration= $request ->input('duration');
        $leave_type_id = $request ->input('leave_type_id');
        $leave_process_procedure = $request ->input('leave_process_procedure');
        $leaveYear = $request ->input('leaveYear');
        $startDate = $request ->input('start_date');
        $endDate = $request ->input('end_date');

        $searchData = [];
        $allSearchInputs = array();
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        // check data
        if ($dept_id) $allSearchInputs['department'] = $dept_id ;
        if ($designation_id) $allSearchInputs['designation'] = $designation_id ;
        $leaveStructure = EmployeeLeaveStructure::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $leaveApplications=EmployeeLeaveApplication::where('status','!=',0)->get()->groupBy('employee_id');
        $leaveAssignData=EmployeeLeaveAssign::all()->groupBy('emp_id');
        $leaveAssignHistoryData=EmployeeLeaveAssignHistory::get()->groupBy('emp_id');
        $searchData=EmployeeInformation::where($allSearchInputs)->get();

        $institute= $this->academicHelper->getInstituteProfile();

        if ($request->type == "print") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('employee::reports.all-employee-leave-report-pdf', compact('searchData','institute','leaveStructure'))->setPaper('a2', 'landscape');
            return $pdf->stream();
        }
        else{
            $stdListView = view('employee::reports.employee-list', compact('leaveApplications','leaveAssignHistoryData','startDate','endDate','leaveAssignData','leaveStructure','searchData','duration','leave_type_id','dept_id','designation_id','leave_process_procedure','leaveYear'))->render();
            return ['status'=>'success', 'msg'=>'Employee List found', 'html'=>$stdListView];
        }

    }
    public function singleEmployeeLeaveStatus($id)
    {
        $employeeData=EmployeeInformation::with('singleUser','singleDepartment','singleDesignation')->where('user_id',$id)->first();
        $employeeLeaveAssign=EmployeeLeaveAssign::with('leaveStructureDetail')->where('emp_id',$id)->get();
//        return $employeeLeaveAssign;
        $employeeLeaveAssignHistory=EmployeeLeaveAssignHistory::where('emp_id',$id)->get()->keyBy('leave_structure_id');
        $employeeLeaveApplication=EmployeeLeaveApplication::with('leaveStructureName')->where('employee_id',$id)->orderBy('id','DESC')->get();
        return view('employee::reports.single-employee-leave-status-report',compact('employeeData','employeeLeaveAssign','employeeLeaveAssignHistory','employeeLeaveApplication'));

    }

    public function assignSubmitEmployee(Request $request)
    {
//        return $request;
        foreach ($request['leave_id'] as $key=>$leave)
        {
            foreach ($request['leave_duration'] as $empid=>$leave_detail)
            {
                if($empid==$key)
                {
                    foreach ($leave as $singleLeave)
                    {
                        $employeeLeaveAssign=EmployeeLeaveAssign::where('emp_id',$empid)
                            ->where('leave_structure_id',$singleLeave)->first();
                        if($employeeLeaveAssign)
                        {
                            $employeeLeaveStructure=EmployeeLeaveStructure::where('id',$singleLeave)->first();
                            if($employeeLeaveStructure->cf==1)
                            {
                                $data=array();
                                $data['leave_duration'] = $employeeLeaveAssign->leave_duration+$leave_detail[$singleLeave];
                                $data['leave_remain'] = $employeeLeaveAssign->leave_remain+$leave_detail[$singleLeave];
                                $data['updated_by'] = Auth::user()->id;
                                $employeeLeaveAssignUpdate=EmployeeLeaveAssign::where('emp_id',$empid)
                                    ->where('leave_structure_id',$singleLeave)->update($data);
                                if($employeeLeaveAssignUpdate)
                                {
                                    $leaveAssign=new EmployeeLeaveAssignHistory();
                                    $leaveAssign->emp_id=$empid;
                                    $leaveAssign->leave_structure_id=$singleLeave;
                                    $leaveAssign->leave_duration=$leave_detail[$singleLeave];
                                    $leaveAssign->leave_remain=$employeeLeaveAssign->leave_remain+$leave_detail[$singleLeave];
                                    $leaveAssign->created_by=Auth::user()->id;
                                    $leaveAssign->save();
                                }
                            }
                            else{
                                $data=array();
                                $data['leave_duration'] =$leave_detail[$singleLeave];
                                $data['leave_remain'] = $leave_detail[$singleLeave];
                                $data['updated_by'] = Auth::user()->id;
                                $employeeLeaveAssignUpdate=EmployeeLeaveAssign::where('emp_id',$empid)
                                    ->where('leave_structure_id',$singleLeave)->update($data);
                                if($employeeLeaveAssignUpdate)
                                {
                                    $leaveAssign=new EmployeeLeaveAssignHistory();
                                    $leaveAssign->emp_id=$empid;
                                    $leaveAssign->leave_structure_id=$singleLeave;
                                    $leaveAssign->leave_duration=$leave_detail[$singleLeave];
                                    $leaveAssign->leave_remain=$leave_detail[$singleLeave];
                                    $leaveAssign->created_by=Auth::user()->id;
                                    $leaveAssign->save();
                                }
                            }
                        }
                        else{
                            $leaveAssign=new EmployeeLeaveAssign();
                            $leaveAssign->emp_id=$empid;
                            $leaveAssign->leave_structure_id=$singleLeave;
                            $leaveAssign->leave_duration=$leave_detail[$singleLeave];
                            $leaveAssign->leave_remain=$leave_detail[$singleLeave];
                            $leaveAssign->created_by=Auth::user()->id;
                            $leaveAssignStore=$leaveAssign->save();
                            if($leaveAssignStore)
                            {
                                $leaveAssign=new EmployeeLeaveAssignHistory();
                                $leaveAssign->emp_id=$empid;
                                $leaveAssign->leave_structure_id=$singleLeave;
                                $leaveAssign->leave_duration=$leave_detail[$singleLeave];
                                $leaveAssign->leave_remain=$leave_detail[$singleLeave];
                                $leaveAssign->created_by=Auth::user()->id;
                                $leaveAssign->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function AllLeaveAssign()
    {
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // employee Leave Type
        $allLeaveType=$this->employeeLeaveType->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        $allLeaveApplications = $this->employeeLeaveApplication->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId])->get();
        return view('employee::pages.leave.all-leave-application',compact('allDesignaitons','allDepartments','allLeaveType','campusId','allLeaveApplications'));
    }

    public function LeaveEncashment()
    {

        $currentYear=$now = Carbon::now()->year;
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // employee Leave Type
        $allLeaveType=$this->employeeLeaveType->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
//        Employee List
        $employeeData=$this->employeeInformation->get();
        return view('employee::pages.leave-management.user-leave-encashment',compact('employeeData','allDesignaitons','allDepartments','allLeaveType','campusId','currentYear'));
//        return view('employee::pages.leave-management.user-leave-encashment')->with('leaveStructureProfile', null);
    }

    public function searchEmployeeEncahment(Request $request)
    {
        $dept_id  = $request->input('dept_id');
        $designation_id  = $request->input('designation_id');
        $emp_id  = $request->input('emp_id');
        $emp_name  = $request->input('emp_name');
        $duration= $request ->input('duration');
        $leave_type_id = $request ->input('leave_type_id');
        $leave_process_procedure = $request ->input('leave_process_procedure');
        $leave_year = $request ->input('leave_year');

        $currentYear=$now = Carbon::now()->year;

        $searchData = [];
        $allSearchInputs = array();

        $allSearchInputs['campus_id '] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id '] = $this->academicHelper->getInstitute();

        // check data
        if ($dept_id) $allSearchInputs['department'] = $dept_id ;
        if ($designation_id) $allSearchInputs['designation'] = $designation_id ;
        if ($emp_id) $allSearchInputs['user_id'] = $emp_id;
        if ($emp_name) $allSearchInputs['first_name'] = $emp_name;

        $searchData=$this->employeeInformation->where($allSearchInputs)->get();
        $stdListView = view('employee::pages.leave-management.employee-encashment-list', compact('searchData','duration','leave_type_id','dept_id','designation_id','leave_process_procedure','currentYear','leave_year'))->render();
        return ['status'=>'success', 'msg'=>'Student List found', 'html'=>$stdListView];

    }

}
