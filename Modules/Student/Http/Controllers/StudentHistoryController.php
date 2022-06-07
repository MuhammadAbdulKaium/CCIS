<?php

namespace Modules\Student\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HealthCare\Entities\HealthPrescription;
use Modules\House\Entities\CommunicationRecord;
use Modules\House\Entities\House;
use Modules\House\Entities\HouseAppoint;
use Modules\House\Entities\HouseAppointHistory;
use Modules\House\Entities\HouseHistory;
use Modules\House\Entities\PocketMoneyHistory;
use Modules\House\Entities\Room;
use Modules\Mess\Entities\MessTable;
use Modules\Mess\Entities\MessTableHistory;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;

class StudentHistoryController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($id, Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';
        $tab = '';

        return view('student::pages.student-profile.student-history', compact('pageAccessData', 'personalInfo', 'page', 'tab'));
    }

    public function houseHistory(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);

        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';
        $tab = 'house';

        $houseHistoriesJson = HouseHistory::where('student_id', $personalInfo->id)->value('house_history');
        if ($houseHistoriesJson) {
            $houseHistories = array_reverse(json_decode($houseHistoriesJson, true));
        } else {
            $houseHistories = [];
        }

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $rooms = Room::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('student::pages.student-profile.student-house-history', compact('pageAccessData', 'personalInfo', 'page', 'tab', 'houseHistories', 'houses', 'rooms'));
    }

    public function houseAppointHistory(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);

        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';
        $tab = 'house-appoint';

        $houseAppointHistories = HouseAppointHistory::with('appoint')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'user_id' => $personalInfo->user()->id
        ])->latest()->get();

        return view('student::pages.student-profile.student-house-appoint-history', compact('pageAccessData', 'personalInfo', 'page', 'tab', 'houseAppointHistories'));
    }

    public function pocketMoneyHistory(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);

        $personalInfo = StudentInformation::findOrFail($id);
        $studentProfile = StudentProfileView::where('user_id', $personalInfo->user_id)->first();
        $page = 'history';
        $tab = 'pocket-money';

        $pocketMoneyHistories = PocketMoneyHistory::with('bankBranch', 'user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'std_id' => $studentProfile->std_id
        ])->latest()->get();

        return view('student::pages.student-profile.student-house-pocket-money', compact('pageAccessData', 'personalInfo', 'page', 'tab', 'pocketMoneyHistories'));
    }

    public function messTableHistory(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);
        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';
        $tab = 'mess-table';

        $tableHistories = MessTableHistory::with('table')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'person_type' => 1,
            'person_id' => $id
        ])->latest()->get();

        // dd($tableHistories);

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');

        return view('student::pages.student-profile.student-mess-table-history', compact('pageAccessData', 'personalInfo', 'page', 'tab', 'tableHistories', 'houses'));
    }

    public function medicalHistory(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);

        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';
        $tab = 'medical';

        $medicalHistories = HealthPrescription::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'patient_type' => 1,
            'patient_id' => $id
        ])->orderByDesc('id')->get();

        return view('student::pages.student-profile.student-medical-history', compact('pageAccessData', 'personalInfo', 'page', 'tab', 'medicalHistories'));
    }

    public function communicationHistory(Request $request, $id)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'student/manage']);

        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';
        $tab = 'communication';

        $communicationRecords = CommunicationRecord::with('academicYear')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'student_id' => $id,
        ])->orderByDesc('id')->get();

        return view('student::pages.student-profile.student-communication-history', compact('pageAccessData', 'personalInfo', 'page', 'tab', 'communicationRecords'));
    }
}
