@php
$user = Auth::user();
$userRole = $user->role() ? $user->role()->name : null;
$menuAccess = getMenuList();
//print_r($menuAccess);

//static array prepared for menu access
$academicsSOPSetup = ['academics/academic-year', 'academics/admission-year', 'academics/academic-level', 'academics/semester', 'academics/division', 'academics/subject', 'academics/subject/group', 'academics/batch', 'academics/section'];

$humanResourceSOPSetup = ['employee/departments', 'employee/designations', 'employee/evaluations'];

$cadetsSOPSetup = ['student/cadet-activity-directory', 'student/club/setup/enrollment', 'student/task/schedule', 'student/view/task/schedule'];

$accountsSOPSetup = ['accounts/accounts-configuration', 'accounts/fiscal-year', 'accounts/voucher-config-list', 'accounts/chart-of-accounts'];

$inventorySOPSetup = ['inventory/stock-group-grid', 'inventory/stock-category', 'inventory/unit-of-measurement', 'inventory/stock-list', 'inventory/stock-item-serial', 'inventory/voucher-config-list', 'inventory/store', 'inventory/vendor', 'inventory/customer', 'inventory/stock-master-excel-import'];

$healthCareSOPSetup = ['healthcare/investigation'];

$roleUserSOPSetup = ['userrolemanagement/upload-routes', 'userrolemanagement/roll-permissions', 'userrolemanagement/user-permissions'];

$setting = ['default/index', 'setting/country', 'setting/state', 'setting/city', 'setting', 'setting/fees/setting/list', 'setting/sms/setting/getway', 'setting/institute/sms-price', 'setting/sms/getway/list', 'setting/institute/campus/assign', 'setting/rights/role', 'setting/manage/users', 'setting/change/password', 'setting/performance/category'];
@endphp
<style>
    .menu_icon{
        height: 20px;
        width: 20px;
    }
