<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamMarkParameter;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\GradeDetails;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Event\Entities\EventMark;
use Modules\HealthCare\Entities\HealthPrescription;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\CadetWarning;
use Modules\Student\Entities\DetailReportLayout;
use Modules\Student\Entities\ReportRemark;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App;
use App\Helpers\ExamHelper;
use PDF;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use App\Helpers\UserAccessHelper;
use App\Subject;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ExamCategory;
use Modules\Academics\Entities\SubjectMark;

class CadetReportController extends Controller
{
    use UserAccessHelper;
    use ExamHelper;
    private $academicHelper;
    private $academicsLevel;

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
        $terms = Semester::all();
        $exams = ExamCategory::where('alias', 'term-end')->first()->examNames;

        return view('student::pages.cadet-reports.cadet-detail-reports', compact('pageAccessData', 'academicLevels', 'academicYears', 'terms', 'exams'));
    }
    public function reportSummary($id)
    {
        $student = StudentProfileView::where('std_id', '=', $id)->first();
        $academicLevels = $this->academicsLevel->all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $physicalFitness = CadetPerformanceActivity::where('cadet_category_id', '=', 21)->get();
        $selfVirtues = CadetPerformanceActivity::where('cadet_category_id', '=', 25)->get();

        return view('student::pages.cadet-reports.final-summary-report', compact('physicalFitness', 'selfVirtues', 'student'));
    }

    public function reportSummarySingle($id)
    {
        $student = StudentProfileView::where('std_id', '=', $id)->first();
        $academicLevels = $this->academicsLevel->all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $physicalFitness = CadetPerformanceActivity::where('cadet_category_id', '=', 21)->get();
        $selfVirtues = CadetPerformanceActivity::where('cadet_category_id', '=', 25)->get();

        return view('student::pages.cadet-reports.final-summary-single', compact('physicalFitness', 'selfVirtues', 'student'));
    }



    public function create(Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/detail/reports']);
        $reportLayouts = DetailReportLayout::all();

        return view('student::pages.cadet-reports.modal.detail-report-layout', compact('pageAccessData', 'reportLayouts'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            DetailReportLayout::insert([
                'title' => $request->title,
                'description' => $request->description,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id()
            ]);

            DB::commit();
            Session::flash('message', 'New report layout block created successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating report layout block.');
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
        $detailReportLayout = DetailReportLayout::findOrFail($id);

        DB::beginTransaction();
        try {
            $detailReportLayout->update([
                'title' => $request->title,
                'description' => $request->description,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            Session::flash('message', 'Report layout block updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating layout block.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        DetailReportLayout::findOrFail($id)->delete();

        Session::flash('message', 'Report layout block deleted successfully.');
        return redirect()->back();
    }

    public function searchStudentsForReport(Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/detail/reports']);
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

        return view('student::pages.cadet-reports.student-list', compact('students', 'pageAccessData'))->render();
    }

    public function printDetailReport(Request $request)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $student = StudentProfileView::with('singleBatch', 'singleSection')->where('std_id', $request->studentId)->first();
        $batch = Batch::findOrFail($request->batchId);

        // -----New Exam Marks code starts here-----
        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
            'batch_id' => $request->batchId
        ])->get();
        if ($request->sectionId) {
            $examMarks = $examMarks->where('section_id', $request->sectionId);
        }
        $examIds = array_keys($examMarks->groupBy('exam_id')->toArray());
        if (sizeof($examIds) > 0) {
            $effectiveExamIds = ExamName::where('effective_on', 1)->whereIn('id', $examIds)->get()->groupBy('exam_category_id')->toArray();
            $examCategories = (sizeof($effectiveExamIds) > 0) ? ExamCategory::with('examNames')->whereIn('id', array_keys($effectiveExamIds))->get()->keyBy('id') : [];
        } else {
            $effectiveExamIds = [];
            $examCategories = [];
        }
        $examMarks = $examMarks->groupBy('subject_id');
        $termFinalExamCategory = ExamCategory::where('alias', 'term-end')->first();

        $subjects = Subject::leftJoin('subject_group_assign', 'subject_group_assign.sub_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name', 'subject.subject_code', 'subject_alias', 'subject_group_assign.sub_id', 'subject_group_assign.sub_group_id')
            ->whereIn('subject.id', array_keys($examMarks->toArray()))->get()->groupBy('sub_group_id')->toArray();
        $subjectMarks = SubjectMark::with('exam.ExamCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'batch_id' => $request->batchId
        ])->get();
        $subjectMarksExamWise = $subjectMarks->where('exam_id', $request->examId)->keyBy('subject_id');
        $subjectMarks = $subjectMarks->groupBy('subject_id');

        $sheetData = $this->getTermWiseMarkSheet(
            $request->yearId,
            $request->termId,
            $request->examId,
            [$request->batchId],
            $request->sectionId,
        );
        $minMaxSubjectMarks = $this->getMaxMinSubjectMarks($subjects, $sheetData);
        $firstLastStudents = $this->getFirstLastStudents($sheetData);

        $totalCriteriaIds = [];
        $totalExamCatIds = [];
        foreach ($subjects as $key => $subjectGroup) {
            foreach ($subjectGroup as $subject) {
                foreach ($examCategories as $examCategory) {
                    if ($termFinalExamCategory->id == $examCategory->id) {
                        if (isset($subjectMarksExamWise[$subject['id']])) {
                            $marks = json_decode($subjectMarksExamWise[$subject['id']]->marks, 1);
                            $conversionPoint = ($subjectMarksExamWise[$subject['id']]->full_marks != 0) ? $subjectMarksExamWise[$subject['id']]->full_mark_conversion / $subjectMarksExamWise[$subject['id']]->full_marks : 0;
                            foreach ($marks['fullMarks'] as $criteriaId => $mark) {
                                $totalCriteriaIds[$criteriaId] = round($mark * $conversionPoint, 2);
                            }
                        }
                    } else {
                        if (isset($subjectMarks[$subject['id']])) {
                            $fullConversionMark = $subjectMarks[$subject['id']]->whereIn('exam_id', $examCategory->examNames->pluck('id'))->avg('full_mark_conversion');
                            $totalExamCatIds[$examCategory->id] = $fullConversionMark;
                        }
                    }
                }
            }
        }
        // dd($sheetData);
        // -----New Exam Marks code finishes here-----

        $allStudentIds = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'batch' => $batch->id
        ])->pluck('std_id');

        $academicYear = ($request->yearId) ? AcademicsYear::findOrFail($request->yearId) : null;
        $term = ($request->termId) ? Semester::findOrFail($request->termId) : null;
        $exam = ($request->examId) ? ExamName::findOrFail($request->examId) : null;

        $examMarkParameters = ExamMarkParameter::get()->keyBy('id');

        $examMarks = ExamMark::with('subject')->where([
            'student_id' => $request->studentId,
            ['academic_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['semester_id', 'like', ($request->termId) ? $request->termId : '%'],
            ['exam_id', 'like', ($request->examId) ? $request->examId : '%'],
        ])->get()->groupBy('subject_id')->all();

        $allExamMarks = ExamMark::with('subject')->where([
            ['academic_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['semester_id', 'like', ($request->termId) ? $request->termId : '%'],
            ['exam_id', 'like', ($request->examId) ? $request->examId : '%'],
        ])->whereIn('student_id', $allStudentIds)->get()->groupBy('subject_id')->all();

        $allExamMarksGroupByStd = ExamMark::with('subject')->where([
            ['academic_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['semester_id', 'like', ($request->termId) ? $request->termId : '%'],
            ['exam_id', 'like', ($request->examId) ? $request->examId : '%'],
        ])->whereIn('student_id', $allStudentIds)->get()->groupBy('student_id')->all();

        $grades = GradeDetails::all();

        $coCurricularMarks = EventMark::with('event')->where([
            'student_id' => $request->studentId,
            'performance_type_id' => 1,
            ['mark', '!=', null],
        ])->get();

        $extraCurricularMarks = EventMark::with('event')->where([
            'student_id' => $request->studentId,
            'performance_type_id' => 9,
            ['mark', '!=', null],
        ])->get();

        $healthMarks = HealthPrescription::where([
            'patient_type' => 1,
            'patient_id' => $request->studentId,
            ['score', '!=', null]
        ])->get();

        $disciplineMarks = CadetAssesment::where([
            'student_id' => $request->studentId,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'type' => 4,
            ['academics_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['cadet_performance_activity_id', '!=', null]
        ])->get();

        $psychologyMarks = CadetAssesment::where([
            'student_id' => $request->studentId,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'type' => 2,
            ['academics_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
        ])->get();

        if ($academicYear) {
            $startDate = Carbon::parse($academicYear->start_date);
            $endDate = Carbon::parse($academicYear->end_date);

            $coCurricularMarks = $coCurricularMarks->where('date_time', '>=', $startDate)->where('date_time', '<=', $endDate);
            $extraCurricularMarks = $extraCurricularMarks->where('date_time', '>=', $startDate)->where('date_time', '<=', $endDate);
            $healthMarks = $healthMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
        }

        if ($term) {
            if ($academicYear) {
                $currentYear = $academicYear->year_name;
            }else{
                $currentYear = Carbon::now()->format('Y');
            }
            $startDate = Carbon::parse($term->start_day . '-' . $term->start_month . '-' . $currentYear);
            $endDate = Carbon::parse($term->end_day . '-' . $term->end_month . '-' . $currentYear);

            $coCurricularMarks = $coCurricularMarks->where('date_time', '>=', $startDate)->where('date_time', '<=', $endDate);
            $extraCurricularMarks = $extraCurricularMarks->where('date_time', '>=', $startDate)->where('date_time', '<=', $endDate);
            $healthMarks = $healthMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $disciplineMarks = $disciplineMarks->where('date', '>=', $startDate)->where('date', '<=', $endDate);
            $psychologyMarks = $psychologyMarks->where('date', '>=', $startDate)->where('date', '<=', $endDate);
        }

        $coCurricularMarks = $coCurricularMarks->groupBy('event_id')->all();
        $extraCurricularMarks = $extraCurricularMarks->groupBy('event_id')->all();
        $disciplineMarks = $disciplineMarks->groupBy('cadet_performance_activity_id')->all();
        $psychologyMarks = $psychologyMarks->groupBy('cadet_performance_category_id')->all();

        $warnings = CadetWarning::where([
            'student_id' => $request->studentId,
            ['academic_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['semester_id', 'like', ($request->termId) ? $request->termId : '%'],
        ])->get();

        $allRemarks = ReportRemark::where([
            'student_id' => $request->studentId,
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
        ])->get();

        $layouts = DetailReportLayout::all();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('student::pages.cadet-reports.cadet-detail-reports-pdf', compact(
            'institute',
            'academicYear',
            'term',
            'exam',
            'examCategories',
            'termFinalExamCategory',
            'subjects',
            'subjectMarksExamWise',
            'sheetData',
            'firstLastStudents',
            'totalCriteriaIds',
            'totalExamCatIds',
            'minMaxSubjectMarks',
            'student',
            'allStudentIds',
            'examMarkParameters',
            'examMarks',
            'allExamMarks',
            'allExamMarksGroupByStd',
            'grades',
            'coCurricularMarks',
            'extraCurricularMarks',
            'healthMarks',
            'disciplineMarks',
            'psychologyMarks',
            'warnings',
            'allRemarks',
            'layouts',
        ))->setPaper('a4', 'portrait');
        return $pdf->stream('cadet-detail-report.pdf');
    }



    public function transcript()
    {
        $academicLevels = $this->academicsLevel->all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $terms = Semester::all();

        return view('student::pages.cadet-reports.cadet-transcript-reports', compact('academicLevels', 'academicYears', 'terms'));
    }

    public function searchStudentsForTranscriptReport(Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/transcript/reports']);
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

        return view('student::pages.cadet-reports.transcript-student-list', compact('pageAccessData', 'students'))->render();
    }

    public function printSummaryTranscriptReport(Request $request)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $student = StudentProfileView::with('singleBatch', 'singleSection')->where('std_id', $request->studentId)->first();
        $studentInfo = StudentInformation::findOrFail($request->studentId);

        $academicYear = ($request->yearId) ? AcademicsYear::findOrFail($request->yearId) : null;
        $term = ($request->termId) ? Semester::findOrFail($request->termId) : null;

        $coCurricularMarks = EventMark::with('event')->where([
            'student_id' => $request->studentId,
            'performance_type_id' => 1,
            ['mark', '!=', null],
        ])->get();

        $extraCurricularMarks = EventMark::with('event')->where([
            'student_id' => $request->studentId,
            'performance_type_id' => 9,
            ['mark', '!=', null],
        ])->get();

        $disciplineMarks = CadetAssesment::where([
            'student_id' => $request->studentId,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'type' => 4,
            ['academics_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['cadet_performance_activity_id', '!=', null]
        ])->get();

        $psychologyMarks = CadetAssesment::where([
            'student_id' => $request->studentId,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'type' => 2,
            ['academics_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
        ])->get();

        $healthMarks = HealthPrescription::where([
            'patient_type' => 1,
            'patient_id' => $request->studentId,
            ['score', '!=', null]
        ])->get();

        if ($academicYear) {
            $startDate = Carbon::parse($academicYear->start_date);
            $endDate = Carbon::parse($academicYear->end_date);

            $coCurricularMarks = $coCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $extraCurricularMarks = $extraCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $healthMarks = $healthMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
        }

        if ($term) {
            $currentYear = Carbon::now()->format('Y');
            $startDate = Carbon::parse($term->start_day . '-' . $term->start_month . '-' . $currentYear);
            $endDate = Carbon::parse($term->end_day . '-' . $term->end_month . '-' . $currentYear);

            $coCurricularMarks = $coCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $extraCurricularMarks = $extraCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $healthMarks = $healthMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $disciplineMarks = $disciplineMarks->where('date', '>=', $startDate)->where('date', '<=', $endDate);
            $psychologyMarks = $psychologyMarks->where('date', '>=', $startDate)->where('date', '<=', $endDate);
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('student::pages.cadet-reports.cadet-summary-transcript-pdf', compact(
            'institute',
            'academicYear',
            'term',
            'student',
            'studentInfo',
            'coCurricularMarks',
            'extraCurricularMarks',
            'disciplineMarks',
            'psychologyMarks',
            'healthMarks',
        ))->setPaper('a4', 'portrait');
        return $pdf->stream('cadet-summary-transcript.pdf');
    }

    public function printDetailTranscriptReport(Request $request)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $student = StudentProfileView::with('singleBatch', 'singleSection')->where('std_id', $request->studentId)->first();
        $studentInfo = StudentInformation::findOrFail($request->studentId);

        $academicYear = ($request->yearId) ? AcademicsYear::findOrFail($request->yearId) : null;
        $term = ($request->termId) ? Semester::findOrFail($request->termId) : null;

        $coCurricularMarks = EventMark::with('event')->where([
            'student_id' => $request->studentId,
            'performance_type_id' => 1,
            ['mark', '!=', null],
        ])->get();

        $extraCurricularMarks = EventMark::with('event')->where([
            'student_id' => $request->studentId,
            'performance_type_id' => 9,
            ['mark', '!=', null],
        ])->get();

        $healthMarks = HealthPrescription::where([
            'patient_type' => 1,
            'patient_id' => $request->studentId,
            ['score', '!=', null]
        ])->get();

        $disciplineMarks = CadetAssesment::where([
            'student_id' => $request->studentId,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'type' => 4,
            ['academics_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
            ['cadet_performance_activity_id', '!=', null]
        ])->get();

        $psychologyMarks = CadetAssesment::where([
            'student_id' => $request->studentId,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'type' => 2,
            ['academics_year_id', 'like', ($request->yearId) ? $request->yearId : '%'],
        ])->get();

        if ($academicYear) {
            $startDate = Carbon::parse($academicYear->start_date);
            $endDate = Carbon::parse($academicYear->end_date);

            $coCurricularMarks = $coCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $extraCurricularMarks = $extraCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $healthMarks = $healthMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
        }

        if ($term) {
            $currentYear = Carbon::now()->format('Y');
            $startDate = Carbon::parse($term->start_day . '-' . $term->start_month . '-' . $currentYear);
            $endDate = Carbon::parse($term->end_day . '-' . $term->end_month . '-' . $currentYear);

            $coCurricularMarks = $coCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $extraCurricularMarks = $extraCurricularMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $healthMarks = $healthMarks->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            $disciplineMarks = $disciplineMarks->where('date', '>=', $startDate)->where('date', '<=', $endDate);
            $psychologyMarks = $psychologyMarks->where('date', '>=', $startDate)->where('date', '<=', $endDate);
        }

        $coCurricularMarks = $coCurricularMarks->groupBy('event_id')->all();
        $extraCurricularMarks = $extraCurricularMarks->groupBy('event_id')->all();
        $disciplineMarks = $disciplineMarks->groupBy('cadet_performance_activity_id')->all();
        $psychologyMarks = $psychologyMarks->groupBy('cadet_performance_category_id')->all();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('student::pages.cadet-reports.cadet-detail-transcript-pdf', compact(
            'institute',
            'academicYear',
            'term',
            'student',
            'studentInfo',
            'coCurricularMarks',
            'extraCurricularMarks',
            'disciplineMarks',
            'psychologyMarks',
            'healthMarks',
        ))->setPaper('a4', 'portrait');
        return $pdf->stream('cadet-detail-transcript.pdf');
    }
}
