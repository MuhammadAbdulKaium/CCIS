<div class="box-header with-border">
    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-bell"></i> 
        Alert & Notification 
        <span class="notification-num-label">All: {{ $allNotificationsNum }}</span>
        <span class="text-warning notification-num-label">Pending: {{ $pendingNotificationsNum }}</span>
        <span class="text-danger notification-num-label">My Pending: {{ sizeof($myPendingNotificationIds) }}</span>
        <span class="text-success notification-num-label">Approved: {{ $approvedNotificationsNum }}</span>
    </h3>
</div>
<div class="box-body table-responsive">
    <table class="table table-bordered" id="level-of-approval-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Menu/ID</th>
                <th>Voucher NO/Remarks</th>
                <th>Approval Status</th>
                <th>Approval Info</th>
                <th>Time</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
                <tr class="@if(isset($myPendingNotificationIds[$notification->id])){{ 'bg-danger' }}@elseif ($notification->action_status==0){{ 'bg-warning' }}@endif">
                    <td>{{ $loop->index+1 }}</td>
                    <td><a href="{{ url($notification->menu_link) }}" target="_blank">{{ $notification->menu_name }}</a></td>
                    <td>
                        {{-- For Account Module Notification --}}
                        @if ($notification->module_name == 'Accounts' && $notification->accountsVoucher)
                            {{ $notification->accountsVoucher->voucher_no }}
                        {{-- For Inventory Module Notification --}}
                        @elseif ($notification->module_name == 'Inventory')
                            @if ($notification->unique_name == 'new_requisition')
                                @if ($notification->newRequisitionDetails)    
                                    @if ($notification->newRequisitionDetails->item)
                                        {{ $notification->newRequisitionDetails->item->product_name }}
                                    @endif
                                @endif
                            @elseif ($notification->unique_name == 'purchase_requisition')
                                @if ($notification->purchaseRequisitionDetails)    
                                    @if ($notification->purchaseRequisitionDetails->item)
                                        {{ $notification->purchaseRequisitionDetails->item->product_name }}
                                    @endif
                                @endif
                            @elseif ($notification->unique_name == 'cs')
                                @if ($notification->CSDetails)    
                                    @if ($notification->CSDetails->item)
                                        {{ $notification->CSDetails->item->product_name }}
                                    @endif
                                @endif
                            @elseif ($notification->unique_name == 'purchase_order')
                                @if ($notification->purchaseOrderDetails)    
                                    @if ($notification->purchaseOrderDetails->item)
                                        {{ $notification->purchaseOrderDetails->item->product_name }}
                                    @endif
                                @endif
                            @elseif ($notification->unique_name == 'purchase_invoice')
                                @if ($notification->purchaseInvoiceDetails)    
                                    @if ($notification->purchaseInvoiceDetails->item)
                                        {{ $notification->purchaseInvoiceDetails->item->product_name }}
                                    @endif
                                @endif
                            @elseif ($notification->unique_name == 'exam_result')
                                @if ($notification->examList)    
                                    @if ($notification->examList->exam)
                                        {{ $notification->examList->exam->exam_name }}
                                    @endif
                                @endif
                            @endif
                        {{-- For Academics Module Notification --}}
                        @elseif ($notification->module_name == 'Academics')
                            @if ($notification->unique_name == 'exam_result')
                                @if ($notification->examList)    
                                    @if ($notification->examList->exam)
                                        {{ $notification->examList->exam->exam_name }}
                                    @endif
                                @endif
                            @endif
                        {{-- For Employee Module Notification --}}
                        @elseif ($notification->module_name == 'Employee')
                            @if ($notification->leaveApplications)
                                @if ($notification->leaveApplications->user)
                                    {{ $notification->leaveApplications->user->name }}
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($notification->action_status == 0)
                            Pending
                        @elseif ($notification->action_status == 1)
                            Approved
                        @elseif ($notification->action_status == 2)
                            Partially Approved
                        @elseif ($notification->action_status == 3)
                            Rejected
                        @endif
                    </td>
                    <td>
                        @if ($notification->approval_info)
                            @php
                                $approvalInfo = json_decode($notification->approval_info);
                            @endphp
                            @foreach ($approvalInfo as $approvalData)
                                @php
                                    $userApproved = [];
                                    $comma = false;
                                @endphp
                                <span>
                                    <span>Step {{ $approvalData->step }}: </span>
                                    <span class="text-primary">@if ($approvalData->allMembers == 'yes') -All- @else -Any- @endif</span>
                                    @if ($approvalData->role_id && !$approvalData->user_ids)
                                        @isset($allRoles[$approvalData->role_id])    
                                            (<b>Role: </b>{{ $allRoles[$approvalData->role_id]->display_name }})
                                        @endisset
                                    @endif

                                    @foreach ($approvalData->users_approved as $approvalLog)
                                        @if ($comma), @endif
                                        <b class="@if ($approvalLog->user_id==$authUserId)text-success @endif">
                                            {{ $allUsers[$approvalLog->user_id]->name }}
                                            ({{ Carbon\Carbon::parse($approvalLog->approved_at)->diffForHumans() }})
                                        </b>
                                        @php
                                            $comma = true;
                                            $userApproved[$approvalLog->user_id] = true;
                                        @endphp
                                    @endforeach

                                    @if ($approvalData->user_ids)
                                        @foreach ($approvalData->user_ids as $userId)
                                            @if (!isset($userApproved[$userId]) && isset($allUsers[$userId]))
                                                <span class="@if ($userId==$authUserId)font-weight-bold text-danger @endif">
                                                    @if ($comma), @endif{{ $allUsers[$userId]->name }}
                                                </span>
                                                @php
                                                    $comma = true;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                </span><br>
                            @endforeach
                        @else
                            @isset($approvalLayers[$notification->unique_name])    
                                @foreach ($approvalLayers[$notification->unique_name] as $step)
                                    @php
                                        $approvalLogs = [];
                                        $stepPassed = false; 
                                        $roleName = null;
                                        $allUserIds = [];
                                        if ($step->role_id) {
                                            if (isset($allRoles[$step->role_id])) {
                                                $roleName = $allRoles[$step->role_id]->display_name;
                                            }
                                        }
                                        if($step->user_ids){
                                            $allUserIds = json_decode($step->user_ids);
                                        }
                                        
                                        if ($notification->module_name == 'Accounts' && $notification->accountsVoucher) {
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
                                            if (isset($accountsVoucherApprovalLogs[$voucherType])) {
                                                $approvalLogs = $accountsVoucherApprovalLogs[$voucherType]
                                                ->where('voucher_id', $notification->menu_id)
                                                ->where('approval_layer', $step->layer);
                                            }
                                            if ($notification->accountsVoucher->approval_level > $step->layer) {
                                                $stepPassed = true; 
                                            }
                                        } elseif ($notification->module_name == 'Inventory') {
                                            if (isset($inventoryVoucherApprovalLogs[$notification->unique_name])) {
                                                $approvalLogs = $inventoryVoucherApprovalLogs[$notification->unique_name]
                                                ->where('voucher_details_id', $notification->menu_id)
                                                ->where('approval_layer', $step->layer);
                                            }
                                            if ($notification->unique_name == 'new_requisition' && $notification->newRequisitionDetails) {
                                                if ($notification->newRequisitionDetails->approval_level > $step->layer) {
                                                    $stepPassed = true; 
                                                }
                                            } elseif ($notification->unique_name == 'purchase_requisition' && $notification->purchaseRequisitionDetails) {
                                                if ($notification->purchaseRequisitionDetails->approval_level > $step->layer) {
                                                    $stepPassed = true; 
                                                }
                                            } elseif ($notification->unique_name == 'cs' && $notification->CSDetails) {
                                                if ($notification->CSDetails->approval_level > $step->layer) {
                                                    $stepPassed = true; 
                                                }
                                            } elseif ($notification->unique_name == 'purchase_order' && $notification->purchaseOrderDetails) {
                                                if ($notification->purchaseOrderDetails->approval_level > $step->layer) {
                                                    $stepPassed = true; 
                                                }
                                            } elseif ($notification->unique_name == 'purchase_invoice' && $notification->purchaseInvoiceDetails) {
                                                if (isset($inventoryVoucherApprovalLogs[$notification->unique_name])) {
                                                    $approvalLogs = $inventoryVoucherApprovalLogs[$notification->unique_name]
                                                    ->where('voucher_id', $notification->menu_id)
                                                    ->where('approval_layer', $step->layer);
                                                }
                                                if ($notification->purchaseInvoiceDetails->approval_level > $step->layer) {
                                                    $stepPassed = true; 
                                                }
                                            }
                                        } elseif ($notification->module_name == 'Academics') {
                                            if (isset($academicsApprovalLogs[$notification->unique_name])) {
                                                $approvalLogs = $academicsApprovalLogs[$notification->unique_name]
                                                ->where('menu_id', $notification->menu_id)
                                                ->where('approval_layer', $step->layer);
                                            }
                                            if ($notification->unique_name == 'exam_result' && $notification->examList) {
                                                if ($notification->examList->step > $step->layer) {
                                                    $stepPassed = true;
                                                }
                                            }
                                        }

                                        $userApproved = [];
                                        $comma = false;
                                    @endphp

                                    <span>
                                        @if ($step->layer == $notification->approval_level && $notification->action_status == 0)
                                            <b>Step {{ $step->layer }}: </b>
                                        @else
                                            <span>Step {{ $step->layer }}: </span>
                                        @endif
                                        <span class="text-primary">@if ($step->all_members) -All- @else -Any- @endif</span>
                                        @if ($roleName && sizeof($allUserIds)<1)
                                            (<b>Role: </b>{{ $roleName }})
                                        @endif

                                        @foreach ($approvalLogs as $approvalLog)
                                            @if ($notification->module_name == 'Accounts' || $notification->module_name == 'Inventory')
                                                @php
                                                    $userApproved[$approvalLog->approval_id] = true;
                                                    $comma = true;
                                                @endphp
                                                <b class="@if ($approvalLog->approval_id==$authUserId)text-success @endif">
                                                    {{ $approvalLog->user->name }}
                                                    ({{ Carbon\Carbon::parse($approvalLog->date)->diffForHumans() }})
                                                </b>
                                            @else
                                                @php
                                                    $userApproved[$approvalLog->user_id] = true;
                                                    $comma = true;
                                                @endphp
                                                <b class="@if ($approvalLog->user_id==$authUserId)text-success @endif">
                                                    {{ $approvalLog->user->name }}
                                                    ({{ Carbon\Carbon::parse($approvalLog->created_at)->diffForHumans() }})
                                                </b>
                                            @endif
                                            @if (!$loop->last),@endif
                                        @endforeach

                                        @foreach ($allUserIds as $userId)
                                            @if (!isset($userApproved[$userId]) && isset($allUsers[$userId]))
                                                <span class="@if ($userId==$authUserId)font-weight-bold text-danger @endif">
                                                    @if ($comma), @endif{{ $allUsers[$userId]->name }}
                                                </span>
                                                @php
                                                    $comma = true;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </span><br>
                                @endforeach
                            @endisset
                        @endif
                    </td>
                    <td>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</td>
                    <td><a href="{{ url($notification->menu_link) }}" target="_blank">Details</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>