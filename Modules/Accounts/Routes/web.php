<?php

Route::group(['middleware' => ['auth', 'cadet-user-permission'], 'prefix' => 'accounts'], function () {
    Route::get('/', 'AccountsController@index');

    // Chart of Account routes Start
    Route::get('/chart-of-accounts', 'ChartOfAccountController@index');
    Route::get('/chart-of-accounts/create-group', 'ChartOfAccountController@create');
    Route::get('/chart-of-accounts/create-ledger', 'ChartOfAccountController@createLedger');
    Route::post('chart-of-accounts/store', ['access' => ['accounts/chart-of-accounts/create-group', 'chart-of-accounts/create-ledger'], 'uses' => 'ChartOfAccountController@store']);
    Route::get('/chart-of-accounts/edit/{id}', ['access' => ['accounts/chart-of-accounts.edit'], 'uses' => 'ChartOfAccountController@edit']);
    Route::post('/chart-of-accounts/update/{id}', ['access' => ['accounts/chart-of-accounts.edit'], 'uses' => 'ChartOfAccountController@update']);
    Route::get('/chart-of-accounts/delete/{id}', ['access' => ['accounts/chart-of-accounts.delete'], 'uses' => 'ChartOfAccountController@destroy']);
    Route::get('/chart-of-accounts-config', 'ChartOfAccountController@chartOfAccountsConfig');
    Route::post('/chart-of-accounts-config-update', 'ChartOfAccountController@chartOfAccountsConfigUpdate');
    // Chart of Account routes End


    // Fiscal year routes start
    Route::get('/fiscal-year', 'FiscalYearController@index');
    // Fiscal year routes start

    // Accounts voucher config start
    Route::get('/voucher-config-list', 'AccountsVoucherConfigController@index');
    Route::get('/voucher-config-list/create', 'AccountsVoucherConfigController@create');
    Route::get('/voucher-config-list/{id}/edit', ['access' => ['accounts/voucher-config-list.edit'], 'uses' => 'AccountsVoucherConfigController@edit']);
    Route::post('/voucher-config-list', ['access' => ['accounts/voucher-config-list/create'], 'uses' => 'AccountsVoucherConfigController@store']);
    Route::post('/voucher-config-list/update/{id}', ['access' => ['accounts/voucher-config-list.edit'], 'uses' => 'AccountsVoucherConfigController@update']);
    // Accounts voucher config end

    // Accounts Configuration start
    Route::get('/accounts-configuration', 'AccountsConfigurationController@index');
    Route::get('/accounts-configuration/{label_name}/edit', ['access' => ['accounts/accounts-configuration.edit'], 'uses' => 'AccountsConfigurationController@edit']);
    Route::post('/accounts-configuration/update/{label_name}', ['access' => ['accounts/accounts-configuration.edit'], 'uses' => 'AccountsConfigurationController@update']);

    // Accounts Configuration end

    // Budget Allocation routes Start
    Route::get('/budget-allocation', 'BudgetAllocationController@index');
    Route::get('/budget-allocation/add-budget', 'BudgetAllocationController@create');
    Route::post('/budget-allocation/store-budget', ['access' => ['accounts/budget-allocation/add-budget'], 'uses' => 'BudgetAllocationController@store']);
    Route::get('/budget-allocation/edit-budget/{id}', ['access' => ['accounts/budget-allocation.edit'], 'uses' => 'BudgetAllocationController@edit']);
    Route::post('/budget-allocation/update-budget/{id}', ['access' => ['accounts/budget-allocation.edit'], 'uses' => 'BudgetAllocationController@update']);
    Route::get('/budget-allocation/delete-budget/{id}', ['access' => ['accounts/budget-allocation.delete'], 'uses' => 'BudgetAllocationController@destroy']);

    // Budget Allocation routes End


    // Payment Voucher Start

    Route::get('/payment-voucher', 'PaymentVoucherController@page');
    Route::get('/payment-voucher-data', ['access' => ['accounts/payment-voucher'], 'uses' => 'PaymentVoucherController@index']);
    Route::get('/payment-voucher-data/create', 'PaymentVoucherController@create');
    Route::post('/payment-voucher-data', ['access' => ['accounts/payment-voucher-data/create', 'accounts/payment-voucher.edit'], 'uses' => 'PaymentVoucherController@store']);
    Route::get('/payment-voucher-data/{id}/edit', ['access' => ['accounts/payment-voucher.edit'], 'uses' => 'PaymentVoucherController@edit']);
    Route::post('/payment-voucher-approval/{id}', ['access' => ['accounts/payment-voucher.approval'], 'uses' => 'PaymentVoucherController@voucherApproval']);
    Route::get('/payment-voucher-data/{id}', ['access' => ['accounts/payment-voucher.show'], 'uses' => 'PaymentVoucherController@show']);
    Route::delete('/payment-voucher-data/{id}', ['access' => ['accounts/payment-voucher.delete'], 'uses' => 'PaymentVoucherController@destroy']);

    Route::get('/check-acc-voucher-no', 'PaymentVoucherController@checkVoucher');
    Route::get('/print/payment-voucher/{id}', ['access' => ['accounts/payment-voucher.print'], 'uses' => 'PaymentVoucherController@print']);

    // Payment Voucher End

    // Receive Voucher Start

    Route::get('/receive-voucher', 'ReceiveVoucherController@page');
    Route::get('/receive-voucher-data', ['access' => ['accounts/receive-voucher'], 'uses' => 'ReceiveVoucherController@index']);
    Route::get('/receive-voucher-data/create', 'ReceiveVoucherController@create');
    Route::post('/receive-voucher-data', ['access' => ['accounts/receive-voucher-data/create', 'accounts/receive-voucher.edit'], 'uses' => 'ReceiveVoucherController@store']);
    Route::get('/receive-voucher-data/{id}/edit', ['access' => ['accounts/receive-voucher.edit'], 'uses' => 'ReceiveVoucherController@edit']);
    Route::post('/receive-voucher-approval/{id}', ['access' => ['accounts/receive-voucher.approval'], 'uses' => 'ReceiveVoucherController@voucherApproval']);
    Route::get('/receive-voucher-data/{id}', ['access' => ['accounts/receive-voucher.show'], 'uses' => 'ReceiveVoucherController@show']);
    Route::delete('/receive-voucher-data/{id}', ['access' => ['accounts/receive-voucher.delete'], 'uses' => 'ReceiveVoucherController@destroy']);
    Route::get('/print/receive-voucher/{id}', ['access' => ['accounts/receive-voucher.print'], 'uses' => 'ReceiveVoucherController@print']);

    // Receive Voucher End

    // Journal Voucher Start
    Route::get('/journal-voucher', 'JournalVoucherController@page');
    Route::get('/journal-voucher-data', ['access' => ['accounts/journal-voucher'], 'uses' => 'JournalVoucherController@index']);
    Route::get('/journal-voucher-data/create', 'JournalVoucherController@create');
    Route::post('/journal-voucher-data', ['access' => ['accounts/journal-voucher-data/create', 'accounts/journal-voucher.edit'], 'uses' => 'JournalVoucherController@store']);
    Route::get('/journal-voucher-data/{id}/edit', ['access' => ['accounts/journal-voucher.edit'], 'uses' => 'JournalVoucherController@edit']);
    Route::get('/journal-voucher-data/{id}', ['access' => ['accounts/journal-voucher.show'], 'uses' => 'JournalVoucherController@show']);
    Route::post('/journal-voucher-approval/{id}', ['access' => ['accounts/journal-voucher.approval'], 'uses' => 'JournalVoucherController@voucherApproval']);
    Route::delete('/journal-voucher-data/{id}', ['access' => ['accounts/journal-voucher.delete'], 'uses' => 'JournalVoucherController@destroy']);
    Route::get('/print/journal-voucher/{id}', ['access' => ['accounts/journal-voucher.print'], 'uses' => 'JournalVoucherController@print']);

    // Journal Voucher End


    // Contra Voucher Start

    Route::get('/contra-voucher', 'ContraVoucherController@page');
    Route::get('/contra-voucher-data', ['access' => ['accounts/contra-voucher'], 'uses' => 'ContraVoucherController@index']);
    Route::get('/contra-voucher-data/create', 'ContraVoucherController@create');
    Route::post('/contra-voucher-data', ['access' => ['accounts/contra-voucher-data/create', 'accounts/contra-voucher.edit'], 'uses' => 'ContraVoucherController@store']);
    Route::get('/contra-voucher-data/{id}/edit', ['access' => ['accounts/contra-voucher.edit'], 'uses' => 'ContraVoucherController@edit']);
    Route::post('/contra-voucher-approval/{id}', ['access' => ['accounts/contra-voucher.approval'], 'uses' => 'ContraVoucherController@voucherApproval']);
    Route::get('/contra-voucher-data/{id}', ['access' => ['accounts/contra-voucher.show'], 'uses' => 'ContraVoucherController@show']);
    Route::delete('/contra-voucher-data/{id}', ['access' => ['accounts/contra-voucher.delete'], 'uses' => 'ContraVoucherController@destroy']);
    Route::get('/print/contra-voucher/{id}', ['access' => ['accounts/contra-voucher.print'], 'uses' => 'ContraVoucherController@print']);

    // Contra Voucher End



    // SignatoryConfig Start
    Route::get('/signatory-config-data/{report_name}', 'SignatoryConfigController@page');
    Route::get('/signatory-confin-form', 'SignatoryConfigController@createForm');
    Route::get('/signatory-confin-getdesignation', 'SignatoryConfigController@getdesignation');
    Route::post('/signatory-confin-data/post', "SignatoryConfigController@insertSignatory");
    Route::get('/signatory-confin-data/delete/{id}', "SignatoryConfigController@deleteSignatory");
    // SignatoryConfig End

    //Trial balance report Start
    route::get('/reports/trial-balance',"TrialBalanceReportController@index")->name('trail-balance.index');
    route::post('/reports/trial-balance/search',"TrialBalanceReportController@search")->name('trail-balance.search');
    route::post('/reports/ledger/search',"TrialBalanceReportController@ledgersearch")->name('ledger.search');
    Route::get('/report/ledger-details/{id}/{type}/{fromDate}/{toDate}', ['access' => ['accounts/ledger-report'], 'uses' => 'TrialBalanceReportController@ledgerdetails'])->name('trail-balance.ledgerdetails');
    //Trial balance report end



});
