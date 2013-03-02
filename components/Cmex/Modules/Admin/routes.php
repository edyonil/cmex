<?php

// All routes related to the admin system
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{
    Route::get('/', function()
    {
        return Redirect::to('admin/dashboard');
    });

    Route::resource('user', 'Cmex\Modules\Admin\Controller\User');

    Route::get('dashboard', 'Cmex\Modules\Admin\Controller\Dashboard@handle');

    Route::get('media', 'Cmex\Modules\Admin\Controller\Media@index');
    Route::get('media/{path}', 'Cmex\Modules\Admin\Controller\Media@show')->where('path', '[A-Za-z0-9/.]+');

    Route::get('{module}', function($module)
    {
        return $module;
    });

});