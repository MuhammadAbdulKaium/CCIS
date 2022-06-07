<?php

namespace Modules\Employee\Http\Controllers;

use App\Address;
use App\Content;
use App\RoleUser;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Employee\Entities\EmployeeAttachment;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeDocument;
use Modules\Employee\Entities\EmployeeInformation;
use App\UserInfo;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeStatus;
use Modules\Employee\Entities\EmployeeStatusAssign;
use Modules\Employee\Entities\HrManageView;
use Modules\Employee\Entities\Imports\EmployeeImport;
use Modules\Payroll\Entities\BankBranchDetails;
use Modules\Payroll\Entities\BankDetails;
use Modules\Payroll\Entities\SalaryAssign;
use Modules\Payroll\Entities\SalaryDeduct;
use Modules\Payroll\Entities\SalaryGenerateHistoryList;
use Modules\Payroll\Entities\SalaryGenerateList;
use Modules\Payroll\Entities\SalaryHead;
use Modules\Payroll\Entities\SalaryProcessHistoryList;
use Modules\Payroll\Entities\SalaryScale;
use Modules\Payroll\Entities\salaryStructure;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Redirect;
use Session;
use Validator;
use App\Models\Role;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\UserAccessHelper;
use DateTime;


class EmployeeController extends Controller
{

    private $user;
    private $userInfo;
    private $role;
    private $department;
    private $designation;
    private $employeeInformation;
    private $academicHelper;
    private $country;
    private $campus;
    use UserAccessHelper;


    // constructor
    public function __construct(User $user, Role $role, EmployeeDepartment $department, EmployeeDesignation $designation, EmployeeInformation $employeeInformation, UserInfo $userInfo, AcademicHelper $academicHelper, Country $country, Campus $campus)
    {
        $this->user = $user;
        $this->userInfo = $userInfo;
        $this->role = $role;
        $this->department = $department;
        $this->designation = $designation;
        $this->employeeInformation = $employeeInformation;
        $this->academicHelper = $academicHelper;
        $this->country = $country;
        $this->campus = $campus;
    }

    // employee index function
    public function index(Request $request)
    {


        // return view
        return view('employee::pages.index', compact('pageAccessData'));
    }

