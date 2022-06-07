<?php

namespace Modules\Student\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\Batch;
use Modules\House\Entities\House;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentProfileView;
use App;
use Illuminate\Support\Facades\Auth;

class CadetSummaryReportsController extends Controller
{
    private $academicHelper;
    private $academicsLevel;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper, AcademicsLevel $academicsLevel)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;
    }
    
    public function index()
    {
        $allBatch=Batch::all();
        $existInstitute=false;
        $institute=$this->academicHelper->getInstitute();
        if($institute){
            $allInstitute=[];
            $students=StudentProfileView::where('institute',$institute)->get();
            $existInstitute=true;
            $institute=Institute::findOrfail($institute);
            array_push($allInstitute,$institute);

        }else{
            $students=StudentProfileView::all();
            $allInstitute=Institute::all();
        }

        return view('student::reports.summary-reports',compact('existInstitute','students','allInstitute','allBatch'));
    }
    
    public function searchStudentReport(Request $request){
        $student = StudentProfileView::with('singleBatch', 'singleUser', 'singleStudent', 'singleEnroll')->where('std_id',$request->student_id)->first();
        $familyMembers = $student->singleStudent->myGuardians();
        $father = null;
        $mother = null;
        $brothers = [];
        $sisters = [];
        $totalIncome = 0;

        foreach ($familyMembers as $familyMember) {
            $person = $familyMember->guardian();
            switch($person->type) {
                case '0': 
                    $mother =  $person;
                    $totalIncome += $person->income;
                    break;
                case '1': 
                    $father =  $person;
                    $totalIncome += $person->income;
                    break;
                case '2': 
                    array_push($sisters, $person);
                    break;
                case '3': 
                    array_push($brothers, $person); 
                    break;
            }
        }

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');

        if($request->type=="print")
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('student::reports.summary-report-pdf',compact('student', 'houses', 'father', 'mother', 'brothers', 'sisters', 'totalIncome', 'user'))->setPaper('a4', 'potrait');
            return $pdf->stream('cadet-summary-report.pdf');
        }else{
            $view= view('student::reports.summary-report-table',compact('student', 'houses', 'father', 'mother', 'brothers', 'sisters', 'totalIncome'))->render();
            return ['status'=>'success','data'=>$view];
        }
    }

    public function searchStudents(Request  $request){
        $institute=$this->academicHelper->getInstitute();
        if(!$institute){
            $institute=$request->id;
        }

       $students= StudentProfileView::when($institute,function ($query,$institute){
           $query->where('institute',$institute);})
           ->when($batch=$request->batch,function ($query,$batch){
            $query->where('batch',$batch);
            })
           ->when($section=$request->section,function ($query,$section){
               $query->where('section',$section);
           })
           ->get();

    return $students;

    }
    public function create()
    {

        return view('student::create');
    }
    
    public function store(Request $request)
    {
        //
    }
    
    public function show($id)
    {
        return view('student::show');
    }
    
    public function edit($id)
    {
        return view('student::edit');
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }
}
