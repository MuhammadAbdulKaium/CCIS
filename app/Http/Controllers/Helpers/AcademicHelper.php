<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;

use App\Subject;
use App\User;
use App\UserInfo;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\Assessments;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentInformation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Academics\Entities\BatchSemester;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Setting\Entities\State;
use Modules\Setting\Entities\City;
use Modules\Academics\Entities\ExamStatus;
use Modules\Academics\Entities\SubjectGroup;
use Auth;
use Carbon\Carbon;
use Modules\Academics\Entities\AcademicsApprovalLog;
use Modules\LevelOfApproval\Entities\ApprovalLayer;

class AcademicHelper extends Controller
{

    private $institute;
    private $batch;
    private $section;
    private $subject;
    private $classSubject;
    private $assessments;
    private $semester;
    private $academicsYear;
    private $academicsLevel;
    private $studentProfileView;
    private $employeeInformation;
    private $batchSemester;
    private $studentInformation;
    private $campus;
    private $additionalSubject;
    private $state;
    private $city;
    private $examStatus;
    private $subjectGroup;

    public function __construct(Institute $institute, Batch $batch, Section $section, Subject $subject, ClassSubject $classSubject, Assessments $assessments, Semester $semester, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel, StudentProfileView $studentProfileView, EmployeeInformation $employeeInformation, BatchSemester $batchSemester, StudentInformation $studentInformation, Campus $campus, AdditionalSubject $additionalSubject, State $state, City $city, ExamStatus $examStatus, SubjectGroup $subjectGroup)
    {
        $this->institute = $institute;
        $this->batch = $batch;
        $this->section = $section;
        $this->subject = $subject;
        $this->classSubject = $classSubject;
        $this->assessments = $assessments;
        $this->semester = $semester;
        $this->academicsYear = $academicsYear;
        $this->academicsLevel = $academicsLevel;
        $this->studentProfileView = $studentProfileView;
        $this->employeeInformation = $employeeInformation;
        $this->batchSemester = $batchSemester;
        $this->studentInformation = $studentInformation;
        $this->campus = $campus;
        $this->additionalSubject = $additionalSubject;
        $this->state = $state;
        $this->city = $city;
        $this->examStatus = $examStatus;
        $this->subjectGroup = $subjectGroup;
    }

    // find exam status
    public function findExamStatus($semester, $section, $batch, $level, $academicYear, $campus, $institute)
    {
        // find exam status
        return  $examStatus = $this->examStatus->where([
            'semester' => $semester, 'section' => $section, 'batch' => $batch, 'level' => $level, 'academic_year' => $academicYear, 'campus' => $campus, 'institute' => $institute,
        ])->first();
    }


    // get additional subject list
    public function getAdditionalSubjectStdList($subId, $section, $batch, $campus, $institute)
    {
        // response data
        $responseData = [];
        // find academic year
        $academicYear = $this->getAcademicYear();
        // find additional subject list
        $additionalSubjectList = $this->additionalSubject
            ->where(['section' => $section, 'batch' => $batch, 'campus' => $campus, 'institute' => $institute])->get(['std_id', 'sub_list']);
        // checking
        if ($additionalSubjectList->count() > 0) {
            // looping
            foreach ($additionalSubjectList as $addSubject) {
                // find subject list
                $subList = json_decode($addSubject->sub_list);
                // checking sublist
                if ($subList) {
                    // subject list looping
                    foreach ($subList as $subType => $stdSubList) {
                        // student subject list
                        $subjectArray = (array) json_decode($stdSubList);
                        // checking student subject list
                        if (in_array($subId, $subjectArray)) {
                            $responseData[$addSubject->std_id] = $subId;
                        }
                    }
                }
            }
        }
        // return response data
        return $responseData;
    }

    // check subject student list from class student list
    public function getClassSubjectStudentList($classSubject, $classSubjectStdList, $classStdList)
    {
        // response array list
        $responseData = array();
        // checking class subject type
        if ($classSubject->subject_type == 0 || $classSubject->subject_type == 1) {
            // return class student list
            return $classStdList;
        } else {
            // checking
            if (count($classSubjectStdList) > 0 and count($classStdList) > 0) {
                // class student list looping
                foreach ($classStdList as $student) {
                    // checking student in the subject student list
                    if (array_key_exists($student['id'], $classSubjectStdList)) {
                        $responseData[] = $student;
                    }
                }
            }
        }

        // return response data
        return $responseData;
    }

