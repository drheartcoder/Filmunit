<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\UserModel;
use App\Models\MediaListingMasterModel;
use App\Models\MediaListingDetailModel;

use Sentinel;
use Flash;
use DB;
use Session;

class CartController extends Controller
{
    public function __construct(
	    							CartModel $cart_model,
	    							MediaListingMasterModel $media_listing_master_model,
	    							MediaListingDetailModel $media_listing_detail_model,
	    							UserModel $user_model    							
    							   )  
    {   
		$this->CartModel 		              	  = $cart_model;
		$this->UserModel 	 	    	      	  = $user_model;
		$this->MediaListingMasterModel 	 	      = $media_listing_master_model;
		$this->MediaListingDetailModel 	 	      = $media_listing_detail_model;
        $this->arr_view_data 		  		      = [];

        $this->module_url_path          	      = url('/')."/cart";
        $this->photos_base_img_path               = config('app.project.img_path.photos');
    	$this->photos_public_img_path             = url('/').'/'.config('app.project.img_path.photos');
        $this->footage_base_img_path     	      = config('app.project.img_path.footage');
    	$this->footage_public_img_path      	  = url('/').'/'.config('app.project.img_path.footage');
  	    $this->admin_footage_public_img_path      = url('/').'/'.config('app.project.img_path.admin_footage');
  	    $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
    }

	public function index()
	{
		$arr_data = [];

        $status               = false;
        $user                 = false;
        $obj_data             = false;
        $arr_data             = [];
        $arr_data['filesize'] = [];
        $arr_data['size']     = [];
        $visitor_id           = '';
        $buyer_id       	  = 0;

        $visitor_id 	= Session::get('visitor_id');
        
        //Check User is Logged in or not
        $user = Sentinel::check();
        
        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
          $obj_data = $this->CartModel->where('buyer_id',$buyer_id)->with('listing_details.format_details')->with('master_details.listing_details.format_details')->get();
        }
        else
        {
          $obj_data = $this->CartModel->where('visitor_id',$visitor_id)->with('listing_details.format_details')->with('master_details.listing_details.format_details')->get();
        }

        if($obj_data!=false)
        {
        	$arr_data = $obj_data->toArray();

        	$arr_image_size['size']     = [];
			$arr_image_size['filesize'] = [];
			$size     = '';
			$filesize = '';

			foreach ($arr_data as $key => $value)
			{
				if($value['type']=='photo')
				{
						$filesize = filesize($this->photos_base_img_path.$value['listing_details']['enc_item_name'])/(1024*1024);
						$filesize = number_format((float)$filesize, 2, '.', '').' MB';
						array_push($arr_image_size['filesize'], $filesize);

						list($width, $height) = getimagesize($this->photos_public_img_path.$value['listing_details']['enc_item_name']);
						$size = $width ." X ".$height;
						array_push($arr_image_size['size'], $size);
				}
				else
				{
						$filesize = filesize($this->footage_base_img_path.$value['listing_details']['enc_item_name'])/(1024*1024);
						$filesize = number_format((float)$filesize, 2, '.', '').' MB';
						array_push($arr_image_size['filesize'], $filesize);

	 					list($width, $height) = getimagesize($this->footage_base_img_path.$value['listing_details']['enc_item_name']);
						$size = $width ." X ".$height;
						array_push($arr_image_size['size'], $size);
				}
	    	}
        }
        else
        {
            return redirect()->back();
        }

    	$this->arr_view_data['title'] 							    = "Cart - ".config('app.project.name');
    	$this->arr_view_data['module_title'] 		    		    = "Cart";
    	$this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
    	$this->arr_view_data['admin_photos_public_img_path'] 		= $this->admin_photos_public_img_path;
    	$this->arr_view_data['module_url_path'] 		            = $this->module_url_path;
    	$this->arr_view_data['arr_data']     			            = $arr_data;
    	$this->arr_view_data['arr_image_size']     			        = $arr_image_size;

