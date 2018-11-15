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
    Route::get('/{module}/list.html', 'ModulesController@list') -> name('module.list');
    Route::get('/{module}/show/{id}.html', 'ModulesController@show') -> name('module.detail');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Backend'], function() {
    Route::get('sign/in', 'SignController@in') -> name('backend.sign.in');
    Route::post('sign/in', 'SignController@doIn') -> name('backend.sign.in.post');
    Route::get('/', 'BackendController@index') -> name('backend.index');

    Route::get('contents', 'ContentsController@list') -> name('backend.contents');
    Route::get('contents/add', 'ContentsController@form') -> name('backend.contents.add');
    Route::get('contents/edit', 'ContentsController@form') -> name('backend.contents.edit');
    Route::post('contents/store', 'ContentsController@store') -> name('backend.contents.store');
    Route::get('contents/delete', 'ContentsController@delete') -> name('backend.contents.delete');


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

    Route::get('settings/links', 'SettingsController@links') -> name('backend.settings.links');


    Route::any('upload/ue', 'UploadController@ueUpload') -> name('backend.upload.ue');
    Route::post('upload/thumbnail', 'UploadController@thumbnail') -> name('backend.upload.thumbnail');
});
