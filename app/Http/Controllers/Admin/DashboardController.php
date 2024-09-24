<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\UserModel;
use App\Models\MediaListingMasterModel;
use App\Models\PackageModel;
use App\Models\BookingModel;
use App\Models\OrderDetailsModel;
use App\Models\TransactionModel;
use Sentinel;
use DB;

class DashboardController extends Controller
{
	public function __construct(UserModel $user, BookingModel $booking_model, 
								OrderDetailsModel $order_details_model, MediaListingMasterModel $media_listing_model,
								TransactionModel $transaction_model)
	{
		$this->arr_view_data            = [];
		$this->module_title             = "Dashboard";
		$this->UserModel                = $user;
		$this->BookingModel             = $booking_model;
		$this->OrderDetailsModel 	    = $order_details_model;
		$this->MediaListingMasterModel  = $media_listing_model;
		$this->TransactionModel 	    = $transaction_model;
		$this->module_view_folder	    = "admin.dashboard";
		$this->admin_url_path           = url(config('app.project.admin_panel_slug'));
	}
   
    public function index(Request $request)
    {	
    	$buyer_count  = $seller_count =  $photos_and_footage_count =  $packages_count =  $buyer_booking_count = $seller_booking_count = 
    	$price = 0;

		$buyer_count			  				= $this->UserModel->where('role','=','buyer')->count();

		$seller_count			 			    = $this->UserModel->where('role','=','seller')->count();

		$photos_and_footage_count 				= $this->MediaListingMasterModel->where('is_approved','=',1)->count();

		$photos_and_footage_disapprove_count    = $this->MediaListingMasterModel->where('is_approved','=',2)->count();
  

		$price = $this->TransactionModel->whereMonth('transaction_date', '=', date('m'))
										->whereYear('transaction_date', '=', date('Y'))
									    ->sum('total');
		

		$packages_count           = PackageModel::count();

		$buyer_booking_count	  = $this->BookingModel->where('status','=','paid')->count(); 

		$seller_booking_count	  = $this->OrderDetailsModel->where('seller_status','=','paid')->groupBy('transaction_id')->get(); 

		$seller_booking_counts	  = $seller_booking_count->count();

    	$arr_tile_color = array('tile-grey');

    	$this->arr_view_data['page_title']                				 = $this->module_title;
    	$this->arr_view_data['admin_url_path']             				 = $this->admin_url_path;
    	$this->arr_view_data['buyer_count']               			     = $buyer_count;
    	$this->arr_view_data['seller_count']   		                     = $seller_count;
    	$this->arr_view_data['photos_and_footage_count']   			     = $photos_and_footage_count;
    	$this->arr_view_data['photos_and_footage_disapprove_count']      = $photos_and_footage_disapprove_count;
    	$this->arr_view_data['packages_count']   		                 = $packages_count;
    	$this->arr_view_data['buyer_booking_count']   	                 = $buyer_booking_count;
    	$this->arr_view_data['seller_booking_counts']                    = $seller_booking_counts;
    	$this->arr_view_data['price']                    				 = $price;
    	$this->arr_view_data['arr_tile_color']                           = $arr_tile_color;
    	$this->arr_view_data['arr_final_tile']                           = $this->built_dashboard_tiles($request);

    	return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function built_dashboard_tiles($request)
    {
    	/*------------------------------------------------------------------------------
    	| Note: Directly Use icon name - like, fa fa-user and use directly - 'user'
    	------------------------------------------------------------------------------*/
		
		$arr_final_tile = [];

		$user = Sentinel::check();
      
  	    $admin_type = "";
	    
  	    if($user)
  	    {
			$arr_final_tile[] = ['module_slug'  => 'account_settings',
								  'css_class'   => 'cogs',
								  'module_title'=> 'Account Settings'];



			$arr_final_tile[] = ['module_slug'  => 'email_template',
								  'css_class'   => 'envelope',
								  'module_title'=> 'Email Templates'];


			$arr_final_tile[] = ['module_slug'  => 'notifications',
								  'css_class'   => 'bell',
								  'module_title'=> 'Notifications'];

			$arr_final_tile[] = ['module_slug'  => 'photos_and_footage',
								  'css_class'   => 'image',
								  'module_title'=> 'Photos And Footage'];

			$arr_final_tile[] = ['module_slug'  => 'packs',
								  'css_class'   => 'briefcase',
								  'module_title'=> 'Package'];								  


  	    	$arr_final_tile[] = ['module_slug'  => 'users',
								  'css_class'   => 'users',
								  'module_title'=> 'Users'];

  	    	$arr_final_tile[] = ['module_slug'  => 'booking/buyer',
								  'css_class'   => 'dollar',
								  'module_title'=> 'Bookings'];

			$arr_final_tile[] = ['module_slug'  => 'commission',
								  'css_class'   => 'money',
								  'module_title'=> 'Commission'];

			$arr_final_tile[] = ['module_slug'  => 'download',
								  'css_class'   => 'download',
								  'module_title'=> 'Downlaod Attempts'];								  								  								  	

		}		
		return 	$arr_final_tile;						  
    }

}

