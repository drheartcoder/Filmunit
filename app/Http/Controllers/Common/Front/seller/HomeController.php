<?php

namespace App\Http\Controllers\Front\seller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Usermodel;
use App\Models\NotificationModel;
use App\Models\OrderDetailsModel;
use App\Models\TransactionModel;
use App\Models\MediaListingDetailModel;
use App\Models\MediaListingMasterModel;

use Sentinel;

class HomeController extends Controller
{
    public function __construct(
                                UserModel $user_model,
                                MediaListingDetailModel $master_listing_details_model,
                                MediaListingMasterModel $master_listing_master_model,
                                OrderDetailsModel $order_details_model,
                                NotificationModel $notify
                                )  
    {
        $this->UserModel                    = $user_model;
		$this->OrderDetailsModel 	        = $order_details_model;
        $this->MediaListingDetailModel      = $master_listing_details_model;
        $this->MediaListingMasterModel      = $master_listing_master_model;
        $this->arr_view_data = [];
    }

    public function index()
    {
        $arr_user       = [];
        $obj_user       = $check_user = false;
        $obj_transaction= false;
        $seller_id      = '';
        $media_count    = $admin_media_count = $transaction_count  = 0;

    	$check_user = Sentinel::check();

    	if(isset($check_user) && $check_user==true && $check_user['role']=='seller')
    	{
            $seller_id = $check_user['id'];

    		$obj_user = $this->UserModel->where('id',$seller_id)->select('id','first_name','last_name','email','contact_number','address','zipcode','country','city')->first();
    		if($obj_user!=false)
    		{
    			$arr_user = $obj_user->toArray();
    		}

            $admin_media_count = $this->MediaListingMasterModel->where('seller_id',$seller_id)->where('is_admin_uploaded','1')->count();
            $media_count       = $this->MediaListingMasterModel->where('seller_id',$seller_id)->where('is_admin_uploaded','0')->count();
            $obj_transaction   = $this->OrderDetailsModel->where('seller_id',$seller_id)->groupBy('transaction_id')->get();
            if($obj_transaction!=false)
            {
                $transaction_count = count($obj_transaction);
            }
        }

        $this->arr_view_data['arr_user']             = $arr_user;
        $this->arr_view_data['admin_media_count']    = $admin_media_count;
        $this->arr_view_data['media_count']          = $media_count;
    	$this->arr_view_data['transaction_count']    = $transaction_count;
    	$this->arr_view_data['title']                = "Dashboard - ".config('app.project.name');
       	return view('front.seller.dashboard',$this->arr_view_data);
    }

}
