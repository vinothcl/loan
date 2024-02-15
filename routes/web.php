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
});
