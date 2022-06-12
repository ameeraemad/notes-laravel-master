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

//===============================================================
//                      CMS-ADMIN ROUTES
//===============================================================
Route::prefix('cms/admin/')->namespace('Auth')->middleware('guest:admin')->group(function () {
    Route::get('login', 'AdminAuthController@showLoginView')->name('cms.admin.login_view');
    Route::post('login', 'AdminAuthController@login')->name('cms.admin.login');
    Route::view('blocked', 'cms.blocked')->name('cms.admin.blocked');
});

Route::prefix('cms/admin/')->namespace('Auth')->middleware('auth:admin')->group(function () {
    Route::get('password-reset', 'AdminAuthController@showResetPasswordView')->name('cms.admin.password_reset_view');
    Route::post('password-reset', 'AdminAuthController@resetPassword')->name('cms.admin.password_reset');
    Route::get('logout', 'AdminAuthController@logout')->name('cms.admin.logout');
});

Route::prefix('cms/admin/')->namespace('CMS\Admin')->middleware('auth:admin')->group(function () {
    Route::get('roles/{id}/edit-permissions', 'RoleController@editRolePermissions')->name('roles.edit-permissions');
    Route::post('roles/{id}/update-permissions', 'RoleController@updateRolePermissions')->name('roles.update-permissions');

    Route::get('students/{id}/edit-permissions', 'StudentController@editPermissions')->name('students.edit-permissions');
    Route::post('students/{id}/update-permissions', 'StudentController@updatePermissions')->name('students.update-permissions');

    Route::get('admins/{id}/edit-permissions', 'AdminController@editPermissions')->name('admins.edit-permissions');
    Route::post('admins/{id}/update-permissions', 'AdminController@updatePermissions')->name('admins.update-permissions');
});

Route::prefix('cms/admin/')->namespace('CMS\Admin')->middleware('auth:admin')->group(function () {
    Route::resource('admins', 'AdminController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
});

Route::prefix('cms/admin/')->namespace('CMS\Admin')->middleware('auth:admin,student')->group(function () {
    Route::resource('users', 'UserController');
    Route::resource('students', 'StudentController');
    Route::resource('categories', 'CategoryController');
    Route::resource('notes', 'NoteController');
    Route::resource('notifications', 'NotificationController');

    Route::get('', 'CmsDashboardController@show')->name('cms.admin.dashboard');
    Route::get('notes/user/{id}', 'NoteController@showUserNotes')->name('cms.admin.user.notes');
    Route::get('categories/user/{id}', 'CategoryController@showUserCategories')->name('cms.admin.user.categories');
    Route::get('students/{id}/users', 'StudentController@showUsers')->name('cms.admin.student.users');
});

//===============================================================
//                      CMS-STUDENT ROUTES
//===============================================================
Route::prefix('cms/student/')->namespace('Auth')->middleware('guest:student')->group(function () {
    Route::get('login', 'StudentAuthController@showLoginView')->name('cms.student.login_view');
    Route::post('login', 'StudentAuthController@login')->name('cms.student.login');

    Route::get('register', 'StudentAuthController@showRegisterView')->name('cms.student.register_view');
    Route::post('register', 'StudentAuthController@register')->name('cms.student.register');

    Route::get('forgot-password', 'StudentAuthController@showForgetPassword')->name('cms.student.forgot_password_view');
    Route::post('forgot-password', 'StudentAuthController@requestNewPassword')->name('cms.student.forgot_password');
});

Route::prefix('cms/student/')->namespace('Auth')->middleware('auth:student')->group(function () {
    Route::get('password-reset', 'StudentAuthController@showResetPasswordView')->name('cms.student.password_reset_view');
    Route::post('password-reset', 'StudentAuthController@resetPassword')->name('cms.student.password_reset');
    Route::get('logout', 'StudentAuthController@logout')->name('cms.student.logout');
});

Route::view('privacy-policy', 'cms.privacy-policy');

Route::get('mailable', function () {
    $student = App\Student::where('email', 'momen_sisalem92@live.com')->first();
    $user = $student->users()->first();
    return new App\Mail\NewUserRegistered($student, $user);
});
