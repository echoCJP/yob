<?php

use Yob\Route;

Route::get('api/test', 'Api/TestController@test1');

Route::get('/', function() {
  echo "Welcome";
});

Route::get('/name/(:all)', function($name) {
  echo 'Your name is '.$name;
});

Route::get('home', 'HomeController@home');
// Route::get('home/my/(:num)', 'HomeController@home');
Route::get('mail', 'HomeController@mail');
Route::get('redis', 'HomeController@redis');
Route::get('test', 'HomeController@test');

Route::error(function() {
    echo '404 Not Found';
});

Route::post('test/post', 'HomeController@testPost');

Route::dispatch();

