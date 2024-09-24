<?php

namespace App\Http\Controllers\Front\buyer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Usermodel;
use App\Models\OrderDetailsModel;
use App\Models\TransactionModel;
use App\Models\FavouriteModel;

use Sentinel;

class HomeController extends Controller
{
    public function __construct(
                                UserModel $user_model,
                                OrderDetailsModel $order_details_model,
                                FavouriteModel $favourite_model,
                                TransactionModel $transaction_model
                                )  
    {
        $this->UserModel         = $user_model;
        $this->OrderDetailsModel = $order_details_model;
        $this->FavouriteModel    = $favourite_model;
		$this->TransactionModel  = $transaction_model;
        $this->arr_view_data     = [];
    }

    public function index()
    {
    	$arr_user 	    = [];
    	$obj_user 	    = $check_user = false;
        $obj_downlaod   = false;
        $obj_favourite  = false;
        $obj_transaction= false;
        $arr_downlaod   = [];
        $arr_favourite  = [];
        $arr_transaction= [];
    	$buyer_id       = '';
        $download_count = $favourite_count = $transction_count =0;

    	$check_user = Sentinel::check();

    	if(isset($check_user) && $check_user==true && $check_user['role']=='buyer')
    	{
    		$buyer_id = $check_user['id'];

    		$obj_user = $this->UserModel->where('id',$buyer_id)->select('id','first_name','last_name','email','contact_number','address','zipcode','country','city')->first();
    		if($obj_user!=false)
    		{
    			$arr_user = $obj_user->toArray();
    		}

            $obj_downlaod = $this->OrderDetailsModel->where('buyer_id',$buyer_id)->get();
            if($obj_downlaod!=false)
            {
                $arr_downlaod = $obj_downlaod->toArray();
                $download_count = count($arr_downlaod); 
            }

            $obj_favourite = $this->FavouriteModel->where('buyer_id',$buyer_id)->get();
            if($obj_favourite!=false)
            {
                $arr_favourite   = $obj_favourite->toArray();
                $favourite_count = count($arr_favourite); 
            }

            $obj_transaction = $this->TransactionModel->where('buyer_id',$buyer_id)->get();
            if($obj_transaction!=false)
            {
                $arr_transaction   = $obj_transaction->toArray();
                $transction_count = count($arr_transaction); 
            }                        
    	}

        $this->arr_view_data['arr_user']         = $arr_user;
        $this->arr_view_data['download_count']   = $download_count;
        $this->arr_view_data['favourite_count']  = $favourite_count;
    	$this->arr_view_data['transction_count'] = $transction_count;
    	$this->arr_view_data['title']            = "Dashboard - ".config('app.project.name');
       	return view('front.buyer.dashboard',$this->arr_view_data);
    }

}
