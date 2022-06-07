<?php

namespace Modules\Student\Http\Controllers;

use App\Helpers\ExamHelper;
use App\Helpers\UserAccessHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Setting\Entities\CadetAssessmentActivity;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceActivityPoint;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Subject;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamList;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\SubjectGroup;

class StudentEnrollController extends Controller
{
    private $enrollHistory;
    private $studentEnrollment;
    private $studentInformation;
    private $academicHelper;
    use UserAccessHelper;
    use ExamHelper;

    // construct method
    public function __construct(StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StdEnrollHistory $enrollHistory, AcademicHelper $academicHelper)
    {
        $this->enrollHistory = $enrollHistory;
        $this->studentEnrollment = $studentEnrollment;
        $this->studentInformation = $studentInformation;
        $this->academicHelper = $academicHelper;
    }

    // get student academic information
    public function getStudentAcademics($id, Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/
        manage']);
        // student information
        $personalInfo = $this->studentInformation->findOrFail($id);
        // return view with variables
        return view('student::pages.student-profile.student-academic', compact('pageAccessData', 'personalInfo'))->with('page', 'academics');
    }
    public function getStudentAcademics2($id, Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        // student information
        $personalInfo = $this->studentInformation->findOrFail($id);
        $activity = CadetPerformanceActivity::where('cadet_category_id', 19)->get();
        $academics = CadetAssesment::where('student_id', $id)
            ->where('type', 19)
            ->orderBy('date', 'DESC')
            ->get();
        // return view with variables

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'student_id' => $id
        ])->get()->groupBy('exam_id')->toArray();
        $examLists = ExamList::with('year', 'term', 'batch', 'section')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'publish_status' => 2
        ])->whereIn('exam_id', array_keys($examMarks))->latest()->get();

        $years = AcademicsYear::all();
        $terms = Semester::where('status', 1)->get();
        $exams = ExamName::all();

        $subjects = Subject::all();

        return view('student::pages.student-profile.student-academic3', compact('pageAccessData', 'personalInfo', 'academics', 'activity', 'examLists', 'years', 'terms', 'exams', 'subjects'))->with('page', 'examResult')->with('std_id', $id);
    }

    public function getStudentAcademicsSubject($id, $item)
    {
        $personalInfo = $this->studentInformation->findOrFail($id);
        $examDeatils = CadetAssessmentActivity::where('assessment_id', $item)->get();
        return view('student::pages.student-profile.modals.student-exam-subject', compact('examDeatils', 'personalInfo'));
    }
    public function getStudentPsychologyView($id, $item)
    {
        $personalInfo = $this->studentInformation->findOrFail($id);
        $examDeatils = CadetAssessmentActivity::where('assessment_id', $item)->get();
        return view('student::pages.student-profile.modals.student-exam-subject', compact('examDeatils', 'personalInfo'));
    }
    public function getStudentAcademicsEntry($id)
    {
        $personalInfo = $this->studentInformation->findOrFail($id);
        $performanceType = CadetPerformanceCategory::findOrFail(19);
        $activities = CadetPerformanceActivity::where('cadet_category_id', '19')->get();
        $activity = 1;
        $value = CadetPerformanceActivityPoint::where('cadet_performance_activity', $activity)->get();
        // return view with variables
        return view('student::pages.student-profile.student-academic-entry', compact('personalInfo', 'performanceType', 'activities', 'value'))->with('page', 'academics')->with('std_id', $id);
    }

    public function getStudentAcademicPoint(Request $request)
    {
        $activity = 1;
        $value = CadetPerformanceActivityPoint::where('cadet_performance_activity', $activity)->get();
    }

    // get student course enroll page
    public function courseEnroll()
    {
        // return view with variables
        return view('student::pages.student-profile.modals.academic-course-enroll');
    }

    // get student course edit page
    public function editEnroll($id)
    {
        // find student enroll profile
        $enrollProfile = $this->studentEnrollment->find($id);
        // academic level list
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variables
        return view('student::pages.student-profile.modals.academic-course-enroll-update', compact('enrollProfile', 'allAcademicsLevel'));
    }


    // update academic info
    public function updateEnroll(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'std_id' => 'required', 'enroll_id' => 'required', 'academic_year' => 'required',
            'academic_level' => 'required', 'batch' => 'required', 'section' => 'required|max:100', 'gr_no' => 'required|max:100',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // request details
            $grNo = $request->input('gr_no');
            $stdId = $request->input('std_id');
            $enrollId = $request->input('enroll_id');
            $year = $request->input('academic_year');
            $level = $request->input('academic_level');
            $batch = $request->input('batch');
            $section = $request->input('section');
            // Start transaction!
            DB::beginTransaction();

            // find student enrollment profile
            if ($stdEnroll = $this->studentEnrollment->find($enrollId)) {
                // update student enrollment profile
                $stdEnroll->gr_no = $grNo;
                $stdEnroll->academic_year = $year;
                $stdEnroll->academic_level = $level;
                $stdEnroll->batch = $batch;
                $stdEnroll->section = $section;
                // save and checking
                if ($stdEnroll->save()) {
                    // find student enrollment history
                    $stdEnrollHistory = $stdEnroll->history('IN_PROGRESS');
                    // update enroll history
                    $stdEnrollHistory->gr_no = $grNo;
                    $stdEnrollHistory->academic_year = $year;
                    $stdEnrollHistory->academic_level = $level;
                    $stdEnrollHistory->batch = $batch;
                    $stdEnrollHistory->section = $section;
                    // save enroll history
                    if ($stdEnrollHistory->save()) {
                        // If we reach here, then data is valid and working. Commit the queries!
                        DB::commit();
                        // return with success msg
                        return ['status' => true, 'msg' => 'Academic Information Updated Successfully !!!!!'];
                    } else {
                        // Rollback and then redirect back to form with errors
                        DB::rollback();
                        // return with failed msg
                        return ['status' => false, 'msg' => 'Academic Information Updated Successfully !!!!!'];
                    }
                } else {
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                    // return with failed msg
                    return ['status' => false, 'msg' => 'Academic Information Updated Successfully !!!!!'];
                }
            } else {
                // return with failed msg
                return ['status' => false, 'msg' => 'Academic Information Updated Successfully !!!!!'];
            }
        } else {
            // return with failed msg
            return ['status' => false, 'msg' => 'Invalid Information'];
        }
    }

    // get student course info page
    public function courseInfo()
    {
        return view('student::pages.student-profile.modals.academic-course-info');
    }

    // get student course info edit page
    public function courseInfoEdit()
    {
        return view('student::pages.student-profile.modals.academic-course-info-update');
    }
    // get student batch info page
    public function batchInfo()
    {
        return view('student::pages.student-profile.modals.academic-batch-info');
    }

    public function getExamResultDataForChart(Request $request)
    {
        $chartData = [];
        $groupNames = [];

        if ($request->yearIds) {
            $years = AcademicsYear::whereIn('id', $request->yearIds)->get();
        } else {
            $years = AcademicsYear::latest()->get();
        }
        if ($request->termId) {
            $terms = Semester::where('id', $request->termId)->get();
        } else {
            $terms = Semester::all();
        }
        $type = $request->type;

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where('student_enrollments.std_id', $request->stdId)
            ->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        $subjects = Subject::get()->keyBy('id');
        $subjectGroups = SubjectGroup::get()->keyBy('id');

        if ($request->subjectIds) {
            $subjectIds = $request->subjectIds;
        } else {
            $subjectIds = array_keys($subjects->toArray());
        }

        foreach ($years as $year) {
            $enrollment = $studentEnrollments->firstWhere('academic_year', $year->id);

            if ($enrollment) {
                $chartData[$year->id] = [
                    'yearName' => $year->year_name,
                    'data' => []
                ];
                $tempChartData = [];

                foreach ($terms as $term) {
                    if ($request->examId) {
                        $exam = ExamName::findOrFail($request->examId);
                        $sheetData = $this->getExamWiseMarkSheet($year->id, $term->id, $request->examId, [$enrollment->batch], $enrollment->section);
                        if ($type == 'summary') {
                            array_push($tempChartData, [
                                "group_name" => $exam->exam_name,
                                "name" => $term->name,
                                "value" => $sheetData[$request->stdId]['totalAvgMarkPercentage'],
                            ]);
                            array_push($groupNames, $exam->exam_name);
                        } else if ($type == 'details') {
                            foreach ($sheetData[$request->stdId] as $subId => $subjectMark) {
                                if (is_array($subjectMark) && in_array($subId, $subjectIds)) {
                                    array_push($tempChartData, [
                                        "group_name" => $subjects[$subId]->subject_name,
                                        "name" => $term->name,
                                        "value" => $subjectMark['totalMark'],
                                    ]);
                                    array_push($groupNames, $subjects[$subId]->subject_name);
                                }
                            }
                        }
                    } else {
                        $sheetData = $this->getTermWiseSummaryMarkSheet($year->id, $term->id, [$enrollment->batch], $enrollment->section);
                        if ($type == 'summary') {
                            array_push($tempChartData, [
                                "group_name" => "Term Final",
                                "name" => $term->name,
                                "value" => $sheetData[$request->stdId]['avg'],
                            ]);
                            array_push($groupNames, "Term Final");
                        } else if ($type == 'details') {
                            foreach ($sheetData[$request->stdId] as $groupId => $subjectGroup) {
                                if (is_array($subjectGroup)) {
                                    foreach ($subjectGroup as $subId => $subjectMark) {
                                        if (is_array($subjectMark) && in_array($subId, $subjectIds)) {
                                            array_push($tempChartData, [
                                                "group_name" => $subjects[$subId]->subject_name,
                                                "name" => $term->name,
                                                "value" => $subjectMark['avgMark'],
                                            ]);
                                            array_push($groupNames, $subjects[$subId]->subject_name);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                foreach (array_unique($groupNames) as $groupName) {
                    foreach ($tempChartData as $data) {
                        if ($groupName == $data['group_name']) {
                            array_push($chartData[$year->id]['data'], $data);
                        }
                    }
                }
            }
        }

        return $chartData;
    }
}
