<?php 
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(array('prefix' => 'seller/'), function()
{
	$route_slug        = "";
	$module_controller = "Front\seller\HomeController@";

	/*----------------------------------------------------------------------------------------
									Seller Home Route  
	----------------------------------------------------------------------------------------*/
	Route::get('dashboard',              									[	'as'	=> $route_slug.'index', 
																				'uses'	=> $module_controller.'index']);

	/*----------------------------------------------------------------------------------------
										Seller Photos and Footage Route  
	----------------------------------------------------------------------------------------*/
	
	Route::group(array('prefix' => 'photos_and_footage/'), function()
	{
		$route_slug        = "";
		$module_controller = "Front\seller\PhotosAndFootageController@";
		
		Route::get('/',              									    [	'as'	=> $route_slug.'index', 
																				'uses'	=> $module_controller.'index']);
		
		Route::get('/admin_listing',              						    [	'as'	=> $route_slug.'index_admin', 
																				'uses'	=> $module_controller.'index_admin']);		

		Route::get('/add',              									[	'as'	=> $route_slug.'add', 
																				'uses'	=> $module_controller.'add']);

		Route::post('/store',              									[	'as'	=> $route_slug.'store', 
																				'uses'	=> $module_controller.'store']);

		Route::get('/edit/{enc_id}',              							[	'as'	=> $route_slug.'edit', 
																				'uses'	=> $module_controller.'edit']);

		Route::post('/update/{enc_id}',              						[	'as'	=> $route_slug.'update', 
																				'uses'	=> $module_controller.'update']);

		Route::get('/delete/{enc_id}',              						[	'as'	=> $route_slug.'delete', 
																				'uses'	=> $module_controller.'delete']);

		Route::get('/view/{enc_id}',              							[	'as'	=> $route_slug.'view', 
																				'uses'	=> $module_controller.'view']);

		Route::any('/check_media_duplication',              				[	'as'	=> $route_slug.'check_media_duplication', 
																				'uses'	=> $module_controller.'check_media_duplication']);																																																																																			
	});

	/*----------------------------------------------------------------------------------------
									Finance Route  
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => 'finance/'), function()
	{
		$route_slug        = "";
		$module_controller = "Front\seller\FinanceController@";

		Route::get('/',              										[	'as'	=> $route_slug.'index', 
																				'uses'	=> $module_controller.'index']);

		Route::get('view/{enc_id}',              							[	'as'	=> $route_slug.'view', 
																				'uses'	=> $module_controller.'view']);

		Route::get('invoice/{enc_transaction_id}',              		    [	'as'	=> $route_slug.'invoice', 
																				'uses'	=> $module_controller.'invoice']);																						

	});	
	
});