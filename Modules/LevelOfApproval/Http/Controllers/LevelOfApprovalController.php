<?php

namespace Modules\LevelOfApproval\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Models\Role;
use App\User;
use App\UserInfo;
use Carbon\Carbon;
use CreateInventoryVoucherApprovalLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsApprovalLog;
use Modules\Accounts\Entities\AccountsVoucherApprovalLogModel;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\LeaveApprovalLog;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\LevelOfApproval\Entities\ApprovalLayer;
use Modules\LevelOfApproval\Entities\ApprovalNotification;
use Modules\LevelOfApproval\Entities\LevelOfApproval;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentProfileView;

class LevelOfApprovalController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index()
    {
        $levelOfApprovals = LevelOfApproval::all();
        $approvalLayers = ApprovalLayer::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('level_of_approval_unique_name');

        return view('levelofapproval::index', compact('levelOfApprovals', 'approvalLayers'));
    }

    public function create()
    {
        return view('levelofapproval::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('levelofapproval::show');
    }

    public function edit($id)
    {
        $levelOfApproval = LevelOfApproval::with('approvalLayers')->findOrFail($id);
        $approvalLayers = ApprovalLayer::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'level_of_approval_unique_name' => $levelOfApproval->unique_name,
        ])->get();
        $roles = Role::with('roleUsers')->get();
        $userIds = UserInfo::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('user_id')->toArray();
        array_push($userIds, 1);
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        $approvalLayerRow = view('levelofapproval::snippet.approval-layer-row', compact('roles', 'users', 'levelOfApproval', 'approvalLayers'))->render();
        $userRole = Auth::user()->role();

        return view('levelofapproval::modal.edit-approval-layers', compact('levelOfApproval', 'approvalLayerRow', 'approvalLayers', 'userRole', 'roles', 'users'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $previousLayers = ApprovalLayer::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'level_of_approval_unique_name' => $request->unique_name
        ])->get()->keyBy('layer');

        DB::beginTransaction();
        try {
            if ($request->step) {
                foreach ($request->step as $step) {
                    if (isset($previousLayers[$step])) {
                        $previousLayers[$step]->update([
                            'role_id' => (isset($request->roleId[$step]))?$request->roleId[$step]:null,
                            'user_ids' => (isset($request->userIds[$step]))?json_encode($request->userIds[$step]):null,
                            'all_members' => (isset($request->allMandatory[$step]))?$request->allMandatory[$step]:null,
                            'po_value' => (isset($request->poValue[$step]))?$request->poValue[$step]:null,
                            'updated_by' => $user->id,
                        ]);
        
                        unset($previousLayers[$step]);
                    } else {
                        ApprovalLayer::create([
                            'level_of_approval_unique_name' => $request->unique_name,
                            'layer' => $step,
                            'role_id' => (isset($request->roleId[$step]))?$request->roleId[$step]:null,
                            'user_ids' => (isset($request->userIds[$step]))?json_encode($request->userIds[$step]):null,
                            'all_members' => (isset($request->allMandatory[$step]))?$request->allMandatory[$step]:null,
                            'po_value' => (isset($request->poValue[$step]))?$request->poValue[$step]:null,
                            'created_by' => $user->id,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }
    
            foreach ($previousLayers as $previousLayer) {
                $previousLayer->delete();
            }

            DB::commit();
            Session::flash('message','Successfully saved approval layers.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('message','Error saving approval layers.');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        //
    }

    public function getPersonsFromRole(Request $request)
    {
        if ($request->roleId) {
            $role = Role::findOrFail($request->roleId);
            if ($role->name == 'super-admin') {
                return $role->roleUsers;
            }
            $userIds = $role->roleUsers->pluck('id');
            $userIds = UserInfo::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('user_id', $userIds)->pluck('user_id')->toArray();
            return User::whereIn('id', $userIds)->get();
        } else {
            $userIds = UserInfo::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->pluck('user_id')->toArray();
            array_push($userIds, 1);
            return User::whereIn('id', $userIds)->get();
        }
    }

    public function myPendingNotificationIds()
    {
        $authUser = Auth::user();
        $notificationIds = [];
        $approvalLayers = ApprovalLayer::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('level_of_approval_unique_name');
        $pendingNotifications = ApprovalNotification::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'action_status' => 0,
        ])->get();
        $accountsVoucherApprovalLogs = AccountsVoucherApprovalLogModel::with('user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('voucher_type');
        $inventoryVoucherApprovalLogs = VoucherApprovalLogModel::with('user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('voucher_type');
        // $leaveApprovalLogs = LeaveApprovalLog::with('user')->where([
        //     'campus_id' => $this->academicHelper->getCampus(),
        //     'institute_id' => $this->academicHelper->getInstitute(),
        // ])->get()->groupBy('voucher_type');
        $academicsApprovalLogs = AcademicsApprovalLog::with('user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('menu_type');

        foreach ($pendingNotifications as $notification) {
            if ($notification->module_name == 'Accounts') {
                $voucherType = 0;
                if ($notification->unique_name == 'payment_voucher') {
                    $voucherType = 1;
                } elseif ($notification->unique_name == 'receive_voucher') {
                    $voucherType = 2;
                } elseif ($notification->unique_name == 'journal_voucher') {
                    $voucherType = 3;
                } elseif ($notification->unique_name == 'contra_voucher') {
                    $voucherType = 4;
                }
                $approvalLogs = isset($accountsVoucherApprovalLogs[$voucherType])?$accountsVoucherApprovalLogs[$voucherType]:null;
            } elseif ($notification->module_name == 'Inventory'){
                $approvalLogs = isset($inventoryVoucherApprovalLogs[$notification->unique_name])?$inventoryVoucherApprovalLogs[$notification->unique_name]:null;
            } elseif ($notification->module_name == 'Academics'){
                $approvalLogs = isset($academicsApprovalLogs[$notification->unique_name])?$academicsApprovalLogs[$notification->unique_name]:null;
            }

            if (isset($approvalLayers[$notification->unique_name])) {
                $approvalLayer = $approvalLayers[$notification->unique_name]->firstWhere('layer', $notification->approval_level);

                if ($approvalLayer) {
                    $meAlreadyApproved = null;
                    if ($approvalLogs) {
                        if ($notification->module_name == 'Accounts' || $notification->module_name == 'Inventory') {
                            $meAlreadyApproved = $approvalLogs
                                ->where('voucher_id', $notification->menu_id)
                                ->where('approval_layer', $notification->approval_level)
                                ->firstWhere('approval_id', $authUser->id);
                        }else{
                            $meAlreadyApproved = $approvalLogs
                                ->where('menu_id', $notification->menu_id)
                                ->where('approval_layer', $notification->approval_level)
                                ->firstWhere('user_id', $authUser->id);
                        }
                    }

                    if (!$meAlreadyApproved) {
                        if ($approvalLayer->user_ids) {
                            $userIds = json_decode($approvalLayer->user_ids);
                            if (in_array($authUser->id, $userIds)) {
                                $notificationIds[$notification->id] = true;
                            }
                        } elseif ($approvalLayer->role_id) {
                            if($authUser->role()->id == $approvalLayer->role_id){
                                $notificationIds[$notification->id] = true;
                            }
                        }
                    }
                }
            }
        }

        return $notificationIds;
    }

    public function alertNotificationTable(Request $request)
    {
        $authUserId = Auth::id();
        $allRoles = Role::get()->keyBy('id');
        // $allUserIds = UserInfo::where([
        //     'campus_id' => $this->academicHelper->getCampus(),
        //     'institute_id' => $this->academicHelper->getInstitute(),
        // ])->pluck('user_id')->toArray();
        $allUsers = User::get()->keyBy('id');

        $approvalLayers = ApprovalLayer::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('level_of_approval_unique_name');
        $notifications = ApprovalNotification::with(
            'accountsVoucher',
            'newRequisitionDetails.item',
            'purchaseRequisitionDetails.item',
            'CSDetails.item',
            'purchaseOrderDetails.item',
            'purchaseInvoiceDetails.item',
        )->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->latest()->paginate(20);
        $allNotificationsNum = ApprovalNotification::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->count();
        $pendingNotificationsNum = ApprovalNotification::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'action_status' => 0,
        ])->count();
        $myPendingNotificationIds = $this->myPendingNotificationIds();
        $approvedNotificationsNum = ApprovalNotification::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'action_status' => 1,
        ])->count();
        $accountsVoucherApprovalLogs = AccountsVoucherApprovalLogModel::with('user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('voucher_type');
        $inventoryVoucherApprovalLogs = VoucherApprovalLogModel::with('user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('voucher_type');
        // $leaveApprovalLogs = LeaveApprovalLog::with('user')->where([
        //     'campus_id' => $this->academicHelper->getCampus(),
        //     'institute_id' => $this->academicHelper->getInstitute(),
        // ])->get()->groupBy('voucher_type');
        $academicsApprovalLogs = AcademicsApprovalLog::with('user')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->groupBy('menu_type');
        return view('levelofapproval::snippet.notification-table', compact(
            'authUserId', 
            'allRoles',
            'allUsers',
            'notifications',
            'approvalLayers', 
            'accountsVoucherApprovalLogs',
            'inventoryVoucherApprovalLogs',
            'allNotificationsNum',
            'pendingNotificationsNum',
            'myPendingNotificationIds',
            'approvedNotificationsNum',
            // 'leaveApprovalLogs',
            'academicsApprovalLogs'
        ))->render();
    }
    
    public function alertNotificationPage(Request $request)
    {
        $authUserId = Auth::id();
        $page = $request->page;
        $notifications = ApprovalNotification::with(
            'accountsVoucher', 
            'newRequisitionDetails.item',
            'purchaseRequisitionDetails.item',
            'CSDetails.item',
            'purchaseOrderDetails.item',
            'purchaseInvoiceDetails.item',
            'leaveApplications',
            'examList.exam'
        )->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->latest()->paginate(20);
        $table = $this->alertNotificationTable($request);
        return view('levelofapproval::alert-notification', compact('table', 'notifications', 'page'));
    }
}
