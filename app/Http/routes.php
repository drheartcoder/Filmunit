<?php


// dump($users);

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

/*----------------------------------------------------------------------------------------
	Admin Roles
----------------------------------------------------------------------------------------*/

Route::group(array('prefix' => '/','middleware'=>['front']), function()
{
	$route_slug       = "";
	$module_controller = "Front\AuthController@";

	Route::get('',['as' => $route_slug.'index',  'uses' => $module_controller.'index']);
});

/*---------------------------------------------------------------------------------------
	End
-----------------------------------------------------------------------------------------*/

$admin_path = config('app.project.admin_panel_slug');

Route::group(['middleware' => ['web']], function ()  use($admin_path) 
{
	/* Admin Routes */

	Route::group(['prefix' => $admin_path,'middleware'=>['admin']], function () 
	{

		$module_controller = "Admin\AuthController@";
		$route_slug        = "admin_auth_";
		
	   /*----------------------------------------------------------------------------------------
			Admin Home Route  
		----------------------------------------------------------------------------------------*/

		Route::get('/',              													[	'as'	=> $route_slug.'login', 
																							'uses'	=> $module_controller.'login']);	
		
		Route::get('login',          													[	'as'	=> $route_slug.'login',         
																							'uses'	=> $module_controller.'login']);	
		
		Route::post('process_login',  													[	'as'	=> $route_slug.'process_login',
																							'uses'	=> $module_controller.'process_login']);	
		
		Route::get('change_password', 													[	'as'	=> $route_slug.'change_password',
																							'uses'	=> $module_controller.'change_password']);	
		Route::post('update_password',													[	'as'	=> $route_slug.'update_password' ,
																							'uses'	=> $module_controller.'update_password']);	
		
		Route::post('process_forgot_password',											[	'as'	=> $route_slug.'forgot_password',
																							'uses'	=> $module_controller.'process_forgot_password']);
		
		Route::get('validate_admin_reset_password_link/{enc_id}/{enc_reminder_code}', 	[	'as'	=> $route_slug.'validate_admin_reset_password_link',
																							'uses'	=> $module_controller.'validate_reset_password_link']);
		
		Route::post('reset_password',													[	'as'	=> $route_slug.'reset_passsword',
																							'uses'	=> $module_controller.'reset_password']);

		Route::get('/get_users/{user_type}',											[	'as'		=> $route_slug.'get_users',
																							'uses'		=>'Admin\DashboardController@get_users']);	
		
		/*----------------------------------------------------------------------------------------
			Dashboard  
		----------------------------------------------------------------------------------------*/

		Route::get('/dashboard',						[	'as'		=> $route_slug.'dashboard',
															'uses'		=>'Admin\DashboardController@index']);	
		
		Route::get('/logout',   						[	'as'		=> $route_slug.'logout',
															'uses'		=> $module_controller.'logout']);	

		/*----------------------------------------------------------------------------------------
			Account Settings  
		----------------------------------------------------------------------------------------*/
			Route::group(['prefix'=>'account_settings'],function()
			{
				$module_controller 	= "Admin\AccountSettingsController@";
				$route_slug 		= "account_settings";

				Route::get('/',                        			[  		'as'		=> $route_slug.'index',
																		'uses'		=> $module_controller.'index']);

				Route::post('update_profile',          			[		'as'		=> $route_slug.'update_profile',
											         	 				'uses'		=> $module_controller.'update_profile']);	

				Route::post('update_site_setting',     			[		'as'		=> $route_slug.'update_site_setting',
											         	 				'uses'		=> $module_controller.'update_site_setting']);	

				Route::post('update_site_emails',      			[		'as'		=> $route_slug.'update_site_emails',
											         	 				'uses'		=> $module_controller.'update_site_emails']);	

				Route::post('update_site_contact_details',      [		'as'		=> $route_slug.'update_site_contact_details',
											         	 				'uses'		=> $module_controller.'update_site_contact_details']);	

/*				Route::get('delete/{enc_id}',          [		'as'		=> $route_slug.'delete',
																'uses'		=> $module_controller.'delete']);	

				Route::get('activate/{enc_id}',        [		'as'		=> $route_slug.'activate',
																'uses'		=> $module_controller.'activate']);	

				Route::get('deactivate/{enc_id}',      [		'as'		=> $route_slug.'deactivate',
																'uses'		=> $module_controller.'deactivate']);

				Route::post('multi_action',            [		'as'		=> $route_slug.'multi_action',
																'uses'		=> $module_controller.'multi_action']);	
				
				Route::get('/get_records',             [		'as' 		=> $route_slug.'get_records',
																'uses' 		=> $module_controller.'get_records']);	*/
			});

		//module_permission:categories.list|categories.create|categories.update|categories.delete

		/*----------------------------------------------------------------------------------------
			Contact Enquiry 
		----------------------------------------------------------------------------------------*/

			Route::group(array('prefix' => '/notifications'), function()
			{
				$route_slug       =  "notifications";
				$module_controller = "Admin\NotificationsController@";
				$module_slug       = "notifications";

				Route::get('/', 						['as' 		=> $route_slug.'manage',				'uses' 		=> $module_controller.'index']);
				Route::post('/get',	        			['as'       => $route_slug.'get_notifications',		'uses'		=> $module_controller.'get_notifications']);	        
				Route::get('/delete/{enc_id}',	        ['as'       => $route_slug.'delete',				'uses'		=> $module_controller.'delete']);	        
		        Route::post('multi_action',		        ['as'       => $route_slug.'multi_action',			'uses'		=> $module_controller.'multi_action']);	
			});

		/*----------------------------------------------------------------------------------------
			Categories  
		----------------------------------------------------------------------------------------*/

			Route::group(['prefix'=>'photos_and_footage'],function()
			{
				$route_slug       = "photos_and_footage_";
				$module_controller = "Admin\PhotosAndFootageController@";

				Route::get('/',                        [  		'as'		=> $route_slug.'index',
																'uses'		=> $module_controller.'index']);	

				Route::get('approve/{enc_id}',         [		'as'		=> $route_slug.'approve',
																'uses'		=> $module_controller.'approve']);	

				Route::post('disapprove',      		   [		'as'		=> $route_slug.'disapprove',
																'uses'		=> $module_controller.'disapprove']);

				Route::get('create',          		   [		'as'		=> $route_slug.'create',
																'uses'		=> $module_controller.'create']);

				Route::get('delete/{enc_id}',          [		'as'		=> $route_slug.'delete',
																'uses'		=> $module_controller.'delete']);

				Route::post('store',                   [		'as'		=> $route_slug.'store',
																'uses'		=> $module_controller.'store']);

				Route::get('edit/{enc_id}',            [		'as'		=> $route_slug.'edit',
																'uses'		=> $module_controller.'edit']);	
																	
				Route::post('update/{enc_id}',         [		'as'		=> $route_slug.'update',
											         	 		'uses'		=> $module_controller.'update']);

				Route::get('clone/{enc_id}',           [	    'as'		=> $route_slug.'clone_photo_footage', 
																'uses'		=> $module_controller.'clone_photo_footage']);											         	 			

				Route::post('store_clone',             [		'as'		=> $route_slug.'store_clone',
																'uses'		=> $module_controller.'store_clone']);

				Route::get('view/{enc_id}',            [		'as'		=> $route_slug.'view',
																'uses'		=> $module_controller.'view']);

				Route::post('upload_replica',      	   [		'as'		=> $route_slug.'upload_replica',
																'uses'		=> $module_controller.'upload_replica']);

				Route::post('update_commission',       [		'as'		=> $route_slug.'update_commission',
																'uses'		=> $module_controller.'update_commission']);																																	

				Route::get('/get_records',             [		'as' 		=> $route_slug.'get_records',
																'uses' 		=> $module_controller.'get_records']);	

				Route::post('multi_action',            [		'as'		=> $route_slug.'multi_action',
																'uses'		=> $module_controller.'multi_action']);

				Route::get('activate/{enc_id}',        [		'as'		=> $route_slug.'activate',
																'uses'		=> $module_controller.'activate']);	

				Route::get('deactivate/{enc_id}',      [		'as'		=> $route_slug.'deactivate',
																'uses'		=> $module_controller.'deactivate']);
			});

		/*----------------------------------------------------------------------------------------
			Categories  
		----------------------------------------------------------------------------------------*/

/*			Route::group(['prefix'=>'categories'],function()
			{
				$route_slug       = "admin_category_";
				$module_controller = "Admin\CategoryController@";

				Route::get('/',                        [  		'as'		=> $route_slug.'index',
																'uses'		=> $module_controller.'index']);	

				Route::get('/sub_categories/{enc_id}', [		'as'		=> $route_slug.'subcategory_index',
																'uses'		=> $module_controller.'index_sub_category']);		

				Route::get('create/{enc_id?}',         [		'as'		=> $route_slug.'create',
																'uses'		=> $module_controller.'create']);	

				Route::post('store',                   [		'as'		=> $route_slug.'store',
																'uses'		=> $module_controller.'store']);	

				Route::get('edit/{enc_id}',            [		'as'		=> $route_slug.'edit',
																'uses'		=> $module_controller.'edit']);	

				Route::post('update/{enc_id}',         [		'as'		=> $route_slug.'update',
											         	 		'uses'		=> $module_controller.'update']);	

				Route::get('delete/{enc_id}',          [		'as'		=> $route_slug.'delete',
																'uses'		=> $module_controller.'delete']);	

				Route::get('activate/{enc_id}',        [		'as'		=> $route_slug.'activate',
																'uses'		=> $module_controller.'activate']);	

				Route::get('deactivate/{enc_id}',      [		'as'		=> $route_slug.'deactivate',
																'uses'		=> $module_controller.'deactivate']);

				Route::post('multi_action',            [		'as'		=> $route_slug.'multi_action',
																'uses'		=> $module_controller.'multi_action']);	
				
				Route::get('/get_records',             [		'as' 		=> $route_slug.'get_records',
																'uses' 		=> $module_controller.'get_records']);	
			});*/

		
		/*----------------------------------------------------------------------------------------
			Contact Enquiry 
		----------------------------------------------------------------------------------------*/

			Route::group(array('prefix'=>'/contact_enquiry'), function ()
			{
				$route_slug       = "admin_contact_enquiry_";
				$route_controller = "Admin\ContactEnquiryController@";

				Route::get('/',					  		[		'as' 		=> $route_slug.'index',
														   		'uses'		=> $route_controller.'index']);

				Route::get('/view/{enc_id}',	   		[		'as' 		=> $route_slug.'details',
																'uses'		=> $route_controller.'view']);

				Route::get('delete/{enc_id}',	   		[		'as' 		=> $route_slug.'delete',
																'uses'		=> $route_controller.'delete']);

				Route::post('multi_action',		   		[		'as'		=> $route_slug.'multi_action',
																'uses'		=> $route_controller.'multi_action']);	
			});

		/*----------------------------------------------------------------------------------------
			Faq 
		----------------------------------------------------------------------------------------*/

			Route::group(array('prefix' => '/faq'), function()
			{
				$route_slug       = 'admin_faq_';
				$route_controller = 'Admin\FAQController@';

				Route::get('/',							[		'as'		=> $route_slug.'index', 
																'uses' 		=> $route_controller.'index']);
				
				Route::get('/create',					[		'as' 		=> $route_slug.'create', 
									  							'uses' 		=> $route_controller.'create']);

				Route::post('/store',					[		'as' 		=> $route_slug.'store', 
									  							'uses' 		=> $route_controller.'store']);

				Route::get('/edit/{enc_id}',			[		'as' 		=> $route_slug.'edit', 
																'uses'		=> $route_controller.'edit']);

				Route::post('/update/{enc_id}',			[		'as' 		=> $route_slug.'update', 
											 					'uses' 		=> $route_controller.'update']);

				Route::get('/delete/{enc_id}',			[		'as' 		=> $route_slug.'edit', 
											   					'uses' 		=> $route_controller.'delete']);

				Route::get('activate/{enc_id}',			[		'as' 		=> $route_slug.'activate',
																'uses'		=> $route_controller.'activate']);	

				Route::get('deactivate/{enc_id}',		[		'as' 		=> $route_slug.'deactivate',
												  				'uses' 		=> $route_controller.'deactivate']);

				Route::post('multi_action',				[		'as'		=> $route_slug.'multi_action',
																'uses' 		=> $route_controller.'multi_action']);
		 		
			});


		/*----------------------------------------------------------------------------------------
			Users 
		----------------------------------------------------------------------------------------*/
			

			Route::group(array('prefix' => '/users'), function()
			{
				$route_slug       = "users";
				$module_controller = "Admin\UserController@";

				Route::get('/', 						[		'as' 		=> $route_slug.'manage',
														 		'uses' 		=> $module_controller.'index']);

				Route::get('get_records', 	 			[		'as' 		=> $route_slug.'manage',
												 				'uses' 		=> $module_controller.'get_records']);

				Route::get('view/{enc_id}',	     		[		'as' 		=> $route_slug.'view',
															 	'uses' 		=> $module_controller.'view']);

				Route::get('update',	     			[		'as' 		=> $route_slug.'update',
															 	'uses' 		=> $module_controller.'update']);

				Route::get('unblock/{enc_id}',  		[		'as'		=> $route_slug.'activate',
															 	'uses' 		=> $module_controller.'unblock']);	

				Route::get('block/{enc_id}',			[		'as'		=> $route_slug.'deactivate',
															 	'uses'		=> $module_controller.'block']);	

				Route::get('activate/{enc_id}',  		[		'as'		=> $route_slug.'activate',
															 	'uses' 		=> $module_controller.'activate']);	

				Route::get('deactivate/{enc_id}',		[		'as'		=> $route_slug.'deactivate',
															 	'uses'		=> $module_controller.'deactivate']);

			});

		
		/*----------------------------------------------------------------------------------------
			Static Pages - CMS
		----------------------------------------------------------------------------------------*/

			Route::group(array('prefix' => '/static_pages'), function()
			{
				$route_slug       = "static_pages_";
				$module_controller = "Admin\StaticPageController@";

				Route::get('/', 				 		[		'as'	 	=> $route_slug.'manage',
																'uses' 		=> $module_controller.'index']);

				Route::get('create',			 		[		'as' 		=> $route_slug.'create',
																'uses'		=> $module_controller.'create']);

				Route::get('edit/{enc_id}',		 		[		'as' 		=> $route_slug.'edit',
																'uses' 		=> $module_controller.'edit']);

				Route::any('store',				 		[		'as' 		=> $route_slug.'store',
																'uses' 		=> $module_controller.'store']);
  

				Route::post('update/{enc_id}',	 		[		'as' 		=> $route_slug.'update',
																'uses' 		=> $module_controller.'update']);


				Route::get('delete/{enc_id}',	 		[		'as' 		=> $route_slug.'delete',
																'uses' 		=> $module_controller.'delete']);
	

				Route::get('activate/{enc_id}',  		[		'as' 		=> $route_slug.'activate',
																'uses'		=> $module_controller.'activate']);


				Route::get('deactivate/{enc_id}',		[		'as'		=> $route_slug.'deactivate',
																'uses' 		=> $module_controller.'deactivate']);
	

				Route::post('multi_action',		 		[		'as' 		=> $route_slug.'multi_action',
																'uses' 		=> $module_controller.'multi_action']);
	
			});

		
		/*---------------------------------------------------------------------------------------
		|	Email Template
		-----------------------------------------------------------------------------------------*/

			Route::group(array('prefix' => '/email_template'), function()
			{
				$route_slug        = "admin_email_template_";
				$module_controller = "Admin\EmailTemplateController@";

				Route::get('create',					[		'as'		=> $route_slug.'create',
									 						 	'uses' 		=> $module_controller.'create']);


				Route::post('store/',					[		'as' 		=> $route_slug.'store',
				 					  							'uses' 		=> $module_controller.'store']);


				Route::get('edit/{enc_id}',				[		'as' 		=> $route_slug.'edit',
					 											'uses' 		=> $module_controller.'edit']);


				Route::get('view/{enc_id}/{act_lng}',	[		'as' 		=> $route_slug.'edit',
					 											'uses' 		=> $module_controller.'view']);


				Route::post('update/{enc_id}',			[		'as'		=> $route_slug.'update',
											   					'uses' 		=> $module_controller.'update']);


				Route::get('/',							[		'as' 		=> $route_slug.'index', 
																'uses' 		=> $module_controller.'index']);

			});

		
		/*----------------------------------------------------------------------------------------
			Site Settings
		----------------------------------------------------------------------------------------*/

			Route::get('site_settings', 				[		'as' 		=> 'site_settings',
																'uses' 		=> 'Admin\SiteSettingController@index']);

			Route::get('social', 						[		'as' 		=> 'social',
																'uses' 		=> 'Admin\SiteSettingController@index']);

			Route::post('social/update/{enc_id}',		[		'as' 		=> 'social',
																'uses' 		=> 'Admin\SiteSettingController@update']);

		
		/*----------------------------------------------------------------------------------------
			User Module
		----------------------------------------------------------------------------------------*/

			Route::group(array('prefix' => '/users'), function()
			{	
				$route_slug        = "admin_traveller_";
				$module_controller = "Admin\UserController@";

				Route::get('/',							[		'as' 		=> $route_slug.'index',
																'uses' 		=> $module_controller.'index']);

				Route::get('create/',					[		'as'		=> $route_slug.'create',
																'uses' 		=> $module_controller.'create']);

				Route::post('store/',					[		'as' 		=> $route_slug.'store',
																'uses' 		=> $module_controller.'store']);

				Route::get('edit/{enc_id}',				[		'as' 		=> $route_slug.'edit',
																'uses' 		=> $module_controller.'edit']);

				Route::post('update',					[		'as' 		=> $route_slug.'update',
																'uses' 		=> $module_controller.'update']);

				Route::get('activate/{enc_id}',			[		'as' 		=> $route_slug.'activate',
																'uses' 		=> $module_controller.'activate']);	

				Route::get('deactivate/{enc_id}',		[		'as'		=> $route_slug.'deactivate',
																'uses'		=> $module_controller.'deactivate']);

				Route::post('multi_action', 			[		'as' 		=> $route_slug.'multi_action',
																'uses' 		=> $module_controller.'multi_action']);

				Route::get('delete/{enc_id}',			[		'as' 		=> $route_slug.'update',
																'uses' 		=> $module_controller.'delete']);
				
				Route::get('/get_records',				[		'as' 		=> $route_slug.'get_records',
																'uses' 		=> $module_controller.'get_records']);
			});


		/*---------------------------------------------------------------------------------------
		|	Testimonial
		-----------------------------------------------------------------------------------------*/		
			Route::group(array('prefix' => '/testimonial'), function()
			{
				$route_slug        = "testimonial_";
				$module_controller = "Admin\TestimonialController@";

				Route::get('/',							[		'as' 		=> $route_slug.'index',
																'uses' 		=> $module_controller.'index']);

				Route::get('/create',					[		'as' 		=> $route_slug.'create',
																'uses' 		=> $module_controller.'create']);

				Route::post('/store',					[		'as' 		=> $route_slug.'store',
																'uses' 		=> $module_controller.'store']);

				Route::get('/edit/{enc_id}',			[		'as' 		=> $route_slug.'edit',
																'uses' 		=> $module_controller.'edit']);

				Route::post('/update/{enc_id}',			[		'as'		=> $route_slug.'update',
																'uses'		=> $module_controller.'update']);

				Route::get('/delete/{enc_id}',			[		'as' 		=> $route_slug.'delete',
																'uses' 		=> $module_controller. 'delete']);

				Route::get('activate/{enc_id}',			[		'as'		=> $route_slug.'activate',
																'uses' 		=> $module_controller.'activate']);

				Route::get('deactivate/{enc_id}',		[		'as' 		=> $route_slug.'deactivate',
																'uses' 		=> $module_controller.'deactivate']);

				Route::post('multi_action',				[		'as' 		=> $route_slug.'multi_action',
																'uses' 		=> $module_controller.'multi_action']);
		 		
			});

		
		/*----------------------------------------------------------------------------------------
		    News 
		----------------------------------------------------------------------------------------*/
			Route::group(array('prefix' => '/news'), function()
			{
				$route_slug       = "news_";
				$module_controller = "Admin\NewsController@";

				Route::get('/', 					 	[		'as' 		=> $route_slug.'manage',
																'uses' 		=> $module_controller.'index']);

				Route::get('create',			 		[		'as' 		=> $route_slug.'create',
																'uses' 		=> $module_controller.'create']);
				
				Route::get('edit/{enc_id}',				[		'as' 		=> $route_slug.'edit',
																'uses' 		=> $module_controller.'edit']);
				
				Route::any('store',				 		[		'as' 		=> $route_slug.'store',
																'uses' 		=> $module_controller.'store']);
				
				Route::post('update',	         		[		'as' 		=> $route_slug.'update',
																'uses'		=> $module_controller.'update']);
				
				Route::get('delete/{enc_id}',	 		[		'as' 		=> $route_slug.'delete',
																'uses'		=> $module_controller.'delete']);	
				
				Route::get('activate/{enc_id}',  		[		'as' 		=> $route_slug.'activate',
																'uses' 		=> $module_controller.'activate']);	
				
				Route::get('deactivate/{enc_id}',		[		'as'		=> $route_slug.'deactivate',
																'uses' 		=> $module_controller.'deactivate']);	
				
				Route::post('multi_action',		 		[		'as' 		=> $route_slug.'multi_action',
																'uses' 		=> $module_controller.'multi_action']);	

			});
	
		/*----------------------------------------------------------------------------------------
		    NewsLetter
		----------------------------------------------------------------------------------------*/
		

			Route::group(['prefix'=>'/newsletter'],function()
		    {
		    	$route_slug       = "newsletter_";
		    	$newsletter_controller = "Admin\NewsLetterController@";

		        Route::get('/',							[		'as'		=> $route_slug.'manage',
		        												'uses'		=> $newsletter_controller.'index']);	
		        
		        Route::get('/create',					[		'as'		=> $route_slug.'create',
		        												'uses'		=> $newsletter_controller.'create']);	
		        
		        Route::post('/store',					[		'as'		=> $route_slug.'store',
		        												'uses'		=> $newsletter_controller.'store']);	
		        
		        Route::get('/edit/{enc_id}',			[		'as'		=> $route_slug.'edit',
		        												'uses'		=> $newsletter_controller.'edit']);	
		        
		        Route::post('/update/{enc_id}',			[		'as'		=> $route_slug.'update',
		        												'uses'		=> $newsletter_controller.'update']);	
		        
		        Route::get('delete/{enc_id}',			[		'as'		=> $route_slug.'delete',
		        												'uses'		=> $newsletter_controller.'delete']);		
				
				Route::get('activate/{enc_id}',			[		'as'		=> $route_slug.'activate',
																'uses'		=> $newsletter_controller.'activate']);		
				
				Route::get('deactivate/{enc_id}',		[		'as'		=> $route_slug.'deactivate',
																'uses'		=> $newsletter_controller.'deactivate']);		
				
				Route::post('/multi_action',			[		'as'		=> $route_slug.'multi_action',
																'uses'		=> $newsletter_controller.'multi_action']);	
				
				Route::get('/send',						[		'as'		=> $route_slug.'send',
																'uses'		=> $newsletter_controller.'send']);	
				
				Route::post('/send_email',				[		'as'		=> $route_slug.'send',
																'uses'		=> $newsletter_controller.'send_email']);		
				
				Route::get('/view/{enc_id}',	    	[		'as'		=> $route_slug.'view',
																'uses'		=> $newsletter_controller.'view']);	
			});			

		/*----------------------------------------------------------------------------------------
		    Newsletter subscriber 
		----------------------------------------------------------------------------------------*/

			Route::group(['prefix'=>'/newsletter/subscriber'],function()
		    {
		    	$newsletter_subscriber_controller = "Admin\SubscribersController@";
		    	$route_slug ='subscriber_';

		        Route::get('/',							[		'as'		=> $route_slug.'manage',
		        												'uses'		=> $newsletter_subscriber_controller.'index']);	
		        
		        Route::get('export/',					[		'as'		=> $route_slug.'export',
		        												'uses'		=> $newsletter_subscriber_controller.'export']);	
		        
		        Route::get('delete/{enc_id}',			[		'as'		=> $route_slug.'delete',
		        												'uses'		=> $newsletter_subscriber_controller.'delete']);		
				
				Route::get('activate/{enc_id}',			[		'as'		=> $route_slug.'activate',
																'uses'		=> $newsletter_subscriber_controller.'activate']);		
				
				Route::get('deactivate/{enc_id}',		[		'as'		=> $route_slug.'deactivate',
																'uses'		=> $newsletter_subscriber_controller.'deactivate']);		
				
				Route::post('multi_action',				[		'as'		=> $route_slug.'multi_action',
																'uses'		=> $newsletter_subscriber_controller.'multi_action']);		       
		    });

		/*----------------------------------------------------------------------------------------
		    Website Commission 
		----------------------------------------------------------------------------------------*/

				$module_controller = "Admin\CommissionController@";
				$route_slug="commission_";

				Route::get('commission',				[		'as'		=> $route_slug."index",
																'uses'		=> $module_controller.'index']);

				Route::post('commission/update',		[		'as'		=> $route_slug."update",
																'uses'		=> $module_controller.'update']);


		/*----------------------------------------------------------------------------------------
		    Download Attempts
		----------------------------------------------------------------------------------------*/

				$module_controller = "Admin\DownloadController@";
				$route_slug="download";

				Route::get('download',				[		'as'		=> $route_slug."index",
																'uses'		=> $module_controller.'index']);

				Route::post('download/update',		[		'as'		=> $route_slug."update",
																'uses'		=> $module_controller.'update']);

		
		/*----------------------------------------------------------------------------------------
		    Website Bookings 
		----------------------------------------------------------------------------------------*/

				
			Route::group(array('prefix'=>'/booking'),function()
			{
				$route_slug = 'booking_';
				$module_controller = 'Admin\BookingController@';

				Route::get('/buyer',	    		            [		'as'		=> $route_slug.'index',
																		'uses'		=> $module_controller.'index']);
				
				Route::get('buyer/get_records',		            [		'as'		=> $route_slug.'get_buyer_records',
																		'uses'		=> $module_controller.'get_buyer_records']);

				Route::get('/seller',	    		         	[		'as'		=> $route_slug.'seller_list',
																		'uses'		=> $module_controller.'seller_list']);
				
				Route::get('seller/get_records',		        [		'as'		=> $route_slug.'get_seller_records',
																  		'uses'		=> $module_controller.'get_seller_records']);

				Route::get('export', 					        [		'as'		=> $route_slug.'view',
																	    'uses'		=> $module_controller.'export']);

				Route::get('export_seller', 					        [		'as'		=> $route_slug.'export_seller',
																	    'uses'		=> $module_controller.'export_seller']);

				Route::get('usertype',					        [		'as'		=> $route_slug.'users',
																		'uses'		=> $module_controller.'users']);
				
				Route::any('seller/view/{enc_id}/{seller_id}',  [		'as'		=> $route_slug.'seller_view',
																		'uses'		=> $module_controller.'seller_view']);

				Route::any('seller/paid/{enc_id}/{seller_id}',  [		'as'		=> $route_slug.'paid',
																		'uses'		=> $module_controller.'paid']);

				Route::any('seller/unpaid/{enc_id}/{seller_id}',[		'as'		=> $route_slug.'unpaid',
																		'uses'		=> $module_controller.'unpaid']);																						
				
				Route::any('buyer/view/{enc_id}',				[		'as'		=> $route_slug.'view',
																		'uses'		=> $module_controller.'view']);

				Route::any('seller/invoice/{enc_id}/{seller_id}',[		'as'		=> $route_slug.'invoice',
																		'uses'		=> $module_controller.'invoice']);

			});

		/*----------------------------------------------------------------------------------------
			Packs 
		----------------------------------------------------------------------------------------*/

			Route::group(array('prefix' => '/packs'), function()
			{
				$route_slug       =  "packs";
				$module_controller = "Admin\PackageController@";
				$module_slug       = "packs";

				Route::get('/', 						    [	'as' 		=> $route_slug.'manage',
																'uses' 		=> $module_controller.'index']);
				
				Route::get('/create',	        			[	'as'       => $route_slug.'create',
																'uses'		=> $module_controller.'create']);

				Route::post('/store',	        			[	'as'       => $route_slug.'store',
																'uses'		=> $module_controller.'store']);																	        

				Route::get('edit/{enc_id}',        			[	'as'		=> $route_slug.'edit',
																'uses'		=> $module_controller.'edit']);

				Route::post('update/{enc_id}',        		[	'as'		=> $route_slug.'update',
																'uses'		=> $module_controller.'update']);																	

				Route::get('view/{enc_id}',        			[	'as'		=> $route_slug.'view',
																'uses'		=> $module_controller.'view']);	

				Route::get('activate/{enc_id}',        		[	'as'		=> $route_slug.'activate',
																'uses'		=> $module_controller.'activate']);	

				Route::get('deactivate/{enc_id}',      		[	'as'		=> $route_slug.'deactivate',
																'uses'		=> $module_controller.'deactivate']);

				Route::get('delete/{enc_id}',      			[	'as'		=> $route_slug.'delete',
																'uses'		=> $module_controller.'delete']);

				Route::post('multi_action',            		[	'as'		=> $route_slug.'multi_action',
																'uses'		=> $module_controller.'multi_action']);
				
				Route::get('/manage_index_records',          [	'as' 		=> $route_slug.'manage_index_records',
																'uses' 		=> $module_controller.'manage_index_records']);					

				Route::get('/get_records',             		[	'as' 		=> $route_slug.'get_records',
																'uses' 		=> $module_controller.'get_records']);	

				Route::get('/get_edit_records',             [	'as' 		=> $route_slug.'get_edit_records',
																'uses' 		=> $module_controller.'get_edit_records']);	

				Route::any('/update_order',             	[	'as' 		=> $route_slug.'update_order',
																'uses' 		=> $module_controller.'update_order']);																																																	
			});

	});	    
	
	/*------------------------------------------
	| Front Route starts Here
	--------------------------------------------*/	
	Route::group(['middleware'=>['front']], function () 
	{
		 include(app_path('Http/Routes/front.php'));
	});
	/*------------	Ends ---------------*/

	/*------------------------------------------
	| Buyer Route starts Here
	--------------------------------------------*/	
	Route::group(['middleware'=>['front']], function () 
	{
		 include(app_path('Http/Routes/buyer.php'));
	});
	/*------------	Ends ---------------*/

	/*------------------------------------------
	| Seller Route starts Here
	--------------------------------------------*/	
	Route::group(['middleware'=>['front']], function () 
	{
		 include(app_path('Http/Routes/seller.php'));
	});
	/*------------	Ends ---------------*/

});

