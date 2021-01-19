<?php

Route::group(['namespace' => 'Botble\Quanlysv\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'quanlysvs', 'as' => 'quanlysv.'], function () {
            Route::resource('', 'QuanlysvController')->parameters(['' => 'quanlysv']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'QuanlysvController@deletes',
                'permission' => 'quanlysv.destroy',
            ]);
        });

        //danh sach lop
        Route::group(['prefix' => 'danhsachlops', 'as' => 'danhsachlop.'], function () {
            Route::resource('', 'DanhsachlopController')->parameters(['' => 'danhsachlop']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'DanhsachlopController@deletes',
                'permission' => 'danhsachlop.destroy',
            ]);
        });

        //danh sach sv
        Route::group(['prefix' => 'danhsachsvs', 'as' => 'danhsachsv.'], function () {
            Route::resource('', 'DanhsachsvController')->parameters(['' => 'danhsachsv']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'DanhsachsvController@deletes',
                'permission' => 'danhsachsv.destroy',
            ]);
        });
    });

});
