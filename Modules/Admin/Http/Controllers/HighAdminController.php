<?php

namespace Modules\Admin\Http\Controllers;


use App\Address;
use App\Helpers\UserAccessHelper;
use App\Subject;
use App\User;
use App\Models\Role;
use App\RoleUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\SessionHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\division;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Http\Controllers\DivisionController;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeStatus;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\State;
use Modules\Setting\Entities\InstituteAddress;
use App\UserInfo;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Http\Controllers\AttendanceUploadController;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Employee\Http\Controllers\NationalHolidayController;
use Modules\Employee\Http\Controllers\WeekOffDayController;
use Modules\Setting\Http\Controllers\CampusController;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;
use Modules\Student\Http\Controllers\StudentController;

class HighAdminController extends Controller
{

    private $user;
    private $role;
    private $studentProfileView;
    private $roleUser;
    private $sessionHelper;
    private $academicHelper;
    private $validator;
    private $state;
    private $instituteAddress;
    private $attendanceUploadController;
    private $attendanceUpload;
    private $holidayController;
    private $weekOffDayController;
    private $attendanceReportController;
    private $userInfo;
    use UserAccessHelper;

    public function __construct(User $user, Role $role, RoleUser $roleUser, SessionHelper $sessionHelper, AcademicHelper $academicHelper, Validator $validator, State $state, InstituteAddress $instituteAddress, AttendanceUploadController $attendanceUploadController, AttendanceUpload $attendanceUpload, NationalHolidayController $holidayController, WeekOffDayController $weekOffDayController, StudentAttendanceReportController $attendanceReportController, UserInfo $userInfo, StudentProfileView $studentProfileView)
    {
        $this->user = $user;
        $this->role = $role;
        $this->roleUser = $roleUser;
        $this->sessionHelper = $sessionHelper;
        $this->academicHelper = $academicHelper;
        $this->validator = $validator;
        $this->state = $state;
        $this->instituteAddress = $instituteAddress;
        $this->attendanceUploadController = $attendanceUploadController;
        $this->attendanceUpload = $attendanceUpload;
        $this->holidayController = $holidayController;
        $this->weekOffDayController = $weekOffDayController;
        $this->attendanceReportController = $attendanceReportController;
        $this->userInfo = $userInfo;
        $this->studentProfileView = $studentProfileView;
    }


    public function highDashboardStatics()
    {
        $allInst = Institute::all();
        $type = CadetPerformanceType::whereIn('id', ['6', '1'])->get();
        return view('admin::pages.manage-uno.pieChart', compact('allInst', 'type'))->with('page', 'institute');
    }

    public function getHighDashboardCadetRegister()
    {
        $semesters = Semester::all();
        $examNames = ExamName::all();
        $subjects = Subject::all();
        $allInst = Institute::all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $academicLevel = AcademicsLevel::all();

        $type = CadetPerformanceType::whereIn('id', ['6', '1'])->get();
        return view('admin::pages.manage-uno.cadetRegister', compact('academicYears', 'semesters', 'examNames', 'subjects',
            'allInst', 'type', 'academicLevel'))
            ->with
            ('page', 'cadet');

    }

