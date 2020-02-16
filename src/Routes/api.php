<?php

use Illuminate\Http\Request;



Route::group(['middleware' => ['api.auth','api'], 'prefix' => 'api'], function () {

    //图片压缩
//    Route::get('images/{date}/{img}', 'System\\Api\\ImgController@images');

    Route::prefix('admin')->name('admin.api.')->group(function () {
        Route::apiResource('user/admin', LarfreePermission\Controllers\User\AdminController::class,['adv'=>true]);
        Route::apiResource('permission/roles', LarfreePermission\Controllers\Permission\RolesController::class,['adv'=>true]);
        Route::apiResource('permission/permissions', LarfreePermission\Controllers\Permission\PermissionsController::class);
        Route::get('permission/roles/nav/tree', LarfreePermission\Controllers\Permission\RolesController::class.'@navTree');
    });
});

