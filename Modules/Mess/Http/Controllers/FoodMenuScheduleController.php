<?php

namespace Modules\Mess\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\Batch;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Mess\Entities\FoodMenu;
use Modules\Mess\Entities\FoodMenuCategory;
use Modules\Mess\Entities\FoodMenuSchedule;
use Modules\Student\Entities\StudentProfileView;
use App;
use Modules\Inventory\Entities\StockUOM;
use Modules\Mess\Entities\FoodMenuItem;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;

class   FoodMenuScheduleController extends Controller
{
    private $academicHelper;
    use App\Helpers\UserAccessHelper;


    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }




    public function index(Request  $request)
    {
        $pageAccessData = self::linkAccess($request);
        $foodMenuCategories = FoodMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $batches = Batch::all();

        $departments = EmployeeDepartment::all();


        return view('mess::food-menu-schedule.index', compact('pageAccessData', 'foodMenuCategories', 'batches', 'departments'));
    }



    public function create()
    {
        return view('mess::create');
    }



    public function store(Request $request)
    {
        //
    }



    public function show($id)
    {
        return view('mess::show');
    }



    public function edit($id)
    {
        return view('mess::edit');
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }

    public function printSchedule(Request $request)
    {
        $campus= Campus::where('institute_id',$this->academicHelper->getInstitute())->first();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $begin = new DateTime($request->fromDate);

        $end = new DateTime($request->toDate);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $previousSchedules = FoodMenuSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->whereBetween('date', [$begin, $end])->get();

        $previousFoodSchedules = FoodMenuSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->whereBetween('date', [$begin, $end])->select('menu_id as id')->groupBy('menu_id')->get();
        $fmsId = [];
        foreach ($previousFoodSchedules as $id) {
            array_push($fmsId, $id->id);
        }

        $singleFoodMenus = FoodMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $fmsId)->get();
        $foodMenus = FoodMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $foodMenuItems = FoodMenuItem::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $uoms = StockUOM::all();
        $pdf = App::make('dompdf.wrapper');
        // return view('mess::food-menu-schedule.schedule-table-pdf', compact('campus','institute','singleFoodMenus', 'uoms', 'foodMenuItems', 'period', 'previousSchedules', 'foodMenus'));
        $pdf->loadView('mess::food-menu-schedule.schedule-table-pdf', compact('campus','institute','singleFoodMenus', 'uoms', 'foodMenuItems', 'period', 'previousSchedules', 'foodMenus'))->setPaper('a2', 'portrait');
        return $pdf->stream();
    }


    // Ajax Methods
    public function foodMenuScheduleTable(Request $request)
    {
        $begin = new DateTime($request->fromDate);
        $end = new DateTime($request->toDate);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $previousSchedules = FoodMenuSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $foodMenus = FoodMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $foodMenuItems = FoodMenuItem::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $uoms = StockUOM::all();
        return view('mess::food-menu-schedule.schedule-table', compact('uoms', 'foodMenuItems', 'period', 'previousSchedules', 'foodMenus'))->render();
    }

    public function getMenuFromCategory(Request $request)
    {
        if ($request->menuCategoryId) {
            return FoodMenuCategory::findOrFail($request->menuCategoryId)->menus;
        } else {
            return [];
        }
    }

    public function getFormDesignationFromClassDepartment(Request $request)
    {
        if ($request->type == 1) {
            $batch = Batch::findOrFail($request->batchDepartmentId);
            $noOfStudents = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'batch' => $batch->id
            ])->count();

            return ['formDesignations' => $batch->section(), 'strength' => $noOfStudents];
        } elseif ($request->type == 2) {
            $department = EmployeeDepartment::findOrFail($request->batchDepartmentId);
            $noOfEmployees = EmployeeInformation::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'department' => $department->id
            ])->count();

            return ['formDesignations' => $department->designations(), 'strength' => $noOfEmployees];
        } else {
            return ['formDesignations' => [], 'strength' => 0];
        }
    }

    public function getStrengthFormDesignation(Request $request)
    {
        if ($request->type == 1) {
            return StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->formDesignationId
            ])->count();
        } elseif ($request->type == 2) {
            return EmployeeInformation::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'designation' => $request->formDesignationId
            ])->count();
        } else {
            return 0;
        }
    }

    public function saveFoodMenuSchedule(Request $request)
    {
        $time = Carbon::createFromFormat('H:i', $request->time)->toTimeString();

        DB::beginTransaction();
        try {
            foreach ($request->dates as $date) {
                $formattedDate = Carbon::createFromFormat('d/m/Y', $date)->toDateString();

                foreach ($request->slots as $slot) {
                    $previousSchedule = FoodMenuSchedule::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'date' => $formattedDate,
                        'slot' => $slot
                    ])->first();

                    if ($previousSchedule) {
                        $previousSchedule->update([
                            'time' => $time,
                            'menu_category_id' => $request->menuCategory,
                            'menu_id' => $request->menu,
                            'persons' => $request->persons,
                            'person_details' => $request->personDetails,
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ]);
                    } else {
                        FoodMenuSchedule::insert([
                            'date' => $formattedDate,
                            'slot' => $slot,
                            'time' => $time,
                            'menu_category_id' => $request->menuCategory,
                            'menu_id' => $request->menu,
                            'persons' => $request->persons,
                            'person_details' => $request->personDetails,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }
}
