<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route return hello world
// Route::get('/hello-world', function () {
//     return 'Hello World';
// });

// //Route return view
// Route::get('/test', function () {
//     return view('test');
// });