<?php

namespace Modules\Canteen\Http\Controllers;

use App\Helpers\UserAccessHelper;
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
use Modules\Canteen\Entities\CanteenStockIn;
use Modules\Canteen\Http\Requests\CanteenStockInRequest;

class CanteenStockInController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }




    public function index($id = null,Request $request)
    {
        $pageAccessData = self::linkAccess($request);
        $menuCategories = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $stockIns = CanteenStockIn::with('category', 'menu')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderByDesc('id')->get();

        $stockIn = null;
        if ($id) {
            $stockIn = CanteenStockIn::with('category')->findOrFail($id);
        }

        return view('canteen::stock-in.index', compact('pageAccessData','menuCategories', 'stockIns', 'stockIn'));
    }



    public function create()
    {
        return view('canteen::create');
    }



    public function store(CanteenStockInRequest $request)
    {
        $menu = CanteenMenu::findOrFail($request->menuId);
        $effectiveCostDates = json_decode($menu->effective_cost_dates, 1);
        $date = Carbon::parse($request->date);
        $cost = null;

        foreach ($effectiveCostDates as $key => $effectiveCostDate) {
            $menuDate = Carbon::parse($effectiveCostDate['dateTime']);
            if ($date->gte($menuDate)) {
                $cost = $effectiveCostDate['cost'];
            }
        }
        if (!$cost) {
            Session::flash('errorMessage', 'Sorry! This item was created after your selected date.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            CanteenStockIn::insert([
                'date' => $date->toDateString(),
                'category_id' => $request->categoryId,
                'menu_id' => $request->menuId,
                'qty' => $request->qty,
                'cost' => $cost,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            $menu->update([
                'available_qty' => $menu->available_qty + $request->qty,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            Session::flash('message', 'Stock In successfull.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Erro creating Stock In.');
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



    public function update(CanteenStockInRequest $request, $id)
    {
        $stockIn = CanteenStockIn::findOrFail($id);
        $previousMenu = $stockIn->menu;
        $menu = CanteenMenu::findOrFail($request->menuId);
        $effectiveCostDates = json_decode($menu->effective_cost_dates, 1);
        $date = Carbon::parse($request->date);
        $cost = null;

        foreach ($effectiveCostDates as $key => $effectiveCostDate) {
            $menuDate = Carbon::parse($effectiveCostDate['dateTime']);
            if ($date->gte($menuDate)) {
                $cost = $effectiveCostDate['cost'];
            }
        }
        if (!$cost) {
            Session::flash('errorMessage', 'Sorry! This item was created after your selected date.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $newQty = $previousMenu->available_qty - $stockIn->qty;
            if ($newQty < 0) {
                Session::flash('errorMessage', 'Sorry! Items are already sold out can not reduce the quantity.');
                return redirect()->back();
            } else {
                if ($previousMenu->id == $menu->id) {
                    $menu->update([
                        'available_qty' => $newQty + $request->qty,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    $previousMenu->update([
                        'available_qty' => $newQty,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);
                    $menu->update([
                        'available_qty' => $menu->available_qty + $request->qty,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            $stockIn->update([
                'date' => $date->toDateString(),
                'category_id' => $request->categoryId,
                'menu_id' => $request->menuId,
                'qty' => $request->qty,
                'cost' => $cost,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            DB::commit();
            Session::flash('message', 'Stock In updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating stock in.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $stockIn = CanteenStockIn::findOrFail($id);
        $menu = $stockIn->menu;
        $newCost = $menu->available_qty - $stockIn->qty;

        if ($newCost >= 0) {
            $menu->update([
                'available_qty' => $newCost,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
            $stockIn->delete();
            Session::flash('message', 'Stock In deleted successfully.');
            return redirect()->back();
        } else {
            Session::flash('errorMessage', 'Products are already sold out can not delete stock in.');
            return redirect()->back();
        }
    }


    public function getMenusFromCategory(Request $request)
    {
        if ($request->categoryId) {
            return CanteenMenuCategory::findOrFail($request->categoryId)->menus;
        } else {
            return [];
        }
    }

    public function getUomFromMenus(Request $request)
    {
        if ($request->menuId) {
            return CanteenMenu::findOrFail($request->menuId)->uom;
        } else {
            return "";
        }
    }
}
