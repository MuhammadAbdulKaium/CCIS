<?php

namespace Modules\Canteen\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Canteen\Entities\CanteenMenu;
use Modules\Canteen\Entities\CanteenMenuCategory;
use Modules\Canteen\Entities\CanteenTransaction;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentProfileView;

class CanteenTransactionController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }



    public function index()
    {
        $menuCategories = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $menus = CanteenMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('canteen::transaction.index', compact('menuCategories', 'menus'));
    }



    public function create()
    {
        return view('canteen::create');
    }



    public function store(Request $request)
    {
        $menuItems = json_decode($request->purchaseDetails, 1);

        DB::beginTransaction();
        try {
            foreach ($menuItems as $key => $menuItem) {
                $menu = CanteenMenu::findOrFail($menuItem['id']);

                if ($menu->available_qty < $menuItem['qty']) {
                    DB::rollBack();
                    Session::flash('errorMessage', 'Error! Product stock out.');
                    return redirect()->back();
                }

                $menu->update([
                    'available_qty' => $menu->available_qty - $menuItem['qty'],
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            }

            CanteenTransaction::insert([
                'customer_type' => $request->customerType,
                'customer_id' => $request->customerId,
                'purchase_details' => $request->purchaseDetails,
                'total' => $request->total,
                'previous_dues' => $request->previousDues,
                'amount_given' => $request->amountGiven,
                'payment_for' => $request->paymentFor,
                'change_to_customer' => $request->amountGiven - $request->paymentFor,
                'carry_forwarded_due' => ($request->total + $request->previousDues) - $request->paymentFor,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute()
            ]);

            DB::commit();
            Session::flash('message', 'Transaction completed successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error completing transaction.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('canteen::show');
    }



    public function edit($id)
    {
        return view('canteen::edit');
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }

    public function transactionHistory($type, $id)
    {
        $transactions = CanteenTransaction::where([
            'customer_type' => $type,
            'customer_id' => $id,
        ])->orderByDesc('id')->get();

        return view('canteen::transaction.modal.transaction-history', compact('transactions'));
    }

    public function customerProcessing()
    {
        $menuCategories = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        return view('canteen::transaction.customer-processing', compact('menuCategories'));
    }

    public function getCustomerInfo(Request $request)
    {
        $type = $request->type;
        if ($type == 1) {
            $person = StudentProfileView::with('singleBatch', 'singleSection')->where('std_id', $request->id)->first();
        } elseif ($type == 2) {
            $person = EmployeeInformation::findOrFail($request->id);
        } else {
            $person = null;
        }

        $lastTrans = CanteenTransaction::where([
            'customer_type' => $type,
            'customer_id' => $request->id,
        ])->orderByDesc('id')->first();

        $html = view('canteen::transaction.transaction-user-info', compact('type', 'person', 'lastTrans'))->render();

        return ['html' => $html, 'lastTrans' => $lastTrans];
    }

    public function searchTransactions(Request $request)
    {
        $menuCategories = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $students = StudentProfileView::with('singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $employees = EmployeeInformation::all();

        $transactions = CanteenTransaction::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            ['customer_type', 'like', ($request->type) ? $request->type : '%'],
            ['customer_id', 'like', ($request->personId) ? $request->personId : '%'],
            ['purchase_details', 'like', ($request->categoryId) ? '%"categoryId":' . $request->categoryId . '%' : '%'],
            ['purchase_details', 'like', ($request->menuId) ? '%"id":' . $request->menuId . '%' : '%'],
        ])->orderByDesc('id')->get();

        if ($request->fromDate) {
            $fromDate = Carbon::parse($request->fromDate);
            $transactions = $transactions->where('created_at', '>=', $fromDate);
        }

        if ($request->toDate) {
            $toDate = Carbon::parse($request->toDate);
            $toDate->addDays(1);
            $transactions = $transactions->where('created_at', '<=', $toDate);
        }

        return view('canteen::transaction.customer-processing-tables', compact('transactions', 'menuCategories', 'students', 'employees'))->render();
    }
}