    // all academics years list
    public function getAllAcademicYears()
    {
        //        return $this->academicsYear->where(['status'=>0])->get();
        return $this->academicsYear->get();
    }

    // get institute profile
    public function getInstituteProfile()
    {
        return $this->institute->find($this->getInstitute());
    }

    // get institute academic year id
    public function getAcademicYear()
    {
        return session()->get('academic_year');
    }

    // get institute admission year id
    public function getAdmissionYear()
    {
        return AcademicsAdmissionYear::where(['status' => 1])->first()->id;
    }

    // find academic year profile
    public function getAcademicYearProfile()
    {
        return $this->academicsYear->find($this->getAcademicYear());
    }
    // get institute modules
    public function getAcademicSemester()
    {
        // all semester
        return $this->semester->where([
            'academic_year_id' => $this->getAcademicYear(),
            'status' => 1,
        ])->get();
    }

    // get institute id
    public function getInstitute()
    {
        return session()->get('institute');
    }

    // get institute campus id
    public function getCampus()
    {
        return session()->get('campus');
    }

    // get institute grading scale id
    public function getGradingScale()
    {
        return session()->get('grading_scale');
    }
    // get institute modules
    public function getInstituteModules()
    {
        return $this->getInstituteProfile()->instituteModules()->get();
    }

    // get all academic levels using this academic year id and status is_active
    public function getAllAcademicLevel()
    {
        return $this->academicsLevel->where([
            'is_active' => 1
        ])->get();
    }

    // institution academic detail information
    public function getAcademicInfo()
    {
        $campusId = $this->getCampus();
        $instituteId = $this->getInstitute();
        // qry maker
        $qry = ['campus' => $campusId, 'institute' => $instituteId];
        // institute total student list
        $studentList = $this->studentProfileView->where($qry)->get()->count();
        // institute total employee list
        $employeeList = $this->employeeInformation->where(['campus_id' => $campusId, 'institute_id' => $instituteId]);
        // batch / class list
        $batchList = $this->batch->get()->count();
        // section list
        $sectionList = $this->section->get()->count();
        // subject  list
        $subjectList = $this->subject->get()->count();

        // academic details
        return (object)[
            'batch' => $batchList,
            'section' => $sectionList,
            'subject' => $subjectList,
            'student' => $studentList,
            'teacher' => $employeeList->where(['category' => 1])->get()->count(),
            'staff' => $employeeList->where(['category' => 0])->get()->count(),
            'total_employee' => $employeeList->get()->count()
        ];
    }

    // find batch
    public function getBatchList()
    {
        $campusId = $this->getCampus();
        $instituteId = $this->getInstitute();
        // qry maker
        $qry = ['campus' => $campusId, 'institute' => $instituteId];
        $allBatch = $this->batch->get();

        // response array
        $batchList = array();
        // looping for adding division into the batch name
        foreach ($allBatch as $batch) {
            if ($batch->get_division()) {
                $batchList[$batch->id] = $batch->batch_name;
            } else {
                $batchList[$batch->id] = $batch->batch_name;
            }
        }

        return $batchList;
    }

    // find batch
    public function getBatch($batchId)
    {
        return $this->batch->find($batchId);
    }
    // find section
    public function getSection($sectionId)
    {
        return $this->section->find($sectionId);
    }
    // find subject
    public function getSubject($subjectId)
    {
        return $this->subject->find($subjectId);
    }
    // find subject
    public function getClassSubject($classSubjectId)
    {
        return $this->classSubject->find($classSubjectId);
    }
    // find subject
    public function getAssessment($assessmentId)
    {
        return $this->assessments->find($assessmentId);
    }
    // find semester
    public function getSemester($semesterId)
    {
        return $this->semester->find($semesterId);
    }
    // get all institute list
    public function getInstituteList()
    {
        return $this->institute->orderBy('institute_serial', 'ASC')->get();
    }

    // get all campus list
    public function getCampusList()
    {
        return $this->campus->orderBy('name', 'ASC')->get();
    }

    // find batch semester list
    public function getBatchSemesterList($academicYear, $academicLevel,  $academicBatch)
    {
        $semesterList = array();
        // semester list
        $batchSemesterList =  $this->batchSemester->where([
            'academic_year' => $academicYear,
            'academic_level' => $academicLevel,
            'batch' => $academicBatch
        ])->get();

        // looping for adding division into the batch name
        foreach ($batchSemesterList as $myBatchSemester) {
            $semester = $myBatchSemester->semester();
            //  checking semester profile
            if ($semester) {
                // semester list maker
                $semesterList[] = [
                    'id' => $semester->id,
                    'name' => $semester->name,
                    'start_date' => $semester->start_date,
                    'end_date' => $semester->end_date
                ];
            }
        }
        // return
        return $semesterList;
    }