    public function getHighDashboardHrRegister(Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'employee/manage']);

        // campus and institute id
        $institutes = Institute::all();
        // employee departments
        $statuses = EmployeeStatus::all();
        $allDepartments = EmployeeDepartment::orderBy('name', 'ASC')->get();
        $allDesignations = EmployeeDesignation::orderBy('name', 'ASC')->get();
        $institute = Institute::all();
        $depertments = EmployeeDepartment::all();
        $designations = EmployeeDesignation::all();

        return view('admin::pages.manage-uno.hrRegister', compact('institutes', 'statuses', 'allDepartments', 'pageAccessData', 'allDesignations'))
            ->with
            ('page', 'hr');
        return $designations;
    }


    public function getAjaxInstituteCampus($id)
    {
        $academicYear = Campus::where('institute_id', $id)->get();
        $data = [];
        $houseOption = [];
        $houses = House::where('institute_id', $id)->get();
        // return gettype($houses);
        // $houses=$houses->count();
        if ($academicYear->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($academicYear as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->name . '</option>');
            }
        }
        if ($houses->count() > 0) {
            array_push($houseOption, '<option value="">-- Select --</option>');
            foreach ($houses as $item) {
                array_push($houseOption, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->name . '</option>');
            }


        }

        $p['house'] = $houseOption;
        $p['campus'] = $data;
        return json_encode($p);
    }

    public function getAjaxAcademicYear($id)
    {
        $academicYear = AcademicsYear::where('campus_id', $id)->get();
        $data = [];
        if ($academicYear->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($academicYear as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->year_name . '</option>');
            }
        }

        return json_encode($data);
    }

    public function getAjaxAcademicDivision($id)
    {
        $academicYear = division::where('campus', $id)->get();
        $data = [];
        if ($academicYear->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($academicYear as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->name . '</option>');
            }
        }

        return json_encode($data);
    }

    public function getAjaxAcademicBatch($id)
    {
        $academicYear = Batch::where('academics_level_id', $id)->get();
        $data = [];
        if ($academicYear->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($academicYear as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->batch_name . '</option>');
            }
        }

        return json_encode($data);
    }

    public function getAjaxAcademicSection($id)
    {
        $academicYear = Section::where('batch_id', $id)->get();
        $data = [];
        if ($academicYear->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($academicYear as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->section_name . '</option>');
            }
        }

        return json_encode($data);
    }

    //Develop by Dev9


    public function searchHr(Request $request)
    {
        //return $request->all();
        /// return  gettype($request->dor_start);
        //return Carbon::today();
        // return  Carbon::parse($request->dor_start)->format('Y-m-d');



        $child_count=$request->input('child_count');


        $statuses = $request->statuses;
        $allSearchInput = [];
        $institutes = $request->inst;
        $category=null;
        if ($request->category){
            if($request->category=="one") {
                $category=null;
                $allSearchInput['category']=1;
            }else{
                $category=12;
            }

        }
        //return $allSearchInput;
        if ($request->job_duration) {

            $duration = $request->job_duration;

            $month = $duration[1] | 0;
            $year = $duration[0] | 0;
            $day = 365 * $year + $month * 30;

        }
        if ($request->previous_exp) {

            $duration = $request->previous_exp;

            $month = $duration[1] | 0;
            $year = $duration[0] | 0;
            $prev_exp = 365 * $year + $month * 30;
        }
        if ($prev_exp == 0) $prev_exp = null;

        if ($day == 0) $day = null;
        if ($request->contact_no) $phone = $request->contact_no; else $phone = null;
        if ($request->email) $email = $request->email; else $email = null;
        if ($request->emp_id) $employee_number = $request->emp_id; else $employee_number = null;
        if ($request->designation) ($designation = $request->designation); else $designation = null;
        if ($request->blood_group) ($allSearchInput['blood_group'] = $request->blood_group);
        if ($request->marital_status) ($allSearchInput['marital_status'] = $request->marital_status);
        if ($request->gender) ($allSearchInput['gender'] = $request->gender);
        if ($request->medical_category) $medical_category=$request->medical_category; else $medical_category=null;
        if ($request->central_position_serial) ($allSearchInput['central_position_serial'] = $request->central_position_serial);
        if ($request->religion) ($allSearchInput['religion'] = $request->religion);
        //return $allSearchInput;

        if ($request->department) ($department = $request->department); else $department = null;
        if ($request->employee_name) ($name = $request->employee_name); else $name = null;
        if ($request->permanent_address)
            ($permanent_address = $request->permanent_address);
        else
            $permanent_address = null;
        ($request->present_address) ? ($present_address = $request->present_address) : ($present_address = null);

        //if($request->)
        if (!$institutes) $institutes = Institute::all()->pluck('id');
        $r_start = $request->dor_start;
        $r_end = $request->dor_end;
        if ($r_end && $r_start) {

            $reteirment_array['start'] = $r_start;
            $reteirment_array['end'] = $r_end;
        } else {

            $reteirment_array = null;
        }
        if ($request->bd_start && $request->bd_end) {
            $birth_array['start'] = $request->bd_start;
            $birth_array['end'] = $request->bd_end;
            //return $birth_array;
        } else {
            $birth_array = null;
        }
        if ($request->doj_start && $request->doj_end) {
            $join_array['start'] = $request->doj_start;
            $join_array['end'] = $request->doj_end;
        } else $join_array = null;

//Store the namewise search data
        if ($name) {
            $nameWise = DB::table('hr_manage_view')->where('Name', 'LIKE', '%' . $name . '%')->get()->pluck('hr_id')->toArray();

        } else $nameWise = null;

        $hrEmployees =
            EmployeeInformation::
            with(['permanentAdd','singleUser','employeeManageView', 'previousExperience', 'presentAdd', 'currentStatusSingle'])
                ->whereIn('institute_id', $institutes)
            ->when($designation, function ($query, $designation) {
                $query->whereIn('designation', $designation);
            })->when($category,function ($query,$category){
                $query->where('category','!=',1);
            })
            ->when($department, function ($query, $department) {
                $query->whereIn('department', $department);
            })->when($reteirment_array, function ($query, $reteirment_array) {
                $query->where('dor', '>', Carbon::parse($reteirment_array['start'])->format('Y-m-d'))->where('dor', '<',
                    Carbon::parse($reteirment_array['end']));
            })->when($birth_array, function ($query, $birth_array) {
                $query->where('dob', '>', Carbon::parse($birth_array['start'])->format('Y-m-d'))->where('dob', '<',
                    Carbon::parse($birth_array['end']));
            })->when($join_array, function ($query, $join_array) {
                $query->where('dor', '>', Carbon::parse($join_array['start'])->format('Y-m-d'))->where('dor', '<',
                    Carbon::parse($join_array['end']));

            })->when($phone, function ($query, $phone) {
                $query->where('phone', 'LIKE', '%' . $phone . '%')->orWhere('alt_mobile', 'LIKE', '%' . $phone . '%');

            })->when($email, function ($query, $email) {
                $query->where('email', 'LIKE', '%' . $email . '%')->orWhere('alt_mobile', 'LIKE', '%' . $email . '%');

            })->when($medical_category, function ($query, $medical_category) {
                    $query->where('medical_category', 'LIKE', '%' . $medical_category . '%');

                })
            ->when($day, function ($query, $day) {
                $query->where('doj', '<', Carbon::now()->subDays($day));

            })->when($permanent_address, function ($query, $permanent_address) {
                $query->whereHas('presentAdd', function ($query_new) use ($permanent_address) {
                    $query_new->where('address', 'LIKE', '%' . $permanent_address . '%');

                });
            })
            ->when($employee_number, function ($query, $employee_number) {

                $query->whereHas('employeeManageView',function ($qN) use ($employee_number){
                   $qN->where('employee_id','LIKE','%'.$employee_number.'%');
                });
            })
            ->when($present_address, function ($query, $present_address) {
                $query->whereHas('presentAdd', function ($query_new) use ($present_address) {
                    $query_new->where('address', 'LIKE', '%' . $present_address . '%');

                });
            })
            ->where($allSearchInput)


            ->get();

        if ($statuses) {
            $filteredByStatus = [];
            foreach ($hrEmployees as $employee) {

                if ($employee->currentStatusSingle && sizeof($employee->currentStatusSingle) > 0) {
                    // return $employee;
                    // return gettype($employee->currentStatusSingle);
                    $stat = $employee->currentStatusSingle->first()->status_id;
                    //return $statuses;
                    if (in_array($stat, $statuses)) {
                        array_push($filteredByStatus, $employee->id);
                    }


                }
                //return $employee->currentStatusSingle;
            }

            $hrEmployees = $hrEmployees->whereIn('id', $filteredByStatus);
            //return $filteredByStatus;


        }
        if ($prev_exp) {
            $filteredByExp = [];
            foreach ($hrEmployees as $employee) {
                if ($employee->previousExperience && sizeof($employee->previousExperience) > 0) {
                    $totalExp = 0;
                    foreach ($employee->previousExperience as $exp) {

                        $endDate = Carbon::parse($exp->experience_to_date);
                        $start = Carbon::parse($exp->experience_from_date);


                        $exp = $start->diff($endDate)->days;
                        $totalExp += $exp;

                    }
                    //return $totalExp;
                    if ($prev_exp <= $totalExp) {
                        array_push($filteredByExp, $employee->id);
                    }
                }


            }
            $hrEmployees = $hrEmployees->whereIn('id', $filteredByExp);

        }
        if ($nameWise) {
          $hrEmployees= $hrEmployees->whereIn('id', $nameWise);
        }
        $empkey=$hrEmployees->pluck('user_id');
        $empkeySort=User::whereIn('id',$empkey)->get()->sortBy('username')->pluck('id');

        $allEmployee=$hrEmployees->keyBy('user_id');
        $pageAccessData = self::linkAccess($request, ['manualRoute' => "employee/manage"]);
        $allSearchInputs=$allSearchInput;
        return view('employee::pages.includes.teacher-list', compact('child_count','empkeySort','allEmployee', 'pageAccessData', 'allSearchInputs'));

        return $hrEmployees;

        return $hrEmployees->where('full_name', 'LIKE', '%');
    }

    public function getAjaxAcademicLevel($id)
    {
        $academicLevel = $this->academicHelper->getAllAcademicLevel();
        $data = [];
        if ($academicLevel->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($academicLevel as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->level_name . '</option>');
            }
        }

        return json_encode($data);
    }

    public function searchFeesSkill($allInfo, $skill, $fees, $institute)
    {
        if ($skill) {
            $skilled_student = CadetAssesment::where('type', 30)->where('remarks', 'like', '%' . $skill . '%')->pluck
            ('student_id');
        } else {
            $skilled_student = null;
        }
        if ($skilled_student && $fees) {

            return StudentInformation::with('fees')->where($allInfo)->whereIn('institute', $institute)
                ->where($allInfo)->whereIn('id',
                    $skilled_student)
                ->whereHas('fees', function ($query) use ($fees) {
                    $query->where('tution_fees', '=', $fees);

                })->pluck('user_id');
        } else if ($skilled_student && !$fees) {
            return StudentInformation::with('fees')->where($allInfo)->whereIn('institute', $institute)
                ->where($allInfo)->whereIn('id', $skilled_student)->get()->pluck('user_id');
        } else if (!$skilled_student && $fees) {
            return StudentInformation::with('fees')->where($allInfo)->whereIn('institute', $institute)
                ->whereHas('fees', function ($query) use ($fees) {
                    $query->where('tution_fees', '=', $fees);

                })->get()->pluck('user_id');
        } else {
            return StudentInformation::with('fees')->where($allInfo)->whereIn('institute', $institute)->get()->pluck('user_id');
        }


    }
    // Basically a helper function to get the Parents wise data search .Pass Request to this it will return a array with student or null value
    public function getParentWiseData($request){
        $phone=$request->phone;
        $first_name=$request->first_name;
        $last_name=$request->last_name;
        $father_first_name=$request->father_name;
        $mother_first_name=$request->mother_name;
        $mother_last_name=$request->mother_last_name;
        $father_last_name=$request->father_last_name;
        $father_occupation=$request->father_occupation;
        $mother_occupation=$request->mother_occupation;
        $parentStudentData=null;
        if( $phone|| $father_first_name || $first_name || $last_name || $father_last_name || $mother_first_name ||
            $mother_last_name ||
            $father_occupation
            || $mother_occupation){
            $parentStudentData=StudentProfileView::with('studentParents.singleGuardian')
                ->when($father_first_name,function ($query,$father_first_name){
                    $query->whereHas('studentParents.singleGuardian',function ($queryOne) use ($father_first_name){
                        $queryOne->where('type',1)->where('first_name','LIKE','%'.$father_first_name.'%');
                    });
                })
                ->when($father_last_name,function ($query,$father_last_name){
                    $query->whereHas('studentParents.singleGuardian',function ($queryOne) use ($father_last_name){
                        $queryOne->where('type',1)->where('last_name','LIKE','%'.$father_last_name.'%');
                    });
                })
                ->when($mother_first_name,function ($query,$mother_first_name){
                    $query->whereHas('studentParents.singleGuardian',function ($queryOne) use ($mother_first_name){
                        $queryOne->where('type',0)->where('first_name','LIKE','%'.$mother_first_name.'%');
                    });
                })
                ->when($mother_last_name,function ($query,$mother_last_name){
                    $query->whereHas('studentParents.singleGuardian',function ($queryOne) use ($mother_last_name){
                        $queryOne->where('type',0)->where('first_name','LIKE','%'.$mother_last_name.'%');
                    });
                })
                ->when($father_occupation,function ($query,$father_occupation){
                    $query->whereHas('studentParents.singleGuardian',function ($queryOne) use ($father_occupation){
                        $queryOne->where('type',1)->where('occupation','LIKE','%'.$father_occupation.'%');
                    });
                })
                ->when($mother_occupation,function ($query,$mother_occupation){
                    $query->whereHas('studentParents.singleGuardian',function ($queryOne) use ($mother_occupation){
                        $queryOne->where('type',0)->where('occupation','LIKE','%'.$mother_occupation.'%');
                    });
                })->when($first_name,function ($q,$first_name){
                    $q->where('first_name','LIKE','%'.$first_name.'%');
                })->when($last_name,function ($q,$last_name){
                    $q->where('last_name','LIKE','%'.$last_name.'%');
                })->when($phone ,function ($q,$phone){
                    $q->whereHas('studentParents.singleGuardian',function ($q1) use ( $phone){
                        $q1->where('is_guardian',1)->where('mobile','LIKE','%'.$phone.'%');
                    });
                })
                ->pluck('std_id')->toArray();
        }

        return $parentStudentData;
    }


    public function cadetRegisterSearch(Request $request)
    {
        $parentWiseData=$this->getParentWiseData($request);
       // return $parentWiseData;

        $start = microtime(true);
        $status = $request->input('status');
        if ($status == null) {
            $status = 1;
        }
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $tution_fees = $request->tuition_fees;
        $tutionFeesWiseStudent = null;
        if ($tution_fees != null) {
            $tutionFeesWiseStudent = StudentEnrollment::where([

                'tution_fees' => $tution_fees
            ])->pluck('std_id');

        }

        $campus_selected = $request->campusId;
        $institute_selected = $request->inst;


        $searchExamwise = array();
        $exam_id = $request->examId;
        $subject_id = $request->subjectId;
        $academic_year_id = $request->academic_year;
        $semester_id = $request->tremId;
        $on_100 = $request->marks;
        $criteriaId = $request->criteriaId;
        $topCadet = $request->topCadet;
        $checkingParamter = $request->checkingParamter;
        $batch = $request->input('batch');


        if ($exam_id) $searchExamwise['exam_id'] = $exam_id;
        if ($subject_id) $searchExamwise['subject_id'] = $subject_id;
        if ($academic_year_id) $searchExamwise['academic_year_id'] = $academic_year_id;
        if ($semester_id) $searchExamwise['semester_id'] = $semester_id;
        if ($batch) $searchExamwise['batch_id'] = $batch;


        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $catType = $request->cattype;
        $categoryID = $request->categoryID;
        $categoryActivity = $request->categoryActivity;
        $blood_group = $request->blood_group;
        $religion = $request->religion;
        $pressentAddress = $request->pressentAddress;
        $permanentAddress = $request->permanentAddress;
        $phone = $request->phone;
        $batch_no = $request->batch_no;
        $batch = $request->input('batch');
        // qry
        $searchData = [];
        $allSearchInputs = array();
        $allSearchInformation = array();
        $allPressentAddress = array();
        // checking return type
        /*        if ($returnType == "json") {
                    // input institute and campus id
                    if ($campus_selected) {
                        $allSearchInputs['campus'] = $campus_selected;
                    }
                    if ($institute_selected) {
                        $allSearchInputs['institute'] = $institute_selected;

                    }
                } else {
                    // input institute and campus id
                    if ($campus_selected) {
                        $allSearchInputs['campus'] = $campus_selected;
                    }
                    if ($institute_selected) {
                        $allSearchInputs['institute'] = $institute_selected;

                    }
                }*/
        // return $allSearchInputs;
        //return StudentProfileView::where($allSearchInputs)->get();

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;
        // check grNo
        if ($grNo) $allSearchInputs['gr_no'] = $grNo;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        if ($religion) $allSearchInputs['religion'] = $religion;
        // check email
        if ($blood_group) $allSearchInformation['blood_group'] = $blood_group;
        if ($batch_no) $allSearchInformation['batch_no'] = $batch_no;
        $campus_selected = $request->campusId;
        $institute_selected = $request->inst;

        if (!$institute_selected) {
            $institute_selected = Institute::all()->pluck('id');
        }

        $skill = $request->skill;
        //return $allSearchInformation;
        $student_information = $this->searchFeesSkill($allSearchInformation, $skill, $tution_fees,
            $institute_selected);


        //  return ['status' => 'success', 'msg' => 'Student List found', 'html' => $student_information];

        if ($semester_id && $subject_id) {
            ExamMark::where($searchExamwise)->where('total_mark', $on_100)->get();


            if ($criteriaId) {

                $examMarks = ExamMark::whereIn('institute_id', $institute_selected)->where($searchExamwise)->orderBy('total_mark', 'DESC')->get();
                // return $examMarks;

                $student_id = [];
                $mark_student = [];

                foreach ($examMarks as $examMark) {
                    // return json_decode($examMark->breakdown_mark,true);
                    foreach (json_decode($examMark->breakdown_mark) as $key => $mark) {
                        $std_id = (string)$examMark->student_id;
                        //return gettype($std_id);
                        $mark = (int)$mark;
                        //return  gettype($mark);
                        if ($criteriaId == $key) {
                            if ($checkingParamter && $on_100) {
                                if ($checkingParamter == '>') {
                                    if ($mark < $on_100) {
                                        $mark_student[$std_id] = $mark;

                                        array_push($student_id, $examMark->student_id);
                                    }

                                } elseif ($checkingParamter == '<') {
                                    if ($mark > $on_100) {
                                        $mark_student[$std_id] = $mark;
                                        array_push($student_id, $examMark->student_id);
                                    }
                                } else {
                                    if ($mark == $on_100) {
                                        $mark_student[$std_id] = $mark;
                                        array_push($student_id, $examMark->student_id);
                                    }
                                }
                            } else {
                                $mark_student[$std_id] = $mark;

                                array_push($student_id, $examMark->student_id);
                            }

                        }

                    }


                }//end of iterating all student marks
                //  return gettype($mark_student);
                // arsort($mark_student);

                arsort($mark_student);
                $student_id = [];

                foreach ($mark_student as $key => $value) {


                    array_push($student_id, $key);
                }

                //return $student_id;

            } else {
                if ($checkingParamter && $on_100) {
                    $student_id = ExamMark::whereIn('institute_id', $institute_selected)->where($searchExamwise)
                        ->where('total_mark',
                            $checkingParamter, $on_100)->orderBy('total_mark', 'DESC')->pluck('student_id');
                } else if ($on_100) {
                    $student_id = ExamMark::whereIn('institute_id', $institute_selected)->where($searchExamwise)->where('total_mark', $on_100)->orderBy
                    ('total_mark', 'DESC')->pluck('student_id');
                } else if ($checkingParamter && $on_100) {
                    $student_id = ExamMark::whereIn('institute_id', $institute_selected)->where($searchExamwise)->where('total_mark', $checkingParamter, $on_100)->orderBy('total_mark', 'DESC')->pluck('student_id');

                } else {

                    $student_id = ExamMark::whereIn('institute_id', $institute_selected)->where($searchExamwise)->orderBy('total_mark', 'DESC')->pluck('student_id');

                }
                // return gettype($student_id);

                $student_id = $student_id->toArray();
                //return $student_id;
                //  return $searchExamwise;


            }
            // return $student_id;
            $user_id = StudentProfileView::whereIn('std_id', $student_id)
                ->when($parentWiseData,function ($query,$parentWiseData){
                    $query->whereIn('std_id',$parentWiseData);
                })
                ->where('status', $status)->pluck('user_id');
            //return $user_id;
            $filteredStudent = $this->seacrhCadetWithExam($request, $user_id);

            $newSerial = [];
            foreach ($student_id as $key => $value) {
                if (in_array($value, $filteredStudent->toArray())) {
                    array_push($newSerial, $value);
                }
            }
            //return $newSerial;

            $student_id = $newSerial;
            $temp = [];
            if (isset($student_id)) {

                /* if ($topCadet != 0) {
                     $count = 0;
                     foreach ($student_id as $key => $value) {

                         if ($count == $topCadet) break;
                         $count++;
                         array_push($temp, $value);

                     }
                     $student_id = $temp;

                 }*/
                // return $student_id;
                //  return $filteredStudent;


                $searchData = $this->studentProfileView->
                with('singleLevel','singleEnroll','singleSection','singleBatch','studentParents.singleGuardian','roomStudent','academicYear', 'singleStudent')

                    ->whereIn('institute', $institute_selected)
                    ->where('status',
                        $status)->whereIn('std_id', $student_id)->get()->keyBy('std_id');


            } else {
                $searchData = null;
            }
            $houses = House::where([

                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->get()->keyBy('id');
            //return count($searchData);

            $stdListView = view('student::pages.includes.student-list', compact('pageAccessData', 'topCadet', 'status', 'pageAccessData', 'searchData', 'student_id', 'houses'))->render();
            return ['status' => 'success', 'msg' => 'Studentss List found', 'html' => $stdListView];
            //return $searchData;
        }

        // if($pressentAddressUserId) $allSearchInputs[]

        if ($phone) {
            $studentPhone = StudentGuardian::where([
                'mobile' => $phone
            ])->first();
        }
       // return $studentPhone;


        if (isset($studentPhone)) {
            $studentId = StudentParent::where('gud_id', $studentPhone->id)->first();

            $allSearchInputs['std_id'] = $studentId->std_id;
        }

        if ($pressentAddress) {
            $pressentAllStduntId = Address::where('type', "STUDENT_PRESENT_ADDRESS")->where('address', 'LIKE', "%{$pressentAddress}%")->pluck('user_id');
        }
        if ($permanentAddress) {
            $permanentAllStduntId = Address::where('type', "STUDENT_PERMANENT_ADDRESS")->where('address', 'LIKE', "%{$permanentAddress}%")->pluck('user_id');
        }

        //    return $this->studentProfileView->get();
        // return $allSearchInputs;
        if ($catType != null && $categoryID == null && $categoryActivity == null) {
            $val = 0;
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            //            return \response()->json($searchData);
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('student_id', $cated->std_id)->first();


                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
            //            return \response()->json($searchData);
        } elseif ($catType != null && $categoryID != null && $categoryActivity == null) {
            $val = 0;
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')
                    ->where
                    ($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('performance_category_id', $categoryID)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } elseif ($catType != null && $categoryID != null && $categoryActivity != null) {
            $val = 0;
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('performance_category_id', $categoryID)
                    ->where('cadet_performance_activity_id', $categoryActivity)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } else {
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            //  return \response()->json($allSearchInformation);
        }
        $parentData=$this->getParentWiseData($request);
        if($parentData){
            $searchData= $searchData->whereIn('std_id',$parentData);
            //echo $searchData;
        }
        $searchstudentData=$searchData->pluck('std_id')->toArray();
        $searchData=StudentProfileView::
        with('singleLevel','singleEnroll','singleSection','singleBatch','studentParents.singleGuardian','roomStudent','academicYear', 'singleStudent')
            ->whereIn('std_id',$searchstudentData)->get();
       // return $searchData;
        //return $searchData->whereIn('institute',$institute_selected);

        if ($request->house) {

            $houseWiseStdId = RoomStudent::where([
                'house_id' => $request->house
            ])->pluck('student_id');
            $searchData = $searchData->whereIn('std_id', $houseWiseStdId);
        }



        $houses = House::where([

            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');
        //return  $searchData;
        // return \response()->json($allSearchInputs);


//
//                $stdListView = view('student::pages.includes.student-list', compact('pageAccessData', 'searchData', 'houses'))->render();
        //           return ['status' => 'success', 'msg' => 'Student List found', 'html' => $searchData];
        $status = $request->input('status');
        if ($status == null) {
            $status = 1;
        }
        //return $student_id;
        if (!isset($student_id)) {

            $searchData=$searchData->sortBy('username');
            $student_id = $searchData->pluck('std_id')->toArray();
            $searchData = $searchData->keyBy('std_id');


        }
   ;
        $end = microtime(true);
        $total_time = $end - $start;
        // return $total_time;
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $st=microtime(false);
        $st=microtime(true);
        $stdListView = view('student::pages.includes.student-list',
            compact('pageAccessData', 'status', 'searchData', 'student_id', 'houses'))->render();
        $en=microtime(true);
       // return $en-$st;
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    public function globalSearch(Request $request)
    {
        $status = $request->input('status') | 1;
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $tution_fees = $request->tuition_fees;
        $searchExamwise = array();
        //store Exam wise data
        $exam_id = $request->examId;
        $subject_id = $request->subjectId;
        $academic_year_id = $request->academic_year;
        $semester_id = $request->tremId;
        $on_100 = $request->marks;
        $criteriaId = $request->criteriaId;
        $topCadet = $request->topCadet;
        $checkingParamter = $request->checkingParamter;
        $batch = $request->input('batch');


        //Store Other Data
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $catType = $request->cattype;
        $categoryID = $request->categoryID;
        $categoryActivity = $request->categoryActivity;
        $blood_group = $request->blood_group;
        $religion = $request->religion;
        $pressentAddress = $request->pressentAddress;
        $permanentAddress = $request->permanentAddress;
        $phone = $request->phone;
        $batch_no = $request->batch_no;
        $batch = $request->input('batch');


    }


    public function seacrhCadetWithExam($request, $examData)
    {

        $tution_fees = $request->tuition_fees;

        $tutionFeesWiseStudent = null;
        if ($tution_fees != null) {
            $tutionFeesWiseStudent = StudentEnrollment::where([

                'tution_fees' => $tution_fees
            ])->pluck('std_id');

        }


        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $catType = $request->cattype;
        $categoryID = $request->categoryID;
        $categoryActivity = $request->categoryActivity;
        $blood_group = $request->blood_group;
        $religion = $request->religion;
        $pressentAddress = $request->pressentAddress;
        $permanentAddress = $request->permanentAddress;
        $phone = $request->phone;
        $batch_no = $request->batch_no;
        $batch = $request->input('batch');


        // qry
        $searchData = [];
        $allSearchInputs = array();
        $allSearchInformation = array();
        $allPressentAddress = array();


        // check grNo
        if ($grNo) $allSearchInputs['gr_no'] = $grNo;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        if ($religion) $allSearchInputs['religion'] = $religion;
        $status = $request->input('status');
        if ($status == null) {
            $status = 1;
        }
        $allSearchInputs['status'] = $status;
        // check email
        if ($blood_group) $allSearchInformation['blood_group'] = $blood_group;
        if ($batch_no) $allSearchInformation['batch_no'] = $batch_no;
        $campus_selected = $request->campusId;
        $institute_selected = $request->inst;
        if (!$institute_selected) {
            $institute_selected = Institute::all()->pluck('id');
        }
        if ($campus_selected) {
            $allSearchInformation['campus'] = $campus_selected;
        }

        // return $allSearchInformation;
        $student_information = $this->searchFeesSkill($allSearchInformation, $request->skill, $tution_fees,
            $institute_selected);


        if ($phone) {
            $studentPhone = StudentGuardian::where([
                'mobile' => $phone
            ])->first();
        }

        if (isset($studentPhone)) {
            $studentId = StudentParent::where('gud_id', $studentPhone->id)->first();

            $allSearchInputs['std_id'] = $studentId->std_id;
        }

        if ($pressentAddress) {
            $pressentAllStduntId = Address::where('type', "STUDENT_PRESENT_ADDRESS")->where('address', 'LIKE', "%{$pressentAddress}%")->pluck('user_id');
        }
        if ($permanentAddress) {
            $permanentAllStduntId = Address::where('type', "STUDENT_PERMANENT_ADDRESS")->where('address', 'LIKE', "%{$permanentAddress}%")->pluck('user_id');
        }

        //    return $this->studentProfileView->get();
        //return $allSearchInputs;
        if ($catType != null && $categoryID == null && $categoryActivity == null) {

            $val = 0;
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            //            return \response()->json($searchData);
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('student_id', $cated->std_id)->first();


                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
            //            return \response()->json($searchData);
        } elseif ($catType != null && $categoryID != null && $categoryActivity == null) {

            $val = 0;
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('performance_category_id', $categoryID)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } elseif ($catType != null && $categoryID != null && $categoryActivity != null) {

            $val = 0;
            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {
                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('performance_category_id', $categoryID)
                    ->where('cadet_performance_activity_id', $categoryActivity)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } else {

            if (isset($pressentAllStduntId) && isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($permanentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $permanentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else if (isset($pressentAllStduntId)) {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $pressentAllStduntId)->whereIn('user_id', $student_information)->get();
            } else {

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where
                ($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
            // return \response()->json($searchData);
        }


        if ($request->house) {
            $houseWiseStdId = RoomStudent::where([

                'house_id' => $request->house
            ])->pluck('student_id');
            $searchData = $searchData->whereIn('std_id', $houseWiseStdId);
        }
        $parentData=$this->getParentWiseData($request);
        if($parentData){
            $searchData= $searchData->whereIn('std_id',$parentData);
            //echo $searchData;
        }


        return $searchData->pluck('std_id');


    }

    public function searchcadetData(Request $request)
    {
        $allInst = Institute::all();
        $type = CadetPerformanceType::whereIn('id', ['6', '1'])->get();

        //        dd(session()->get('institute'));
        $institute = $request->inst;
        $campus = $request->campusId;
        $academicYear = $request->year;
        $month = $request->month;
        $academicLevel = $request->levelID;
        $divisionID = $request->divisionId;
        $batch = $request->classID;
        $section = $request->sectionID;
        $catType = $request->cattype;
        $categoryID = $request->categoryID;
        $categoryActivity = $request->categoryActivity;
        $username = $request->std_username;

        $searchData = [];
        $allSearchInputs = array();

        $allSearchInputs['campus'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        if ($institute) $allSearchInputs['institute'] = $institute;

        if ($campus) $allSearchInputs['campus'] = $campus;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        if ($catType != null && $categoryID == null && $categoryActivity == null) {
            $val = 0;
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('institute_id', $institute)
                    ->where('campus_id', $campus)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } elseif ($catType != null && $categoryID != null && $categoryActivity == null) {
            $val = 0;
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('institute_id', $institute)
                    ->where('campus_id', $campus)
                    ->where('performance_category_id', $categoryID)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } elseif ($catType != null && $categoryID != null && $categoryActivity != null) {
            $val = 0;
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
            foreach ($searchData as $cated) {
                $record = CadetAssesment::where('type', $catType)
                    ->where('institute_id', $institute)
                    ->where('campus_id', $campus)
                    ->where('performance_category_id', $categoryID)
                    ->where('cadet_performance_activity_id', $categoryActivity)
                    ->where('student_id', $cated->std_id)->first();

                if ($record == null) {
                    unset($searchData[$val]);
                }
                $val++;
            }
        } else {
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
        }
        //        return json_encode($searchData) ;
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $stdListView = view('admin::pages.manage-uno.partial.cadet-list', compact('pageAccessData', 'searchData'))
            ->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];

        //        return view('admin::pages.manage-uno.cadetRegister',compact('searchData','allInst','type'))->with('page','cadet');

    }

    public function getAjaxTypeCategory($id)
    {
        $data = [];
        array_push($data, '<option value="">-- Select --</option>');
        if ($id == 6) {
            $category = CadetPerformanceCategory::where('id', 19)->get();
        } else {
            $category = CadetPerformanceCategory::where('category_type_id', $id)->get();
        }
        //       $category=CadetPerformanceCategory::where('category_type_id',$id)->get();

        if ($category->count() > 0) {

            foreach ($category as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->category_name . '</option>');
            }
        }
        return json_encode($data);
    }

    public function getAjaxCategoryActivity($id)
    {
        $data = [];

        $activity = CadetPerformanceActivity::where('cadet_category_id', $id)->get();

        if ($activity->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($activity as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->activity_name . '</option>');
            }
        }
        return json_encode($data);
    }
}
