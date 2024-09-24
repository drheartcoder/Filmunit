<?php

namespace App\Http\Controllers\Front\seller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Usermodel;
use App\Models\ResolutionModel;
use App\Models\DurationModel;
use App\Models\RatioModel;
use App\Models\FpsModel;
use App\Models\FormatModel;
use App\Models\OrientationModel;
use App\Models\MediaListingMasterModel;
use App\Models\MediaListingDetailModel;
use App\Models\NotificationModel;
use App\Models\CommissionModel;
use App\Models\CartModel;
use App\Models\FavouriteModel;
use App\Models\PackageDetailModel;

use Sentinel;
use Validator;
use Flash;

class PhotosAndFootageController extends Controller
{
    public function __construct(UserModel $user_model,
                                ResolutionModel $resolution_model,
                                DurationModel $duration_model,
                                RatioModel $ratio_model,
                                FpsModel $fps_model,
                                FormatModel $format_model,
                                OrientationModel $orientation_model,
                                MediaListingMasterModel $media_listing_master_model,
                                MediaListingDetailModel $media_listing_detail_model,
                                CommissionModel $comission_model,
                                CartModel $cart_model,
                                FavouriteModel $favourite_model,
                                PackageDetailModel $package_detail_model,
                                NotificationModel $notify
                                )  
    {
        $this->UserModel                          = $user_model;
        $this->ResolutionModel                    = $resolution_model;
        $this->DurationModel                      = $duration_model;
        $this->RatioModel                         = $ratio_model;
        $this->FpsModel                           = $fps_model;
        $this->FormatModel                        = $format_model;
        $this->OrientationModel                   = $orientation_model;
        $this->MediaListingMasterModel            = $media_listing_master_model;
        $this->MediaListingDetailModel            = $media_listing_detail_model;
        $this->NotificationModel                  = $notify;
        $this->CommissionModel                    = $comission_model;
        $this->CartModel                          = $cart_model;
        $this->FavouriteModel                     = $favourite_model;
        $this->PackageDetailModel                 = $package_detail_model;

        $this->arr_view_data                      = [];
        $this->module_url_path                    = url('/')."/seller/photos_and_footage";

        $this->photos_base_img_path               = config('app.project.img_path.photos');
        $this->photos_public_img_path             = url('/').'/'.config('app.project.img_path.photos');
        $this->footage_base_img_path              = config('app.project.img_path.footage');
        $this->footage_public_img_path            = url('/').'/'.config('app.project.img_path.footage');
        $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
        $this->admin_footage_image_public_path    = url('/').'/'.config('app.project.img_path.admin_footage_image');
    }

