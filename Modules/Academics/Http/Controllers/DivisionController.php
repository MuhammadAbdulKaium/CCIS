<?php

namespace Modules\Academics\Http\Controllers;

use App\Helpers\UserAccessHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Entities\Division;
use Modules\Academics\Entities\AcademicsYear;
use App\Http\Controllers\Helpers\AcademicHelper;

class DivisionController extends Controller
{

    private $division;
    private $academicsYear;
    private $academicHelper;
    use UserAccessHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper, AcademicsYear $academicsYear, Division $division)
    {
        $this->division = $division;
        $this->academicsYear  = $academicsYear;
        $this->academicHelper  = $academicHelper;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        // find campus (institute) all division list
        $divisionList = $this->division->get();
        // return view with variable
        return view('academics::division.index', compact('divisionList', 'pageAccessData'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // division profile
        $divisionProfile = null;
        // return view with variable
        return view('academics::division.division', compact('divisionProfile'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response|mixed
     */
    public function store(Request $request)
    {
        // store requested profile name
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:100|unique:academics_division',
            'short_name' => 'required|max:100',
        ]);

        if ($validator->passes()) {
            // receive request details
            $name = $request->input('name');
            $shortName = $request->input('short_name');
            $divisionId = $request->input('division_id');
            // $academicYearId = $this->academicHelper->getAcademicYear();

            // now checking the division id
            if ($divisionId > 0) {
                $divisionProfile = $this->division->find($divisionId);
            } else {
                $divisionProfile = new $this->division();
            }
            // input profile details
            $divisionProfile->name = $name;
            $divisionProfile->short_name = $shortName;
            // $divisionProfile->academic_year = $academicYearId;
            if ($divisionId == 0) $divisionProfile->status = 1;
            // save and checking
            if ($divisionProfile->save()) {
                // session data
                Session::flash('success', 'Division Submitted');
                // return
                return redirect()->back();
            } else {
                // session data
                Session::flash('warning', 'Unable to Submit Division');
                // return
                return redirect()->back();
            }
        } else {
            // session data
            Session::flash('warning', 'Invalid information');
            // return
            return redirect()->back()->withErrors($validator);
        }
    }

    public function update(Request $request)
    {
        // store requested profile name
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:100|unique:academics_division',
            'short_name' => 'required|max:100',
        ]);

        if ($validator->passes()) {
            // receive request details
            $name = $request->input('name');
            $shortName = $request->input('short_name');
            $divisionId = $request->input('division_id');
            // $academicYearId = $this->academicHelper->getAcademicYear();

            // now checking the division id
            if ($divisionId > 0) {
                $divisionProfile = $this->division->find($divisionId);
            } else {
                $divisionProfile = new $this->division();
            }
            // input profile details
            $divisionProfile->name = $name;
            $divisionProfile->short_name = $shortName;
            // $divisionProfile->academic_year = $academicYearId;
            if ($divisionId == 0) $divisionProfile->status = 1;
            // save and checking
            if ($divisionProfile->save()) {
                // session data
                Session::flash('success', 'Division Submitted');
                // return
                return redirect()->back();
            } else {
                // session data
                Session::flash('warning', 'Unable to Submit Division');
                // return
                return redirect()->back();
            }
        } else {
            // session data
            Session::flash('warning', 'Invalid information');
            // return
            return redirect()->back()->withErrors($validator);
        }
    }
    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($divisionId)
    {
        // division profile
        $divisionProfile = $this->division->find($divisionId);
        // return view with variable
        return view('academics::division.update-division', compact('divisionProfile'));
    }


    /**
     * change status of the specified resource
     * @return Response|mixed
     */
    public function status($divisionId)
    {
        // division profile
        $divisionProfile = $this->division->find($divisionId);
        // checking
        if ($divisionProfile) {
            // status
            $divisionProfile->status = ($divisionProfile->satus == 1 ? 0 : 1);
            // save and checking
            if ($divisionProfile->save()) {
                // session data
                Session::flash('success', 'Division Status Changed');
                // return
                return redirect()->back();
            } else {
                // session data
                Session::flash('warning', 'Unable to Change Division Status');
                // return
                return redirect()->back();
            }
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response|mixed
     */
    public function destroy($divisionId)
    {
        // division profile
        $divisionProfile = $this->division->find($divisionId);
        // checking
        if ($divisionProfile) {
            // delete and checking
            if ($divisionProfile->delete()) {
                // session data
                Session::flash('success', 'Division Deleted');
                // return
                return redirect()->back();
            } else {
                // session data
                Session::flash('warning', 'Unable to Delete Division');
                // return
                return redirect()->back();
            }
        } else {
            abort(404);
        }
    }
}
