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

class DownloadController extends Controller
{
    public function __construct(
	    							TransactionModel $transaction_model,
	    							OrderDetailsModel $order_details_model
    							)  
    {   
		$this->TransactionModel 		          = $transaction_model;
		$this->OrderDetailsModel 		          = $order_details_model;
        
        $this->module_url_path          	      = url('/')."/buyer/downloads";
        $this->photos_base_img_path               = config('app.project.img_path.photos');
    	$this->photos_public_img_path             = url('/').'/'.config('app.project.img_path.photos');
        $this->footage_base_img_path     	      = config('app.project.img_path.footage');
    	$this->footage_public_img_path      	  = url('/').'/'.config('app.project.img_path.footage');
  	    $this->admin_footage_public_img_path      = url('/').'/'.config('app.project.img_path.admin_footage');
  	    $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
    }

    public function index()
    {
    	$user             = false;
        $obj_data       = false;
        $arr_pagination = [];
        $arr_data 		  = [];
        $arr_image_size = [];

        $user = Sentinel::check();
        
        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
          $obj_data = $this->OrderDetailsModel->where('buyer_id',$buyer_id)
          									  ->where('buyer_status','paid')
          									  ->with(['listing_details'=>function($query){
          									  	$query->with('master_details','format_details');
          									  	}])
          								      ->orderBy('id','DESC')
          								      ->paginate(5);

          if($obj_data!=false)
          {
          	$arr_pagination = $obj_data;
       		  $arr_data       = $obj_data->toArray();
        	
        	  $arr_image_size['size']     = [];
      			$arr_image_size['filesize'] = [];
      			$size     = '';
      			$filesize = '';

      			if(isset($arr_data['data']) && count($arr_data['data'])>0)
      			{							
      				foreach ($arr_data['data'] as $key => $value)
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
          }
        }
        else
        {
          return redirect()->back();
        }

        $this->arr_view_data['arr_pagination']                      = $arr_pagination;
        $this->arr_view_data['arr_data']                            = $arr_data;
    	  $this->arr_view_data['arr_image_size']     			            = $arr_image_size;
        $this->arr_view_data['module_url_path']                     = $this->module_url_path;
      	$this->arr_view_data['title']    							              = "Downloads - ".config('app.project.name');
      	$this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
      	$this->arr_view_data['admin_photos_public_img_path']        = $this->admin_photos_public_img_path;
       	
       	return view('front.buyer.downloads.index',$this->arr_view_data);
    }

    public function download($enc_id)
    {
    	  $user           = false;
        $obj_data       = false;
        $attempt_status = false;
        $arr_data 		= [];
        $id 			= $id = '';
    	  $image_id       = $filename = $tempImage = '';

        $user = Sentinel::check();
        
        if(($user!=false && $user['role']=='buyer')&&$enc_id!='')
        {
        	$buyer_id      = $user['id'];
        	$id       	   = base64_decode($enc_id);
        	$obj_data      = $this->OrderDetailsModel->where('buyer_id',$buyer_id)
        											 ->where('id',$id)
        											 ->with('listing_details')
        											 ->first();

	        if($obj_data!=false)
	        {
	            $arr_data = $obj_data->toArray();
	            $filename = $arr_data['listing_details']['item_name'];
	            $tempImage = tempnam(sys_get_temp_dir(), $filename);
	            if($arr_data['download_attempt']>0)
	            {
		            if($arr_data['type']=='photo')
		            {
		            	$attempt_status = copy($this->photos_public_img_path.$arr_data['listing_details']['enc_item_name'], $tempImage);
		            }
		            else
		            {
		            	$attempt_status = copy($this->footage_public_img_path.$arr_data['listing_details']['enc_item_name'], $tempImage);
		            }

		            if($attempt_status)
		            {
		            	$download_count          = $arr_data['download_attempt']-1;
		            	$update_download_attempt = $this->OrderDetailsModel->where('buyer_id',$buyer_id)
	        											 				   ->where('id',$id)  
	        											 				   ->update(['download_attempt'=>$download_count]);
		            }

		            if($update_download_attempt)
		            {
		            	return response()->download($tempImage, $filename);
		            }
		        }
				      return redirect()->back();            	
	        }
			     return redirect()->back();            	
        }
    }
}
