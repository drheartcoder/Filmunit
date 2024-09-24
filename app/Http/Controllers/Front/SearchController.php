<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MediaListingMasterModel;
use App\Models\ResolutionModel;
use App\Models\DurationModel;
use App\Models\RatioModel;
use App\Models\FpsModel;
use App\Models\FormatModel;
use App\Models\OrientationModel;
use App\Models\CartModel;
use App\Models\PackageModel;
use App\Models\PackageDetailModel;

use Sentinel;
use Flash;
use DB;
use Session;

class SearchController extends Controller
{
    public function __construct(
    							MediaListingMasterModel $media_listing_master_model,
    							ResolutionModel $resolution_model,
    							DurationModel $duration_model,
    							RatioModel $ratio_model,
    							FpsModel $fps_model,
    							FormatModel $format_model,
    							OrientationModel $orientation_model,    							
    							PackageModel $package_model,    							
    							PackageDetailModel $package_detail_model,    							
	    						CartModel $cart_model
    							)  
    {   
		$this->MediaListingMasterModel 		      = $media_listing_master_model;
		$this->ResolutionModel 	 	    	      = $resolution_model;
		$this->DurationModel 	 	    	      = $duration_model;
		$this->RatioModel 	 	    		      = $ratio_model;
		$this->FpsModel 	 	    		      = $fps_model;
		$this->FormatModel 	 	    		      = $format_model;
		$this->OrientationModel 	 		      = $orientation_model;
		$this->CartModel 		              	  = $cart_model;
		$this->PackageModel 		              = $package_model;
		$this->PackageDetailModel 		          = $package_detail_model;
        $this->arr_view_data 		  		      = [];
        
        $this->module_url_path          	      = url('/')."/listing";
        $this->photos_base_img_path               = config('app.project.img_path.photos');
    	$this->photos_public_img_path             = url('/').'/'.config('app.project.img_path.photos');
        $this->footage_base_img_path     	      = config('app.project.img_path.footage');
    	$this->footage_public_img_path      	  = url('/').'/'.config('app.project.img_path.footage');
  	    $this->admin_footage_public_img_path      = url('/').'/'.config('app.project.img_path.admin_footage');
  	    $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
        $this->package_images_public_path         = url('/').'/'.config('app.project.img_path.package_images');
        $this->admin_footage_image_public_path    = url('/').'/'.config('app.project.img_path.admin_footage_image');
        $this->admin_footage_image_base_path      = config('app.project.img_path.admin_footage_image');

    }

