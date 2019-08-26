<?php

Route::group(['prefix' => '/v1', 'middleware' => ['auth:api'], 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::post('change-password', 'ChangePasswordController@changePassword')->name('auth.change_password');
    Route::apiResource('rules', 'RulesController', ['only' => ['index']]);
    Route::apiResource('courier-companies',     'CourierCompaniesController');
    Route::apiResource('courier-routers',       'CourierRoutersController');
    Route::apiResource('printer-companies',     'PrinterCompaniesController');
    Route::apiResource('printer-routers',       'PrinterRoutersController');
    Route::apiResource('permissions',           'PermissionsController');
    Route::apiResource('roles',                 'RolesController');
    Route::apiResource('users',                 'UsersController');
    Route::apiResource('orders',                'OrdersController');

    Route::get('fetch-continents',              'WorldController@getContinents');
    Route::get('fetch-countries/{continent}',   'WorldController@getCountries');
    Route::get('fetch-regions/{country}',       'WorldController@getRegions');
    Route::get('fetch-non-assigned-users',      'UsersController@fetchNonAssignedUsers');
});

Route::group(['prefix' => '/v2', 'middleware' => ['auth:api'], 'namespace' => 'Api\V2', 'as' => 'api.'], function () {
    Route::post('change-password', 'ChangePasswordController@changePassword')->name('auth.change_password');

    Route::apiResource('lots',                  'LotsController');
    Route::apiResource('virtual-boxes',         'VirtualBoxesController');

    Route::get('get-pending-books',             'BooksController@getPendingBooks');
    Route::get('books-in-lots',                 'BooksController@getBooksInLots');
    Route::get('printing-orders',               'BooksController@getPrintingBooks');
    Route::get('printed-orders',                'OrdersController@getPrintedOrders');
    Route::get('finished-orders',               'OrdersController@getFinishedOrders');
    Route::get('all-orders',                    'OrdersController@getAllOrders');
    Route::get('printer-books/{id}',            'BooksController@getBook');
    Route::get('printer-orders/{id}',           'OrdersController@getOrder');

    Route::put('printer-orders/set-printed',    'BooksController@setAsPrinted');
    Route::put('printer-orders/set-printing',   'BooksController@setAsPrinting');

    Route::post('virtual-boxes/publish',        'VirtualBoxesController@publish');
});