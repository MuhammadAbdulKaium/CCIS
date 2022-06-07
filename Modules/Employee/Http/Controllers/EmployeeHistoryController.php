<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\HouseAppointHistory;
use Svg\Tag\Rect;

class EmployeeHistoryController extends Controller
{
    use UserAccessHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index()
    {
        return view('employee::index');
    }

    public function create()
    {
        return view('employee::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('employee::show');
    }

    public function edit($id)
    {
        return view('employee::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    public function statusAssignHistory($id,Request  $request){
        $employeeInfo=EmployeeInformation::with('employeeStatus')->where('id',$id)->first();
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);

        return view('employee::pages.profile.status-assign', compact('employeeInfo','pageAccessData'))
            ->with('tab', 'status-assign')->with('page','history');

    }

    public function houseAppointHistory($id, Request $request)
    {
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);
        // find employee information
        $employeeInfo = EmployeeInformation::findOrFail($id);

        $houseAppointHistories = HouseAppointHistory::with('appoint', 'house', 'institute')->where([
            // 'campus_id' => $this->academicHelper->getCampus(),
            // 'institute_id' => $this->academicHelper->getInstitute(),
            'user_id' => $employeeInfo->user()->id
        ])->latest()->get();
        // return view
        return view('employee::pages.profile.house-appoint-history', compact('employeeInfo','pageAccessData','houseAppointHistories'))->with('page', 'history')->with('tab','house_appoint_history');
    }
}