    public function index(Request $request)
    {
    	//dd($request->all());
    	$obj_arr_data 	= false;
    	$arr_data     	= [];
    	$user         	= [];
    	$arr_pagination = [];
    	$seller_id	  	= '';

    	$obj_resolution 		= false;
    	$obj_ratio 				= false;
    	$obj_fps 				= false;
    	$obj_photo_formats      = false;
    	$obj_footage_formats    = false;
    	$obj_orientation        = false;
    	$arr_resolution 		= [];
    	$arr_ratio 				= [];
    	$arr_fps 				= [];
    	$arr_photo_formats 		= [];
    	$arr_footage_formats 	= [];
    	$arr_orientation 		= [];

    	$obj_resolution = $this->ResolutionModel->orderBy('size','ASC')->get();
    	if($obj_resolution!=false)
    	{
			$arr_resolution = $obj_resolution->toArray();    		
    	}

		$obj_ratio = $this->RatioModel->orderBy('value','ASC')->get();
    	if($obj_ratio!=false)
    	{
			$arr_ratio = $obj_ratio->toArray();    		
    	}

    	$obj_fps = $this->FpsModel->orderBy('value','ASC')->get();
    	if($obj_fps!=false)
    	{
			$arr_fps = $obj_fps->toArray();    		
    	}

    	$obj_footage_formats = $this->FormatModel->where('type','footage')->orderBy('name','ASC')->get();
    	if($obj_footage_formats!=false)
    	{
			$arr_footage_formats = $obj_footage_formats->toArray();    		
    	}

    	$obj_photo_formats = $this->FormatModel->where('type','photo')->orderBy('name','ASC')->get();
    	if($obj_photo_formats!=false)
    	{
			$arr_photo_formats = $obj_photo_formats->toArray();    		
    	}

		$obj_orientation = $this->OrientationModel->orderBy('value','ASC')->get();
    	if($obj_orientation!=false)
    	{
			$arr_orientation = $obj_orientation->toArray();    		
    	}


/*------------------------------------ Searching starts here -----------------------------------------*/
    	$type = $keyword  = '';
    	$arr_photo_format = $arr_footage_format = [];
    	$arr_orient       = [];
    	$ratio            = [];
    	$resolution       = [];
    	$fps       		  = [];

    	$alpha_channel 	= '';
    	$alpha_matte 	= '';
    	$media_release 	= '';
    	$looping 		= '';
    	$model_release 	= '';
    	$liscense_type 	= '';
    	$fx 			= '';

		if($request->has('type'))
		{
			$type = $request->input('type');
		}

		if($type!='photo' && $type!='footage')
		{
			$type='footage';
		}

		if($request->has('duration')&&$request->input('duration')!='')
		{
			$duration = $request->input('duration');
		}
		
		if($request->has('keyword'))
		{	

			$keyword = trim(strtolower($request->input('keyword')));
			$keyword = str_replace('\\',"",$keyword);
		}

		//Photo fields	   
	    if($request->has('photo_format'))
		{
			$arr_photo_format = $request->input('photo_format');
		}

		if($request->has('orientation'))
		{
			$arr_orient = $request->input('orientation');
		}

		//Footage fields	   
	    if($request->has('footage_format'))
		{
			$arr_footage_format = $request->input('footage_format');
		}

	    if($request->has('ratio'))
		{
			$ratio = $request->input('ratio');
		}

	    if($request->has('resolution'))
		{
			$resolution = $request->input('resolution');
		}

		if($request->has('fps'))
		{
			$fps = $request->input('fps');
		}

		if($request->has('alpha_channel'))
		{
			$alpha_channel = $request->input('alpha_channel');
		}

		if($request->has('alpha_matte'))
		{
			$alpha_matte = $request->input('alpha_matte');
		}

		if($request->has('media_release'))
		{
			$media_release = $request->input('media_release');
		}

		if($request->has('looping'))
		{
			$looping = $request->input('looping');
		}

		if($request->has('model_release'))
		{
			$model_release = $request->input('model_release');
		}

		if($request->has('liscense_type'))
		{
			$liscense_type = $request->input('liscense_type');
		}

		if($request->has('fx'))
		{
			$fx = $request->input('fx');
		}
										
		if($type=='footage')
		{
	 		$obj_arr_data = DB::table('media_listing_master AS M')
							->select(
								'M.id AS M_id',
								'M.slug AS M_slug',
								'M.seller_id AS M_seller_id',
								'M.description AS M_description',
								'M.type AS M_type',
								'M.keywords AS M_keywords',
								'M.title AS M_title',
								'M.duration AS M_duration',
								'M.ratio AS M_ratio',
								'M.alpha_channel AS M_alpha_channel',
								'M.alpha_matte AS M_alpha_matte',
								'M.media_release AS M_media_release',
								'M.looping AS M_looping',
								'M.model_release AS M_model_release',
								'M.liscense_type AS M_liscense_type',
								'M.fx AS M_fx',
								'M.admin_enc_item_name AS M_admin_enc_item_name',
								'M.admin_enc_footage_image AS M_admin_enc_footage_image',
								'List.id AS List_id',
								'List.list_id AS list_id',
								'List.item_name AS item_name',
								'List.enc_item_name AS enc_item_name',
								'List.price AS price',
								'List.format AS format',
								'List.orientation as orientation',
								'List.resolution as resolution',
								'List.fps as fps',
								'F.name as format_name',
								'FPS.value as fps_value',
								'R.value as ratio_value',
								'Res.size as resolution_value',
								'fp.value as fps_value',
								 DB::raw('MAX(List.price) AS maxPrice'),
								 DB::raw('MIN(List.price) AS minPrice')
								)
							->join( 'media_listing_details as List' , 'List.list_id' , '=' , 'M.id')
							->join('formats as F' , 'F.id' , '=' , 'List.format')
							->join('resolution as Res' , 'Res.id' , '=' , 'List.resolution')
							->join( 'ratio as R' , 'R.id' , '=' , 'M.ratio')
							->join( 'fps as FPS' , 'FPS.id' , '=' , 'List.fps')
							->join('fps as fp' , 'fp.id' , '=' , 'List.fps')
							->where( 'M.is_approved' , '1' )
							->where( 'M.type' , 'footage' )
							->groupBy('M.id')
							->orderBy( 'M.id' , 'DESC' );

			}
			else if($type=='photo')
			{
					 		$obj_arr_data = DB::table('media_listing_master AS M')
							->select(
								'M.id AS M_id',
								'M.slug AS M_slug',
								'M.seller_id AS M_seller_id',
								'M.description AS M_description',
								'M.type AS M_type',
								'M.keywords AS M_keywords',
								'M.title AS M_title',
								'M.duration AS M_duration',
								'M.ratio AS M_ratio',
								'M.alpha_channel AS M_alpha_channel',
								'M.alpha_matte AS M_alpha_matte',
								'M.media_release AS M_media_release',
								'M.looping AS M_looping',
								'M.model_release AS M_model_release',
								'M.liscense_type AS M_liscense_type',
								'M.fx AS M_fx',
								'M.admin_enc_item_name AS M_admin_enc_item_name',
								'M.admin_enc_footage_image AS M_admin_enc_footage_image',
								'List.id AS List_id',
								'List.list_id AS list_id',
								'List.item_name AS item_name',
								'List.enc_item_name AS enc_item_name',
								'List.price AS price',
								'List.format AS format',
								'List.orientation as orientation',
								'List.resolution as resolution',
								'List.fps as fps',
								'F.name as format_name',
								'O.value as orientation_value',
								 DB::raw('MAX(List.price) AS maxPrice'),
								 DB::raw('MIN(List.price) AS minPrice')
								)
							->join( 'media_listing_details as List' , 'List.list_id' , '=' , 'M.id')
							->join('formats as F' , 'F.id' , '=' , 'List.format')
							->join('orientation as O' , 'O.id' , '=' , 'List.orientation')
							->where( 'M.is_approved' , '1' )
							->where( 'M.type' , 'photo' )
							->groupBy('M.id')
							->orderBy( 'M.id' , 'DESC' );

			}

	  		if(isset($keyword) && $keyword!='')
			{
				if(isset($type) && $type=='footage')
				{
					$obj_arr_data = $obj_arr_data->whereRaw('( FIND_IN_SET("'.$keyword.'", M.keywords) OR M.title LIKE "%'.$keyword.'%" OR F.name LIKE "%'.$keyword.'%" OR List.price LIKE "%'.$keyword.'%" OR M.duration LIKE "%'.$keyword.'%" OR M.ratio LIKE "%'.$keyword.'%" OR FPS.value LIKE "%'.$keyword.'%" OR R.value LIKE "%'.$keyword.'%" OR Res.size LIKE "%'.$keyword.'%") ');
				}

				if(isset($type) && $type=='photo')
				{
					$obj_arr_data = $obj_arr_data->whereRaw('( FIND_IN_SET("'.$keyword.'", M.keywords) OR M.title LIKE "%'.$keyword.'%" OR F.name LIKE "%'.$keyword.'%" OR List.price LIKE "%'.$keyword.'%" OR O.value LIKE "%'.$keyword.'%") ');
				}				
			}

			//Photo formats
			if(isset($arr_photo_format) && count($arr_photo_format)>0)
			{
				$i = 0;
				$str ='';
				foreach ($arr_photo_format as $key => $format_list)
				{
					if( $i==0 ? $condition = ' (' : $condition = ' OR ' )
					$str.= $condition .'F.name LIKE "%'.$format_list.'%"';
					$i++;
				}
					$obj_arr_data = $obj_arr_data->whereRaw($str.')');
			}

			//Photo orientation
			if(isset($arr_orient) && count($arr_orient)>0)
			{
				$o = 0;
				$str1 = '';
				foreach ($arr_orient as $key => $orient_list)
				{
					if( $o==0 ? $condition = ' (' : $condition = ' OR ' )
					$str1.= $condition .'O.value LIKE "%'.$orient_list.'%"';
					$o++;
				}
					$obj_arr_data = $obj_arr_data->whereRaw($str1.')');
			}

			//Photo Price
			if($request->has('photo_max_price') && $request->has('photo_min_price'))
			{
				$photo_max_price = 500;
				$photo_min_price = 1;
				
				if($request->input('photo_max_price')!=0 && $request->input('photo_min_price')!=0 )
				{
					$photo_max_price = $request->input('photo_max_price');
					$photo_min_price = $request->input('photo_min_price');
				}
				
				if($photo_max_price>=500)
				{
					$obj_arr_data = $obj_arr_data->whereRaw('List.price >=  '.$photo_min_price);
				}
				else
				{
					$obj_arr_data = $obj_arr_data->whereRaw("List.price >= ".$photo_min_price." AND List.price <= ".$photo_max_price);
				} 
			}

			//Footage Duration
			if($request->has('max_duration') && $request->has('min_duration'))
			{
				$max_duration = 120;
				$min_duration = 1;
				
				if($request->input('max_duration')!=0 && $request->input('min_duration')!=0 )
				{
					$max_duration = $request->input('max_duration');
					$min_duration = $request->input('min_duration');
				}
				if($max_duration>=120)
				{
					$obj_arr_data = $obj_arr_data->whereRaw('M.duration >=  '.$min_duration);
				}
				else
				{
					$obj_arr_data = $obj_arr_data->whereRaw("M.duration >= ".$min_duration." AND M.duration <= ".$max_duration);
				} 
			}

			//Footage Price
			if($request->has('footage_max_price') && $request->has('footage_min_price'))
			{
				$footage_max_price = 500;
				$footage_min_price = 1;
				
				if($request->input('footage_max_price')!=0 && $request->input('footage_min_price')!=0 )
				{
					$footage_max_price = $request->input('footage_max_price');
					$footage_min_price = $request->input('footage_min_price');
				}
				if($footage_max_price>=500)
				{
					$obj_arr_data = $obj_arr_data->whereRaw('List.price >=  '.$footage_min_price);
				}
				else
				{
					$obj_arr_data = $obj_arr_data->whereRaw("List.price >= ".$footage_min_price." AND List.price <= ".$footage_max_price);
				} 
			}			

		    //Footage formats
			if(isset($arr_footage_format) && count($arr_footage_format)>0)
			{
				$i = 0;
				$str ='';
				foreach ($arr_footage_format as $key => $format_list)
				{
					if( $i==0 ? $condition = ' (' : $condition = ' OR ' )
					$str.= $condition .'F.name LIKE "%'.$format_list.'%"';
					$i++;
				}
					$obj_arr_data = $obj_arr_data->whereRaw($str.')');
			}

			//Footage ratio
			if(isset($ratio) && count($ratio)>0)
			{
				$j = 0;
				$str_ratio ='';
				foreach ($ratio as $key => $list)
				{
					if( $j==0 ? $condition = ' (' : $condition = ' OR ' )
					$str_ratio.= $condition .'R.value LIKE "%'.$list.'%"';
					$j++;
				}
					$obj_arr_data = $obj_arr_data->whereRaw($str_ratio.')');
			}

			//Footage resolution
			if(isset($resolution) && count($resolution)>0)
			{
				$k = 0;
				$str_resolution ='';
				foreach ($resolution as $key => $list)
				{
					if( $k==0 ? $condition = ' (' : $condition = ' OR ' )
					$str_resolution.= $condition .'Res.size LIKE "%'.$list.'%"';
					$k++;
				}
					$obj_arr_data = $obj_arr_data->whereRaw($str_resolution.')');
			}

			//Footage fps
			if(isset($fps) && count($fps)>0)
			{
				$l = 0;
				$str_fps ='';
				foreach ($fps as $key => $list)
				{
					if( $l==0 ? $condition = ' (' : $condition = ' OR ' )
					$str_fps.= $condition .'fp.value LIKE "%'.$list.'%"';
					$l++;
				}
					$obj_arr_data = $obj_arr_data->whereRaw($str_fps.')');
			}

		    //Footage alpha_channel
			if(isset($alpha_channel) && $alpha_channel!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.alpha_channel LIKE "%'.$alpha_channel.'%"');
			}

			if(isset($alpha_matte) && $alpha_matte!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.alpha_matte LIKE "%'.$alpha_matte.'%"');
			}

			if(isset($media_release) && $media_release!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.media_release LIKE "%'.$media_release.'%"');
			}

			if(isset($looping) && $looping!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.looping LIKE "%'.$looping.'%"');
			}

			if(isset($model_release) && $model_release!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.model_release LIKE "%'.$model_release.'%"');
			}

			if(isset($liscense_type) && $liscense_type!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.liscense_type LIKE "%'.$liscense_type.'%"');
			}

			if(isset($fx) && $fx!='')
			{
				$obj_arr_data = $obj_arr_data->whereRaw('M.fx LIKE "%'.$fx.'%"');
			}									

			//DB::enableQueryLog();
			$obj_arr_data = $obj_arr_data->paginate(12);
			// dd(DB::getQueryLog());
	    
	   		if($obj_arr_data!=false)
	   		{
	            $arr_pagination   = $obj_arr_data;
	            $arr_data         = $obj_arr_data->toArray();
	   		}

	    	$this->arr_view_data['arr_data'] 							= $arr_data;
	        $this->arr_view_data['arr_pagination']          			= $arr_pagination;
	    	$this->arr_view_data['arr_resolution']  		            = $arr_resolution;
	    	$this->arr_view_data['arr_ratio']       		            = $arr_ratio;
	    	$this->arr_view_data['arr_fps']         		            = $arr_fps;
	    	$this->arr_view_data['arr_footage_formats']   	            = $arr_footage_formats;
	    	$this->arr_view_data['arr_photo_formats']   	            = $arr_photo_formats;
	    	$this->arr_view_data['arr_orientation']     	            = $arr_orientation;        
	    	$this->arr_view_data['title'] 								= "Search - ".config('app.project.name');
	    	$this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
	    	$this->arr_view_data['admin_photos_public_img_path'] 		= $this->admin_photos_public_img_path;
	    	$this->arr_view_data['admin_footage_image_public_path']     = $this->admin_footage_image_public_path;
	    	$this->arr_view_data['module_url_path'] 					= $this->module_url_path;
	    	
	       	return view('front.search.index',$this->arr_view_data);
    }

