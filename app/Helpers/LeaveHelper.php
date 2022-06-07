<?php
namespace App\Helpers;

use App\Models\Role;
use DB;
use Auth;
use Modules\Employee\Entities\LeaveApprovalLog;
use Modules\LevelOfApproval\Entities\ApprovalLayer;

trait LeaveHelper {
    public static function getInstitute(){
        return session()->get('institute');
    }
    public static function getCampus(){
        return session()->get('campus');
    }

    public static function alreadyApproved($menu, $menu_type, $auth_user_id){
        $menuApprovalLog = LeaveApprovalLog::where([
            'campus_id' => self::getCampus(),
            'institute_id' => self::getInstitute(),
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

    public static function getApprovalInfo($approval_type, $approvalData){
        $auth_user = Auth::user();
        $allApprovalLayers = ApprovalLayer::where([
            'campus_id' => self::getCampus(),
            'institute_id' => self::getInstitute(),
            'level_of_approval_unique_name' => $approval_type,
        ]);
        $approvalLayer = $allApprovalLayers->get()->firstWhere('layer', $approvalData->step);
        $approval_access=false;
        $last_step = $allApprovalLayers->max('layer');

        if ($approvalLayer) {
            if ($approvalLayer->user_ids) {
                $userIds = json_decode($approvalLayer->user_ids);
                if (in_array($auth_user->id, $userIds)) {
                    if (!self::alreadyApproved($approvalData, $approval_type, $auth_user->id)) {
                        $approval_access = true;
                    }
                }
            } else if ($approvalLayer->role_id){
                if ($auth_user->role()->id == $approvalLayer->role_id) {
                    if (!self::alreadyApproved($approvalData, $approval_type, $auth_user->id)) {
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

    public static function allUserApproved($menu_type, $menu, $userIds){
        $voucherApprovalLogs = LeaveApprovalLog::where([
            'campus_id' => self::getCampus(),
            'institute_id' => self::getInstitute(),
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

    public static function anyUserApproved($menu_type, $menu, $userIds){
        $voucherApprovalLogs = LeaveApprovalLog::where([
            'campus_id' => self::getCampus(),
            'institute_id' => self::getInstitute(),
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

    public static function approvalLayerPassed($approval_type, $approvalData, $ignCurUser){
        $auth_user = Auth::user();
        $approvalLayer = ApprovalLayer::where([
            'campus_id' => self::getCampus(),
            'institute_id' => self::getInstitute(),
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
            return self::allUserApproved($approval_type, $approvalData, $userIds);
        } else {
            if ($ignCurUser) {
                return true;
            }
            return self::anyUserApproved($approval_type, $approvalData, $userIds);
        }

        return false;
    }

    public static function getUserIdsFromApprovalLayer($approval_type, $approvalLevel)
    {
        $approvalLayer = ApprovalLayer::where([
            'campus_id' => self::getCampus(),
            'institute_id' => self::getInstitute(),
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
}