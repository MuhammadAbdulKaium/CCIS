<?php

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'employee', 'namespace' => 'Modules\Employee\Http\Controllers'], function () {

    // Employee Information Bulk Edit 
    Route::get('/bulk-edit','EmployeeBulkEditController@index');
    Route::get('/bulk-edit/search','EmployeeBulkEditController@employeeSearch');
    Route::post('/bulk/edit/save','EmployeeBulkEditController@employeEdit');
    Route::get('/class/fees','EmployeeBulkEditController@classFess');

    // employee index
    Route::get('/', 'EmployeeController@index');

    // manage employees
    Route::get('/manage', 'EmployeeController@manageEmployee')->name('manage-hr');
    Route::post('/manage/search', 'EmployeeController@searchEmployee');
    Route::post('/manage/searchNew', 'EmployeeController@newSearchEmployee');
    Route::post('/employee/search', 'EmployeeLeaveController@searchEmployee');
    Route::post('/manage/search/payroll', 'EmployeeController@searchEmployeePayroll');
    Route::post('/manage/search/payroll/assign', 'EmployeeController@searchEmployeePayrollAssign');
    Route::post('/manage/search/payroll/generate', 'EmployeeController@searchEmployeePayrollGenerate');
    Route::post('/manage/search/payroll/deduction', 'EmployeeController@searchEmployeePayrollDeduction');
    Route::post('/leave/status/search', 'EmployeeLeaveController@searchEmployeeLeaveStatus');
    Route::get('/single/leave/status/{id}', 'EmployeeLeaveController@singleEmployeeLeaveStatus');
    Route::post('/search/leave/encashment', 'EmployeeLeaveController@searchEmployeeEncahment');
    Route::post('/assign/form/submit', 'EmployeeLeaveController@assignSubmitEmployee');

    Route::get('import/images', ['access' => ['employee/import.image'], 'uses' => 'EmployeeController@imagePage']);
    Route::post('image/import/upload', ['access' => ['employee/import.image'], 'uses' => 'EmployeeController@imageUpload']);

    Route::get('show/childs/{id}', 'EmployeeController@showChilds');



    // employee download


    Route::post('/manage/download/excel', 'EmployeeController@searchEmployeeDownload');


    // manage teacher
    Route::get('/manage/teacher', 'EmployeeController@manageTeacher');
    Route::post('/manage/teacher', 'EmployeeController@findTeacherList');

    // employee create
    Route::get('/create', ['access' => ['employee/create'], 'uses' => 'EmployeeController@createEmployee']);
    // employee store
    Route::post('/store', ['access' => ['employee/create'], 'uses' => 'EmployeeController@storeEmployee']);

    // employee info like email and photo
    Route::get('/email/edit/{id}', 'EmployeeInfoController@employeeEmailEdit');
    Route::post('/email/update/{id}', 'EmployeeInfoController@employeeEmailUpdate');
    Route::get('/photo/edit/{id}', 'EmployeeInfoController@employeePhotoEdit');
    Route::post('/photo/store', 'EmployeeInfoController@storeEmployeePhoto');
    Route::post('/photo/update/{id}', 'EmployeeInfoController@employeePhotoUpdate');

    // employee Discipline
    Route::get('/profile/discipline/{id}', ['access' => ['employee/discipline'], 'uses' => 'EmployeeDisciplineController@index']);
    Route::get('/profile/create/discipline/{id}', ['access' => ['employee/discipline.create'], 'uses' => 'EmployeeDisciplineController@create']);
    Route::post('/profile/store/discipline', ['access' => ['employee/discipline.create'], 'uses' => 'EmployeeDisciplineController@store']);
    Route::get('/profile/edit/discipline/{id}', ['access' => ['employee/discipline.edit'], 'uses' => 'EmployeeDisciplineController@edit']);
    Route::post('/profile/update/discipline/{id}', ['access' => ['employee/discipline.edit'], 'uses' => 'EmployeeDisciplineController@update']);
    Route::get('/profile/delete/discipline/{id}', ['access' => ['employee/discipline.delete'], 'uses' => 'EmployeeDisciplineController@delete']);
    
    // employee training
    Route::get('/profile/training/{id}', ['access' => ['employee/training'], 'uses' => 'EmployeeTrainingController@index']);
    Route::get('/profile/create/training/{id}', ['access' => ['employee/training.create'], 'uses' => 'EmployeeTrainingController@create']);
    Route::post('/profile/store/training', ['access' => ['employee/training.create'], 'uses' => 'EmployeeTrainingController@store']);
    Route::get('/profile/edit/training/{id}', ['access' => ['employee/training.edit'], 'uses' => 'EmployeeTrainingController@edit']);
    Route::post('/profile/update/training/{id}', ['access' => ['employee/training.edit'], 'uses' => 'EmployeeTrainingController@update']);
    Route::get('/profile/delete/training/{id}', ['access' => ['employee/training.delete'], 'uses' => 'EmployeeTrainingController@delete']);
    
    // employee special duty
    Route::get('/profile/special-duty/{id}', ['access' => ['employee/special-duty'], 'uses' => 'EmployeeSpecialDutyController@index']);
    Route::get('/profile/create/special-duty/{id}', ['access' => ['employee/special-duty.create'], 'uses' => 'EmployeeSpecialDutyController@create']);
    Route::post('/profile/store/special-duty', ['access' => ['employee/special-duty.create'], 'uses' => 'EmployeeSpecialDutyController@store']);
    Route::get('/profile/edit/special-duty/{id}', ['access' => ['employee/special-duty.edit'], 'uses' => 'EmployeeSpecialDutyController@edit']);
    Route::post('/profile/update/special-duty/{id}', ['access' => ['employee/special-duty.edit'], 'uses' => 'EmployeeSpecialDutyController@update']);
    Route::get('/profile/delete/special-duty/{id}', ['access' => ['employee/special-duty.delete'], 'uses' => 'EmployeeSpecialDutyController@delete']);

    // employee Publication
    Route::get('/profile/publication/{id}', ['access' => ['employee/publication'], 'uses' => 'EmployeePublicationController@index']);
    Route::get('/profile/create/publication/{id}', ['access' => ['employee/publication.create'], 'uses' => 'EmployeePublicationController@create']);
    Route::post('/profile/store/publication', ['access' => ['employee/publication.create'], 'uses' => 'EmployeePublicationController@store']);
    Route::get('/profile/edit/publication/{id}', ['access' => ['employee/publication.edit'], 'uses' => 'EmployeePublicationController@edit']);
    Route::post('/profile/update/publication/{id}', ['access' => ['employee/publication.edit'], 'uses' => 'EmployeePublicationController@update']);
    Route::get('/profile/delete/publication/{id}', ['access' => ['employee/publication.delete'], 'uses' => 'EmployeePublicationController@delete']);

    // employee EmployeeAcrController
    Route::get('/profile/acr/{id}', ['access' => ['employee/acr'], 'uses' => 'EmployeeAcrController@index']);
    Route::get('/profile/create/acr/{id}', ['access' => ['employee/acr.create'], 'uses' => 'EmployeeAcrController@create']);
    Route::post('/profile/store/acr', ['access' => ['employee/acr.create'], 'uses' => 'EmployeeAcrController@store']);
    Route::get('/profile/edit/acr/{id}', ['access' => ['employee/acr.edit'], 'uses' => 'EmployeeAcrController@edit']);
    Route::post('/profile/update/acr/{id}', ['access' => ['employee/acr.edit'], 'uses' => 'EmployeeAcrController@update']);
    Route::get('/profile/delete/acr/{id}', ['access' => ['employee/acr.delete'], 'uses' => 'EmployeeAcrController@delete']);

    // employee EmployeeContributionBoardResultController
    Route::get('/profile/contribution-board-result/{id}', ['access' => ['employee/contribution-board-result'], 'uses' => 'EmployeeContributionBoardResultController@index']);
    Route::get('/profile/create/contribution-board-result/{id}', ['access' => ['employee/contribution-board-result.create'], 'uses' => 'EmployeeContributionBoardResultController@create']);
    Route::post('/profile/store/contribution-board-result', ['access' => ['employee/contribution-board-result.create'], 'uses' => 'EmployeeContributionBoardResultController@store']);
    Route::get('/profile/edit/contribution-board-result/{id}', ['access' => ['employee/contribution-board-result.edit'], 'uses' => 'EmployeeContributionBoardResultController@edit']);
    Route::post('/profile/update/contribution-board-result/{id}', ['access' => ['employee/contribution-board-result.edit'], 'uses' => 'EmployeeContributionBoardResultController@update']);
    Route::get('/profile/delete/contribution-board-result/{id}', ['access' => ['employee/contribution-board-result.delete'], 'uses' => 'EmployeeContributionBoardResultController@delete']);

    // Employee Seniority List Report
    Route::get('/seniority/list/report','EmployeeSeniorityListController@index');
    Route::post('/seniority/list/report/submit','EmployeeSeniorityListController@store');

    // Employee Profile Details Report
    Route::get('/profile/details/report','EmployeeProfileDetailsReportController@index')->name('employee.profile-details');
    Route::get('/profile/details/report/search','EmployeeProfileDetailsReportController@searchEmployeeReport');
    Route::get('/profile/details/report/search-employee','EmployeeProfileDetailsReportController@searchEmployee');
    
    

    // employee personal
    Route::get('/profile/personal/{id}', ['access' => ['employee/profile'], 'uses' => 'EmployeeInfoController@showEmployeeInfo']);
    Route::get('/profile/personal/edit/{id}', ['access' => ['employee/profile.edit'], 'uses' => 'EmployeeInfoController@editEmployeeInfo']);
    Route::post('/profile/personal/update/{id}', ['access' => ['employee/profile.edit'], 'uses' => 'EmployeeInfoController@updateEmployeeInfo']);

    // employee addresses
    Route::get('/profile/address/{id}', ['access' => ['employee/address'], 'uses' => 'EmployeeAddressController@showEmployeeAddress']);
    Route::post('/profile/address/store', ['access' => ['employee/address.create'], 'uses' => 'EmployeeAddressController@storeEmployeeAddress']);
    Route::get('/profile/address/edit/{id}', ['access' => ['employee/address.edit'], 'uses' => 'EmployeeAddressController@editEmployeeAddress']);
    Route::post('/profile/address/update/{id}', ['access' => ['employee/address.edit'], 'uses' => 'EmployeeAddressController@updateEmployeeAddress']);

    // employee guardian
    Route::get('/profile/guardian/{id}', ['access' => ['employee/family'], 'uses' => 'EmployeeGuardianController@show']);
    Route::post('/profile/guardian/store', ['access' => ['employee/family.create'], 'uses' => 'EmployeeGuardianController@store']);
    Route::get('/profile/guardian/create/{id}', ['access' => ['employee/family.create'], 'uses' => 'EmployeeGuardianController@create']);
    Route::get('/profile/guardian/edit/{id}', ['access' => ['employee/family.edit'], 'uses' => 'EmployeeGuardianController@edit']);
    Route::post('/profile/guardian/update/{id}', ['access' => ['employee/family.edit'], 'uses' => 'EmployeeGuardianController@update']);
    Route::get('/profile/guardian/delete/{id}', ['access' => ['employee/family.delete'], 'uses' => 'EmployeeGuardianController@destroy']);
    Route::get('/profile/guardian/emergency/{id}', ['access' => ['employee/family.emergency'], 'uses' => 'EmployeeGuardianController@status']);

    // employee document
    Route::get('/profile/document/{id}', ['access' => ['employee/document'], 'uses' => 'EmployeeAttachmentController@show']); //home
    Route::get('/profile/document/create/{id}', ['access' => ['employee/document.create'], 'uses' => 'EmployeeAttachmentController@create']);
    Route::post('/profile/documents/store', ['access' => ['employee/document.create'], 'uses' => 'EmployeeAttachmentController@store']);
    Route::get('/profile/documents/edit/{id}', ['access' => ['employee/document.edit'], 'uses' => 'EmployeeAttachmentController@edit']);
    Route::post('/profile/documents/update/{id}', ['access' => ['employee/document.edit'], 'uses' => 'EmployeeAttachmentController@update']);
    Route::get('/profile/documents/delete/{id}', ['access' => ['employee/document.delete'], 'uses' => 'EmployeeAttachmentController@destroy']);
    Route::get('/profile/documents/status/{id}/{status}', 'EmployeeAttachmentController@status');

    // employee qualification
    Route::get('/profile/qualification/{id}', ['access' => ['employee/qualification'], 'uses' => 'EmployeeQualificationController@index']);
    Route::get('/profile/create/qualification/{id}', ['access' => ['employee/qualification.create'], 'uses' => 'EmployeeQualificationController@create']);
    Route::post('/profile/store/qualification', ['access' => ['employee/qualification.create'], 'uses' => 'EmployeeQualificationController@store']);
    Route::get('/profile/edit/qualification/{id}', ['access' => ['employee/qualification.edit'], 'uses' => 'EmployeeQualificationController@edit']);
    Route::post('/profile/update/qualification/{id}', ['access' => ['employee/qualification.edit'], 'uses' => 'EmployeeQualificationController@update']);
    Route::get('/profile/delete/qualification/{id}', ['access' => ['employee/qualification.delete'], 'uses' => 'EmployeeQualificationController@destroy']);

    // employee experience
    Route::get('/profile/experience/{id}', ['access' => ['employee/experience'], 'uses' => 'EmployeeExperienceController@index']);
    Route::get('/profile/create/experience/{id}', ['access' => ['employee/experience.create'], 'uses' => 'EmployeeExperienceController@create']);
    Route::post('/profile/store/experience', ['access' => ['employee/experience.create'], 'uses' => 'EmployeeExperienceController@store']);
    Route::get('/profile/edit/experience/{id}', ['access' => ['employee/experience.edit'], 'uses' => 'EmployeeExperienceController@edit']);
    Route::post('/profile/update/experience/{id}', ['access' => ['employee/experience.edit'], 'uses' => 'EmployeeExperienceController@update']);
    Route::get('/profile/delete/experience/{id}', ['access' => ['employee/experience.delete'], 'uses' => 'EmployeeExperienceController@destroy']);

    //Routes By Asif Start
    Route::get('/profile/transfer/{id}', ['access' => ['employee/transfer'], 'uses' => 'EmployeeTransferController@index']);
    Route::get('/profile/make/transfer/{id}', ['access' => ['employee/transfer.make'], 'uses' => 'EmployeeTransferController@show']);
    Route::post('/profile/make/transfer', ['access' => ['employee/transfer.make'], 'uses' => 'EmployeeTransferController@store']);
    Route::get('/get/campus/from/institute', ['access' => ['employee/transfer'], 'uses' => 'EmployeeTransferController@getCampusFromInstitute']);
    //Routes By Asif End

    // employee department
    Route::get('/departments', 'EmployeeDepartmentController@index');
    Route::get('/departments/{id}', ['access' => ['employee/department.show'], 'uses' => 'EmployeeDepartmentController@show']);
    Route::get('/department/create', ['access' => ['employee/department.create'], 'uses' => 'EmployeeDepartmentController@create']);
    Route::post('/departments/store', ['access' => ['employee/department.create'], 'uses' => 'EmployeeDepartmentController@store']);
    Route::get('/departments/edit/{id}', ['access' => ['employee/department.edit'], 'uses' => 'EmployeeDepartmentController@edit']);
    Route::post('/departments/update/{id}', ['access' => ['employee/department.edit'], 'uses' => 'EmployeeDepartmentController@update']);
    Route::get('/departments/delete/{id}', ['access' => ['employee/department.delete'], 'uses' => 'EmployeeDepartmentController@destroy']);

    // employee designation
    Route::get('/designations', 'EmployeeDesignationController@index');
    Route::get('/designation/create', ['access' => ['employee/designation.create'], 'uses' => 'EmployeeDesignationController@create']);
    Route::get('/designations/{id}', ['access' => ['employee/designation.show'], 'uses' => 'EmployeeDesignationController@show']);
    Route::post('/designations/store', ['access' => ['employee/designation.create'], 'uses' => 'EmployeeDesignationController@store']);
    Route::get('/designations/edit/{id}', ['access' => ['employee/designation.edit'], 'uses' => 'EmployeeDesignationController@edit']);
    Route::post('/designations/update/{id}', ['access' => ['employee/designation.edit'], 'uses' => 'EmployeeDesignationController@update']);
    Route::get('/designations/delete/{id}', ['access' => ['employee/designation.delete'], 'uses' => 'EmployeeDesignationController@destroy']);

    // employee history
    Route::get('/profile/house-appoint/history/{id}', ['access' => ['employee/house-appoint/history'], 'uses' => 'EmployeeHistoryController@houseAppointHistory']);

    //By Dev9
    Route::get('/profile/status/history/{id}', ['access' => ['employee/promotion'], 'uses' => 'EmployeeHistoryController@statusAssignHistory']);
    Route::get('/profile/promotion/{id}', ['access' => ['employee/promotion'], 'uses' => 'EmployeePromotionController@index'])->name('employee.promotion.index');
    Route::get('/profile/promote/{id}', ['access' => ['employee/promotion.promote'], 'uses' => 'EmployeePromotionController@promote'])->name('employee.promotion.create');
    Route::get('/profile/promotion/{id}/edit', ['access' => ['employee/promotion.edit'], 'uses' => 'EmployeePromotionController@editPromotion'])->name('employee.promotion.edit');
    Route::post('/profile/promotion/{id}/store', ['access' => ['employee/promotion.edit'], 'uses' => 'EmployeePromotionController@storePromotion'])->name('employee.promotion.store');
    Route::post('/promote/store', ['access' => ['employee/promotion.promote'], 'uses' => 'EmployeePromotionController@store']);

    // Employee Award
    Route::get('/profile/award/{id}', ['access' => ['employee/award'], 'uses' => 'EmployeeAwardController@index'])->name('employee.award.index');
    Route::get('/profile/create/award/{id}', ['access' => ['employee/award.create'], 'uses' => 'EmployeeAwardController@create'])->name('employee.award.create');
    Route::post('/profile/create/award/{id}', ['access' => ['employee/award.create'], 'uses' => 'EmployeeAwardController@store'])->name('employee.award.create');
    Route::get('/profile/edit/award/{id}', ['access' => ['employee/award.edit'], 'uses' => 'EmployeeAwardController@edit'])->name('employee.award.edit');
    Route::post('/profile/edit/award/{id}', ['access' => ['employee/award.edit'], 'uses' => 'EmployeeAwardController@update'])->name('employee.award.edit');
    Route::get('/profile/delete/award/{id}', ['access' => ['employee/award.delete'], 'uses' => 'EmployeeAwardController@destroy'])->name('employee.award.delete');


    // employee import
    Route::get('/import', 'EmployeeController@importEmployee');
    Route::post('/import/list', 'EmployeeController@showImportedEmployeeList');
    Route::post('/import/list/check', 'EmployeeController@checkImportedEmployeeList');
    Route::post('/import/upload', 'EmployeeController@uploadEmployee');
    Route::post('/check/emails', 'EmployeeController@checkEmployeeEmail');


    // employee report
    Route::get('/report/profile/{id}', 'EmployeeReoprtController@index');
    // Route::get('/report/profile/{id}', 'EmployeeReoprtController@indexReport');

    ////////////////////  Holiday Management ////////////////////

    Route::get('/manage/national-holiday', 'NationalHolidayController@index');
    Route::get('/manage/national-holiday/create', ['access' => ['employee/national-holiday.create'], 'uses' => 'NationalHolidayController@create']);
    Route::post('/manage/national-holiday/store', ['access' => ['employee/national-holiday.create', 'employee/national-holiday.edit'], 'uses' => 'NationalHolidayController@store']);
    Route::get('/manage/national-holiday/edit/{holidayId}', ['access' => ['employee/national-holiday.edit'], 'uses' => 'NationalHolidayController@edit']);
    Route::get('/manage/national-holiday/delete/{holidayId}', ['access' => ['employee/national-holiday.delete'], 'uses' => 'NationalHolidayController@destroy']);

    ////////////////////  Week-off Management ////////////////////

    Route::get('/manage/week-off', 'WeekOffDayController@index');
    Route::get('/manage/week-off/create', ['access' => ['employee/week-off.create'], 'uses' => 'WeekOffDayController@create']);
    Route::post('/manage/week-off/store', ['access' => ['employee/week-off.create'], 'uses' => 'WeekOffDayController@store']);
    Route::get('/manage/week-off/edit/', ['access' => ['employee/week-off.edit'], 'uses' => 'WeekOffDayController@edit']);
    Route::post('/manage/week-off/update/', ['access' => ['employee/week-off.edit'], 'uses' => 'WeekOffDayController@update']);
    Route::get('/manage/week-off/delete/{weekOffId}', ['access' => ['employee/week-off.delete'], 'uses' => 'WeekOffDayController@destroy']);


    ////////////////////  Holiday Management ////////////////////
    //employee leave
    Route::get('/leavetype', 'EmployeeLeaveController@index');
    Route::get('/addleavetype', 'EmployeeLeaveController@add_leave_type');
    Route::get('/leavestructure', 'EmployeeLeaveController@leave_structure');
    Route::get('/addleavestructure', 'EmployeeLeaveController@add_leave_structure');
    Route::get('/leave/type','EmployeeLeaveController@index');
    //    Route::get('/addleavetype', 'EmployeeLeaveController@add_leave_type');
    Route::get('/leave/structure','EmployeeLeaveController@leaveStructure');
    Route::get('/add/leave/structure', ['access' => ['employee/leave-structure.create'], 'uses' =>'EmployeeLeaveController@addLeaveStructure']);
    Route::post('/store/leave/structure', ['access' => ['employee/leave-structure.create'], 'uses' =>'EmployeeLeaveController@storeLeaveStructure']);
    Route::get('/edit/leave/structure/{id}',['access' => ['employee/leave-structure.edit'], 'uses' =>'EmployeeLeaveController@editLeaveStructure']);
    Route::post('/update/leave/structure', ['access' => ['employee/leave-structure.edit'], 'uses' =>'EmployeeLeaveController@updateLeaveStructure']);

    //Holiday Calendar assign
    Route::get('/holiday-calender/assign','HolidayCalenderController@assign');
    Route::post('/employee/search/holiday', 'HolidayCalenderController@searchEmployee');
    Route::post('/holiday/assign/form/submit', 'HolidayCalenderController@assignSubmitEmployee');
    //Leave Assign
    Route::get('/leave/assign', 'EmployeeLeaveController@LeaveAssign');
//    Wasted
    Route::get('/all/leave/assign', 'EmployeeLeaveController@AllLeaveAssign');
    Route::get('/leave/encashment', 'EmployeeLeaveController@LeaveEncashment');
    Route::get('/leave/assign/user', 'EmployeeLeaveController@userLeaveAssign');
    Route::get('/leave/assign/role', 'EmployeeLeaveController@roleLeaveAssign');
    
    
    Route::get('/department/designation/{id}', 'EmployeeLeaveController@getAjaxDepartmentDesignation');
    Route::get('/manage/search', 'EmployeeLeaveController@searchEmployee');

    ////////////////////  Leave Management ////////////////////
    // leave type
    Route::get('/manage/leave/type', 'LeaveManagementController@getType');
    Route::get('/manage/leave/type/create', ['access' => ['employee/leave.type.create'], 'uses' => 'LeaveManagementController@createType']);
    Route::post('/manage/leave/type/store', ['access' => ['employee/leave.type.create'], 'uses' => 'LeaveManagementController@storeType']);
    Route::get('/manage/leave/type/edit/{id}', ['access' => ['employee/leave.type.edit'], 'uses' => 'LeaveManagementController@editType']);
    Route::post('/manage/leave/type/update/{id}', ['access' => ['employee/leave.type.edit'], 'uses' => 'LeaveManagementController@updateType']);
    Route::get('/manage/leave/type/delete/{id}', ['access' => ['employee/leave.type.delete'], 'uses' => 'LeaveManagementController@destroyType']);
    // leave structure
    Route::get('/manage/leave/structure', 'LeaveManagementController@getStructure');
    Route::get('/manage/leave/structure/create', ['access' => ['employee/leave.structure.create'], 'uses' => 'LeaveManagementController@createStructure']);
    Route::post('/manage/leave/structure/store', ['access' => ['employee/leave.structure.create'], 'uses' => 'LeaveManagementController@storeStructure']);
    Route::get('/manage/leave/structure/edit/{id}', ['access' => ['employee/leave.structure.edit'], 'uses' => 'LeaveManagementController@editStructure']);
    Route::post('/manage/leave/structure/update/{id}', ['access' => ['employee/leave.structure.edit'], 'uses' => 'LeaveManagementController@updateStructure']);
    Route::get('/manage/leave/structure/delete/{id}', ['access' => ['employee/leave.structure.delete'], 'uses' => 'LeaveManagementController@destroyStructure']);
    Route::get('/manage/leave/structure/{id}/{status}', ['access' => ['employee/leave.structure.status'], 'uses' => 'LeaveManagementController@statusStructure']);
    // leave structure type
    Route::get('/manage/leave/structure/venus/add/{id}', ['access' => ['employee/leave.structure.assign-type'], 'uses' => 'LeaveManagementController@createStructureType']);
    Route::get('/manage/leave/structure/venus/edit/{id}', ['access' => ['employee/leave.structure.update-type'], 'uses' => 'LeaveManagementController@editStructureType']);
    Route::post('/manage/leave/structure/assign/type/store', ['access' => ['employee/leave.structure.assign-type', 'employee/leave.structure.update-type'], 'uses' => 'LeaveManagementController@storeStructureType']);

    // leave entitlement
    Route::get('/manage/leave/entitlement', 'LeaveManagementController@getLeaveEntitlement');
    Route::get('/manage/leave/entitlement/search', 'LeaveManagementController@getLeaveEntitledList');
    Route::get('/manage/leave/entitlement/create', ['access' => ['employee/leave.entitlement.create'], 'uses' => 'LeaveManagementController@createLeaveAllocation']);
    Route::post('/manage/leave/entitlement/store', ['access' => ['employee/leave.entitlement.create'], 'uses' => 'LeaveManagementController@storeLeaveAllocation']);

    // leave application
    Route::get('/leave/application', 'LeaveManagementController@leaveApplication');
    Route::get('/leave/application/create', 'LeaveManagementController@createLeaveApplication');
    Route::get('/leave/application/apply', 'LeaveManagementController@applyLeaveApplication');
    Route::post('/leave/application/store', 'LeaveManagementController@storeLeaveApplication');
    Route::get('/manage/leave/application', 'LeaveManagementController@manageLeaveApplication');
    Route::get('/applied/leave/application/edit/{id}',['access' => ['employee/leave.application.edit'], 'uses' => 'LeaveManagementController@editLeaveApplication']);
    Route::post('/applied/leave/application/store/', 'LeaveManagementController@approvedLeaveApplication');
    Route::get('/manage/leave/application/{id}', 'LeaveManagementController@showLeaveApplication');
    Route::post('/manage/leave/application/status', 'LeaveManagementController@changeLeaveApplicationStatus');
    Route::get('/leave/application/create/from/admin', 'LeaveManagementController@createLeaveApplicationFromAdmin');


    // leave report
    Route::get('/leave/status/report', 'LeaveManagementController@getLeaveStatusReport');
    Route::get('/leave/status/report/pdf', 'LeaveManagementController@getLeaveStatusReportPDF');
    Route::post('/manage/leave/report', 'LeaveManagementController@downloadEmployeeLeave');

    // ajax request
    Route::get('/find/leave/structure/type/{id}', 'LeaveManagementController@findStructureTypes');
    Route::get('/find/leave/types/{id}', 'LeaveManagementController@findEmployeeStructureTypes');
    Route::get('/find/leave/structure/{id}', 'LeaveManagementController@findEmployeeLeaveStructure');
    Route::get('/find/designation/list/{id}', 'EmployeeDesignationController@findDesignationList');

    //get only teacher

    Route::get('/find/teacher', 'EmployeeInfoController@getOnlyTeacher');
    Route::get('/find/stuff/', 'EmployeeInfoController@getAllStuff');
    Route::get('/find/stuff/{department_id}', 'EmployeeInfoController@getStuffByDepartment');

    // find all teacher and staff
    Route::get('/find/employee', 'EmployeeInfoController@getAllEmployee');

    // update employee web position
    Route::post('/update/web-position', 'EmployeeController@updateWebPosition');
    /////////////////////// Start Shift Management///////////////////////////
    Route::get('/shift', 'EmpShiftManagementController@index');
    Route::get('/shift/create', 'EmpShiftManagementController@create');
    Route::post('/shift/store', 'EmpShiftManagementController@store');
    Route::get('/shift/{id}', 'EmpShiftManagementController@show');
    Route::get('/shift/edit/{id}', 'EmpShiftManagementController@edit');
    Route::post('/shift/update/{id}', 'EmpShiftManagementController@update');
    Route::get('/shift/delete/{id}', 'EmpShiftManagementController@destroy');
    /////////////////////// End Shift Management///////////////////////////


    /////////////////////// End employee Shift Allocation Management///////////////////////////
    Route::get('/shift_allocation_home', 'EmpShiftAllocationManageController@index');
    Route::get('/shift_allocation', 'EmpShiftAllocationManageController@shift_allocation');
    Route::post('/shift_allocation/store', 'EmpShiftAllocationManageController@store');
    Route::post('/shift_allocation/emp_list', 'EmpShiftAllocationManageController@emp_list');
    //    Route::get('/no_shift_emp','EmpShiftAllocationManageController@no_shift_emp');
    /////////////////////// End employee Shift Allocation Management///////////////////////////

    /////////////////////// Start employee Attendance Management///////////////////////////
    Route::get('/employee-attendance', 'EmpAttendanceManageController@index');
    Route::get('/employee-attendance/today', 'EmpAttendanceManageController@view');
    Route::get('/add-attendance', 'EmpAttendanceManageController@addAttendance');
    Route::get('/update-attendance/{id}', 'EmpAttendanceManageController@updateAttendance');
    Route::post('/add-attendance/store', 'EmpAttendanceManageController@store');
    Route::get('/employee-attendance/custom', 'EmpAttendanceManageController@addCustomAttendance');
    Route::post('/employee-attendance/custom', 'EmpAttendanceManageController@getCustomAttendance');
    Route::post('/employee-attendance/custom/store', 'EmpAttendanceManageController@storeCustomAttendance');
    //    Route::get('/emp-attendance/{id}','EmpAttendanceManageController@show');
    Route::post('/employee-attendance/emp_list', 'EmpAttendanceManageController@emp_list');
    Route::get('/upload-attendance', 'EmpAttendanceManageController@uploadAttForm');
    Route::post('/upload-attendance/fileUp', 'EmpAttendanceManageController@fileUp');
    Route::post('/upload-attendance/fileUpSave', 'EmpAttendanceManageController@fileUpSave');
    Route::post('/upload-attendance', 'EmpAttendanceManageController@uploadAttStore');
    Route::post('/attendance/report/download', 'EmpAttendanceManageController@downloadAttendanceReport');
    Route::get('/employee-monthly-attendance', 'EmpAttendanceManageController@viewMonthlyReportFrom');
    Route::post('/employee-monthly-attendance-report', 'EmpAttendanceManageController@monthlyAttendanceReport');


    /////////////////////// End Attendance Management///////////////////////////


    /////////////////////// Start Employee Ot Management///////////////////////////
    Route::get('/employee-over-time-entry', 'EmpOtManageController@index');
    Route::get('/employee-over-time-entry/add', 'EmpOtManageController@create');
    Route::post('/employee-over-time-entry/store', 'EmpOtManageController@store');

    /////////////////////// End Employee Ot Management///////////////////////////


    /////////////////////// Start Employee Attendance Setting ///////////////////////////
    Route::get('/employee-attendance-setting', 'EmployeeAttendanceSettingController@index');
    Route::get('/employee-attendance-setting/create', ['access' => ['employee/attendance.setting.create'], 'uses' => 'EmployeeAttendanceSettingController@create']);
    Route::get('/employee-attendance-setting/edit/{id}', ['access' => ['employee/attendance.setting.edit'], 'uses' => 'EmployeeAttendanceSettingController@edit']);
    Route::post('/employee-attendance-setting/store', ['access' => ['employee/attendance.setting.create', 'employee/attendance.setting.edit'], 'uses' => 'EmployeeAttendanceSettingController@store']);
    Route::get('/employee-attendance-setting/delete/{id}', ['access' => ['employee/attendance.setting.delete'], 'uses' => 'EmployeeAttendanceSettingController@delete']);

    /////////////////////// End Employee Attendance Setting///////////////////////////
    ///
    /// Change Employee Status
       //Route::get('/employee-status/change/{empID}', 'EmployeeController@changeEmployeeStatus');
 //Route::get('/employee/change-status/{empID}', 'EmployeeController@changeEmployeeStatus');
 Route::get('/change-status/{empID}', 'EmployeeController@changeEmployeeStatus');
 Route::post('/assign/save', 'EmployeeController@changeEmployeeStatusSave');

    //    get Ajax


    // Employee Status
    Route::get('/employee/status', 'EmployeeStatusController@index');
    Route::get('/employee/status/create', ['access' => ['employee/status.create'], 'uses' => 'EmployeeStatusController@create']);
    Route::post('/employee/status/store', ['access' => ['employee/status.create'], 'uses' => 'EmployeeStatusController@store']);
    Route::get('/employee/status/edit/{id}', ['access' => ['employee/status.edit'], 'uses' => 'EmployeeStatusController@edit']);
    Route::post('/employee/status/update/{id}', ['access' => ['employee/status.edit'], 'uses' => 'EmployeeStatusController@update']);
    Route::get('/employee/status/delete/{id}', ['access' => ['employee/status.delete'], 'uses' => 'EmployeeStatusController@destroy']);


    // Shift Configuration
    Route::get('/shift-configuration/{id?}','ShiftConfigurationController@index' );

    Route::post('/shift-configuration/add', 'ShiftConfigurationController@store');
    Route::post('/shift-configuration/update/{id}', ['access' => ['employee/shift-configuration.edit'], 'uses' => 'ShiftConfigurationController@update']);
    Route::get('/shift-configuration/delete/{id}', ['access' => ['employee/shift-configuration.delete'], 'uses' => 'ShiftConfigurationController@destroy']);


    // Holiday Calender
    Route::get('/holiday-calender/{id?}', 'HolidayCalenderController@index');
    Route::post('/create/holiday-calender', 'HolidayCalenderController@store');
    Route::post('/update/holiday-calender/{id}', ['access' => ['employee/holiday-calender.edit'], 'uses' => 'HolidayCalenderController@update']);
    Route::get('/holiday-calender/set-up/{id}', ['access' => ['employee/holiday-calender.set-up'], 'uses' => 'HolidayCalenderController@calenderSetUP']);
    Route::get('/search/holiday-calender', 'HolidayCalenderController@calenderSearch');
    Route::post('/save/holiday-calender/{id}', ['access' => ['employee/holiday-calender.set-up'], 'uses' => 'HolidayCalenderController@calenderSave']);
    Route::get('/delete/holiday-calender/{id}', ['access' => ['employee/holiday-calender.delete'], 'uses' => 'HolidayCalenderController@destroy']);


    // Evaluations
    Route::get('/evaluations/{id?}', 'EvaluationController@index');
    Route::post('/create/evaluation-parameter', 'EvaluationController@store');
    Route::get('/edit/evaluation-parameter/{id}', ['access' => ['employee/evaluation.parameter.edit'], 'uses' => 'EvaluationController@edit']);
    Route::post('/update/evaluation-parameter/{id}', ['access' => ['employee/evaluation.parameter.edit'], 'uses' => 'EvaluationController@update']);
    Route::get('/delete/evaluation-parameter/{id}', ['access' => ['employee/evaluation.parameter.delete'], 'uses' => 'EvaluationController@destroy']);
    Route::post('/create/evaluation', 'EvaluationController@storeEvaluation');
    Route::get('/edit/evaluation/{id}', ['access' => ['employee/evaluation.edit'], 'uses' => 'EvaluationController@editEvaluation']);
    Route::post('/update/evaluation/{id}', ['access' => ['employee/evaluation.edit'], 'uses' => 'EvaluationController@updateEvaluation']);
    Route::get('/delete/evaluation/{id}', ['access' => ['employee/evaluation.delete'], 'uses' => 'EvaluationController@destroyEvaluation']);
    Route::get('/assign-view/evaluation-parameter/{id}', ['access' => ['employee/evaluation.assign'], 'uses' => 'EvaluationController@assignViewEvaluationParameter']);
    Route::post('/assign/evaluation-parameter', ['access' => ['employee/evaluation.assign'], 'uses' => 'EvaluationController@assignEvaluationParameter']);
    Route::post('/setup/evaluation-parameter', 'EvaluationController@setupEvaluationParameter');
    Route::post('/setup/update/evaluation-parameter', 'EvaluationController@setupUpdateEvaluationParameter');

    // Vacancy Report (Designation)
    Route::get('/vacancy-report-designation/report', 'VacancyReportController@getVacancyReportDesignation');
    Route::get('/vacancy-report-designation/designation-report', 'VacancyReportController@searchvacancyByDesignation');

    // Vacancy Report (Department)
    Route::get('/vacancy-report-department/report', 'VacancyReportController@getVacancyReportDepartment');
    Route::get('/vacancy-report-department/department-report', 'VacancyReportController@searchvacancyByDepartmentCurrentInstitute');
    // Route::get('/leave/status/report/pdf', 'LeaveManagementController@getLeaveStatusReportPDF');
    // Route::post('/manage/leave/report', 'LeaveManagementController@downloadEmployeeLeave');
});



Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'employee', 'namespace' => 'Modules\Employee\Http\Controllers'], function () {

    // Evaluation with student permission

    Route::get('/evaluation/view/{id?}', ['access' => ['employee/evaluation.view'], 'uses' => 'EvaluationController@evaluationView']);
    Route::post('/evaluation/score/distribution', 'EvaluationController@evaluationScoreDistribution');
    Route::get('/evaluation/search/view', 'EvaluationController@evaluationSearchView');
    Route::get('/evaluation/history/view', 'EvaluationController@evaluationHistoryView');
    Route::post('/evaluation/search', 'EvaluationController@evaluationSearch');
    Route::post('/evaluation/history', 'EvaluationController@evaluationHistory');

    // Ajax Routes of Evaluation
    Route::get('/evaluation/ajax/search/year', 'EvaluationController@evaluationAjaxSearchYear');
    Route::get('/evaluation/ajax/search/evaluation', 'EvaluationController@evaluationAjaxSearchEvaluation');

    // Route::get('/vacancy-report-designation/report', 'VacancyReportController@getVacancyReportDesignation');
    // Route::post('/vacancy-report-designation/designation-report', 'InventoryReportController@searchvacancyByDesignation');
});
