<?php

namespace App\Http\Controllers\Front\buyer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\TransactionModel;
use App\Models\OrderDetailsModel;

use Sentinel;
use Flash;
use DB;

class FinanceController extends Controller
{
    public function __construct(
	    							TransactionModel $transaction_model,
	    							OrderDetailsModel $order_details_model
    							)  
    {   
  		$this->TransactionModel 		          = $transaction_model;
  		$this->OrderDetailsModel 		          = $order_details_model;
          
      $this->module_url_path          	      = url('/')."/buyer/finance";
      $this->photos_base_img_path               = config('app.project.img_path.photos');
    	$this->invoice_public_img_path            = url('/').'/'.config('app.project.invoice');
      $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
      $this->admin_footage_image_public_path    = url('/').'/'.config('app.project.img_path.admin_footage_image');      
    }

    public function index()
    {
  	  $user     = false;
      $obj_data = false;
      $arr_pagination = [];
      $arr_data = [];

      $user = Sentinel::check();
      
      if($user!=false && $user['role']=='buyer')
      {
        $buyer_id = $user['id'];
        $obj_data = $this->TransactionModel->where('buyer_id',$buyer_id)
                                           ->where('status','paid')
        								                   ->orderBy('transaction_date','DESC')
        								                   ->paginate(10);

        if($obj_data!=false)
        {
        	$arr_pagination = $obj_data;
     		$arr_data       = $obj_data->toArray();
        }
        else
        {
          return redirect()->back();
        }
      }
      else
      {
        return redirect()->back();
      }

      $this->arr_view_data['arr_pagination']                      = $arr_pagination;
      $this->arr_view_data['arr_data']                            = $arr_data;

      $this->arr_view_data['module_url_path']                     = $this->module_url_path;
      $this->arr_view_data['invoice_public_img_path']             = $this->invoice_public_img_path;
  	  $this->arr_view_data['title']    							= "Finance - ".config('app.project.name');
     	
     	return view('front.buyer.finance.index',$this->arr_view_data);

    }

    public function view($enc_id)
    {
  	  $user     = false;
      $obj_data = false;
      $arr_data = [];
      $id       = $buyer_id = '';

      $user = Sentinel::check();
      
      if($user!=false && $user['role']=='buyer' && $enc_id!='')
      {
        $id = base64_decode($enc_id);	
        $obj_data = $this->OrderDetailsModel->where('transaction_id',$id)
        									  ->with(['listing_details'=>function($query){
        									  	$query->with('format_details')
        									  	      ->with('master_details');
        									  }])
        								      ->orderBy('id','DESC')
        								      ->get();

        if($obj_data!=false)
        {
     		   $arr_data  = $obj_data->toArray();
        }
      }

      $this->arr_view_data['arr_data']                 = $arr_data;
      $this->arr_view_data['module_url_path']          = $this->module_url_path;
      $this->arr_view_data['invoice_public_img_path']  = $this->invoice_public_img_path;
  	  $this->arr_view_data['title']    							   = "View Finance - ".config('app.project.name');
      $this->arr_view_data['admin_photos_public_img_path']     = $this->admin_photos_public_img_path;
      $this->arr_view_data['admin_footage_image_public_path']  = $this->admin_footage_image_public_path;       
     	
     	return view('front.buyer.finance.view',$this->arr_view_data);
    }    
}
