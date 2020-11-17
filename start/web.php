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

//Router::addMenu('One Click Course', 'MainController@index' );
Router::addMenu('One Click Classroom Setup', 'MainController@blank' );
Router::addSubMenu('One Click Classroom Setup', 'Courses', 'MainController@index', ['menu_slug' => 'one-click-classroom-setup']);
Router::addSubMenu('One Click Classroom Setup', 'Course Setup', 'MainController@create');

Router::addChannel('get','classroom-update-page', 'MainController@classroom_update_page');

// Delete 
Router::post('classroom-delete', 'MainController@delete' );


//
Router::addChannel( 'post', 'test', 'MainController@test' );
//Router::addChannel( 'post', 'MainController@index', 'test2' );

Router::addSubMenu('One Click Classroom Setup', 'Plugin Settings', 'SettingsController@index');
Router::post('save-settings', 'SettingsController@store' );

//One Click Saving - controller
Router::addChannel( 'post', 'classroom-store', 'ClassroomController@store' );
