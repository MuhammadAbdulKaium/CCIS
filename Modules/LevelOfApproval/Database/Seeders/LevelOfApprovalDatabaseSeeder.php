<?php

namespace Modules\LevelOfApproval\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LevelOfApproval\Entities\LevelOfApproval;
use Modules\Setting\Entities\Institute;

class LevelOfApprovalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        LevelOfApproval::insert([
            // Accounts Rows Start
            [
                'module_name' => 'Academics',
                'sub_module_name' => 'Reports',
                'menu_name' => 'Tabulation Sheet(Exam)',
                'unique_name' => 'exam_result',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Accounts',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Payment Voucher',
                'unique_name' => 'payment_voucher',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Accounts',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Receive Voucher',
                'unique_name' => 'receive_voucher',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Accounts',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Journal Voucher',
                'unique_name' => 'journal_voucher',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Accounts',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Contra Voucher',
                'unique_name' => 'contra_voucher',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            // Accounts Rows End

            // Inventory Rows Start
            [
                'module_name' => 'Inventory',
                'sub_module_name' => 'Operation',
                'menu_name' => 'New Requisition',
                'unique_name' => 'new_requisition',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Inventory',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Purchase Requisition',
                'unique_name' => 'purchase_requisition',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Inventory',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Comparative Statement',
                'unique_name' => 'cs',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Inventory',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Purchase Order',
                'unique_name' => 'purchase_order',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            [
                'module_name' => 'Inventory',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Purchase Invoice',
                'unique_name' => 'purchase_invoice',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ],
            // Inventory Rows End

            // Leave Rows Start
            [
                'module_name' => 'Employee',
                'sub_module_name' => 'Operation',
                'menu_name' => 'Manage Leave Applications',
                'unique_name' => 'leave_application',
                'created_at' => Carbon::now(),
                'created_by' => 1
            ]
            // Leave Rows End
        ]);
        // $this->call("OthersTableSeeder");
    }
}
