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
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\ClassTeacherAssign;
use Modules\Academics\Entities\ManageTimetable\TimeTable;
use Modules\Academics\Entities\SubjectTeacher;
use Modules\Communication\Entities\Event;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeLeaveApplication;
use Modules\Employee\Entities\EmployeeTransferHistory;
use Modules\Event\Entities\Event as EntitiesEvent;
use Modules\House\Entities\House;
use Modules\House\Entities\HouseAppoint;
use Modules\House\Entities\HouseAppointUser;
use Modules\LevelOfApproval\Entities\ApprovalLayer;
use Modules\Library\Entities\IssueBook;
use Modules\Payroll\Entities\SalaryAssign;
use Modules\Mess\Entities\MessTableSeat;
use Modules\Setting\Entities\Institute;

class EmployeeTransferController extends Controller
{
    use UserAccessHelper;
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index($id,Request $request)
    {
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);
        $employeeInfo = EmployeeInformation::with('singleUser')->findOrFail($id);
        $employeeTransferHistories = EmployeeTransferHistory::with('institute')->where('employee_id', $id)->orderBy('id', 'DESC')->get();

        if (!isCurrentCampus($employeeInfo)) {
            return abort(404);
        }

        return view('employee::pages.profile.transfer', compact('pageAccessData','employeeInfo','employeeTransferHistories'))->with('page', 'transfer');
    }

    
    public function create()
    {
        return view('employee::create');
    }

    protected function employeeDependenciesCheck($employeeId){
        $employeeInfo = EmployeeInformation::findOrFail($employeeId);
        $userInfo = $employeeInfo->singleUser;
        $userId = $userInfo->id;
        $dependencies = [];

        // --- Asif's Work Start ---
        // Physical Room Start
        $employeeRoom = DB::table('employee_room')->where('employee_id', $employeeId)->first();
        if ($employeeRoom) {
            array_push($dependencies, [
                'name' => 'Physical Room FM/HR',
                'link' => url('/academics/physical/rooms')
            ]);
        }
        // Physical Room End
        // Manage Academics Start
        $subjectTeacher = SubjectTeacher::where('employee_id', $employeeId)->first();
        if ($subjectTeacher) {
            array_push($dependencies, [
                'name' => 'Manage Academics Subjects',
                'link' => url('/academics/manage/subject')
            ]);
        }
        // Manage Academics End
        // Manage Academics Start
        $eventJudge = EntitiesEvent::where('employee_id', 'LIKE', '%'.$employeeId.'%')->first();
        if ($eventJudge) {
            array_push($dependencies, [
                'name' => 'Event Judge',
                'link' => url('/event')
            ]);
        }
        // Manage Academics End
        // Level Of Approval Start
        $approvalLayer = ApprovalLayer::where('user_ids', 'LIKE', '%'.$userId.'%')->first();
        if ($approvalLayer) {
            array_push($dependencies, [
                'name' => 'Level Of Approval',
                'link' => url('/levelofapproval')
            ]);
        }
        // Level Of Approval End
        // House Start
        $house = House::where('employee_id', $employeeId)->first();
        if ($house) {
            array_push($dependencies, [
                'name' => 'House Master',
                'link' => url('/house/manage-house')
            ]);
        }
        // House End
        // House Appoint Start
        $houseAppoint = HouseAppointUser::where('user_id', $userId)->first();
        if ($houseAppoint) {
            array_push($dependencies, [
                'name' => 'House Appoint',
                'link' => url('/house/house-appoints')
            ]);
        }
        // House Appoint End
        // Mess Table Seats Start
        $messTableSeat = MessTableSeat::where('person_id', $employeeId)->first();
        if ($messTableSeat) {
            array_push($dependencies, [
                'name' => 'Mess Table Seat',
                'link' => url('/mess/table')
            ]);
        }
        // Mess Table Seats End
        // --- Asif's Work End ---

        // --- Mazharul works starting

        $classTeacherAssign=ClassTeacherAssign::where('teacher_id',$employeeId)->first();
        if($classTeacherAssign){
            array_push($dependencies,[
                'name'=>'Assign Form Master',
                'link'=>url('academics/timetable/class-teacher-assign')
            ]);
        }

        //check if  employee is assigned on Time Table
        $teacherSubject=SubjectTeacher::where('employee_id',$employeeId)->first();
        if($teacherSubject){
            array_push($dependencies,[
                'name'=>'Academics / Manage Time Table',
                'link'=>url('academics/timetable/manage')
            ]);
        }
        $timeTable=TimeTable::where('teacher',$employeeId)->first();
        if($timeTable){
            array_push($dependencies,[
                'name'=>'Timetable Periods',
                'link'=>url('academics/timetable/timetable')
            ]);
        }

        $issuedBook=IssueBook::where('holder_id',$userId)->where('status','!=',4)->first();
        if($issuedBook){
            array_push($dependencies,[
               'name'=>'Book Issued',
               'link'=>url('library/library-borrow-transaction/borrower')

            ]);
        }
        $salary_assign=SalaryAssign::where('emp_id',$employeeId)->first();
        if($salary_assign){
            $salary_assign->delete();
        }
        $leaveApplication=EmployeeLeaveApplication::where('employee_id',$employeeId)->where('status',2)->first();
        if($leaveApplication){
            array_push($dependencies,[
               'name'=>'Leave Application',
               'link'=>url('manage/leave/application')
            ]);
        }

        return $dependencies;
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required',
            'institute_id' => 'required',
        ]);
        
        $dependencies = $this->employeeDependenciesCheck($request->employee_id);
        if (sizeof($dependencies)>0) {
            Session::flash('dependencies', $dependencies);
            return redirect()->back();
        }
        
        // Transfer Start
        $fromDate = null;
        $toDate = Carbon::parse($request->transfer_date);
        $employee = EmployeeInformation::findOrFail($request->employee_id);
        $employeeStatus = ($employee->currentStatus())?$employee->currentStatus()->status:null;
        if ($employeeStatus) {
            if ($employeeStatus->category != 1) {
                Session::flash('errorMessage', 'Employee is not Active, can not transfer!');
                return redirect()->back();
            }
        }

        $prevHistory = EmployeeTransferHistory::where('employee_id', $employee->id)->orderBy('id', 'DESC')->first();
        DB::beginTransaction();
        try {
            if (!$prevHistory) {
                if ($employee->doj) {
                    if (Carbon::parse($employee->doj)<=Carbon::now()) {
                        $fromDate = Carbon::parse($employee->doj);
                    }
                }
                $prevHistory = EmployeeTransferHistory::create([
                    'employee_id' => $employee->id,
                    'campus_id' => $employee->campus_id,
                    'institute_id' => $employee->institute_id,
                    'designation_id' => ($employee->singleDesignation)?$employee->singleDesignation->id:null,
                    'from' => $fromDate,
                    'created_by' => Auth::id()
                ]);
            }
            if($prevHistory->from){
                if($prevHistory->from > $toDate){
                    Session::flash('errorMessage', 'Can not transfer to a back date!');
                    return redirect()->back();
                }
            }
            $prevHistory->update([
                'to' => $toDate,
                'updated_by' => Auth::id()
            ]);
            EmployeeTransferHistory::create([
                'employee_id' => $employee->id,
                'campus_id' => $request->campus_id,
                'institute_id' => $request->institute_id,
                'designation_id' => ($employee->singleDesignation)?$employee->singleDesignation->id:null,
                'from' => $toDate->addDay(),
                'created_by' => Auth::id()
            ]);
            $employee->update([
                'campus_id' => $request->campus_id,
                'institute_id' => $request->institute_id,
                'position_serial' => 0,
                'updated_by' => Auth::id()
            ]);
            DB::table('user_institution_campus')->where('user_id', $employee->singleUser->id)->update([
                'campus_id' => $request->campus_id,
                'institute_id' => $request->institute_id,
            ]);

            DB::commit();
            Session::flash('success', 'Employee transfered successfully!');
        } catch (\Throwable $th) {
            DB::roolback();
            Session::flash('success', $th);
        }
        // Transfer End

        return redirect(url('/employee/manage'));
    }

    
    public function show($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        if (!isCurrentCampus($employeeInfo)) {
            return abort(404);
        }
        $institutes = Institute::where('id', '!=', $employeeInfo->institute_id)->get();
        return view('employee::pages.profile.modal.make-transfer', compact('employeeInfo', 'institutes'));
    }

    
    public function edit($id)
    {
        return view('employee::edit');
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }

    public function getCampusFromInstitute(Request $request)
    {
        $campuses = Institute::findOrFail($request->employee_id)->campus();
        return $campuses;
    }
}
