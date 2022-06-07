<?php

namespace Modules\Student\Http\Controllers;

use App\Address;
use App\Helpers\UserAccessHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ClassSubStudent;
use Modules\Academics\Entities\Section;
use Illuminate\Support\Facades\Log;
use Modules\CadetFees\Entities\CadetFeesAssign;
use Modules\CadetFees\Entities\CadetFeesGenerate;
use Modules\CadetFees\Entities\FeesStructure;
use Modules\CadetFees\Entities\FeesStructureDetails;
use Modules\CadetFees\Entities\StudentFeesCollection;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StudentActivityDirectoryActivity;
use Modules\Student\Entities\StudentActivityDirectoryCategory;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Subject;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\InvoiceFine;
use Modules\Fees\Entities\PaymentExtra;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Reports\Entities\IdCardTemplate;
use Redirect;
use Session;
use App\User;
use Illuminate\Support\Facades\Input;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Student\Entities\StdEnrollHistory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamMarkParameter;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\SubjectMark;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;

class StudentController extends Controller
{

    private $academicHelper;
    private $academicsYear;
    private $studentProfileView;
    private $studentInformation;
    private $feesInvoice;
    private $invoiceFine;
    private $paymentExtra;
    private $invoicePayment;
    private $user;
    private $idCardTemplate;
    private $attendanceUpload;
    private $studentEnrollment;
    private $academicsLevel;
    private $stdEnrollHistory;
    private $classSubStudent;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper, StdEnrollHistory $stdEnrollHistory, AcademicsLevel $academicsLevel, StudentEnrollment $studentEnrollment, AttendanceUpload $attendanceUpload, InvoicePayment $invoicePayment, InvoiceFine $invoiceFine, PaymentExtra $paymentExtra, FeesInvoice $feesInvoice, StudentProfileView $studentProfileView, AcademicsYear $academicsYear, StudentInformation $studentInformation, User $user, IdCardTemplate $idCardTemplate, ClassSubStudent $classSubStudent)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsYear = $academicsYear;
        $this->studentProfileView = $studentProfileView;
        $this->studentInformation = $studentInformation;
        $this->feesInvoice = $feesInvoice;
        $this->invoiceFine = $invoiceFine;
        $this->paymentExtra = $paymentExtra;
        $this->invoicePayment = $invoicePayment;
        $this->user = $user;
        $this->idCardTemplate = $idCardTemplate;
        $this->attendanceUpload = $attendanceUpload;
        $this->studentEnrollment = $studentEnrollment;
        $this->academicsLevel = $academicsLevel;
        $this->stdEnrollHistory = $stdEnrollHistory;
        $this->classSubStudent = $classSubStudent;
    }

    public function ViewDailyDuty()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $academicLevels = $this->academicsLevel->get();
        // all inputs
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        return View('student::pages.scheduled.scheduled-view', compact('academicLevels', 'allInputs'))->with('allEnrollments', null);
    }

    public function searchDutyStudent(Request $request)
    {
        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $catType = $request->cattype;
        $categoryID = $request->categoryID;
        $categoryActivity = $request->categoryActivity;

        $period = CarbonPeriod::create($start_date, $end_date);


        //        $days = $end_date->diffInDays($start_date);

        // qry
        $searchData = [];
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

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
        // check email
        if ($username) $allSearchInputs['username'] = $username;


        // for cat type
        if ($catType != null && $categoryID == null && $categoryActivity == null) {
            $val = 0;
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
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
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
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
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
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
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
            //            return \response()->json($searchData);
        }
        $activities = StudentActivityDirectoryActivity::with('studentActivityDirectoryCategories')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $categories = StudentActivityDirectoryCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $stdListView = view('student::pages.includes.student-daily-scheduled-list', compact('searchData', 'period', 'activities', 'categories'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    public function manageStudent(Request $request)
    {
        $pageAccessData = self::linkAccess($request);

        // Academic year
        $academicYears = $this->academicHelper->getAllAcademicYears();

        $type = CadetPerformanceType::whereIn('id', ['6', '1'])->get();

        $academicLevels = $this->academicsLevel->get();
        $semesters = Semester::all();
        $examNames = ExamName::all();
        $subjects = Subject::all();

        // $personalInfo=$this->studentInformation->where([
        //     'institute_id'=>$this->academicHelper->getInstitute(),
        //     'campus_id'=>$this->academicHelper->getCampus()
        // ])->get();
        // return "ss";
        // all inputs
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        // return
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        // return view with vaiables
        return View('student::pages.student-manage', compact('subjects', 'examNames', 'semesters', 'pageAccessData', 'academicYears', 'academicLevels', 'allInputs', 'type', 'houses'))->with('allEnrollments', null);
    }


    // manage student alumni
    public function manageStudentAlumni()
    {

        $academicYears = $this->academicHelper->getAllAcademicYears();

        $academicLevels = $this->academicsLevel->get();


        //        return "ss";
        // all inputs
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        // return view with vaiables
        return View('student::pages.student-manage-alumni', compact('academicYears', 'academicLevels', 'allInputs'));
    }


    public function searchStudentAlumni(Request $request)
    {

        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');


        // qry
        $allSearchInputs = array();
        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        // define
        //        $allSearchInputs['student_enrollment_history.batch_status'] = 'LEVEL_UP';
        //        $allSearchInputs['student_enrollment_history.batch_status'] = 'LEVEL_UP';


        $alumniStudents = $this->stdEnrollHistory
            ->where($allSearchInputs)
            ->whereIn('batch_status', ['LEVEL_UP', 'GRADUATED'])
            ->paginate(20);

        // std list view maker
        $stdListView = view('student::pages.includes.student-list-alumni', compact('alumniStudents', 'allSearchInputs'))->render();
        // return with variables
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }


    public function manageStudentStatus()
    {

        $academicYears = $this->academicHelper->getAllAcademicYears();

        $academicLevels = $this->academicsLevel->get();


        //        return "ss";
        // all inputs
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        // return view with vaiables
        return View('student::pages.student-manage-status', compact('academicYears', 'academicLevels', 'allInputs'));
    }


    // search student status active or deactive student

    public function searchStudentStatus(Request $request)
    {
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');


        // qry
        $allSearchInputs = array();
        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;


        $deactiveStudents = DB::table('student_enrollments')
            ->join('student_informations', function ($join) {
                $join->on('student_enrollments.std_id', '=', 'student_informations.id')
                    ->where('student_informations.status', '=', 0);
            })->where($allSearchInputs)
            ->paginate(20);


        // std list view maker
        $stdListView = view('student::pages.includes.student-list-deactive', compact('deactiveStudents', 'allSearchInputs'))->render();
        // return with variables
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    public function searchSubject(Request $request)
    {
        // return $request->examidId;
        $sub_id = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $request->examId
        ])->pluck('subject_id');
        return Subject::whereIn('id', $sub_id)->get();
    }
    public function searchSubjectGlobal(Request $request)
    {
        // return $request->examidId;
        $sub_id = ExamMark::where([

            'exam_id' => $request->examId
        ])->pluck('subject_id');
        return Subject::whereIn('id', $sub_id)->get();
    }

    public function searchCriteria(Request $request)
    {

        $subjectMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'subject_id' => $request->subjectId
        ])->first();
        $criteriaId = [];
        foreach (json_decode($subjectMarks->breakdown_mark) as $key => $mark) {
            array_push($criteriaId, $key);
        }
        return [
            'marks' => $subjectMarks->on_100,
            'criteria' => ExamMarkParameter::whereIn('id', $criteriaId)->get()
        ];
    }
    public function searchCriteriaGlobal(Request $request)
    {

        $subjectMarks = ExamMark::where([

            'subject_id' => $request->subjectId
        ])->first();
       // return ExamMark::all();
        $criteriaId = [];
        foreach (json_decode($subjectMarks->breakdown_mark) as $key => $mark) {
            array_push($criteriaId, $key);
        }
        return [
            'marks' => $subjectMarks->on_100,
            'criteria' => ExamMarkParameter::whereIn('id', $criteriaId)->get()
        ];
    }


    // search employee
    public function searchStudent(Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $tution_fees=$request->tuition_fees;
        $tutionFeesWiseStudent=null;
        if($tution_fees!=null){
            $tutionFeesWiseStudent=StudentEnrollment::where([

                'tution_fees'=>$tution_fees
            ])->pluck('std_id');

        }
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
        $searchExamwise['campus_id'] = $this->academicHelper->getCampus();
        $searchExamwise['institute_id'] = $this->academicHelper->getInstitute();

        if ($exam_id) $searchExamwise['exam_id'] = $exam_id;
        if ($subject_id) $searchExamwise['subject_id'] = $subject_id;
        if ($academic_year_id) $searchExamwise['academic_year_id'] = $academic_year_id;
        if ($semester_id) $searchExamwise['semester_id'] = $semester_id;
        if ($batch) $searchExamwise['batch_id'] = $batch;


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
        // checking return type
        if ($returnType == "json") {
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

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
        $allSearchInformation['campus'] = $this->academicHelper->getCampus();
        $allSearchInformation['institute'] = $this->academicHelper->getInstitute();
        //return $allSearchInformation;
        //return $request->all();
       // return $tutionFeesWiseStudent;
        $skilled_student=null;
        if($request->skill)
        {
            //return $request->skill;
            $skillName=$request->skill;
            $skilled_student= CadetAssesment::where('type',30)->where('remarks','like','%'.$skillName.'%')->pluck
            ('student_id');

        }

        if($tutionFeesWiseStudent){
            if($skilled_student){
                $student_information = StudentInformation::where($allSearchInformation)->whereIn('id',
                    $tutionFeesWiseStudent)->whereIn('id',
                    $skilled_student)->pluck('user_id');

            }else{
                $student_information = StudentInformation::where($allSearchInformation)->whereIn('id',
                    $tutionFeesWiseStudent)->pluck('user_id');


            }

        }else{
            if($skilled_student){
                $student_information = StudentInformation::where($allSearchInformation)->whereIn('id',
                    $skilled_student)->pluck('user_id');

            }else{
                $student_information = StudentInformation::where($allSearchInformation)->pluck('user_id');
                 }

        }

        if ($semester_id && $subject_id) {
            ExamMark::where($searchExamwise)->where('total_mark', $on_100)->get();

            if ($criteriaId) {
                ($topCadet) ? ($examMarks = ExamMark::where($searchExamwise)->orderBy
                ('total_mark', 'DESC')->get()) :
                    ($examMarks = ExamMark::where($searchExamwise)->get());
                $student_id = [];
                $mark_student = [];
                // return  $examMarks;
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
                    $student_id = ExamMark::where($searchExamwise)->where('total_mark', $checkingParamter, $on_100)->orderBy('total_mark', 'DESC')->pluck('student_id');
                } else if ($on_100) {
                    $student_id = ExamMark::where($searchExamwise)->where('total_mark', $on_100)->orderBy('total_mark', 'DESC')->pluck('student_id');
                } else if ($checkingParamter && $on_100) {
                    $student_id = ExamMark::where($searchExamwise)->where('total_mark', $checkingParamter, $on_100)->orderBy('total_mark', 'DESC')->pluck('student_id');

                } else {

                    $student_id = ExamMark::where($searchExamwise)->orderBy('total_mark', 'DESC')->pluck('student_id');

                }
                $student_id = $student_id->toArray();

            }
            $user_id=StudentProfileView::whereIn('std_id',$student_id)->pluck('user_id');
          $filteredStudent= $this->seacrhCadetWithExam($request,$user_id);
          //return $filteredStudent;
           // return StudentProfileView::whereIn('std_id',$student_id)->get();

          /*  $prevStudentId = $searchData->pluck('std_id')->toArray();

            $student_id = array_intersect($student_id, $prevStudentId);*/

            //return $student_id;

            //return ['status'=>'success','html'=>$searchData];
            $newSerial=[];
            foreach ($student_id as $key=>$value){
                if(in_array($value,$filteredStudent->toArray())){
                    array_push($newSerial,$value);
                }
            }
            //return $filteredStudent;
            $student_id= $newSerial;
            $temp = [];
            if (isset($student_id)) {
                if (!isset($topCadet)) {
                    $topCadet = 0;
                }
                if ($topCadet != 0) {
                    $count = 0;
                    foreach ($student_id as $key => $value) {

                        if ($count == $topCadet) break;
                        $count++;
                        array_push($temp, $value);

                    }
                    $student_id = $temp;

                }
                  //  return $filteredStudent;
                $status=$request->input('status');
                if($status==null){
                    $status=1;
                }
                $searchstudentData=$searchData->pluck('std_id')->toArray();

                $searchData = $this->studentProfileView->
                with('singleLevel','singleSection','singleBatch','singleEnroll','studentParents.singleGuardian','roomStudent','academicYear', 'singleStudent')
                    ->where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute(),
                ])->whereIn('std_id', $student_id)->get()->keyBy('std_id');

                //storing the sorted student with limits;

                //return $searchData;
                // return $student_id;
            } else {
                $searchData = null;
            }
            $houses = House::where([

                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->get()->keyBy('id');

            $stdListView = view('student::pages.includes.student-list', compact('status','pageAccessData', 'searchData', 'student_id', 'houses'))->render();
            return ['status' => 'success', 'msg' => 'Studentss List found', 'html' => $stdListView];
            //return $searchData;
        }

        // if($pressentAddressUserId) $allSearchInputs[]

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

                $searchData = $this->studentProfileView->with('roomStudent', 'singleStudent')->where($allSearchInputs)->where('username', "LIKE", "%{$username}%")->whereIn('user_id', $student_information)->get();
            }
        }
       // return $searchData;
        $parentData=$this->getParentWiseData($request);
        if($parentData){
            $searchData= $searchData->whereIn('std_id',$parentData);
            //echo $searchData;
        }

        if ($request->house) {
            $houseWiseStdId = RoomStudent::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'house_id' => $request->house
            ])->pluck('student_id');
            $searchData = $searchData->whereIn('std_id', $houseWiseStdId);
        }

        $houses = House::where([

            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');
        $status=$request->input('status');
        if($status==null){
            $status=1;
        }
        $searchstudentData=$searchData->pluck('std_id')->toArray();
        $searchData=StudentProfileView::
        with('singleLevel','singleEnroll','singleSection','singleBatch','studentParents.singleGuardian','roomStudent','academicYear', 'singleStudent')
            ->whereIn('std_id',$searchstudentData)->get();

        if (!isset($student_id)) {

            $searchData=$searchData->sortBy('username');
            $student_id = $searchData->pluck('std_id')->toArray();
            $searchData = $searchData->keyBy('std_id');


        }
     //  return $searchData;
        // $student_id=$searchData->pluck('std_id')->toArray();
        // return $student_id;


       // return $status;
        $st=microtime(false);
        $st=microtime(true);
        $stdListView = view('student::pages.includes.student-list', compact('status','pageAccessData', 'searchData', 'student_id', 'houses'))->render();
        $en=microtime(true);
       // return $en-$st;
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }


        public function seacrhCadetWithExam($request,$examData){

            $tution_fees=$request->tuition_fees;
            // return $tution_fees;
            $tutionFeesWiseStudent=null;
            if($tution_fees!=null){
                $tutionFeesWiseStudent=StudentEnrollment::where([

                    'tution_fees'=>$tution_fees
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
            // check email
            if ($blood_group) $allSearchInformation['blood_group'] = $blood_group;
            if ($batch_no) $allSearchInformation['batch_no'] = $batch_no;
            $allSearchInformation['campus'] = $this->academicHelper->getCampus();
            $allSearchInformation['institute'] = $this->academicHelper->getInstitute();
           // return $allSearchInformation;
            $skilled_student=null;
            if($request->skill)
            {
                $skillName=$request->skill;
                $skilled_student= CadetAssesment::where('type',30)->where('remarks', 'LIKE', "%".$skillName."%")->pluck('student_id');

            }
            // return $request->skill;
            if($tutionFeesWiseStudent){
                if($skilled_student){
                    $student_information = StudentInformation::where($allSearchInformation)->whereIn('id',
                        $tutionFeesWiseStudent)->whereIn('id',
                        $skilled_student)->pluck('user_id');

                }else{
                    $student_information = StudentInformation::where($allSearchInformation)->whereIn('id',
                        $tutionFeesWiseStudent)->pluck('user_id');


                }

            }else{
                if($skilled_student){
                    $student_information = StudentInformation::where($allSearchInformation)->whereIn('id',
                        $skilled_student)->pluck('user_id');

                }else{
                    $student_information = StudentInformation::where($allSearchInformation)->pluck('user_id');
                }

            }

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
            }
            elseif ($catType != null && $categoryID != null && $categoryActivity == null) {

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
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
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
        if($father_first_name || $first_name || $last_name || $father_last_name || $mother_first_name || $mother_last_name ||
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
                })
                ->pluck('std_id')->toArray();
        }

        return $parentStudentData;
    }


    public function searchCadetFees(Request $request)
    {
        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $feesStructure = $request->feesStructure;

        $categoryActivity = $request->categoryActivity;

        // qry
        $searchData = [];
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

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
        // check email
        if ($username) $allSearchInputs['username'] = $username;


        else {
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
            $academicYearProfile = AcademicsYear::where([
                'status' => '1'
            ])->first();
            $checkFeesAssign = CadetFeesAssign::where([
                'academic_year' => $academicYearProfile->id,
                'academic_level' => $academicLevel,
                'batch' => $batch,
                'section' => $section,
                'structure_id' => $feesStructure
            ])->get()->keyBy('std_id');
        }
        $feesStructureList = FeesStructure::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $feesStructureDetailsList = FeesStructureDetails::join('fees_head', 'fees_structure_details.head_id', 'fees_head.id')->where('structure_id', $feesStructure)
            ->select('fees_structure_details.*', 'fees_head.fees_head')->get();
        $tutionFeesFromProfile = StudentEnrollment::all()->groupBy('std_id');

        $stdListView = view('student::pages.includes.student-list-fees', compact('tutionFeesFromProfile', 'checkFeesAssign', 'academicYearProfile', 'feesStructureDetailsList', 'searchData', 'feesStructure', 'feesStructureList'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    public function searchCadetFeesGenerate(Request $request)
    {
        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $month_name = $request->input('month_name');
        $fine = $request->input('late_fine');
        $fine_type = $request->input('fine_type');
        $payment_last_date = $request->input('payment_last_date');

        // qry
        $searchData = [];
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

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
        // check email
        if ($username) $allSearchInputs['username'] = $username;
        // check Month


        else {
            $searchData = $this->studentProfileView->with('singleEnroll')->where($allSearchInputs)->get();
        }
        $academicYearProfile = AcademicsYear::where([
            'status' => '1'
        ])->first();
        $generateFeesData = CadetFeesGenerate::where(
            ['academic_year' => $academicYearProfile->id, 'academic_level' => $academicLevel, 'batch' => $batch, 'section' => $section, 'month_name' => $month_name]
        )->get()->keyBy('std_id');
        $feesCollectionData = StudentFeesCollection::all()->keyBy('std_id');
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        $stdListView = view('student::pages.includes.student-list-fees-generate-page', compact('feesCollectionData', 'generateFeesData', 'fine', 'fine_type', 'searchData', 'month_name', 'payment_last_date', 'month_list'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    public function searchCadetFeesDetails(Request $request)
    {
        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('std_username');
        $returnType = $request->input('return_type', 'view');
        $pageType = $request->input('page_type', 'manage_std');
        $month_name = $request->input('month_name');
        $year = $request->input('year');
        $status = $request->input('status');
        $payment_last_date = $request->input('payment_last_date');

        // qry
        $searchData = [];
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

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
        // check email
        if ($username) $allSearchInputs['username'] = $username;


        else {
            $searchData = $this->studentProfileView->where($allSearchInputs)->get();
            $academicYearProfile = AcademicsYear::where([
                'status' => '1'
            ])->first();
            if ($status != null) {
                $searchData = CadetFeesGenerate::join('student_manage_view', 'cadet_fees_generate.std_id', 'student_manage_view.std_id')
                    ->leftJoin('student_fees_collection', 'cadet_fees_generate.id', 'student_fees_collection.fees_generate_id')
                    ->leftJoin('users', 'users.id', 'student_fees_collection.paid_by')
                    ->where([
                        'cadet_fees_generate.academic_year' => $academicYearProfile->id,
                        'cadet_fees_generate.academic_level' => $academicLevel,
                        'cadet_fees_generate.batch' => $batch,
                        'cadet_fees_generate.section' => $section,
                        'cadet_fees_generate.month_name' => $month_name,
                        'cadet_fees_generate.status' => $status
                    ])
                    ->select(
                        'users.name',
                        'cadet_fees_generate.id',
                        'cadet_fees_generate.inv_id',
                        'cadet_fees_generate.status',
                        'student_fees_collection.total_payable',
                        'student_fees_collection.paid_amount',
                        'student_fees_collection.pay_date',
                        'student_fees_collection.paid_by',
                        'student_fees_collection.total_dues',
                        'student_fees_collection.transaction_id',
                        'student_fees_collection.payment_mode',
                        'student_fees_collection.payment_type',
                        'student_fees_collection.fine_amount',
                        'student_manage_view.std_id',
                        'student_manage_view.first_name',
                        'student_manage_view.last_name',
                        'student_manage_view.std_id',
                        'cadet_fees_generate.payment_last_date',
                        'cadet_fees_generate.fine_type',
                        'cadet_fees_generate.late_fine',
                        'cadet_fees_generate.fees',
                        'student_manage_view.gr_no'
                    )->get();
            } else {
                $searchData = CadetFeesGenerate::join('student_manage_view', 'cadet_fees_generate.std_id', 'student_manage_view.std_id')
                    ->leftJoin('student_fees_collection', 'cadet_fees_generate.id', 'student_fees_collection.fees_generate_id')
                    ->leftJoin('users', 'users.id', 'student_fees_collection.paid_by')
                    ->where([
                        'cadet_fees_generate.academic_year' => $academicYearProfile->id,
                        'cadet_fees_generate.academic_level' => $academicLevel,
                        'cadet_fees_generate.batch' => $batch,
                        'cadet_fees_generate.section' => $section,
                        'cadet_fees_generate.month_name' => $month_name
                    ])
                    ->select(
                        'users.name',
                        'student_fees_collection.discount',
                        'cadet_fees_generate.id',
                        'cadet_fees_generate.inv_id',
                        'cadet_fees_generate.status',
                        'student_fees_collection.total_payable',
                        'student_fees_collection.paid_amount',
                        'student_fees_collection.pay_date',
                        'student_fees_collection.paid_by',
                        'student_fees_collection.total_dues',
                        'student_fees_collection.transaction_id',
                        'student_fees_collection.payment_mode',
                        'student_fees_collection.payment_type',
                        'student_fees_collection.fine_amount',
                        'student_manage_view.std_id',
                        'student_manage_view.first_name',
                        'student_manage_view.last_name',
                        'student_manage_view.gr_no',
                        'student_manage_view.std_id',
                        'cadet_fees_generate.payment_last_date',
                        'cadet_fees_generate.fine_type',
                        'cadet_fees_generate.late_fine',
                        'cadet_fees_generate.fees'
                    )
                    ->get();
            }
        }
        $feesCollections = StudentFeesCollection::all()->groupBy('std_id');
        //    return $searchData;
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        $statuses = array(0 => 'Pending', 1 => 'Paid', 2 => 'Partially Paid');
        $stdListView = view('student::pages.includes.student-list-fees-details-page', compact('feesCollections', 'statuses', 'year', 'status', 'searchData', 'month_name', 'payment_last_date', 'month_list'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    ////////////////////////// Manage Student Profile /////////////////////////////

    // manageStudentProfile
    public function manageStudentProfile(Request $request)
    {
        // request type
        $requestType = $request->input('request_type', 'page');
        // checking request type
        if ($requestType == 'manage_std_profile') {
            // request details
            $stdCount = $request->input('std_count');
            $stdList = $request->input('std_list');

            // checking student list
            if (!empty($stdList) and $stdList != null and count($stdList) > 0) {
                // Start transaction!
                DB::beginTransaction();
                // try to upload attendance list
                try {

                    // std loop counter
                    $stdLoopCounter = 0;
                    // std list looping
                    foreach ($stdList as $stdId => $stdInfo) {
                        // convert std info into the object format
                        $stdInfo = (object)$stdInfo;

                        // find std profile
                        $stdProfile = $this->studentInformation->find($stdId);
                        // update student profile details
                        $stdProfile->first_name = '';
                        $stdProfile->middle_name = $stdInfo->name;
                        $stdProfile->last_name = '';
                        $stdProfile->email = $stdInfo->email;
                        $stdProfile->punch_id = $stdInfo->punch_id;
                        // save and checking
                        if ($stdProfile->save()) {
                            // find student enroll profile
                            $stdEnrollProfile = $stdProfile->enroll();
                            // update enroll profile
                            $stdEnrollProfile->gr_no = $stdInfo->gr_no;
                            // save and checking
                            if ($stdEnrollProfile->save()) {
                                // find student enrollment history
                                $stdEnrollHistory = $stdEnrollProfile->history('IN_PROGRESS');
                                // now update student enroll history
                                $stdEnrollHistory->gr_no = $stdInfo->gr_no;
                                // save and checking
                                if ($stdEnrollHistory->save()) {
                                    // find student user profile
                                    $stdUserProfile = $stdProfile->user();
                                    // find std email
                                    $userEmail = $this->user->where(['email' => $stdInfo->email])->first();
                                    // checking
                                    if (($userEmail == null) || ($stdUserProfile->id == $userEmail->id)) {
                                        // find std username
                                        $userUsername = $this->user->where(['username' => $stdInfo->username])->first();
                                        // checking
                                        if (($userUsername == null) || ($stdUserProfile->id == $userUsername->id)) {
                                            // update user details
                                            $stdUserProfile->name = $stdInfo->name;
                                            $stdUserProfile->email = $stdInfo->email;
                                            $stdUserProfile->username = $stdInfo->username;
                                            // checking and save
                                            if ($stdUserProfile->save()) {
                                                // std count
                                                $stdLoopCounter += 1;
                                            } else {
                                                // Rollback and then redirect back to form with errors
                                                DB::rollback();
                                                // return
                                                return ['status' => 'failed', 'msg' => ' Unable to save student user profile !!!'];
                                            }
                                        } else {
                                            // Rollback and then redirect back to form with errors
                                            DB::rollback();
                                            // return
                                            return ['status' => 'failed', 'msg' => 'Username "' . $stdInfo->username . '" Already exits !!!'];
                                        }
                                    } else {
                                        // Rollback and then redirect back to form with errors
                                        DB::rollback();
                                        // return
                                        return ['status' => 'failed', 'msg' => 'Email Address "' . $stdInfo->email . '" Already exits !!!'];
                                    }
                                } else {
                                    // Rollback and then redirect back to form with errors
                                    DB::rollback();
                                    // return
                                    return ['status' => 'failed', 'msg' => 'Unable to update Enroll History for "' . $stdInfo->username];
                                }
                            } else {
                                // Rollback and then redirect back to form with errors
                                DB::rollback();
                                // return
                                return ['status' => 'failed', 'msg' => ' Unable to save student enrollment profile !!!'];
                            }
                        } else {
                            // Rollback and then redirect back to form with errors
                            DB::rollback();
                            // return
                            return ['status' => 'failed', 'msg' => ' Unable to save student information profile !!!'];
                        }
                    }
                } catch (ValidationException $e) {
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                } catch (Exception $e) {
                    DB::rollback();
                    throw $e;
                }

                // checking
                if ($stdLoopCounter == $stdCount) {
                    // If we reach here, then data is valid and working. Commit the queries!
                    DB::commit();
                    // return
                    return ['status' => 'success', 'msg' => 'Student profile(s) Updated !!!'];
                } else {
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                    // return
                    return ['status' => 'failed', 'msg' => ' Unable to save student profile(s) !!!'];
                }
            }
        } else {
            // academic year list
            $academicYears = $this->academicsYear->where([
                'institute_id' => $this->academicHelper->getInstitute(),
                'campus_id' => $this->academicHelper->getCampus()
            ])->get();
            // return view with variable
            return view('student::pages.manage-student-profile', compact('academicYears'));
        }
    }


    // search student Downlaod

    public function searchStudentDownload(Request $request)
    {

        //institute Proifle
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        //        return $request->all();

        $downloadType = $request->download_type;

        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        // input institute and campus id
        $allSearchInputs['campus'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute'] = $this->academicHelper->getInstitute();

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        // class profile
        $classProfile = $this->academicHelper->findBatch($batch);
        $sectionProfile = $this->academicHelper->FindSection($section);

        // search result
        $allEnrollments = $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();

        //        return view('student::pages.reports.student_list', compact('allEnrollments'));
        //        exit();
        view()->share(compact('allEnrollments', 'allSearchInputs', 'downloadType', 'instituteInfo', 'classProfile', 'sectionProfile'));

        if ($downloadType == 'excel') {
            //generate excel
            return Excel::create('Student List', function ($excel) {
                $excel->sheet('Student List', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('student::pages.reports.student_list');
                });
            })->download('xlsx');
        } else {
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('student::pages.reports.student_list')->setPaper('a4', 'landscape');
            // return
            return $pdf->stream();
        }
    }


    public function searchAlumniStudentDownload(Request $request)
    {

        //        return $request->all();
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');


        // qry
        $allSearchInputs = array();
        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        // define
        //        $allSearchInputs['student_enrollment_history.batch_status'] = 'LEVEL_UP';
        //        $allSearchInputs['student_enrollment_history.batch_status'] = 'LEVEL_UP';


        $alumniStudents = $this->stdEnrollHistory
            ->where($allSearchInputs)
            ->whereIn('batch_status', ['LEVEL_UP', 'GRADUATED'])
            ->get();


        view()->share(compact('alumniStudents'));
        //generate excel
        Excel::create('Student List', function ($excel) {
            $excel->sheet('Student List', function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                $sheet->loadView('student::pages.reports.student_alumni_list');
            });
        })->download('xlsx');
    }


    // download deactive student herre
    public function downloadDeactiveStudent(Request $request)
    {

        //        return $request->all();
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');


        // qry
        $allSearchInputs = array();
        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        // define
        //        $allSearchInputs['student_enrollment_history.batch_status'] = 'LEVEL_UP';
        //        $allSearchInputs['student_enrollment_history.batch_status'] = 'LEVEL_UP';


        $deactiveStudents = DB::table('student_enrollments')
            ->join('student_informations', function ($join) {
                $join->on('student_enrollments.std_id', '=', 'student_informations.id')
                    ->where('student_informations.status', '=', 0);
            })->where($allSearchInputs)
            ->paginate(20);


        view()->share(compact('deactiveStudents'));
        //generate excel
        Excel::create('Student List', function ($excel) {
            $excel->sheet('Student List', function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                $sheet->loadView('student::pages.reports.student_deactive_list');
            });
        })->download('xlsx');
    }


    // show student id card
    public function showIdCard($stdId)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // std profile
        $stdProfile = $this->studentProfileView->where(['std_id' => $stdId])->first();
        // $instituteInfo
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // institute information
        $templateProfile = $this->idCardTemplate->where(['campus' => $campusId, 'institute' => $instituteId])->first();
        // return view with variable
        return view('student::pages.student-profile.modals.student-id-card', compact('stdProfile', 'instituteInfo', 'templateProfile'));
    }

    // show student id card
    public function downloadIdCard(Request $request)
    {
        $stdId = $request->input('std_id');
        // std profile
        $studentList = $this->studentProfileView->where(['std_id' => $stdId])->get();

        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // institute information
        $templateProfile = $this->idCardTemplate->where(['campus' => $campusId, 'institute' => $instituteId])->first();
        // template setting
        $tempType = $templateProfile ? $templateProfile->temp_type : [];


        //        // share all variables with the view
        view()->share(compact('studentList', 'instituteInfo', 'templateProfile'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        //  checking id card template type (landscape or portrait)
        if ($tempType == 0) {
            if ($templateProfile->temp_id == 1) {

                $pdf->loadView('reports::pages.report.id-card-land-template-one')->setPaper('a4', 'landscape');
            } elseif ($templateProfile->temp_id == 2) {
                $pdf->loadView('reports::pages.report.id-card-land-template-two')->setPaper('a4', 'landscape');
            } elseif ($templateProfile->temp_id == 3) {
                $pdf->loadView('reports::pages.report.id-card-land-template-three')->setPaper('a4', 'landscape');
            }
        } else {
            $pdf->loadView('reports::pages.report.id-card-port-template-one')->setPaper('a4', 'portrait');
        }
        // return
        return $pdf->download('class_section_student_id_card.pdf');
    }

    // show student id card
    public function getReportCard($stdId)
    {
        // std profile
        $studentProfile = $this->studentInformation->where(['id' => $stdId])->first();
        // checking
        if ($studentProfile) {
            // student list
            $studentList = null;
            // return view with variables
            return view('academics::manage-assessments.report-card-parent', compact('studentList', 'studentProfile'));
        } else {
            abort(404);
        }
    }

    // show student id card
    public function getAttendanceInfo($stdId)
    {
        // std profile
        $studentProfile = $this->studentInformation->where(['id' => $stdId])->first();
        // checking
        if ($studentProfile) {
            // student list
            $studentList = null;
            $semesterList = $this->academicHelper->getAcademicSemester();
            // return view with variables
            return view('academics::manage-attendance.attendance-parent', compact('studentProfile', 'studentList', 'semesterList'));
        } else {
            abort(404);
        }
    }



    // public function student


    //get invoice by Id
    public function getInvoiceById(Request $request, $invoiceId, $backUrl)
    {
        //        return $backUrl;
        $currentUrl = str_replace('+', '/', $backUrl);
        $currentUrl = str_replace('>>', '?', $currentUrl);
        $currentUrl = str_replace('-', '%', $currentUrl);

        $invoice = $this->feesInvoice->find($invoiceId);
        $institute = $this->academicHelper->getInstituteProfile();
        // get extra payment by invoice payer id

        $payment_extra = $this->paymentExtra->select("*")->where("student_id", $invoice->payer()->id)->first();
        $paymentList = $this->getInvoicePaymentListByInvoice($invoiceId);

        // get due date count
        $fees = $invoice->fees();

        $day_fine_amount = get_fees_day_amount($fees->due_date);

        // if any fine payment then check and show invoice page
        $invoiceFine = $this->invoiceFine->where('payer_id', $invoice->payer_id)->where('invoice_id', $invoiceId)->get();
        return view('fees::pages.show_invoice_student', compact('invoice', 'institute', 'paymentList', 'payment_extra', 'day_fine_amount', 'invoiceFine', 'currentUrl'))->with('page', '');
    }


    public function getInvoicePaymentListByInvoice($invoiceId)
    {
        return $this->invoicePayment->where('invoice_id', $invoiceId)->get();
    }

    public function studentUpdateListExport(Request $request)
    {

        //        return $request->all();

        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        // input institute and campus id
        $allSearchInputs['campus'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute'] = $this->academicHelper->getInstitute();

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

        // search result
        $allEnrollments = $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->orderBy('username', 'asc')->get();

        view()->share(compact('allEnrollments', 'allSearchInputs'));
        //generate excel
        Excel::create('student_list', function ($excel) {
            $excel->sheet('student_list', function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                $sheet->loadView('student::pages.reports.student_update_list');
            });
        })->download('xlsx');
    }


    // student profile import modal

    public function studentImportModal()
    {
        return view('student::pages.modal.student_import');
    }


    public function studentImportExcelFile(Request $request)
    {
        //        return $request->all();

        if (Input::hasFile('student_import')) {
            $path = Input::file('student_import')->getRealPath();
            $allEnrollments = Excel::load($path, function ($reader) {
            })->get();


            $stdListView = view('student::pages.includes.student-update-profile-list', compact('allEnrollments'))->render();

            return ['status' => 'success', 'html' => $stdListView];
        } else {
            return "error";
        }
    }


    /**
     * Check emails duplicacy for students
     */
    public function checkEmails(Request $request)
    {
        // institute infor

        $institute_id = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $student_ids = $request->input('student_id');
        $student_ids = json_decode($student_ids);

        $user_ids = $request->input('user_id');
        $user_ids = json_decode($user_ids);

        $emails = $request->input('student_email');
        $emails = json_decode($emails);
        $result = array();

        for ($i = 1; $i < count($emails); $i++) {
            $userList = User::where('email', '=', $emails[$i])->get();
            if ($userList->count() > 0) {
                Log::info('check-email');
                $userProifle = User::where('email', '=', $emails[$i])->where('id', $user_ids[$i])->first();
                if ($userProifle) {
                    $result[$i] = 0;
                } else {
                    $result[$i] = 1;
                }
            } else {
                $result[$i] = 1;
            }
        }
        return $result;
    }


    public function checkPunchIds(Request $request)
    {

        // institute infor

        $institute_id = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $student_ids = $request->input('student_id');
        $student_ids = json_decode($student_ids);

        $user_ids = $request->input('user_id');
        $user_ids = json_decode($user_ids);
        $punchId = $request->input('student_punch_id');
        $punchIds = json_decode($punchId);
        $result = array();

        for ($i = 1; $i < count($punchIds); $i++) {

            $userList = StudentInformation::where('punch_id', '=', $punchIds[$i])->where('institute', $institute_id)->where('campus', $campus_id)->get();
            if ($userList->count() > 0) {
                $userProifle = StudentInformation::where('punch_id', '=', $punchIds[$i])->where('id', $student_ids[$i])->first();
                if ($userProifle) {
                    $result[$i] = 0;
                } else {
                    $result[$i] = 1;
                }
            } else {

                $userProifle = StudentInformation::where('punch_id', '=', NUll)->where('id', $student_ids[$i])->first();
                if ($userProifle) {
                    $result[$i] = 0;
                } else {
                    $result[$i] = 1;
                }
            }
        }
        return $result;
    }


    public function checkSinglePunchId(Request $request)
    {


        $institute_id = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $punchId = $request->input('student_punch_id');

        $punchProfile = StudentInformation::where('punch_id', '=', $punchId)->where('institute', $institute_id)->where('campus', $campus_id)->first();
        if ($punchProfile) {
            return 1;
        } else {
            return 0;
        }
    }

    // check single email id


    public function checkSingleEmail(Request $request)
    {


        $institute_id = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $email = $request->input('email');

        $studentEmailProfile = StudentInformation::where('email', '=', $email)->where('institute', $institute_id)->where('campus', $campus_id)->first();
        if ($studentEmailProfile) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getStudentDeviceAttedance()
    {

        return view('student::pages.get-student-device-attedance');
    }

    public function storeStudentCustomAttendance(Request $request)
    {
        // get institute and campus id
        $institute_id = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();
        // student attendance list
        $attendanceList = $request->attendance_list;
        // check attendance list
        if (empty($attendance_list))
            foreach ($attendanceList as $key => $student) {
                // find student attendance profile
                $studentAttendanceProfile = $this->attendanceUpload->where('std_id', $student['person_id'])->whereDate('entry_date_time', '=', date('Y-m-d', strtotime($student['access_date'])))->first();

                if (!empty($studentAttendanceProfile)) {
                    //get student all information
                    $studentProfile = $this->studentEnrollment->where('std_id', $student['person_id'])->first();
                    if (!empty($studentProfile) && ($studentAttendanceProfile->entry_date_time > $student['access_date'] . ' ' . $student['access_time'])) {
                        $studentAttendanceProfile->std_id = $studentProfile->std_id;
                        $studentAttendanceProfile->std_gr_no = $studentProfile->gr_no;
                        $studentAttendanceProfile->entry_date_time = $student['access_date'] . ' ' . $student['access_time'];
                        $studentAttendanceProfile->academic_year = $studentProfile->academic_year;
                        $studentAttendanceProfile->level = $studentProfile->academic_level;
                        $studentAttendanceProfile->batch = $studentProfile->batch;
                        $studentAttendanceProfile->section = $studentProfile->section;
                        $studentAttendanceProfile->institute = $institute_id;
                        $studentAttendanceProfile->campus = $campus_id;
                        $result = $studentAttendanceProfile->save();
                    }
                } else {
                    $studentProfile = $this->studentEnrollment->where('std_id', $student['person_id'])->first();
                    if (!empty($studentProfile)) {
                        $studentAttendanceObject = new $this->attendanceUpload;
                        $studentAttendanceObject->std_id = $studentProfile->std_id;
                        $studentAttendanceObject->std_gr_no = $studentProfile->gr_no;
                        $studentAttendanceObject->entry_date_time = $student['access_date'] . ' ' . $student['access_time'];
                        $studentAttendanceObject->academic_year = $studentProfile->academic_year;
                        $studentAttendanceObject->level = $studentProfile->academic_level;
                        $studentAttendanceObject->batch = $studentProfile->batch;
                        $studentAttendanceObject->section = $studentProfile->section;
                        $studentAttendanceObject->institute = $institute_id;
                        $studentAttendanceObject->campus = $campus_id;
                        $result = $studentAttendanceObject->save();
                    }
                }
            }

        if ($result) {
            return ['status' => true, 'msg' => 'Student Attendance Uploaded'];
        } else {
            return ['status' => false, 'msg' => 'Unable to Uploaded Attendance Try Again'];
        }
    }

    public function test(Type $var = null)
    {
        return view('student::pages.student-profile.student-dream', compact('personalInfo', 'recordInfo'))->with('page', 'dream');
    }

    // student class subject report card

    public function studentClassSubjectReport(Request $request)
    {
        //institute Proifle
        $instituteInfo = $this->academicHelper->getInstituteProfile();

        $downloadType = $request->download_type;

        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        // campus and institute
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // input institute and campus id
        $allSearchInputs['campus'] = $campus;
        $allSearchInputs['institute'] = $institute;

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        // find student subject choice list
        $studentSubList = $this->classSubStudent->getClassSectionStudentSubjectList($section, $batch, $campus, $institute);

        // class profile
        $classProfile = $this->academicHelper->findBatch($batch);
        $sectionProfile = $this->academicHelper->FindSection($section);

        // search result
        $allEnrollments = $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
        // share all variables with view
        view()->share(compact('allEnrollments', 'allSearchInputs', 'downloadType', 'instituteInfo', 'classProfile', 'sectionProfile', 'studentSubList'));
        // checking download type
        if ($downloadType == 'excel') {
            //generate excel
            return Excel::create('Student List', function ($excel) {
                $excel->sheet('Student List', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                    $sheet->loadView('student::pages.reports.student_class_subject_excel');
                });
            })->download('xlsx');
        } else {
            //return view('student::pages.reports.student_class_subject_pdf');
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('student::pages.reports.student_class_subject_pdf')->setPaper('a4', 'landscape');
            // return
            return $pdf->stream();
        }
    }
}
