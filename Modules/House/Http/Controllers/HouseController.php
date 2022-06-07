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
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\House;
use Modules\House\Entities\HouseAppoint;
use Modules\House\Entities\HouseAppointHistory;
use Modules\House\Entities\HouseAppointUser;
use Modules\House\Entities\HouseHistory;
use Modules\House\Entities\Room;
use Modules\House\Entities\RoomStudent;
use Modules\House\Entities\WeightageConfig;
use Modules\House\Http\Requests\HouseRequest;
use Modules\House\Http\Requests\RoomRequest;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Student\Entities\StudentProfileView;
use App;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use PDF;
class HouseController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $houses = House::with('rooms', 'housePrefect.singleUser', 'houseMaster.singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $rooms = Room::with('house', 'roomStudents')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $employeeIds = House::with('rooms', 'roomStudents')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('employee_id');

        $employees = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereNotIn('id', $employeeIds)->get();

        return view('house::index', compact('houses', 'pageAccessData', 'rooms', 'employees'));
    }
    // print house
    public function print($id){
        $campus= Campus::where('institute_id',$this->academicHelper->getInstitute())->first();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $house = House::with('rooms', 'housePrefect.singleUser', 'houseMaster.singleUser')->findOrFail($id);
        $maxTd = 0;
        $rooms = $house->rooms->groupBy('floor_no');
        foreach ($rooms as $floors) {
            if (sizeof($floors)>$maxTd) {
                $maxTd = sizeof($floors);
            }
        }
       $roomStudents = RoomStudent::with('student.singleUser', 'student.singleBatch', 'student.singleSection')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->get();
        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'status'=>1
        ]);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('house::print-house',compact('house', 'rooms', 'maxTd', 'roomStudents', 'students','campus','institute'))->setPaper('a2', 'landscape');
        return $pdf->stream();
        // return view('house::print-house', compact('house', 'rooms', 'maxTd', 'roomStudents', 'students','campus','institute'));
    }

    public function create()
    {
        return view('house::create');
    }


    public function store(HouseRequest $request)
    {
        //Validation start
        $sameNameHouse = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();
        $sameAliasHouse = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'alias' => $request->name
        ])->first();
        if ($sameNameHouse) {
            Session::flash('errorMessage', 'Error! There is already a house with this name.');
            return redirect()->back();
        }
        if ($sameAliasHouse) {
            Session::flash('errorMessage', 'Error! There is already a house with this alias.');
            return redirect()->back();
        }
        //Validation end

        DB::beginTransaction();
        try {
            $newHouseId = House::insertGetId([
                'name' => $request->name,
                'bengali_name' => $request->bengali_name,
                'alias' => $request->alias,
                'no_of_floors' => $request->floors,
                'employee_id' => $request->employeeId,
                'house_master_history' => json_encode([[
                    'employeeId' => $request->employeeId,
                    'assignedDate' => Carbon::now()
                ]]),
                'motto' => $request->motto,
                'bengali_motto' => $request->bengali_motto,
                'color_name' => $request->color_name,
                'color' => $request->color,
                'symbol_name' => $request->symbol_name,
                'symbol' => $request->symbol,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            // Generating House Master History Start for cadet_house_table
            $houseHistory = HouseHistory::where('employee_id', $request->employeeId)->first();

            if ($houseHistory) {
                $houseHistoryData = json_decode($houseHistory->house_master_history, true);
                $generatedData = [
                    'houseId' => $newHouseId,
                    'fromDate' => Carbon::now(),
                    'toDate' => null,
                ];
                array_push($houseHistoryData, $generatedData);

                $houseHistory->update([
                    'house_master_history' => json_encode($houseHistoryData),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                HouseHistory::insert([
                    'employee_id' => $request->employeeId,
                    'house_master_history' => json_encode([[
                        'houseId' => $newHouseId,
                        'fromDate' => Carbon::now(),
                        'toDate' => null,
                    ]]),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }
            // Generating House Master History End for cadet_house_table

            if ($newHouseId) {
                DB::commit();
                Session::flash('message', 'Success! New House Created Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error! Error creating house.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error! Error creating house.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('house::show');
    }


    public function edit($id)
    {
        $house = House::findOrFail($id);

        $employeeIds = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('employee_id')->toArray();

        if (($key = array_search($house->employee_id, $employeeIds)) !== false) {
            unset($employeeIds[$key]);
        }

        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereNotIn('id', $employeeIds)->get();

        $studentIds = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->pluck('student_id');
        $students = StudentProfileView::whereIn('std_id', $studentIds)->get();

        return view('house::modal.edit_house', compact('house', 'employees', 'students'));
    }


    public function update(HouseRequest $request, $id)
    {
        $house = House::findOrFail($id);

        $sameNameHouse = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        $maxFloor = Room::where('house_id', $house->id)->max('floor_no');

        if ($maxFloor > $request->floors) {
            Session::flash('errorMessage', 'Error! Can not decrease floors, room assigned at top floors.');
            return redirect()->back();
        }

        if ($sameNameHouse) {
            if ($house->id != $sameNameHouse->id) {
                Session::flash('errorMessage', 'Error! There is already a house in this name.');
                return redirect()->back();
            }
        }

        // Generation House Master History Start
        // To own table
        $houseMasterHistory = $house->house_master_history;

        if ($request->employeeId != $house->employee_id) {
            $houseMasterHistoryArray = json_decode($houseMasterHistory);

            array_push($houseMasterHistoryArray, [
                'employeeId' => $request->employeeId,
                'assignedDate' => Carbon::now()
            ]);

            $houseMasterHistory = json_encode($houseMasterHistoryArray);
        }

        // To cadet_house_history table
        if ($request->employeeId != $house->employee_id) {
            $houseHistory = HouseHistory::where('employee_id', $request->employeeId)->first();
            if ($houseHistory) {
                $houseHistoryData = json_decode($houseHistory->house_master_history, true);
                $generatedData = [
                    'houseId' => $house->id,
                    'fromDate' => Carbon::now(),
                    'toDate' => null,
                ];
                array_push($houseHistoryData, $generatedData);

                $houseHistory->update([
                    'house_master_history' => json_encode($houseHistoryData),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                HouseHistory::insert([
                    'employee_id' => $request->employeeId,
                    'house_master_history' => json_encode([[
                        'houseId' => $house->id,
                        'fromDate' => Carbon::now(),
                        'toDate' => null,
                    ]]),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            // Previous employee toDate change
            $previousHouseHistory = HouseHistory::where('employee_id', $house->employee_id)->first();
            $previousHouseHistoryData = json_decode($previousHouseHistory->house_master_history, true);
            $lastData = end($previousHouseHistoryData);
            $lastData['toDate'] = Carbon::now();

            array_pop($previousHouseHistoryData);
            array_push($previousHouseHistoryData, $lastData);

            $previousHouseHistory->update([
                'house_master_history' => json_encode($previousHouseHistoryData),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);
        }
        // Generation House Master History End


        // Generation House Prefect History Start
        $housePrefectHistory = $house->house_prefect_history;

        if ($request->studentId != $house->student_id) {
            // To own table
            if ($housePrefectHistory) {
                $housePrefectHistoryArray = json_decode($housePrefectHistory);
            } else {
                $housePrefectHistoryArray = [];
            }

            array_push($housePrefectHistoryArray, [
                'studentId' => $request->studentId,
                'assignedDate' => Carbon::now()
            ]);

            $housePrefectHistory = json_encode($housePrefectHistoryArray);

            // To cadet_house_history table
            $houseHistory = HouseHistory::where('student_id', $request->studentId)->first();
            $historyData = ['houseId' => $house->id, 'fromDate' => Carbon::now(), 'toDate' => null];

            if ($houseHistory) {
                if ($houseHistory->prefect_history) {
                    $previousHouseHistory = json_decode($houseHistory->prefect_history);
                    array_push($previousHouseHistory, $historyData);
                    $houseHistory->update([
                        'prefect_history' => json_encode($previousHouseHistory)
                    ]);

                    // previous prefect's to date set
                    $prevStudentHouseHistory = HouseHistory::where('student_id', $house->student_id)->first();
                    $previousHouseHistory = json_decode($prevStudentHouseHistory->prefect_history, true);
                    $lastData = end($previousHouseHistory);
                    $lastData['toDate'] = Carbon::now();

                    array_pop($previousHouseHistory);
                    array_push($previousHouseHistory, $lastData);

                    $prevStudentHouseHistory->update([
                        'prefect_history' => json_encode($previousHouseHistory)
                    ]);
                } else {
                    $houseHistory->update([
                        'prefect_history' => json_encode([$historyData])
                    ]);
                }
            }
        }
        // Generation House Prefect History End

        DB::beginTransaction();
        try {
            $updateHouse = $house->update([
                'name' => $request->name,
                'bengali_name' => $request->bengali_name,
                'alias' => $request->alias,
                'no_of_floors' => $request->floors,
                'employee_id' => $request->employeeId,
                'house_master_history' => $houseMasterHistory,
                'motto' => $request->motto,
                'bengali_motto' => $request->bengali_motto,
                'color_name' => $request->color_name,
                'color' => $request->color,
                'symbol_name' => $request->symbol_name,
                'symbol' => $request->symbol,
                'student_id' => $request->studentId,
                'house_prefect_history' => $housePrefectHistory,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            if ($updateHouse) {
                DB::commit();
                Session::flash('message', 'Success! House Updated Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error! Error updating house.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error! Error updating house.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $house = House::findOrFail($id);

        if (sizeof($house->rooms) > 0) {
            Session::flash('errorMessage', 'Dependencies Found! This House has rooms assigned to it.');
            return redirect()->back();
        } else {
            $house->delete();
            Session::flash('message', 'Success! House Deleted Successfully.');
            return redirect()->back();
        }
    }


    public function createRoom(RoomRequest $request)
    {
        $sameNameRoom = Room::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'name' => $request->name
        ])->first();
        if ($sameNameRoom) {
            Session::flash('errorMessage', 'Error! There is already a room in this house with the same name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertRoom = Room::insert([
                'house_id' => $request->houseId,
                'floor_no' => $request->floor,
                'name' => $request->name,
                'no_of_beds' => $request->beds,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($insertRoom) {
                DB::commit();
                Session::flash('message', 'Success! New Room Created Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating new Room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating new Room.');
            return redirect()->back();
        }
    }


    public function editRoom($id)
    {
        $room = Room::with('house')->findOrFail($id);
        $houses = House::with('rooms')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('house::modal.edit_room', compact('room', 'houses'));
    }

    public function updateRoom(RoomRequest $request, $id)
    {
        $room = Room::findOrFail($id);

        $sameNameRoom = Room::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'name' => $request->name
        ])->first();
        if ($sameNameRoom) {
            if ($room->id != $sameNameRoom->id) {
                Session::flash('errorMessage', 'Error! There is already a room in this house with the same name.');
                return redirect()->back();
            }
        }

        $maxBed = RoomStudent::where('room_id', $room->id)->max('bed_no');

        if ($maxBed > $request->beds) {
            Session::flash('errorMessage', 'Error! Can not decrease beds, student assigned at that bed.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $updateRoom = $room->update([
                'house_id' => $request->houseId,
                'floor_no' => $request->floor,
                'name' => $request->name,
                'no_of_beds' => $request->beds,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($updateRoom) {
                DB::commit();
                Session::flash('message', 'Success! Room Updated Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating Room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating Room.');
            return redirect()->back();
        }
    }

    public function deleteRoom($id)
    {
        $roomStudent = RoomStudent::where('room_id', $id)->first();

        if ($roomStudent) {
            Session::flash('errorMessage', 'Assigned students found in this room, make the beds blank first.');
            return redirect()->back();
        } else {
            Room::findOrFail($id)->delete();
            Session::flash('message', 'Success! Room deleted Successfully.');
            return redirect()->back();
        }
    }

    public function assignBeds($id)
    {
        $room = Room::findOrFail($id);
        $academicLevels = $this->academicHelper->getAllAcademicLevel();
        $roomStudents = RoomStudent::with('batch', 'section', 'student.singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'room_id' => $room->id,
        ])->get();

        return view('house::modal.assign_beds', compact('room', 'academicLevels', 'roomStudents'));
    }

    // Ajax Methods start
    public function findSectionsFromAcaemicLevel(Request $request)
    {
        if ($request->academicLevelId) {
            $batcheIds = AcademicsLevel::findOrFail($request->academicLevelId)->batch()->pluck('id');
            $sections = Section::with('singleBatch')->whereIn('batch_id', $batcheIds)->get();
            return $sections;
        } else {
            return [];
        }
    }

    public function findSectionsFromBatch(Request $request)
    {
        if ($request->batchId) {
            $batch = Batch::findOrFail($request->batchId);
            return $batch->section();
        } else {
            return [];
        }
    }

    public function findStudentsFromSection(Request $request)
    {
        $assignedStudentIds = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('student_id');

        if ($request->sectionId) {
            return StudentProfileView::with('singleUser')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->sectionId,
                'status'=>1
            ])->whereNotIn('std_id', $assignedStudentIds)->get();
        } else {
            return [];
        }
    }

    public function assignStudentToBed(Request $request)
    {
        $previousRoomStudent = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'floor_no' => $request->floorNo,
            'room_id' => $request->roomId,
            'bed_no' => $request->bedNo,
        ])->first();
        $student = StudentProfileView::where('std_id', $request->studentId)->first();

        if ($previousRoomStudent) {
            $isPrefect = House::where('student_id', $previousRoomStudent->student_id)->first();
            if ($isPrefect) {
                return 2;
            }
        }

        $houseHistoryData = [
            'fromDate' => Carbon::now(),
            'toDate' => null,
            'houseId' => $request->houseId,
            'floorNo' => $request->floorNo,
            'roomId' => $request->roomId,
            'bedNo' => $request->bedNo,
            'batchId' => $student->singleBatch->id,
            'sectionId' => $student->singleSection->id,
        ];
        $houseHistory = HouseHistory::where('student_id', $request->studentId)->first();

        DB::beginTransaction();
        try {
            $updateRoomStudent = null;
            $insertRoomStudent = null;

            // House history storing starts
            if ($houseHistory) {
                $houseHistoryArray = json_decode($houseHistory->house_history, true);
                array_push($houseHistoryArray, $houseHistoryData);

                $houseHistory->update([
                    'house_history' => json_encode($houseHistoryArray),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                HouseHistory::insert([
                    'student_id' => $request->studentId,
                    'house_history' => json_encode([$houseHistoryData]),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }
            // House history storing ends

            if ($previousRoomStudent) {
                // House history previous student to_date changing starts
                $houseHistory2 = HouseHistory::where('student_id', $previousRoomStudent->student_id)->first();

                $houseHistory2Array = json_decode($houseHistory2->house_history, true);
                $lastData = end($houseHistory2Array);
                $lastData['toDate'] = Carbon::now();

                array_pop($houseHistory2Array);
                array_push($houseHistory2Array, $lastData);

                $houseHistory2->update([
                    'house_history' => json_encode($houseHistory2Array),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
                // House history previous student to_date changing ends

                $updateRoomStudent = $previousRoomStudent->update([
                    'student_id' => $request->studentId,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                $insertRoomStudent = RoomStudent::insert([
                    'house_id' => $request->houseId,
                    'floor_no' => $request->floorNo,
                    'room_id' => $request->roomId,
                    'bed_no' => $request->bedNo,
                    'student_id' => $request->studentId,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            if ($updateRoomStudent || $insertRoomStudent) {
                DB::commit();
                $student = StudentProfileView::with('singleBatch', 'singleSection', 'singleUser')->where('std_id',
                        $request->studentId)->where('status',1)->first();
                return ['student' => $student];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    public function removeStudentFromBed(Request $request)
    {
        $roomStudent = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'room_id' => $request->roomId,
            'bed_no' => $request->bedNo
        ])->first();

        $isPrefect = House::where('student_id', $roomStudent->student_id)->first();
        if ($isPrefect) {
            return 2;
        }

        if ($roomStudent) {
            // House history student to_date changing starts
            $houseHistory = HouseHistory::where('student_id', $roomStudent->student_id)->first();

            $houseHistoryArray = json_decode($houseHistory->house_history, true);
            $lastData = end($houseHistoryArray);
            $lastData['toDate'] = Carbon::now();

            array_pop($houseHistoryArray);
            array_push($houseHistoryArray, $lastData);

            $houseHistory->update([
                'house_history' => json_encode($houseHistoryArray),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
            // House history student to_date changing ends

            $roomStudent->delete();
            return 1;
        } else {
            return 0;
        }
    }
    // Ajax Methods end


    public function cadetsEvaluation(Request  $request)
    {
        $pageAccessData = self::linkAccess($request);
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('house::evaluation.index', compact('academicYears', 'pageAccessData'));
    }

    public function weightageConfig()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('house::evaluation.modal.weightage-config', compact('academicYears'));
    }

    //Ajax Methods Start
    public function getSemesterFromYear(Request $request)
    {
        return AcademicsYear::findOrFail($request->academicYearId)->semesters();
    }

    public function getWeightageEventsFromType(Request $request)
    {
        $previousWeightage = WeightageConfig::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'type' => $request->type,
        ])->get();

        if ($request->type == 1) {
            $exams = ExamName::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();

            return [$previousWeightage, $exams];
        } else if ($request->type == 2) {
            $performances = CadetPerformanceCategory::where("category_type_id", 9)->orderBy('flag', 'asc')->get();

            return [$previousWeightage, $performances];
        } else if ($request->type == 3) {
            $performances = CadetPerformanceCategory::where("category_type_id", 1)->orderBy('flag', 'asc')->get();

            return [$previousWeightage, $performances];
        } else {
            return [];
        }
    }

    public function deleteWeightage(Request $request)
    {
        $weightage = WeightageConfig::findOrFail($request->weightageId);
        if ($weightage) {
            $weightage->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function getEventsFromType(Request $request)
    {
        if ($request->type == 1) {
            $exams = ExamName::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();

            return $exams;
        } else if ($request->type == 2) {
            $performances = CadetPerformanceCategory::where("category_type_id", 9)->orderBy('flag', 'asc')->get();
            return $performances;
        } else if ($request->type == 3) {
            $performances = CadetPerformanceCategory::where("category_type_id", 1)->orderBy('flag', 'asc')->get();
            return $performances;
        } else {
            return [];
        }
    }

    public function searchEvaluationTable(Request $request)
    {
        $academicYearId = $request->academicYearId;
        $semesterId = $request->semesterId;
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $type = $request->type;
        $eventId = $request->eventId;

        $exams = ExamName::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('cadet_exam_marks')
                ->whereColumn('cadet_exam_marks.exam_id', 'cadet_exam_name.id');
        })->get();
        $examWeightages = WeightageConfig::with('exam')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
            'type' => 1
        ])->get();
        $extraCurriculars = CadetPerformanceCategory::where('category_type_id', 9)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cadet_assesment')
                    ->whereColumn('cadet_assesment.cadet_performance_category_id', 'cadet_performance_category.id');
            })->get();
        $extraCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
            'type' => 2
        ])->get();
        $coCurriculars = CadetPerformanceCategory::where('category_type_id', 1)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cadet_assesment')
                    ->whereColumn('cadet_assesment.cadet_performance_category_id', 'cadet_performance_category.id');
            })->get();
        $coCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
            'type' => 3
        ])->get();

        if ($eventId) {
            if ($type == 1) {
                $examWeightages = WeightageConfig::with('exam')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'type' => 1,
                    'exam_id' => $eventId
                ])->get();
                $exams = ExamName::where('id', $eventId)->get();
            } else if ($type == 2) {
                $extraCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'type' => 2,
                    'performance_cat_id' => $eventId
                ])->get();
                $extraCurriculars = CadetPerformanceCategory::where('id', $eventId)->get();
            } else if ($type == 3) {
                $coCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'type' => 3,
                    'performance_cat_id' => $eventId
                ])->get();
                $coCurriculars = CadetPerformanceCategory::where('id', $eventId)->get();
            }
        }

        return view('house::evaluation.evaluation-table', compact('academicYearId', 'semesterId', 'houses', 'type', 'exams', 'examWeightages', 'coCurriculars', 'coCurricularWeightages', 'extraCurriculars', 'extraCurricularWeightages'))->render();
    }
    //Ajax Methods End

    public function saveWeightage(Request $request)
    {
        $eventTypeId = '';
        if ($request->type == 1) {
            $eventTypeId = 'exam_id';
        } else if ($request->type == 2 || $request->type == 3) {
            $eventTypeId = 'performance_cat_id';
        }

        DB::beginTransaction();
        try {
            foreach ($request->events as $key => $event) {
                if ($event) {
                    $previousWeightage = WeightageConfig::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'academic_year_id' => $request->academicYearId,
                        'semester_id' => $request->semesterId,
                        'type' => $request->type,
                        $eventTypeId => $event
                    ])->first();

                    if ($previousWeightage) {
                        $previousWeightage->update([
                            'mark' => $request->marks[$key],
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id(),
                        ]);
                    } else {
                        WeightageConfig::insert([
                            'academic_year_id' => $request->academicYearId,
                            'semester_id' => $request->semesterId,
                            'type' => $request->type,
                            $eventTypeId => $event,
                            'mark' => $request->marks[$key],
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Weightage Configured Successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Configuring weightage.');
            return redirect()->back();
        }
    }


    public function viewHouses()
    {
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('house::view-houses', compact('houses'));
    }

    public function searchHouse(Request $request)
    {
        $house = House::with('rooms', 'housePrefect.singleUser', 'houseMaster.singleUser')->findOrFail($request->houseId);
        $roomStudents = RoomStudent::with('student.singleUser', 'student.singleBatch', 'student.singleSection')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->get();
        $students = StudentProfileView::with('singleUser.appointUser.appoint', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'status'=>1
        ]);
        $appointUsers = HouseAppointUser::with('appoint', 'user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->get();

        return view('house::house-table', compact('house', 'roomStudents', 'students', 'appointUsers'))->render();
    }

    public function assignStudentsPage()
    {
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('house::assign-students.assign-students', compact('houses'));
    }

    public function assignStudentsSearchHouse(Request $request)
    {
        $house = House::with('rooms', 'housePrefect.singleUser', 'houseMaster.singleUser')->findOrFail($request->houseId);
        $batches = Batch::all();
        $batch = ($request->batchId) ? Batch::findOrFail($request->batchId) : null;
        $sections = ($batch) ? $batch->section() : [];
        $section = ($request->sectionId) ? Section::findOrFail($request->sectionId) : null;
        $roomStudents = RoomStudent::with('student.singleUser', 'student.singleBatch', 'student.singleSection')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->get();
        $appointUsers = HouseAppointUser::with('appoint', 'user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->get();
        $students = [];
        if ($section) {
            $assignedStudentIds = RoomStudent::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->pluck('student_id');

            $students = StudentProfileView::with('singleUser.appointUser.appoint')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->sectionId
            ])->whereNotIn('std_id', $assignedStudentIds)->get();
        }

        return view('house::assign-students.assign-students-house-table', compact('house', 'batches', 'batch', 'sections', 'section', 'roomStudents', 'students', 'appointUsers'))->render();
    }

    public function assignStudentsModal(Request $request)
    {
        $room = Room::findOrFail($request->roomId);
        $bedNo = $request->bedNo;
        $batches = Batch::all();
        $batch = null;
        $sections = [];
        $section = null;
        $students = [];
        $student = null;

        if ($request->stdId) {
            $student = StudentProfileView::where('std_id', $request->stdId)->first();
            $batch = $student->batch();
            $section = $student->section();
            $sections = $batch->section();

            $assignedStudentIds = RoomStudent::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->where('student_id', '!=', $student->std_id)->pluck('student_id');
            $students = StudentProfileView::with('singleUser')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $section->id,
                'status'=>1
            ])->whereNotIn('std_id', $assignedStudentIds)->get();
        }

        return view('house::assign-students.students-assign-fields', compact('room', 'bedNo', 'batches', 'batch', 'sections', 'section', 'students', 'student'))->render();
    }

    public function bulkAssignStudentsToBed(Request $request)
    {
        $assignedStudentIds = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('student_id');

        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'status'=>1
        ])->whereNotIn('std_id', $assignedStudentIds)->get();

        if (isset($request->studentIds)) {
            $students = $students->whereIn('std_id', $request->studentIds)->toArray();
        } else if (isset($request->sectionId)) {
            $students = $students->where('section', $request->sectionId)->toArray();
        } else {
            $students = $students->where('batch', $request->batchId)->toArray();
        }
        $revStudents = array_reverse($students);

        $allPreviousRoomStudents = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $houseHistories = HouseHistory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        DB::beginTransaction();
        try {
            foreach ($request->roomIds as $floorNo => $floorNos) {
                foreach ($floorNos as $roomId) {
                    $room = Room::findOrFail($roomId);

                    for ($bedNo = 1; $bedNo <= $room->no_of_beds; $bedNo++) {
                        $previousRoomStudent = $allPreviousRoomStudents
                            ->where('house_id', $request->houseId)
                            ->where('floor_no', $floorNo)
                            ->where('room_id', $roomId)
                            ->firstWhere('bed_no', $bedNo);

                        if (!$previousRoomStudent) {
                            $student = array_pop($revStudents);

                            if ($student) {
                                $houseHistoryData = [
                                    'fromDate' => Carbon::now(),
                                    'toDate' => null,
                                    'houseId' => $request->houseId,
                                    'floorNo' => $floorNo,
                                    'roomId' => $roomId,
                                    'bedNo' => $bedNo,
                                    'batchId' => $student['single_batch']['id'],
                                    'sectionId' => $student['single_section']['id'],
                                ];
                                $houseHistory = $houseHistories->firstWhere('student_id', $student['std_id']);

                                // House history storing starts
                                if ($houseHistory) {
                                    $houseHistoryArray = json_decode($houseHistory->house_history, true);
                                    array_push($houseHistoryArray, $houseHistoryData);

                                    $houseHistory->update([
                                        'house_history' => json_encode($houseHistoryArray),
                                        'updated_at' => Carbon::now(),
                                        'updated_by' => Auth::id(),
                                    ]);
                                } else {
                                    HouseHistory::insert([
                                        'student_id' => $student['std_id'],
                                        'house_history' => json_encode([$houseHistoryData]),
                                        'created_at' => Carbon::now(),
                                        'created_by' => Auth::id(),
                                        'campus_id' => $this->academicHelper->getCampus(),
                                        'institute_id' => $this->academicHelper->getInstitute(),
                                    ]);
                                }
                                // House history storing ends

                                RoomStudent::insert([
                                    'house_id' => $request->houseId,
                                    'floor_no' => $floorNo,
                                    'room_id' => $roomId,
                                    'bed_no' => $bedNo,
                                    'student_id' => $student['std_id'],
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'campus_id' => $this->academicHelper->getCampus(),
                                    'institute_id' => $this->academicHelper->getInstitute(),
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return "success";
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function bulkRemoveStudentsFromBed(Request $request)
    {
        $allRoomStudents = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $house = House::findOrFail($request->houseId);
        $housePrefectId = $house->student_id;
        $houseHistories = HouseHistory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        foreach ($request->roomIds as $floorNo => $floorNos) {
            foreach ($floorNos as $roomId) {
                $room = Room::findOrFail($roomId);

                for ($bedNo = 1; $bedNo <= $room->no_of_beds; $bedNo++) {
                    $roomStudent = $allRoomStudents->where('room_id', $roomId)->firstWhere('bed_no', $bedNo);

                    if ($roomStudent) {
                        if ($roomStudent->student_id != $housePrefectId) {
                            // House history student to_date changing starts
                            $houseHistory = $houseHistories->firstWhere('student_id', $roomStudent->student_id);

                            $houseHistoryArray = json_decode($houseHistory->house_history, true);
                            $lastData = end($houseHistoryArray);
                            $lastData['toDate'] = Carbon::now();

                            array_pop($houseHistoryArray);
                            array_push($houseHistoryArray, $lastData);

                            $houseHistory->update([
                                'house_history' => json_encode($houseHistoryArray),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id(),
                            ]);
                            // House history student to_date changing ends

                            $roomStudent->delete();
                        }
                    }
                }
            }
        }

        return "Success";
    }

    public function houseAppoints(Request $request, $id=null)
    {
        $pageAccessData = self::linkAccess($request);
        $houseAppoints = HouseAppoint::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $selectedAppoint = null;
        $users = [];
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $houseAppointUsers = [];
        if ($id) {
            $selectedAppoint = HouseAppoint::findOrFail($id);
            $houseAppointUsers = HouseAppointUser::with('user', 'house', 'stuProfile.roomStudent.house')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'appoint_id' => $id
            ])->get();
            $houseAppointUserIds = $houseAppointUsers->pluck('user_id');
            if ($selectedAppoint->category == 'cadet') {
                $stdIds = RoomStudent::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->pluck('student_id')->toArray();
                $users = StudentProfileView::with('singleUser', 'roomStudent.house')->where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute(),
                ])->whereIn('std_id', $stdIds)->whereNotIn('user_id', $houseAppointUserIds)->get();
            } else {
                if ($selectedAppoint->category == 'hr') {
                    $users = EmployeeInformation::with('singleUser')->where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'category' => 0
                    ])->whereNotIn('user_id', $houseAppointUserIds)->get();
                } elseif ($selectedAppoint->category == 'fm') {
                    $users = EmployeeInformation::with('singleUser')->where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'category' => 1
                    ])->whereNotIn('user_id', $houseAppointUserIds)->get();
                }
            }
        }

        return view('house::house-appoints.index', compact('pageAccessData', 'houseAppoints', 'selectedAppoint', 'users', 'houses', 'houseAppointUsers'));
    }

    public function storeHouseAppoints(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required',
        ]);

        $preSameNameAppoint = HouseAppoint::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category' => $request->category,
            'name' => $request->name,
        ])->first();

        DB::beginTransaction();
        try {
            if ($request->appointId) {
                // Update old one
                $appoint = HouseAppoint::findOrFail($request->appointId);
                $appointHistories = $appoint->appointHistories;
                $canUpdate = false;
                if (!$preSameNameAppoint) {
                    $canUpdate = true;
                } elseif ($preSameNameAppoint->id == $appoint->id) {
                    $canUpdate = true;
                }

                if ($appointHistories->count()>0) {
                    if ($appoint->category != $request->category) {
                        Session::flash('errorMessage', 'Error! User was appointed to this appoint once, can\'t change category now.');
                        return redirect()->back();
                    }
                }

                if ($canUpdate) {
                    $appoint->update([
                        'category' => $request->category,
                        'name' => $request->name,
                        'symbol' => $request->symbol,
                        'color' => $request->color,
                        'updated_by' => Auth::id()
                    ]);

                    Session::flash('message', 'Success! House appoint updated successfully.');
                } else {
                    DB::rollBack();
                    Session::flash('errorMessage', 'Error! Can\'t update House appoint with same name.');
                }
            } else {
                // Create new one
                if (!$preSameNameAppoint) {
                    HouseAppoint::create([
                        'category' => $request->category,
                        'name' => $request->name,
                        'symbol' => $request->symbol,
                        'color' => $request->color,
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);

                    Session::flash('message', 'Success! House appoint created successfully.');
                } else {
                    DB::rollBack();
                    Session::flash('errorMessage', 'Error! Can\'t make new House appoint with same name.');
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('errorMessage', $th);
        }

        return redirect()->back();
    }

    public function editHouseAppoint($id)
    {
        $appoint = HouseAppoint::findOrFail($id);
        return view('house::house-appoints.edit', compact('appoint'));
    }

    public function deleteHouseAppoint($id)
    {
        $appoint = HouseAppoint::findOrFail($id);
        $appointHistories = $appoint->appointHistories;
        if ($appointHistories->count()>0) {
            Session::flash('errorMessage', 'Error! User was appointed to this appoint once, can\'t delete now.');
            return redirect()->back();
        }
        $appoint->delete();
        Session::flash('message', 'Success! House appoint deleted successfully.');
        return redirect('/house/house-appoints');
    }

    public function assignUserToAppoint(Request $request)
    {
        $request->validate([
            'userIds' => 'required'
        ]);
        $appoint = HouseAppoint::findOrFail($request->appointId);
        if ($appoint->category != 'cadet') {
            $request->validate([
                'houseId' => 'required'
            ]);
        }
        $authId = Auth::id();

        DB::beginTransaction();
        try {
            foreach ($request->userIds as $userId) {
                HouseAppointUser::create([
                    'appoint_id' => $appoint->id,
                    'user_id' => $userId,
                    'appoint_category' => $appoint->category,
                    'house_id' => $request->houseId,
                    'created_by' => $authId,
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                HouseAppointHistory::create([
                    'appoint_id' => $appoint->id,
                    'user_id' => $userId,
                    'appoint_category' => $appoint->category,
                    'house_id' => $request->houseId,
                    'action' => 'assign',
                    'created_by' => $authId,
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            DB::commit();
            Session::flash('message', 'Success! Users assigned to appoint successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('errorMessage', $th);
        }

        return redirect()->back();
    }

    public function removeUserFromAppoint($id)
    {
        $houseAppointUser = HouseAppointUser::findOrFail($id);
        $authId = Auth::id();

        DB::beginTransaction();
        try {
            HouseAppointHistory::create([
                'appoint_id' => $houseAppointUser->appoint_id,
                'user_id' => $houseAppointUser->user_id,
                'appoint_category' => $houseAppointUser->appoint_category,
                'house_id' => $houseAppointUser->house_id,
                'action' => 'remove',
                'created_by' => $authId,
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);
            $houseAppointUser->delete();

            DB::commit();
            Session::flash('message', 'Success! Users removed from appoint successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('errorMessage', $th);
        }

        return redirect()->back();
    }
}