    // find class section group subject list
    public function findClassSectionSubjectList($section, $batch, $campus, $institute)
    {
        // group subject array list
        $subjectArrayList = array();

        // find class section group subject list
        $subjectList = DB::table('class_subjects as cs')
            ->join('subject as s', 's.id', '=', 'cs.subject_id')
            ->select('s.id as s_id', 'cs.id as cs_id', 's.subject_name as s_name', 'cs.subject_code as s_code', 'cs.subject_type as s_type')
            ->where(['cs.section_id' => $section, 'cs.class_id' => $batch, 's.campus' => $campus, 's.institute' => $institute])
            ->orderBy('sorting_order', 'ASC')->get();

        // subject list looping
        foreach ($subjectList as $subject) {
            // add group and subject details
            $subjectArrayList[$subject->cs_id] = [
                's_id' => $subject->s_id,  'name' => $subject->s_name, 'code' => $subject->s_code, 'type' => $subject->s_type
            ];
        }

        // return group subject list
        return $subjectArrayList;
    }

    // find class section group subject list
    public function findClassSectionGroupSubjectList($section = null, $batch, $campus, $institute)
    {
        // group subject array list
        $groupSubjectArrayList = array();

        // find class section group subject list
        $subjectList = DB::table('class_subjects as cs')->join('subject as s', 's.id', '=', 'cs.subject_id')->join('subject_group as g', 'g.id', '=', 'cs.subject_group')
            ->select('g.id as g_id', 'g.name as g_name', 'cs.id as cs_id', 's.subject_name as s_name', 'cs.subject_code as s_code', 'cs.subject_type as s_type')
            ->where(['cs.class_id' => $batch, 'g.campus' => $campus, 'g.institute' => $institute]);

        if ($section != null) {
            $subjectList->where('cs.section_id', $section);
        }
        $subjectListRes = $subjectList->orderBy('sorting_order', 'ASC')->get();

        // subject list looping
        foreach ($subjectListRes as $subject) {
            // checking group id
            if (array_key_exists($subject->g_id, $groupSubjectArrayList)) {
                // add another subject details
                $groupSubjectArrayList[$subject->g_id]['subject'][$subject->cs_id] = $subject->s_name;
                $groupSubjectArrayList[$subject->g_id]['code'][] = $subject->s_code;
            } else {
                // add group and subject details
                $groupSubjectArrayList[$subject->g_id] = [
                    'name' => $subject->g_name, 'type' => $subject->s_type, 'subject' => [$subject->cs_id => $subject->s_name], 'code' => [$subject->s_code]
                ];
            }
        }

        // return group subject list
        return $groupSubjectArrayList;
    }


