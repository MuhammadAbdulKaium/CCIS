<?php

namespace Modules\UserRoleManagement\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\Imports\EmployeeImport;
use Modules\RoleManagement\Entities\Role;
use Modules\Student\Entities\StudentProfileView;
use Modules\UserRoleManagement\Entities\Imports\RouteImport;
use Modules\UserRoleManagement\Entities\MenuRoute;
use Modules\UserRoleManagement\Entities\RolePermission;
use Modules\UserRoleManagement\Entities\UserPermission;

class UserRoleManagementController extends Controller
{
    private $department;
    private $academicHelper;


    // constructor
    public function __construct(EmployeeDepartment $department, AcademicHelper $academicHelper)
    {
        $this->department = $department;
        $this->academicHelper = $academicHelper;
    }
    public function index()
    {
        return view('userrolemanagement::pages.upload-routes');
    }



    public function create()
    {
        return view('userrolemanagement::create');
    }



    public function store(Request $request)
    {
        $routes = Excel::toArray(new RouteImport(),$request->file('routeList'));

        DB::beginTransaction();
        try {
            foreach ($routes[0] as $route){
                if ($route['type']){
                    MenuRoute::create([
                        'uid' => $route['unique_id'],
                        'parent_uid' => $route['parent_uid'],
                        'has_child' => $route['has_child'],
                        'link_type' => $route['type'],
                        'label' => $route['label'],
                        'route_link' => $route['route_link'],
                        'route_method' => $route['route_method'],
                        'route_name' => $route['route_name'],
                        'order_no' => $route['order_no'],
                        'created_by' => Auth::id()
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Routes uploaded successfully.');
            return redirect()->back();
        } catch (\Exception $e){
            DB::rollback();
            Session::flash('errorMessage', 'Error uploading routes.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('userrolemanagement::show');
    }



    public function edit($id)
    {
        return view('userrolemanagement::edit');
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }


    public function rolePermissionsView(){
        $roles = Role::all();
        return view('userrolemanagement::pages.role-permissions', compact('roles'));
    }

    public function userPermissionsView(){
        $roles = Role::all();
        // campus and institute id
        $instituteId= $this->academicHelper->getInstitute();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // // all inputs as objects
        $batches = $this->academicHelper->getBatchList();

        return view('userrolemanagement::pages.user-permissions', compact('roles', 'allDepartments', 'batches'));
    }

    public function searchUser(Request $request){
        if ($request->category == 2){
            $role = 'student';

            if ($request->userIds){
                $users = StudentProfileView::where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute()
                ])->whereIn('std_id', $request->userIds)->get();
            } elseif ($request->section){
                $users = StudentProfileView::where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute(),
                    'section' => $request->section
                ])->get();
            } elseif ($request->batch){
                $users = StudentProfileView::where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute(),
                    'batch' => $request->batch
                ])->get();
            } else{
                $users = StudentProfileView::where([
                    'campus' => $this->academicHelper->getCampus(),
                    'institute' => $this->academicHelper->getInstitute()
                ])->get();
            }
        } else{
            $role = 'employee';

            if ($request->userIds){
                $users = EmployeeInformation::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute()
                ])->whereIn('id', $request->userIds)->get();
            } elseif ($request->designation){
                $users = EmployeeInformation::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'designation' => $request->designation
                ])->get();
            } elseif ($request->department){
                $users = EmployeeInformation::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'department' => $request->department
                ])->get();
            } else{
                $users = EmployeeInformation::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute()
                ])->get();
            }
        }

        return view('userrolemanagement::pages.snippets.user-list', compact('role','users'))->render();
    }

    public function menuAccessibility($type, $id){
        $role = null;
        $user = null;
        $rolePermissions = null;
        $userPermissions = null;

        if ($type == 'role'){
            $role = Role::findOrFail($id);
            $rolePermissions = RolePermission::where('role_id', $id)->get()->keyBy('menu_route_id');
        } else if($type == 'user'){
            $user = User::findOrFail($id);
            $userPermissions = UserPermission::where('user_id', $id)->get()->keyBy('menu_route_id');

            if (sizeof($userPermissions)<1){
                $userPermissions = null;
                $rolePermissions = RolePermission::where('role_id', $user->role()->id)->get()->keyBy('menu_route_id');
            }
        }

        $allRoutes = MenuRoute::all();

        return view('userrolemanagement::pages.modal.menu-accessibility', compact('role','user', 'allRoutes', 'rolePermissions', 'userPermissions'));
    }

    public function saveMenuAccessibility(Request $request){
        DB::beginTransaction();
        try {
            if($request->roleId){
                $oldRouteIds = RolePermission::where('role_id', $request->roleId)->pluck('menu_route_id')->toArray();
                $newRouteIds = ($request->routes)?$request->routes:[];
                $deleteIds = array_diff($oldRouteIds, $newRouteIds);
                $createIds = array_diff($newRouteIds, $oldRouteIds);

                if (sizeof($deleteIds) > 0){
                    RolePermission::where('role_id', $request->roleId)->whereIn('menu_route_id', $deleteIds)->delete();
                }

                foreach ($createIds as $key => $route){
                    RolePermission::create([
                        'menu_route_id' => $route,
                        'role_id' => $request->roleId,
                        'created_by' => Auth::id()
                    ]);
                }

                Session::flash('message', 'Role permission saved successfully.');
            } elseif ($request->userId){
                $oldRouteIds = UserPermission::where('user_id', $request->userId)->pluck('menu_route_id')->toArray();
                $newRouteIds = ($request->routes)?$request->routes:[];
                $deleteIds = array_diff($oldRouteIds, $newRouteIds);
                $createIds = array_diff($newRouteIds, $oldRouteIds);

                if (sizeof($deleteIds) > 0){
                    UserPermission::where('user_id', $request->userId)->whereIn('menu_route_id', $deleteIds)->delete();
                }

                foreach ($createIds as $key => $route){
                    UserPermission::create([
                        'menu_route_id' => $route,
                        'user_id' => $request->userId,
                        'created_by' => Auth::id()
                    ]);
                }

                Session::flash('message', 'User permission saved successfully.');
            }
           DB::commit();
       } catch (\Exception $e){
           DB::rollBack();
           Session::flash('errorMessage', 'Error saving permissions.');
       }

        return redirect()->back();
    }

    //Ajax Methods
    public function getEmployeesFromDesignation(Request $request){
        if ($request->designation){
            return EmployeeInformation::with('singleUser')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'designation' => $request->designation
            ])->get();
        } else{
            return [];
        }
    }

    public function getStudentsFromSection(Request $request){
        if ($request->section){
            return StudentProfileView::with('singleUser')->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
                'section' => $request->section
            ])->get();
        } else{
            return [];
        }
    }
}
