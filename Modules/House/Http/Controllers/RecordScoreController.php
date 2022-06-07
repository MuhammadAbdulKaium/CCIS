<?php

namespace Modules\House\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\House;
use Modules\House\Entities\RecordScore;
use Modules\House\Entities\RoomStudent;
use Modules\House\Http\Requests\RecordScoreRequest;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\StudentProfileView;

class RecordScoreController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }



    public function index($id = null,Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $selectedHouse = ($id) ? House::findOrFail($id) : null;
        // if ($selectedHouse) {
        //     if ($selectedHouse->houseMaster->user_id != Auth::id()) {
        //         Session::flash('errorMessage', 'You have no permission to access that page.');
        //         return redirect()->back();
        //     }
        // }

        $employee = EmployeeInformation::where('user_id', Auth::id())->first();
        if ($employee) {
            $houses = House::with('rooms')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'employee_id' => $employee->id
            ])->get();
        } else {
            // Need to make null array
            $houses = House::with('rooms')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->get();
        }

        if ($selectedHouse) {
            $studentIds = RoomStudent::where('house_id', $selectedHouse->id)->pluck('student_id');
            $students = StudentProfileView::with('singleUser')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->whereIn('std_id', $studentIds)->get();
        } else {
            $students = [];
        }

        $academicYears = $this->academicHelper->getAllAcademicYears();
        $batches = Batch::all();

        return view('house::record-score.index', compact('pageAccessData','selectedHouse', 'houses', 'students', 'academicYears', 'batches'));
    }



    public function create(Request $request)
    {
        return redirect('/house/record-score/' . $request->houseId);
    }



    public function store(RecordScoreRequest $request)
    {
        $studentIds = RoomStudent::where('house_id', $request->houseId)->pluck('student_id');
        if ($request->sectionId) {
            $students = StudentProfileView::whereIn('std_id', $studentIds)->where('section', $request->sectionId)->get();
        } else {
            $students = StudentProfileView::whereIn('std_id', $studentIds)->where('batch', $request->batchId)->get();
        }

        DB::beginTransaction();
        try {
            foreach ($students as $key => $student) {
                $previousRecord = RecordScore::where([
                    'house_id' => $request->houseId,
                    'academic_year_id' => $request->yearId,
                    'semester_id' => $request->termId,
                    'student_id' => $student->std_id
                ])->first();

                if ($previousRecord) {
                    $previousRecord->update([
                        'category_id' => $request->categoryId,
                        'score' => $request->score,
                        'remarks' => $request->remarks,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    RecordScore::insert([
                        'house_id' => $request->houseId,
                        'academic_year_id' => $request->yearId,
                        'semester_id' => $request->termId,
                        'student_id' => $student->std_id,
                        'admission_year_id' => $student->enroll()->admission_year,
                        'date' => Carbon::parse($request->date)->toDateString(),
                        'category_id' => $request->categoryId,
                        'score' => $request->score,
                        'remarks' => $request->remarks,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Record score added successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error adding record score.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        $house = House::findOrFail($id);
        $batches = Batch::all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $performanceTypes = CadetPerformanceType::all();

        return view('house::record-score.modal.create-record-score', compact('house', 'batches', 'academicYears', 'performanceTypes'));
    }



    public function edit($id)
    {
        return view('house::edit');
    }



    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->studentIds as $key => $studentId) {
                $record = RecordScore::where([
                    'house_id' => $request->houseId,
                    'academic_year_id' => $request->yearId,
                    'semester_id' => $request->semesterId,
                    'student_id' => $studentId
                ])->first();

                $record->update([
                    'category_id' => $request->categoryIds[$studentId],
                    'score' => $request->scores[$studentId],
                    'remarks' => $request->remarks[$studentId],
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();
            Session::flash('message', 'Success! Record score updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating record score.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        //
    }


    public function recordScoreHistory($id)
    {
        $student = StudentProfileView::where('std_id', $id)->first();
        $recordScores = RecordScore::with('academicYear', 'term', 'category')->where('student_id', $id)->orderByDesc('id')->get();
        return view('house::record-score.modal.record-score-history', compact('recordScores', 'student'));
    }


    public function getTermFromYear(Request $request)
    {
        if ($request->yearId) {
            return AcademicsYear::findOrFail($request->yearId)->semesters();
        } else {
            return [];
        }
    }

    public function getSectionsFromBatch(Request $request)
    {
        if ($request->batchId) {
            return Batch::findOrFail($request->batchId)->section();
        } else {
            return [];
        }
    }

    public function searchRecordScores(Request $request)
    { $pageAccessData = self::linkAccess($request  , ['manualRoute'=>'house/record-score']);
        $recordScores = RecordScore::with('student.singleUser', 'admissionYear', 'academicYear')->where([
            'house_id' => $request->houseId,
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
        ])->get();

        if ($request->studentId) {
            $recordScores = $recordScores->where('student_id', $request->studentId);
        } elseif ($request->sectionId) {
            $studentIds = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->sectionId
            ])->pluck('std_id');
            $recordScores = $recordScores->whereIn('student_id', $studentIds);
        } elseif ($request->batchId) {
            $studentIds = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'batch' => $request->batchId
            ])->pluck('std_id');
            $recordScores = $recordScores->whereIn('student_id', $studentIds);
        }

        $performanceTypes = CadetPerformanceType::all();

        return view('house::record-score.record-score-table', compact('recordScores','pageAccessData', 'performanceTypes'))->render();
    }
}
