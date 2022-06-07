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
use Modules\Academics\Entities\Batch;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\CommunicationRecord;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;
use Modules\House\Http\Requests\CommunicationRecordRequest;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentProfileView;
use PDF;

class CommunicationRecordController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }



    public function index($id = null,Request $request)
    {
        $pageAccessData = self::linkAccess($request  );

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
                'status'=>1
            ])->whereIn('std_id', $studentIds)->get();
        } else {
            $students = [];
        }

        $academicYears = $this->academicHelper->getAllAcademicYears();
        $batches = Batch::all();

        return view('house::communication-record.index', compact('pageAccessData','selectedHouse', 'houses', 'students', 'academicYears', 'batches'));
    }



    public function create(Request $request)
    {
        return redirect('/house/communication-records/' . $request->houseId);
    }



    public function store(Request $request)
    {
        $date = Carbon::parse($request->date);
        $fromTime = Carbon::parse($request->fromTime);
        $toTime = Carbon::parse($request->toTime);
        $admissionYearId = StudentProfileView::where('std_id', $request->studentId)->first()->enroll()->admission_year;

        DB::beginTransaction();
        try {
            CommunicationRecord::insert([
                'house_id' => $request->houseId,
                'student_id' => $request->studentId,
                'admission_year_id' => $admissionYearId,
                'academic_year_id' => $request->yearId,
                'date' => $date->format('Y-m-d'),
                'month' => (int)$date->format('m'),
                'mode' => $request->mode,
                'from_time' => $fromTime->format('H:i:s'),
                'to_time' => $toTime->format('H:i:s'),
                'communication_topics' => $request->communicationTopics,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            DB::commit();
            Session::flash('message', 'Success! Communication record added successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error adding communication record.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        $house = House::findOrFail($id);
        $studentIds = RoomStudent::where('house_id', $house->id)->pluck('student_id');
        $students = StudentProfileView::with('singleUser')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'status'=>1
        ])->whereIn('std_id', $studentIds)->get();
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('house::communication-record.modal.create-record', compact('house', 'students', 'academicYears'));
    }



    public function edit($id)
    {
        return view('house::edit');
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }


    public function searchCommunicationRecords(Request $request)
    {
        $groupedCommunicationRecords = CommunicationRecord::with('academicYear', 'admissionYear', 'student.singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'academic_year_id' => $request->yearId,
            'month' => $request->month,
        ])->get();

        if ($request->mode) {
            $groupedCommunicationRecords = $groupedCommunicationRecords->where('mode', $request->mode);
        }

        if ($request->studentId) {
            $groupedCommunicationRecords = $groupedCommunicationRecords->where('student_id', $request->studentId);
        } elseif ($request->sectionId) {
            $studentIds = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->sectionId,
                'status'=>1
            ])->pluck('std_id');
            $groupedCommunicationRecords = $groupedCommunicationRecords->whereIn('student_id', $studentIds);
        } elseif ($request->batchId) {
            $studentIds = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'batch' => $request->batchId,
                'status'=>1
            ])->pluck('std_id');
            $groupedCommunicationRecords = $groupedCommunicationRecords->whereIn('student_id', $studentIds);
        }

        $groupedCommunicationRecords = $groupedCommunicationRecords->groupBy('student_id')->all();

        return view('house::communication-record.communication-record-table', compact('groupedCommunicationRecords'))->render();
    }

    public function printCommunicationRecords(Request $request)
    {
        $groupedCommunicationRecords = CommunicationRecord::with('academicYear', 'admissionYear', 'student.singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'academic_year_id' => $request->yearId,
            'month' => $request->month,
        ])->get();

        if ($request->mode) {
            $groupedCommunicationRecords = $groupedCommunicationRecords->where('mode', $request->mode);
        }

        if ($request->studentId) {
            $groupedCommunicationRecords = $groupedCommunicationRecords->where('student_id', $request->studentId);
        } elseif ($request->sectionId) {
            $studentIds = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->sectionId,
                'status'=>1
            ])->pluck('std_id');
            $groupedCommunicationRecords = $groupedCommunicationRecords->whereIn('student_id', $studentIds);
        } elseif ($request->batchId) {
            $studentIds = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'batch' => $request->batchId,
                'status'=>1
            ])->pluck('std_id');
            $groupedCommunicationRecords = $groupedCommunicationRecords->whereIn('student_id', $studentIds);
        }

        $groupedCommunicationRecords = $groupedCommunicationRecords->groupBy('student_id')->all();

        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $house = House::findOrFail($request->houseId);

        $pdf = PDF::loadView('house::communication-record.communication-record-pdf', compact('groupedCommunicationRecords', 'institute', 'house'));
        return $pdf->download('communication_records.pdf');
    }
}
