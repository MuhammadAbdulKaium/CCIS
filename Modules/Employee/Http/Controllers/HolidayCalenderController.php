<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeHolidayCalenderAssign;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeLeaveAssign;
use Modules\Employee\Entities\EmployeeLeaveStructure;
use Modules\Employee\Entities\HolidayCalenderCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Employee\Entities\HolidayCalender;

class HolidayCalenderController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }
    
    
    public function index($id = '',Request  $request)
    {
        if (!empty($id)){
            $pageAccessData = self::linkAccess($request, ['manualRoute'=>'employee/holiday-calender']);
        }else {
            $pageAccessData = self::linkAccess($request);
        }

        $holidayCalenderCategory = '';
        if ($id) {
            $holidayCalenderCategory = HolidayCalenderCategory::findOrFail($id);
        }
        $holidayCalenderCategories = HolidayCalenderCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('employee::pages.holiday-calender.index', compact('pageAccessData','holidayCalenderCategory', 'holidayCalenderCategories'));
    }

    
    public function assign()
    {
        $currentYear=$now = Carbon::now()->year;
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = EmployeeDesignation::orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = EmployeeDepartment::orderBy('name', 'ASC')->get();
        // employee Leave Type
        return view('employee::pages.holiday-calender.holiday-assign',compact('allDesignaitons','allDepartments','campusId','currentYear'));
    }
    public function create()
    {
        return view('employee::create');
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
        $calender_year = $request ->input('calender_year');

        $currentYear=$now = Carbon::now();

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
        $holidayCalenders=HolidayCalenderCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();;

        $holidayAssignData=EmployeeHolidayCalenderAssign::where('calender_year',$calender_year)->get()->keyBy('emp_id');
        $searchData=EmployeeInformation::where($allSearchInputs)->get();
        $stdListView = view('employee::pages.holiday-calender.employee-list', compact('holidayCalenders','holidayAssignData','leaveStructure','searchData','duration','leave_type_id','dept_id','designation_id','leave_process_procedure','currentYear','calender_year'))->render();
        return ['status'=>'success', 'msg'=>'Employee List found', 'html'=>$stdListView];

    }
    public function assignSubmitEmployee(Request $request)
    {
        if(count($request['userCheckbox'])>0)
        {
            foreach ($request['userCheckbox'] as $user)
            {
                echo $request['holiday_calender_id'][$user];
                $holidayAssign=new EmployeeHolidayCalenderAssign();
                $holidayAssign->emp_id=$user;
                $holidayAssign->callender_category_id=$request['holiday_calender_id'][$user];
                $holidayAssign->calender_year=$request->calender_year;
                $holidayAssign->created_by=Auth::user()->id;
                $holidayAssign->save();
            }
        }
    }
    
    public function store(Request $request)
    {
        $sameNameCalender = HolidayCalenderCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->get();

        if (sizeOf($sameNameCalender) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a calendar category in this name.');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $calenderCategory = HolidayCalenderCategory::insert([
                    'name' => $request->name,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            
                if ($calenderCategory) {
                    DB::commit();
                    Session::flash('message', 'Success! Holiday Calender category created successfully.');
                    return redirect()->back();
                }else{
                    Session::flash('errorMessage', 'Error creating holiday calender category.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();            
                Session::flash('errorMessage', 'Error creating holiday calender category.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', 'Sorry! Please Input Valid Data.');
            return redirect()->back();
        }
    }

    
    
    public function show($id)
    {
        return view('employee::show');
    }

    
    
    public function edit($id)
    {
        return view('employee::edit');
    }

    
    
    public function update(Request $request, $id)
    {
        $holidayCalender = HolidayCalenderCategory::findOrFail($id);

        $sameNameCalender = HolidayCalenderCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        if ($sameNameCalender) {
            if ($sameNameCalender->id != $holidayCalender->id) {
                Session::flash('errorMessage', 'Sorry! There is already a calendar category in this name.');
                return redirect()->back();
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator) {
            DB::beginTransaction();
            try {          
                $updateHolidayCalender = $holidayCalender->update([
                    'name' => $request->name,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            
                if ($updateHolidayCalender) {
                    DB::commit();
                    Session::flash('message', 'Success! Holiday Calender category updated successfully.');
                    return redirect()->back();
                }else{
                    Session::flash('errorMessage', 'Error updating holiday calender category.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
            
                Session::flash('errorMessage', 'Error updating holiday calender category.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', 'Sorry! Please Input Valid Data.');
            return redirect()->back();
        }
    }

    
    
    public function destroy($id)
    {
        $holidayCalender = HolidayCalender::where('holiday_calender_category_id', $id)->get();
        if (sizeof($holidayCalender) > 0) {
            Session::flash('alert', 'Sorry! Dependencies found.');
            return redirect()->back();
        }else{
            HolidayCalenderCategory::findOrFail($id)->delete();
            Session::flash('message', 'Success! Calender Category Deleted Successfully.');
            return redirect()->back();
        }
    }

    public function calenderSetUP($id)
    {
        $calanderCategory = HolidayCalenderCategory::findOrFail($id);

        return view('employee::pages.holiday-calender.modal.calender-set-up', compact('calanderCategory'));
    }

    public function calenderSearch(Request $request)
    {
        $startDateTime = new Carbon($request->startDate);
        $endDateTime = new Carbon($request->endDate);

        $result = HolidayCalender::where('holiday_calender_category_id', $request->calender_category_id)->whereDate('holiday', '>=', $startDateTime)->whereDate('holiday', '<=', $endDateTime)->get();

        return $result;
    }

    public function calenderSave(Request $request, $id)
    {
        if (isset($request->dates)) {
            DB::beginTransaction();
            try {            
                foreach ($request->dates as $key => $date) {
                    $date = new Carbon($date);
    
                    $calender = HolidayCalender::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'holiday_calender_category_id' => $id
                    ])->whereDate('holiday', $date->format('Y-m-d'))->first();
    
                    if ($calender) {
                        // Update in table
                        if ($request->types[$key]) {
                            $calender->update([
                                'type' => $request->types[$key],
                                'details' => $request->details[$key],
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ]);
                        }else{
                            $calender->delete();
                        }
                    } else {
                        // Insert into table
                        if ($request->types[$key]) {
                            HolidayCalender::insert([
                                'holiday_calender_category_id' => $id,
                                'holiday' => $date,
                                'type' => $request->types[$key],
                                'details' => $request->details[$key],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'campus_id' => $this->academicHelper->getCampus(),
                                'institute_id' => $this->academicHelper->getInstitute()
                            ]);
                        }                    
                    }
                }

                DB::commit();
                Session::flash('message', 'Success! Holiday Calender saved successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error saving Holiday Calender.');
                return redirect()->back();
            }
        }else {
            return redirect()->back();
        }
    }
}