	public function view($slug)
    {
    	$user                     = [];
    	$obj_arr_data             = false;
    	$obj_arr_similar_data     = false;
    	$obj_similar_photographer = false;
    	$obj_cart_data 			  = false;
    	$arr_data 			      = [];
    	$arr_similar_data 	      = [];
    	$arr_similar_photographer = [];
    	$arr_image_size           = [];
    	$arr_cart_data            = [];
    	$list_id 				  = '';
    	$keyword                  = '';
    	$seller_id                = '';
    	$arr_keyword              = [];
    	$type                     = '';
    	$is_booked                = 0;
    	$visitor_id               = $buyer_id = '';
  
    	$user = Sentinel::check();

    	if($slug!='')
    	{
    		if($user!=false)
    		{
    			$user_id = $user['id'];
    		}

    		$obj_arr_data = $this->MediaListingMasterModel->where('slug',$slug)
    													  ->with(['listing_details'=>function($query){
    													  	$query->with('format_details')
		    													  ->with('orientation_details')
		    													  ->with('resolution_details')
		    													  ->with('fps_details')
    													  		  ->orderBy('price','ASC');
    													  }])
    													  ->with(['seller_details'=>function($query){
    													  	$query->select('id','first_name','last_name');
    													  }])
    													  ->with('favourite_list','ratio_details')
    													  ->with('cart_list')
    													  ->first();
    		if($obj_arr_data!=false)
    		{
    			$arr_data    = $obj_arr_data->toArray();
    			$list_id     = $arr_data['id'];

    			$arr_image_size['size']     = [];
    			$arr_image_size['filesize'] = [];
    			$size     = '';
    			$filesize = '';

    			foreach ($arr_data['listing_details'] as $key => $details)
    			{
    				if($arr_data['type']=='photo')
    				{
	 					$filesize = filesize($this->photos_base_img_path.$details['enc_item_name'])/(1024*1024);
	 					$filesize = number_format((float)$filesize, 2, '.', '').' MB';
	 					array_push($arr_image_size['filesize'], $filesize);

	 					list($width, $height) = getimagesize($this->photos_public_img_path.$details['enc_item_name']);
	 					$size = $width ." X ".$height;
	 					array_push($arr_image_size['size'], $size);
    				}
    				else
    				{
						$filesize = filesize($this->footage_base_img_path.$details['enc_item_name'])/(1024*1024);
	 					$filesize = number_format((float)$filesize, 2, '.', '').' MB';
	 					array_push($arr_image_size['filesize'], $filesize);
    				}
		    	}


		        if($user!=false && $user['role']=='buyer')
		        {
		          $buyer_id 	 = $user['id'];
		          $obj_cart_data = $this->CartModel->where('buyer_id',$buyer_id)->get();
		        }
		        else
		        {
        		  $visitor_id 	 = Session::get('visitor_id');
		          $obj_cart_data = $this->CartModel->where('visitor_id',$visitor_id)->get();
		        }

		        if($obj_cart_data!=false)
		        {
		        	$arr_cart_data = $obj_cart_data->toArray();	    	
		        }
			/*-------------------------------------------- Similar Product Search ----------------------------------------------*/
	    		$type 		 = $arr_data['type'];
	    		$seller_id 	 = $arr_data['seller_details']['id'];
				$str_keyword = '';
				$str_keyword = $arr_data['keywords'];
				$arr_keyword = explode(',', $str_keyword);

		 		$obj_arr_similar_data = DB::table('media_listing_master AS M')
										->select(
											'M.id AS M_id',
											'M.slug AS M_slug',
											'M.seller_id AS M_seller_id',
											'M.description AS M_description',
											'M.type AS M_type',
											'M.keywords AS M_keywords',
											'M.title AS M_title',
											'M.duration AS M_duration',
											'M.ratio AS M_ratio',
											'M.alpha_channel AS M_alpha_channel',
											'M.alpha_matte AS M_alpha_matte',
											'M.media_release AS M_media_release',
											'M.looping AS M_looping',
											'M.model_release AS M_model_release',
											'M.liscense_type AS M_liscense_type',
											'M.fx AS M_fx',
											'M.admin_enc_item_name AS M_admin_enc_item_name',
											'M.admin_enc_footage_image AS M_admin_enc_footage_image',
											'List.id AS List_id',
											'List.list_id AS list_id',
											'List.item_name AS item_name',
											'List.price AS price',
											'List.format AS format',
											'F.name as format_name',
											 DB::raw('MAX(List.price) AS maxPrice'),
											 DB::raw('MIN(List.price) AS minPrice')
											)
										->join( 'media_listing_details as List' , 'List.list_id' , '=' , 'M.id')
										->join('formats as F' , 'F.id' , '=' , 'List.format')
										->where( 'M.is_approved' , '1' )
										->where( 'M.type' , $type )
										->where( 'M.id','<>', $list_id )
										->groupBy('M.id')
										->LIMIT(4)
										->orderBy( 'M.id' , 'DESC' );
				
		  		if(isset($arr_keyword) && count($arr_keyword))
				{
					$i = 0;
					$str ='';

					foreach ($arr_keyword as $key => $keyword)
					{
						if( $i==0 ? $condition = ' (' : $condition = ' OR ' )
						$str.= $condition .'FIND_IN_SET("'.$keyword.'", M.keywords) OR M.title LIKE "%'.$keyword.'%"';
						$i++;
					}
					$obj_arr_similar_data = $obj_arr_similar_data->whereRaw($str.')');
				}
				
				$arr_similar_data = $obj_arr_similar_data->get();

		/*-------------------------------------------- Similar Sellers Products ----------------------------------------------*/

		 		$obj_arr_similar_data = DB::table('media_listing_master AS M')
										->select(
											'M.id AS M_id',
											'M.slug AS M_slug',
											'M.seller_id AS M_seller_id',
											'M.description AS M_description',
											'M.type AS M_type',
											'M.keywords AS M_keywords',
											'M.title AS M_title',
											'M.duration AS M_duration',
											'M.ratio AS M_ratio',
											'M.alpha_channel AS M_alpha_channel',
											'M.alpha_matte AS M_alpha_matte',
											'M.media_release AS M_media_release',
											'M.looping AS M_looping',
											'M.model_release AS M_model_release',
											'M.liscense_type AS M_liscense_type',
											'M.fx AS M_fx',
											'M.admin_enc_item_name AS M_admin_enc_item_name',
											'M.admin_enc_footage_image AS M_admin_enc_footage_image',
											'List.id AS List_id',
											'List.list_id AS list_id',
											'List.item_name AS item_name',
											'List.price AS price',
											'List.format AS format',
											'F.name as format_name',
											 DB::raw('MAX(List.price) AS maxPrice'),
											 DB::raw('MIN(List.price) AS minPrice')
											)
										->join( 'media_listing_details as List' , 'List.list_id' , '=' , 'M.id')
										->join('formats as F' , 'F.id' , '=' , 'List.format')
										->where( 'M.is_approved' , '1' )
										->where( 'M.type' , $type )
										->where( 'M.seller_id' , $seller_id )
										->where( 'M.id','<>', $list_id )
										->groupBy('M.id')
										->LIMIT(4)
										->orderBy( 'M.id' , 'DESC' );

				$arr_similar_photographer = $obj_arr_similar_data->get();
	    	}
	    	else
	    	{
	    		return redirect()->back();
	    	}
    	}

    	$this->arr_view_data['title'] 							    = "View Photos and Footage - ".config('app.project.name');
    	$this->arr_view_data['module_title'] 		    		    = "View Photos and Footage";
    	$this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
    	$this->arr_view_data['admin_photos_public_img_path'] 		= $this->admin_photos_public_img_path;
    	$this->arr_view_data['admin_footage_image_public_path']     = $this->admin_footage_image_public_path;    	
    	$this->arr_view_data['module_url_path'] 		            = $this->module_url_path;
    	$this->arr_view_data['arr_data']     			            = $arr_data;
    	$this->arr_view_data['arr_image_size']     			        = $arr_image_size;
    	$this->arr_view_data['arr_similar_data']     			    = $arr_similar_data;
    	$this->arr_view_data['arr_similar_photographer']     	    = $arr_similar_photographer;
    	$this->arr_view_data['arr_cart_data']     	    			= $arr_cart_data;

       	return view('front.search.view',$this->arr_view_data);
    }

