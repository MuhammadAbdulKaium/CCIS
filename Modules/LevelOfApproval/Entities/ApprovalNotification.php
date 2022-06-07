<?php

namespace Modules\LevelOfApproval\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\ExamList;
use Modules\Accounts\Entities\AccountsTransactionModel;
use Modules\Employee\Entities\EmployeeLeaveApplication;
use Modules\Inventory\Entities\ComparativeStatementDetailsModel;
use Modules\Inventory\Entities\NewRequisitionDetailsModel;
use Modules\Inventory\Entities\PurchaseInvoiceDetailsModel;
use Modules\Inventory\Entities\PurchaseOrderDetailsModel;
use Modules\Inventory\Entities\PurchaseRequisitionDetailsModel;

class ApprovalNotification extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_approval_notifications';
    protected $guarded = [];

    

    public function accountsVoucher()
    {
        return $this->belongsTo(AccountsTransactionModel::class, 'menu_id', 'id');
    }

    public function newRequisitionDetails()
    {
        return $this->belongsTo(NewRequisitionDetailsModel::class, 'menu_id', 'id');
    }

    public function purchaseRequisitionDetails()
    {
        return $this->belongsTo(PurchaseRequisitionDetailsModel::class, 'menu_id', 'id');
    }

    public function CSDetails()
    {
        return $this->belongsTo(ComparativeStatementDetailsModel::class, 'menu_id', 'id');
    }

    public function purchaseOrderDetails()
    {
        return $this->belongsTo(PurchaseOrderDetailsModel::class, 'menu_id', 'id');
    }

    public function purchaseInvoiceDetails()
    {
        return $this->belongsTo(PurchaseInvoiceDetailsModel::class, 'menu_id', 'id');
    }

    public function leaveApplications()
    {
        return $this->belongsTo(EmployeeLeaveApplication::class, 'menu_id', 'id');
    }

    public function examList()
    {
        return $this->belongsTo(ExamList::class, 'menu_id', 'id');
    }
}
