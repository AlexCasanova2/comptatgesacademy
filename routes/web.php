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




Route::group(['middlewareGroups' => ['web']], function () {
    //login de usuario
    Route::get('/', 'Pub\IndexController@login');
    Route::post('/', 'Pub\IndexController@tryLogin');
	Route::get('/logout', 'Pub\IndexController@logout');
	
	 Route::get('/seeder', 'Admin\IndexController@seedNumber'); //ruta base /admin

  
});


//Este es el grupo de URL's para /admin
Route::group(['middlewareGroups' => ['isAdmin','web'], 'prefix' => '/admin'], function () {
    Route::get('/', 'Admin\IndexController@index'); //ruta base /admin

    Route::get('/stands', 'Admin\StandsController@index'); //ruta base /admin
    Route::post('/stands/', 'Admin\StandsController@insert'); //ruta base /admin
    Route::post('/stands/get/{id}', 'Admin\StandsController@getProduct'); //ruta base /admin
    Route::post('/stands/remove/{id}', 'Admin\StandsController@removeBox'); //ruta base /admin
    Route::any('/stands/datatables', 'Admin\StandsController@datatables'); //ruta base /admin

    Route::get('/stand/{id}', 'Admin\StandsController@stand'); //ruta base /admin
    Route::get('/stand/{id}/{day}/{month}/{year}/{hour}', 'Admin\StandsController@standbyday'); //ruta base /admin
    Route::get('/comptatge/editbyday', 'Admin\StandsController@comptatgebyday'); //ruta base /admin
    
        
    
    Route::get('/comptatge/edit', 'Admin\StandsController@comptatge'); //ruta base /admin
  
    Route::get('/statics', 'Admin\StandsController@statics'); //ruta base /admin
    Route::any('/statics/datatables', 'Admin\StandsController@datatableStatics'); //ruta base /admin
    Route::post('/statics', 'Admin\StandsController@getStatistics')->name('getStatistics');
    Route::post('/graphs', 'Admin\StandsController@getGraphs')->name('getGraphPerDay');
});