    public function index()
    {
        $obj_arr_data   = false;
        $arr_data       = [];
        $user           = [];
        $arr_pagination = [];
        $seller_id      = '';

        //Check User
        $user = Sentinel::check();

        if(isset($user) && $user==true)
        {
            $seller_id    = $user['id'];
            $obj_arr_data = $this->MediaListingMasterModel->where('seller_id',$seller_id)
                                                          ->with('listing_details.order_details')
                                                          ->orderBy('created_at','DESC')
                                                          ->where('is_admin_uploaded',0)->paginate(10);
            if($obj_arr_data!=false)
            {
                $arr_pagination   = $obj_arr_data;
                $arr_data         = $obj_arr_data->toArray();
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

        $this->arr_view_data['arr_data']                         = $arr_data;
        $this->arr_view_data['arr_pagination']                   = $arr_pagination;
        $this->arr_view_data['title']                            = "My Photos and Footage - ".config('app.project.name');
        $this->arr_view_data['module_title']                     = "My Photos and Footage";
        $this->arr_view_data['module_url_path']                  = $this->module_url_path;
        $this->arr_view_data['admin_photos_public_img_path']     = $this->admin_photos_public_img_path;
        $this->arr_view_data['admin_footage_image_public_path']  = $this->admin_footage_image_public_path;

        return view('front.seller.photos_and_footage.index',$this->arr_view_data);
    }

    public function index_admin()
    {
        $obj_arr_data   = false;
        $arr_data       = [];
        $user           = [];
        $arr_pagination = [];
        $seller_id      = '';

        //Check User
        $user = Sentinel::check();

        if(isset($user) && $user==true)
        {
            $seller_id    = $user['id'];
            $obj_arr_data = $this->MediaListingMasterModel->where('seller_id',$seller_id)->where('is_admin_uploaded',1)->orderBy('created_at','DESC')->paginate(10);
            if($obj_arr_data!=false)
            {
                $arr_pagination   = $obj_arr_data;
                $arr_data         = $obj_arr_data->toArray();
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

        $this->arr_view_data['arr_data']                         = $arr_data;
        $this->arr_view_data['arr_pagination']                   = $arr_pagination;
        $this->arr_view_data['title']                            = "My Photos and Footage by Admin- ".config('app.project.name');
        $this->arr_view_data['module_title']                     = "My Photos and Footage by Admin";
        $this->arr_view_data['module_url_path']                  = $this->module_url_path;
        $this->arr_view_data['admin_photos_public_img_path']     = $this->admin_photos_public_img_path;
        $this->arr_view_data['admin_footage_image_public_path']  = $this->admin_footage_image_public_path;        

        return view('front.seller.photos_and_footage.index_admin',$this->arr_view_data);
    }    

    public function add()
    {
        $obj_resolution         = false;
        $obj_ratio              = false;
        $obj_fps                = false;
        $obj_photo_formats      = false;
        $obj_footage_formats    = false;
        $obj_orientation        = false;
        $arr_resolution         = [];
        $arr_ratio              = [];
        $arr_fps                = [];
        $arr_photo_formats      = [];
        $arr_footage_formats    = [];
        $arr_orientation        = [];

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

        $this->arr_view_data['title']                   = "Upload Photos and Footage - ".config('app.project.name');
        $this->arr_view_data['module_title']            = "Upload Photos and Footage";
        $this->arr_view_data['module_url_path']         = $this->module_url_path;
        $this->arr_view_data['arr_resolution']          = $arr_resolution;
        $this->arr_view_data['arr_ratio']               = $arr_ratio;
        $this->arr_view_data['arr_fps']                 = $arr_fps;
        $this->arr_view_data['arr_footage_formats']     = $arr_footage_formats;
        $this->arr_view_data['arr_photo_formats']       = $arr_photo_formats;
        $this->arr_view_data['arr_orientation']         = $arr_orientation;

        return view('front.seller.photos_and_footage.add',$this->arr_view_data);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $arr_rules          = [];
        $user               = [];
        $arr_data           = [];
        $arr_list_data      = [];
        $insert_master_list = [];
        $arr_listing_details= [];
        $arr_commission     = [];
        $seller_id          = "";
        $media_slug         = '';
        $lastInsertId       = $title = '';
        $check_if_exists    = $commission = 0;

        $arr_rules['type']                = "required";
        $arr_rules['title']               = "required|min:3|max:255"; 
        $arr_rules['keywords']            = "required|min:3|max:255";
        $arr_rules['description']         = "required|min:10|max:1000";

        if($request->has('type') && $request->input('type')=='photo')
        {
            $arr_rules['photo_price']             = "required"; 
            $arr_rules['photo_format']        = "required";
            $arr_rules['orientation']         = "required";         
            $arr_rules['arr_photo']           = "required";         
        }

        if($request->has('type') && $request->input('type')=='footage')
        {
            $arr_rules['footage_price']   = "required"; 
            $arr_rules['ratio']           = "required"; 
            $arr_rules['duration']        = "required";
            $arr_rules['alpha_channel']   = "required";
            $arr_rules['alpha_matte']     = "required";
            $arr_rules['media_release']   = "required";
            $arr_rules['looping']         = "required";
            $arr_rules['model_release']   = "required";
            $arr_rules['liscense_type']   = "required";
            $arr_rules['fx']              = "required";
            $arr_rules['footage_format']  = "required";         
            $arr_rules['resolution']      = "required";         
            $arr_rules['fps']             = "required";         
            $arr_rules['arr_footage']     = "required";         
        }

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            //return ['status'=>'error','msg'=> 'All Fields are required'];
            //return redirect()->back()->withErrors($validator)->withInput();  
            return response()->json(['status'=>'error1']);
            //return 'error1';
        }
        //Check User
        $user = Sentinel::check();

        if(isset($user) && $user==true)
        {
            $seller_id   = $user['id'];
            $seller_name = $user['first_name'].' '.$user['last_name'];

            $arr_commission = $this->CommissionModel->first();

            if(isset($arr_commission) && count($arr_commission)>0)
            {
              $arr_commission = $arr_commission->toArray();
              $commission     = $arr_commission['commission'];
            }

            $title = $request->input('title');
            $type  = $request->input('type');
            
            $check_if_exists = $this->MediaListingMasterModel->where('title',$title)->where('type',$type)->count();

            if($check_if_exists>0)
            {
                Flash::error('This '.$request->input('type').' is already exists.');
                //return redirect()->back();
                
                /*Flash::error('This '.$request->input('type').' is already exists.');
                return response()->json(['status'=>'error','msg'=> 'This '.$request->input('type').' is already exists.']);*/
                return response()->json(['status'=>'error2']);
            }
            else
            {
                $media_slug = $request->input('title');
                $media_slug = trim($request->input('title'));
                $media_slug = str_replace(' ', '_', $media_slug);
                $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
                $media_slug = $media_slug.rand(10,100);

                $arr_data['type']            = $request->input('type') ;
                $arr_data['slug']            = $media_slug ;
                $arr_data['seller_id']       = $seller_id ;
                $arr_data['title']           = $request->input('title') ;
                $arr_data['keywords']        = strtolower($request->input('keywords')) ;
                $arr_data['description']     = $request->input('description') ;
                $arr_data['commission']      = $commission ;
                
                if($request->has('type') && $request->input('type')=='photo')
                {
                    $arr_list_data['price']          = $request->input('photo_price') ;
                    $arr_list_data['photo_format']   = $request->input('photo_format') ;
                    $arr_list_data['orientation']    = $request->input('orientation') ;
                    $arr_list_data['arr_photo']      = $request->file('arr_photo') ;
                }

                if($request->has('type') && $request->input('type')=='footage')
                {
                    $arr_data['ratio']               = $request->input('ratio') ;
                    $arr_data['duration']            = $request->input('duration') ;
                    $arr_data['alpha_channel']       = $request->input('alpha_channel') ;
                    $arr_data['alpha_matte']         = $request->input('alpha_matte') ;
                    $arr_data['media_release']       = $request->input('media_release') ;
                    $arr_data['looping']             = $request->input('looping') ;
                    $arr_data['model_release']       = $request->input('model_release') ;
                    $arr_data['liscense_type']       = $request->input('liscense_type') ;
                    $arr_data['fx']                  = $request->input('fx') ;
                    
                    $arr_list_data['price']          = $request->input('footage_price') ;
                    $arr_list_data['footage_format'] = $request->input('footage_format') ;
                    $arr_list_data['resolution']     = $request->input('resolution') ;
                    $arr_list_data['fps']            = $request->input('fps') ;
                    $arr_list_data['arr_footage']    = $request->file('arr_footage') ;
                }

                $insert_master_list = $this->MediaListingMasterModel->create($arr_data);
                
                if($insert_master_list)
                {
                    $lastInsertId = $insert_master_list->id;

                    /*-------------------------------------------- If selected media type is Photo -------------------------------------------------*/
                    if($arr_data['type']=='photo')
                    {
                        if(isset($arr_list_data['price']) &&  isset($arr_list_data['photo_format'])  && isset($arr_list_data['orientation']))
                        {
                            if(count($arr_list_data['price'])>0 && count($arr_list_data['photo_format'])>0 && count($arr_list_data['orientation'])>0 && count($arr_list_data['arr_photo'])>0)
                            {
                                
                                foreach($arr_list_data['arr_photo'] as $key => $file)
                                {
                                    $filename           = rand(1111,9999);
                                    $original_file_name = $file->getClientOriginalName();
                                    $fileExt            = $file-> getClientOriginalExtension();
                                    $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

                                    $isUpload = $file->move($this->photos_base_img_path, $fileName);
                                    if($isUpload)
                                    {
                                        $arr_listing_details['list_id']       = $lastInsertId;  
                                        $arr_listing_details['enc_item_name'] = $fileName;  
                                        $arr_listing_details['item_name']     = $original_file_name;

                                        $arr_listing_details['price']       = $arr_list_data['price'][$key];  
                                        $arr_listing_details['format']      = $arr_list_data['photo_format'][$key];  
                                        $arr_listing_details['orientation'] = $arr_list_data['orientation'][$key];
                                        
                                        $insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
                                    }
                                }
                            }
                        }
                    }

                    /*-------------------------------------------- If selected media type is Footage -------------------------------------------------*/
                    if($arr_data['type']=='footage')
                    {

                        if(isset($arr_list_data['price']) &&  isset($arr_list_data['footage_format'])  && isset($arr_list_data['resolution']) && isset($arr_list_data['fps']))
                        {
                            if(count($arr_list_data['price'])>0 && count($arr_list_data['footage_format'])>0 && count($arr_list_data['resolution'])>0 && count($arr_list_data['arr_footage'])>0 && count($arr_list_data['fps'])>0 )
                            {
                                
                                foreach($arr_list_data['arr_footage'] as $key => $file)
                                {
                                    $filename           = rand(1111,9999);
                                    $original_file_name = $file->getClientOriginalName();
                                    $fileExt            = $file-> getClientOriginalExtension();
                                    $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

                                    $isUpload = $file->move($this->footage_base_img_path, $fileName);
                                    if($isUpload)
                                    {
                                        $arr_listing_details['list_id']       = $lastInsertId;  
                                        $arr_listing_details['enc_item_name'] = $fileName;  
                                        $arr_listing_details['item_name']     = $original_file_name;

                                        $arr_listing_details['price']         = $arr_list_data['price'][$key];  
                                        $arr_listing_details['format']        = $arr_list_data['footage_format'][$key];  
                                        $arr_listing_details['resolution']    = $arr_list_data['resolution'][$key];
                                        $arr_listing_details['fps']           = $arr_list_data['fps'][$key];
                                        
                                        $insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
                                    }
                                }
                            }
                        }
                    }
                    
                    if($insert_list_details)
                    {
                        // Admin notification
                        $arr_notification_data_admin                 = [];
                        $send_notification_admin                     = false;
                        $arr_notification_data_admin['from_user_id'] = $seller_id;
                        $arr_notification_data_admin['to_user_id']   = 1;
                        $arr_notification_data_admin['message']      = 'Hello! '.$seller_name.' has uploaded new '.$arr_data['type'].' and waiting for Approval.'.'<a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';

                        //dd($arr_notification_data_admin);
                        $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

                        // Seller notification
                        $arr_notification_data_seller                 = [];
                        $send_notification_seller                     = false;
                        $arr_notification_data_seller['from_user_id'] = 1;
                        $arr_notification_data_seller['to_user_id']   = $seller_id;
                        $arr_notification_data_seller['message']      = 'Hello! You have uplaoded new '.$arr_data['type'].' and sent for Approval.'.'<a href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';

                        //dd($arr_notification_data_seller);
                       
                        $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);                    

                        Flash::success('Your '.ucwords($arr_data['type'].' has been uploaded successfully and sent for approval.'));
                        //return redirect(url('/seller/photos_and_footage'));
                        return response()->json(['status'=>'success']);
                        //return "success";
                        //return response()->json(['status'=>'success','msg'=> 'This '.$request->input('type').' is already exists.','url'=>url('/seller/photos_and_footage')]);                        
                    }
                    else
                    {
                        //Flash::error('Error occured while storing '.ucwords($arr_data['type']));
                        //return response()->json(['status'=>'error','msg'=> 'Error occured while storing '.ucwords($arr_data['type'])]);
                        return response()->json(['status'=>'error']);
                        //return "error";
                    }
                }
            }    
        }
        else
        {
            //Flash::error('Error occured while storing '.ucwords($arr_data['type']));
            //return redirect()->back();
            return response()->json(['status'=>'error']);
        }
    }

    public function edit($enc_id)
    {
        $obj_resolution         = false;
        $obj_ratio              = false;
        $obj_fps                = false;
        $obj_photo_formats      = false;
        $obj_footage_formats    = false;
        $obj_orientation        = false;
        $obj_arr_data           = false;
        $arr_resolution         = [];
        $arr_ratio              = [];
        $arr_fps                = [];
        $arr_photo_formats      = [];
        $arr_footage_formats    = [];
        $arr_orientation        = [];
        $arr_data               = [];
        $list_id                = '';

        if($enc_id!='')
        {
            $list_id = base64_decode($enc_id);
            $obj_arr_data = $this->MediaListingMasterModel->where('id',$list_id)
                                                          ->with('listing_details')
                                                          ->first();
            if($obj_arr_data!=false)
            {
                $arr_data = $obj_arr_data->toArray();   
            }

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
        }

        $this->arr_view_data['title']                   = "Edit Photos and Footage - ".config('app.project.name');
        $this->arr_view_data['module_title']            = "Edit Photos and Footage";
        $this->arr_view_data['photos_public_img_path']  = $this->photos_public_img_path;
        $this->arr_view_data['footage_public_img_path'] = $this->footage_public_img_path;
        $this->arr_view_data['module_url_path']         = $this->module_url_path;
        $this->arr_view_data['arr_resolution']          = $arr_resolution;
        $this->arr_view_data['arr_ratio']               = $arr_ratio;
        $this->arr_view_data['arr_fps']                 = $arr_fps;
        $this->arr_view_data['arr_footage_formats']     = $arr_footage_formats;
        $this->arr_view_data['arr_photo_formats']       = $arr_photo_formats;
        $this->arr_view_data['arr_orientation']         = $arr_orientation;
        $this->arr_view_data['arr_data']                = $arr_data;
        $this->arr_view_data['enc_id']                  = $enc_id;

        return view('front.seller.photos_and_footage.edit',$this->arr_view_data);
    }

    public function update(Request $request,$enc_id)
    {
        $arr_rules             = [];
        $user                  = [];
        $seller_id             = [];
        $arr_data              = [];
        $arr_list_data         = [];
        $insert_master_list    = [];
        $arr_listing_details   = [];
        $oldimage              = [];
        $oldimagename          = [];
        $oldfootage            = [];
        $oldfootagename        = [];
        $listing_id            = [];
        $get_master_list       = [];
        $update_master_list    = false;
        $insert_list_details   = false;
        $update_list_details   = false;

        $lastInsertId          = $title = $media_slug = '';
        $check_if_exists       = 0;
        $update_status_message = 0;
        $list_id               = base64_decode($enc_id);

        $arr_rules['type']                = "required";
        $arr_rules['title']               = "required|min:3|max:255"; 
        $arr_rules['keywords']            = "required|min:3|max:255";
        $arr_rules['description']         = "required|min:10|max:1000";
        $arr_rules['price']               = "required"; 

        if($request->has('type') && $request->input('type')=='photo')
        {
            $arr_rules['photo_format']        = "required";
            $arr_rules['orientation']         = "required";         
            //$arr_rules['arr_photo']           = "required";           
        }

        if($request->has('type') && $request->input('type')=='footage')
        {
            $arr_rules['ratio']           = "required"; 
            $arr_rules['duration']        = "required";
            $arr_rules['alpha_channel']   = "required";
            $arr_rules['alpha_matte']     = "required";
            $arr_rules['media_release']   = "required";
            $arr_rules['looping']         = "required";
            $arr_rules['model_release']   = "required";
            $arr_rules['liscense_type']   = "required";
            $arr_rules['fx']              = "required";
            $arr_rules['footage_format']  = "required";         
            $arr_rules['resolution']      = "required";         
            $arr_rules['fps']             = "required";         
            //$arr_rules['arr_footage']     = "required";           
        }

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return response()->json(['status'=>'error1']);
        }
        //Check User
        $user = Sentinel::check();

        if(isset($user) && $user==true)
        {
            $seller_id = $user['id'];
            $seller_name = $user['first_name'].' '.$user['last_name'];
            
            $title = $request->input('title');
            $type = $request->input('type');
            
            $check_if_exists = $this->MediaListingMasterModel->where('title',$title)->where('id','<>',$list_id)->where('type',$type)->count();

            if($check_if_exists>0)
            {
                return response()->json(['status'=>'error2']);
            }

            $media_slug = $request->input('title');
            $media_slug = trim($request->input('title'));
            $media_slug = str_replace(' ', '_', $media_slug);
            $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
            $media_slug = $media_slug.rand(10,100);

            $arr_data['slug']            = $media_slug ;
            $arr_data['type']            = $request->input('type') ;
            $arr_data['seller_id']       = $seller_id ;
            $arr_data['title']           = $request->input('title') ;
            $arr_data['keywords']        = strtolower($request->input('keywords')) ;
            $arr_data['description']     = $request->input('description') ;

            if($request->has('listing_id'))
            {
                $listing_id = $request->input('listing_id');
            }

            if($request->has('type') && $request->input('type')=='photo')
            {
                $arr_list_data['price']          = $request->input('price') ;
                $arr_list_data['photo_format']   = $request->input('photo_format') ;
                $arr_list_data['orientation']    = $request->input('orientation') ;
                $arr_list_data['arr_photo']      = $request->file('arr_photo') ;
                
                $oldimage                        = $request->input('oldimage');
                $oldimagename                    = $request->input('oldimagename');
            }

            if($request->has('type') && $request->input('type')=='footage')
            {
                $arr_data['ratio']               = $request->input('ratio') ;
                $arr_data['duration']            = $request->input('duration') ;
                $arr_data['alpha_channel']       = $request->input('alpha_channel') ;
                $arr_data['alpha_matte']         = $request->input('alpha_matte') ;
                $arr_data['media_release']       = $request->input('media_release') ;
                $arr_data['looping']             = $request->input('looping') ;
                $arr_data['model_release']       = $request->input('model_release') ;
                $arr_data['liscense_type']       = $request->input('liscense_type') ;
                $arr_data['fx']                  = $request->input('fx') ;
                
                $arr_list_data['price']          = $request->input('price') ;
                $arr_list_data['footage_format'] = $request->input('footage_format') ;
                $arr_list_data['resolution']     = $request->input('resolution') ;
                $arr_list_data['fps']            = $request->input('fps') ;
                $arr_list_data['arr_footage']    = $request->file('arr_footage') ;
            
                $oldfootage                      = $request->input('oldfootage');
                $oldfootagename                  = $request->input('oldfootagename');
            }

            $get_master_list = $this->MediaListingMasterModel->where('id',$list_id)->first();
            if(isset($get_master_list) && count($get_master_list))
            {
                $get_master_list = $get_master_list->toArray();
            }

            $insert_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data);
            
            if($insert_master_list)
            {
                $lastInsertId = $list_id;

                /*-------------------------------------------- Deleting old values -------------------------------------------------*/

                $obj_delete_old_photos_and_footages = [];
                $obj_delete_old_photos_and_footages = $this->MediaListingDetailModel->where('list_id',$list_id)->get();

                if($arr_data['type']=='photo')
                {
                    if(count($obj_delete_old_photos_and_footages)!=count($arr_list_data['arr_photo']))
                    {
                        $arr_data_approve = [];
                        $arr_data_approve = ['is_approved'=>0];
                        
                        $update_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data_approve);
                        $update_status_message = 1;
                    }
                }
                else
                {
                    if(count($obj_delete_old_photos_and_footages)!=count($arr_list_data['arr_footage']))
                    {
                        $arr_data_approve = [];
                        $arr_data_approve = ['is_approved'=>0];
                        
                        $update_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data_approve);
                        $update_status_message = 1;
                    }                    
                }

                if(isset($obj_delete_old_photos_and_footages) && count($obj_delete_old_photos_and_footages)>0)
                {
                    $obj_delete_old_photos_and_footages = $obj_delete_old_photos_and_footages->toArray();

                    foreach ($obj_delete_old_photos_and_footages as $key => $listing)
                    {
                        foreach ($listing_id as $listing_key => $current_id)
                        {                           
                            if($arr_data['type']=='photo')
                            {
                                if($current_id==$listing['id'])
                                {
                                    if(
                                            $arr_data['title']                           != $get_master_list['title'] ||
                                            $arr_data['keywords']                        != $get_master_list['keywords'] || 
                                            $arr_data['description']                     != $get_master_list['description'] ||
                                            $arr_list_data['price'][$listing_key]        != $listing['price'] ||
                                            $arr_list_data['photo_format'][$listing_key] != $listing['format'] ||
                                            $arr_list_data['orientation'][$listing_key]  != $listing['orientation']
                                            )
                                        {
                                            $arr_data_approve = [];
                                            $arr_data_approve = ['is_approved'=>0];
                                            
                                            $update_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data_approve);
                                            $update_status_message = 1;
                                        }
                                }

                                if($current_id==$listing['id'] && $arr_list_data['arr_photo'][$listing_key]!=null)
                                {                               
                                    @unlink($this->photos_base_img_path.$listing['enc_item_name']);
                                    $arr_data_approve = [];
                                    $arr_data_approve = ['is_approved'=>0];
                                    
                                    $update_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data_approve);
                                    $update_status_message = 1;
                                }
                            }
                            else if($arr_data['type']=='footage')
                            {
                                if($current_id==$listing['id'])
                                {
                                    if(
                                            $arr_data['title']                              != $get_master_list['title'] ||
                                            $arr_data['keywords']                           != $get_master_list['keywords'] || 
                                            $arr_data['description']                        != $get_master_list['description'] ||
                                            $arr_data['duration']                           != $get_master_list['duration'] ||
                                            $arr_data['ratio']                              != $get_master_list['ratio'] ||
                                            $arr_data['alpha_channel']                      != $get_master_list['alpha_channel'] ||
                                            $arr_data['alpha_matte']                        != $get_master_list['alpha_matte'] ||
                                            $arr_data['media_release']                      != $get_master_list['media_release'] ||
                                            $arr_data['looping']                            != $get_master_list['looping'] ||
                                            $arr_data['model_release']                      != $get_master_list['model_release'] ||
                                            $arr_data['liscense_type']                      != $get_master_list['liscense_type'] ||
                                            $arr_data['fx']                                 != $get_master_list['fx'] ||
                                            $arr_list_data['price'][$listing_key]           != $listing['price'] ||
                                            $arr_list_data['footage_format'][$listing_key]  != $listing['format'] ||
                                            $arr_list_data['resolution'][$listing_key]      != $listing['resolution'] ||
                                            $arr_list_data['fps'][$listing_key]             != $listing['fps']
                                          )
                                    {
                                        $arr_data_approve = [];
                                        $arr_data_approve = ['is_approved'=>0];
                                        
                                        $update_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data_approve);
                                        $update_status_message = 1;
                                    }
                                }

                                if($current_id==$listing['id'] && $arr_list_data['arr_footage'][$listing_key]!=null)
                                {                                   
                                    $arr_data_approve = [];
                                    $arr_data_approve = ['is_approved'=>0];
                                    
                                    $update_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data_approve);
                                    $update_status_message = 1;

                                    @unlink($this->footage_base_img_path.$listing['enc_item_name']);
                                }
                            }   
                        }
                        $delete_obj_from_media_lisitng_detail = $this->MediaListingDetailModel->where('list_id',$list_id)->delete();
                    }

                }
                /*-------------------------------------------- If selected media type is Photo -------------------------------------------------*/
                
                if($arr_data['type']=='photo')
                {
                    if(isset($arr_list_data['price']) &&  isset($arr_list_data['photo_format'])  && isset($arr_list_data['orientation']))
                    {
                        if(count($arr_list_data['price'])>0 && count($arr_list_data['photo_format'])>0 && count($arr_list_data['orientation'])>0)
                        {                           
                            foreach($arr_list_data['arr_photo'] as $key => $file)
                            {
                                if($file!=null)
                                {
                                    $filename           = rand(1111,9999);
                                    $original_file_name = $file->getClientOriginalName();
                                    $fileExt            = $file-> getClientOriginalExtension();
                                    $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

                                    $isUpload = $file->move($this->photos_base_img_path, $fileName);    
                                    if($isUpload)
                                    {
                                        $arr_listing_details['enc_item_name'] = $fileName;
                                        $arr_listing_details['item_name']     = $original_file_name;
                                    }  
                                }
                                else
                                {
                                    $arr_listing_details['enc_item_name'] = $oldimage[$key];
                                    $arr_listing_details['item_name']     = $oldimagename[$key];
                                }
                                
                                $arr_listing_details['list_id']           = $lastInsertId;  
                                $arr_listing_details['price']             = $arr_list_data['price'][$key];  
                                $arr_listing_details['format']            = $arr_list_data['photo_format'][$key];  
                                $arr_listing_details['orientation']       = $arr_list_data['orientation'][$key];
                                
                                $insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
                            }
                        }
                    }
                }

                /*-------------------------------------------- If selected media type is Footage -------------------------------------------------*/
                if($arr_data['type']=='footage')
                {
                    if(isset($arr_list_data['price']) &&  isset($arr_list_data['footage_format'])  && isset($arr_list_data['resolution']) && isset($arr_list_data['fps']))
                    {
                        if(count($arr_list_data['price'])>0 && count($arr_list_data['footage_format'])>0 && count($arr_list_data['resolution'])>0 && count($arr_list_data['arr_footage'])>0 && count($arr_list_data['fps'])>0 )
                        {                           
                            foreach($arr_list_data['arr_footage'] as $key => $file)
                            {
                                if($file!=null)
                                {
                                    $filename           = rand(1111,9999);
                                    $original_file_name = $file->getClientOriginalName();
                                    $fileExt            = $file-> getClientOriginalExtension();
                                    $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

                                    $isUpload = $file->move($this->footage_base_img_path, $fileName);
                                    if($isUpload)
                                    {
                                        $arr_listing_details['enc_item_name'] = $fileName;
                                        $arr_listing_details['item_name']     = $original_file_name;
                                    }
                                }   
                                else
                                {
                                    $arr_listing_details['enc_item_name'] = $oldfootage[$key];
                                    $arr_listing_details['item_name']     = $oldfootagename[$key];
                                }
                                
                                $arr_listing_details['list_id']       = $lastInsertId;  
                                $arr_listing_details['price']         = $arr_list_data['price'][$key];  
                                $arr_listing_details['format']        = $arr_list_data['footage_format'][$key];  
                                $arr_listing_details['resolution']    = $arr_list_data['resolution'][$key];
                                $arr_listing_details['fps']           = $arr_list_data['fps'][$key];
                                
                                $insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
                            }
                        }
                    }
                }
                
                if($insert_list_details)
                {
                    // Admin notification
                    $arr_notification_data_admin                 = [];
                    $send_notification_admin                     = false;
                    $arr_notification_data_admin['from_user_id'] = $seller_id;
                    $arr_notification_data_admin['to_user_id']   = 1;
                    $arr_notification_data_admin['message']      = 'Hello! '.$seller_name.' has updated '.$arr_data['type'].' and waiting for Approval.'.'<a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($list_id).'">View</a>';

                    //dd($arr_notification_data_admin);
                    $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

                    if($update_status_message==0)
                    {
                        return response()->json(['status'=>'success2']);
                        /*Flash::success('Your '.ucwords($arr_data['type'].' has been updated successfully.'));
                        return redirect(url('/seller/photos_and_footage'));*/
                    }
                    else
                    {
                        // Seller notification
                        $arr_notification_data_seller                 = [];
                        $send_notification_seller                     = false;
                        $arr_notification_data_seller['from_user_id'] = 1;
                        $arr_notification_data_seller['to_user_id']   = $seller_id;
                        $arr_notification_data_seller['message']      = 'Hello! You have updated '.$arr_data['type'].' and sent for Approval.'.'<a href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($list_id).'">View</a>';

                       /* $arr_notification_data_seller['message']      = 'Hello! You have updated '.$arr_data['type'].' and sent for Approval.';*/

                        //dd($arr_notification_data_seller);

                        $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);

                        return response()->json(['status'=>'success1']);
                        /*Flash::success('Your '.ucwords($arr_data['type'].' has been updated successfully and sent for approval.'));
                        return redirect(url('/seller/photos_and_footage'));*/
                    }
                }
                else
                {
                        return response()->json(['status'=>'error']);

                   /* Flash::error('Error occured while updating '.ucwords($arr_data['type']));
                    return redirect()->back();  */                  
                }
            }
        }
        else
        {
                return "error";
            /*Flash::error('Error occured while updating '.ucwords($arr_data['type']));
            return redirect()->back();*/
        }
    }

    public function delete($enc_id)
    {
        $status         = false;
        $master_status  = false;
        $obj_data       = false;
        $user           = [];
        $list_id        = '';
        $seller_id      = '';
        $seller_name    = '';
        $arr_data       = [];
        $obj_delete_old_photos_and_footages = [];

        $user = Sentinel::check();

        if($enc_id!='' && $user==true)
        {
            $seller_id = $user['id'];
            $list_id = base64_decode($enc_id);

            /*---------------------------------------- Delete and Unlink images and data ----------------------------------------------*/
            $obj_data = $this->MediaListingMasterModel->where('id',$list_id)->with('seller_details')->first();
            
            if($obj_data!=false)
            {
                $obj_data    = $obj_data->toArray();
                $seller_name = $obj_data['seller_details']['first_name'].' '.$obj_data['seller_details']['last_name'];

                $obj_delete_old_photos_and_footages = $this->MediaListingDetailModel->where('list_id',$list_id)->get();
                
                foreach ($obj_delete_old_photos_and_footages as $key => $listing)
                {
                    if($obj_data['type']=='photo')
                    {
                        @unlink($this->photos_base_img_path.$listing['enc_item_name']);
                    }
                    else if($obj_data['type']=='footage')
                    {
                        @unlink($this->footage_base_img_path.$listing['enc_item_name']);
                    }   
                    
                    /*------------------------------------ Delete From my favourite and Package --------------------------------*/
                    $delete_form_favourite = $this->FavouriteModel->where('list_id',$listing['id'])->delete();

                    $delete_form_package   = $this->PackageDetailModel->where('list_id',$listing['id'])->delete();
                
                }
                // Delete From the cart
                $delete_form_cart = $this->CartModel->where('master_id',$list_id)->delete();

                $master_status = $this->MediaListingMasterModel->where('id',$list_id)->where('seller_id',$seller_id)->delete();

                if($master_status)
                {
                    $status = $this->MediaListingDetailModel->where('list_id',$list_id)->delete();
                    if($status)
                    {
                        // Admin notification
                        $arr_notification_data_admin                 = [];
                        $send_notification_admin                     = false;
                        $arr_notification_data_admin['from_user_id'] = $seller_id;
                        $arr_notification_data_admin['to_user_id']   = 1;
                        $arr_notification_data_admin['message']      = 'Hello! '.$seller_name.' has deleted '. ucwords($obj_data['title']).' '.ucwords($obj_data['type']);

                        $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

                        // Seller notification
                        $arr_notification_data_seller                 = [];
                        $send_notification_seller                     = false;
                        $arr_notification_data_seller['from_user_id'] = 1;
                        $arr_notification_data_seller['to_user_id']   = $seller_id;
                        $arr_notification_data_seller['message']      = 'Hello! You have deleted '.ucwords($obj_data['title']).' '.ucwords($obj_data['type']).' successfully.';

                        $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);

                        Flash::success('Your '.$obj_data['type'].' has been deleted successfully.');
                        return redirect(url('/seller/photos_and_footage'));                  
                    }
                    else
                    {
                        Flash::error('Error occured while deleting '.$obj_data['type'].'.');
                        return redirect(url('/seller/photos_and_footage'));                  
                    }
                }
                else
                {
                    Flash::error('Error occured while deleting '.$obj_data['type'].'.');
                    return redirect(url('/seller/photos_and_footage'));                  
                }
            }
        }
        else
        {
            Flash::error('Error occured while deleting '.$obj_data['type'].'.');
            return redirect(url('/seller/photos_and_footage'));                  
        }
    }

    public function view($enc_id)
    {
        $user                   = [];
        $obj_duration           = false;
        $obj_ratio              = false;
        $obj_arr_data           = false;
        $arr_duration           = [];
        $arr_ratio              = [];
        $arr_data               = [];
        $list_id                = '';

        $user = Sentinel::check();
        if($enc_id!='' && isset($user))
        {
            $user_id = $user['id'];
            $list_id = base64_decode($enc_id);
            $obj_arr_data = $this->MediaListingMasterModel->where('id',$list_id)
                                                          ->with(['listing_details'=>function($query){
                                                            $query->with('format_details')
                                                                  ->with('orientation_details')
                                                                  ->with('resolution_details')
                                                                  ->with('fps_details');
                                                          }])                                                         
                                                          ->first();

            if($obj_arr_data!=false)
            {
                $arr_data = $obj_arr_data->toArray();           

                $obj_duration = $this->DurationModel->where('id',$arr_data['duration'])->orderBy('value','ASC')->first();
                if($obj_duration!=false)
                {
                    $arr_duration = $obj_duration->toArray();           
                }

                $obj_ratio = $this->RatioModel->where('id',$arr_data['ratio'])->orderBy('value','ASC')->first();
                if($obj_ratio!=false)
                {
                    $arr_ratio = $obj_ratio->toArray();         
                }
            }
            else
            {
                return redirect()->back();
            }
        }

        $this->arr_view_data['title']                   = "View Photos and Footage - ".config('app.project.name');
        $this->arr_view_data['module_title']            = "View Photos and Footage";
        $this->arr_view_data['photos_public_img_path']  = $this->photos_public_img_path;
        $this->arr_view_data['footage_public_img_path'] = $this->footage_public_img_path;
        $this->arr_view_data['module_url_path']         = $this->module_url_path;
        $this->arr_view_data['arr_duration']            = $arr_duration;
        $this->arr_view_data['arr_ratio']               = $arr_ratio;
        $this->arr_view_data['arr_data']                = $arr_data;
        $this->arr_view_data['enc_id']                  = $enc_id;

        return view('front.seller.photos_and_footage.view',$this->arr_view_data);
    }

    /*------------------------ Check media title duplication for ajax request ----------------------------*/
    public function check_media_duplication(Request $request)
    {
        $check_status = false;
        $enc_id = '';
        
        if($request->has('title') && $request->has('type'))
        {
            $title = $request->input('title');
            $type  = $request->input('type');
            if($request->has('enc_id'))
            {
                $enc_id = base64_decode($request->input('enc_id'));
                $check_status = $this->MediaListingMasterModel->where('id','<>',$enc_id)->where('title',$title)->where('type',$type)->first();
            }
            else
            {
                $check_status = $this->MediaListingMasterModel->where('title',$title)->where('type',$type)->first();
            }
            
            if(count($check_status)>0)
            {
                return 'true';
            }
            else
            {
                return 'false';
            }
        }
        return 'false';
    }        
}
