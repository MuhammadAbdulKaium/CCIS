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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeePublication;
use Modules\Employee\Entities\EmployeePublicationDetails;

class EmployeePublicationController extends Controller
{
    use UserAccessHelper;

    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id, Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => "employee/manage"]);
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $publications = EmployeePublication::with('publicationEditions','singleInstitute')->where([
            'employee_id' => $id,
        ])->latest()->get();
        return view('employee::pages.profile.publication', compact('employeeInfo', 'publications', 'pageAccessData'))->with('page', 'publication');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);

        return view('employee::pages.modals.publication-create', compact('employeeInfo'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publication_title' => 'required',
            'editions.*' => 'required',
            'date.*' => 'required',
            'attachment.*' => 'nullable|mimes:jpeg,jpg,png,pdf,doc,docx|max:600'
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();

            try {
                // publication creat
                $publication_id =  EmployeePublication::insertGetId([
                    "employee_id" => $request->employee_id,
                    "publication_title" => $request->publication_title,
                    "publication_description" => $request->publication_description,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    "created_at" => Carbon::now(),
                ]);

                // publication details insert
                if ($publication_id > 0) {

                    foreach ($request->editions as $key => $value) {
                        $attachmentName = null;
                        $date = $request->date[$key] ? $request->date[$key] : " ";
                        $remarks = $request->remarks[$key] ? $request->remarks[$key] : " ";
                        $editions = $request->editions[$key] ? $request->editions[$key] : " ";
                        if (isset($request->file('attachment')[$key])) {
                            $image = $request->file('attachment')[$key];
                            $attachmentName = 'Publication-' . $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path() . '/assets/Employee/Publication/', $attachmentName);
                        }

                        EmployeePublicationDetails::create([
                            "pub_id" => $publication_id,
                            "employee_id" => $request->employee_id,
                            "editions" => $editions,
                            "date" => $date,
                            "remarks" => $remarks,
                            "attachment" => $attachmentName,
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
                DB::commit();
                Session::flash('message', 'Employee Publication added successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Data added Fail');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Fill the fields with valid data.');
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
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        $employeePublication = EmployeePublication::with('publicationEditions')->findOrFail($id);

        return view('employee::pages.modals.publication-update', compact('employeePublication'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'publication_title' => 'required',
            'editions.*' => 'required',
            'date.*' => 'required',
            'attachment.*' => 'nullable|mimes:jpeg,jpg,png,pdf,doc,docx|max:600'
        ]);


        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $publication = EmployeePublication::findOrFail($id);
                // publication Updated Start
                $publication_title = $request->publication_title ? $request->publication_title : $publication->publication_title;
                $publication_description = $request->publication_description ? $request->publication_description : $publication->publication_description;
                $publication->update([
                    "employee_id" => $request->employee_id,
                    "publication_title" => $publication_title,
                    "publication_description" => $publication_description,
                    'updated_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    "updated_at" => Carbon::now(),
                ]);
                // publication Updated End
                $employee_publication = EmployeePublicationDetails::where('pub_id', $id)->get();
                $checkUpdateOrInsert = array_intersect($employee_publication->pluck('id')->toArray(), array_keys($request->editions)); //if update else insert
                $array_diff = array_diff(array_keys($request->editions), $checkUpdateOrInsert); // if insert 
                $delete_Editions = array_diff($employee_publication->pluck('id')->toArray(), $checkUpdateOrInsert); //delete Edition

                //  Delete Edition Start
                if (count($delete_Editions) > 0) {
                    foreach ($delete_Editions as $de_id) {
                        $delete_Edition =  EmployeePublicationDetails::findOrFail($de_id);
                        $file_name = $delete_Edition->attachment;
                        $file_path = public_path() . '/assets/Employee/Publication/' . $file_name;
                        if ($file_name && file_exists($file_path)) {
                            unlink($file_path);
                        }
                        $delete_Edition->delete();
                    }
                }
                // Delete Edition End

                // Employee Edition  Insert Start
                if (count($array_diff) > 0) {
                    foreach ($array_diff as $diff_id) {
                        $date = $request->date[$diff_id] ? $request->date[$diff_id] : " ";
                        $remarks = $request->remarks[$diff_id] ? $request->remarks[$diff_id] : " ";
                        $editions = $request->editions[$diff_id] ? $request->editions[$diff_id] : " ";
                        $attachmentName = null;
                        if (isset($request->file('attachment')[$diff_id])) {
                            $image = $request->file('attachment')[$diff_id];
                            $attachmentName = 'Publication-' . $diff_id . '-' . time() . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path() . '/assets/Employee/Publication/', $attachmentName);
                        }
                        EmployeePublicationDetails::create([
                            "pub_id" => $id,
                            "employee_id" => $request->employee_id,
                            "editions" => $editions,
                            "date" => $date,
                            "remarks" => $remarks,
                            "attachment" => $attachmentName,
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
                // Employee Edition  Insert End

                // Employee Edition Update or Insert Start
                if (count($checkUpdateOrInsert) > 0) {
                    foreach ($checkUpdateOrInsert as $up_id) {
                        $edition_update = EmployeePublicationDetails::findOrFail($up_id);

                        $date = $request->date[$up_id] ? $request->date[$up_id] : $edition_update->date;
                        $remarks = $request->remarks[$up_id] ? $request->remarks[$up_id] : " ";
                        $editions = $request->editions[$up_id] ? $request->editions[$up_id] : $edition_update->editions;

                        if (isset($request->file('attachment')[$up_id])) {
                            $file_name = $edition_update->attachment;
                            $file_path = public_path() . '/assets/Employee/Publication/' . $file_name;
                            if ($request->file('attachment')[$up_id] && $file_name) {

                                if ($file_name && file_exists($file_path)) {
                                    unlink($file_path);
                                }
                            }
                            $image = $request->file('attachment')[$up_id];
                            $attachmentName = 'Publication-' . $up_id . '-' . time() . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path() . '/assets/Employee/Publication/', $attachmentName);
                            $edition_update->update([
                                "attachment" => $attachmentName,
                            ]);
                        }
                        $edition_update->update([
                            "pub_id" => $id,
                            "employee_id" => $request->employee_id,
                            "editions" => $editions,
                            "date" => $date,
                            "remarks" => $remarks,
                            'updated_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                            "updated_at" => Carbon::now(),
                        ]);
                    }
                } else {
                    foreach ($checkUpdateOrInsert as $up_id) {

                        $date = $request->date[$up_id] ? $request->date[$up_id] : " ";
                        $remarks = $request->remarks[$up_id] ? $request->remarks[$up_id] : " ";
                        $editions = $request->editions[$up_id] ? $request->editions[$up_id] : " ";
                        $attachmentName = null;
                        if (isset($request->file('attachment')[$up_id])) {
                            $image = $request->file('attachment')[$up_id];
                            $attachmentName = 'Publication-' . $up_id . '-' . time() . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path() . '/assets/Employee/Publication/', $attachmentName);
                        }
                        EmployeePublicationDetails::create([
                            "pub_id" => $id,
                            "employee_id" => $request->employee_id,
                            "editions" => $editions,
                            "date" => $date,
                            "remarks" => $remarks,
                            "attachment" => $attachmentName,
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
                // Employee Edition Update or Insert End
                DB::commit();
                Session::flash('message', 'Employee Publication Updated successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Data Updated Fail');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Fill the fields with valid data.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete($id)
    {
        $employeePublication = EmployeePublication::with('publicationEditions')->findOrFail($id);
        if (count($employeePublication->publicationEditions) > 0) {
            $employeeEdition = EmployeePublicationDetails::where('pub_id', $id)->get();
            foreach ($employeeEdition as $edition) {
                $file_name = $edition->attachment;
                $file_path = public_path() . '/assets/Employee/Publication/' . $file_name;
                if ($file_name && file_exists($file_path)) {
                    unlink($file_path);
                }
                $edition->delete();
            }
        }
        $employeePublication->delete();
        Session::flash('message', 'Employee Publication deleted successfully.');
        return redirect()->back();
    }
}
