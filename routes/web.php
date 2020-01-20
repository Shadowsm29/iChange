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

Route::middleware("auth")->group(function() {
    
    Route::get("users/change-password-form", "UsersController@changePasswordForm")->name("users.change-password-form");
    
    Route::put("users/change-password", "UsersController@changePassword")->name("users.change-password");

    Route::middleware("pwd_expired")->group(function() {
        
        Route::get('/home', 'HomeController@index')->name('home');

        Route::middleware("idea_submitter")->group(function() {

            Route::get('/ideas/create', 'IdeaController@create')->name('ideas.create');

            Route::post('/ideas', 'IdeaController@store')->name('ideas.store');

            Route::get('/ideas/my-active-ideas', 'IdeaController@listMyActiveIdeas')->name('ideas.my-active-ideas');
        
            Route::get('/ideas/my-all-ideas', 'IdeaController@listMyAllIdeas')->name('ideas.my-all-ideas');

        });

        Route::middleware("all_ideas")->group(function() {

            Route::get('ideas/all', 'IdeaController@listAllIdeas')->name('ideas.all-ideas');
        
            Route::get('ideas/all-active', 'IdeaController@listAllActiveIdeas')->name('ideas.all-active');

        });

        Route::middleware("idea_processor")->group(function() {

            Route::get('/ideas/personal-que', 'IdeaController@showPersonalQue')->name('ideas.personal-que');
            
        });

        Route::middleware("business_users")->group(function() {

            Route::get('/ideas/display/{idea}', 'IdeaController@display')->name('ideas.display');
        
            Route::put('/ideas/approve/{idea}', 'IdeaController@approve')->name('ideas.approve');
            
            Route::put('/ideas/decline/{idea}', 'IdeaController@decline')->name('ideas.decline');
            
            Route::put('/ideas/back-to-submitter/{idea}', 'IdeaController@backToSubmitter')->name('ideas.back-to-submitter');
            
            Route::put('/ideas/forward/{idea}', 'IdeaController@forward')->name('ideas.forward');
            
            Route::put('/ideas/cancel/{idea}', 'IdeaController@cancel')->name('ideas.cancel');
            
            Route::put('/ideas/update-resubmit/{idea}', 'IdeaController@updateResubmit')->name('ideas.update-resubmit');
            
            Route::put('/ideas/update-complete/{idea}', 'IdeaController@updateComplete')->name('ideas.update-complete');
            
            Route::put('/ideas/{idea}', 'IdeaController@update')->name('ideas.update');
            
        });

        Route::middleware("admin_area")->group(function() {
    
            Route::middleware("user_mgmt")->group(function() {
    
                Route::resource("users", "UsersController");
                
                Route::get("users/{user}/reset-form", "UsersController@resetForm")->name("users.reset-form");
                
                Route::get("trashed-users", "UsersController@trashed")->name("users.trashed");
                
                Route::put("restore/user/{user}", "UsersController@restore")->name("users.restore");
    
                Route::put("users/{user}/reset", "UsersController@reset")->name("users.reset");

            });
    
            Route::middleware("backend_mgmt")->group(function() {
    
                Route::resource("change-types", "ChangeTypesController");
            
                Route::resource("justifications", "JustificationsController");
    
                Route::resource("supercircles", "SupercirclesController");
                
                Route::resource("circles", "CirclesController");
    
            });
        
            
        });
        
    });

    
});