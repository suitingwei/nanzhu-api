<?php

Route::get('/test', function () {

    $groupUser = \App\Models\GroupUser::find(34602);
    dd($groupUser);
});


Route::get('/', 'HomeController@welcome');

require app_path('Http/api_routes.php');

//不能删,防止有人用旧版的统筹入口登录,重定向到新的
Route::group(['middleware' => ['web']], function () {
    Route::auth();
});

//网页im
Route::resource('/im', 'ImController');

//天气
Route::get("/weather", "WeatherController@index");

//城市列表
Route::get("/cities", "CitiesController@index");

require app_path('Http/mobile_routes.php');
