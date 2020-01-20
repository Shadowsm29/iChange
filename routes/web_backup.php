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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware("auth")->group(function(){

    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::resource("ideas", "IdeaController");
    
    Route::resource("users", "UsersController");
    
    Route::resource("change-types", "ChangeTypesController");
    
    Route::resource("justifications", "JustificationsController");

    Route::resource("supercircles", "SupercirclesController");
    
    Route::resource("circles", "CirclesController");
    
});

// Route::middleware(["auth", "admin"])->group(function(){

//     Route::get('/home', 'HomeController@index')->name('home');
    
//     Route::resource("ideas", "IdeaController");
    
// });

