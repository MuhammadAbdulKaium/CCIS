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
use Modules\Canteen\Entities\CanteenMenuRecipe;
use Modules\Canteen\Http\Requests\CanteenMenuCategoryRequest;
use Modules\Canteen\Http\Requests\CanteenMenuRecipeRequest;
use Modules\Canteen\Http\Requests\CanteenMenuRequest;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\StockUOM;

class MenuRecipeController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }



    public function index(Request  $request)
    {
        $pageAccessData = self::linkAccess($request);
        $menuCategories = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $uoms = StockUOM::all();

        $menus = CanteenMenu::with('category', 'uom')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $recipes = CanteenMenuRecipe::with('menu')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        return view('canteen::menu-recipe.index', compact('pageAccessData','menuCategories', 'uoms', 'menus', 'recipes'));
    }



    public function create()
    {
        return view('canteen::create');
    }



    public function store(CanteenMenuCategoryRequest $request)
    {
        $sameNameCategory = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_name' => $request->categoryName
        ])->get();

        if (sizeOf($sameNameCategory) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a menu category in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $menuCategoryId = CanteenMenuCategory::insertGetId([
                'category_name' => $request->categoryName,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($menuCategoryId) {
                DB::commit();
                Session::flash('message', 'New menu category created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating menu category.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating menu category.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('canteen::show');
    }



    public function edit($id)
    {
        $menuCategory = CanteenMenuCategory::findOrFail($id);

        return view('canteen::menu-recipe.modal.edit-menu-category', compact('menuCategory'));
    }



    public function update(Request $request, $id)
    {
        $menuCategory = CanteenMenuCategory::findOrFail($id);

        $sameNameCategory = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_name' => $request->categoryName
        ])->first();

        if ($sameNameCategory) {
            if ($sameNameCategory->id != $menuCategory->id) {
                Session::flash('errorMessage', 'Sorry! There is already a menu category in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $updateMenuCategory = $menuCategory->update([
                'category_name' => $request->categoryName,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateMenuCategory) {
                DB::commit();
                Session::flash('message', 'Menu category updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating menu category.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating menu category.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $menu = CanteenMenu::where('category_id', $id)->first();

        if ($menu) {
            Session::flash('errorMessage', 'Dependencies found, can not delete.');
            return redirect()->back();
        } else {
            CanteenMenuCategory::findOrFail($id)->delete();
            Session::flash('message', 'Menu category deleted successfully.');
            return redirect()->back();
        }
    }










    // Menu Methods
    public function storeMenu(CanteenMenuRequest $request)
    {
        $sameNameMenu = CanteenMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'menu_name' => $request->menuName
        ])->get();

        if (sizeOf($sameNameMenu) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a menu in this name.');
            return redirect()->back();
        }

        $effectiveCostDates = [[
            'dateTime' => Carbon::today(),
            'cost' => $request->costing
        ]];

        DB::beginTransaction();
        try {
            $insertMenu = CanteenMenu::insert([
                'category_id' => $request->categoryId,
                'menu_name' => $request->menuName,
                'uom_id' => $request->uomId,
                'cost' => $request->costing,
                'sell_price' => $request->sellPrice,
                'effective_cost_dates' => json_encode($effectiveCostDates),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute()
            ]);

            if ($insertMenu) {
                DB::commit();
                Session::flash('message', 'New menu created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating new menu.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating new menu.');
            return redirect()->back();
        }
    }

    public function editMenu($id)
    {
        $menu = CanteenMenu::findOrFail($id);
        $menuCategories = CanteenMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $uoms = StockUOM::all();

        return view('canteen::menu-recipe.modal.edit-menu', compact('menu', 'menuCategories', 'uoms'));
    }


    public function updateMenu(CanteenMenuRequest $request, $id)
    {
        $menu = CanteenMenu::findOrFail($id);

        $sameNameMenu = CanteenMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'menu_name' => $request->menuName
        ])->first();

        if ($sameNameMenu) {
            if ($sameNameMenu->id != $menu->id) {
                Session::flash('errorMessage', 'Sorry! There is already a menu in this name.');
                return redirect()->back();
            }
        }

        // History Keeping Start
        $costEffectiveDate = [
            'dateTime' => Carbon::parse($request->effectiveDate),
            'cost' => $request->costing
        ];
        $effectiveCostDates = json_decode($menu->effective_cost_dates, 1);
        $lastItem = end($effectiveCostDates);
        if ($lastItem['cost'] != $request->costing) {
            array_push($effectiveCostDates, $costEffectiveDate);
        }
        // History Keeping End


        DB::beginTransaction();
        try {
            $updateMenu = $menu->update([
                'category_id' => $request->categoryId,
                'menu_name' => $request->menuName,
                'uom_id' => $request->uomId,
                'cost' => $request->costing,
                'sell_price' => $request->sellPrice,
                'effective_cost_dates' => json_encode($effectiveCostDates),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateMenu) {
                DB::commit();
                Session::flash('message', 'Menu updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating menu.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating menu.');
            return redirect()->back();
        }
    }


    public function deleteMenu($id)
    {
        $menuRecipe = CanteenMenuRecipe::where('menu_id', $id)->first();

        if ($menuRecipe) {
            Session::flash('errorMessage', 'Dependencies found, can not delete.');
            return redirect()->back();
        } else {
            CanteenMenu::findOrFail($id)->delete();
            Session::flash('message', 'Menu deleted successfully.');
            return redirect()->back();
        }
    }

    public function menuHistory($id)
    {
        $menu = CanteenMenu::findOrFail($id);

        $effectiveCostDates = json_decode($menu->effective_cost_dates, 1);
        $histories = array_reverse($effectiveCostDates);

        return view('canteen::menu-recipe.modal.menu-history', compact('menu', 'histories'));
    }





    // Menu Recipe Methods

    public function storeMenuRecipe(CanteenMenuRecipeRequest $request)
    {
        $sameNameMenuRecipe = CanteenMenuRecipe::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'recipe_name' => $request->recipeName
        ])->get();

        if (sizeOf($sameNameMenuRecipe) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a menu recipe in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertMenuRecipe = CanteenMenuRecipe::insert([
                'menu_id' => $request->menuId,
                'recipe_name' => $request->recipeName,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute()
            ]);

            if ($insertMenuRecipe) {
                DB::commit();
                Session::flash('message', 'New menu recipe created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating new menu recipe.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating new menu recipe.');
            return redirect()->back();
        }
    }

    public function destroyRecipeItems($id)
    {
        $recipe = CanteenMenuRecipe::findOrFail($id);

        if ($recipe) {
            $recipe->delete();
            Session::flash('message', 'Menu recipe deleted successfully.');
            return redirect()->back();
        } else {
            Session::flash('errorMessage', 'Error deleting menu recipe.');
            return redirect()->back();
        }
    }

    public function editMenuRecipe($id)
    {
        $recipe = CanteenMenuRecipe::with('menu')->findOrFail($id);
        $menus = CanteenMenu::with('category', 'uom')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('canteen::menu-recipe.modal.edit-menu-recipe', compact('recipe', 'menus'));
    }

    public function updateMenuRecipe(CanteenMenuRecipeRequest $request, $id)
    {
        $recipe = CanteenMenuRecipe::with('menu')->findOrFail($id);

        $sameNameMenuRecipe = CanteenMenuRecipe::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'recipe_name' => $request->recipeName
        ])->first();

        if ($sameNameMenuRecipe) {
            if ($sameNameMenuRecipe->id != $recipe->id) {
                Session::flash('errorMessage', 'Sorry! There is already a menu recipe in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $updateMenuRecipe = $recipe->update([
                'menu_id' => $request->menuId,
                'recipe_name' => $request->recipeName,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateMenuRecipe) {
                DB::commit();
                Session::flash('message', 'Menu recipe updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating new menu recipe.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating new menu recipe.');
            return redirect()->back();
        }
    }


    public function assignRecipeItems($id)
    {
        $recipe = CanteenMenuRecipe::findOrFail($id);
        $stockItems = CadetInventoryProduct::where([
            'category_id' => 4
        ])->get();
        $uoms = StockUOM::all()->keyBy('id');

        return view('canteen::menu-recipe.modal.assign-recipe-items', compact('recipe', 'stockItems', 'uoms'));
    }

    // Menu Recipe Ajax Methods
    public function assignItemToRecipe(Request $request)
    {
        $recipe = CanteenMenuRecipe::findOrFail($request->recipeId);
        $stockItems = [];
        if ($recipe->stock_items) {
            $stockItems = json_decode($recipe->stock_items, 1);

            foreach ($stockItems as $key => $stockItem) {
                if ($stockItem['id'] == $request->stockItem['id']) {
                    return 2;
                }
            }
        }
        array_push($stockItems, $request->stockItem);

        DB::beginTransaction();
        try {
            $recipe->update([
                'stock_items' => $stockItems,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }

    public function getRecipeStockItems(Request $request)
    {
        $recipe = CanteenMenuRecipe::findOrFail($request->recipeId);
        return json_decode($recipe->stock_items, 1);
    }

    public function updateRecipeItem(Request $request)
    {
        $recipe = CanteenMenuRecipe::findOrFail($request->recipeId);
        $stockItems = json_decode($recipe->stock_items, 1);

        if ($stockItems) {
            foreach ($stockItems as $key => $stockItem) {
                if ($stockItem['id'] == $request->itemId) {
                    $stockItems[$key]['id'] = $request->newItemId;
                    $stockItems[$key]['qty'] = $request->itemQty;
                } elseif ($stockItem['id'] == $request->newItemId) {
                    return 2;
                }
            }
        }

        DB::beginTransaction();
        try {
            $recipe->update([
                'stock_items' => $stockItems,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }

    public function removeRecipeItem(Request $request)
    {
        $recipe = CanteenMenuRecipe::findOrFail($request->recipeId);
        $stockItems = json_decode($recipe->stock_items, 1);

        if ($stockItems) {
            foreach ($stockItems as $key => $stockItem) {
                if ($stockItem['id'] == $request->itemId) {
                    array_splice($stockItems, $key, 1);
                }
            }

            if (sizeof($stockItems) < 1) {
                $stockItems = null;
            }
        }

        DB::beginTransaction();
        try {
            $recipe->update([
                'stock_items' => $stockItems,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }
}