    // find class section group subject list
    public function findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute, $type = 'group')
    {
        // additional subject array list
        $additionalArrayList = array();

        // find class section group subject list
        $additionalSubject = DB::table('academic_additional_subjects')
            ->where(['batch' => $batch, 'campus' => $campus, 'institute' => $institute]);
        if ($section != null) {
            $additionalSubject->where('section', $section);
        }

        $additionalSubjectList =  $additionalSubject->distinct('std_id')
            ->get(['std_id', 'sub_list', 'group_list']);

        // additional subject looping
        foreach ($additionalSubjectList as $additional) {
            // student id
            $stdId = $additional->std_id;
            // subject and group list
            $subList = (array) json_decode($additional->sub_list);
            $groupList = (array) json_decode($additional->group_list);

            // checking type
            if ($type == 'group') {
                // checking student subject (e_1)
                if (array_key_exists('e_1', $groupList) and $groupList['e_1']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['e_1'];
                }
                // checking student subject (e_2)
                if (array_key_exists('e_2', $groupList) and $groupList['e_2']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['e_2'];
                }
                // checking student subject (e_3)
                if (array_key_exists('e_3', $groupList) and $groupList['e_3']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['e_3'];
                }
                // checking student subject (e_3)
                if (array_key_exists('opt', $groupList) and $groupList['opt']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['opt'];
                }
            } else {
                $pattern = array('/[^a-zA-Z0-9 -]/');
                // checking student subject (e_1)
                if (array_key_exists('e_1', $subList) and $subList['e_1']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['e_1']);
                }
                // checking student subject (e_2)
                if (array_key_exists('e_2', $subList) and $subList['e_2']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['e_2']);
                }
                // checking student subject (e_3)
                if (array_key_exists('e_3', $subList) and $subList['e_3']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['e_3']);
                }
                // checking student subject (e_3)
                if (array_key_exists('opt', $subList) and $subList['opt']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['opt']);
                }
            }
        }

        // return additional subject list
        return $additionalArrayList;
    }


    // find academic Year profile using institute and campus id
    public function findInstituteAcademicYear($instituteId, $campusId)
    {
        return $this->academicsYear->where(['status' => 1])->first();
    }


    // find academic year profile
    public function findYear($yearId)
    {
        return $this->academicsYear->find($yearId);
    }
    // find academic level profile
    public function findLevel($levelId)
    {
        return $this->academicsLevel->find($levelId);
    }
    // find academic batch profile
    public function findBatch($batchId)
    {
        return $this->batch->find($batchId);
    }
    // find academic section profile
    public function findSection($sectionId)
    {
        return $this->section->find($sectionId);
    }

    // find institute profile
    public function findInstitute($instituteId)
    {
        return $this->institute->find($instituteId);
    }

    // find campus profile
    public function findCampus($campusId)
    {
        return $this->campus->find($campusId);
    }

    // find campus profile with institute id
    public function findCampusWithInstId($campusId, $instId)
    {
        return $this->campus->where(['id' => $campusId, 'institute_id' => $instId])->first();
    }

    ////////////// std information ////////
    // find academic section profile
    public function findStudent($stdId)
    {
        return $this->studentInformation->find($stdId);
    }

    // find state list
    public function stateList()
    {
        /// country id
        $countryId = 1;
        // all state list
        return $this->state->where(['country_id' => $countryId])->get(['id', 'name']);
    }

    // find state list
    public function getCityList($stateId)
    {
        // all city list
        return $this->city->where(['state_id' => $stateId])->get(['id', 'name']);
    }


    public function stdIdsHasTheSub($batchId, $sectionId, $subjectId)
    {
        $stdIds = [];
        if (is_array($batchId)) {
            $stdSubjects = AdditionalSubject::whereIn('batch', $batchId)->get();
        } else {
            $stdSubjects = AdditionalSubject::where('batch', $batchId)->get();
        }

        if ($sectionId) {
            $stdSubjects = $stdSubjects->where('section', $sectionId);
        }

        foreach ($stdSubjects as $key1 => $stdSubject) {
            $subList = json_decode($stdSubject->sub_list, 1);

            foreach ($subList as $key2 => $subIds) {
                if (strpos($subIds, (string)$subjectId)) {
                    $stdIds[$stdSubject->std_id] = $stdSubject->std_id;
                }
            }
        }

        return $stdIds;
    }

    public function alreadyApproved($menu, $menu_type, $auth_user_id){
        $menuApprovalLog = AcademicsApprovalLog::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'menu_id' => $menu->id,
            'menu_type' => $menu_type,
            'user_id' => $auth_user_id,
            'approval_layer' => $menu->step,
        ])->first();

        if ($menuApprovalLog) {
            if($menuApprovalLog->action_status == 0){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getApprovalInfo($approval_type, $approvalData){
        $auth_user = Auth::user();
        $allApprovalLayers = ApprovalLayer::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'level_of_approval_unique_name' => $approval_type,
        ]);
        $approvalLayer = $allApprovalLayers->get()->firstWhere('layer', $approvalData->step);
        $approval_access=false;
        $last_step = $allApprovalLayers->max('layer');
        
        if ($approvalLayer) {
            if ($approvalLayer->user_ids) {
                $userIds = json_decode($approvalLayer->user_ids);
                if (in_array($auth_user->id, $userIds)) {
                    if (!$this->alreadyApproved($approvalData, $approval_type, $auth_user->id)) {
                        $approval_access = true;
                    }
                }
            } else if ($approvalLayer->role_id){
                if ($auth_user->role()->id == $approvalLayer->role_id) {
                    if (!$this->alreadyApproved($approvalData, $approval_type, $auth_user->id)) {
                        $approval_access = true;
                    }
                }
            } else {
                $approval_access = false;
            }
        } else {
            $approval_access = false;
        }
        
        return [
            'approval_access'=>$approval_access,
            'last_step'=>$last_step
        ];
    }

    public function allUserApproved($menu_type, $menu, $userIds){
        $voucherApprovalLogs = AcademicsApprovalLog::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'menu_id' => $menu->id,
            'menu_type' => $menu_type,
            'approval_layer' => $menu->step,
        ])->get()->keyBy('user_id');

        foreach ($userIds as $userId) {
            if (isset($voucherApprovalLogs[$userId])) {
                if ($voucherApprovalLogs[$userId]->action_status != 1) {
                    return false;
                }
            } else {
                return false;
            }
        }

        if (sizeof($userIds)>0) {
            return true;
        } else {
            return false;
        }
    }

    public function anyUserApproved($menu_type, $menu, $userIds){
        $voucherApprovalLogs = AcademicsApprovalLog::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'menu_id' => $menu->id,
            'menu_type' => $menu_type,
            'approval_layer' => $menu->step,
        ])->get()->keyBy('user_id');

        foreach ($userIds as $userId) {
            if (isset($voucherApprovalLogs[$userId])) {
                if ($voucherApprovalLogs[$userId]->action_status == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    public function approvalLayerPassed($approval_type, $approvalData, $ignCurUser){
        $auth_user = Auth::user();
        $approvalLayer = ApprovalLayer::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'level_of_approval_unique_name' => $approval_type,
            'layer' => $approvalData->step
        ])->first();

        if($approvalLayer->user_ids){
            $userIds = json_decode($approvalLayer->user_ids);
        }else if($approvalLayer->role_id){
            $userIds = Role::findOrFail($approvalLayer->role_id)->roleUsers->pluck('id');
        }else{
            $userIds = [];
        }

        if ($approvalLayer->all_members == 'yes') {
            if ($ignCurUser && (($key = array_search($auth_user->id, $userIds)) !== false)) {
                unset($userIds[$key]);
                if (sizeof($userIds)<1) {
                    return true;
                }
            }
            return $this->allUserApproved($approval_type, $approvalData, $userIds);
        } else {
            if ($ignCurUser) {
                return true;
            }
            return $this->anyUserApproved($approval_type, $approvalData, $userIds);
        }

        return false;
    }

    public function getUserIdsFromApprovalLayer($approval_type, $approvalLevel)
    {
        $approvalLayer = ApprovalLayer::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'level_of_approval_unique_name' => $approval_type,
            'layer' => $approvalLevel
        ])->first();
        
        if ($approvalLayer) {
            if($approvalLayer->user_ids){
                $userIds = json_decode($approvalLayer->user_ids);
            }else if($approvalLayer->role_id){
                $userIds = Role::findOrFail($approvalLayer->role_id)->roleUsers->pluck('id');
            }else{
                $userIds = [];
            }
        } else {
            $userIds = [];
        }
        return $userIds;
    }

    public function generateApprovalHistoryInfo($approval_type, $approvalData){
        $auth_user_id = Auth::user()->id;
        $allApprovalLayers = ApprovalLayer::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'level_of_approval_unique_name' => $approval_type,
        ])->orderBy('layer', 'asc')->get()->keyBy('layer');
        $approvalLogs = AcademicsApprovalLog::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
            'menu_id' => $approvalData->id,
            'menu_type' => $approval_type
        ])->get()->groupBy('approval_layer');
        
        $approvalInfo = [];
        foreach ($allApprovalLayers as $key => $approvalLayer) {
            $approvedUsersInfo = [];
            if (isset($approvalLogs[$key])) {
                foreach ($approvalLogs[$key] as $approvalLog) {
                    $approvedUsersInfo[$approvalLog->approval_id] = [
                        'user_id' => $approvalLog->user_id,
                        'approved_at' => $approvalLog->created_at,
                    ];
                }
            }
            
            if ($key == $approvalData->step) {
                $approvedUsersInfo[$auth_user_id] = [
                    'user_id' => $auth_user_id,
                    'approved_at' => Carbon::now(),
                ];
            }
            
            $approvalInfo[$key] = [
                'step' => $key,
                'allMembers' => $approvalLayer->all_members,
                'role_id' => $approvalLayer->role_id,
                'user_ids' => ($approvalLayer->user_ids)?json_decode($approvalLayer->user_ids, 1):null,
                'users_approved' => $approvedUsersInfo
            ];
        }

        return $approvalInfo;
    }

    public function getAllUsers()
    {
        $allUserIds = UserInfo::where([
            'campus_id' => $this->getCampus(),
            'institute_id' => $this->getInstitute(),
        ])->pluck('user_id')->toArray();
        return User::whereIn('id', $allUserIds)->orWhere('username', 'superadmin')->get()->keyBy('id');
    }
}