	public function seller_listing($enc_id)
    {
    	$obj_similar_seller 	  = false;
    	$arr_data 			      = [];
    	$arr_similar_seller 	  = [];
    	$seller_id                = '';
  
    	if($enc_id!='')
    	{
    		$seller_id = base64_decode($enc_id);
			/*-------------------------------------------- Similar Sellers Products ----------------------------------------------*/

		 		$obj_similar_seller = DB::table('media_listing_master AS M')
										->select(
											'M.id AS M_id',
											'M.slug AS M_slug',
											'M.seller_id AS M_seller_id',
											'M.description AS M_description',
											'M.type AS M_type',
											'M.keywords AS M_keywords',
											'M.title AS M_title',
											'M.duration AS M_duration',
											'M.ratio AS M_ratio',
											'M.alpha_channel AS M_alpha_channel',
											'M.alpha_matte AS M_alpha_matte',
											'M.media_release AS M_media_release',
											'M.looping AS M_looping',
											'M.model_release AS M_model_release',
											'M.liscense_type AS M_liscense_type',
											'M.fx AS M_fx',
											'M.admin_enc_item_name AS M_admin_enc_item_name',
											'M.admin_enc_footage_image AS M_admin_enc_footage_image',
											'List.id AS List_id',
											'List.list_id AS list_id',
											'List.item_name AS item_name',
											'List.price AS price',
											'List.format AS format',
											'F.name as format_name',
											 DB::raw('MAX(List.price) AS maxPrice'),
											 DB::raw('MIN(List.price) AS minPrice')
											)
										->join( 'media_listing_details as List' , 'List.list_id' , '=' , 'M.id')
										->join('formats as F' , 'F.id' , '=' , 'List.format')
										->where( 'M.is_approved' , '1' )
										->where( 'M.seller_id' , $seller_id )
										->groupBy('M.id')
										->orderBy( 'M.id' , 'DESC' )
										->paginate(12);
	    
	   		if($obj_similar_seller!=false)
	   		{
	            $arr_pagination   = $obj_similar_seller;
	            $arr_data         = $obj_similar_seller->toArray();

		    	$this->arr_view_data['title'] 							    = "Listing Photos and Footage - ".config('app.project.name');
		    	$this->arr_view_data['module_title'] 		    		    = "Listing Photos and Footage";
		    	$this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
		    	$this->arr_view_data['admin_photos_public_img_path'] 		= $this->admin_photos_public_img_path;
		    	$this->arr_view_data['admin_footage_image_public_path']     = $this->admin_footage_image_public_path;
		    	$this->arr_view_data['module_url_path'] 		            = $this->module_url_path;
		    	$this->arr_view_data['arr_data']     			            = $arr_data;
		    	$this->arr_view_data['arr_pagination']     			        = $arr_pagination;

		       	return view('front.search.seller_listing',$this->arr_view_data);
	   		}										
    	}
		return redirect()->back();
    }

