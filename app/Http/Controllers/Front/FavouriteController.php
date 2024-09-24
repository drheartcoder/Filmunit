<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\FavouriteModel;
use App\Models\UserModel;

use Sentinel;
use Flash;
use DB;


class FavouriteController extends Controller
{
    public function __construct(
	    							FavouriteModel $favourite_model,
	    							UserModel $user_model    							
    							   )  
    {   
    		$this->FavouriteModel 		                = $favourite_model;
    		$this->UserModel 	 	    	      	        = $user_model;
        
        $this->module_url_path          	        = url('/')."/my_collection";
        $this->photos_base_img_path               = config('app.project.img_path.photos');
    	  $this->photos_public_img_path             = url('/').'/'.config('app.project.img_path.photos');
        $this->footage_base_img_path     	        = config('app.project.img_path.footage');
    	  $this->footage_public_img_path      	    = url('/').'/'.config('app.project.img_path.footage');
  	    $this->admin_footage_public_img_path      = url('/').'/'.config('app.project.img_path.admin_footage');
  	    $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
        $this->admin_footage_image_base_path      = config('app.project.img_path.admin_footage_image');
        $this->admin_footage_image_public_path    = url('/').'/'.config('app.project.img_path.admin_footage_image');
    }

    public function index()
    {
    	$user     = false;
        $obj_data = false;
        $arr_data = [];
        $arr_pagination = [];

        $user = Sentinel::check();
        
        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
          $obj_data = $this->FavouriteModel->where('buyer_id',$buyer_id)
          								   ->with(['listing_details'=>function($query){
          								   	$query->with('master_details')
          								   		  ->with('format_details');
          								   }])
          								   ->orderBy('id','DESC')
          								   ->paginate(6);

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
        $this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
        $this->arr_view_data['admin_photos_public_img_path']        = $this->admin_photos_public_img_path;
        $this->arr_view_data['admin_footage_image_public_path']     = $this->admin_footage_image_public_path;
    	  $this->arr_view_data['title']    							              = "My Collection - ".config('app.project.name');
       	
       	return view('front.my_collection.index',$this->arr_view_data);
    }

    /*------------- Add Items To favourite list --------------*/

	public function add(Request $request)
	{
		$list_id             = '';
		$status 	         = false;
		$arr_data            = [];
		$user                = false;
		$buyer_id            = '';
		$favourite_count     = 0;
		$obj_check_if_exists = 0;

		$user = Sentinel::check();

		if($request->input('enc_list_id')!='' && $user!=false && $user['role']=='buyer')
		{
			$list_id 			 = base64_decode($request->input('enc_list_id'));
            $buyer_id            = $user['id'];
            $obj_check_if_exists = $this->FavouriteModel->where('buyer_id',$buyer_id)->where('list_id',$list_id)->count();
	        
	        if($obj_check_if_exists==1)
	        {
				return response(array("status"=>"fail","msg"=>"This item is already present in the favourite.","favourite_count"=>$favourite_count));
	        }

			$arr_data['buyer_id'] = $buyer_id;
			$arr_data['list_id']  = $list_id;

			$status = $this->FavouriteModel->create($arr_data);
	        
	        $favourite_count = $this->FavouriteModel->where('buyer_id',$buyer_id)->count();
			
			if($status)
			{
				return response(array("status"=>"success","msg"=>"Item has been added to favourites successfully.","favourite_count"=>$favourite_count));
			}
			else
			{
				return response(array("status"=>"fail","msg"=>"Problem occuered while adding item to favourites. Please try again.","favourite_count"=>$favourite_count));
			}			
 		}
		else
		{
			return response(array("status"=>"fail","msg"=>"Problem occuered while adding item to favourites. Please try again.","favourite_count"=>$favourite_count));
		}
	}

    /*------------- Remove Items from favourite list --------------*/

	public function remove($enc_list_id)
	{
		$id       = '';
		$status 	   = false;
		$arr_data      = [];
		$user          = false;
		$buyer_id      = '';

		$user = Sentinel::check();

		if($enc_list_id!='' && $user!=false)
		{
			$id  = base64_decode($enc_list_id);
			$buyer_id = $user['id'];

			$status = $this->FavouriteModel->where('id',$id)->where('buyer_id',$buyer_id)->delete();
			if($status)
			{
				Flash::success("Item has been successfully removed from my collection.");
	        	return redirect()->back();				
			}
			else
			{
				Flash::error("Problem occuered while removing item from my collection. Please try again.");
	        	return redirect()->back();
			}			
 		}
		else
		{
			Flash::error("Problem occuered while removing item from my collection. Please try again.");
        	return redirect()->back();
		}
	}

    /*------------- Get Favourite Count --------------*/

    public function count(Request $request) 
    {
        $status         = false;
        $user           = false;
        $visitor_id     = '';
        $arr_count      = $buyer_id = 0;

        //Check User is Logged in or not
        $user = Sentinel::check();
        
        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
          $arr_count = $this->FavouriteModel->where('buyer_id',$buyer_id)->count();
        }
        
        return $arr_count;
    }	        
}
