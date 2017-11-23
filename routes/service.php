<?php

Route::group(['namespace' => 'Service', 'as' => 'service', 'prefix' => 'service'], function (){
    Route::get('wallpaper/cron', 'WallpaperController@cron');
});
