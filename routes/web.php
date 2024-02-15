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
//Route::get('/register', [App\Http\Controllers\HomeController::class, 'index'])->name('register');

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

	#Managetype - vinothcl
	Route::group(['prefix' => '/manage-type', 'page-group' => '/manage-type'], function () {
		Route::get('/', 'App\Http\Controllers\ManageTypeController@index')->name('manage-type');
		Route::get('/get-type-list-ajax', 'App\Http\Controllers\ManageTypeController@gettypeListAjax')->name('gettypeListAjax');
		Route::get('/add', 'App\Http\Controllers\ManageTypeController@add')->name('manage-type-add');
		Route::post('/save', 'App\Http\Controllers\ManageTypeController@save')->name('manage-type-save');
		Route::post('/update', 'App\Http\Controllers\ManageTypeController@update')->name('manage-type-update');
		Route::get('/edit/{id}', 'App\Http\Controllers\ManageTypeController@edit')->name('manage-type-edit');
		Route::any('/delete/{id}', 'App\Http\Controllers\ManageTypeController@delete')->name('manage-type-delete');
	});

});