    public function importEmployee(Request $request)
    {
        $pageAccessData = self::linkAccess($request);


        return view('employee::pages.employee-import.employee-import-info', compact('pageAccessData'));
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    //
    public function uploadEmployee(Request $request)
    {
        $employeeCounter = $request->input('emp_count');
        // checking
        if ($employeeCounter > 0) {
            // Start transaction!
            DB::beginTransaction();
            // loop counter
            $loopCounter = 0;
            // looping
            for ($i = 1; $i <= $employeeCounter; $i++) {
                // receive single employee
                $singleEmployee = $request->$i;

                // employee details
                $campus = $singleEmployee['campus'];
                $role = $singleEmployee['role'];
                $category = $singleEmployee['category'];
                // $title = $singleEmployee['title'];
                $firstName = $singleEmployee['first_name'];
                $middleName = $singleEmployee['middle_name'];
                $lastName = $singleEmployee['last_name'];
                $email = strtolower($singleEmployee['email']);
                $department = $singleEmployee['department'];
                $designation = $singleEmployee['designation'];
                $gender = $singleEmployee['gender'];
                $birthDate = $singleEmployee['birth_date'];
                $joiningDate = $singleEmployee['joining_date'];

                // validating all requested input data
                $validator = Validator::make(['email' => $email], [
                    'email' => 'required|unique:users'
                ]);

                // storing requesting input data
                if ($validator->passes()) {

                    // employee user creation
                    try {
                        $userFullName = $firstName . " " . $middleName . " " . $lastName;
                        // create user profile for student
                        $userProfile = $this->manageUserProfile(0, [
                            'name' => $userFullName,
                            'email' => $email,
                            'password' => bcrypt(123456)
                        ]);
                        // checking user profile
                        if ($userProfile) {
                            $userInfoProfile = new $this->userInfo();
                            // add user details
                            $userInfoProfile->user_id = $userProfile->id;
                            $userInfoProfile->institute_id = $this->academicHelper->getInstitute();
                            $userInfoProfile->campus_id = $campus;
                            // save user Info profile
                            $userInfoProfileSaved = $userInfoProfile->save();
                        }
                    } catch (ValidationException $e) {
                        // Rollback and then redirect
                        // back to form with errors
                        // Redirecting with error message
                        DB::rollback();
                        return redirect()->back()
                            ->withErrors($e->getErrors())
                            ->withInput();
                    } catch (Exception $e) {
                        DB::rollback();
                        throw $e;
                    }

                    // student profile creation
                    try {
                        $employeeInfo = $this->manageEmployeeProfile(0, [
                            'user_id' => $userProfile->id,
                            //                            'title'        => $title,
                            'first_name' => $firstName,
                            'middle_name' => $middleName,
                            'last_name' => $lastName,
                            'alias' => strtolower($middleName),
                            'gender' => $gender,
                            'dob' => date('Y-m-d', strtotime($birthDate)),
                            'doj' => date('Y-m-d', strtotime($joiningDate)),
                            'department' => $department,
                            'designation' => $designation,
                            'category' => $category,
                            'email' => $email,
                            'phone' => 0,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                            //                           'nationality'   => 0,
                            //                        'experience_year' => $request->input('experience_year'),
                            //                        'experience_month' => $request->input('experience_month'),
                        ]);
                    } catch (ValidationException $e) {
                        // Rollback and then redirect
                        // back to form with errors
                        DB::rollback();
                        return redirect()->back()
                            ->withErrors($e->getErrors())
                            ->withInput();
                    } catch (Exception $e) {
                        DB::rollback();
                        throw $e;
                    }

                    // student role assignment
                    try {
                        // set employee role
                        $employeeRoleProfileAssignment = $this->setEmpRole($role, $userProfile);
                    } catch (ValidationException $e) {
                        // Rollback and then redirect
                        // back to form with errors
                        DB::rollback();
                        return redirect()->back()
                            ->withErrors($e->getErrors())
                            ->withInput();
                    } catch (Exception $e) {
                        DB::rollback();
                        throw $e;
                    }

                    // loop counter
                    $loopCounter = ($loopCounter + 1);
                } else {
                    Session::flash('warning', 'Duplicate email found');
                    // receiving page action
                    return redirect('/employee/import');
                }
            }
            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();
            // looping checking
            if ($loopCounter == $employeeCounter) {
                Session::flash('success', 'employee list uploaded');
                // return redirect
                return redirect('/employee/manage');
            }
        } else {
            Session::flash('warning', 'Employee List is empty');
            // return redirect
            return redirect()->back();
        }
    }

    //
    public function showImportedEmployeeList(Request $request)
    {
        $users = User::all();
        $data = Excel::toArray(new EmployeeImport(), $request->file('employee_list'));
        //        return $data;
        return view('employee::pages.employee-import.employee-import-list', compact('data', 'users'));
    }

    public function checkImportedEmployeeList(Request $request)
    {
        $array = array();
        $array2 = array();
        $array3 = array();
        $totalRow = array();
        $currentUserEmailArray = array();
        for ($i = 0; $i < count($request->employee_no); $i++) {
            if (isset($array2[$request['employee_no'][$i]])) {
                $array3[$array2[$request['employee_no'][$i]] + 1] = $request['employee_no'][$i];
                $array3[$i + 1] = $request['employee_no'][$i];
            } else {
                $array2[$request['employee_no'][$i]] = $i;
            }
        }
        if (sizeof($array3)) {
            return ['status' => 'inlineDuplicate', 'msg' => 'Inline Duplicated Data', 'inlineUser' => $array3];
        } else {
            for ($i = 0; $i < count($request->employee_no); $i++) {
                $currentUser = User::where('username', $request['employee_no'][$i])->first();
                if ($currentUser) {
                    array_push($array, $currentUser);
                }
            }
            if (sizeof($array)) {
                return ['status' => 'duplicate', 'msg' => 'Duplicated Data', 'currentUser' => $array];
            } else {
                for ($i = 0; $i < count($request->employee_no); $i++) {
                    if ($request['email_login_id'][$i]) {
                        $currentUserEmail = User::where('email', $request['email_login_id'][$i])->first();
                        if ($currentUserEmail) {
                            array_push($currentUserEmailArray, $currentUserEmail);
                        }
                    }
                }
                if (sizeof($currentUserEmailArray)) {
                    return ['status' => 'emailDuplicate', 'msg' => 'Email Duplicated Data', 'duplicateEmail' => $currentUserEmailArray];
                } else {
                    DB::beginTransaction();
                    try {
                        for ($i = 0; $i < count($request['employee_no']); $i++) {
                            //Get Department Value
                            $deptInput = $request['department'][$i];
                            if ($deptInput) {
                                $deptInfo = EmployeeDepartment::where([
                                    'name' => $deptInput
                                ])->first();

                                if ($deptInfo) {
                                    $department = $deptInfo->id;
                                } else {
                                    $department = 0;
                                }
                            }
                            //Get Designation Value
                            $desigInput = $request['designation'][$i];
                            if ($desigInput) {
                                $desigInfo = EmployeeDesignation::where([
                                    'name' => $desigInput
                                ])->first();
                                if ($desigInfo) {
                                    $designation = $desigInfo->id;
                                } else {
                                    $designation = 0;
                                }
                            }

                            //Get category Value
                            $getCategoryInput = $request['category'][$i];
                            if ($getCategoryInput) {
                                if ($getCategoryInput == 'Teaching') {
                                    $category = 1;
                                } else {
                                    $category = 2;
                                }
                            }

                            $userName = $request['employee_no'][$i];
                            $userEmail = $request['email_login_id'][$i];
                            $checkEmployee = User::where('username', '=', $userName)->first();
                            $checkEmployeeEmail = User::where('email', '=', $userEmail)->first();
                            if (!$checkEmployee) {
                                $empStore = new User();
                                $empStore->name = $request['first_name'][$i] . ' ' . $request['last_name'][$i];
                                $empStore->email = $request['email_login_id'][$i];
                                $empStore->username = $request['employee_no'][$i];
                                $empStore->password = bcrypt(123456);
                                $storeRecordID = $empStore->save();
                                if ($storeRecordID) {
                                    if ($storeRecordID) {
                                        $role_user = new RoleUser();
                                        $role_user->user_id = $empStore->id;
                                        $role_user->role_id = 5;
                                        $role_user->save();

                                        $user_campus_inst = new UserInfo();
                                        $user_campus_inst->user_id = $empStore->id;
                                        $user_campus_inst->campus_id = $this->academicHelper->getCampus();
                                        $user_campus_inst->institute_id = $this->academicHelper->getInstitute();
                                        $user_campus_inst->save();

                                        // Generating date
                                        $dob = ($this->validateDate($request['date_of_birth'][$i])) ? $request['date_of_birth'][$i] : null;
                                        $doj = ($this->validateDate($request['date_of_joining'][$i])) ? $request['date_of_joining'][$i] : null;
                                        $dor = ($this->validateDate($request['date_of_retirement'][$i])) ? $request['date_of_retirement'][$i] : null;

                                        $employeeInfo = new EmployeeInformation();
                                        $employeeInfo->user_id = $empStore->id;
                                        $employeeInfo->title = 'FM';
                                        $employeeInfo->first_name = $request['first_name'][$i];
                                        $employeeInfo->last_name = $request['last_name'][$i];
                                        $employeeInfo->alias = $request['alias'][$i];
                                        $employeeInfo->gender = $request['gender'][$i];
                                        $employeeInfo->dob = $dob;
                                        $employeeInfo->doj = $doj;
                                        $employeeInfo->dor = $dor;
                                        $employeeInfo->department = $department;
                                        $employeeInfo->designation = $designation;
                                        $employeeInfo->category = $category;
                                        $employeeInfo->email = $request['email_login_id'][$i];
                                        $employeeInfo->phone = $request['phone'][$i];
                                        $employeeInfo->alt_mobile = $request['alternative_mobile'][$i];
                                        $employeeInfo->marital_status = $request['marital_status'][$i];
                                        $employeeInfo->position_serial = $request['position_serial'][$i];
                                        $employeeInfo->institute_id = $this->academicHelper->getInstitute();
                                        $employeeInfo->campus_id = $this->academicHelper->getCampus();
                                        $employeeInfo->save();


                                        $presentAddress = new Address();
                                        $presentAddress->user_id = $empStore->id;
                                        $presentAddress->type = 'EMPLOYEE_PRESENT_ADDRESS';
                                        $presentAddress->address = $request['present_address'][$i];
                                        $presentAddress->save();

                                        $permanentAddress = new Address();
                                        $permanentAddress->user_id = $empStore->id;
                                        $permanentAddress->type = 'EMPLOYEE_PERMANENT_ADDRESS';
                                        $permanentAddress->address = $request['permanent_address'][$i];
                                        $permanentAddress->save();

                                        $last_qft = new EmployeeDocument();
                                        $last_qft->employee_id = $employeeInfo->id;
                                        $last_qft->document_type = 1;
                                        $last_qft->qualification_type = 3;
                                        $last_qft->document_details = $request['last_academic_qualification'][$i];
                                        $last_qft->created_by = Auth::id();
                                        $last_qft->campus_id = $this->academicHelper->getCampus();
                                        $last_qft->institute_id = $this->academicHelper->getInstitute();

                                        $last_qft->save();

                                        $spcl_qft = new EmployeeDocument();
                                        $spcl_qft->employee_id = $employeeInfo->id;
                                        $spcl_qft->document_type = 1;
                                        $spcl_qft->qualification_type = 2;
                                        $spcl_qft->document_details = $request['special_qualification'][$i];
                                        $spcl_qft->created_by = Auth::id();
                                        $spcl_qft->campus_id = $this->academicHelper->getCampus();
                                        $spcl_qft->institute_id = $this->academicHelper->getInstitute();
                                        $spcl_qft->save();

                                        if ($request['nid_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'nid';
                                            $employeeDocument->document_details = $request['nid_no'][$i];
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->save();
                                        }
                                        if ($request['passport_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'passport';
                                            $employeeDocument->document_details = $request['passport_no'][$i];
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->save();
                                        }

                                        if ($request['birth_certificate_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'birth';
                                            $employeeDocument->document_details = $request['birth_certificate_no'][$i];
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->save();
                                        }

                                        if ($request['tin_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'tin';
                                            $employeeDocument->document_details = $request['tin_no'][$i];
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->save();
                                        }
                                        if ($request['driving_license_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'dl';
                                            $employeeDocument->document_details = $request['driving_license_no'][$i];
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->save();
                                        }

                                        $father_info = new StudentGuardian();
                                        $father_info->type = 1;
                                        $father_info->gender = 1;
                                        $father_info->first_name = $request['fathers_name'][$i];
                                        $father_info_store = $father_info->save();
                                        if ($father_info_store) {
                                            $parent_info = new StudentParent();
                                            $parent_info->gud_id = $father_info->id;
                                            $parent_info->emp_id = $employeeInfo->id;
                                            $parent_info->save();
                                        }

                                        $mothers_info = new StudentGuardian();
                                        $mothers_info->type = 0;
                                        $mothers_info->gender = 2;
                                        $mothers_info->first_name = $request['mothers_name'][$i];
                                        $mothers_info_store = $mothers_info->save();
                                        if ($mothers_info_store) {
                                            $parent_info = new StudentParent();
                                            $parent_info->gud_id = $mothers_info->id;
                                            $parent_info->emp_id = $employeeInfo->id;
                                            $parent_info->save();
                                        }

                                        // Spouse Date
                                        $spouseDob = ($this->validateDate($request['spouse_date_of_birth'][$i])) ? $request['spouse_date_of_birth'][$i] : null;

                                        $spouse_details = new StudentGuardian();
                                        $spouse_details->type = 6;
                                        $spouse_details->gender = 2;
                                        $spouse_details->first_name = $request['spouse_name'][$i];
                                        $spouse_details->occupation = $request['spouse_occupation'][$i];
                                        $spouse_details->mobile = $request['spouse_mobile'][$i];
                                        $spouse_details->nid_number = $request['spouse_nid'][$i];
                                        $spouse_details->date_of_birth = $spouseDob;
                                        $spouse_details_save = $spouse_details->save();
                                        if ($spouse_details_save) {
                                            $parent_info = new StudentParent();
                                            $parent_info->gud_id = $spouse_details->id;
                                            $parent_info->emp_id = $employeeInfo->id;
                                            $parent_info->save();
                                        }

                                        if ($request['child_1_name'][$i]) {
                                            // Child1 Date
                                            $child1Dob = ($this->validateDate($request['child_1_date_of_birth'][$i])) ? $request['child_1_date_of_birth'][$i] : null;

                                            if ($request['child_1_gender'][$i] == 'Male') {
                                                $child_1_gender = 1;
                                            } else {
                                                $child_1_gender = 2;
                                            }

                                            $child1 = new StudentGuardian();
                                            $child1->type = $child_1_gender == 1 ? 7 : 8;
                                            $child1->gender = $child_1_gender;
                                            $child1->first_name = $request['child_1_name'][$i];
                                            $child1->date_of_birth = $child1Dob;
                                            $child1_store = $child1->save();
                                            if ($child1_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id = $child1->id;
                                                $parent_info->emp_id = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }
                                        if ($request['child_2_name'][$i]) {
                                            // Child2 Date
                                            $child2Dob = ($this->validateDate($request['child_2_date_of_birth'][$i])) ? $request['child_2_date_of_birth'][$i] : null;

                                            if ($request['child_2_gender'][$i] == 'Male') {
                                                $child_2_gender = 1;
                                            } else {
                                                $child_2_gender = 2;
                                            }

                                            $child2 = new StudentGuardian();
                                            $child2->type = $child_2_gender == 1 ? 7 : 8;
                                            $child2->gender = $child_2_gender;
                                            $child2->first_name = $request['child_2_name'][$i];
                                            $child2->date_of_birth = $child2Dob;
                                            $child2_store = $child2->save();
                                            if ($child2_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id = $child2->id;
                                                $parent_info->emp_id = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }

                                        if ($request['child_3_name'][$i]) {
                                            // Child3 Date
                                            $child3Dob = ($this->validateDate($request['child_3_date_of_birth'][$i])) ? $request['child_3_date_of_birth'][$i] : null;

                                            if ($request['child_3_gender'][$i] == 'Male') {
                                                $child_3_gender = 1;
                                            } else {
                                                $child_3_gender = 2;
                                            }

                                            $child_3 = new StudentGuardian();
                                            $child_3->type = $child_3_gender == 1 ? 7 : 8;
                                            $child_3->gender = $child_3_gender;
                                            $child_3->first_name = $request['child_3_name'][$i];
                                            $child_3->date_of_birth = $child3Dob;
                                            $child3_store = $child_3->save();
                                            if ($child3_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id = $child_3->id;
                                                $parent_info->emp_id = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }

                                        if ($request['child_4_name'][$i]) {
                                            // Child4 Date
                                            $child4Dob = ($this->validateDate($request['child_4_date_of_birth'][$i])) ? $request['child_4_date_of_birth'][$i] : null;

                                            if ($request['child_4_gender'][$i] == 'Male') {
                                                $child_4_gender = 1;
                                            } else {
                                                $child_4_gender = 2;
                                            }

                                            $child4 = new StudentGuardian();
                                            $child4->type = $child_4_gender == 1 ? 7 : 8;
                                            $child4->gender = $child_4_gender;
                                            $child4->first_name = $request['child_4_name'][$i];
                                            $child4->date_of_birth = $child4Dob;
                                            $child4_store = $child4->save();

                                            if ($child4_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id = $child4->id;
                                                $parent_info->emp_id = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }
                                    }
                                }
                                if ($storeRecordID) {
                                    array_push($totalRow, $i);
                                }
                            }
                        }
                        if (sizeof($totalRow)) {
                            DB::commit();
                            return ['status' => 'recordSuccessfull', 'msg' => 'Data record Successfully', 'recordData' => $totalRow];
                        }
                    } catch (Exception $e) {
                        // Rollback
                        DB::rollback();
                        // throw exceptions
                        throw $e;
                        return 420;
                    }
                }
            }
        }


        //
        ////        $data = Excel::toArray(new EmployeeImport(),$request->file('employee_list'));
        ////        return view('employee::pages.employee-import.employee-import-list', compact('data'));
    }


    // create employee function
    public function createEmployee()
    {
        // all department list
        $allDepartments = $this->department->orderBy('name', 'ASC')->get();
        // all designation list
        $allDesignations = $this->designation->orderBy('name', 'ASC')->get();
        // all nationality
        $allNationality = $this->country->orderBy('nationality', 'ASC')->get(['id', 'nationality']);
        // institute all campus list
        $allCampus = $this->campus->orderBy('name', 'ASC')->where('institute_id', $this->academicHelper->getInstitute())->get();
        // all roles
        $allRole = $this->role->orderBy('name', 'ASC')->whereNotIn('name', ['parent', 'student', 'admin'])->get();

        return view('employee::pages.employee-add', compact('allDepartments', 'allDesignations', 'allRole', 'allNationality', 'allCampus'));
    }

    // store employee function
    public function storeEmployee(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'campus' => 'required',
            'role' => 'required',
            // 'title'            => 'required',
            'first_name' => 'required|max:100',
            // 'last_name'        => 'required|max:100',
            // 'alias'            => 'required|max:100',
            'gender' => 'required',
            'dob' => 'required',
            //'doj'              => 'required',
            'department' => 'required|numeric',
            'designation' => 'required|numeric',
            'category' => 'required|numeric',
            'email' => 'required|email|max:100|unique:users',
            // 'phone'            => 'required|max:20',
            // 'marital_status'   => 'required',
            // 'nationality'      => 'required|numeric',
            // 'experience_year'  => 'required|numeric',
            // 'experience_month' => 'required|numeric',
            'central_position_serial'=>    'required|unique:employee_informations|numeric',

        ]);
       // return $request->all();

        // vaildator checker
        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();

            // student user cration
            try {

                $userFullName = $request->input('first_name') . " " . $request->input('middle_name') . " " . $request->input('last_name');
                // create user profile for student
                // $manageUserProfile = $this->manageUserProfile($userId, $userData);
                $userProfile = $this->manageUserProfile(0, ['name' => $userFullName, 'email' => $request->input('email'), 'password' => bcrypt(123456)]);

                // checking user profile
                if ($userProfile) {
                    $userInfoProfile = new $this->userInfo();
                    // add user details
                    $userInfoProfile->user_id = $userProfile->id;
                    $userInfoProfile->institute_id = $this->academicHelper->getInstitute();
                    $userInfoProfile->campus_id = $request->input('campus');
                    // save user Info profile
                    $userInfoProfileSaved = $userInfoProfile->save();
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                // Redirecting with error message
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

            // student profile creation
            try {

                $employeeInfo = $this->manageEmployeeProfile(0, [
                    'user_id' => $userProfile->id,
                    'title' => $request->input('title'),
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'employee_no' => $request->input('employee_no'),
                    'alias' => $request->input('alias'),
                    'gender' => $request->input('gender'),
                    'dob' => date('Y-m-d', strtotime($request->input('dob'))),
                    'doj' => date('Y-m-d', strtotime($request->input('doj'))),
                    'dor' => date('Y-m-d', strtotime($request->input('dor'))),
                    'department' => $request->input('department'),
                    'central_position_serial' => $request->input('central_position_serial'),
                    'medical_category' => $request->input('medical_category'),
                    'designation' => $request->input('designation'),
                    'category' => $request->input('category'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'nationality' => $request->input('nationality'),
                    'experience_year' => $request->input('experience_year'),
                    'experience_month' => $request->input('experience_month'),
                    'position_serial' => $request->input('position_serial'),
                    'campus_id' => $request->input('campus'),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                // Address Creation
                if ($request->present_address) {
                    Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'EMPLOYEE_PRESENT_ADDRESS',
                        'address' => $request->present_address
                    ]);
                }
                if ($request->permanent_address) {
                    Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'EMPLOYEE_PERMANENT_ADDRESS',
                        'address' => $request->permanent_address
                    ]);
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

            // student role assignment
            try {
                // set employee role
                $employeeRoleProfileAssignment = $this->setEmpRole($request->input('role'), $userProfile);
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();
            // checking and redirecting
            if ($employeeInfo) {
                Session::flash('success', 'Employee profile created');
                return redirect('/employee/profile/personal/' . $employeeInfo->id);
            } else {
                Session::flash('warning', 'unable to crate employee profile');
                // receiving page action
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // manage employee
    public function manageEmployee(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $statuses = EmployeeStatus::all();

        // campus and institute id
        $instituteId = $this->academicHelper->getInstitute();
        // employee departments
        $allDepartments = $this->department->orderBy('name', 'ASC')->get();
        $allDesignations = $this->designation->orderBy('name', 'ASC')->get();

        return view('employee::pages.employee-manage',
            compact('allDepartments', 'pageAccessData','statuses', 'allDesignations'));
    }

    public function imagePage()
    {
        return view('employee::pages.employee-import.employee-import-image');
    }

    public function imageUpload(Request $request)
    {
        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $missingStdIds = [];

            //            $file_name_timestamp = "ems";
            foreach ($image as $files) {
                $user_id = trim($files->getClientOriginalName(), '.' . $files->getClientOriginalExtension());
                $file_name_timestamp = "ems" . $user_id . '-' . Carbon::now()->timestamp;
                //Image Info
                //User Info
                $userID = User::where('username', $user_id)->first();

                if ($userID) {
                    $user = $userID['id'];
                    //                echo $user;
                    //                echo '<br>';
                    $destinationPath = 'assets/users/images/';
                    $imageName = $file_name_timestamp . "." . $files->getClientOriginalExtension();
                    $ext = $files->getClientOriginalExtension();
                    $files->move($destinationPath, $imageName);
                    $data[] = $imageName;

                    //End Image Info

                    //Personal Info
                    $personalInfo = EmployeeInformation::where('user_id', $user)->first();
                    $enrollment = $personalInfo->id;
                    //                return $enrollment;
                    //
                    $campus = $this->academicHelper->getCampus();
                    $institute = $this->academicHelper->getInstitute();
                    //                //Check Image Exist or not
                    $imageAttachmentUpdate = EmployeeAttachment::where('emp_id', $enrollment)->where('doc_type', 'PROFILE_PHOTO')->first();
                    echo $imageAttachmentUpdate;

                    DB::beginTransaction();
                    if ($imageAttachmentUpdate) {
                        try {
                            $contentFind = Content::where('id', $imageAttachmentUpdate->doc_id)->first();
                            $contentFind->name = $imageName;
                            $contentFind->file_name = $imageName;
                            $contentFind->path = $destinationPath;
                            $contentFind->mime = $ext;
                            $content_update = $contentFind->save();
                            //
                            ////                        if($content_update)
                            ////                        {
                            ////                            $photoStore=new CadetPersonalPhoto;
                            ////                            $photoStore->image = $imageName;
                            ////                            $photoStore->date = date('Y-m-d');
                            ////                            $photoStore->cadet_no = $user;
                            ////                            $photoStore->student_id = $enrollment->std_id;
                            ////                            $photoStore->campus_id = $campus;
                            ////                            $photoStore->institute_id = $institute;
                            ////                            $photoStore->academics_year_id=$enrollment->academic_year;
                            ////                            $photoStore->section_id=$enrollment->section;
                            ////                            $photoStore->batch_id= $enrollment->batch;
                            ////                            $photoStorage=$photoStore->save();
                            ////                        }
                            DB::commit();
                        } catch (Exception $e) {
                            DB::rollback();
                            return redirect()->back($e->getMessage());
                        }
                        //
                        //
                        //
                    } else {
                        try {
                            $userDocument = new Content();
                            // storing user document
                            $userDocument->name = $imageName;
                            $userDocument->file_name = $imageName;
                            $userDocument->path = $destinationPath;
                            $userDocument->mime = $ext;
                            $insertDocument = $userDocument->save();
                            //
                            //
                            if ($insertDocument) {
                                $studentAttachment = new EmployeeAttachment();
                                // storing student attachment
                                $studentAttachment->emp_id = $enrollment;
                                $studentAttachment->doc_id = $userDocument->id;
                                $studentAttachment->doc_type = "PROFILE_PHOTO";
                                $studentAttachment->doc_status = 0;
                                // save student attachment profile
                                $attachmentUploaded = $studentAttachment->save();
                                DB::commit();
                            }
                            ////                        if($insertDocument)
                            ////                        {
                            ////                            $photoStore=new CadetPersonalPhoto;
                            ////                            $photoStore->image = $imageName;
                            ////                            $photoStore->date = date('Y-m-d');
                            ////                            $photoStore->cadet_no = $user;
                            ////                            $photoStore->student_id = $enrollment->std_id;
                            ////                            $photoStore->campus_id = $campus;
                            ////                            $photoStore->institute_id = $institute;
                            ////                            $photoStore->academics_year_id=$enrollment->academic_year;
                            ////                            $photoStore->section_id=$enrollment->section;
                            ////                            $photoStore->batch_id= $enrollment->batch;
                            ////                            $photoStorage=$photoStore->save();
                            ////                        }
                            //                        if($insertDocument){
                            //                            // If we reach here, then data is valid and working. Commit the queries!
                            //                            DB::commit();
                        }
                            //
                            //
                        catch (ValidationException $e) {
                            // Rollback and then redirect
                            // back to form with errors
                            DB::rollback();
                            return redirect()->back($e->getMessage());
                        }
                        //
                    }
                } else {
                    array_push($missingStdIds, $user_id);
                }
            }
            $imageName = "";
        }

        if (sizeof($missingStdIds) > 0) {
            $str = '';
            foreach ($missingStdIds as $missingId) {
                $str .= $missingId . ", ";
            }

            Session::flash('warning', 'No Employees found with these ids- ' . $str . '! All other photos are uploaded.');
            // receiving page action
            return redirect()->back();
        }

        // return
        Session::flash('success', 'Employee Image Uploaded !!!');
        // receiving page action
        return redirect()->back();
    }

    // manage teacher
    public function manageTeacher(Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        // campus and institute id
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where(['institute_id' => $instituteId,])->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id' => $instituteId, 'dept_type' => 0])->orderBy('name', 'ASC')->get();
        // return view
        return view('employee::pages.manage-teacher', compact('allDesignaitons', 'allDepartments', 'pageAccessData'));
    }

    // find teacher
    public function findTeacherList(Request $request)
    {
        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');

        // qry
        $allSearchInputs = array();

        // checking return type
        // status
        $allSearchInputs['status'] = 1;
        // input institute and campus id
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if ($category) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = $this->employeeInformation->where($allSearchInputs)->orderBy('status', 'DESC')->orderBy('sort_order', 'ASC')->get();
        // return view with variable
        return view('employee::pages.includes.teacher-list', compact('allEmployee', 'allSearchInputs'));
    }

    // search employee
    public function searchEmployeePayroll(Request $request)
    {
        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');
        $scaleId = $request->input('salaryScale');
        $amount = $request->input('amount');
        $bank_id = $request->input('bank_id');
        $branch_id = $request->input('branch_id');
        // return type
        $returnType = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // status
            $allSearchInputs['status'] = 1;
            // input institute and campus id
            $allSearchInputs['campus_id'] = $campusId;
            $allSearchInputs['institute_id'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        }

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if (!empty($category) || $category != null) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;
        // Check Scale ID
//        if($scaleId) $allSearchInputs['salary_scale'] = $scaleId;
//        // Check Bank ID
//        if($bank_id) $allSearchInputs['bank_details_id'] = $bank_id;
//        // Check Bank ID
//        if($branch_id) $allSearchInputs['bank_branch_details_id'] = $branch_id;

        $allEmployee = $this->employeeInformation
            ->where($allSearchInputs)->select('employee_informations.*')
            ->get();
//        $allEmployee = $this->employeeInformation->leftJoin('cadet_employee_salary_assign','employee_informations.id','cadet_employee_salary_assign.emp_id')
//            ->where($allSearchInputs)->select('employee_informations.*','cadet_employee_salary_assign.salary_scale','cadet_employee_salary_assign.salary_amount')
//            ->orderBy('status','DESC')->orderBy('sort_order', 'ASC')->get();
        $bankName = BankDetails::where(['campus_id' => $this->academicHelper->getCampus(), 'institute_id' => $this->academicHelper->getInstitute()])->get();
        $branchName = BankBranchDetails::all();
        $salaryScales = SalaryScale::with('gradeName')->get();
        $salaryAssign = SalaryAssign::all()->groupBy('emp_id');
        $salaryAssignData = SalaryAssign::all();
        // checking
        if ($returnType == "json") {
            // return with variables
            return $allEmployee;
        } else {
            // checking
            if ($allEmployee) {
                return view('employee::pages.includes.payroll-employee-list', compact('salaryAssignData', 'salaryAssign', 'branchName', 'bank_id', 'branch_id', 'bankName', 'amount', 'scaleId', 'salaryScales', 'allEmployee', 'allSearchInputs'));
            } else {
                Session::flash('warning', 'unable to perform the action');
                // return redirect
                return redirect()->back();
            }
        }

    }

    // search employee
    public function searchEmployeePayrollAssign(Request $request)
    {
        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');
        $scaleId = $request->input('salaryScale');
        $amount = $request->input('amount');
        $bank_id = $request->input('bank_id');
        $branch_id = $request->input('branch_id');
        $month_name = $request->input('month_name');
        $head_id = $request->input('head_id');
        // return type
        $returnType = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // status
            $allSearchInputs['status'] = 1;
            // input institute and campus id
            $allSearchInputs['campus_id'] = $campusId;
            $allSearchInputs['institute_id'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        }

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if (!empty($category) || $category != null) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;
        // Check Scale ID
        if ($scaleId) $allSearchInputs['salary_scale'] = $scaleId;
        // Check Bank ID
        if ($bank_id) $allSearchInputs['bank_details_id'] = $bank_id;
        // Check Bank ID
        if ($branch_id) $allSearchInputs['bank_branch_details_id'] = $branch_id;
//        return $allSearchInputs;

        $allEmployee = $this->employeeInformation->join('cadet_employee_salary_assign', 'employee_informations.id', 'cadet_employee_salary_assign.emp_id')
            ->where($allSearchInputs)->select('employee_informations.*', 'cadet_employee_salary_assign.salary_scale', 'cadet_employee_salary_assign.salary_amount')
            ->orderBy('status', 'DESC')->orderBy('sort_order', 'ASC')->get();
        $bankName = BankDetails::where(['campus_id' => $this->academicHelper->getCampus(), 'institute_id' => $this->academicHelper->getInstitute()])->get();
        $branchName = BankBranchDetails::all();
        $salaryScales = SalaryScale::with('gradeName')->get();
        $salaryAssign = SalaryAssign::all()->groupBy('emp_id');
        $salaryAssignData = SalaryAssign::all();
        $salaryDeductionHead = SalaryHead::where(['type' => 1, 'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()])->get();
        $salaryDeductionRecords = SalaryDeduct::all();
        // checking
        if ($returnType == "json") {
            // return with variables
            return $allEmployee;
        } else {
            // checking
            if ($allEmployee) {
                return view('payroll::pages.salaryDeduction.payroll-employee-assign-list', compact('salaryDeductionRecords', 'head_id', 'month_name', 'salaryDeductionHead', 'salaryAssignData', 'salaryAssign', 'branchName', 'bank_id', 'branch_id', 'bankName', 'amount', 'scaleId', 'salaryScales', 'allEmployee', 'allSearchInputs'));
            } else {
                Session::flash('warning', 'unable to perform the action');
                // return redirect
                return redirect()->back();
            }
        }

    }

    public function searchEmployeePayrollGenerate(Request $request)
    {

        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');
        $scaleId = $request->input('salaryScale');
        $bank_id = $request->input('bank_id');
        $branch_id = $request->input('branch_id');
        $month_name = $request->input('month_name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $year = $request->input('year');

        $date1 = date_create($request->input('end_date'));
        $date2 = date_create($request->input('start_date'));
        $diff = date_diff($date2, $date1);
        $salary_days = $diff->format("%a");
        // return type
        $returnType = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // status
            $allSearchInputs['status'] = 1;
            // input institute and campus id
            $allSearchInputs['campus_id'] = $campusId;
            $allSearchInputs['institute_id'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        }

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if (!empty($category) || $category != null) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;
        // Check Scale ID
        if ($scaleId) $allSearchInputs['salary_scale'] = $scaleId;
        // Check Bank ID
        if ($bank_id) $allSearchInputs['bank_details_id'] = $bank_id;
        // Check Bank ID
        if ($branch_id) $allSearchInputs['bank_branch_details_id'] = $branch_id;
        // Month id
//        if($month_name) $allSearchInputs['month_name'] = $month_name;
//        // Year
//        if($year) $allSearchInputs['year'] = $year;
//        return $allSearchInputs;

        $allEmployee = $this->employeeInformation->leftjoin('cadet_employee_salary_assign', 'employee_informations.id', 'cadet_employee_salary_assign.emp_id')
            ->where($allSearchInputs)->select('employee_informations.*', 'cadet_employee_salary_assign.salary_scale', 'cadet_employee_salary_assign.salary_amount')
            ->orderBy('status', 'DESC')->orderBy('sort_order', 'ASC')->get();
        $bankName = BankDetails::where(['campus_id' => $this->academicHelper->getCampus(), 'institute_id' => $this->academicHelper->getInstitute()])->get();
        $branchName = BankBranchDetails::all();
        $salaryScales = SalaryScale::with('gradeName')->get();
        $salaryAssign = SalaryAssign::all()->groupBy('emp_id');
        $salaryAssignData = SalaryAssign::all();
        $salaryDeductionHead = SalaryHead::where(['type' => 1, 'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()])->get();
        $salaryAdditionHead = SalaryHead::where(['type' => 0, 'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()])->get();
        $salaryDeductionRecords = SalaryDeduct::all()->groupBy('emp_id');
        $salaryStructures = salaryStructure::where(['institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()])->get()->groupBy('salary_scale_id');
        $salaryGeneratedList = SalaryGenerateList::all()->groupBy('emp_id');
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        $generateHistory = SalaryGenerateHistoryList::all();
        $processHistory = SalaryProcessHistoryList::all();
        // checking
        if ($returnType == "json") {
            // return with variables
            return $allEmployee;
        } else {
            // checking
            if ($allEmployee) {
                return view('payroll::pages.salaryGenerate.payroll-employee-generate-list', compact('processHistory', 'generateHistory', 'start_date', 'end_date', 'salary_days', 'month_list', 'salaryGeneratedList', 'salaryStructures', 'salaryAdditionHead', 'salaryDeductionRecords', 'month_name', 'salaryDeductionHead', 'salaryAssignData', 'salaryAssign', 'branchName', 'bank_id', 'branch_id', 'bankName', 'scaleId', 'salaryScales', 'allEmployee', 'allSearchInputs', 'year'));
            } else {
                Session::flash('warning', 'unable to perform the action');
                // return redirect
                return redirect()->back();
            }
        }

    }

    // search employee
    public function newSearchEmployee(Request $request){
        $statuses = $request->statuses;
        $allSearchInput = [];

        $institutes = $this->academicHelper->getInstitute();
      //  return $institutes;

        $category=null;
        if ($request->category){
            if($request->category=="one") {
                $category=null;
                $allSearchInput['category']=1;
            }else{
                $category=12;
            }

        }
      // return $allSearchInput;
        if ($request->job_duration) {

            $duration = $request->job_duration;

            $month = $duration[1] | 0;
            $year = $duration[0] | 0;
            $day = 365 * $year + $month * 30;

        }
        if ($request->previous_exp) {

            $duration = $request->previous_exp;

            $month = $duration[1] | 0;
            $year = $duration[0] | 0;
            $prev_exp = 365 * $year + $month * 30;
        }
        if ($prev_exp == 0) $prev_exp = null;

        if ($day == 0) $day = null;
        if ($request->contact_no) $phone = $request->contact_no; else $phone = null;
        if ($request->email) $email = $request->email; else $email = null;
        if ($request->emp_id) $employee_number = $request->emp_id; else $employee_number = null;
        if ($request->designation) ($designation = $request->designation); else $designation = null;
        if ($request->blood_group) ($allSearchInput['blood_group'] = $request->blood_group);
        if ($request->marital_status) ($allSearchInput['marital_status'] = $request->marital_status);
        if ($request->gender) ($allSearchInput['gender'] = $request->gender);
        if ($request->religion) ($allSearchInput['religion'] = $request->religion);
        if ($request->medical_category) $medical_category=$request->medical_category; else $medical_category=null;
        if ($request->central_position_serial) ($allSearchInput['central_position_serial'] = $request->central_position_serial);
        $allSearchInput['institute_id']=$institutes;
        //return $allSearchInput;

        if ($request->department) ($department = $request->department); else $department = null;
        if ($request->employee_name) ($name = $request->employee_name); else $name = null;
        if ($request->permanent_address)
            ($permanent_address = $request->permanent_address);
        else
            $permanent_address = null;
        ($request->present_address) ? ($present_address = $request->present_address) : ($present_address = null);

        //if($request->)

        $r_start = $request->dor_start;
        $r_end = $request->dor_end;
        if ($r_end && $r_start) {

            $reteirment_array['start'] = $r_start;
            $reteirment_array['end'] = $r_end;
        } else {

            $reteirment_array = null;
        }
        if ($request->bd_start && $request->bd_end) {
            $birth_array['start'] = $request->bd_start;
            $birth_array['end'] = $request->bd_end;
            //return $birth_array;
        } else {
            $birth_array = null;
        }
        if ($request->doj_start && $request->doj_end) {
            $join_array['start'] = $request->doj_start;
            $join_array['end'] = $request->doj_end;
        } else $join_array = null;

//Store the namewise search data
        if ($name) {
            $nameWise = DB::table('hr_manage_view')->where('Name', 'LIKE', '%' . $name . '%')->get()->pluck('hr_id')->toArray();

        } else $nameWise = null;
       // return $allSearchInput;

        $hrEmployees = EmployeeInformation::with(['permanentAdd','employeeManageView', 'previousExperience', 'presentAdd', 'currentStatusSingle'])
            ->when($designation, function ($query, $designation) {
                $query->whereIn('designation', $designation);
            })->when($category,function ($query,$category){
                $query->where('category','!=',1);
            })
            ->when($department, function ($query, $department) {
                $query->whereIn('department', $department);
            })->when($reteirment_array, function ($query, $reteirment_array) {
                $query->where('dor', '>', Carbon::parse($reteirment_array['start'])->format('Y-m-d'))->where('dor', '<',
                    Carbon::parse($reteirment_array['end']));
            })->when($birth_array, function ($query, $birth_array) {
                $query->where('dob', '>', Carbon::parse($birth_array['start'])->format('Y-m-d'))->where('dob', '<',
                    Carbon::parse($birth_array['end']));
            })->when($join_array, function ($query, $join_array) {
                $query->where('dor', '>', Carbon::parse($join_array['start'])->format('Y-m-d'))->where('dor', '<',
                    Carbon::parse($join_array['end']));

            })->when($phone, function ($query, $phone) {
                $query->where('phone', 'LIKE', '%' . $phone . '%')->orWhere('alt_mobile', 'LIKE', '%' . $phone . '%');

            })->when($email, function ($query, $email) {
                $query->where('email', 'LIKE', '%' . $email . '%')->orWhere('alt_mobile', 'LIKE', '%' . $email . '%');

            })->when($medical_category, function ($query, $medical_category) {
                $query->where('medical_category', 'LIKE', '%' . $medical_category . '%');

            })
            ->when($day, function ($query, $day) {
                $query->where('doj', '<', Carbon::now()->subDays($day));

            })->when($permanent_address, function ($query, $permanent_address) {
                $query->whereHas('presentAdd', function ($query_new) use ($permanent_address) {
                    $query_new->where('address', 'LIKE', '%' . $permanent_address . '%');

                });
            })->when($employee_number, function ($query, $employee_number) {

                $query->whereHas('employeeManageView',function ($qN) use ($employee_number){
                    $qN->where('employee_id','LIKE','%'.$employee_number.'%');
                });
            })
            ->when($present_address, function ($query, $present_address) {
                $query->whereHas('presentAdd', function ($query_new) use ($present_address) {
                    $query_new->where('address', 'LIKE', '%' . $present_address . '%');

                });
            })
            ->where($allSearchInput)
            ->get();
    //    return $hrEmployees;

        if ($statuses) {
            $filteredByStatus = [];
            foreach ($hrEmployees as $employee) {

                if ($employee->currentStatusSingle && sizeof($employee->currentStatusSingle) > 0) {
                    // return $employee;
                    // return gettype($employee->currentStatusSingle);
                    $stat = $employee->currentStatusSingle->first()->status_id;
                    //return $statuses;
                    if (in_array($stat, $statuses)) {
                        array_push($filteredByStatus, $employee->id);
                    }


                }
                //return $employee->currentStatusSingle;
            }

            $hrEmployees = $hrEmployees->whereIn('id', $filteredByStatus);
            //return $filteredByStatus;


        }
        if ($prev_exp) {
            $filteredByExp = [];
            foreach ($hrEmployees as $employee) {
                if ($employee->previousExperience && sizeof($employee->previousExperience) > 0) {
                    $totalExp = 0;
                    foreach ($employee->previousExperience as $exp) {

                        $endDate = Carbon::parse($exp->experience_to_date);
                        $start = Carbon::parse($exp->experience_from_date);


                        $exp = $start->diff($endDate)->days;
                        $totalExp += $exp;

                    }
                    //return $totalExp;
                    if ($prev_exp <= $totalExp) {
                        array_push($filteredByExp, $employee->id);
                    }
                }


            }
            $hrEmployees = $hrEmployees->whereIn('id', $filteredByExp);

        }
        if ($nameWise) {
            $hrEmployees= $hrEmployees->whereIn('id', $nameWise);
        }
        $empkey=$hrEmployees->pluck('user_id');
        $empkeySort=User::whereIn('id',$empkey)->get()->sortBy('username')->pluck('id');

        $allEmployee=$hrEmployees->keyBy('user_id');
        $pageAccessData = self::linkAccess($request, ['manualRoute' => "employee/manage"]);
        $allSearchInputs=$allSearchInput;
        $child_count=$request->input('child_count');
       // return $child_count;
        return view('employee::pages.includes.teacher-list', compact('empkeySort','child_count','allEmployee', 'pageAccessData', 'allSearchInputs'));

    }
    public function searchEmployee(Request $request)
    {
       // return $request->all();
        $pageAccessData = self::linkAccess($request, ['manualRoute' => "employee/manage"]);

        $instituteId = $request->input('institute');
        $campusId = $request->input('campus');
        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');
        // return type
        $returnType = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // status
            $allSearchInputs['status'] = 1;
            // input institute and campus id
            $allSearchInputs['campus_id'] = $campusId;
            $allSearchInputs['institute_id'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        }

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check

        if (!empty($category) || $category != null) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = $this->employeeInformation->where($allSearchInputs)->orderBy('status', 'DESC')->orderBy('sort_order', 'ASC')->get();
        // checking
        if ($returnType == "json") {
            // return with variables
            return $allEmployee;
        } else {
            // checking
            if ($allEmployee) {
                return view('employee::pages.includes.teacher-list', compact('allEmployee', 'pageAccessData', 'allSearchInputs'));
            } else {
                Session::flash('warning', 'unable to perform the action');
                // return redirect
                return redirect()->back();
            }
        }
    }


    // employee download excel file

    public function searchEmployeeDownload(Request $request)
    {

        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');

        // qry
        $allSearchInputs = array();

        // status
        $allSearchInputs['status'] = 1;
        // input institute and campus id
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if ($category) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = EmployeeInformation::where($allSearchInputs)->orderBy('status', 'DESC')->get();
        // checking
        // checking
        if ($allEmployee) {

            view()->share(compact('allEmployee'));
            //generate excel
            Excel::create('Employee List', function ($excel) {
                $excel->sheet('Employee List', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('employee::reports.employee_list');
                });
            })->download('xlsx');
        } else {
            Session::flash('warning', 'ubable to perform the action');
            // return redirect
            return redirect()->back();
        }
    }


    // create or update user profile
    public function manageUserProfile($userId, $userData)
    {
        // userId checking
        if ($userId > 0) {
            $userProfile = $this->user->findOrFail($userId)->update($userData);
        } else {
            $userProfile = $this->user->create($userData);
        }

        // userProfile checking
        if ($userProfile) {
            return $userProfile;
        } else {
            return false;
        }
    }

    // create or update employee profile
    public function manageEmployeeProfile($empId, $empData)
    {
        // $empId checking
        if ($empId > 0) {
            $employeeProfile = $this->employeeInformation->findOrFail($empId)->update($empId);
        } else {
            $employeeProfile = $this->employeeInformation->create($empData);
        }

        // employeeProfile checking
        if ($employeeProfile) {
            return $employeeProfile;
        } else {
            return false;
        }
    }

    // set employee role using user profile
    public function setEmpRole($roleName, $userProfile)
    {
        // roleProfile
        $employeeRoleProfile = $this->role->where('id', $roleName)->first();
        // assigning student role to this user
        return $userProfile->attachRole($employeeRoleProfile);
    }

    //check Employee Email Exists or not
    public function checkEmployeeEmail(Request $request)
    {
        $emails = $request->input('form_data');
        $emails = json_decode($emails);
        $result_same_sheet = array();
        $result = array();

        for ($i = 1; $i < count($emails); $i++) {

            $user = User::where('email', '=', $emails[$i])->get();
            if (empty($result_same_sheet[$emails[$i]])) {

                if ($user->count() > 0) {
                    $result[$i] = 0;
                } else {
                    $result_same_sheet[$emails[$i]] = 1;
                    $result[$i] = 1;
                }
            } else {
                $result[$i] = 0;
            }
        }
        return $result;
    }

    // create or update user profile
    public function updateWebPosition(Request $request)
    {
        // employee id
        $empId = $request->input('emp_id');
        // employee id
        $position = $request->input('sort_order');
        // checking
        if (!empty($empId) and $empId > 0 and !empty($position) and $position > 0) {
            // employee profile
            $employeeProfile = $this->employeeInformation->find($empId);
            // update web position
            $employeeProfile->sort_order = (int)$position;
            // checking
            if ($employeeProfile->save()) {
                // teacher list
                return 'success';
            } else {
                return 'failed';
            }
        } else {
            return 'failed';
        }
    }


    public function getAllEmployId()
    {
        //        return "test";
        $attendaceDevice = AttendanceDevice::where('institute_id', 13)->get();
        return $registerEmplyeeId = $attendaceDevice->pluck('registration_id');

        $emplyeeList = EmployeeInformation::wherenotin('id', $registerEmplyeeId)->where('institute_id', 13)->get();
        return $ids = $emplyeeList->pluck('id');
    }

    public function changeEmployeeStatusSave(Request $request)
    {
        DB::beginTransaction();
        try {
            $newAssign = new EmployeeStatusAssign();
            $newAssign->employee_id = $request->emp_id;
            $newAssign->status_id = $request->selected_status;
            $newAssign->effective_from = Carbon::make( $request->effective)->toDate();
            $newAssign->created_by = Auth::id();
            $newAssign->campus_id = $this->academicHelper->getCampus();
            $newAssign->institute_id = $this->academicHelper->getInstitute();
            $newAssign->save();
            DB::commit();
            //return ['status' => 'success', 'msg' => ' List found', 'html' => $newAssign];

        } catch (Exception $e) {
            return [
                'status'=>'error','msg'=>"Something went wrong ",'error'=>$e
            ];

        }
        $empID= $request->emp_id;
        $employeeProfile = EmployeeInformation::with('employeeStatus')->where('id', $empID)->first();
        $stdListView = view('employee::pages.includes.assign-history', compact('employeeProfile'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];

       // return ['status' => 'success', 'data' => $request->all()];
    }

    //change emplyee status
    public function changeEmployeeStatus($empID)
    {
        $allStatus = EmployeeStatus::all();
        $employeeProfile = EmployeeInformation::with('employeeStatus')->where('id', $empID)->first();

        return view('employee::pages.modals.employee-change-status', compact('allStatus', 'employeeProfile'));
        $employeeProfile = $this->employeeInformation->find($empID);

    }


    public function showChilds($id)
    {
        $employee = EmployeeInformation::findOrFail($id);

        return view('employee::pages.modals.employee-child-list', compact('employee'));
    }
}
