<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeePromotion;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use PHPUnit\Exception;

class EmployeePromotionController extends Controller
{ use UserAccessHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {

        $this->academicHelper = $academicHelper;
    }
    public function index($id,Request $request)
    {

        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);
        // find employee information
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $allCampus=Campus::all()->keyBy('id');
        $allDept=EmployeeDepartment::all()->keyBy('id');
      $allDesignation=EmployeeDesignation::all()->keyBy('id');
        $allInstitute=Institute::all()->keyBy('id');
        $promotions=EmployeePromotion::where('employee_id',$employeeInfo->id)->get()->sortByDesc('created_at');
        $newPromotion=$promotions->where('status','pending');

         // return sizeof($newPromotion);
       // return $promotions;

        return view('employee::pages.profile.promotion.index', compact('allDept','allDesignation','allCampus','allInstitute','promotions','employeeInfo','pageAccessData','newPromotion')
        )->with('page',
            'promotion');

    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function promote(Request  $request,$id)
    {
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);

        $departments=EmployeeDepartment::all();
        $designations=EmployeeDesignation::all();
        $lastPromotion=EmployeePromotion::where('employee_id',$id)->where('status','approved')->get()->sortByDesc('promotion_date');

        $employee=EmployeeInformation::findOrfail($id);
        return view('employee::pages.profile.promotion.promote',compact(
            'designations','pageAccessData','departments','employee','lastPromotion'
        ));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
     
        if(!$request->input('last_promotion_date')){
           $lastJoin=Carbon::parse($request->input('system_last_promotion'))->toDateString();
        }else{
            $lastJoin=Carbon::parse($request->input('last_promotion_date'))->toDateString();
        }

        $institute=$this->academicHelper->getInstitute();
        $campus=$this->academicHelper->getCampus();
        $employee=EmployeeInformation::where([
            'id'=>$request->id
        ])->first();
        if(!$employee) return  redirect()->back()->with('error',"Invalid Form Submission");
        $lastPromotion=EmployeePromotion::where('employee_id',$request->id)->where('status','approved')->get()->sortByDesc('promotion_date');

        DB::beginTransaction();
        try {
            $promotion=new  EmployeePromotion();
            $promotion->employee_id=$request->input('id');
            $promotion->previous_department=$employee->department;
            $promotion->previous_designation=$employee->designation;
            $promotion->previous_category=$employee->category;

            $promotion->department=$request->department;
            $promotion->designation=$request->input('designation');
            $promotion->category=$request->input('category');
            $promotion->status="pending";
            $promotion->campus=$campus;
            $promotion->institute=$institute;
            $promotion->promotion_board=$request->promotion_board;

            $promotion->prev_campus=$campus;
            $promotion->prev_institute=$institute;
            $promotion->created_by=Auth::id();
            $promotion->last_promotion_date=$lastJoin;

            $promotion->save();
            DB::commit();

            return  redirect()->back()->with('success',"Successfully Created");
        }catch (\Exception $e){
            DB::rollBack();
            return  redirect()->back()->with('error',"Something Went Wrong ");
        }




    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public  function  storePromotion(Request $request,$id){
       // return $request;
        if(!$request->input('last_promotion_date')){
            $lastJoin=Carbon::parse($request->input('system_last_promotion'))->toDateString();
        }else{
            $lastJoin=Carbon::parse($request->input('last_promotion_date'))->toDateString();
        }

        $institute=$this->academicHelper->getInstitute();
        $campus=$this->academicHelper->getCampus();
        $employee=EmployeeInformation::where([
            'id'=>$request->id
        ])->first();

        if(!$employee)  redirect()->back()->with('error',"Invalid Form Submission");
        $promotion=EmployeePromotion::findOrfail($id);
        $promotion->department=$request->department;
        $promotion->designation=$request->input('designation');
        $promotion->category=$request->input('category');
        $promotion->promotion_board=$request->promotion_board;
        $promotion->last_promotion_date=$lastJoin;
        $promotion->reasoning=$request->reasoning;
        $promotion->board_remarks=$request->board_remarks;
        //return $promotion;
       if($request->status==="pending"){
           $promotion->save();
           return  redirect()->back()->with('success',"Successfully Updated");
       }else{

           if($promotion->status!=="pending")
               return  redirect()->back()->with("Something went wrong ");
           else{
               DB::beginTransaction();

              try {
                   $changedStatus=$request->status;
                   if($changedStatus=="approved"){
                       $promotion->approved_by=Auth::id();
                       $promotion->promotion_date=Carbon::today();
                       $promotion->status="approved";
                       $promotion->save();
                       //return $promotion;
                       $employee=EmployeeInformation::findOrfail($promotion->employee_id);
                       $employee->department=$promotion->department;
                       $employee->designation=$promotion->designation;
                       $employee->category=$promotion->category;
                       $employee->save();
                       DB::commit();
                       return redirect()->back()->with("success","Successfully Promoted employee");
                   }else if($changedStatus==="rejected"){
                       $promotion->approved_by=Auth::id();
                       $promotion->promotion_date=Carbon::today();
                       $promotion->status="rejected";
                       $promotion->save();
                       DB::commit();
                       return redirect()->back()->with("success","Successfully Rejected the  employee promotion");
                   }
               }catch (\Exception $e){
                   DB::rollBack();
                   return redirect()->back()->with("error","something went wrong");

               }


           }

       }
    }
    public function editPromotion(Request  $request,$id)
    {
        $pageAccessData = self::linkAccess($request,['manualRoute'=>"employee/manage"]);

        $promotionInfo=EmployeePromotion::findOrfail($id);

        $employee=EmployeeInformation::findOrfail($promotionInfo->employee_id);
        $lastPromotion=EmployeePromotion::where('employee_id',$employee->id)->where('status','approved')->get()
            ->sortByDesc('promotion_date');

        $departments=EmployeeDepartment::all();
        $designations=EmployeeDesignation::all();
        return view('employee::pages.profile.promotion.edit-promotion',compact(
            'designations','pageAccessData','departments','employee','promotionInfo','lastPromotion'
        ));
       // return $id;
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
