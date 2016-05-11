<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'work', 'uses' => 'HomeController@index']);

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
// Route::get('auth/register', 'Auth\AuthController@getRegister');
// Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/', ['as' => 'adminHome', 'uses' => 'Admin\AdminController@adminHome']);
    Route::get('/403', ['as' => 'adminForbidden', 'uses' => 'Admin\AdminController@forbidden']);

    Route::resource('users', 'Admin\UsersController');
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');

    Route::controller('profile', 'Admin\ProfileController', [
        'getEdit' => 'admin.profile.edit',
        'postUpdate' => 'admin.profile.update',
    ]);
});

// Manage users, roles and permissions
Entrust::routeNeedsPermission('admin/users*', 'manage_users', redirect(route('adminForbidden')));
Entrust::routeNeedsPermission('admin/roles*', 'manage_users', redirect(route('adminForbidden')));
Entrust::routeNeedsPermission('admin/permissions*', 'manage_users', redirect(route('adminForbidden')));