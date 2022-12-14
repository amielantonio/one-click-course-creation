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

//Main Menu
Router::addMenu('One Click Classroom Setup', 'MainController@blank' );
Router::addSubMenu('One Click Classroom Setup', 'Courses', 'MainController@index', ['menu_slug' => 'one-click-classroom-setup']);


//Classrooms
Router::addSubMenu('One Click Classroom Setup', 'Course Setup', 'ClassroomController@create');

//Save
Router::addChannel( 'post', 'classroom-store', 'ClassroomController@store' );
//Update
Router::get('classroom-edit', 'ClassroomController@edit');
Router::addChannel( 'post', 'classroom-update', 'ClassroomController@update' );

// Delete
Router::post('classroom-delete', 'ClassroomController@delete' );
Router::post('classroom-trash', 'ClassroomController@trash');

//Settings
Router::addSubMenu('One Click Classroom Setup', 'Plugin Settings', 'SettingsController@index');
//Save - settings
Router::post('save-settings', 'SettingsController@store' );


//Tags
Router::get('oc-tags','ClassroomController@ajaxTags');

//TESTS

Router::get('test-classroom-store','TestController@classroomStore');
