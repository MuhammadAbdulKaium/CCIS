<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Payroll\Entities\BankBranchDetails;
use Modules\Payroll\Entities\BankDetails;

class BankDetailsController extends Controller
{
    private $academicHelper;
    private $department;

    public function __construct(AcademicHelper $academicHelper, EmployeeDepartment $department)
    {
        $this->academicHelper = $academicHelper;
        $this->department = $department;
    }
    public function searchBankBranch($id)
    {
       return $bankBranch=BankBranchDetails::where('bank_id',$id)->get();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $bankNames = BankDetails::where(['campus_id'=>$this->academicHelper->getCampus(),'institute_id'=>$this->academicHelper->getInstitute()])->get();
        $branchName = BankBranchDetails::with('bankName')->get();
        return view('payroll::pages.bank.index',compact('bankNames','branchName'));
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('payroll::pages.bank.create');
    }
    public function createBranch()
    {
        $bankNames = BankDetails::where(['campus_id'=>$this->academicHelper->getCampus(),'institute_id'=>$this->academicHelper->getInstitute()])->get();
        return view('payroll::pages.bankBranch.create',compact('bankNames'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $bank = new BankDetails();
        $bank->bank_name=$request->bank_name;
        $bank->institute_id = $this->academicHelper->getInstitute();
        $bank->campus_id = $this->academicHelper->getCampus();
        $bank->created_by=Auth::user()->id;
        $storeBank=$bank->save();
        if($storeBank)
        {
            Session::flash('success', 'Bank created');
            return redirect()->back();
        }
        else{
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }

    }
    public function storeBranch(Request $request)
    {
        $branch = new BankBranchDetails();
        $branch->branch_name=$request->branch_name;
        $branch->bank_id = $request->bank_id;
        $branch->branch_location = $request->branch_location;
        $branch->branch_phone = $request->branch_phone;
        $branch->created_by=Auth::user()->id;
        $storeBranch=$branch->save();
        if($storeBranch)
        {
            Session::flash('success', 'Branch created');
            return redirect()->back();
        }
        else{
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $bank=BankDetails::where('id',$id)->first();
        return view('payroll::pages.bank.edit',compact('bank'));
    }
    public function editBranch($id)
    {
        $bankNames = BankDetails::where(['campus_id'=>$this->academicHelper->getCampus(),'institute_id'=>$this->academicHelper->getInstitute()])->get();
        $branch=BankBranchDetails::where('id',$id)->first();
        return view('payroll::pages.bankBranch.edit',compact('branch','bankNames'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $bank=BankDetails::where('id',$request->id)->first();
        $updateBank=$bank->update(['bank_name'=>$request->bank_name]);
        if($updateBank)
        {
            Session::flash('success', 'Bank Updated');
            return redirect()->back();
        }
        else{
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }
    }
    public function updateBranch(Request $request)
    {
        $branch=BankBranchDetails::where('id',$request->id)->first();
        $updateBank=$branch->update(['branch_name'=>$request->branch_name,'bank_id'=>$request->bank_id,
            'branch_location'=>$request->branch_location,'branch_phone'=>$request->branch_phone]);
        if($updateBank)
        {
            Session::flash('success', 'Branch Updated');
            return redirect()->back();
        }
        else{
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }
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
