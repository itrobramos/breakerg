<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/home', 'HomeController@index')->name('home');


Route::get('/users/changePassword', 'UserController@changePassword')->name('users.changePassword');
Route::post('/users/updatePassword', 'UserController@updatePassword')->name('users.updatePassword');

Route::get('clients', 'ClientController@index')->name('clients.index');
Route::get('/clients/add', 'ClientController@create')->name('clients.add');
Route::post('/clients/store', 'ClientController@store')->name('clients.store');
Route::post('/clients/storeAjax', 'ClientController@storeAjax')->name('clients.storeAjax');
Route::get('/clients/edit/{id}', 'ClientController@edit')->name('clients.edit');
Route::patch('/clients/edit/{id}', 'ClientController@update')->name('clients.update');
Route::get('/clients/destroy/{id}', 'ClientController@destroy')->name('clients.destroy');

Route::get('users', 'UserController@index')->name('users.index');
Route::get('/users/add', 'UserController@create')->name('users.add');
Route::post('/users/store', 'UserController@store')->name('users.store');
Route::get('/users/edit/{id}', 'UserController@edit')->name('users.edit');
Route::patch('/users/edit/{id}', 'UserController@update')->name('users.update');
Route::get('/users/destroy/{id}', 'UserController@destroy')->name('users.destroy');

Route::get('suppliers', 'SupplierController@index')->name('suppliers.index');
Route::get('/suppliers/add', 'SupplierController@create')->name('suppliers.add');
Route::post('/suppliers/store', 'SupplierController@store')->name('suppliers.store');
Route::get('/suppliers/edit/{id}', 'SupplierController@edit')->name('suppliers.edit');
Route::patch('/suppliers/edit/{id}', 'SupplierController@update')->name('suppliers.update');
Route::get('/suppliers/destroy/{id}', 'SupplierController@destroy')->name('suppliers.destroy');

Route::get('products', 'ProductController@index')->name('products.index');
Route::get('/products/add', 'ProductController@create')->name('products.create');
Route::post('/products/store', 'ProductController@store')->name('products.store');
Route::post('/products/storeAjax', 'ProductController@storeAjax')->name('products.storeAjax');
Route::get('/products/edit/{id}', 'ProductController@edit')->name('products.edit');
Route::patch('/products/edit/{id}', 'ProductController@update')->name('products.update');
Route::get('/products/destroy/{id}', 'ProductController@destroy')->name('products.destroy');


Route::get('product_types', 'ProductTypeController@index')->name('product_types.index');
Route::get('/product_types/add', 'ProductTypeController@create')->name('product_types.add');
Route::post('/product_types/store', 'ProductTypeController@store')->name('product_types.store');
Route::get('/product_types/edit/{id}', 'ProductTypeController@edit')->name('product_types.edit');
Route::patch('/product_types/edit/{id}', 'ProductTypeController@update')->name('product_types.update');
Route::get('/product_types/destroy/{id}', 'ProductTypeController@destroy')->name('product_types.destroy');

Route::get('entries','EntryController@index')->name('entries.index');
Route::post('entries', 'EntryController@indexPost')->name('entries.index');
Route::post('entries/export', 'EntryController@export')->name('entries.export');
Route::get('/entries/add','EntryController@create')->name('entries.add');
Route::post('/entries/store','EntryController@store')->name('entries.store');
Route::get('/entries/edit/{id}','EntryController@edit')->name('entries.edit');
Route::post('/entries/edit/{id}','EntryController@update')->name('entries.update');
Route::get('/entries/destroy/{id}','EntryController@destroy')->name('entries.destroy');
Route::get('/entries/show/{id}','EntryController@show')->name('entries.show');

Route::get('entries/products','EntryController@products')->name('entries.products');
Route::post('entries/products', 'EntryController@productsPost')->name('entries.products');
Route::post('entries/products/export', 'EntryController@productsExport')->name('entries.products.export');


Route::get('sales','SaleController@index')->name('sales.index');
Route::post('sales', 'SaleController@indexPost')->name('sales.index');
Route::post('sales/export', 'SaleController@export')->name('sales.export');
Route::get('/sales/add','SaleController@create')->name('sales.add');
Route::post('/sales/store','SaleController@store')->name('sales.store');
Route::get('/sales/edit/{id}','SaleController@edit')->name('sales.edit');
Route::post('/sales/edit/{id}','SaleController@update')->name('sales.update');
Route::get('/sales/destroy/{id}','SaleController@destroy')->name('sales.destroy');
Route::get('/sales/show/{id}','SaleController@show')->name('sales.show');

Route::get('sales/products','SaleController@products')->name('sales.products');
Route::post('reports/cashflow', 'ReportController@cashflowDate')->name('reports.cashflowDate');

///Reportes

Route::get('reports', 'ReportController@index')->name('reports');
Route::get('reports/cashflow', 'ReportController@cashflow')->name('reports.cashflow');
Route::post('reports/cashflow', 'ReportController@cashflowDate')->name('reports.cashflowDate');
// Route::get('reports/purchase', 'ReportController@purchase')->name('reports.purchase');

Route::get('reports/inventary', 'ReportController@inventary')->name('reports.inventary');
Route::post('reports/inventary', 'ReportController@inventaryPost')->name('reports.products');
Route::post('reports/inventary/export', 'ReportController@inventaryExport')->name('reports.products.export');

Auth::routes(
    ['register' => false]
);
