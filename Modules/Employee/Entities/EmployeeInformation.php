<?php

namespace Modules\Employee\Entities;

use App\Address;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use Wildside\Userstamps\Userstamps;

class  EmployeeInformation extends Model
{

    use Userstamps;
    use SoftDeletes;


    // Table name
    protected $table = 'employee_informations';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'employee_no',
        'position_serial',
        'alias',
        'gender',
        'dob',
        'doj',
        'dor',
        'department',
        'designation',
        'category',
        'email',
        'phone',
        'blood_group',
        'marital_status',
        'nationality',
        'experience_year',
        'experience_month',
        'campus_id',
        'institute_id',
        'sort_order',
        'alt_mobile',
        'birth_place',
        'religion',
        //dev9 New two column add
        'central_position_serial','medical_category'
    ];
    public function employeeStatus(){
        return $this->hasMany('Modules\Employee\Entities\EmployeeStatusAssign','employee_id','id');
    }
    public function currentStatus(){
      $statuses=  $this->hasMany('Modules\Employee\Entities\EmployeeStatusAssign','employee_id','id');
      if($statuses){
          return $statuses ->whereDate('effective_from','<=', Carbon::today())

              ->orderBy('effective_from','desc')->orderBy('created_at','desc')->first();
      }

    }
    public function previousExperience(){
        return $this->hasMany(EmployeeDocument::class,'employee_id','id')->where('document_type',2);

    }
    public function currentStatusSingle(){
        $statuses=  $this->hasMany('Modules\Employee\Entities\EmployeeStatusAssign','employee_id','id');
        if($statuses){
            return $statuses ->whereDate('effective_from','<=', Carbon::today())

                ->orderBy('effective_from','desc')->orderBy('created_at','desc');
        }

    }
    public function getEmployeAddress(){
        return $this->hasMany('App\Address','user_id','user_id');
    }
    //returs the user information from the user db table
    public function user()
    {
        // getting user info
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }
    public function permanentAdd(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class,'user_id','user_id')->where('type','=',"EMPLOYEE_PERMANENT_ADDRESS");
    }
    public function presentAdd(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class,'user_id','user_id')->where('type','=',"EMPLOYEE_PRESENT_ADDRESS");
    }
    public function  employeeManageView(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HrManageView::class,'hr_id','id');
    }

    public function singleUser()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    // return signatory information
    public function signatoryConfig(){
        return $this->belongsTo('Modules\Accounts\Entities\SignatoryConfig','id','empolyee_id');
    }

    // employee nationality
    public function nationality()
    {
        return $this->belongsTo('Modules\Setting\Entities\Country', 'nationality', 'id')->first();
    }

    // returs single document of the user with attachment type
    public function singelAttachment($type)
    {
        // getting student attatchment from student attachment db table
        $attachment = $this->hasOne('Modules\Employee\Entities\EmployeeAttachment', 'emp_id', 'id')
            ->where('doc_type', $type)->first();
        // checking
        if ($attachment) {
            // return student attachment
            return $attachment;
        } else {
            // return false
            return false;
        }
    }

    // returs all Attachment of the student
    public function allAttachment()
    {
        // getting student attatchment from student attachment db table
        $attachment = $this->hasMany('Modules\Employee\Entities\EmployeeAttachment', 'emp_id', 'id')->get();
        // checking
        if ($attachment) {
            // return student attachment
            return $attachment;
        } else {
            // return false
            return false;
        }
    }

    // returs all guardians of the student
    public function allGuardian()
    {
        // getting student attatchment from student attachment db table
        $allGuardian = $this->hasMany('Modules\Employee\Entities\EmployeeGuardian', 'emp_id', 'id')->get();
        // checking
        if ($allGuardian) {
            // return student attachment
            return $allGuardian;
        } else {
            // return false
            return false;
        }
    }

    public function myGuardians()
    {
        return $this->hasMany('Modules\Student\Entities\StudentParent', 'emp_id', 'id')->orderBy('id', 'desc')->get();
    }
    public function childCount(){
        $guardians= $this->hasMany('Modules\Student\Entities\StudentParent', 'emp_id', 'id')->orderBy('id', 'desc')
            ->get();
        $count=0;
        foreach ($guardians as $guardian){
            if ($guardian->singleGuardian && $guardian->singleGuardian->type==7 ){
                $count++;
            }elseif ($guardian->singleGuardian && $guardian->singleGuardian->type==8 ){
                $count++;
            }
        }
        return $count;

    }


    public function singleDepartment()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department', 'id');
    }

    public function singleDesignation()
    {
        return $this->belongsTo(EmployeeDesignation::class, 'designation', 'id');
    }

    // returs employee designaiton
    public function designation()
    {
        $designation = $this->belongsTo('Modules\Employee\Entities\EmployeeDesignation', 'designation', 'id')->first();
        // checking
        if ($designation) {
            // return designaiton
            return $designation;
        } else {
            // return false
            return false;
        }
    }
    
    // returs employee department
    public function department()
    {
        $department = $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'department', 'id')->first();
        // checking
        if ($department) {
            // return department
            return $department;
        } else {
            // return false
            return false;
        }
    }

    // return designatio list
    public function designations()
    {
        return $this->hasMany('Modules\Employee\Entities\EmployeeDesignation', 'dept_id', 'department')->get();
    }

    // employee gender
    public function gender($type)
    {
        $thisInstitute = session()->get('institute');
        $thisCampus = session()->get('campus');

        // total employee
        $totalEmployee = self::where([
            'status' => 1,
            'campus_id' => $thisCampus,
            'institute_id' => $thisInstitute
        ])->count();
        // checking
        if ($type == 'all') {
            // return total count
            return $totalEmployee;
        } else {
            // count a single type employee
            $genderEmployee = self::where([
                'status' => 1,
                'gender' => $type,
                'campus_id' => $thisCampus,
                'institute_id' => $thisInstitute
            ])->get()->count();
            // calculate and return the percentage
            return $percentage = ($genderEmployee / $totalEmployee) * 100;
        }
    }

    // return employee leave allocation details
    public function leaveAllocation()
    {
        return $this->hasOne('Modules\Employee\Entities\EmployeeLeaveEntitlement', 'employee', 'id')->first();
    }

    // return employee leave allocation details
    public function leaveStructure()
    {
        return $this->hasOne('Modules\Employee\Entities\EmployeeInformation', 'employee', 'id');
    }


    /**
     * @param $empId
     * @return bool|string
     */
    public static function empName($empId)
    {
        $value = self::where('id', $empId)->get();
        // checking
        if (count($value) > 0) {
            // return Name
            return $value[0]->title . ' ' . $value[0]->first_name . ' ' . $value[0]->middle_name . ' ' . $value[0]->last_name;
        } else {
            // return false
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public static function empDept($id)
    {
        $value = self::where('id', $id)->get();
        if (count($value) > 0) {
            // return Department Name
            return $value[0]->department()->name;
        } else {
            // return false
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public static function empDesig($id)
    {
        $value = self::where('id', $id)->get();
        if (count($value) > 0) {
            // return Department Name
            return $value[0]->designation()->name;
        } else {
            // return false
            return false;
        }
    }

    public function totalLeaveConsumed()
    {
        return $this->hasMany('Modules\Employee\Entities\EmployeeLeaveHistory', 'employee', 'id')->get();
    }


    public function oneDayAttedanceInfo($month, $date)
    {
        // getting student attatchment from student attachment db table
        $result = $this->hasOne('Modules\Employee\Entities\EmployeeAttendance', 'emp_id', 'id')
            ->whereMonth('employee_attendance.created_at', $month)
            ->whereDay('employee_attendance.created_at', $date)
            ->first();
        // checking
        if ($result) {
            // return student attachment
            return $result;
        } else {
            // return false
            return false;
        }
    }

    public function  getEmplyeeIdByUserId($userID)
    {
        $value = self::where('user_id', $userID)->first();
        if ($value) {
            return $value->id;
        } else {
            return false;
        }
    }


    // returs employee attendance Setting
    public function attendanceSetting()
    {
        $attendanceProfile = $this->belongsTo('Modules\Employee\Entities\EmployeeAttendanceSetting', 'emp_id', 'emp_id')->first();
        // checking
        if ($attendanceProfile) {
            // return designaiton
            return $attendanceProfile;
        } else {
            // return false
            return false;
        }
    }


    public function physcialRoomsClub($ids)
    {
        return $this->belongsToMany('Modules\Academics\Entities\PhysicalRoom', 'employee_room', 'employee_id', 'room_id')->with('activities')->whereIn('category_id', $ids)->get();
    }
    public function event($id)
    {
        return $this->belongsTo('Modules\Event\Entities\Event', 'employee_id', 'id');
    }


    public function presentAddress()
    {
        $userId = $this->user()->id;

        $address = Address::where([
            'user_id' => $userId,
            'type' => 'STUDENT_PRESENT_ADDRESS'
        ])->first();

        if ($address) {
            return $address->address;
        } else {
            return null;
        }
    }

    public function permanentAddress()
    {
        $userId = $this->user()->id;

        $address = Address::where([
            'user_id' => $userId,
            'type' => 'STUDENT_PERMANENT_ADDRESS'
        ])->first();

        if ($address) {
            return $address->address;
        } else {
            return null;
        }
    }
    public function getSingleInstitute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
    public function employeeAttachment(){
        return $this->belongsTo(EmployeeAttachment::class,'emp_id','id');
    }

    public function qualifications(){
        return $this->hasMany(EmployeeDocument::class,'employee_id','id')
            ->where('document_type',1);
    }
    public function  promotions(){
        return $this->hasMany(EmployeePromotion::class,'employee_id','id')->where('status','approved');
    }
    public function disciplines(){
        return $this->hasMany(EmployeeDiscipline::class,'employee_id','id');
    }
    public function trainings(){
        return  $this->hasMany(EmployeeTraining::class,'employee_id','id');
    }
    public function transfers(){
        return $this->hasMany(EmployeeTransferHistory::class,'employee_id','id');
    }

}
