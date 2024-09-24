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

Route::group(array('prefix' => 'buyer/'), function()
{
	$route_slug        = "";
	$module_controller = "Front\buyer\HomeController@";

	/*----------------------------------------------------------------------------------------
									Buyer Route  
	----------------------------------------------------------------------------------------*/

	Route::get('dashboard',              									[	'as'	=> $route_slug.'index', 
																				'uses'	=> $module_controller.'index']);

	/*----------------------------------------------------------------------------------------
									Finance Route  
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => 'finance/'), function()
	{
		$route_slug        = "";
		$module_controller = "Front\buyer\FinanceController@";

		Route::get('/',              										[	'as'	=> $route_slug.'index', 
																				'uses'	=> $module_controller.'index']);

		Route::get('view/{enc_id}',              							[	'as'	=> $route_slug.'view', 
																				'uses'	=> $module_controller.'view']);		

	});

	/*----------------------------------------------------------------------------------------
									Downloads Route  
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => 'downloads/'), function()
	{
		$route_slug        = "";
		$module_controller = "Front\buyer\DownloadController@";

		Route::get('/',              										[	'as'	=> $route_slug.'index', 
																				'uses'	=> $module_controller.'index']);

		Route::get('view/{enc_id}',              							[	'as'	=> $route_slug.'view', 
																				'uses'	=> $module_controller.'view']);

		Route::any('download/{enc_id}',              						[	'as'	=> $route_slug.'download', 
																				'uses'	=> $module_controller.'download']);																						

	});	

});
