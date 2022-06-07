<?php

namespace Modules\HealthCare\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\HealthCare\Entities\HealthDrug;
use Modules\HealthCare\Entities\HealthInvestigation;
use Modules\HealthCare\Entities\HealthInvestigationReport;
use Modules\HealthCare\Entities\HealthPrescription;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App\Helpers\InventoryHelper;
use PDF;
use App;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\HealthCare\Entities\HealthCareAttachFile;
use Modules\HealthCare\Entities\HealthDrugDetails;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;
use Modules\Inventory\Entities\AllStockOutModel;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Inventory\Entities\StoreWiseItemModel;

class HealthCareController extends Controller
{
    use InventoryHelper;
    use App\Helpers\UserAccessHelper;
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index(Request $request)
    {
        $prescriptions = HealthPrescription::with('cadet', 'employee','singleHouse')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $pageAccessData = self::linkAccess($request);
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get(['id', 'name']);
        $batchs = Batch::get(['id', 'batch_name']);
        $departments = EmployeeDepartment::orderBy('name', 'ASC')->get(['id', 'name']);
        $designations = EmployeeDesignation::orderBy('name', 'ASC')->get(['id', 'name']);


        return view('healthcare::pages.prescription.index', compact('houses', 'departments', 'designations', 'batchs', 'prescriptions', 'pageAccessData'));
    }
    public function searchSection(Request $request)
    {
        return Section::where('batch_id', $request->batch)->get();
    }

    public function create(Request $request)
    {
        if ($request->followUpId) {
            $followUpPrescription = HealthPrescription::with('attachFile')->where('barcode', $request->followUpId)->first();
            if (!$followUpPrescription) {
                Session::flash('errorMessage', 'Error! No prescription found with this ID.');
                return redirect()->back();
            }
            // User Type 1 = cadet and 2 = HR/FM
            $userType = $followUpPrescription->patient_type;
            if ($userType == 1) {
                $house = House::findOrFail($request->house_id);
                $patient = StudentInformation::with('singleUser','singleRoom.house')->findOrFail($followUpPrescription->patient_id);
            } else if ($userType == 2) {
                $patient = EmployeeInformation::with('singleUser')->findOrFail($followUpPrescription->patient_id);
            } else {
                $patient = null;
            }
        } else {
            $request->validate([
                'userType' => 'required',
                'userId' => 'required'
            ]);
            $followUpPrescription = null;
            // User Type 1 = cadet and 2 = HR/FM
            $userType = $request->userType;
            if ($userType == 1) {
                $house = House::findOrFail($request->house_id)->name;
                $patient = StudentInformation::with('singleUser','singleRoom.house')->findOrFail($request->userId);
            } else if ($userType == 2) {
                $patient = EmployeeInformation::with('singleUser')->findOrFail($request->userId);
            } else {
                $patient = null;
            }
        }


        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $todayDate = Carbon::now();
        $medicalOfficer = Auth::user();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $investigations = HealthInvestigation::all();
        $drugs = CadetInventoryProduct::where([
            'code_type_id' => 3
        ])->get();

        $prescription = null;
        return view('healthcare::pages.prescription.prescription', compact('userType', 'patient', 'patientAge', 'todayDate', 'medicalOfficer', 'institute', 'investigations', 'prescription', 'followUpPrescription', 'drugs'));
    }


    public function store(Request $request)
    {

        // Attach File Validation Start
        if ($request->hasFile('fileAttach')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'pdf'];
            foreach ($request->file('fileAttach') as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if (!$check) {
                    Session::flash('errorMessage', 'Please Attach Jpg,Png or PDF.');
                    return redirect()->back();
                }
            }
        }
        // Attach File Validation End


        $total_pr = HealthPrescription::count() + 1;
        $institueName = Institute::findOrFail(self::getInstituteId())->institute_alias;
        $generate_pr = $institueName . '-PR-' . $total_pr;
        $total_lab = HealthInvestigationReport::count() + 1;
        $generateLabBarCode = $institueName . '-LR-' . $total_lab;
        $investigations = json_decode($request->content, 1)['investigations'];
        $treatments = json_decode($request->content, 1)['treatments'];


