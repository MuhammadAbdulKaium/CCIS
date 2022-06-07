<?php

namespace Modules\Academics\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\BoardExamMarkDetail;
use Modules\Academics\Entities\BoardExamResult;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\Subject;
use Modules\Student\Entities\StudentProfileView;

class BoardExamResultController extends Controller
{
    private $academicHelper;
    private $classSubject;

    public function __construct(AcademicHelper $academicHelper, ClassSubject $classSubject)
    {
        $this->academicHelper = $academicHelper;
        $this->classSubject = $classSubject;
    }

    public function boardExamStudentSearch(Request $request)
    {

        $yearId = $request->yearId;

        $getClass = $request->batchId;
        $getSection = $request->sectionId;


        $getStudent = StudentProfileView::with('singleUser')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'batch' => $getClass,
            'section' => $getSection,
        ])->get();


        $stdListView = view('academics::exam.board-exam-result-list', compact('getStudent', 'yearId', 'getClass', 'getSection'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }


    public function test(Request $request)
    {
        return dd($request->all());
    }

    public function getSubjectsFromBatch($batchId)
    {
        $subjectIds = ClassSubject::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'class_id' => $batchId
        ])->pluck('subject_id');
        return Subject::whereIn('id', $subjectIds)->get();
    }

    public function searchPublicExamStudent(Request $request)
    {
        $yearId = $request->yearId;
        $getClass = $request->batchId;
        if (isset($request->sectionId)) {
            $getSection = $request->sectionId;
            $sectionName = $this->academicHelper->findSection($getSection);
        } else {
            $getSection = null;
            $sectionName = null;
        }

        $boardExamType = $request->boardExamType;

        $yearName = $this->academicHelper->findYear($yearId);
        $className = $this->academicHelper->findBatch($getClass);
        if ($request->sectionId) {
            $studentIdList = BoardExamResult::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $yearId,
                'batch_id' => $getClass,
                'section_id' => $getSection,
                'board_exam_type' => $boardExamType
            ])->pluck('student_id');
        } else {
            $studentIdList = BoardExamResult::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $yearId,
                'batch_id' => $getClass,
                'board_exam_type' => $boardExamType
            ])->pluck('student_id');
        }

        if (sizeof($studentIdList) > 0) {
            $boardResults = BoardExamResult::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $yearId,
                'batch_id' => $getClass,
                'board_exam_type' => $boardExamType
            ])->get()->keyBy('student_id');
            $getStudent = StudentProfileView::whereIn('std_id', $studentIdList)->get()->keyby('std_id');
        } else {

            $getStudent = StudentProfileView::with('singleUser')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'batch' => $getClass,
            ])->get();
            $boardResults = null;
            if ($request->sectionId) {
                $getStudent = $getStudent->where('section', $getSection);
            }
        }
        $getSubject = $this->classSubject->getClassSubjectList($getClass);

        $stdListView = view('academics::exam.board-exam-result-list', compact('getStudent', 'boardExamType', 'studentIdList', 'yearId', 'getClass', 'getSection', 'getSubject', 'yearName', 'className', 'sectionName', 'boardResults'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView, 'all' => $getSubject];
    }

    public function boardExamResult()
    {

        $academicYears = AcademicsYear::all();
        return view('academics::exam.board-exam-result', compact('academicYears'));
    }

    public function boardExamSearchClass(Request $request)
    {
        if ($request->yearId) {
            return AcademicsYear::findOrFail($request->yearId)->batchList();
        } else {
            return [];
        }
    }
    public function getSectionWithStudentId($id)
    {
        $studentInfo = StudentProfileView::where('std_id', '=', $id)->first();
        return $studentInfo->section;
    }
    public function getClassWithStudentId($id)
    {
        $studentInfo = StudentProfileView::where('std_id', '=', $id)->first();
        return $studentInfo->batch;
    }
    public function publicExamSaveStudentMarks(Request $request)
    {
        $students = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get()->keyby('std_id');

        $getSection = $request->getSection;


        DB::beginTransaction();
        try {
            foreach ($request->boardExamReg as $key => $mark) {
                $previousExamMark = BoardExamResult::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $request->academicYearId,
                    'batch_id' => $this->getClassWithStudentId($key),
                    'section_id' => $this->getSectionWithStudentId($key),
                    'student_id' => $key,
                    'board_exam_type' => $request->boardExamType,
                ])->first();

                if ($previousExamMark) {
                    $previousExamMark->update([
                        'board_name' => $request->boardName[$key],
                        'session_year' => $request->sessionYear[$key],
                        'board_exam_roll' => $request->boardExamRoll[$key],
                        'board_exam_reg' => $request->boardExamReg[$key],
                        'total_gpa' => $request->totalGpa[$key],
                        'total_marks' => $request->totalMarks[$key],
                        'total_score' => $request->totalScore[$key],
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ]);
                    foreach ($request->gpa as $subkey => $submark) {
                        $boardMarkDetails = BoardExamMarkDetail::where('cadet_board_exam_result_id', '=', $previousExamMark->id)
                            ->where('subject_id', '=', $subkey)->first();
                        if ($boardMarkDetails) {
                            $boardMarkDetails->update([
                                'subject_gpa' => $request->gpa[$subkey][$key],
                                'subject_marks' => $request->marks[$subkey][$key],
                                'subject_score' => $request->score[$subkey][$key],
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()

                            ]);
                        } else {
                            BoardExamMarkDetail::insert([
                                'cadet_board_exam_result_id' => $boardMarkDetails->id,
                                'subject_id' => $subkey,
                                'subject_gpa' => $request->gpa[$subkey][$key],
                                'subject_marks' => $request->marks[$subkey][$key],
                                'subject_score' => $request->score[$subkey][$key],
                                'created_by' => Auth::id(),
                                'crated_at' => Carbon::now()
                            ]);
                        }
                    }
                } else {
                    BoardExamResult::insert([
                        'batch_id' => $this->getClassWithStudentId($key),
                        'section_id' => $this->getSectionWithStudentId($key),
                        'student_id' => $key,
                        'academic_year_id' => $request->academicYearId,
                        'session_year' => $request->sessionYear[$key],
                        'board_exam_type' => $request->boardExamType,
                        'board_name' => $request->boardName[$key],
                        'board_exam_roll' => $request->boardExamRoll[$key],
                        'board_exam_reg' => $request->boardExamReg[$key],
                        'total_gpa' => $request->totalGpa[$key],
                        'total_marks' => $request->totalMarks[$key],
                        'total_score' => $request->totalScore[$key],
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now()
                    ]);
                    $examMark = BoardExamResult::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'academic_year_id' => $request->academicYearId,
                        'batch_id' => $this->getClassWithStudentId($key),
                        'section_id' => $this->getSectionWithStudentId($key),
                        'student_id' => $key,
                        'board_exam_type' => $request->boardExamType
                    ])->first();
                    foreach ($request->gpa as $subkey => $submark) {
                        BoardExamMarkDetail::insert([
                            'cadet_board_exam_result_id' => $examMark->id,
                            'subject_id' => $subkey,
                            'subject_gpa' => $request->gpa[$subkey][$key],
                            'subject_marks' => $request->marks[$subkey][$key],
                            'subject_score' => $request->score[$subkey][$key],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }


            DB::commit();
            Session::flash('message', 'Exam marks saved successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Saving Exam Marks.');
            return redirect()->back();
        }
    }

    public function finalSheet()
    {
        return view('academics::exam.final-sheet');
    }
    public function finalSheetPdf()
    {
        $pdf = App::make('dompdf.wrapper');
        // load semester report card view
        $pdf->loadView('academics::exam.final-sheet-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('academics::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('academics::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('academics::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('academics::edit');
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
