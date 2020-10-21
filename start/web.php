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
Router::addMenu('One Click Classroom Setup', 'MainController@index' );
Router::addSubMenu('One Click Classroom Setup', 'Clone Classroom', 'MainController@create');
Router::addSubMenu('One Click Classroom Setup', 'Plugin Settings', 'MainController@settings');


Router::addSubMenu('One Click Classroom Setup','Update', 'MainController@update');

//
Router::addChannel( 'post', 'test', 'MainController@test' );
//Router::addChannel( 'post', 'MainController@index', 'test2' );
