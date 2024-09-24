<?php 
$pages = [];
$pages = DB::select('select post_name from wp_posts where post_type = "page" AND post_status="publish"');
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


/*
>>>>>>> 9dd8246a03f3255a17cd2944b91aa9a47fd85e41
Route::any('store_user/',	   								  			 [	'as'	=> 'store_user', 'uses'	=> 'Front\AuthController@store_user']);
Route::any('verify/{user_id}/{activation_code}',	    	  			 [	'as'	=> 'verify', 'uses'	=> 'Front\AuthController@verify']);
Route::any('process_forgot_password/',	    				  			 [	'as'	=> 'process_forgot_password', 'uses'	=> 'Front\AuthController@process_forgot_password']);
Route::any('validate_reset_password_link/{enc_id}/{enc_reminder_code}',  [	'as'	=> 'validate_reset_password_link', 'uses'	=> 'Front\AuthController@validate_reset_password_link']);
Route::any('reset_password/',  											 [	'as'	=> 'reset_password', 'uses'	=> 'Front\AuthController@reset_password']);

Route::any('/',['as' => 'home_page' ,'uses' => 'Front\HomeController@index']);
*/

Route::group(array('prefix' => '/','middleware'=>['front']), function() use($pages)
{
	$route_slug        = "";
	$module_controller = "Front\AuthController@";


if(count($pages)>0)
{
	foreach($pages as $main)
	{
	  Route::get($main->post_name,['as'=> '','uses'=>'Front\HomeController@static_page']);
	}
}

	/*----------------------------------------------------------------------------------------
									Front Home Route  
	----------------------------------------------------------------------------------------*/

	Route::get('',              												[	'as'	=> $route_slug.'index', 
																					'uses'	=> 'Front\HomeController@index']);

	/*Route::get('about',              											[	'as'	=> $route_slug.'about', 
																					'uses'	=> 'Front\HomeController@about']);

	Route::get('contactus',              										[	'as'	=> $route_slug.'contactus', 
																					'uses'	=> 'Front\HomeController@contactus']);

	Route::get('terms-and-conditions',              							[	'as'	=> $route_slug.'terms_and_condition', 
																					'uses'	=> 'Front\HomeController@terms_and_condition']);

	Route::get('privacy-and-policy',              								[	'as'	=> $route_slug.'privacy_policy', 
																					'uses'	=> 'Front\HomeController@privacy_policy']);*/																																											
	
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

	Route::post('check_email_duplication',              						[	'as'	=> $route_slug.'check_email_duplication', 
																					'uses'	=> $module_controller.'check_email_duplication']);
	
	Route::get('verify/{user_id}/{activation_code}',    						[	'as'	=> $route_slug.'verify', 
																					'uses'	=> $module_controller.'verify']);

	Route::post('process_login',   												[	'as'	=> $route_slug.'process_login', 
																					'uses'	=> $module_controller.'process_login']);

	Route::post('process_forgot_password',   								  	[	'as'	=> $route_slug.'process_forgot_password', 
																					'uses'	=> $module_controller.'process_forgot_password']);

	Route::post('validate_reset_password_link/{enc_id}/{activation_code}',    	[	'as'	=> $route_slug.'validate_reset_password_link', 
																					'uses'	=> $module_controller.'validate_reset_password_link']);

	Route::get('account',              									    	[	'as'	=> $route_slug.'account', 
																					'uses'	=> $module_controller.'account']);

	Route::get('edit_account',    												[	'as'	=> $route_slug.'edit_account', 
																					'uses'	=> $module_controller.'edit_account']);

	Route::post('update_account',    											[	'as'	=> $route_slug.'update_account', 
																					'uses'	=> $module_controller.'update_account']);

	Route::get('change_password',    											[	'as'	=> $route_slug.'change_password', 
																					'uses'	=> $module_controller.'change_password']);

	Route::post('update_password',    											[	'as'	=> $route_slug.'update_password', 
																					'uses'	=> $module_controller.'update_password']);

	Route::get('logout',    													[	'as'	=> $route_slug.'logout', 
																					'uses'	=> $module_controller.'logout']);

	Route::post('get_notification_count',              							[	'as'	=> $route_slug.'get_notification_count', 
																					'uses'	=> 'Common\CommonDataController@get_notification_count']);

	Route::get('notifications',              									[	'as'	=> $route_slug.'notification', 
																					'uses'	=> 'Common\CommonDataController@get_all_notifications']);

	Route::get('notifications/delete/{enc_id}',              					[	'as'	=> $route_slug.'notification', 
																					'uses'	=> 'Common\CommonDataController@delete_notifications']);
	
	
	/*----------------------------------------------------------------------------------------
										Searching Routes  
	----------------------------------------------------------------------------------------*/
	
	Route::group(array('prefix' => '/listing','middleware'=>['front']), function()
	{
		$route_slug        = "";
		$module_controller = "Front\SearchController@";

		Route::get('/',              											[	'as'	=> $route_slug.'listing', 
																					'uses'	=> $module_controller.'index']);

		Route::get('view/{slug}',              									[	'as'	=> $route_slug.'listing', 
																					'uses'	=> $module_controller.'view']);

		Route::get('seller/{enc_id}',              								[	'as'	=> $route_slug.'listing', 
																					'uses'	=> $module_controller.'seller_listing']);

	});

	/*----------------------------------------------------------------------------------------
										Favourites Routes  
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => '/my_collection','middleware'=>['front']), function()
	{
		$route_slug        = "";
		$module_controller = "Front\FavouriteController@";

		Route::get('/',              											[	'as'	=> $route_slug.'my_collection', 
																					'uses'	=> $module_controller.'index']);

		Route::any('add',              											[	'as'	=> $route_slug.'my_collection', 
																					'uses'	=> $module_controller.'add']);

		Route::get('remove/{enc_list_id}',              						[	'as'	=> $route_slug.'my_collection', 
																					'uses'	=> $module_controller.'remove']);		

		Route::get('favourite_list',              								[	'as'	=> $route_slug.'my_collection', 
																					'uses'	=> $module_controller.'favourite_list']);

		Route::any('count',              								        [	'as'	=> $route_slug.'my_collection', 
																					'uses'	=> $module_controller.'count']);																																											
	});

	/*----------------------------------------------------------------------------------------
										Cart Routes  
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => '/cart','middleware'=>['front']), function()
	{
		$route_slug        = "";
		$module_controller = "Front\CartController@";

		Route::get('/',              											[	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'index']);

		Route::any('add',              											[	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'add']);

		Route::any('delete/{enc_cart_id}',              						[	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'delete']);		

		Route::any('cart_count',              								    [	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'cart_count']);

		Route::any('edit',              								    	[	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'edit']);

		Route::any('update',              								    	[	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'update']);																																																																			

		Route::any('get_sublisting',              								[	'as'	=> $route_slug.'cart', 
																					'uses'	=> $module_controller.'get_sublisting']);

	});	

	/*----------------------------------------------------------------------------------------
										Payment Routes  
	----------------------------------------------------------------------------------------*/

	Route::get('/checkout',              									    [	'as'	=> 'payment', 
																					'uses'	=> 'Common\PaymentController@index']);

	Route::group(array('prefix' => '/payment','middleware'=>['front']), function()
	{
		$route_slug        = "";
		$module_controller = "Common\PaymentController@";

		Route::get('paynow',              										[	'as'	=> $route_slug.'payment', 
																					'uses'	=> $module_controller.'payment_handler']);

		Route::any('charge',              										[	'as'	=> $route_slug.'payment', 
																					'uses'	=> $module_controller.'charge_handler']);

		Route::any('cancel',              										[	'as'	=> $route_slug.'payment', 
																					'uses'	=> $module_controller.'cancel']);		

		Route::any('success',              								    	[	'as'	=> $route_slug.'payment', 
																					'uses'	=> $module_controller.'success']);

		Route::any('cardpay',              										[	'as'	=> $route_slug.'payment', 
																					'uses'	=> $module_controller.'cardpay_handler']);
	});



	/*----------------------------------------------------------------------------------------
										Newsletters subscriber   
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => '/subscriber','middleware'=>['front']), function() 
	{
		$route_slug        = "";
		$module_controller = "Front\SubscriberController@";

		Route::any('/',              											[	'as'	=> $route_slug.'subscriber', 
																					'uses'	=> $module_controller.'index']);

																																																						
	});	


	/*----------------------------------------------------------------------------------------
										Terms and conditions 
	----------------------------------------------------------------------------------------*/

	Route::any('terms_condition',              [	'as'	=> 'terms_condition', 
											     	'uses'	=> 'Front\AuthController@check_terms_conditions']);

	/*----------------------------------------------------------------------------------------
										Packages 
	----------------------------------------------------------------------------------------*/

	Route::group(array('prefix' => '/package','middleware'=>['front']), function() 
	{
		$route_slug        = "";
		$module_controller = "Front\SearchController@";

		Route::any('/',              											[	'as'	=> $route_slug.'subscriber', 
																					'uses'	=> $module_controller.'total_package_listing']);

		Route::get('/{slug}',              										[	'as'	=> $route_slug.'listing', 
																					'uses'	=> $module_controller.'package_listing']);
	});	

});