</style>
<nav>
    @if (!Request::is('/'))
        <ul class="sidebar_menu" id="side_menu">
            @if (hasMenuAccess($menuAccess, $academicsSOPSetup))
                {{-- Academics Start --}}
                <li class="treeview">
                    <a href="#">
                        <img src="{{ asset('assets/icon/AOA-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> Academics
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (hasMenuAccess($menuAccess, $academicsSOPSetup))
                            <li>
                                <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i>SOP Setup
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('academics/academic-year', $menuAccess))
                                        <li><a href="{{ url('academics/academic-year') }}"><i
                                                    class="fa fa-calendar"></i>
                                                Academic Year</a></li>
                                    @endif
                                    @if (in_array('academics/admission-year', $menuAccess))
                                        <li><a href="{{ url('academics/admission-year') }}"><i
                                                    class="fa fa-calendar"></i> Admission Year</a></li>
                                    @endif
                                    @if (in_array('academics/academic-level', $menuAccess))
                                        <li><a href="{{ url('academics/academic-level') }}"><i
                                                    class="fa fa-graduation-cap"></i> Academic Level</a></li>
                                    @endif
                                    @if (in_array('academics/semester', $menuAccess))
                                        <li><a href="{{ url('academics/semester') }}"><i
                                                    class="fa fa-info-circle"></i>
                                                Term</a></li>
                                    @endif
                                    @if (in_array('academics/division', $menuAccess))
                                        <li><a href="{{ url('academics/division') }}"><i
                                                    class="fa fa-info-circle"></i>
                                                Group</a></li>
                                    @endif
                                    @if (in_array('academics/subject', $menuAccess))
                                        <li><a href="{{ url('academics/subject') }}"><i class="fa fa-book"></i>
                                                Subject</a>
                                        </li>
                                    @endif
                                    @if (in_array('academics/subject/group', $menuAccess))
                                        <li><a href="{{ url('academics/subject/group') }}"><i
                                                    class="fa fa-book"></i>
                                                Subject Group</a>
                                        </li>
                                    @endif
                                    @if (in_array('academics/batch', $menuAccess))
                                        <li><a href="{{ url('academics/batch') }}"><i class="fa fa-sitemap"></i>
                                                Batch</a>
                                        </li>
                                    @endif
                                    @if (in_array('academics/section', $menuAccess))
                                        <li><a href="{{ url('academics/section') }}"><i class="fa fa-sitemap"></i>
                                                Section</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
                {{-- Academics End --}}
            @endif


            {{-- Online Academic Start --}}
            @if (in_array('onlineacademics/onlineacademic/classtopic', $menuAccess))
                <li>
                    <a href="{{ url('/onlineacademics/onlineacademic/classtopic') }}">
                        <img src="{{ asset('assets/icon/OnlineAcademics-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> Online Academic
                    </a>
                </li>
            @endif
            {{-- Online Academic End --}}



            {{-- Human Resource Start --}}
            @if (hasMenuAccess($menuAccess, $humanResourceSOPSetup) || hasMenuAccess($menuAccess, $humanResourceOperations))
                <li class="treeview">
                    <a href="#"><img src="{{ asset('assets/icon/HR-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> Human Resource
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (hasMenuAccess($menuAccess, $humanResourceSOPSetup))
                            <li>
                                <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (hasMenuAccess($menuAccess, ['employee/departments', 'employee/designations']))
                                        <li class="treeview">
                                            <a href="#"><i class="fa fa-user"></i> Employee Register
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                @if (in_array('employee/departments', $menuAccess))
                                                    <li><a href="{{url('/employee/departments')}}"><i class="fa fa-sitemap"></i>
                                                            Department</a>
                                                    </li>
                                                @endif
                                                @if (in_array('employee/designations', $menuAccess))
                                                    <li><a href="{{url('/employee/designations')}}"><i class="fa fa-signal"></i>
                                                            Designation</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if (in_array('employee/evaluations', $menuAccess))
                                        <li><a href="{{ url('/employee/evaluations') }}"><i
                                                    class="fa fa-gears"></i>
                                                Evaluation Set Up </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        <li>
                            <a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports
                                <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('superadmin.hr-register') }}"><i class="fa fa-cog"></i>
                                        Central HR Register</a>
                                </li>
                                <li><a href="{{url('employee/vacancy-report-designation/report')}}"><i
                                    class="fa fa-list-alt"></i> Vacancy Report (Designation)</a>
                                </li>
                                <li><a href="{{url('employee/vacancy-report-department/report')}}"><i
                                    class="fa fa-list-alt"></i> Vacancy Report (Department)</a>
                                </li>
                                <li><a href="{{url('employee/seniority/list/report')}}"><i
                                    class="fa fa-list-alt"></i> HR Register Report</a>
                                </li>
                                <li><a href="{{ url('/employee/profile/details/report') }}"><i
                                    class="fa fa-list-alt"></i>HR Details Report</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif
            {{-- Human Resource End --}}

            {{-- Cadets Start --}}
            @if (hasMenuAccess($menuAccess, $cadetsSOPSetup))
                <li class="treeview">
                    <a href="#"><img src="{{ asset('assets/icon/Cadet-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> Cadets
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (hasMenuAccess($menuAccess, $cadetsSOPSetup))
                            <li>
                                <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('student/cadet-activity-directory', $menuAccess))
                                        <li><a href="{{url('/student/cadet-activity-directory')}}"><i class="fa fa-list"></i>
                                                Cadet Activity Directory</a>
                                        </li>
                                    @endif
                                    @if (in_array('student/club/setup/enrollment', $menuAccess))
                                        <li><a href="{{url('/student/club/setup/enrollment')}}"><i class="fa fa-users"></i>
                                                Club Setup</a>
                                        </li>
                                    @endif
                                    @if (in_array('student/task/schedule', $menuAccess))
                                        <li><a href="{{url('/student/task/schedule')}}"><i class="fa fa-users"></i> Task
                                                Schedule</a>
                                        </li>
                                    @endif
                                    @if (in_array('student/view/task/schedule', $menuAccess))
                                        <li><a href="{{url('/student/view/task/schedule')}}"><i class="fa fa-users"></i> View
                                                Task Schedule</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        <li>
                            <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Operations
                                <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/admin/dashboard/cadet/register')}}"><i class="fa fa-cog"></i>
                                        Central Cadet Register</a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </li>
            @endif
            {{-- Cadets End --}}


            {{-- Accounts Start --}}
            @if (hasMenuAccess($menuAccess, $accountsSOPSetup))
                <li class="treeview">
                    <a href="#"><img src="{{ asset('assets/icon/Accounts-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> Accounts
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (hasMenuAccess($menuAccess, $accountsSOPSetup))
                            <li>
                                <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('accounts/accounts-configuration', $menuAccess))
                                        <li><a href="{{ url('/accounts/accounts-configuration') }}"><i
                                                    class="fa fa-sitemap"></i> Accounts Configuration</a></li>
                                    @endif
                                    @if (in_array('accounts/fiscal-year', $menuAccess))
                                        <li><a href="{{ url('/accounts/fiscal-year') }}"><i
                                                    class="fa fa-sitemap"></i>
                                                Fiscal Year</a></li>
                                    @endif
                                    @if (in_array('accounts/voucher-config-list', $menuAccess))
                                        <li><a href="{{ url('/accounts/voucher-config-list') }}"><i
                                                    class="fa fa-sitemap"></i> Voucher Configuration</a></li>
                                    @endif
                                    @if (in_array('accounts/chart-of-accounts', $menuAccess))
                                        <li><a href="{{ url('/accounts/chart-of-accounts') }}"><i
                                                    class="fa fa-sitemap"></i> Chart of Accounts</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif
            {{-- Accounts End --}}


            {{-- Inventory Start --}}
            @if (hasMenuAccess($menuAccess, $inventorySOPSetup))
                <li class="treeview">
                    <a href="#"><img src="{{ asset('assets/icon/INventory-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> Inventory
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (hasMenuAccess($menuAccess, $inventorySOPSetup))
                            <li>
                                <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if(hasMenuAccess($menuAccess,['inventory/stock-group-grid','inventory/stock-category','inventory/unit-of-measurement','inventory/stock-list', 'inventory/stock-item-serial']))
                                        <li>
                                            <a href="#"><i class="fa fa-building"></i> Stock
                                                <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu">
                                                @if(in_array('inventory/stock-group-grid', $menuAccess))
                                                <li><a href="{{url('/inventory/stock-group-grid')}}"> Stock Group</a></li>
                                                @endif
                                                @if(in_array('inventory/stock-category', $menuAccess))
                                                <li><a href="{{url('/inventory/stock-category')}}"> Stock Category</a></li>
                                                @endif
                                                @if(in_array('inventory/unit-of-measurement', $menuAccess))
                                                <li><a href="{{url('/inventory/unit-of-measurement')}}"> Unit Of Measurement</a></li>
                                                @endif
                                                @if(in_array('inventory/stock-list', $menuAccess))
                                                <li><a href="{{url('/inventory/stock-list')}}"> Stock Master</a></li>
                                                @endif
                                                @if(in_array('inventory/stock-master-excel-import', $menuAccess))
                                                <li><a href="{{url('/inventory/stock-master-excel-import')}}"> Stock Master Excel Import</a></li>
                                                @endif
                                                @if(in_array('inventory/stock-item-serial', $menuAccess))
                                                <li><a href="{{url('/inventory/stock-item-serial')}}"> Stock Item Serial</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if (in_array('inventory/voucher-config-list', $menuAccess))
                                        <li>
                                            <a href="#"><i class="fa fa-building"></i> Voucher
                                                <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu">
                                                <li><a href="{{ url('/inventory/voucher-config-list') }}"> Voucher
                                                        Config</a></li>
                                            </ul>
                                        </li>
                                    @endif
                                    @if (in_array('inventory/store', $menuAccess))
                                        <li>
                                            <a href="#"><i class="fa fa-building"></i> Store
                                                <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu">
                                                <li><a href="{{ url('/inventory/store') }}"> Store</a></li>
                                            </ul>
                                        </li>
                                    @endif
                                    @if (in_array('inventory/vendor', $menuAccess))
                                        <li>
                                            <a href="#"><i class="fa fa-building"></i> Purchase
                                                <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu">
                                                <li><a href="{{ url('/inventory/vendor') }}"> Vendor</a></li>
                                            </ul>
                                        </li>
                                    @endif
                                    @if (in_array('inventory/customer', $menuAccess))
                                        <li>
                                            <a href="#"><i class="fa fa-building"></i> Sales
                                                <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu">
                                                <li><a href="{{ url('/inventory/customer') }}"> Customer</a></li>
                                            </ul>
                                        </li>
                                    @endif
                                    @if (in_array('inventory/price-catalogue', $menuAccess))
                                        <li>
                                            <a href="{{ url('/inventory/price-catalogue') }}"><i
                                                    class="fa fa-sitemap"></i>
                                                Price
                                                Catalogue</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        <li>
                            <a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports
                                <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">

                            </ul>
                        </li>
                    </ul>
                </li>
            @endif
            {{-- Inventory End --}}
           

            {{-- User Role Management Start --}}
            @if (hasMenuAccess($menuAccess, $roleUserSOPSetup) || $userRole == 'super-admin')

                <li class="treeview">
                    <a href="#"><img src="{{ asset('assets/icon/User_Role-removebg-preview.png') }}" class="menu_icon icon-margin" alt=""> User Role Management
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (hasMenuAccess($menuAccess, $roleUserSOPSetup) || $userRole == 'super-admin')
                            <li>
                                <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('userrolemanagement/upload-routes', $menuAccess) || $userRole == 'super-admin')
                                        <li><a href="{{ url('/userrolemanagement/upload-routes') }}"><i
                                                    class="fa fa-sitemap"></i> Upload Routes</a></li>
                                    @endif
                                    @if (in_array('userrolemanagement/roll-permissions', $menuAccess) || $userRole == 'super-admin')
                                        <li><a href="{{ url('/userrolemanagement/roll-permissions') }}"><i
                                                    class="fa fa-sitemap"></i> Role Wise Permission</a></li>
                                    @endif
                                    @if (in_array('userrolemanagement/user-permissions', $menuAccess) || $userRole == 'super-admin')
                                        <li><a href="{{ url('/userrolemanagement/user-permissions') }}"><i
                                                    class="fa fa-sitemap"></i> User Wise Permission</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        <li>
                            <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations
                                <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">


                            </ul>
                        </li>

                    </ul>
                </li>
            @endif
            {{-- User Role Management End --}}



            {{-- Setting Start --}}
            @if (hasMenuAccess($menuAccess, $setting))

                <li class="treeview">
                    <a href="#"><img src="{{ asset('assets/icon/Setting-removebg-preview.png') }}" class="menu_icon icon-margin"> Setting
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if(hasMenuAccess($menuAccess,['default/index','setting/country','setting/state','setting/city','setting','setting/fees/setting/list']))
                            <li>
                                <a href="#"><i class="fa fa-cogs"></i> Configuration
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('setting/country', $menuAccess))
                                        <li><a href="{{ url('setting/country') }}"><i class="fa fa-globe"></i>
                                                Country</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting/state', $menuAccess))
                                        <li><a href="{{ url('setting/state') }}"><i class="fa fa-map-marker"></i>
                                                State/Province</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting/city', $menuAccess))
                                        <li><a href="{{ url('setting/city') }}"><i class="fa fa-building-o"></i>
                                                City/Town</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting', $menuAccess))
                                        <li><a href="{{ url('setting') }}"><i class="fa fa-bank"></i>
                                                Institute</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting/fees/setting/list', $menuAccess))
                                        <li><a href="{{url('/setting/fees/setting/list')}}"><i class="fa fa-bank"></i>Fine
                                                Setting</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if (hasMenuAccess($menuAccess, ['setting/sms/setting/getway', 'setting/institute/sms-price', 'setting/sms/getway/list']))
                            <li>
                                <a href="#"><i class="fa fa-envelope"></i> SMS
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('setting/sms/setting/getway', $menuAccess))
                                        <li><a href="{{url('/setting/sms/setting/getway')}}"><i class="fa fa-cogs"></i> SMS
                                                Settings</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting/institute/sms-price', $menuAccess))
                                        <li><a href="{{url('/setting/institute/sms-price')}}"><i class="fa fa-cogs"></i>
                                                SMS Price</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting/sms/getway/list', $menuAccess))
                                        <li><a href="{{url('/setting/sms/getway/list')}}"><i class="fa fa-cogs"></i> SMS
                                                Gateway List</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        @if (hasMenuAccess($menuAccess, ['setting/institute/campus/assign', 'setting/rights/role']))
                            <li>
                                <a href="#">Manage User Rights
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('setting/institute/campus/assign', $menuAccess))
                                        <li><a href="{{url('/setting/institute/campus/assign')}}"><i class="fa fa-male"></i>
                                                Assignment</a></li>
                                    @endif
                                    @if (in_array('setting/rights/role', $menuAccess))
                                        <li><a href="{{url('/setting/rights/role')}}"><i class="fa fa-user-times"></i> Role</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        @if (hasMenuAccess($menuAccess, ['setting/manage/users', 'setting/change/password']))
                            <li>
                                <a href="#"><i class="fa fa-user-secret"></i> Manage Users
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    @if (in_array('setting/manage/users', $menuAccess))
                                        <li><a href="{{url('/setting/manage/users')}}"><i class="fa fa-male"></i> Manage
                                                Admin Users</a>
                                        </li>
                                    @endif
                                    @if (in_array('setting/change/password', $menuAccess))
                                        <li><a href="{{url('/setting/change/password')}}"><i class="fa fa-male"></i> Change
                                                Password</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        @if (in_array('setting/performance/category', $menuAccess))
                            <li>
                                <a href="#"><i class="fa fa-cog"></i> Factor Config
                                    <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="{{url('/setting/performance/category')}}">Factor Add</a></li>

                                </ul>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
            {{-- Setting End --}}







            <li style="height: 40px; width:100%;">

            </li>

        </ul>
    @endif
</nav>