       	return view('front.cart.index',$this->arr_view_data);
	}

    /*------------- Add To cart --------------*/

    public function add(Request $request) 
    {
      if($request->input('enc_master_id')!='' && $request->input('enc_list_id')!='')
      {
        $status                   = false;
        $arr_data                 = [];
        $user                     = false;
        $visitor_id               = $list_id = $master_id = '';
        $obj_check_if_exists      = $buyer_id = $cart_count = $is_booked = 0;
        $obj_list_details         = false;
        $arr_list_details         = [];
        $obj_master_details       = false;
        $arr_master_details       = [];
        $insert_details_to_cart   = false;
        $arr_cart                 = [];
        $arr_listing              = [];

        $visitor_id     = Session::get('visitor_id');
        $list_id    	= base64_decode($request->input('enc_list_id'));
        $master_id      = base64_decode($request->input('enc_master_id'));
        
        //Check if product is already exits in cart.
        $obj_check_if_exists = $this->CartModel->where('list_id',$list_id);

        //Check User is Logged in or not
        $user = Sentinel::check();

        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
          $obj_check_if_exists = $obj_check_if_exists->where('buyer_id',$buyer_id);
        }
        else
        {
          $obj_check_if_exists = $obj_check_if_exists->where('visitor_id',$visitor_id);
        }
          $obj_check_if_exists = $obj_check_if_exists->where('master_id',$master_id)->where('list_id',$list_id)->count();
        
        if($obj_check_if_exists==1)
        {
			return response(array("status"=>"fail","msg"=>"This item is already present in the cart."));
        }
        else
        {
        	$obj_list_details   = $this->MediaListingDetailModel->where('id',$list_id)
        													    ->where('list_id',$master_id)
        													    ->first();

        	$obj_master_details = $this->MediaListingMasterModel->where('id',$master_id)
                                                                ->with('listing_details')
        													    ->first();        													  
            
            if($obj_list_details!=false && $obj_master_details!=false)
            {
            	$arr_list_details   = $obj_list_details->toArray();
            	$arr_master_details = $obj_master_details->toArray();

            	$arr_data['visitor_id'] = $visitor_id;
            	$arr_data['buyer_id']   = $buyer_id;
            	$arr_data['master_id']  = $master_id;
            	$arr_data['list_id']    = $list_id;
            	$arr_data['type']       = $arr_master_details['type'];
            	$arr_data['price']      = $arr_list_details['price'];

        		$insert_details_to_cart = $this->CartModel->create($arr_data);
            	
        		if($buyer_id!=0)
		        {
		          $arr_cart = $this->CartModel->where('buyer_id',$buyer_id)->get();
		        }
		        else
		        {
		          $arr_cart = $this->CartModel->where('visitor_id',$visitor_id)->get();
		        }

                $cart_count = count($arr_cart);

                //Creating Lisitng array
                if(isset($arr_cart) && count($arr_cart)>0)
                {
                    foreach ($arr_cart as $new_key => $cart)
                    {
                        array_push($arr_listing, $cart['list_id']);
                    }
                }    

                foreach ($arr_master_details['listing_details'] as $key => $value)
                {
                    if(in_array($value['id'],$arr_listing))
                    {
                        $is_booked = 1;
                    }
                    else
                    {
                        $is_booked = 0;
                        break;
                    }
                }

            	if($insert_details_to_cart!=false)
            	{

					return response(array("status"=>"success","msg"=>"Item added to the cart.","cart_count"=>$cart_count,"is_booked"=>$is_booked));
            	}
            	else
            	{
					return response(array("status"=>"fail","msg"=>"Error while adding item to the cart. Please try again.","cart_count"=>$cart_count,"is_booked"=>$is_booked));
            	}
            }
            else
            {
				return response(array("status"=>"fail","msg"=>"Error while adding item to the cart. Please try again.","cart_count"=>$cart_count,"is_booked"=>$is_booked));
            }
        }
      }
    }

    /*------------- Get Cart Count --------------*/

    public function cart_count(Request $request) 
    {
        $status         = false;
        $user           = false;
        $visitor_id     = '';
        $arr_count      = $buyer_id = 0;

        $visitor_id 	= Session::get('visitor_id');
        
        //Check User is Logged in or not
        $user = Sentinel::check();
        
        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
          $arr_count = $this->CartModel->where('buyer_id',$buyer_id)->count();
        }
        else
        {
          $arr_count = $this->CartModel->where('visitor_id',$visitor_id)->count();
        }
        return $arr_count;
    }

    /*------------- Delete Cart Item --------------*/

    public function delete($enc_cart_id) 
    {
        $status         = false;
        $user           = false;
        $cart_id        = '';

     	if($enc_cart_id!='')
     	{
     		$cart_id    = base64_decode($enc_cart_id);
            $status = $this->CartModel->where('id',$cart_id)->delete();
	        
	        if($status!=false)
	        {
	        	Flash::success('Item has been removed from the cart');
	        	return redirect()->back();
	        }
	        else
	        {
	          	Flash::error('Error while removing item from the cart');
	        	return redirect()->back();
	        }
        }
        else
        {
          	Flash::error('Error while removing item from the cart');
        	return redirect()->back();
        }
    }

    /*------------- Edit Cart --------------*/

    public function edit(Request $request) 
    {
		$master_id           = '';
		$list_id             = '';
		$enc_cart_id         = '';
		$obj_master_details  = false;
		$arr_master_details  = [];
		$obj_listing_details = false;
		$arr_listing_details = [];

    	if($request->input('enc_master_id')!='' && $request->input('enc_list_id')!='')
    	{
    		$master_id 		= base64_decode($request->input('enc_master_id'));	
    		$list_id   		= base64_decode($request->input('enc_list_id'));
    		$enc_cart_id    = $request->input('enc_cart_id');

    		$obj_master_details = $this->MediaListingMasterModel->where('id',$master_id)->with('listing_details.format_details')->first();
    		$obj_list_details   = $this->MediaListingDetailModel->where('id',$list_id)->first();
    		
    		if($obj_master_details!=false && $obj_list_details!=false)
    		{
    			$arr_master_details  = $obj_master_details->toArray();	
    			$arr_listing_details = $obj_list_details->toArray();	
    		}
    	}
    	return response(array("arr_master_details" => $arr_master_details, "arr_listing_details" => $arr_listing_details,'enc_cart_id'=>$enc_cart_id));
    }

    /*------------- Update Cart --------------*/

    public function update(Request $request) 
    {
		$master_id           = '';
		$list_id             = '';
		$cart_id             = '';
		$obj_listing_details = $status = false;
		$arr_listing_details = [];
		$arr_data 			 = [];
        $user                = false;
        $visitor_id          = '';
        $buyer_id = $obj_check_if_exists = 0;

        $visitor_id 	= Session::get('visitor_id');
        
        //Check User is Logged in or not
        $user = Sentinel::check();
        
    	if($request->input('enc_master_id')!='' && $request->input('enc_list_id')!='' && $request->input('enc_cart_id')!='')
    	{
    		$master_id = base64_decode($request->input('enc_master_id'));	
    		$list_id   = base64_decode($request->input('enc_list_id'));
    		$cart_id   = base64_decode($request->input('enc_cart_id'));

            //Check if product is already exits in cart.
            $obj_check_if_exists = $this->CartModel->where('list_id',$list_id);
            
            if($user!=false && $user['role']=='buyer')
            {
              $buyer_id            = $user['id'];
              $obj_check_if_exists = $obj_check_if_exists->where('buyer_id',$buyer_id);
            }
            else
            {
              $obj_check_if_exists = $obj_check_if_exists->where('visitor_id',$visitor_id);
            }
            
            $obj_check_if_exists = $obj_check_if_exists->where('master_id',$master_id)->where('list_id',$list_id)->count();

            if($obj_check_if_exists==1)
            {
                return response(array("status"=>"fail","msg"=>"This item is already present in the cart."));
            }
                		
            $obj_list_details   = $this->MediaListingDetailModel->where('id',$list_id)->first();
    		
    		if($obj_list_details!=false)
    		{
    			$arr_list_details  = $obj_list_details->toArray();
                $arr_data['price']   = $arr_list_details['price'];
    			$arr_data['list_id'] = $arr_list_details['id'];

		        if($user!=false && $user['role']=='buyer')
		        {
		          $buyer_id = $user['id'];
	    		  $status   = $this->CartModel->where('id',$cart_id)->where('master_id',$master_id)->where('buyer_id',$buyer_id)->update($arr_data);
		        }
		        else
		        {
	    		  $status = $this->CartModel->where('id',$cart_id)->where('master_id',$master_id)->where('visitor_id',$visitor_id)->update($arr_data);
		        }    			
    			
    			if($status!=false)
    			{
    				return response(array("status"=>"success","msg"=>"Item has been updated successfully."));
    			}
    			else
    			{
    				return response(array("status"=>"fail","msg"=>"Error while updating this item."));
    			}
    		}
    	}
		return response(array("status"=>"fail","msg"=>"Error while updating this item."));
    }          	    	
}
