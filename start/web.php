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

Router::addMenu('One Click Course', 'MainController@index' );
Router::addSubMenu('One Click Course', 'TheSubmenu', 'MainController@index');


Router::addSubMenu('One Click Course','Update', 'MainController@update');

//
//Router::addChannel( 'post', 'MainController@test', 'test' );
//Router::addChannel( 'post', 'MainController@index', 'test2' );
