<?php

namespace Modules\Mess\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\House;
use Modules\Mess\Entities\MessTable;
use Modules\Mess\Entities\MessTableHistory;
use Modules\Mess\Entities\MessTableSeat;
use Modules\Mess\Http\Requests\MessTableRequest;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use App;
use PDF;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;

class MessTableController extends Controller
{
    use UserAccessHelper;
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);

        $houseIds = MessTable::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->pluck('house_id');
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $houseIds)->get();
        $messTables = MessTable::with('employee')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $messTableSeats = MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $personSeatNo = null;
        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute()
        ])->get();

        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        //$pageAccessData = self::linkAccess($request,['manualRoute'=>"mess/table"]);

        $messTableView = view('mess::mess-table.mess-tables', compact('messTables', 'pageAccessData', 'houses', 'messTableSeats', 'personSeatNo', 'students', 'employees'))->render();
        return view('mess::mess-table.index', compact('pageAccessData', 'messTableView', 'messTableSeats'));
    }

      // print Table
      public function print(Request $request){
       $pageAccessData = self::linkAccess($request);
        $campus= Campus::where('institute_id',$this->academicHelper->getInstitute())->first();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $houseIds = MessTable::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->pluck('house_id');
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $houseIds)->get();
        $messTables = MessTable::with('employee')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $messTableSeats = MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $personSeatNo = null;
        $students = StudentProfileView::with('singleUser')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute()
        ])->get();

        $employees = EmployeeInformation::with('singleUser')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        // return view('mess::mess-table.print-mess-table', compact('pageAccessData','campus','institute','houses','messTables', 'messTableSeats', 'personSeatNo', 'students', 'employees'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('mess::mess-table.print-mess-table',compact('pageAccessData','campus','institute','houses','messTables', 'messTableSeats', 'personSeatNo', 'students', 'employees'))->setPaper('a1', 'portrait');
        return $pdf->stream();
    }

    public function create()
    {
        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('mess::mess-table.modal.create-table', compact('employees', 'houses'));
    }


    public function store(MessTableRequest $request)
    {
        if ($request->totalSeats % 2 != 0 || $request->totalHighSeats % 2 != 0) {
            Session::flash('errorMessage', 'Seat numbers should must be even.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertTable = MessTable::insert([
                'house_id' => $request->houseId,
                'table_name' => $request->tableName,
                'table_position' => $request->position,
                'total_seats' => $request->totalSeats,
                'total_high_seats' => $request->totalHighSeats,
                'employee_id' => $request->employeeId,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($insertTable) {
                DB::commit();
                Session::flash('message', 'New mess table created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating mess table.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating mess table.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('mess::show');
    }


    public function edit($id)
    {
        $messTable = MessTable::findOrFail($id);
        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('mess::mess-table.modal.edit-table', compact('messTable', 'employees', 'houses'));
    }


    public function update(MessTableRequest $request, $id)
    {
        if ($request->totalSeats % 2 != 0 || $request->totalHighSeats % 2 != 0) {
            Session::flash('errorMessage', 'Seat numbers should must be even.');
            return redirect()->back();
        }

        $assignedSeat = MessTableSeat::where([
            'mess_table_id' => $id,
            ['seat_no', '>', $request->totalSeats]
        ])->first();

        if ($assignedSeat) {
            Session::flash('errorMessage', 'Can not remove seats, persons are assigned on those seats.');
            return redirect()->back();
        }

        $messTable = MessTable::findOrFail($id);

        DB::beginTransaction();
        try {
            $updateTable = $messTable->update([
                'house_id' => $request->houseId,
                'table_name' => $request->tableName,
                'table_position' => $request->position,
                'total_seats' => $request->totalSeats,
                'total_high_seats' => $request->totalHighSeats,
                'employee_id' => $request->employeeId,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateTable) {
                DB::commit();
                Session::flash('message', 'Mess table updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating mess table.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating mess table.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $messTable = MessTable::findOrFail($id);
        $messTableSeat = MessTableSeat::where('mess_table_id', $id)->first();

        if ($messTableSeat) {
            Session::flash('errorMessage', 'Person assigned on this table can not delete.');
            return redirect()->back();
        } else {
            $messTable->delete();
            Session::flash('message', 'Mess table deleted successfully.');
            return redirect()->back();
        }
    }

    public function history($id)
    {
        $messTable = MessTable::findOrFail($id);
        $messTableHistories = MessTableHistory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'mess_table_id' => $id
        ])->orderByDesc('id')->get();

        return view('mess::mess-table.modal.history', compact('messTable', 'messTableHistories'));
    }


    // Ajax Methods
    public function getPersonsFromPersonType(Request $request)
    {
        $previousCadetIds = MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'person_type' => 1
        ])->pluck('person_id');
        $previousEmployeeIds = MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'person_type' => 2
        ])->pluck('person_id');

        if ($request->type == 1) {
            return StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'status'=>1

            ])->whereNotIn('std_id', $previousCadetIds)->get();
        } elseif ($request->type == 2) {
            return EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereNotIn('id', $previousEmployeeIds)->get();
        } else {
            return [];
        }
    }

    public function getAllPersonsFromPersonType(Request $request)
    {
        if ($request->type == 1) {
            return StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();
        } elseif ($request->type == 2) {
            return EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->get();
        } else {
            return [];
        }
    }

    public function assignPersonToSeat(Request $request)
    {
        $previousSeat = MessTableSeat::where([
            'mess_table_id' => $request->tableId,
            'seat_no' => $request->seatNo
        ])->first();

        DB::beginTransaction();
        try {
            if ($previousSeat) {
                //For History Table Start
                if (!($previousSeat->person_type == $request->personType && $previousSeat->person_id == $request->personId)) {
                    MessTableHistory::insert([
                        'mess_table_id' => $previousSeat->mess_table_id,
                        'seat_no' => $previousSeat->seat_no,
                        'person_type' => $previousSeat->person_type,
                        'person_id' => $previousSeat->person_id,
                        'activity' => 2,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                    MessTableHistory::insert([
                        'mess_table_id' => $request->tableId,
                        'seat_no' => $request->seatNo,
                        'person_type' => $request->personType,
                        'person_id' => $request->personId,
                        'activity' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
                //For History Table End

                $previousSeat->update([
                    'person_type' => $request->personType,
                    'person_id' => $request->personId,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                MessTableSeat::insert([
                    'mess_table_id' => $request->tableId,
                    'seat_no' => $request->seatNo,
                    'person_type' => $request->personType,
                    'person_id' => $request->personId,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                //For History Table Start
                MessTableHistory::insert([
                    'mess_table_id' => $request->tableId,
                    'seat_no' => $request->seatNo,
                    'person_type' => $request->personType,
                    'person_id' => $request->personId,
                    'activity' => 1,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
                //For History Table End
            }

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }

    public function getMessTableView(Request $request)
    {
        $houseIds = MessTable::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->pluck('house_id');
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $houseIds)->get();
        $messTables = MessTable::with('employee')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $messTableSeats = MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $personSeatNo = null;
        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute()
        ])->get();

        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $pageAccessData = self::linkAccess($request, ['manualRoute' => "mess/table"]);
        return view('mess::mess-table.mess-tables', compact('pageAccessData', 'houses', 'messTables', 'messTableSeats', 'personSeatNo', 'students', 'employees'))->render();
    }

    public function getPersonDetails(Request $request)
    {
        if ($request->type == 1) {
            return StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where('std_id', $request->personId)->first();
        } elseif ($request->type == 2) {
            return EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->findOrFail($request->personId);
        } else {
            return null;
        }
    }

    public function getMessTableSeats()
    {
        return MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
    }

    public function removePersonFromSeat(Request $request)
    {
        $messTableSeat = MessTableSeat::where([
            'mess_table_id' => $request->tableId,
            'seat_no' => $request->seatNo
        ])->first();

        DB::beginTransaction();
        try {
            // For History Table
            MessTableHistory::insert([
                'mess_table_id' => $messTableSeat->mess_table_id,
                'seat_no' => $messTableSeat->seat_no,
                'person_type' => $messTableSeat->person_type,
                'person_id' => $messTableSeat->person_id,
                'activity' => 2,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            $messTableSeat->delete();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }

    public function searchTableByPerson(Request $request)
    {
        $pageAccessData = self::linkAccess($request);

        $houseIds = MessTable::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->pluck('house_id');
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $houseIds)->get();

        $messTableSeat = MessTableSeat::where([
            'person_type' => $request->type,
            'person_id' => $request->personId
        ])->first();

        if ($messTableSeat) {
            $messTables = MessTable::with('employee')->where('id', $messTableSeat->mess_table_id)->get();
            $personSeatNo = $messTableSeat->seat_no;
        } else {
            $messTables = [];
            $personSeatNo = null;
        }

        $messTableSeats = MessTableSeat::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute()
        ])->get();

        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();


        return view('mess::mess-table.mess-tables', compact('pageAccessData', 'houses', 'messTables', 'messTableSeats', 'personSeatNo', 'students', 'employees'))->render();
    }
}