	public function total_package_listing()
    {
    	$arr_data 			      = [];
  		$obj_data                 = false;
  		$obj_package_data         = false;
  		$arr_package_data         = [];

		$obj_data = $this->PackageModel->where('is_active',1)->with('package_details')->paginate(12);;

   		if($obj_data!=false)
   		{
            $arr_pagination   = $obj_data;
            $arr_data         = $obj_data->toArray();

	    	$this->arr_view_data['title'] 							    = "Packages - ".config('app.project.name');
	    	$this->arr_view_data['module_title'] 		    		    = "Packages";
	    	$this->arr_view_data['package_images_public_path']          = $this->package_images_public_path;
	    	$this->arr_view_data['module_url_path'] 		            = $this->module_url_path;
	    	$this->arr_view_data['arr_data']     			            = $arr_data;
	    	$this->arr_view_data['arr_pagination']     			        = $arr_pagination;

	       	return view('front.search.package_listing_index',$this->arr_view_data);
   		}										
		return redirect()->back();
    }

	public function package_listing($slug)
    {
    	$arr_data 			      = [];
  		$obj_data                 = false;
  		$obj_package_data         = false;
  		$arr_package_data         = [];
  		$package_id               = '';
  		$package_title            = '';

    	if($slug!='')
    	{
    		$obj_package_data = $this->PackageModel->where('slug',$slug)    									   
    									   ->first();
            if($obj_package_data!=false)
            {
            	$arr_package_data = $obj_package_data->toArray();
            	$package_id = $arr_package_data['id'];
            	$package_title = $arr_package_data['title'];
            }
            else
            {
            	return redirect()->back();
            }    									   

    		$obj_data = $this->PackageDetailModel->where('pack_id',$package_id)    									   
										         ->with(['listing_details'=>function($query){
										         	$query->with('master_details','format_details');		
										         }])
								   			     ->paginate(12);
	   		if($obj_data!=false)
	   		{
	            $arr_pagination   = $obj_data;
	            $arr_data         = $obj_data->toArray();

		    	$this->arr_view_data['title'] 							    = "Package Listing Photos and Footage - ".config('app.project.name');
		    	$this->arr_view_data['module_title'] 		    		    = "Package Listing Photos and Footage";
		    	$this->arr_view_data['admin_footage_public_img_path']       = $this->admin_footage_public_img_path;
		    	$this->arr_view_data['admin_photos_public_img_path'] 		= $this->admin_photos_public_img_path;
		    	$this->arr_view_data['admin_footage_image_public_path']     = $this->admin_footage_image_public_path;
		    	$this->arr_view_data['module_url_path'] 		            = $this->module_url_path;
		    	$this->arr_view_data['package_title'] 		                = $package_title;
		    	$this->arr_view_data['arr_data']     			            = $arr_data;
		    	$this->arr_view_data['arr_pagination']     			        = $arr_pagination;

		       	return view('front.search.package_listing',$this->arr_view_data);
	   		}										
    	}
		return redirect()->back();
    }                
}
