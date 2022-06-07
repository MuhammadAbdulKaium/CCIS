<style>
   @php $headerBgColor=institute_property("Header") @endphp
   .main-menu-headerbg {
   @if(!empty($headerBgColor->attribute_value))
   background: {{ $headerBgColor->attribute_value }}  !important;
   @else
   background: #0b460b !important;
   }
   @endif
   .head-text > a {
   color: #fff;
   font-size: 14px;
   font-weight: bold;
   }
</style>
@php 
   $user = Auth::user(); 
   $menuAccess = getMenuList();
   $userRole = ($user->role())?$user->role()->name:null;
   //print_r($menuAccess);

   //static array prepared for menu access 
   $academicsSOPSetup = ['academics/academic-year','academics/admission-year','academics/academic-level','academics/semester','academics/division','academics/subject','academics/subject/group','academics/batch','academics/section','academics/physical/rooms','academics/exam-category/exam','academics/exam/marks']; 
   $academicsOperations = ['academics/manage/subject','academics/manage.assessments','academics/timetable','academics/exam/schedules','academics/exam/attendance','academics/exam/seatPlan','academics/exam/marks/entry','academics/exam/list']; 
   $academicsReports = ['academics/exam/tabulation-sheet/exam', 'academics/exam/tabulation-sheet/term-summary', 'academics/exam/tabulation-sheet/term', 'academics/exam/tabulation-sheet/year']; 

   $eventSOPSetup = ['event']; 
   $eventOperations = ['event/marks'];
   $eventReports = [];

   $humanResourceSOPSetup = ['employee/departments','employee/designations','employee/create','employee/import','employee/import.image','employee/employee/status','employee/shift-configuration','employee/holiday-calender','employee/evaluations','employee/manage/leave/type','employee/manage/leave/type','employee/manage/leave/structure','employee/manage/leave/entitlement','employee/employee-attendance-setting']; 
   $humanResourceOperations = ['employee/manage/teacher','employee/manage', 'employee/bulk-edit','employee/evaluation/view','employee/evaluation/search/view','employee/evaluation/history/view','employee/leave/application','employee/employee-attendance']; 
   $humanResourceReports = [];

   $cadetsSOPSetup = ['student/cadet-activity-directory','student/club.set-up','student/task-schedule','student/view/task/schedule']; 
   $cadetsOperations = ['student/profile.create','student/manage','student/cadet-performance-bulk','student/manage/status','student/import','student/cadet/bulk/edit','student/import.image','student/promote','student/parent/manage','academics/manage.attendance']; 
   $cadetsReports = ['student/detail/reports','student/transcript/reports','student/remarks','student/warnings'];
   
   $communicationSOPSetup = ['communication/sms/sms_credit','communication/sms/sms_log','communication/sms/group','communication/sms/pending_sms','communication/sms/template','communication/sms/template/list','communication/notice','communication/telephone-diary/student-contact','communication/telephone-diary/employee-contact']; 
   $communicationOperations = [];
   $communicationReports = [];

   $reportAndPrintingSOPSetup = ['reports/academics','reports/academics','reports/academics','reports/academics','reports/id-card','reports/sitplan','reports/admit-card','reports/documents#transfer_certificate','reports/documents','reports/attendance','reports/attendance','reports/attendance','report/attendance','report/attendance','reports/result','reports/admission','reports/fees','fees/report/date-wise-fees','reports/fees','reports/invoice','fees/report/index']; 
   $reportAndPrintingOperations = []; 
   $reportAndPrintingReports = [];

   $canteenSOPSetup=['canteen/menu-recipe'];
   $canteenOperations=['canteen/stock-in','canteen/transaction','canteen/customer-processing'];
  $canteenReports = [];


   $accountsSOPSetup = ['accounts/accounts-configuration','accounts/fiscal-year','accounts/voucher-config-list','accounts/chart-of-accounts','accounts/budget-allocation']; 
   $accountsOperations = ['accounts/payment-voucher','accounts/receive-voucher','accounts/journal-voucher','accounts/contra-voucher']; 
   $accountsReports = [];

   $houseSOPSetup = ['house/manage-house', 'house/assign/students/page', 'house/appoints', 'house/pocket-money']; 
   $houseOperations = ['house/view','house/cadets-evaluation','house/communication-records','house/record-score']; 
   $houseReports = [];

   $inventorySOPSetup = ['inventory/stock-group-grid','inventory/stock-category','inventory/unit-of-measurement','inventory/stock-list','inventory/stock-item-serial','inventory/voucher-config-list','inventory/store','inventory/vendor','inventory/customer','inventory/price-catalogue', 'inventory/stock-master-excel-import']; 
   $inventoryOperations = ['inventory/new-requisition','inventory/issue-inventory','inventory/purchase-requisition','inventory/comparative-statement','inventory/purchase-order','inventory/purchase-receive','inventory/purchase-invoice','inventory/stock-in','inventory/stock-out']; 
   $inventoryReports = [];

   $healthCareSOPSetup = ['healthcare/investigation']; 
   $healthCareOperations = ['healthcare/prescription','healthcare/drug/reports','healthcare/investigation/reports']; 
   $healthCareReports = [];

   $messSOPSetup = ['mess/table']; 
   $messOperations = ['mess/food-menu','mess/food-menu-schedule']; 
   $messReports = [];

   $librarySOPSetup = ['library/library-book-category/index','library/library-book-shelf/index','library/library-cupboard-shelf/index','library/library-book-vendor/index','library/library-book-status/index','library/library-book/index','library/library-fine-master/index']; 
   $libraryOperations = ['library/library-borrow-transaction/index','library/library-borrow-transaction/borrower']; 
   $libraryReports = [];

   $roleUserSOPSetup = ['userrolemanagement/upload-routes','userrolemanagement/roll-permissions','userrolemanagement/user-permissions']; 
   $roleUserOperations = []; 

   $setting = ['default/index','setting/country','setting/state','setting/city','setting','setting/fees/setting/list','setting/sms/setting/getway','setting/institute/sms-price','setting/sms/getway/list','setting/institute/campus/assign','setting/rights/role','setting/manage/users','setting/change/password','setting/performance/category'];

   $feesSOPSetup = [];
   $feesOperations = ['cadetfees/generate/fees','cadetfees/fees/collection'];
   $feesReports = [];

   $levelOfApprovalSOPSetup = ['levelofapproval'];
   $levelOfApprovalOperations = ['levelofapproval/alert/notification'];

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
                  @if(hasMenuAccess($menuAccess,$academicsSOPSetup) || hasMenuAccess($menuAccess,$academicsOperations) || hasMenuAccess($menuAccess,$academicsReports))
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
                                 class="fa fa-users"></i> Term</a></li>
                              @endif
                              @if(in_array('academics/division',$menuAccess))
                              <li><a href="{{url('academics/division')}}"><i
                                 class="fa fa-users"></i> Group</a></li>
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
                              @if(in_array('academics/physical/rooms',$menuAccess))
                              <li><a href="{{url('academics/physical/rooms')}}"><i
                                 class="fa fa-object-group"></i> Physical Rooms</a></li>
                              @endif
                              @if(hasMenuAccess($menuAccess, ['academics/exam-category/exam','academics/exam/marks']))
                              <li>
                                 <a href=""><i class="fa fa-graduation-cap"></i> Exam <span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('academics/exam-category/exam',$menuAccess))
                                    <li><a href="{{url('/academics/exam-category/exam')}}"><i
                                       class="fa fa-link"></i> Exam Set UP</a></li>
                                    @endif
                                    @if(in_array('academics/exam/marks',$menuAccess))
                                    <li><a href="{{url('/academics/exam/marks')}}"><i
                                       class="fa fa-question-circle"></i> Exam Marks</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$academicsOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('academics/manage/subject',$menuAccess))
                              <li><a href="{{url('academics/manage/subject')}}"><i class="fa fa-book "></i>Manage
                                 Academics</a>
                              </li>
                              @endif
                              @if(in_array('academics/manage.assessments',$menuAccess))
                              <li><a href="{{url('academics/manage/assessments/grade-setup')}}"><i
                                 class="fa fa-book "></i>Manage Assessments</a></li>
                              @endif
                              @if(in_array('academics/timetable',$menuAccess))
                              <li><a href="{{url('/academics/timetable/timetable')}}"><i
                                 class="fa fa-calendar"></i> Timetable</a>
                              </li>
                              @endif
                              @if(hasMenuAccess($menuAccess,['academics/exam/schedules','academics/exam/attendance','academics/exam/seatPlan','academics/exam/marks/entry','academics/exam/list']))
                              <li>
                                 <a href=""><i class="fa fa-graduation-cap"></i> Exam <span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('academics/exam/schedules',$menuAccess))
                                    <li><a href="{{url('/academics/exam/schedules')}}"><i
                                       class="fa fa-sort-alpha-asc"></i> Exam Schedule</a>
                                    </li>
                                    @endif
                                    @if(in_array('academics/exam/attendance',$menuAccess))
                                    <li><a href="{{url('/academics/exam/attendance')}}"><i
                                       class="fa fa-sort-alpha-asc"></i> Exam
                                       Attendance</a>
                                    </li>
                                    @endif
                                    @if(in_array('academics/exam/seatPlan',$menuAccess))
                                    <li><a href="{{url('/academics/exam/seatPlan')}}"><i
                                       class="fa fa-sort-alpha-asc"></i> Exam Seat Plan</a>
                                    </li>
                                    @endif
                                    @if(in_array('academics/exam/marks/entry',$menuAccess))
                                    <li><a href="{{url('/academics/exam/marks/entry')}}"><i
                                       class="fa fa-sort-alpha-asc"></i> Exam Marks
                                       Entry</a>
                                    </li>
                                    @endif
                                    @if(in_array('academics/exam/list',$menuAccess))
                                    <li><a href="{{url('/academics/exam/list')}}"><i
                                       class="fa fa-sort-alpha-asc"></i> Exam List</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$academicsReports))
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array("academics/exam/tabulation-sheet/exam",$menuAccess))
                              <li><a href="{{url('academics/exam/tabulation-sheet/exam')}}"><i class="fa fa-calendar"></i> Tabulation Sheet(Exam)</a></li>
                              @endif
                              @if(in_array("academics/exam/tabulation-sheet/term-summary",$menuAccess))
                              <li><a href="{{url('academics/exam/tabulation-sheet/term-summary')}}"><i class="fa fa-calendar"></i> Tabulation Sheet(Term) - Summary</a></li>                              
                              @endif
                              @if(in_array("academics/exam/tabulation-sheet/term",$menuAccess))
                              <li><a href="{{url('academics/exam/tabulation-sheet/term')}}"><i class="fa fa-calendar"></i> Tabulation Sheet(Term) - Details</a></li>                            
                              @endif
                              @if(in_array("academics/exam/tabulation-sheet/year",$menuAccess))
                              <li><a href="{{url('academics/exam/tabulation-sheet/year')}}"><i class="fa fa-calendar"></i> Tabulation Sheet(Year)</a></li>                              
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
                  @if(hasMenuAccess($menuAccess,['event/','event/marks']))
                  <li>
                     <a href="#"><span class="fa fa-users icon-margin"></span> Event <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(in_array('event/',$menuAccess))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/event/')}}"><i class="fa fa-user"></i> Set Up Event</a>
                              </li>
                           </ul>
                        </li>
                        @endif
                        @if(in_array('event/marks',$menuAccess))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/event/marks')}}"><i class="fa fa-user"></i> Event Marks</a>
                              </li>
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$humanResourceSOPSetup) || hasMenuAccess($menuAccess,$humanResourceOperations))
                  <li>
                     <a href="#"><span class="fa fa-users icon-margin"></span> Human Resource <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$humanResourceSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(hasMenuAccess($menuAccess,['employee/departments','employee/designations','employee/create','employee/import','employee/import.image']))
                              <li>
                                 <a href="#"><i class="fa fa-user"></i> Employee Register<span
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
                                    @if(in_array('employee/create',$menuAccess))
                                    <li><a href="/employee/create"><i class="fa fa-user-plus"></i> Add
                                       Employee</a>
                                    </li>
                                    @endif
                                    @if(in_array('employee/import',$menuAccess))
                                    <li><a href="/employee/import"><i class="fa fa-upload"></i> Import
                                       Employee</a>
                                    </li>
                                    @endif
                                    @if(in_array('employee/import.image',$menuAccess))
                                    <li><a href="/employee/import/images"><i class="fa fa-upload"></i>
                                       Import Photos</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('employee/employee/status',$menuAccess))
                              <li><a href="{{url('/employee/employee/status')}}"><i
                                 class="fa fa-user"></i> Employee Status</a></li>
                              @endif
                              @if(in_array('employee/shift-configuration',$menuAccess))
                              <li><a href="{{url('/employee/shift-configuration')}}"><i
                                 class="fa fa-share-square-o"></i> Shift Configuration</a></li>
                              @endif
                              @if(in_array('employee/holiday-calender',$menuAccess))
                              <li><a href="{{url('/employee/holiday-calender')}}"><i
                                 class="fa fa-calendar-times-o"></i> Holiday Calender</a></li>
                              @endif
                                 <li><a href="{{url('/employee/holiday-calender/assign')}}"><i
                                               class="fa fa-calendar-plus-o"></i> Holiday Calender Assign</a></li>
                              @if(in_array('employee/evaluations',$menuAccess))
                              <li><a href="{{url('/employee/evaluations')}}"><i class="fa fa-gears"></i>
                                 Evaluation Set Up </a>
                              </li>
                              @endif
                              @if(hasMenuAccess($menuAccess,['employee/manage/leave/type','employee/manage/leave/structure','employee/manage/leave/entitlement']))
                              <li>
                                 <a href="/employee/manage/leave/type"><i class="fa fa-file-text-o"></i>
                                 Leave Management<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('employee/leave/type',$menuAccess))
                                    <li><a href="/employee/leave/type"><i
                                       class="fa fa-life-bouy"></i> Leave Type</a></li>
                                    @endif
                                    @if(in_array('employee/leave/structure',$menuAccess))
                                    <li><a href="/employee/leave/structure"><i
                                       class="fa fa-clock-o"></i> Leave Structure</a></li>
                                    @endif
                                    <li><a href="/employee/leave/assign"><i class="fa fa-clock-o"></i> Leave Assign</a></li>
                                    @if(in_array('employee/manage/leave/entitlement',$menuAccess))
                                    <li><a href="/employee/manage/leave/entitlement"><i
                                       class="fa fa-plus-square"></i> Leave Entitlement</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('employee/employee-attendance-setting',$menuAccess))
                              <li><a href="/employee/employee-attendance-setting"><i
                                 class="glyphicon glyphicon-check"></i> Attendance
                                 Setting</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$humanResourceOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('employee/manage/teacher',$menuAccess))
                              <li><a href="{{url('/employee/manage/teacher')}}"><i
                                 class="fa fa-users"></i>  Teacher Register</a></li>
                              @endif
                              @if(in_array('employee/manage',$menuAccess))
                              <li><a href="/employee/manage"><i class="fa fa-reorder"></i> HR Register</a>
                              </li>
                              @endif
                              @if(in_array('employee/bulk-edit',$menuAccess))
                              <li><a href="/employee/bulk-edit"><i class="fa fa-reorder"></i> Profile Edit</a>
                              </li>
                              @endif
                              @if(in_array('employee/evaluation/view',$menuAccess))
                              <li><a href="{{url('/employee/evaluation/view')}}"><i
                                 class="fa fa-gears"></i> Evaluation </a></li>
                              @endif
                              @if(in_array('employee/evaluation/search/view',$menuAccess))
                              <li><a href="{{url('/employee/evaluation/search/view')}}"><i
                                 class="fa fa-gears"></i> Search Evaluation </a></li>
                              @endif
                              @if(in_array('employee/evaluation/history/view',$menuAccess))
                              <li><a href="{{url('/employee/evaluation/history/view')}}"><i
                                 class="fa fa-gears"></i> Evaluation History </a></li>
                              @endif
                              <li><a href="{{url('/employee/leave/application')}}"><i
                                 class="fa fa-list-alt"></i> Leave Applications</a></li>
                              @if(in_array('employee/manage/leave/application',$menuAccess))
                              <li><a href="{{url('/employee/manage/leave/application')}}"><i
                                 class="fa fa-list-alt"></i> Manage Leave Applications</a>
                              </li>
                              @endif
                              @if(in_array('employee/employee-attendance',$menuAccess))
                              <li><a href="/employee/employee-attendance"><i
                                 class="glyphicon glyphicon-check"></i> Take
                                 Attendance</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/employee/leave/status/report')}}"><i
                                            class="fa fa-list-alt"></i> Leave Status Report</a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  @endif
                  <li>
                     <a href="#"><span class="fa fa-user icon-margin"></span> Payroll <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$cadetsSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/payroll/salary/head"><i class="fa fa-sitemap"></i>
                                    Salary head</a>
                              </li>
                              <li><a href="/payroll/salary/grade"><i class="fa fa-signal"></i>
                                    Salary Grade</a>
                              <li><a href="/payroll/salary/structure"><i class="fa fa-signal"></i>
                                    Salary Structure</a>
                              </li>
                              <li><a href="/payroll/bank"><i class="fa fa-signal"></i>
                                    Bank</a>
                              </li>
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$cadetsOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/payroll/salary/assign"><i class="fa fa-signal"></i>
                                    Salary Assign</a>
                              </li>
                              <li><a href="/payroll/salary/deduction"><i class="fa fa-signal"></i>
                                    Salary Deduction</a>
                              </li>
                              <li><a href="/payroll/salary/generate"><i class="fa fa-signal"></i>
                                    Salary Generate</a>
                              </li>
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$cadetsReports))
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a>
                           <ul class="dropdown-menus">
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @if(hasMenuAccess($menuAccess,$cadetsSOPSetup) || hasMenuAccess($menuAccess,$cadetsOperations) || hasMenuAccess($menuAccess,$cadetsReports))
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
                              @if(in_array('student/club.set-up',$menuAccess))
                              <li><a href="/student/club/setup/enrollment"><i class="fa fa-users"></i>
                                 Club Setup</a>
                              </li>
                              @endif
                              @if(in_array('student/task-schedule',$menuAccess))
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
                        @endif
                        @if(hasMenuAccess($menuAccess,$cadetsOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('student/profile.create',$menuAccess))
                              <li><a href="/student/profile/create"><i class="fa fa-user-plus"></i> Cadet
                                 Enrollment</a>
                              </li>
                              @endif
                              @if(in_array('student/manage',$menuAccess))
                              <li><a href="/student/manage"><i class="fa fa-reorder"></i>Cadet Register</a>
                              </li>
                              @endif
                              @if(in_array('student/cadet-performance-bulk',$menuAccess))
                              <li><a href="/student/cadet-performance-bulk"><i class="fa fa-reorder"></i>
                                 Factor Entries</a>
                              </li>
                              @endif
                              @if(in_array('student/manage/status',$menuAccess))
                              <li><a href="/student/manage/status"><i class="fa fa-reorder"></i> Deactive
                                 Cadet</a>
                              </li>
                              @endif
                              @if(in_array('student/import',$menuAccess))
                              <li><a href="/student/import"><i class="fa fa-upload"></i> Import Cadet</a>
                              </li>
                              @endif
                              @if(in_array('student/cadet/bulk/edit',$menuAccess))
                              <li><a href="/student/cadet/bulk/edit"><i class="fa fa-upload"></i> Profile Edit</a>
                              </li>
                              @endif
                              @if(in_array('student/import.image',$menuAccess))
                              <li><a href="/student/import/images"><i class="fa fa-upload"></i> Import
                                 Photos</a>
                              </li>
                              @endif
                              @if(in_array('student/promote',$menuAccess))
                              <li><a href="/student/promote"><i class="fa fa-exchange"></i> Promote Cadet</a>
                              </li>
                              @endif
                              @if(in_array('student/parent/manage',$menuAccess))
                              <li><a href="/student/parent/manage"><i class="fa fa-users"></i> Manage
                                 Parents</a>
                              </li>
                              @endif
                              @if(in_array('academics/manage.attendance',$menuAccess))
                              <li><a href="{{url('academics/manage/attendance/manage')}}"><i
                                 class="fa fa-book "></i>Manage Attendance</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$cadetsReports))
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('student/detail/reports',$menuAccess))
                              <li><a href="/student/detail/reports"><i class="fa fa-user-plus"></i> Cadet Detail Report</a></li>
                              @endif
                              @if(in_array('student/transcript/reports',$menuAccess))
                              <li><a href="/student/transcript/reports"><i class="fa fa-user-plus"></i> Cadet Transcript Report</a></li>
                              @endif
                              @if(in_array('student/remarks',$menuAccess))
                              <li><a href="/student/remarks"><i class="fa fa-user-plus"></i> Cadet Remarks & Advice</a></li>
                              @endif
                              @if(in_array('student/warnings',$menuAccess))
                              <li><a href="/student/warnings"><i class="fa fa-user-plus"></i> Cadet Warnings</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$feesSOPSetup) || hasMenuAccess($menuAccess,$feesOperations) || hasMenuAccess($menuAccess,$feesReports))
                     <li><a href="/fees/dashboard/panel"><span class="fa fa-money icon-margin"></span> Fees <span class="caret"></span></a>

                        <ul class="dropdown-menus">
                          @if(hasMenuAccess($menuAccess,$feesSOPSetup))
                           <li>
                              <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                           </li>
                           @endif
                          @if(hasMenuAccess($menuAccess,$feesOperations))
                           <li>
                              <a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 @if(in_array('cadetfees/generate/fees',$menuAccess))
                                 <li><a href="/cadetfees/generate/fees"><i class="fa fa-money"></i> Fees Generate</a></li>
                                 @endif
                                 @if(in_array('cadetfees/fees/collection',$menuAccess))
                                 <li><a href="/cadetfees/fees/collection"><i class="fa fa-money"></i> Fees Collection</a></li>
                                 @endif
                              </ul>
                           </li>
                          @endif
                          @if(hasMenuAccess($menuAccess,$feesReports))
                           <li>
                              <a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a>
                           </li>
                          @endif
                        </ul>

                     </li>
                  @endif

                  @if(hasMenuAccess($menuAccess,$communicationSOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-comment icon-margin"></span> Communication <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$communicationSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(hasMenuAccess($menuAccess,['communication/sms/sms_credit', 'communication/sms/sms_log','communication/sms/group','communication/sms/pending_sms','communication/sms/template','communication/sms/template/list']))
                              <li>
                                 <a href="javascript:void(0);"><i class="fa fa-comment"></i> SMS<span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('communication/sms/sms_credit',$menuAccess))
                                    <li><a href="/communication/sms/sms_credit"><i
                                       class="fa fa-comments-o"></i>SMS Credit</a></li>
                                    @endif
                                    @if(in_array('communication/sms/sms_log',$menuAccess))
                                    <li><a href="/communication/sms/sms_log"><i
                                       class="fa fa-comments-o"></i> SMS Log</a></li>
                                    @endif
                                    @if(in_array('communication/sms.group',$menuAccess))
                                    <li><a href="/communication/sms/group"><i
                                       class="fa fa-comments-o"></i> Group SMS</a></li>
                                    @endif
                                    @if(in_array('communication/sms/pending_sms',$menuAccess))
                                    <li><a href="/communication/sms/pending_sms"><i
                                       class="fa fa-comments-o"></i> Pending SMS</a>
                                    </li>
                                    @endif
                                    @if(in_array('communication/sms/template',$menuAccess))
                                    <li><a href="/communication/sms/template"><i
                                       class="fa fa-comments-o"></i> Template SMS</a></li>
                                    @endif
                                    @if(in_array('communication/sms/template/list',$menuAccess))
                                    <li><a href="/communication/sms/template/list"><i
                                       class="fa fa-comments-o"></i> Template SMS List</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('communication/notice',$menuAccess))
                              <li>
                                 <a href="javascript:void(0);"><i class="fa fa-comment"></i> Notice<span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/communication/notice/"><i
                                       class="fa fa-comments-o"></i> Notice </a></li>
                                 </ul>
                              </li>
                              @endif
                              @if(hasMenuAccess($menuAccess,['communication/telephone-diary/student-contact', 'communication/telephone-diary/employee-contact']))
                              <li>
                                 <a href="#"><i class="fa fa-phone"></i> Telephone Diary<span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('communication/telephone-diary/student-contact',$menuAccess))
                                    <li><a href="/communication/telephone-diary/student-contact"><i
                                       class="fa fa-phone-square"></i> Student Contact</a>
                                    </li>
                                    @endif
                                    @if(in_array('communication/telephone-diary/employee-contact',$menuAccess))
                                    <li><a href="/communication/telephone-diary/employee-contact"><i
                                       class="fa fa-phone-square"></i> Employee Contact</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$reportAndPrintingSOPSetup))
                  <li>
                     <a href="#"><span class="fa fa-line-chart icon-margin"></span> Reports and Printing
                     <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$reportAndPrintingSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(hasMenuAccess($menuAccess,['reports/academics']))
                              <li>
                                 <a href="/reports/academics"><i
                                    class="fa fa-line-chart"></i>Academics<span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('reports/academics',$menuAccess))
                                    <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i>
                                       Student Reports</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/academics',$menuAccess))
                                    <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i>
                                       Teacher Reports</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/academics',$menuAccess))
                                    <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i>
                                       Parents Reports</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/id-card',$menuAccess))
                                    <li><a href="/reports/id-card"><i class="fa fa-bar-chart"></i> ID
                                       Card</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/sitplan',$menuAccess))
                                    <li><a href="/reports/sitplan"><i class="fa fa-bar-chart"></i> Seat
                                       Plan</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/admit-card',$menuAccess))
                                    <li><a href="/reports/admit-card"><i class="fa fa-bar-chart"></i>
                                       Admit Card</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/documents#transfer_certificate',$menuAccess))
                                    <li><a href="/reports/documents#transfer_certificate"><i
                                       class="fa fa-bar-chart"></i> Transfer
                                       Certificate</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/documents',$menuAccess))
                                    <li><a href="/reports/documents"><i class="fa fa-bar-chart"></i>
                                       Testimonial</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('reports/attendance', $menuAccess))
                              <li>
                                 <a href="/reports/attendance"><i class="fa fa-building-o"></i>
                                 Attendance<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/reports/attendance')}}"><i
                                       class="fa fa-users"></i> Student Attendance</a></li>
                                    <li><a href="{{url('/report/attendance')}}"><i
                                       class="fa fa-users"></i> Class Section
                                       Attendance</a>
                                    </li>
                                    <li><a href="{{url('/report/attendance')}}"><i
                                       class="fa fa-users"></i> Student Absent Days Report</a>
                                    </li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('reports/result', $menuAccess))
                              <li>
                                 <a href="/reports/result"><i class="fa fa-check-square-o"></i>
                                 Result<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/reports/result"><i class="fa fa-file"></i> Report Card
                                       (Details)</a>
                                    </li>
                                    <li><a href="/reports/result"><i class="fa fa-file"></i> Report Card
                                       (Summary)</a>
                                    </li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('reports/admission', $menuAccess))
                              <li>
                                 <a href="/reports/admission"><i class="fa fa-calendar-o"
                                    aria-hidden="true"></i> Admission<span
                                    class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/reports/admission"><i class="fa fa-bank"
                                       aria-hidden="true"></i>
                                       Admission Reports</a>
                                    </li>
                                 </ul>
                              </li>
                              @endif
                              @if(hasMenuAccess($menuAccess,['reports/fees','fees/report/date-wise-fees','reports/invoice','fees/report/index']))
                              <li>
                                 <a href="/reports/fees"><i class="fa fa-check-square-o"></i> Fees And
                                 Invoice<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('fees/report/date-wise-fees', $menuAccess))
                                    <li><a href="/fees/report/date-wise-fees"><i
                                       class="fa fa-money"></i>Fees Reports (Daily)</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/fees', $menuAccess))
                                    <li><a href="/reports/fees"><i class="fa fa-money"></i> Fees Report
                                       (Details)</a>
                                    </li>
                                    @endif
                                    @if(in_array('reports/invoice', $menuAccess))
                                    <li><a href="/reports/invoice"><i class="fa fa-money"></i> Invoice
                                       Report</a>
                                    </li>
                                    @endif
                                    @if(in_array('fees/report/index', $menuAccess))
                                    <li><a href="/fees/report/index"><i class="fa fa-money"></i> Fees
                                       Reports (Level Wise)</a>
                                    </li>
                                    @endif
                                 </ul>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                           class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$accountsSOPSetup) || hasMenuAccess($menuAccess,$accountsOperations))
                  <li>
                     <a href="#"><span class="fa fa-address-book icon-margin"></span> Accounts <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$accountsSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('accounts/accounts-configuration', $menuAccess))
                              <li><a href="{{url('/accounts/accounts-configuration')}}"><i class="fa fa-sitemap"></i> Accounts Configuration</a></li>
                              @endif
                              @if(in_array('accounts/fiscal-year', $menuAccess))
                              <li><a href="{{url('/accounts/fiscal-year')}}"><i class="fa fa-calendar"></i> Fiscal Year</a></li>
                              @endif
                              @if(in_array('accounts/voucher-config-list', $menuAccess))
                              <li><a href="{{url('/accounts/voucher-config-list')}}"><i class="fa fa-cogs"></i> Voucher Configuration</a></li>
                              @endif
                              @if(in_array('accounts/chart-of-accounts', $menuAccess))
                              <li><a href="{{url('/accounts/chart-of-accounts')}}"><i class="fa fa-pie-chart"></i> Chart of Accounts</a></li>
                              @endif
                              @if(in_array('accounts/budget-allocation', $menuAccess))
                              <li><a href="{{url('/accounts/budget-allocation')}}"><i class="fa fa-line-chart"></i> Budget Allocation</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$accountsOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li>
                                 <a href=""><i class="fa fa-sitemap"></i> Voucher <span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('accounts/payment-voucher', $menuAccess))
                                    <li><a href="{{url('/accounts/payment-voucher')}}"><i class="fa fa-book"></i> Payment Voucher</a></li>
                                    @endif
                                    @if(in_array('accounts/receive-voucher', $menuAccess))
                                    <li><a href="{{url('/accounts/receive-voucher')}}"><i class="fa fa-book"></i> Receive Voucher</a></li>
                                    @endif
                                    @if(in_array('accounts/journal-voucher', $menuAccess))
                                    <li><a href="{{url('/accounts/journal-voucher')}}"><i class="fa fa-book"></i> Journal Voucher</a></li>
                                    @endif
                                    @if(in_array('accounts/contra-voucher', $menuAccess))
                                    <li><a href="{{url('/accounts/contra-voucher')}}"><i class="fa fa-bookk"></i> Contra Voucher</a></li>
                                    @endif
                                 </ul>
                              </li>
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$houseSOPSetup) || hasMenuAccess($menuAccess,$houseOperations))
                  <li>
                     <a href="#"><span class="fa fa-home icon-margin"></span> House <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$houseSOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('house/manage-house', $menuAccess))
                                 <li><a href="{{url('/house/manage-house')}}"><i class="fa fa-cogs"></i>
                                    Manage House</a>
                                 </li>
                              @endif
                              @if(in_array('house/assign/students/page', $menuAccess))
                                 <li><a href="{{url('/house/assign/students/page')}}"><i class="fa fa-users"></i>
                                    Assign Students</a>
                                 </li>
                              @endif
                              @if(in_array('house/appoints', $menuAccess))
                                 <li><a href="{{url('/house/house-appoints')}}"><i class="fa fa-cogs"></i>
                                    House Appoints</a>
                                 </li>
                              @endif
                              @if(in_array('house/pocket-money', $menuAccess))
                                 <li><a href="/house/pocket-money"><i class="fa fa-money"></i> 
                                    Pocket Money</a>
                                 </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$houseOperations))
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                           class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('house/view', $menuAccess))
                              <li><a href="{{url('/house/view')}}"><i class="fa fa-eye"></i> View
                                 Houses</a>
                              </li>
                              @endif
                              @if(in_array('house/cadets-evaluation', $menuAccess))
                              <li><a href="{{url('/house/cadets-evaluation')}}"><i
                                 class="fa fa-sitemap"></i> Evaluation</a></li>
                              @endif
                              @if(in_array('house/communication-records', $menuAccess))
                              <li><a href="{{url('/house/communication-records')}}"><i class="fa fa-commenting-o"></i> Communication Record</a></li>
                              @endif
                              @if(in_array('house/record-score', $menuAccess))
                              <li><a href="{{url('/house/record-score/')}}"><i class="fa fa-sitemap"></i> Record Score</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$inventorySOPSetup) || hasMenuAccess($menuAccess,$inventoryOperations))
                  <li>
                     <a href="#"><span class="fa fa-wrench icon-margin"></span> Inventory <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$inventorySOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(hasMenuAccess($menuAccess,['inventory/stock-group-grid','inventory/stock-category','inventory/unit-of-measurement','inventory/stock-list', 'inventory/stock-item-serial']))
                              <li>
                                 <a href="#"><i class="fa fa-stack-exchange"></i> Stock<span class="caret"></span></a>
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
                                 <a href="#"><i class="fa fa-hospital-o"></i> Store<span class="caret"></span></a>
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
                              <li><a href="{{url('/inventory/price-catalogue')}}"><i class="fa fa-money"></i> Price Catalogue</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$inventoryOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('inventory/new-requisition', $menuAccess))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Requisition<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/new-requisition')}}"> New Requisition</a></li>
                                 </ul>
                              </li>
                              @endif
                              @if(in_array('inventory/issue-inventory', $menuAccess))
                              <li><a href="{{url('/inventory/issue-inventory')}}"><i class="fa fa-building"></i> Issue from Inventory</a></li>
                              @endif
                              <!-- <li><a href="{{url('/inventory/store-transfer-requisition')}}"><i class="fa fa-building"></i> Store Transfer Requisition</a></li>
                                 <li><a href="{{url('/inventory/store-transfer')}}"><i class="fa fa-building"></i> Store Transfer</a></li> -->
                              @if(hasMenuAccess($menuAccess,['inventory/purchase-requisition','inventory/comparative-statement','inventory/purchase-order','inventory/purchase-receive','inventory/purchase-invoice']))
                              <li>
                                 <a href="#"><i class="fa fa-building"></i> Purchase<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    @if(in_array('inventory/purchase-requisition', $menuAccess))
                                    <li><a href="{{url('/inventory/purchase-requisition')}}"> Purchase Requisition</a></li>
                                    @endif
                                    @if(in_array('inventory/comparative-statement', $menuAccess))
                                    <li><a href="{{url('/inventory/comparative-statement')}}">Comparative Statement</a></li>
                                    @endif
                                    @if(in_array('inventory/purchase-order', $menuAccess))
                                    <li><a href="{{url('/inventory/purchase-order')}}"> Purchase order</a></li>
                                    @endif
                                    @if(in_array('inventory/purchase-receive', $menuAccess))
                                    <li><a href="{{url('/inventory/purchase-receive')}}"> Purchase Receive</a></li>
                                    @endif
                                    @if(in_array('inventory/purchase-invoice', $menuAccess))
                                    <li><a href="{{url('/inventory/purchase-invoice')}}"> Purchase Invoice</a></li>
                                    @endif
                                    <!-- <li><a href="{{url('/inventory/purchase-return')}}"> Purchase Return</a></li> -->
                                 </ul>
                              </li>
                              @endif
                              <!-- <li><a href="#"><i class="fa fa-building"></i> Sales<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/sales-order')}}"> Sales Order</a></li>
                                    <li><a href="{{url('/inventory/sales-challan')}}"> Sales Chalan</a></li>
                                 </ul>
                                 </li> -->
                              @if(in_array('inventory/stock-in', $menuAccess))
                              <li><a href="{{url('/inventory/stock-in')}}"><i class="fa fa-building"></i> Stock In</a></li>
                              @endif
                              @if(in_array('inventory/stock-out', $menuAccess))
                              <li><a href="{{url('/inventory/stock-out')}}"><i class="fa fa-building"></i> Stock Out</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$healthCareSOPSetup) || hasMenuAccess($menuAccess,$healthCareOperations))
                  <li>
                     <a href="#"><span class="fa fa-hospital-o icon-margin"></span> Health Care <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(in_array('healthcare/investigation', $menuAccess))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/healthcare/investigation')}}"><i
                                 class="fa fa-sitemap"></i> List Of Investigation</a></li>
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,['healthcare/prescription','healthcare/drug/reports','healthcare/investigation/reports']))
                        <li><a href="#"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Operations <span
                           class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('healthcare/prescription', $menuAccess))
                              <li><a href="{{url('/healthcare/prescription')}}"><i
                                 class="fa fa-book"></i> Prescription</a></li>
                              @endif
                              @if(in_array('healthcare/drug/reports', $menuAccess))
                              <li><a href="{{url('/healthcare/drug/reports')}}"><i
                                 class="fa fa-dropbox"></i> Drug Reports</a></li>
                              @endif
                              @if(in_array('healthcare/investigation/reports', $menuAccess))
                              <li><a href="{{url('/healthcare/investigation/reports')}}"><i
                                 class="fa fa-sitemap"></i> Investigation Reports</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$messSOPSetup) || hasMenuAccess($menuAccess,$messOperations))
                  <li>
                     <a href="#"><span class="fa fa-th-large icon-margin"></span> Mess <span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(in_array('mess/table', $menuAccess))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/mess/table')}}"><i class="fa fa-sitemap"></i> Table</a>
                              </li>
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$messOperations))
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                           class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('mess/food-menu', $menuAccess))
                              <li><a href="{{url('/mess/food-menu')}}"><i class="fa fa-cutlery"></i> Food
                                 Menu</a>
                              </li>
                              @endif
                              @if(in_array('mess/food-menu-schedule', $menuAccess))
                              <li><a href="{{url('/mess/food-menu-schedule')}}"><i
                                 class="fa fa-calendar"></i> Food Menu Schedule</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$canteenSOPSetup) || hasMenuAccess($menuAccess,$canteenOperations))
                  <li><a href="#"><span class="fa fa-coffee icon-margin"></span> Canteen <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                           @if(hasMenuAccess($menuAccess,$canteenSOPSetup))
                              <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 @if(in_array('canteen/menu-recipe', $menuAccess))
                                 <li><a href="{{url('/canteen/menu-recipe')}}"><i class="fa fa-sitemap"></i> Menu & Recipe</a></li>
                                 @endif
                              </ul>
                           </li>
                           @endif

                           @if(hasMenuAccess($menuAccess,$canteenOperations))
                              <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('canteen/stock-in', $menuAccess))
                                 <li><a href="{{url('/canteen/stock-in')}}"><i class="fa fa-exchange"></i> Make & Stock-In</a></li>
                              @endif
                                 @if(in_array('canteen/transaction', $menuAccess))
                                 <li><a href="{{url('/canteen/transaction')}}"><i class="fa fa-truck"></i> Transaction</a></li>
                                 @endif
                                 @if(in_array('canteen/customer-processing', $menuAccess))
                              <li><a href="{{url('/canteen/customer-processing')}}"><i class="fa fa-spinner"></i> Customer Processing</a></li>
                                 @endif
                           </ul>
                        </li>
                           @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$librarySOPSetup) || hasMenuAccess($menuAccess,$libraryOperations))
                  <li>
                     <a href=""><span class="fa fa-university icon-margin"> </span>Library<span
                        class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$librarySOPSetup))
                        <li>
                           <a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup
                           <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('library/library-book-category/index', $menuAccess))
                              <li><a href="/library/library-book-category/index"><i
                                 class="fa fa-sort-alpha-asc"></i> Book Category</a></li>
                              @endif
                              @if(in_array('library/library-book-shelf/index', $menuAccess))
                              <li><a href="/library/library-book-shelf/index"><i
                                 class="glyphicon glyphicon-object-align-bottom"></i>Book
                                 Shelf</a>
                              </li>
                              @endif
                              @if(in_array('library/library-cupboard-shelf/index', $menuAccess))
                              <li><a href="/library/library-cupboard-shelf/index"><i
                                 class="glyphicon glyphicon-equalizer"></i> Cup Board
                                 Shelf</a>
                              </li>
                              @endif
                              @if(in_array('library/library-book-vendor/index', $menuAccess))
                              <li><a href="/library/library-book-vendor/index"><i
                                 class="fa fa-cart-plus"></i> Book Vendor</a></li>
                              @endif
                              @if(in_array('library/library-book-status/index', $menuAccess))
                              <li><a href="/library/library-book-status/index"><i
                                 class="glyphicon glyphicon-tag"></i> Book Status</a></li>
                              @endif
                              @if(in_array('library/library-book/index', $menuAccess))
                              <li><a href="/library/library-book/index"><i
                                 class="glyphicon glyphicon-book"></i> Books</a></li>
                              @endif
                              @if(in_array('library/library-fine-master/index', $menuAccess))
                              <li><a href="/library/library-fine-master/index"><i class="fa fa-eject"></i>
                                 Fine</a>
                              </li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$libraryOperations))
                        <li>
                           <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Operations <span
                              class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('library/library-borrow-transaction/index', $menuAccess))
                              <li><a href="/library/library-borrow-transaction/index"><i
                                 class="fa fa-book"></i> Issue Book</a></li>
                              @endif
                              @if(in_array('library/library-borrow-transaction/borrower', $menuAccess))
                              <li><a href="/library/library-borrow-transaction/borrower"><i
                                 class="fa fa-reply-all"></i> Return/Renew Book</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span
                           class="caret"></span></a></li>
                     </ul>
                  </li>
                  @endif
                  @if(hasMenuAccess($menuAccess,$roleUserSOPSetup) || $userRole == 'super-admin')
                  <li><a href="#"><span class="fa fa-users icon-margin"></span> User Role Management <span class="caret"></span></a>
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
                  @if(hasMenuAccess($menuAccess,$levelOfApprovalSOPSetup) || hasMenuAccess($menuAccess,$levelOfApprovalOperations))
                  <li><a href="#"><span class="fa fa-thumbs-up icon-margin"></span> Level Of Approval <span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        @if(hasMenuAccess($menuAccess,$levelOfApprovalSOPSetup))
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('levelofapproval', $menuAccess))
                                 <li><a href="{{ url('levelofapproval') }}"><i class="fa fa-sitemap"></i> Level Of Approval</a></li>
                              @endif
                           </ul>
                        </li>
                        @endif
                        @if(hasMenuAccess($menuAccess,$levelOfApprovalOperations))
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              @if(in_array('levelofapproval/alert/notification', $menuAccess))
                              <li><a href="{{ url('levelofapproval/alert/notification') }}"><i class="fa fa-sitemap"></i> Alert Notification</a></li>
                              @endif
                           </ul>   
                        </li>
                        @endif
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
            @if(in_array('levelofapproval/alert/notification', $menuAccess))
               <li>
                  <a href="{{ url('/levelofapproval/alert/notification') }}" class="main-menu-style" target="_blank">
                     <i class="fa fa-bell"></i>
                  </a>   
               </li>
            @endif
         </ul>
         @endif
      </div>
   </div>
</nav>
<!--/.nav-collapse -->
<div class=" clearfix"></div>