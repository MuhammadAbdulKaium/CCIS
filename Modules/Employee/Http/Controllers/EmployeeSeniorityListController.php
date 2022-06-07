<?php

namespace Modules\Employee\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Setting\Entities\Institute;

use MPDFGEN;
use View;
use Mpdf;


class EmployeeSeniorityListController extends Controller
{
    use UserAccessHelper;

    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index()
    {
        $authUser = Auth::user();
        $authRole = $authUser->role();
        $institutes = Institute::where([
            'id' => $this->academicHelper->getInstitute()
        ])->get();
        if (!$this->academicHelper->getInstitute() && $authRole->name == 'super-admin') {
            $institutes = Institute::all();
        }
        $departments = EmployeeDepartment::all();
        $designations = EmployeeDesignation::all();

        return view('employee::reports.seniority-list.seniority-list', compact('institutes', 'departments', 'designations'));
    }

    public function create()
    {
        return view('employee::create');
    }

    public function store(Request $request)
    {
        $hide_blank= $request->hide_blank;

        $designations = ($request->input('designation_id')) ? $request->input('designation_id') :
            EmployeeDesignation::all()->pluck('id');
        $departments = ($request->input('department_id')) ? $request->input('department_id') :
            EmployeeDepartment::all()->pluck('id');
        $isDepartmemtsNull = $request->input('department_id');
        $institutes=[];
        if($this->academicHelper->getInstitute()==null){
            $institutes = ($request->input('institute_id')) ? $request->input('institute_id') :
                Institute::all()->pluck('id');
        }else{
            $institute=$this->academicHelper->getInstitute();
            $institutes[0]=$institute;
        }

        $designationWiseEmployee = [];
        foreach ($designations as $designation) {
            $employees = EmployeeInformation::
            with('qualifications', 'singleUser','promotions', 'disciplines', 'trainings', 'transfers')->whereIn('institute_id',
                $institutes)
                ->when($isDepartmemtsNull, function ($query, $departments) {
                    $query->whereIn('department', $departments);

                })->where('designation', $designation)
                ->get()->sortBy('doj')->sortBy('central_position_serial')->keyBy('id');
            $designationWiseEmployee[$designation] = $employees;


        }
        // return  $designationWiseEmployee;

        $selectForm = $request->fields;
        $reportName = $request->input('report_name');
        if (!$selectForm) {
            $selectForm = [];
            $all_form = true;
        } else {
            $all_form = false;
        }

        $allDesignation = EmployeeDesignation::all()->keyBy('id');
        $institute=Institute::find($this->academicHelper->getInstitute());

        if ($request->type == "print") {

            //return $request->all();
            $pageSize=$request->pdf_page_size;
            if($pageSize != 'A1-L' && $pageSize != 'A2-L' && $pageSize != 'A3-L' && $pageSize != 'a4-L') {
                $pageSize = 'A3-L';
            }


            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);

            $user = Auth::user();
            /* $pdf->loadView('employee::reports.seniority-list.seniority-list-dom-pdf', compact('institute','selectForm',
                 'designationWiseEmployee', 'user','reportName', 'allDesignation', 'all_form', 'designations'))
                 ->setPaper('a4', 'landscape');*/
            // return $pdf->stream();



            $stylesheet = file_get_contents('css/bootstrap.min.css');


            //return $pdf->stream('document.pdf');
            $view = View::make('employee::reports.seniority-list.seniority-list-pdf', compact('institute','hide_blank','selectForm', 'designationWiseEmployee', 'reportName', 'allDesignation', 'all_form', 'designations'));

            $html = $view->render();
            $mpdf = new MPDF\Mpdf([
                'default_font' => 'bangla',
                'format' => $pageSize,
                'orientation' => 'L',
                'margin_left' => 2,
                'margin_right' => 2,
                //'margin_top' => 3,
               // 'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0
            ]);
           // $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->SetHTMLFooter('
         <div style="border: none;background:#002d00;">
         <table  style="border: none;background:#002d00;width: 100%;table-layout: fixed;color: white;font-weight: bold" > 
    <tr style="border: none">
        <td width="66%" style="border: none;text-align: left"><div style="padding:.5rem">
        <span>Printed from <b>CCIS</b> by '.$user->name.' on '.Carbon::now().' </span>
         </div></td>
        <td  width="33%" style="border: none;color: white;text-align: right">Page {PAGENO} of {nbpg}</td>
       
    </tr>
</table>
</div>
');

            $mpdf->autoScriptToLang = true;// Mandatory
            $mpdf->autoLangToFont = true;//Mandatory
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } else {


            return view('employee::reports.seniority-list.seniority-list-table',
                compact('selectForm','hide_blank', 'designationWiseEmployee', 'reportName', 'allDesignation', 'all_form', 'designations'));
        }
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
}
