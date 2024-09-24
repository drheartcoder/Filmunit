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

Route::group(array('prefix' => '/','middleware'=>['front']), function()
{
	$route_slug        = "";
	$module_controller = "Front\AuthController@";

	/*----------------------------------------------------------------------------------------
									Front Home Route  
	----------------------------------------------------------------------------------------*/

	Route::get('',              												[	'as'	=> $route_slug.'index', 
																					'uses'	=> 'Front\HomeController@index']);

	Route::get('about',              											[	'as'	=> $route_slug.'about', 
																					'uses'	=> 'Front\HomeController@about']);

	Route::get('contactus',              										[	'as'	=> $route_slug.'contactus', 
																					'uses'	=> 'Front\HomeController@contactus']);

	Route::get('terms-and-conditions',              							[	'as'	=> $route_slug.'terms_and_condition', 
																					'uses'	=> 'Front\HomeController@terms_and_condition']);

	Route::get('privacy-and-policy',              								[	'as'	=> $route_slug.'privacy_policy', 
																					'uses'	=> 'Front\HomeController@privacy_policy']);																																											
	
	Route::get('login',              											[	'as'	=> $route_slug.'login', 
																					'uses'	=> 'Front\HomeController@login']);

	Route::get('signup',              											[	'as'	=> $route_slug.'signup', 
																					'uses'	=> 'Front\HomeController@signup']);

	Route::get('registration_success',              							[	'as'	=> $route_slug.'registration_success', 
																					'uses'	=> 'Front\HomeController@registration_success']);

	Route::get('forgot_password',              									[	'as'	=> $route_slug.'forgot_password', 
																					'uses'	=> 'Front\HomeController@forgot_password']);

	Route::get('reset_password/{enc_id}/{activation_code}',              		[	'as'	=> $route_slug.'reset_password', 
																					'uses'	=> 'Front\HomeController@reset_password']);

	Route::post('store_user',              										[	'as'	=> $route_slug.'store_user', 
																					'uses'	=> $module_controller.'store_user']);
	
	Route::get('verify/{user_id}/{activation_code}',    						[	'as'	=> $route_slug.'verify', 
																					'uses'	=> $module_controller.'verify']);

	Route::post('process_login',   												[	'as'	=> $route_slug.'process_login', 
																					'uses'	=> $module_controller.'process_login']);

	Route::post('process_forgot_password',   								  	[	'as'	=> $route_slug.'process_forgot_password', 
																					'uses'	=> $module_controller.'process_forgot_password']);

	Route::post('validate_reset_password_link/{enc_id}/{activation_code}',    	[	'as'	=> $route_slug.'validate_reset_password_link', 
																					'uses'	=> $module_controller.'validate_reset_password_link']);
});
