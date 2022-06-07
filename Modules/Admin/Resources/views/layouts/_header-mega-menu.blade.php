<style>
   h4.head-text a {
   color: #fff;
   }
   @php $headerBgColor=institute_property("Header") @endphp
   .main-menu-headerbg {
   @if(!empty($headerBgColor->attribute_value))
   background:{{ $headerBgColor->attribute_value }} !important;
   @else
   background:#0b460b;
   @endif
</style>
@php 
   $user = Auth::user(); 
   $userRole = ($user->role())?$user->role()->name:null;
   $menuAccess = getMenuList();
   //print_r($menuAccess);

   //static array prepared for menu access 
   $academicsSOPSetup = ['academics/academic-year','academics/admission-year','academics/academic-level','academics/semester','academics/division','academics/subject','academics/subject/group','academics/batch','academics/section']; 

   $humanResourceSOPSetup = ['employee/departments','employee/designations','employee/evaluations']; 

   $cadetsSOPSetup = ['student/cadet-activity-directory','student/club/setup/enrollment','student/task/schedule','student/view/task/schedule']; 
   
   $accountsSOPSetup = ['accounts/accounts-configuration','accounts/fiscal-year','accounts/voucher-config-list','accounts/chart-of-accounts']; 
  
   $inventorySOPSetup = ['inventory/stock-group-grid','inventory/stock-category','inventory/unit-of-measurement','inventory/stock-list','inventory/stock-item-serial','inventory/voucher-config-list','inventory/store','inventory/vendor','inventory/customer', 'inventory/stock-master-excel-import']; 
   

   $healthCareSOPSetup = ['healthcare/investigation']; 
   

   $roleUserSOPSetup = ['userrolemanagement/upload-routes','userrolemanagement/roll-permissions','userrolemanagement/user-permissions']; 

   $setting = ['default/index','setting/country','setting/state','setting/city','setting','setting/fees/setting/list','setting/sms/setting/getway','setting/institute/sms-price','setting/sms/getway/list','setting/institute/campus/assign','setting/rights/role','setting/manage/users','setting/change/password','setting/performance/category']; 

@endphp
<nav class="navbar navbar-default main-menu main-menu-headerbg" role="navigation" style="min-height: 50px;">
   <div class="container-fluid">
      <div class="navbar-header">
         <h4 class="head-text"><a href="{{URL::to('home')}}"></a></h4>
      </div>
      <div class="">
         @if (!Request::is('/'))
         {{--header menu permission according to the role--}}
         <ul class="nav navbar-nav">
            <li>
               <a href="#" class="main-menu-style"><span class="fa fa-th-large icon-margin"></span> Menu </a>
               <ul class="dropdown-menus main-dropdown">
                  @if(hasMenuAccess($menuAccess,$academicsSOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-book icon-margin"></span> Academics <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$academicsSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('academics/academic-year',$menuAccess))
                              <li><a href="{{url('academics/academic-year')}}"><i
                                 class="fa fa-calendar"></i> Academic Year</a></li>
                              @endif
                              @if(in_array('academics/admission-year',$menuAccess))
                              <li><a href="{{url('academics/admission-year')}}"><i
                                 class="fa fa-calendar"></i> Admission Year</a></li>
                              @endif
                              @if(in_array('academics/academic-level',$menuAccess))
                              <li><a href="{{url('academics/academic-level')}}"><i
                                 class="fa fa-graduation-cap"></i> Academic Level</a></li>
                              @endif
                              @if(in_array('academics/semester',$menuAccess))
                              <li><a href="{{url('academics/semester')}}"><i
                                 class="fa fa-info-circle"></i> Term</a></li>
                              @endif
                              @if(in_array('academics/division',$menuAccess))
                              <li><a href="{{url('academics/division')}}"><i
                                 class="fa fa-info-circle"></i> Group</a></li>
                              @endif
                              @if(in_array('academics/subject',$menuAccess))
                              <li><a href="{{url('academics/subject')}}"><i class="fa fa-book"></i>
                                 Subject</a>
                              </li>
                              @endif
                              @if(in_array('academics/subject/group',$menuAccess))
                              <li><a href="{{url('academics/subject/group')}}"><i class="fa fa-book"></i>
                                 Subject Group</a>
                              </li>
                              @endif
                              @if(in_array('academics/batch',$menuAccess))
                              <li><a href="{{url('academics/batch')}}"><i class="fa fa-sitemap"></i> Batch</a>
                              </li>
                              @endif
                              @if(in_array('academics/section',$menuAccess))
                              <li><a href="{{url('academics/section')}}"><i class="fa fa-sitemap"></i>
                                 Section</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  @if(in_array('onlineacademics/onlineacademic/classtopic',$menuAccess))
                  <li>
                     <a href="{{url('/onlineacademics/onlineacademic/classtopic')}}">
                     <span class="fa fa-desktop icon-margin"></span> Online Academic
                     </a>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$humanResourceSOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-users icon-margin"></span> Human Resource <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$humanResourceSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(hasMenuAccess($menuAccess,['employee/departments','employee/designations']))
                              <li>
                                 <a href="#"><i class="fa fa-user"></i> Employee Management<span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('employee/departments',$menuAccess))
                                    <li><a href="/employee/departments"><i class="fa fa-sitemap"></i>
                                       Department</a>
                                    </li>
                                    @endif
                                    @if(in_array('employee/designations',$menuAccess))
                                    <li><a href="/employee/designations"><i class="fa fa-signal"></i>
                                       Designation</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('employee/evaluations',$menuAccess))
                              <li><a href="{{url('/employee/evaluations')}}"><i class="fa fa-gears"></i>
                                 Evaluation Set Up </a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                           <li>
                              <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Operations
                                 <span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="{{route('superadmin.hr-register')}}"><i class="fa fa-cog"></i>
                                       Central  HR Register</a>
                                 </li>


                              </ul>
                           </li>

                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$cadetsSOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-user icon-margin"></span> Cadets <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$cadetsSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('student/cadet-activity-directory',$menuAccess))
                              <li><a href="/student/cadet-activity-directory"><i class="fa fa-list"></i>
                                 Cadet Activity Directory</a>
                              </li>
                              @endif
                              @if(in_array('student/club/setup/enrollment',$menuAccess))
                              <li><a href="/student/club/setup/enrollment"><i class="fa fa-users"></i>
                                 Club Setup</a>
                              </li>
                              @endif
                              @if(in_array('student/task/schedule',$menuAccess))
                              <li><a href="/student/task/schedule"><i class="fa fa-users"></i> Task
                                 Schedule</a>
                              </li>
                              @endif
                              @if(in_array('student/view/task/schedule',$menuAccess))
                              <li><a href="/student/view/task/schedule"><i class="fa fa-users"></i> View
                                 Task Schedule</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                           <li>
                              <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Operations
                                 <span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                    <li><a href="/admin/dashboard/cadet/register"><i class="fa fa-cog"></i>
                                       Central  Cadet Register</a>
                                    </li>


                              </ul>
                           </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  
                  @if(hasMenuAccess($menuAccess,$accountsSOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-wrench icon-margin"></span> Accounts <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$accountsSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('accounts/accounts-configuration', $menuAccess))
                              <li><a href="{{url('/accounts/accounts-configuration')}}"><i class="fa fa-sitemap"></i> Accounts Configuration</a></li>
                              @endif
                              @if(in_array('accounts/fiscal-year', $menuAccess))
                              <li><a href="{{url('/accounts/fiscal-year')}}"><i class="fa fa-sitemap"></i> Fiscal Year</a></li>
                              @endif
                              @if(in_array('accounts/voucher-config-list', $menuAccess))
                              <li><a href="{{url('/accounts/voucher-config-list')}}"><i class="fa fa-sitemap"></i> Voucher Configuration</a></li>
                              @endif
                              @if(in_array('accounts/chart-of-accounts', $menuAccess))
                              <li><a href="{{url('/accounts/chart-of-accounts')}}"><i class="fa fa-sitemap"></i> Chart of Accounts</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$inventorySOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-wrench icon-margin"></span> Inventory <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$inventorySOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(hasMenuAccess($menuAccess,['inventory/stock-group-grid','inventory/stock-category','inventory/unit-of-measurement','inventory/stock-list', 'inventory/stock-item-serial']))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Stock<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
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
                              @if(in_array('inventory/voucher-config-list', $menuAccess))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Voucher<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/voucher-config-list')}}"> Voucher Config</a></li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('inventory/store', $menuAccess))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Store<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/store')}}"> Store</a></li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('inventory/vendor', $menuAccess))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Purchase<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/vendor')}}"> Vendor</a></li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('inventory/customer', $menuAccess))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Sales<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/customer')}}"> Customer</a></li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('inventory/price-catalogue', $menuAccess))
                              <li><a href="{{url('/inventory/price-catalogue')}}"><i class="fa fa-sitemap"></i> Price Catalogue</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  
                  @if(hasMenuAccess($menuAccess,$roleUserSOPSetup) || $userRole == 'super-admin')
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> User Role Management <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$roleUserSOPSetup) || $userRole == 'super-admin')
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              @if(in_array('userrolemanagement/upload-routes', $menuAccess) || $userRole == 'super-admin')
                              <li><a href="{{url('/userrolemanagement/upload-routes')}}"><i class="fa fa-sitemap"></i> Upload Routes</a></li>
                              @endif
                              @if(in_array('userrolemanagement/roll-permissions', $menuAccess) || $userRole == 'super-admin')
                              <li><a href="{{url('/userrolemanagement/roll-permissions')}}"><i class="fa fa-sitemap"></i> Role Wise Permission</a></li>
                              @endif
                              @if(in_array('userrolemanagement/user-permissions', $menuAccess) || $userRole == 'super-admin')
                              <li><a href="{{url('/userrolemanagement/user-permissions')}}"><i class="fa fa-sitemap"></i> User Wise Permission</a></li>
                              @endif
                           </ul>

                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$setting))
                  <li>
                     <a href="#"><span class="fa fa-cog icon-margin"></span> Setting <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,['default/index','setting/country','setting/state','setting/city','setting','setting/fees/setting/list']))
                        <li>
                           <a href="/default/index"><i class="fa fa-cogs"></i> Configuration<span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('setting/country', $menuAccess))
                              <li><a href="{{url('setting/country')}}"><i class="fa fa-globe"></i>
                                 Country</a>
                              </li>
                              @endif
                              @if(in_array('setting/state', $menuAccess))
                              <li><a href="{{url('setting/state')}}"><i class="fa fa-map-marker"></i>
                                 State/Province</a>
                              </li>
                              @endif
                              @if(in_array('setting/city', $menuAccess))
                              <li><a href="{{url('setting/city')}}"><i class="fa fa-building-o"></i>
                                 City/Town</a>
                              </li>
                              @endif
                              @if(in_array('setting', $menuAccess))
                              <li><a href="{{url('setting')}}"><i class="fa fa-bank"></i>
                                 Institute</a>
                              </li>
                              @endif
                              @if(in_array('setting/fees/setting/list', $menuAccess))
                              <li><a href="/setting/fees/setting/list"><i class="fa fa-bank"></i>Fine
                                 Setting</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,['setting/sms/setting/getway','setting/institute/sms-price','setting/sms/getway/list']))
                        <li>
                           <a href="#"><i class="fa fa-envelope"></i> SMS<span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('setting/sms/setting/getway', $menuAccess))
                              <li><a href="/setting/sms/setting/getway"><i class="fa fa-cogs"></i> SMS
                                 Settings</a>
                              </li>
                              @endif
                              @if(in_array('setting/institute/sms-price', $menuAccess))
                              <li><a href="/setting/institute/sms-price"><i class="fa fa-cogs"></i>
                                 SMS Price</a>
                              </li>
                              @endif
                              @if(in_array('setting/sms/getway/list', $menuAccess))
                              <li><a href="/setting/sms/getway/list"><i class="fa fa-cogs"></i> SMS
                                 Gateway List</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,['setting/institute/campus/assign','setting/rights/role']))
                        <li>
                           <a href="#"> Manage User Rights <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('setting/institute/campus/assign', $menuAccess))
                              <li><a href="/setting/institute/campus/assign"><i
                                 class="fa fa-male"></i> Assignment</a></li>
                              @endif
                              @if(in_array('setting/rights/role', $menuAccess))
                              <li><a href="/setting/rights/role"><i class="fa fa-user-times"></i> Role</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,['setting/manage/users','setting/change/password']))
                        <li>
                           <a href="#"><i class="fa fa-user-secret"></i> Manage Users<span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('setting/manage/users', $menuAccess))
                              <li><a href="/setting/manage/users"><i class="fa fa-male"></i> Manage
                                 Admin Users</a>
                              </li>
                              @endif
                              @if(in_array('setting/change/password', $menuAccess))
                              <li><a href="/setting/change/password"><i class="fa fa-male"></i> Change
                                 Password</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(in_array('setting/performance/category', $menuAccess))
                        <li>
                           <a href="#"><i class="fa fa-cog"></i> Factor Config <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/setting/performance/category">Factor Add</a></li>
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
               </ul>
            </li>
            <li>
               <a href="{{url('/admin/bills/bill-info')}}" style="color:#70ec79;">
                  <i class="fa fa-money" aria-hidden="true" style="margin-right: 10px;"></i>Bill
               </a>
            </li>
         </ul>
         @endif 
      </div>
   </div>
</nav>
<!--/.nav-collapse -->
<div class=" clearfix"></div>