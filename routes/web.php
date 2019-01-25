<?php

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

Route::group(['prefix' => '/', 'namespace' => 'Frontend'], function () {
    Route::get('/', 'IndexController@index') -> name('index');
    Route::get('/search', 'IndexController@search') -> name('index.search');
    Route::get('/download.html', 'IndexController@download') -> name('index.download');
    Route::get('/{module}/list.html', 'ModulesController@list') -> name('module.list');
    Route::get('/{module}/show/{id}.html', 'ModulesController@show') -> name('module.detail');
    Route::get('/department/{department}/list.html', 'DepartmentsController@list') -> name('department.list');
    Route::get('/department/{department}/show/{id}.html', 'DepartmentsController@show') -> name('department.detail');
    Route::get('/special/list.html', 'SpecialController@all') -> name('special.all');
    Route::get('/special/{code}/list.html', 'SpecialController@list') -> name('special.list');
    Route::get('/special/{code}/show/{id}.html', 'SpecialController@show') -> name('special.detail');
});

Route::group(['prefix' => config('app.backend.prefix'), 'namespace' => 'Backend'], function() {
    Route::get('/', function () {
        return redirect(route('backend.sign.in'));
    }) -> name('backend');
    Route::get('sign/in', 'SignController@in') -> name('backend.sign.in');
    Route::post('sign/in', 'SignController@doIn') -> name('backend.sign.in.post');
    Route::get('sign/out', 'SignController@out') -> name('backend.sign.out');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard', 'BackendController@index') -> name('backend.index');

        Route::get('contents', 'ContentsController@list') -> name('backend.contents');
        Route::get('contents/add', 'ContentsController@form') -> name('backend.contents.add');
        Route::get('contents/edit', 'ContentsController@form') -> name('backend.contents.edit');
        Route::post('contents/store', 'ContentsController@store') -> name('backend.contents.store');
        Route::get('contents/delete', 'ContentsController@delete') -> name('backend.contents.delete');

        Route::get('download', 'DownloadController@list') -> name('backend.download');
        Route::get('download/add', 'DownloadController@form') -> name('backend.download.add');
        Route::get('download/edit', 'DownloadController@form') -> name('backend.download.edit');
        Route::post('download/store', 'DownloadController@store') -> name('backend.download.store');
        Route::get('download/delete', 'DownloadController@delete') -> name('backend.download.delete');

        Route::get('index/sections', 'SectionsController@list') -> name('backend.sections');
        Route::post('index/sections/store', 'SectionsController@store') -> name('backend.sections.store');
        Route::get('index/sections/delete', 'SectionsController@delete') -> name('backend.sections.delete');

        Route::get('modules', 'ModulesController@list') -> name('backend.modules');
        Route::get('modules/add', 'ModulesController@form') -> name('backend.modules.add');
        Route::get('modules/edit', 'ModulesController@form') -> name('backend.modules.edit');
        Route::post('modules/store', 'ModulesController@store') -> name('backend.modules.store');
        Route::get('modules/delete', 'ModulesController@delete') -> name('backend.modules.delete');

        Route::get('navigation', 'NavigationController@list') -> name('backend.navigation');
        Route::post('navigation/store', 'NavigationController@store') -> name('backend.navigation.store');
        Route::get('navigation/delete', 'NavigationController@delete') -> name('backend.navigation.delete');

        Route::post('password/reset', 'BackendController@resetPassword') -> name('backend.reset.password');

        Route::get('settings/links', 'SettingsController@links') -> name('backend.settings.links');
        Route::post('settings/links/store', 'SettingsController@linksStore') -> name('backend.settings.links.store');
        Route::get('settings/links/delete', 'SettingsController@linksDelete') -> name('backend.settings.links.delete');
        Route::get('settings/links/edit', 'SettingsController@linksEdit') -> name('backend.settings.links.edit');
        Route::post('settings/links/edit/store', 'SettingsController@linksEditStore') -> name('backend.settings.links.edit.store');

        Route::get('special', 'SpecialController@list') -> name('backend.special');
        Route::post('special/store', 'SpecialController@store') -> name('backend.special.store');
        Route::get('special/delete', 'SpecialController@delete') -> name('backend.special.delete');
        Route::get('special/layouts', 'SpecialController@layout') -> name('backend.special.layouts');
        Route::post('special/layouts/store', 'SpecialController@layoutStore') -> name('backend.special.layouts.store');

        Route::get('system/actions', 'SystemController@actions') -> name('backend.system.actions');
        Route::get('system/actions/add', 'SystemController@actionForm') -> name('backend.system.actions.add');
        Route::get('system/actions/edit', 'SystemController@actionForm') -> name('backend.system.actions.edit');
        Route::post('system/actions/store', 'SystemController@actionStore') -> name('backend.system.actions.store');
        Route::get('system/actions/delete', 'SystemController@actionDelete') -> name('backend.system.actions.delete');

        Route::get('system/departments', 'SystemController@departments') -> name('backend.system.departments');
        Route::get('system/departments/add', 'SystemController@departmentForm') -> name('backend.system.departments.add');
        Route::get('system/departments/edit', 'SystemController@departmentForm') -> name('backend.system.departments.edit');
        Route::post('system/departments/store', 'SystemController@departmentStore') -> name('backend.system.departments.store');
        Route::get('system/departments/delete', 'SystemController@departmentDelete') -> name('backend.system.departments.delete');
        Route::get('system/department', 'SystemController@getDepartment') -> name('backend.system.department');

        Route::get('system/users', 'SystemController@users') -> name('backend.system.users');
        Route::get('system/users/add', 'SystemController@userForm') -> name('backend.system.users.add');
        Route::get('system/users/edit', 'SystemController@userForm') -> name('backend.system.users.edit');
        Route::post('system/users/store', 'SystemController@userStore') -> name('backend.system.users.store');
        Route::get('system/users/delete', 'SystemController@userDelete') -> name('backend.system.users.delete');

        Route::get('system/roles', 'SystemController@roles') -> name('backend.system.roles');
        Route::get('system/roles/add', 'SystemController@roleForm') -> name('backend.system.roles.add');
        Route::get('system/roles/edit', 'SystemController@roleForm') -> name('backend.system.roles.edit');
        Route::post('system/roles/store', 'SystemController@roleStore') -> name('backend.system.roles.store');
        Route::get('system/roles/delete', 'SystemController@roleDelete') -> name('backend.system.roles.delete');

        Route::any('upload/ue', 'UploadController@ueUpload') -> name('backend.upload.ue');
        Route::post('upload/thumbnail', 'UploadController@thumbnail') -> name('backend.upload.thumbnail');
        Route::post('upload/software', 'UploadController@software') -> name('backend.upload.software');
    });
});
