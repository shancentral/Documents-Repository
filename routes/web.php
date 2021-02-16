<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Default
Route::get('/', function(){ return redirect('/customers'); });

// Auth
Auth::routes();
Route::post('/create-user', 'Auth\RegisterController@createUser')->name('create-user');

// Frontend::Users
Route::get('/users', 'Auth\RegisterController@viewUsers')->name('users.index');
Route::get('/users/{id}/edit', 'Auth\RegisterController@editUser')->name('users.edit');
Route::put('/users/{id}', 'Auth\RegisterController@updateUser')->name('users.update');
Route::delete('/users/{id}', 'Auth\RegisterController@deleteUser')->name('users.delete');
Route::get('/change-password', 'Auth\RegisterController@editPassword')->name('password.change');
Route::post('/update-password', 'Auth\RegisterController@updatePassword')->name('password.update');

// Customers
Route::resource('customers', 'CustomersController');
Route::get('/customers-info/{id}/', 'CustomersController@info')->name('customers.info');

// Projects
Route::get('/customers/{customer_id}/projects', 'ProjectsController@index')->name('customers.projects');
Route::get('/customers/{customer_id}/projects/create', 'ProjectsController@create')->name('projects.create');
Route::post('/customers/{customer_id}/projects', 'ProjectsController@store')->name('projects.store');
Route::get('/projects/{id}/edit', 'ProjectsController@edit')->name('projects.edit');
Route::get('/projects/{id}/{info}', 'ProjectsController@edit')->name('projects.info');
Route::put('/projects/{id}', 'ProjectsController@update')->name('projects.update');
Route::delete('/projects/{id}', 'ProjectsController@destroy')->name('projects.destroy');
Route::get('/download/{customer_id}/{project_id}/{file}', 'ProjectsController@download')->name('projects.download');


// customer_id/project_id