        DB::beginTransaction();
        try {

            if ($request->followUp) {
                $followUpPrescription = HealthPrescription::where('barcode', $request->followUp)->first();
                $followUpPrescription->update([
                    'status' => 3,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                ]);
            }

            // Store Prescription at prescription table
            $house_id = isset($request->house)?$request->house:null;
            $prescriptionId = HealthPrescription::insertGetId([
                'house' => $house_id,
                'barcode' => $generate_pr,
                'patient_type' => $request->patientType,
                'patient_id' => $request->patientId,
                'follow_up' => $request->followUp,
                'content' => $request->content,
                'status' => $request->status,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);
            // Prescription Attach File Insert
            if ($request->hasFile('fileAttach')) {
                $images = $request->file('fileAttach');
                foreach ($images as $image) {

                    $extension = $image->getClientOriginalExtension();
                    $fileName = 'attach-'.$prescriptionId.'-'.rand().'-'. time() . '.' . $extension;
                    $image->move(public_path() . '/assets/HealthCare/', $fileName);
                    HealthCareAttachFile::create([
                        'pr_id' => $prescriptionId,
                        'file' => $fileName,
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }


            // Store Investigation at investiagation_reports table
            if (sizeof($investigations) > 0) {
                foreach ($investigations as $key => $investigation) {
                    HealthInvestigationReport::insert([
                        'lab_barcode' => $generateLabBarCode,
                        'patient_type' => $request->patientType,
                        'patient_id' => $request->patientId,
                        'prescription_id' => $prescriptionId,
                        'investigation_id' => $investigation['id'],
                        'status' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            // Store Drugs at drugs table
            if (sizeof($treatments) > 0) {
                foreach ($treatments as $key => $treatment) {
                    HealthDrug::insert([
                        'patient_type' => $request->patientType,
                        'patient_id' => $request->patientId,
                        'prescription_id' => $prescriptionId,
                        'product_id' => $treatment['drugId'],
                        'required_quantity' => $treatment['quantity'],
                        'status' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Prescription created successfully.');
            return redirect('/healthcare/prescription');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Prescription.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('healthcare::show');
    }


    public function edit($id, Request $request)
    {
        $pageAccessData = self::linkAccess($request, ['manualRoute' => 'healthcare/prescription']);
        $prescription = HealthPrescription::with('attachFile')->findOrFail($id);
        $followUpPrescription = null;

        // User Type 1 = cadet and 2 = HR/FM
        $userType = $prescription->patient_type;
        if ($userType == 1) {
            $patient = StudentInformation::with('singleUser','singleRoom.house')->findOrFail($prescription->patient_id);
        } else if ($userType == 2) {
            $patient = EmployeeInformation::with('singleUser')->findOrFail($prescription->patient_id);
        } else {
            $patient = null;
        }
        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $todayDate = Carbon::parse($prescription->created_at);
        $medicalOfficer = $prescription->createdBy;
        $institute = Institute::findOrFail($prescription->institute_id);
        $investigations = HealthInvestigation::all();
        $drugs = CadetInventoryProduct::where([
            'code_type_id' => 3
        ])->get();
        $house = ($prescription->house) ? $prescription->house : " ";
        return view('healthcare::pages.prescription.prescription', compact('house', 'userType', 'pageAccessData', 'patient', 'patientAge', 'todayDate', 'medicalOfficer', 'institute', 'investigations', 'prescription', 'followUpPrescription', 'drugs'));
    }


    public function update(Request $request, $id)
    {
        // Attach File Validation Start
        if ($request->hasFile('fileAttach')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'pdf'];
            foreach ($request->file('fileAttach') as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if (!$check) {
                    Session::flash('errorMessage', 'Please Attach Jpg,Png or PDF.');
                    return redirect()->back();
                }
            }
        }
        // Attach File Validation End
        $prescription = HealthPrescription::findOrFail($id);
        $investigations = json_decode($request->content, 1)['investigations'];
        $treatments = json_decode($request->content, 1)['treatments'];
        // barCode generate Start
        $institueName = Institute::findOrFail(self::getInstituteId())->institute_alias;
        $total_lab = HealthInvestigationReport::count() + 1;
        $generateLabBarCode = $institueName . '-LR-' . $total_lab;
        // barCode generate End

        DB::beginTransaction();
        try {
            $prescription->update([
                'content' => $request->content,
                'status' => $request->status,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);


            // Delete old awaiting investigations
            HealthInvestigationReport::where([
                'prescription_id' => $prescription->id,
                'status' => 1,
            ])->delete();

            // Store Investigation at investiagation_reports table
            if (sizeof($investigations) > 0) {
                foreach ($investigations as $key => $investigation) {
                    $oldInvestigations = HealthInvestigationReport::where([
                        'prescription_id' => $prescription->id,
                        'investigation_id' => $investigation['id'],
                    ])->first();
                    if (!$oldInvestigations) {
                        HealthInvestigationReport::insert([
                            'lab_barcode' => $generateLabBarCode,
                            'patient_type' => $request->patientType,
                            'patient_id' => $request->patientId,
                            'prescription_id' => $prescription->id,
                            'investigation_id' => $investigation['id'],
                            'status' => 1,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            // Prescription Attach File Insert
            if ($request->hasFile('fileAttach')) {
                $images = $request->file('fileAttach');
                foreach ($images as $image) {

                    $extension = $image->getClientOriginalExtension();
                    $fileName = 'attach-'.$id.'-'.rand().'-'. time() . '.' . $extension;
                    $image->move(public_path() . '/assets/HealthCare/', $fileName);
                    HealthCareAttachFile::create([
                        'pr_id' => $id,
                        'file' => $fileName,
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            // Delete old pending drug reports
            $oldDrug = HealthDrug::where([
                'prescription_id' => $prescription->id,
                'status' => 1,
            ])->delete();

            // Store Drugs at drugs table
            if (sizeof($treatments) > 0) {
                foreach ($treatments as $key => $treatment) {
                    $oldDrug = HealthDrug::where([
                        'prescription_id' => $prescription->id,
                        'product_id' => $treatment['drugId'],
                    ])->first();
                    if (!$oldDrug) {
                        HealthDrug::insert([
                            'patient_type' => $request->patientType,
                            'patient_id' => $request->patientId,
                            'prescription_id' => $prescription->id,
                            'product_id' => $treatment['drugId'],
                            'required_quantity' => $treatment['quantity'],
                            'status' => 1,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Prescription updated successfully.');
            return redirect('/healthcare/prescription');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating prescription.');
            return redirect()->back();
        }
    }

    // Attach-File Remove
    public function attachFileRemove(Request $request){
        $existFile = HealthCareAttachFile::findOrFail($request->id);
       $filePath =  public_path() . '/assets/HealthCare/'. $existFile->file;
        if(file_exists($filePath)){
            unlink($filePath);
            $existFile->forceDelete();
        }
        return back();
    }
    public function destroy($id)
    {
        $prescription = HealthPrescription::findOrFail($id);

        $publishedInvestigation = $prescription->investigations->firstWhere('status', '!=', 1);
        $deliveredDrug = $prescription->drugReports->firstWhere('status', '!=', 1);

        if ($publishedInvestigation) {
            Session::flash('errorMessage', 'Can not delete! Drug delivered under this prescription.');
            return redirect()->back();
        } elseif ($deliveredDrug) {
            Session::flash('errorMessage', 'Can not delete! Investigation report published for this prescription.');
            return redirect()->back();
        } else {
            $prescription->investigations()->delete();
            $prescription->drugReports()->delete();
            $prescription->delete();

            Session::flash('message', 'Success! Prescription deleted successfully.');
            return redirect()->back();
        }
    }

    public function prescriptionStatusChange($id, $status)
    {
        $prescription = HealthPrescription::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($status == 2) {
                $prescriptionHistory = [[
                    'status' => $status,
                    'dateTime' => Carbon::now()
                ]];

                $updateStatus = $prescription->update([
                    'status' => $status,
                    'history' => $prescriptionHistory,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                $updateStatus = $prescription->update([
                    'status' => $status,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
            }

            if ($updateStatus) {
                DB::commit();
                Session::flash('message', 'Success! Prescription status changed successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating status.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating status.');
            return redirect()->back();
        }
    }

    public function closePrescription(Request $request, $id)
    {
        $prescription = HealthPrescription::findOrFail($id);

        $statusHistory = [
            'status' => 3,
            'dateTime' => Carbon::now()
        ];
        $daysInHospital = 0;

        if ($prescription->history) {
            $prescriptionHistory = json_decode($prescription->history, 1);
            $admitionDate = Carbon::parse(end($prescriptionHistory)['dateTime']);
            $daysInHospital = $admitionDate->diffInDays(Carbon::now());
            array_push($prescriptionHistory, $statusHistory);
        } else {
            $prescriptionHistory = [$statusHistory];
        }

        $prescription->update([
            'status' => 3,
            'history' => json_encode($prescriptionHistory),
            'days_in_hospital' => $daysInHospital,
            'score' => $request->score,
            'remarks' => $request->remarks,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ]);

        Session::flash('message', 'Success! Prescription status changed successfully.');
        return redirect()->back();
    }

    public function printPrescription($id)
    {
        $prescription = HealthPrescription::with('attachFile')->findOrFail($id);

        $userType = $prescription->patient_type;
        if ($userType == 1) {
            $patient = StudentInformation::with('singleUser')->findOrFail($prescription->patient_id);
        } else if ($userType == 2) {
            $patient = EmployeeInformation::with('singleUser')->findOrFail($prescription->patient_id);
        } else {
            $patient = null;
        }
        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $todayDate = Carbon::parse($prescription->created_at);
        $medicalOfficer = $prescription->createdBy;
        $institute = Institute::findOrFail($prescription->institute_id);
        $investigations = HealthInvestigation::all();
        $drugs = CadetInventoryProduct::where([
            'code_type_id' => 3
        ])->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('healthcare::pages.prescription.prescription-pdf', compact('userType', 'patient', 'patientAge', 'todayDate', 'medicalOfficer', 'institute', 'investigations', 'prescription', 'drugs'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }


    public function usersFromUserType(Request $request)
    {

        $department = $request->department;
        $designation = $request->designation;
        $hrSearch = [];
        if ($department) {
            $hrSearch['department'] = $department;
        }
        if ($designation) {
            $hrSearch['designation'] = $designation;
        }

        $house_id = $request->house_id;
        $batch = $request->batch;
        $section = $request->section;
        $houseStdId = [];
        $searchCadet = [];
        if ($batch) {
            $searchCadet['batch'] = $batch;
        }
        if ($section) {
            $searchCadet['section'] = $section;
        }

        if ($house_id) {
            $houseStdId = RoomStudent::where([
                'house_id' => $house_id,
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->pluck('student_id')->toArray();
        }
        if (sizeof($houseStdId) > 0) {
            $studentId = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->where($searchCadet)->whereIn('std_id', $houseStdId)->pluck('user_id')->toArray();
        } else {

            $studentId = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->where($searchCadet)->pluck('user_id')->toArray();
        }


        if ($request->userType == 1) {
            if (!empty($house_id) || !empty($batch) || !empty($section)) {
                return StudentInformation::with('singleUser')->where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute(),
                ])->whereIn('user_id', $studentId)->get();
            } else {
                return StudentInformation::with('singleUser')->where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute(),
                ])->get();
            }
        } else if ($request->userType == 2) {
            if (!empty($department) || !empty($designation)) {
                return EmployeeInformation::with('singleUser')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->where($hrSearch)->get();
            } else {
                return EmployeeInformation::with('singleUser')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->get();
            }
        } else {
            return [];
        }
    }

    public function drugReports(Request  $request)
    {
        $pageAccessData = self::linkAccess($request);


        $drugReports = HealthDrug::with('cadet.singleUser', 'cadetProfile.singleLevel', 'cadetProfile.singleBatch', 'cadetProfile.singleSection', 'employee.singleUser', 'employee.singleDepartment', 'employee.singleDesignation', 'createdBy', 'drug', 'prescription', 'details')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->latest()->get();
        return view('healthcare::pages.drugs.drug-reports', compact('pageAccessData', 'drugReports'));
    }

    public function drugDeliverModal($id)
    {
        $drugReport = HealthDrug::with('cadet.singleUser', 'employee.singleUser', 'createdBy', 'drug.stockGroup', 'drug.stockCategory', 'prescription', 'details')->findOrFail($id);
        $inventoryStores = InventoryStore::all();

        return view('healthcare::pages.drugs.modal.drug-deliver', compact('drugReport', 'inventoryStores'));
    }

    public function drugDeliver($id, Request $request)
    {
        $request->validate([
            'storeId' => 'required',
            'newIssue' => 'required'
        ]);

        $drugReport = HealthDrug::findOrFail($id);
        $newDrugQty = $drugReport->disbursed_quantity + $request->newIssue;
        $status = ($drugReport->required_quantity <= $newDrugQty) ? 2 : 3;

        DB::beginTransaction();
        try {
            // Stock Out from Inventory Start ----------------------------------
            $storeWiseItem = StoreWiseItemModel::module()->where([
                'store_id' => $request->storeId,
                'item_id' => $drugReport->product_id
            ])->first();
            $itemInfo = CadetInventoryProduct::find($drugReport->product_id);
            $approvalData = (object)['store_id' => $request->storeId, 'item_id' => $drugReport->product_id, 'qty' => $request->newIssue];
            $stockCalInfo  = self::storeStockDecrement($itemInfo, $approvalData);
            $flag = $stockCalInfo['flag'];
            if ($flag) {
                $drugReport->update([
                    'disbursed_quantity' => $newDrugQty,
                    'status' => $status,
                    'updated_by' => Auth::id(),
                ]);

                HealthDrugDetails::create([
                    'drug_report_id' => $drugReport->id,
                    'disburse_qty' => $request->newIssue,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                $current_stock = $stockCalInfo['current_stock'];
                StoreWiseItemModel::module()->where('item_id', $approvalData->item_id)->where('store_id', $approvalData->store_id)->update([
                    'current_stock' => $current_stock
                ]);

                AllStockOutModel::create([
                    'date' => date('Y-m-d'),
                    'item_id' => $approvalData->item_id,
                    'stock_out_id' => $drugReport->prescription->id,
                    'stock_out_details_id' => $drugReport->id,
                    'store_id' => $approvalData->store_id,
                    'qty' => $approvalData->qty,
                    'rate' => $storeWiseItem->avg_cost_price,
                    'stock_out_reason' => "Health Care"
                ]);

                DB::commit();
                Session::flash('message', 'Success! Drug delivered successfully.');
                return redirect()->back();
            } else {
                DB::rollBack();
                Session::flash('errorMessage', $stockCalInfo['msg']);
                return redirect()->back();
            }

            // Stock Out from Inventory End ------------------------------------

            DB::commit();
            Session::flash('message', 'Success! Drug delivered successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('errorMessage', 'Error delivering drug.');
            return redirect()->back();
        }
    }

    public function getStockFromStore(Request $request)
    {
        return StoreWiseItemModel::where([
            'store_id' => $request->storeId,
            'item_id' => $request->productId
        ])->first();
    }
}
