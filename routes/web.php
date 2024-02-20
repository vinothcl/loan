<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
	return redirect(route('home'));
})->name('index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register', [App\Http\Controllers\HomeController::class, 'index'])->name('register');

Route::get('/logout', function () {
	Auth::logout();
	return redirect('/');
})->name('logout');

// admin
Route::group(['middleware' => ['auth', 'AdminValidation']], function () {
	#Manageemployee - vinothcl
	Route::group(['prefix' => '/manage-employee', 'page-group' => '/manage-employee'], function () {
		Route::get('/', 'App\Http\Controllers\ManageEmployeeController@index')->name('manage-employee');
		Route::get('/get-employee-list-ajax', 'App\Http\Controllers\ManageEmployeeController@getemployeeListAjax')->name('getemployeeListAjax');
		Route::get('/add', 'App\Http\Controllers\ManageEmployeeController@add')->name('manage-employee-add');
		Route::post('/save', 'App\Http\Controllers\ManageEmployeeController@save')->name('manage-employee-save');
		Route::post('/update', 'App\Http\Controllers\ManageEmployeeController@update')->name('manage-employee-update');
		Route::get('/edit/{id}', 'App\Http\Controllers\ManageEmployeeController@edit')->name('manage-employee-edit');
		Route::any('/delete/{id}', 'App\Http\Controllers\ManageEmployeeController@delete')->name('manage-employee-delete');
	});
});

// Employee
Route::group(['middleware' => ['auth', 'EmployeeValidation']], function () {
	#Managelead - vinothcl
	Route::group(['prefix' => '/manage-lead', 'page-group' => '/manage-lead'], function () {
		Route::get('/', 'App\Http\Controllers\ManageLeadController@index')->name('manage-lead');
		Route::get('/get-lead-list-ajax', 'App\Http\Controllers\ManageLeadController@getleadListAjax')->name('getleadListAjax');
		Route::get('/add', 'App\Http\Controllers\ManageLeadController@add')->name('manage-lead-add');
		Route::post('/save', 'App\Http\Controllers\ManageLeadController@save')->name('manage-lead-save');
		Route::post('/update', 'App\Http\Controllers\ManageLeadController@update')->name('manage-lead-update');
		Route::get('/edit/{id}', 'App\Http\Controllers\ManageLeadController@edit')->name('manage-lead-edit');
		Route::any('/delete/{id}', 'App\Http\Controllers\ManageLeadController@delete')->name('manage-lead-delete');
	});
});
