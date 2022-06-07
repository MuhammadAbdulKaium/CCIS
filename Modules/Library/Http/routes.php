<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'library', 'namespace' => 'Modules\Library\Http\Controllers'], function () {
    Route::get('/', 'LibraryController@index');


    //library book category
    Route::get('/library-book-category/index', 'BookCategoryController@index');
    Route::post('/library-book-category/store', ['access' => ['library/book-category.create', 'library/book-category.edit'], 'uses' => 'BookCategoryController@store']);
    Route::get('/library-book-category/delete/{id}', ['access' => ['library/book-category.delete'], 'uses' => 'BookCategoryController@delete']);
    Route::get('/library-book-category/edit/{id}', ['access' => ['library/book-category.edit'], 'uses' => 'BookCategoryController@edit']);

    //library book shelf
    Route::get('/library-book-shelf/index', 'BookShelfController@index');
    Route::post('/library-book-shelf/store', ['access' => ['library/book-shelf.create', 'library/book-shelf.edit'], 'uses' => 'BookShelfController@store']);
    Route::get('/library-book-shelf/edit/{id}', ['access' => ['library/book-shelf.edit'], 'uses' => 'BookShelfController@edit']);
    Route::get('/library-book-shelf/delete/{id}', ['access' => ['library/book-shelf.delete'], 'uses' => 'BookShelfController@delete']);

    //library cupboard Shelf
    Route::get('/library-cupboard-shelf/index', 'CupBoardShelfController@index');
    Route::get('/find/library-cupboard-shelf/{id}', 'CupBoardShelfController@getCupBoradShelfByBookShelfId');
    Route::post('/library-cupboard-shelf/store', ['access' => ['library/cupboard-shelf.create', 'library/cupboard-shelf.edit'], 'uses' => 'CupBoardShelfController@store']);
    Route::get('/library-cupboard-shelf/delete/{id}', ['access' => ['library/cupboard-shelf.delete'], 'uses' => 'CupBoardShelfController@delete']);
    Route::get('/library-cupboard-shelf/edit/{id}', ['access' => ['library/cupboard-shelf.edit'], 'uses' => 'CupBoardShelfController@edit']);


    //library vendor master
    Route::get('/library-book-vendor/index', 'BookVendorController@index');
    Route::post('/library-book-vendor/store', ['access' => ['library/book-vendor.create', 'library/book-vendor.edit'], 'uses' => 'BookVendorController@store']);
    Route::get('/library-book-vendor/delete/{id}', ['access' => ['library/book-vendor.delete'], 'uses' => 'BookVendorController@delete']);
    Route::get('/library-book-vendor/edit/{id}', ['access' => ['library/book-vendor.edit'], 'uses' => 'BookVendorController@edit']);

    //book create
    Route::get('/library-book/index', 'BookController@showAllBooks')->name('bookList');;
    Route::get('/library-book/create', ['access' => ['library/book.create'], 'uses' => 'BookController@index']);
    Route::post('/library-book/store', ['access' => ['library/book.create', 'library/book.edit'], 'uses' => 'BookController@store']);
    Route::get('/library-book/view/{id}', ['access' => ['library/book.view'], 'uses' => 'BookController@viewBookDetails']);
    Route::get('/library-book/update-book/{id}', ['access' => ['library/book.edit'], 'uses' => 'BookController@editBook']);

    // add more copy modal
    Route::get('/library-book-master/add-more-copy/{id}', ['access' => ['library/book-master.add-more'], 'uses' => 'BookController@addMoreCopyModal']);
    // add more copy store
    Route::post('/library-book-master/add-more-copy/store', ['access' => ['library/book-master.add-more'], 'uses' => 'BookController@addMoreCopyStore']);
    ///book search
    Route::get('/library-borrow-transaction/search', 'BookController@bookSearch');



    // book list show
    Route::get('/library-borrow-transaction/index', 'BookController@showBorrowBookTransaction');

    //issue Book Modal
    Route::get('/library-borrow-transaction/borrow-book/{id}', ['access' => ['library/borrow-transaction.borrow-book'], 'uses' => 'IssueBookController@showIssueBookModal']);
    Route::post('/library-borrow-transaction/issue-book/store', ['access' => ['library/borrow-transaction.borrow-book'], 'uses' => 'IssueBookController@storeIssueBook']);
    Route::get('/library-borrow-transaction/borrower', 'IssueBookController@showReturnRenewBook');

    Route::get('/library-borrow-transaction/update/{id}', 'IssueBookController@bookBorrowTransactionUpdate');
    // show Modal
    Route::get('/library-borrow-transaction/return-book/{id}', 'IssueBookController@returnRenewBook');
    Route::post('/library-borrow-transaction/return-book/update/{id}', 'IssueBookController@returnRenewBookUpdate');

    //    Fine
    Route::post('/library-borrow-transaction/return-book-with-fine/{id}', 'IssueBookController@returnBookWithFine');
    Route::post('/library-borrow-transaction/return-book-with-fine-manual/{id}', 'IssueBookController@returnBookWithFineManual');
    Route::get('/library-borrow-transaction/return-book-with-fine-show/{id}', 'IssueBookController@returnBookManually');
    Route::get('/library-fine-master/fine-list', 'IssueBookController@fineList')->name('fineList');


    //library book  status
    Route::get('/library-book-status/index', 'BookStatusController@index');
    Route::post('/library-book-status/store', ['access' => ['library/book-status.create', 'library/book-status.edit'], 'uses' => 'BookStatusController@store']);
    Route::get('/library-book-status/edit/{id}', ['access' => ['library/book-status.edit'], 'uses' => 'BookStatusController@edit']);
    Route::get('/library-book-status/delete/{id}', ['access' => ['library/book-status.delete'], 'uses' => 'BookStatusController@delete']);




    //    //library book  Master
    //    Route::get('/library-book-master/index', function (){
    //        return view('library::library-book-master.index');
    //    });



    // library-borrow- transaction book search
    //     Route::get('/library-borrow-transaction/index', function (){
    //         return view('library::library-borrow-transaction.index');
    //     });


    // library book issue search
    //    Route::get('/library-borrow-transaction/index', function (){
    //        return view('library::issue-books.index');
    //    });

    // library return and renuew book


    // library return and renuew book
    //    Route::get('/library-fine-master/fine-list', function (){
    //        return view('library::library-fine-master.fine');
    //    });



});
