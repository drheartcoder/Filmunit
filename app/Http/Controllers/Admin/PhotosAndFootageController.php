<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Traits\MultiActionTrait;

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

use App\Common\Services\EmailService;
use App\Common\Services\CommonDataService;

use Validator;
use Session;
use Flash;
use File;
use Sentinel;
use DB;
use Datatables;
use Image;

class PhotosAndFootageController extends Controller
{
    use MultiActionTrait;
    
    public function __construct(UserModel $user_model,
    							ResolutionModel $resolution_model,
    							DurationModel $duration_model,
    							RatioModel $ratio_model,
    							FpsModel $fps_model,
    							FormatModel $format_model,
    							OrientationModel $orientation_model,
    							MediaListingMasterModel $media_listing_master_model,
    							MediaListingDetailModel $media_listing_detail_model,
                  NotificationModel $notify,
								  CommissionModel $comission_model,
								  EmailService $mail_service,
                  CartModel $cart_model,
                  FavouriteModel $favourite_model,
                  PackageDetailModel $package_detail_model,
                  CommonDataService $commondataservice
    							)  
    {
  		$this->UserModel 	 				        = $user_model;
  		$this->ResolutionModel 	 	    	  = $resolution_model;
  		$this->DurationModel 	 	    	    = $duration_model;
  		$this->RatioModel 	 	    		    = $ratio_model;
  		$this->FpsModel 	 	    		      = $fps_model;
  		$this->FormatModel 	 	    		    = $format_model;
  		$this->OrientationModel 	 		    = $orientation_model;
  		$this->MediaListingMasterModel 	 	= $media_listing_master_model;
  		$this->MediaListingDetailModel 	 	= $media_listing_detail_model;
      $this->NotificationModel          = $notify;
  		$this->CommissionModel      		  = $comission_model;
  		$this->EmailService       	      = $mail_service;
      $this->CommonDataService          = $commondataservice;
      $this->CartModel                  = $cart_model;
      $this->FavouriteModel             = $favourite_model;
      $this->PackageDetailModel         = $package_detail_model;

      $this->arr_view_data 				              = [];
      $this->module_title             	        = "Photos And Footage";
      $this->module_url_path             	      = "photos_and_footage";
      $this->module_url_slug          	        = "photos_and_footage";
      $this->module_view_folder       	        = "admin.photos_and_footage";
      $this->theme_color                        = theme_color();
      $this->photos_base_img_path               = config('app.project.img_path.photos');
      $this->admin_photos_base_img_path         = config('app.project.img_path.admin_photos');
      $this->photos_public_img_path             = url('/').'/'.config('app.project.img_path.photos');
      $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
      $this->footage_base_img_path              = config('app.project.img_path.footage');
      $this->admin_footage_base_img_path     	  = config('app.project.img_path.admin_footage');
      $this->footage_public_img_path            = url('/').'/'.config('app.project.img_path.footage');
      $this->admin_footage_public_img_path      = url('/').'/'.config('app.project.img_path.admin_footage');
      $this->admin_footage_image_public_path    = url('/').'/'.config('app.project.img_path.admin_footage_image');
      $this->admin_footage_image_base_path      = config('app.project.img_path.admin_footage_image');
    } 
 
    public function index()
    {
    	$obj_arr_data 	= false;
    	$arr_data     	= [];
        
		  $obj_arr_data = $this->MediaListingMasterModel->with(['seller_details'=>function($query){
															$query->select('id','first_name','last_name')->where('is_active',1);
														}])
													  ->orderBy('id','DESC')
													  ->get();

   		if($obj_arr_data!=false)
   		{
            $arr_pagination   = $obj_arr_data;
   			    $arr_data         = $obj_arr_data->toArray();
   		}

        $this->arr_view_data['photos_public_img_path']        = $this->photos_public_img_path;
        $this->arr_view_data['footage_public_img_path']       = $this->footage_public_img_path;
        $this->arr_view_data['admin_photos_public_img_path']  = $this->admin_photos_public_img_path;
        $this->arr_view_data['admin_footage_public_img_path'] = $this->admin_footage_public_img_path;
        $this->arr_view_data['admin_footage_base_img_path']   = $this->admin_footage_base_img_path;
        $this->arr_view_data['admin_footage_image_base_path'] = $this->admin_footage_image_base_path;
        $this->arr_view_data['admin_footage_image_public_path'] = $this->admin_footage_image_public_path;
    	  $this->arr_view_data['arr_data'] 				              = $arr_data;
        $this->arr_view_data['page_title']                    = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_title']                  = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']               = $this->module_url_path;
        $this->arr_view_data['theme_color']                   = $this->theme_color;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function get_records(Request $request)
    {        
        $arr_current_user_access =[];
        $arr_current_user_access = $request->user()->permissions;

        $obj_list        = $this->get_media_details($request);

        $current_context = $this;
        $json_result     = Datatables::of($obj_list);
        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result->editColumn('seller_name',function($data) use ($current_context)
                            {
                                $seller_name = ""; 
                                
                                if($data->is_admin_uploaded==1)
                                {
                                  $seller_name = $data->seller_name." / Admin"; 
                                }
                                else
                                {
                                  $seller_name = $data->seller_name; 
                                }

                                return  $seller_name;
                            })
                            ->editColumn('build_thumbnail_btn',function($data) use ($current_context)
                            {
                                $build_thumbnail_btn = "";
                                if($data->type=="PHOTO")
                                {
                                  if($data->admin_enc_item_name!='')
                                  {
                                    $build_thumbnail_btn = '<img src="'.$this->admin_photos_public_img_path.$data->admin_enc_item_name.'" style="height:60px;width:90px">';
                                  }
                                  else
                                  {
                                    $build_thumbnail_btn = '<img src="'.$this->admin_photos_public_img_path.'/default.png" style="height:60px;width:90px">';
                                  }
                                }
                                else
                                {
                                  if($data->admin_enc_footage_image!='')
                                  {
                                    $build_thumbnail_btn = '<img src="'.$this->admin_footage_image_public_path.$data->admin_enc_footage_image.'" style="height:60px;width:90px">';
                                  }
                                  else
                                  {
                                    $build_thumbnail_btn = '<img src="'.$this->admin_footage_image_public_path.'/default.png" style="height:60px;width:90px">';
                                  }
                                }

                                return  $build_thumbnail_btn;
                            })            
                            ->editColumn('build_status_btn',function($data) use ($current_context)
                            {
                                if($data->is_approved==0)
                                {
                                  $modal = '#modal-2';
                                  $view_href =  $this->module_url_path.'/approve/'.base64_encode($data->id);
                                  $build_status_btn = '<a href="'.$view_href.'" class="btn btn-sm btn-success show-tooltip" onclick="return confirm_action(this,event,\'Do you really want to approve this record?\')" title="Approve" ><i class="fa fa-check"></i></a>
                                    <a data-id="'.base64_encode($data->id).'" title="Disapprove" href="'.$modal.'" data-toggle="modal" class="btn btn-sm btn-danger show-tooltip" title="Disapprove" onclick="clear_fields(this)";><i class="fa fa-times"></i></a>';
                                }
                                else if($data->is_approved=='1')
                                {
                                  $build_status_btn = '<a href="javascript:void(0)" class="btn btn-sm btn-success show-tooltip" title="Approved" ><i class="fa fa-check"></i></a>';
                                }
                                else
                                {
                                  $build_status_btn = '<a href="javascript:void(0)" class="btn btn-sm btn-danger show-tooltip" title="Disapproved" ><i class="fa fa-times"></i></a>';
                                }

                                return  $build_status_btn;
                            })    
                            ->editColumn('build_action_btn',function($data) use ($current_context)
                            {
                                $build_edit_action   = '';
                                $build_delete_action = '';
                                $arr_check_boooked_data = [];
                                $obj_check_boooked_data = false;

                                $obj_check_boooked_data = $this->MediaListingDetailModel->where('list_id',$data->id)->with('order_details')->get();

                                if(isset($obj_check_boooked_data) && count($obj_check_boooked_data)>0)
                                {
                                  $arr_check_boooked_data =  $obj_check_boooked_data->toArray();
                                  foreach ($arr_check_boooked_data as $key => $value)
                                  {
                                      if(isset($value['order_details']) && count($value['order_details'])>0)
                                      {
                                        $build_edit_action   = '';
                                        $build_delete_action = '';
                                      }
                                      else
                                      {
                                        $edit_href =  $this->module_url_path.'/edit/'.base64_encode($data->id);
                                        $build_edit_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$edit_href.'" title="Edit"><i class="fa fa-edit" ></i></a>';

                                        $delete_href =  $this->module_url_path.'/delete/'.base64_encode($data->id);
                                        $build_delete_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$delete_href.'" title="Delete" onclick="return confirm_action(this,event,\'Do you really want to delete this record?\')"><i class="fa fa-trash" ></i></a>';
                                      }
                                  }
                                }

                                $build_commission_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" data-id="'.base64_encode($data->id).'" href="#modal-4" data-val="'.$data->commission.'" data-toggle="modal" title="Commission" onclick="set_commission(this);"><i class="fa fa-dollar" ></i></a>';

                                if($data->type=='PHOTO')
                                {
                                  $build_media_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" data-id="'.base64_encode($data->id).'" href="#modal-3" data-type="'.strtolower($data->type).'" data-image="'.$data->admin_enc_item_name.'" data-toggle="modal" title="Upload Media" onclick="clear_all_fields(this);"><i class="fa fa-image" ></i></a>';                                
                                }
                                else
                                {
                                  $build_media_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" data-id="'.base64_encode($data->id).'" href="#modal-5" data-type="'.strtolower($data->type).'" data-footage="'.$data->admin_enc_footage_image.'" data-image="'.$data->admin_enc_item_name.'" data-toggle="modal" title="Upload Media" onclick="clear_all_fields(this);"><i class="fa fa-image" ></i></a>';                                
                                }

                                $clone_href =  $this->module_url_path.'/clone/'.base64_encode($data->id);
                                $build_clone_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$clone_href.'" title="Clone"><i class="fa fa-copy" ></i></a>';                                

                                $view_href =  $this->module_url_path.'/view/'.base64_encode($data->id);
                                $build_view_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$view_href.'" title="View"><i class="fa fa-eye" ></i></a>';



                                return  $build_commission_action." ".$build_media_action." ".$build_view_action." ".$build_clone_action." ".$build_edit_action." ".$build_delete_action;
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        return response()->json($build_result);
    }

    function get_media_details(Request $request)
    {     
        $column = '';
        $role = 'buyer';        
        if($request->input('role') == 'seller')
        {
            $role = 'seller';
            $page_title   = "Manage seller";
        }

        if ($request->input('order')[0]['column'] == 1) 
        {
          $column = "title";
        } 
        if ($request->input('order')[0]['column'] == 2) 
        {
          $column = "type";
        } 
        if ($request->input('order')[0]['column'] == 3) 
        {
          $column = "seller_name";
        } 
        if ($request->input('order')[0]['column'] == 5) 
        {
          $column = "commission";
        } 

        $order = strtoupper($request->input('order')[0]['dir']);

        $user_details             = $this->UserModel->getTable();
        $prefixed_user_details    = DB::getTablePrefix().$this->UserModel->getTable();

        $master_details              = $this->MediaListingMasterModel->getTable();
        $prefixed_master_details     = DB::getTablePrefix().$this->MediaListingMasterModel->getTable();

        $lisitng_details             = $this->MediaListingDetailModel->getTable();
        $prefixed_lisitng_details    = DB::getTablePrefix().$this->MediaListingDetailModel->getTable();

        $user_details                = $this->UserModel->getTable();
        $prefixed_user_details       = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_list = DB::table($master_details)
                                ->select(DB::raw($prefixed_master_details.".id as id,".
                                                "UCASE(".$prefixed_master_details.".type) as type, ".
                                                 $prefixed_master_details.".seller_id as seller_id, ".
                                                 $prefixed_master_details.".title as title, ".
                                                 $prefixed_master_details.".is_approved as is_approved, ".
                                                 $prefixed_master_details.".is_admin_uploaded as is_admin_uploaded, ".
                                                 $prefixed_master_details.".commission as commission, ".
                                                 $prefixed_master_details.".admin_enc_item_name as admin_enc_item_name, ".
                                                 $prefixed_master_details.".admin_enc_footage_image as admin_enc_footage_image, ".                                                 
                                                 "CONCAT(".$prefixed_user_details.".first_name,' ',"
                                                          .$prefixed_user_details.".last_name) as seller_name "
                                                 ))
                                ->join($user_details,$master_details.'.seller_id','=',$user_details.'.id');
                                /*->orderBy($master_details.'.updated_at','DESC')*/

        /* ---------------- Filtering Logic ----------------------------------*/                    
        if(($order =='ASC' || $order =='') && $column=='')
        {
          $obj_list  = $obj_list->orderBy($master_details.'.updated_at','DESC');
        }
        if( $order !='' && $column!='' )
        {
          $obj_list = $obj_list->orderBy($column,$order);
        }

        $arr_search_column = $request->input('column_filter');
        
        if(isset($arr_search_column['q_title']) && $arr_search_column['q_title']!="")
        {
            $search_term      = $arr_search_column['q_title'];
            $obj_list = $obj_list->having('title','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_type']) && $arr_search_column['q_type']!="")
        {
            $search_term      = $arr_search_column['q_type'];
            $obj_list = $obj_list->having('type','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_name']) && $arr_search_column['q_name']!="")
        {
            $search_term      = $arr_search_column['q_name'];
            $obj_list = $obj_list->having('seller_name','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_commission']) && $arr_search_column['q_commission']!="")
        {
            $search_term  = $arr_search_column['q_commission'];
            $obj_list = $obj_list->having('commission','LIKE', '%'.$search_term.'%');
        }

        return $obj_list;
    }


    public function approve($enc_id)
    {
    	$status 	            = false;
      $arr_data             = [];
    	$check_upload_status  = false;
      $seller_id            = '';
        
  		if($enc_id!='')
  		{
  			$id = base64_decode($enc_id);

        $check_upload_status = $this->MediaListingMasterModel->where('id',$id)->with('seller_details')->first();
        if($check_upload_status!=false)
        {
          if($check_upload_status['type']=='photo')
          {
            if($check_upload_status['admin_enc_item_name']=='')
            {
              Flash::error('Please upload same media to the front before approving');
              return redirect()->back(); 
            }
          }
          else
          {
            if($check_upload_status['admin_enc_item_name']=='' || $check_upload_status['admin_enc_footage_image']=='')
            {
              Flash::error('Please upload same media to the front before approving');
              return redirect()->back(); 
            }
          }
          
            $seller_id =  $check_upload_status['seller_details']['id'];
            $type      =  ucwords($check_upload_status['type']);
            $title     =  ucwords($check_upload_status['title']);
            $arr_data  = ['is_approved'=>1,'rejection_note'=>''];
          
            $status = $this->MediaListingMasterModel->where('id',$id)->update($arr_data);

            if($status)
            {
                // Seller notification
                $arr_notification_data_seller                 = [];
                $send_notification_seller                     = false;
                $arr_notification_data_seller['from_user_id'] = 1;
                $arr_notification_data_seller['to_user_id']   = $seller_id;
                $arr_notification_data_seller['message']      = ' Hello! Your request for '.$title.' '.$type.' has been approved.'.'<a href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($id).'">View</a>';

                $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);

                // Admin notification
                $arr_notification_data_admin                 = [];
                $send_notification_admin                     = false;
                $arr_notification_data_admin['from_user_id'] = 0;
                $arr_notification_data_admin['to_user_id']   = 1;
                $arr_notification_data_admin['message']      = ' Hello! You have approved '.ucwords($title).' '.ucwords($type).' successfully.'.'<a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($id).'">View</a>';
              
                $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

                Flash::success($this->module_title. ' Approved Successfully');
            }
            else
            {
                Flash::error('Problem Occured While '.$this->module_title.' Approval ');
            }
  		      return redirect()->back();
        }
        else
        {
            Flash::error('Problem Occured While '.$this->module_title.' Approval ');
            return redirect()->back();
        }
  		}
  		else
  		{
          Flash::error('Problem Occured While '.$this->module_title.' Approval ');
  		    return redirect()->back();
  		}
    }

    public function disapprove(Request $request)
    {
    	$status 	      = $get_seller_details = $arr_mail_data = false;
    	$arr_data     	= $arr_seller_details = [];
    	$arr_mail_data  = [];
    	$id  = $rejection_note = $seller_id = $seller_name = '';

  		if($request->has('note') && $request->has('enc_id'))
  		{
  			$id = base64_decode($request->input('enc_id'));
  			$rejection_note = $request->input('note');
  			
  			$arr_data = ['is_approved'=>2, 'rejection_note'=>$rejection_note];
      		
      		$status = $this->MediaListingMasterModel->where('id',$id)->update($arr_data);
  			
      		/*----------- Send Email notification to Seller ------------------------*/
      		
      		$get_seller_details = $this->MediaListingMasterModel->where('id',$id)->with('seller_details')->first();
      		
      		if($get_seller_details!=false)
      		{
      			$arr_seller_details = $get_seller_details->toArray();	
            $seller_id   =  $arr_seller_details['seller_details']['id'];
      			$seller_name =  $arr_seller_details['seller_details']['first_name'].' '.$arr_seller_details['seller_details']['last_name'];
      		}
      		
      		$arr_mail_data = $this->built_rejection_mail_data($rejection_note, $arr_seller_details['seller_details']);
      		$email_status  = $this->EmailService->send_mail($arr_mail_data);

  	        if($status)
  	        {
          		// Seller notification
        				$arr_notification_data_seller 				        = [];
        				$send_notification_seller     	    		      = false;
  	            $arr_notification_data_seller['from_user_id'] = 1;
  	            $arr_notification_data_seller['to_user_id']   = $seller_id;
  	            $arr_notification_data_seller['message']      = ' Hello! Your request for My Photos and Footage has been rejected due to : '.$rejection_note.' <a target="_new" href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($id).'">View</a>';

  	            $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);
  	            
                // Admin notification
                $arr_notification_data_admin                 = [];
                $send_notification_admin                     = false;
                $arr_notification_data_admin['from_user_id'] = $seller_id;
                $arr_notification_data_admin['to_user_id']   = 1;
                $arr_notification_data_admin['message']      = 'Hello! You have rejected '. ucwords($arr_seller_details['title']).' '.ucwords($arr_seller_details['type']).' of '.$seller_name.' <a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($id).'">View</a>';

                $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);                

  	            Flash::success($this->module_title. ' Disapproved Successfully');
  	        }
  	        else
  	        {
  	            Flash::error('Problem Occured While '.$this->module_title.' Disapproved ');
  	        }

  		    return redirect()->back();
  		}
  		else
  		{
              Flash::error('Problem Occured While '.$this->module_title.' Disapproved ');
  		    return redirect()->back();
  		}
    }

  /*---------------------------------------- Rejection note mail data ----------------------------------------------*/
	 public function built_rejection_mail_data($note, $arr_user)
	 {
	    if($arr_user)
	    {
	        $arr_built_content = ['NAME'       		 => $arr_user['first_name'],
	                              'NOTE'     		 => $note];


	        $arr_mail_data                      = [];
	        $arr_mail_data['email_template_id'] = '4';
	        $arr_mail_data['arr_built_content'] = $arr_built_content;
	        $arr_mail_data['user']              = $arr_user;

	        return $arr_mail_data;
	    }
    return FALSE;
  	}    

  /*---------------------------------------- Delete Media ----------------------------------------------*/
    public function delete($enc_id)
    {
    	$status 		    = false;
    	$master_status  = false;
      $obj_data       = false;
      $arr_mail_data  = false;
      $email_status   = false;
      $list_id        = $seller_id = $seller_name = '';
      $arr_data       = [];
    	$obj_delete_old_photos_and_footages = [];

    	if($enc_id!='')
    	{
    		$list_id   = base64_decode($enc_id);

    		/*---------------------------------------- Delete and Unlink images and data ----------------------------------------------*/
	        $obj_data = $this->MediaListingMasterModel->where('id',$list_id)->with('seller_details')->first();
	        
	        if($obj_data!=false)
	        {
  	        	$obj_data  = $obj_data->toArray();

              $seller_id   =  $obj_data['seller_details']['id'];
              $seller_name =  $obj_data['seller_details']['first_name'].' '.$obj_data['seller_details']['last_name'];
              $arr_data['type']  =  ucwords($obj_data['type']);
              $arr_data['title'] =  ucwords($obj_data['title']);

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
          
          /*---------------------------------------- Email Notiofication ----------------------------------------------*/
          if($seller_id!=1)
          {
            $arr_mail_data = $this->built_media_delete_mail_data($arr_data, $obj_data['seller_details']);
            $email_status  = $this->EmailService->send_mail($arr_mail_data);
          }

            // Delete From the cart
            $delete_form_cart = $this->CartModel->where('master_id',$list_id)->delete();

  	    		$master_status = $this->MediaListingMasterModel->where('id',$list_id)->delete();

  	    		if($master_status!=false)
  	    		{
  	    			$status = $this->MediaListingDetailModel->where('list_id',$list_id)->delete();
  	    			if($status!=false)
  	    			{
                if($seller_id!=1)
                {
                  // Seller notification
                  $arr_notification_data_seller                 = [];
                  $send_notification_seller                     = false;
                  $arr_notification_data_seller['from_user_id'] = 1;
                  $arr_notification_data_seller['to_user_id']   = $seller_id;
                  $arr_notification_data_seller['message']      = 'Hello! Your '.$arr_data['title'].' '.$arr_data['type'].' has been deleted by admin.';

                  $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);

                  // Admin notification
                  $arr_notification_data_admin                 = [];
                  $send_notification_admin                     = false;
                  $arr_notification_data_admin['from_user_id'] = $seller_id;
                  $arr_notification_data_admin['to_user_id']   = 1;
                  $arr_notification_data_admin['message']      = 'Hello! You have deleted '.$arr_data['title'].' '.$arr_data['type'].' of '.$seller_name;

                  $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);
                }

  			        	Flash::success(ucwords($obj_data['type']).' has been deleted successfully.');
  				    	  return redirect()->back();	        		
  	    			}
  	    			else
  			    	{
  				    	Flash::error('Error occured while deleting '.$obj_data['type'].'.');
  				    	return redirect()->back();	        		
  			    	}
  	    		}
	        }
    	}
    	else
    	{
	    	Flash::error('Error occured while deleting '.$obj_data['type'].'.');
	    	return redirect()->back();	        		
    	}
    }

  /*---------------------------------------- Rejection note mail data ----------------------------------------------*/
   public function built_media_delete_mail_data($arr_data, $arr_user)
   {
      if($arr_user && $arr_data)
      {
          $arr_built_content = ['NAME'           => $arr_user['first_name'],
                                'TYPE'           => $arr_data['type'],
                                'TITLE'          => $arr_data['title']
                                ];

          $arr_mail_data                      = [];
          $arr_mail_data['email_template_id'] = '5';
          $arr_mail_data['arr_built_content'] = $arr_built_content;
          $arr_mail_data['user']              = $arr_user;

          return $arr_mail_data;
      }
     return FALSE;
    }


    public function create()
    {
    	$obj_arr_data 	= false;
    	$arr_data     	= [];
    	$obj_seller     = [];
    	$arr_seller     = [];
        
		  $obj_arr_data = $this->MediaListingMasterModel->with(['seller_details'=>function($query){
															$query->select('id','first_name','last_name')->where('is_active',1);
														}])
													  ->orderBy('id','DESC')
													  ->get();

   		if($obj_arr_data!=false)
   		{
            $arr_pagination   = $obj_arr_data;
   		     	$arr_data         = $obj_arr_data->toArray();
   		}
		
		  $obj_seller = $this->UserModel->where('role','seller')
                									  ->where('is_block',0)
                									  ->select('id','first_name','last_name','email')
                									  ->orderBy('first_name','ASC')
                									  ->get();													  
   		
		  if($obj_seller!=false)
   		{
   			$arr_seller   = $obj_seller->toArray();
   		}

    	$obj_resolution = $this->ResolutionModel->orderBy('size','ASC')->get();
    	if($obj_resolution!=false)
    	{
			   $arr_resolution = $obj_resolution->toArray();    		
    	}

    	$obj_duration = $this->DurationModel->orderBy('value','ASC')->get();
    	if($obj_duration!=false)
    	{
			   $arr_duration = $obj_duration->toArray();    		
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

        $this->arr_view_data['photos_public_img_path']   = $this->photos_public_img_path;
        $this->arr_view_data['footage_public_img_path']  = $this->footage_public_img_path;
      	$this->arr_view_data['arr_data'] 				         = $arr_data;
      	$this->arr_view_data['arr_resolution']  		     = $arr_resolution;
      	$this->arr_view_data['arr_duration']    	 	     = $arr_duration;
      	$this->arr_view_data['arr_ratio']       		     = $arr_ratio;
      	$this->arr_view_data['arr_fps']         		     = $arr_fps;
      	$this->arr_view_data['arr_footage_formats']   	 = $arr_footage_formats;
      	$this->arr_view_data['arr_photo_formats']   	   = $arr_photo_formats;
      	$this->arr_view_data['arr_orientation']     	   = $arr_orientation;
      	$this->arr_view_data['arr_seller']     			     = $arr_seller;
        $this->arr_view_data['page_title']               = "Add ".str_plural($this->module_title);
        $this->arr_view_data['module_title']             = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']          = $this->module_url_path;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

   	public function store(Request $request)
    {
    	//dd($request->all());
    	$arr_rules 			    = [];
	    $arr_data           = [];
	    $arr_list_data	    = [];
    	$insert_master_list = [];
      $arr_listing_details= [];
    	$arr_commission     = [];
    	$seller_id 			    = "";
    	$lastInsertId		    = $title = $media_slug = '';
    	$check_if_exists    = $commission = 0;

    	  $arr_rules['type']           	  = "required";
        $arr_rules['title']          	  = "required|min:3|max:255"; 
        $arr_rules['keywords']          = "required|min:3|max:255";
        $arr_rules['description']       = "required|min:10|max:1000";
		    $arr_rules['price']          	  = "required"; 

        if($request->has('type') && $request->input('type')=='photo')
        {
	        $arr_rules['photo_format']        = "required";
	        $arr_rules['orientation']         = "required";        	
	        $arr_rules['arr_photo']           = "required";        	
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
	        $arr_rules['fx']        	    = "required";
	        $arr_rules['footage_format']  = "required";        	
	        $arr_rules['resolution']      = "required";        	
	        $arr_rules['fps']             = "required";        	
	        $arr_rules['arr_footage']     = "required";        	
        }

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }
       	else
       	{
       		if($request->has('seller_id') && $request->input('seller_id')!="")
       		{
       			$arr_user  = [];

       			$seller_id = $request->input('seller_id');
       			
       			$arr_user  = $this->UserModel->where('id',$seller_id)
       										->select('id','first_name','last_name')
       										->first();

       			if(isset($arr_user) && count($arr_user)>0)
       			{
       				$arr_user = $arr_user->toArray();
       			}

            $arr_commission = $this->CommissionModel->first();

            if(isset($arr_commission) && count($arr_commission)>0)
            {
              $arr_commission = $arr_commission->toArray();
              $commission     = $arr_commission['commission'];
            }
       		}
       		else
       		{
       			$seller_id  = 1;
            $commission = 0;
       		}

       		$title = $request->input('title');
       		$type  = $request->input('type');
       		
       		$check_if_exists = $this->MediaListingMasterModel->where('title',$title)->where('type',$type)->count();

       		if($check_if_exists>0)
       		{
       			Flash::error('This '.$request->input('type').' is already exists.');
		    	return redirect()->back();
       		}

          $media_slug = $request->input('title');
          $media_slug = trim($request->input('title'));
          $media_slug = str_replace(' ', '_', $media_slug);
          $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
          $media_slug = $media_slug.rand(10,100);          

          $arr_data['slug']              = $media_slug ;
  		    $arr_data['type']  	           = $request->input('type') ;
  		    $arr_data['seller_id']  	     = $seller_id ;
  		    $arr_data['title']  	         = $request->input('title') ;
  		    $arr_data['keywords']  	       = strtolower($request->input('keywords')) ;
  		    $arr_data['description']  	   = $request->input('description') ;
  		    $arr_data['is_approved']  	   = 0 ;
          $arr_data['is_admin_uploaded'] = 1 ;
          $arr_data['commission']        = $commission ;
          
	        if($request->has('type') && $request->input('type')=='photo')
	        {
  		    	$arr_list_data['price']  	       = $request->input('price') ;
  		    	$arr_list_data['photo_format']   = $request->input('photo_format') ;
  		    	$arr_list_data['orientation']  	 = $request->input('orientation') ;
  		    	$arr_list_data['arr_photo']  	   = $request->file('arr_photo') ;
	        }

	        if($request->has('type') && $request->input('type')=='footage')
	        {
  		    	$arr_data['ratio']  	           = $request->input('ratio') ;
  		    	$arr_data['duration']  	         = $request->input('duration') ;
  		    	$arr_data['alpha_channel']  	   = $request->input('alpha_channel') ;
  		    	$arr_data['alpha_matte']  	     = $request->input('alpha_matte') ;
  		    	$arr_data['media_release']  	   = $request->input('media_release') ;
  		    	$arr_data['looping']  	         = $request->input('looping') ;
  		    	$arr_data['model_release']  	   = $request->input('model_release') ;
  		    	$arr_data['liscense_type']  	   = $request->input('liscense_type') ;
  		    	$arr_data['fx']  	         	     = $request->input('fx') ;
  		    	
  		    	$arr_list_data['price']  	       = $request->input('price') ;
  		    	$arr_list_data['footage_format'] = $request->input('footage_format') ;
  		    	$arr_list_data['resolution']  	 = $request->input('resolution') ;
  		    	$arr_list_data['fps']  	         = $request->input('fps') ;
  		    	$arr_list_data['arr_footage']  	 = $request->file('arr_footage') ;
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
                        $arr_listing_details['list_id'] 	  = $lastInsertId;  
                        $arr_listing_details['enc_item_name'] = $fileName;  
                        $arr_listing_details['item_name']     = $original_file_name;

                      	$arr_listing_details['price'] 		= $arr_list_data['price'][$key];  
	            					$arr_listing_details['format'] 	    = $arr_list_data['photo_format'][$key];  
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
                        $arr_listing_details['list_id'] 	  = $lastInsertId;  
                        $arr_listing_details['enc_item_name'] = $fileName;  
                        $arr_listing_details['item_name']     = $original_file_name;

                      	$arr_listing_details['price'] 		  = $arr_list_data['price'][$key];  
              					$arr_listing_details['format'] 	      = $arr_list_data['footage_format'][$key];  
              					$arr_listing_details['resolution']    = $arr_list_data['resolution'][$key];
              					$arr_listing_details['fps'] 		  = $arr_list_data['fps'][$key];
              					
              					$insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
                    }
		        			}
		        		}
		        	}
	        	}
	        	
	        	if($insert_list_details)
	        	{
		            if($request->has('seller_id') && $request->input('seller_id')!="")
		       		{
		       			// Seller notification
      						$arr_notification_data_seller 				        = [];
      						$send_notification_seller     	    		      = false;
			            $arr_notification_data_seller['from_user_id'] = 1;
			            $arr_notification_data_seller['to_user_id']   = $seller_id;
			            $arr_notification_data_seller['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been uploaded successfully by admin.'.' <a href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';


/*$arr_notification_data_seller['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been uploaded successfully by admin.';
*/
                  

		        		// Admin notification
      						$arr_notification_data_admin 				         = [];
      						$send_notification_admin     	    		       = false;
			            $arr_notification_data_admin['from_user_id'] = 0;
			            $arr_notification_data_admin['to_user_id']   = 1;
			            $arr_notification_data_admin['message']      = 'Hello! You have uploaded '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' successfully on behalf of '.$arr_user['first_name'].' '.$arr_user['last_name'].'. <a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';
		            
		            	$send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);
		       		}
		       		else
		       		{
		        		// Admin notification
      						$arr_notification_data_admin 				 = [];
      						$send_notification_admin     	    		 = false;
			            $arr_notification_data_admin['from_user_id'] = 0;
			            $arr_notification_data_admin['to_user_id']   = 1;
			           /* $arr_notification_data_admin['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been uploaded successfully.';*/
                   $arr_notification_data_admin['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been uploaded successfully.'.'<a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';

		       		}

		            $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

		        	Flash::success('Your '.ucwords($arr_data['type'].' has been uploaded successfully.'));
    				  return redirect(url('/admin/').'/'.$this->module_url_slug);
	        	}
	        	else
	        	{
  			    	Flash::error('Error occured while storing '.ucwords($arr_data['type']));
  			    	return redirect()->back();	        		
	        	}
	        }
	    }
    }

    public function edit($enc_id)
    {
    	$obj_resolution 		    = false;
    	$obj_duration 			    = false;
    	$obj_ratio 				      = false;
    	$obj_fps 				        = false;
    	$obj_photo_formats      = false;
    	$obj_footage_formats    = false;
    	$obj_orientation        = false;
    	$obj_arr_data           = false;
    	$arr_resolution 		    = [];
    	$arr_duration 			    = [];
    	$arr_ratio 			 	      = [];
    	$arr_fps 				        = [];
    	$arr_photo_formats 		  = [];
    	$arr_footage_formats 	  = [];
    	$arr_orientation 		    = [];
    	$arr_data 			        = [];
    	$list_id 				        = '';

    	if($enc_id!='')
    	{
    		$list_id = base64_decode($enc_id);
    		$obj_arr_data = $this->MediaListingMasterModel->with(['seller_details'=>function($query){
                              $query->select('id','first_name','last_name','email')->where('is_active',1);
                              }])->where('id',$list_id)
    													  ->with('listing_details')
    													  ->first();
    		if($obj_arr_data!=false)
    		{
    			$arr_data = $obj_arr_data->toArray();	
          //dd($arr_data);
    		}

	    	$obj_resolution = $this->ResolutionModel->orderBy('size','ASC')->get();
	    	if($obj_resolution!=false)
	    	{
				$arr_resolution = $obj_resolution->toArray();    		
	    	}

	    	$obj_duration = $this->DurationModel->orderBy('value','ASC')->get();
	    	if($obj_duration!=false)
	    	{
				$arr_duration = $obj_duration->toArray();    		
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

        $this->arr_view_data['photos_public_img_path']   = $this->photos_public_img_path;
        $this->arr_view_data['footage_public_img_path']  = $this->footage_public_img_path;
      	$this->arr_view_data['arr_data'] 				         = $arr_data;
      	$this->arr_view_data['arr_resolution']  		     = $arr_resolution;
      	$this->arr_view_data['arr_duration']    		     = $arr_duration;
      	$this->arr_view_data['arr_ratio']       		     = $arr_ratio;
      	$this->arr_view_data['arr_fps']         		     = $arr_fps;
      	$this->arr_view_data['arr_footage_formats']   	 = $arr_footage_formats;
      	$this->arr_view_data['arr_photo_formats']   	   = $arr_photo_formats;
      	$this->arr_view_data['arr_orientation']     	   = $arr_orientation;
        $this->arr_view_data['page_title']               = "Edit ".str_plural($this->module_title);
        $this->arr_view_data['module_title']             = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']          = $this->module_url_path;

        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }

 	  public function update(Request $request,$enc_id)
    {
    	$arr_rules 			     = [];
    	$user      			     = [];
    	$seller_id 			     = [];
	    $arr_data            = [];
	    $arr_list_data	     = [];
    	$insert_master_list  = [];
    	$arr_listing_details = [];
    	$get_master_data     = [];
    	$oldimage            = [];
    	$oldimagename        = [];
    	$oldfootage          = [];
    	$oldfootagename      = [];
    	$listing_id          = [];
    	$lastInsertId		     = $title = $current_id = $media_slug = '';
    	$check_if_exists     = 0;
    	$insert_list_details = false;
    	$update_list_details = false;
    	$list_id             = base64_decode($enc_id);

    	  $arr_rules['type']           	  = "required";
        $arr_rules['title']          	  = "required|min:3|max:255"; 
        $arr_rules['keywords']            = "required|min:3|max:255";
        $arr_rules['description']         = "required|min:10|max:1000";
		    $arr_rules['price']          	  = "required"; 

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
	        $arr_rules['fx']        	  = "required";
	        $arr_rules['footage_format']  = "required";        	
	        $arr_rules['resolution']      = "required";        	
	        $arr_rules['fps']             = "required";        	
	        //$arr_rules['arr_footage']     = "required";        	
        }

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        //Check User
        $user = Sentinel::check();

       	if(isset($user) && $user==true && $enc_id!='')
       	{
       		$title = $request->input('title');
       		$type = $request->input('type');

       		$check_if_exists = $this->MediaListingMasterModel->where('title',$title)->where('id','<>',$list_id)->where('type',$type)->count();

       		if($check_if_exists>0)
       		{
       			Flash::error('This '.$request->input('type').' is already exists.');
		    	return redirect()->back();
       		}

       		$get_master_data = $this->MediaListingMasterModel->where('id',$list_id)->with('seller_details')->first();
       		if(isset($get_master_data) && count($get_master_data)>0)
       		{
       			$get_master_data = $get_master_data->toArray();
       			$seller_id = $get_master_data['seller_details']['id'];
       			$seller_name = $get_master_data['seller_details']['first_name'].' '.$get_master_data['seller_details']['last_name'];
       		}

          $media_slug = $request->input('title');
          $media_slug = trim($request->input('title'));
          $media_slug = str_replace(' ', '_', $media_slug);
          $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
          $media_slug = $media_slug.rand(10,100);          

          $arr_data['slug']            = $media_slug ;
  		    $arr_data['type']  	         = $request->input('type') ;
  		    $arr_data['seller_id']  	   = $seller_id ;
  		    $arr_data['title']  	       = $request->input('title') ;
  		    $arr_data['keywords']  	     = strtolower($request->input('keywords')) ;
  		    $arr_data['description']  	 = $request->input('description') ;
  		    //$arr_data['is_approved']  	 = 1 ;
	        
	        if($request->has('listing_id'))
	        {
	        	$listing_id = $request->input('listing_id');
	        }

	        if($request->has('type') && $request->input('type')=='photo')
	        {
			    
		    	$arr_list_data['price']  	       = $request->input('price') ;
		    	$arr_list_data['photo_format']   = $request->input('photo_format') ;
		    	$arr_list_data['orientation']  	 = $request->input('orientation') ;
		    	$arr_list_data['arr_photo']  	   = $request->file('arr_photo') ;
		    	
		    	$oldimage 						 = $request->input('oldimage');
		    	$oldimagename 				 = $request->input('oldimagename');
	        }

	        if($request->has('type') && $request->input('type')=='footage')
	        {
  		    	$arr_data['ratio']  	         = $request->input('ratio') ;
  		    	$arr_data['duration']  	       = $request->input('duration') ;
  		    	$arr_data['alpha_channel']  	 = $request->input('alpha_channel') ;
  		    	$arr_data['alpha_matte']  	   = $request->input('alpha_matte') ;
  		    	$arr_data['media_release']  	 = $request->input('media_release') ;
  		    	$arr_data['looping']  	       = $request->input('looping') ;
  		    	$arr_data['model_release']  	 = $request->input('model_release') ;
  		    	$arr_data['liscense_type']  	 = $request->input('liscense_type') ;
  		    	$arr_data['fx']  	         	   = $request->input('fx') ;
  		    	
  		    	$arr_list_data['price']  	       = $request->input('price') ;
  		    	$arr_list_data['footage_format'] = $request->input('footage_format') ;
  		    	$arr_list_data['resolution']  	 = $request->input('resolution') ;
  		    	$arr_list_data['fps']  	         = $request->input('fps') ;
  		    	$arr_list_data['arr_footage']  	 = $request->file('arr_footage') ;

  		    	$oldfootage 					 = $request->input('oldfootage');
  		    	$oldfootagename 			 = $request->input('oldfootagename');
	        }

	        $insert_master_list = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data);
	        
	        if($insert_master_list)
	        {
	        	$lastInsertId = $list_id;
	        	/*-------------------------------------------- Deleting old values -------------------------------------------------*/

	        	$obj_delete_old_photos_and_footages = [];
	        	$obj_delete_old_photos_and_footages = $this->MediaListingDetailModel->where('list_id',$list_id)->get();

	        	if(isset($obj_delete_old_photos_and_footages) && count($obj_delete_old_photos_and_footages)>0)
	        	{
	        		$obj_delete_old_photos_and_footages = $obj_delete_old_photos_and_footages->toArray();

		        	foreach ($obj_delete_old_photos_and_footages as $key => $listing)
		        	{
		        		foreach ($listing_id as $listing_key => $current_id)
		        		{		        			
		        			if($arr_data['type']=='photo')
			        		{
			        			if($current_id==$listing['id'] && $arr_list_data['arr_photo'][$listing_key]!=null)
	        					{				        		
	                    		@unlink($this->photos_base_img_path.$listing['enc_item_name']);
			        			}
			        		}
			        		else if($arr_data['type']=='footage')
			        		{
			        			if($current_id==$listing['id'] && $arr_list_data['arr_footage'][$listing_key]!=null)
	        					{				        			
		                    	@unlink($this->footage_base_img_path.$listing['enc_item_name']);
		                }
			        		}	
		        		}
		        	}

	        		$delete_obj_from_media_lisitng_detail = $this->MediaListingDetailModel->where('list_id',$list_id)->delete();
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
							    
                      $arr_listing_details['list_id'] 	      = $lastInsertId;  
                    	$arr_listing_details['price'] 			  = $arr_list_data['price'][$key];  
      					      $arr_listing_details['format'] 	    	  = $arr_list_data['photo_format'][$key];  
      					      $arr_listing_details['orientation'] 	  = $arr_list_data['orientation'][$key];
            					
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
		                        
                      $arr_listing_details['list_id'] 	  = $lastInsertId;  
                    	$arr_listing_details['price'] 		  = $arr_list_data['price'][$key];  
            					$arr_listing_details['format'] 	    = $arr_list_data['footage_format'][$key];  
            					$arr_listing_details['resolution']  = $arr_list_data['resolution'][$key];
            					$arr_listing_details['fps'] 		    = $arr_list_data['fps'][$key];
            					
            					$insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
		        			}
		        		}
		        	}
	        	}
	        	
	        	if($insert_list_details)
	        	{

	        		  // Admin notification
      					$arr_notification_data_admin 				         = [];
      					$send_notification_admin     	    		       = false;
		            $arr_notification_data_admin['from_user_id'] = $seller_id;
		            $arr_notification_data_admin['to_user_id']   = 1;
		            $arr_notification_data_admin['message']      = 'Hello! You have Updated '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' of '.$seller_name.' <a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($list_id).'">View</a>';

		            $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

                // Seller notification
                $arr_notification_data_seller                 = [];
                $send_notification_seller                     = false;
                $arr_notification_data_seller['from_user_id'] = 1;
                $arr_notification_data_seller['to_user_id']   = $seller_id;
                $arr_notification_data_seller['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been updated by admin.'.'<a href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($list_id).'">View</a>';



                $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);

    	        	Flash::success(ucwords($arr_data['type']).' has been updated successfully.');
    		    	  return redirect()->back();	
	        	}
	        	else
	        	{
  			    	Flash::error('Error occured while updating '.ucwords($arr_data['type']));
  			    	return redirect()->back();	 		
	        	}
	        }
	    }
	    else
	    {
	    	Flash::error('Error occured while updating '.ucwords($arr_data['type']));
	    	return redirect()->back();
	    }
    }

    public function clone_photo_footage($enc_id)
    {
    	$obj_resolution 		     = false;
    	$obj_duration 			     = false;
    	$obj_ratio 				       = false;
    	$obj_fps 				         = false;
    	$obj_photo_formats       = false;
    	$obj_footage_formats     = false;
    	$obj_orientation         = false;
    	$obj_arr_data            = false;
    	$arr_resolution 		     = [];
    	$arr_duration 			     = [];
    	$arr_ratio 				       = [];
    	$arr_fps 				         = [];
    	$arr_photo_formats 		   = [];
    	$arr_footage_formats 	   = [];
    	$arr_orientation 		     = [];
    	$arr_data 			         = [];
      $list_id                 = '';
    	$enc_seller_id 				   = '';

    	if($enc_id!='')
    	{
    		$list_id = base64_decode($enc_id);
    		$obj_arr_data = $this->MediaListingMasterModel->where('id',$list_id)
    													  ->with('listing_details')
    													  ->first();
    		

        if($obj_arr_data!=false)
        {
          $arr_data = $obj_arr_data->toArray(); 
          $enc_seller_id = $this->CommonDataService->encrypt_value($arr_data['seller_id']);
    		}

	    	$obj_resolution = $this->ResolutionModel->orderBy('size','ASC')->get();
	    	if($obj_resolution!=false)
	    	{
				  $arr_resolution = $obj_resolution->toArray();    		
	    	}

	    	$obj_duration = $this->DurationModel->orderBy('value','ASC')->get();
	    	if($obj_duration!=false)
	    	{
				  $arr_duration = $obj_duration->toArray();    		
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

        $this->arr_view_data['photos_public_img_path']   = $this->photos_public_img_path;
        $this->arr_view_data['footage_public_img_path']  = $this->footage_public_img_path;
      	$this->arr_view_data['arr_data'] 				         = $arr_data;
      	$this->arr_view_data['arr_resolution']  		     = $arr_resolution;
      	$this->arr_view_data['arr_duration']    		     = $arr_duration;
      	$this->arr_view_data['arr_ratio']       		     = $arr_ratio;
      	$this->arr_view_data['arr_fps']         		     = $arr_fps;
      	$this->arr_view_data['arr_footage_formats']   	 = $arr_footage_formats;
      	$this->arr_view_data['arr_photo_formats']   	   = $arr_photo_formats;
      	$this->arr_view_data['arr_orientation']     	   = $arr_orientation;
        $this->arr_view_data['page_title']               = "Edit ".str_plural($this->module_title);
        $this->arr_view_data['module_title']             = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']          = $this->module_url_path;
        $this->arr_view_data['enc_seller_id']            = $enc_seller_id;

        return view($this->module_view_folder.'.clone',$this->arr_view_data);
    }    

 	public function store_clone(Request $request)
  {
    	//dd($request->all());
    	$arr_rules 			     = [];
    	$user      			     = [];
    	$seller_id 			     = [];
	    $arr_data            = [];
	    $arr_list_data	     = [];
    	$insert_master_list  = [];
    	$arr_listing_details = [];
    	$get_master_data     = [];
    	$oldimage            = [];
    	$oldimagename        = [];
    	$oldfootage          = [];
    	$oldfootagename      = [];
      $listing_id          = [];
    	$arr_commission      = [];
    	$lastInsertId		     = $title = $current_id = $media_slug = '';
    	$check_if_exists     = $commission = 0;
    	$insert_list_details = false;
    	$update_list_details = false;

    	$arr_rules['type']           	  = "required";
      $arr_rules['title']          	  = "required|min:3|max:255"; 
      $arr_rules['keywords']          = "required|min:3|max:255";
      $arr_rules['description']       = "required|min:10|max:1000";
	 	  $arr_rules['price']          	  = "required"; 

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
	        $arr_rules['fx']        	    = "required";
	        $arr_rules['footage_format']  = "required";        	
	        $arr_rules['resolution']      = "required";        	
	        $arr_rules['fps']             = "required";        	
	        //$arr_rules['arr_footage']     = "required";        	
        }

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        else
       	{
       		$title = $request->input('title');
       		$type  = $request->input('type');

       		$check_if_exists = $this->MediaListingMasterModel->where('title',$title)->where('type',$type)->count();

       		if($check_if_exists>0)
       		{
       			Flash::error('This title is already exists. Please change the '.$request->input('type').' title.');
		    	return redirect()->back();
       		}

          $arr_commission = $this->CommissionModel->first();

          if(isset($arr_commission) && count($arr_commission)>0)
          {
            $arr_commission = $arr_commission->toArray();
            $commission     = $arr_commission['commission'];
          }

          $media_slug = $request->input('title');
          $media_slug = trim($request->input('title'));
          $media_slug = str_replace(' ', '_', $media_slug);
          $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
          $media_slug = $media_slug.rand(10,100);          

          $arr_data['slug']               = $media_slug ;
		      $arr_data['type']               = $request->input('type') ;
		      $arr_data['seller_id']  	      = $this->CommonDataService->decrypt_value($request->input('enc_seller_id')) ;
		      $arr_data['title']  	          = $request->input('title') ;
		      $arr_data['keywords']  	        = strtolower($request->input('keywords')) ;
		      $arr_data['description']  	    = $request->input('description') ;
          $arr_data['is_approved']        = 0 ;
          $arr_data['is_admin_uploaded']  = 1 ;
		      $arr_data['commission']         = $commission ;

	        if($request->has('type') && $request->input('type')=='photo')
	        {
  		    	$arr_list_data['price']  	       = $request->input('price') ;
  		    	$arr_list_data['photo_format']   = $request->input('photo_format') ;
  		    	$arr_list_data['orientation']  	 = $request->input('orientation') ;
  		    	$arr_list_data['arr_photo']  	   = $request->file('arr_photo') ;
  		    	
  		    	$oldimage 						 = $request->input('oldimage');
  		    	$oldimagename 				 = $request->input('oldimagename');
	        }

	        if($request->has('type') && $request->input('type')=='footage')
	        {
  		    	$arr_data['ratio']  	         = $request->input('ratio') ;
  		    	$arr_data['duration']  	       = $request->input('duration') ;
  		    	$arr_data['alpha_channel']  	 = $request->input('alpha_channel') ;
  		    	$arr_data['alpha_matte']  	   = $request->input('alpha_matte') ;
  		    	$arr_data['media_release']  	 = $request->input('media_release') ;
  		    	$arr_data['looping']  	       = $request->input('looping') ;
  		    	$arr_data['model_release']  	 = $request->input('model_release') ;
  		    	$arr_data['liscense_type']  	 = $request->input('liscense_type') ;
  		    	$arr_data['fx']  	         	   = $request->input('fx') ;
  		    	
  		    	$arr_list_data['price']  	       = $request->input('price') ;
  		    	$arr_list_data['footage_format'] = $request->input('footage_format') ;
  		    	$arr_list_data['resolution']  	 = $request->input('resolution') ;
  		    	$arr_list_data['fps']  	         = $request->input('fps') ;
  		    	$arr_list_data['arr_footage']  	 = $request->file('arr_footage') ;

  		    	$oldfootage 					 = $request->input('oldfootage');
  		    	$oldfootagename 			 = $request->input('oldfootagename');
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
                      $filename           = rand(1111,9999);
                      $fileExt          = \File::extension($this->photos_base_img_path.$oldimage[$key]);
                      $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

                      if (\File::copy($this->photos_base_img_path.$oldimage[$key] , $this->photos_base_img_path.$fileName))
                      {
                                    $arr_listing_details['enc_item_name'] = $fileName;
                                    $arr_listing_details['item_name']     = $oldimagename[$key];
                      }
                    }
                  
                      $arr_listing_details['list_id']         = $lastInsertId;  
                      $arr_listing_details['price']           = $arr_list_data['price'][$key];  
                      $arr_listing_details['format']          = $arr_list_data['photo_format'][$key];  
                      $arr_listing_details['orientation']     = $arr_list_data['orientation'][$key];
                      
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
                if(count($arr_list_data['price'])>0 && count($arr_list_data['footage_format'])>0 && count($arr_list_data['resolution'])>0 && count($arr_list_data['fps'])>0 )
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
                      $filename           = rand(1111,9999);
                      $fileExt          = \File::extension($this->photos_base_img_path.$oldfootage[$key]);
                      $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

                      if (\File::copy($this->footage_base_img_path.$oldfootage[$key] , $this->footage_base_img_path.$fileName))
                      {
                                    $arr_listing_details['enc_item_name'] = $fileName;
                                    $arr_listing_details['item_name']     = $oldfootagename[$key];
                      }
                    }                           

                      $arr_listing_details['list_id']     = $lastInsertId;  
                      $arr_listing_details['price']       = $arr_list_data['price'][$key];  
                      $arr_listing_details['format']      = $arr_list_data['footage_format'][$key];  
                      $arr_listing_details['resolution']  = $arr_list_data['resolution'][$key];
                      $arr_listing_details['fps']         = $arr_list_data['fps'][$key];
                      
                      $insert_list_details = $this->MediaListingDetailModel->create($arr_listing_details);
                  }
                }
              }
            }
	        	
	        	if($insert_list_details)
	        	{
                // Seller notification
                $arr_notification_data_seller                 = [];
                $send_notification_seller                     = false;
                $arr_notification_data_seller['from_user_id'] = 1;
                $arr_notification_data_seller['to_user_id']   = $arr_data['seller_id'];
                $arr_notification_data_seller['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been uploaded successfully by admin.'.' <a href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';

              // dd($arr_notification_data_seller);

                 /* $arr_notification_data_seller['message']      = 'Hello! Your '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' has been uploaded successfully by admin.';*/

                $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);

	        		  // Admin notification
      					$arr_notification_data_admin 				         = [];
      					$send_notification_admin     	    		       = false;
		            $arr_notification_data_admin['from_user_id'] = 0;
		            $arr_notification_data_admin['to_user_id']   = 1;
		            $arr_notification_data_admin['message']      = 'Hello! You have Uploaded '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' Successfully.'.' <a href=" '.url('/').'/admin/photos_and_footage/view/'.base64_encode($lastInsertId).'">View</a>';

               // dd($arr_notification_data_admin);

              /*   $arr_notification_data_admin['message']      = 'Hello! You have Uploaded '.ucwords($arr_data['title']).' '.ucwords($arr_data['type']).' Successfully.';
*/
		            $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

    	        	Flash::success(ucwords($arr_data['type'].' has been Uploaded successfully.'));
    		    	  return redirect(url('/admin/').'/'.$this->module_url_slug);	
	        	}
	        	else
	        	{
  			    	Flash::error('Error occured while uploading '.ucwords($arr_data['type']));
  			    	return redirect()->back();	 		
	        	}
	        }
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
        $obj_arr_data = $this->MediaListingMasterModel->with(['seller_details'=>function($query){
                              $query->select('id','first_name','last_name','email')->where('is_active',1);
                              }])->where('id',$list_id)
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
      }

    	$this->arr_view_data['title'] 					        = "View Photos and Footage - ".config('app.project.name');
      $this->arr_view_data['module_title']            = "View Photos and Footage";
    	$this->arr_view_data['page_title'] 		          = "View Photos and Footage";
    	$this->arr_view_data['photos_public_img_path']  = $this->photos_public_img_path;
    	$this->arr_view_data['footage_public_img_path'] = $this->footage_public_img_path;
    	$this->arr_view_data['module_url_path'] 		    = $this->module_url_path;
      $this->arr_view_data['arr_duration']            = $arr_duration;
      $this->arr_view_data['arr_ratio']               = $arr_ratio;
      $this->arr_view_data['arr_data']                = $arr_data;
      $this->arr_view_data['enc_id']                  = $enc_id;

      return view($this->module_view_folder.'.view',$this->arr_view_data);
    }

    public function upload_replica(Request $request)
    {
      /*dd($request->all());*/
      $footageFileName='';
      $isUpload='';
      $isUploadFootage = false;
      $arr_rules['enc_list_id']   = "required";
      $arr_rules['type']          = "required"; 

      $validator = Validator::make($request->all(),$arr_rules);

      if($validator->fails())
      {
          Flash::error("Invalid request");          
          return redirect()->back()->withErrors($validator)->withInput();  
      }
      else
      {
        $arr_data = [];
        $file     = $list_id = $type = $footage_file = '';

        $list_id                    = base64_decode($request->input('enc_list_id'));
        $type                       = $request->input('type');
        $old_admin_enc_item_name    = $request->input('old_admin_enc_item_name');
        $old_admin_enc_footage_image    = $request->input('old_admin_enc_footage_image');

        if($type=='photo')
        {
          $file                     = $request->file('file');
          
        }
        else
        {
          $file                     = $request->file('footage_image');
          $footage_file             = $request->file('footage_file');
        }
        
        if($file!='' || $footage_file!='')
        {
          if($type=='photo')
          {
            // watermarked image file
            $arr_data['admin_enc_item_name'] = $old_admin_enc_item_name;

            $filename           = rand(1111,9999);
            $original_file_name = $file->getClientOriginalName();
            $fileExt            = $file-> getClientOriginalExtension();
            $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

            $watermark       = Image::make('uploads/watermark-filmunit.png');
            $watermarked_img = Image::make($file->getRealPath())->insert($watermark, 'center');
            $isUpload        = $watermarked_img->save($this->admin_photos_base_img_path.$fileName);

            if($old_admin_enc_item_name!='')
            {
              unlink($this->admin_photos_base_img_path.$old_admin_enc_item_name);
            }
            $arr_data['admin_enc_item_name'] = $fileName;
            $insert_list_details = $this->MediaListingMasterModel->where('id',$list_id)->where('type',$type)->update($arr_data);
          }
          else if($type=='footage')
          {
            $arr_data['admin_enc_footage_image'] = $old_admin_enc_footage_image;
            $arr_data['admin_enc_item_name']     = $old_admin_enc_item_name;
            
            if($file!='')
            {
              $filename           = rand(1111,9999);
              $original_file_name = $file->getClientOriginalName();
              $fileExt            = $file-> getClientOriginalExtension();
              $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;

              $watermark       = Image::make('uploads/watermark-filmunit.png');
              $watermarked_img = Image::make($file->getRealPath())->insert($watermark, 'center');
              $isUpload        = $watermarked_img->save($this->admin_footage_image_base_path.$fileName);

              if($old_admin_enc_footage_image!='')
              {
                @unlink($this->admin_footage_image_base_path.$old_admin_enc_footage_image);
              }
              $arr_data['admin_enc_footage_image'] = $fileName;
            }

            if($type=='footage' && $footage_file!='')
            {
              $original_file_name = $footage_file->getClientOriginalName();
              $fileExt            = $footage_file-> getClientOriginalExtension();
              $footageFileName    = sha1(uniqid().$original_file_name.uniqid()).'.'.$fileExt;

              $isUploadFootage = $footage_file->move($this->admin_footage_base_img_path, $footageFileName);
              if($old_admin_enc_item_name!='')
              {
                @unlink($this->admin_footage_base_img_path.$old_admin_enc_item_name);
              }
              $arr_data['admin_enc_item_name'] = $footageFileName;
            }
            $insert_list_details = $this->MediaListingMasterModel->where('id',$list_id)->where('type',$type)->update($arr_data);
          }

          if($insert_list_details)
          {  
            Flash::success(ucwords($type)." has been Uploaded successfully.");          
            return redirect()->back();     
          }
          else
          {
            Flash::error("Error occured while Uploading".ucwords($type));          
            return redirect()->back();      
          }
        }
        else
        {
          Flash::error("Nothing has been uploaded");          
          return redirect()->back();     
        }
      }
    }

    public function update_commission(Request $request)
    {
      $arr_rules['enc_list_id']   = "required";
      $arr_rules['commission']    = "required"; 

      $validator = Validator::make($request->all(),$arr_rules);

      if($validator->fails())
      {
          return redirect()->back()->withErrors($validator)->withInput();  
      }
      else
      {
        $arr_data = [];
        $commission  = $list_id = '';

        $list_id                    = base64_decode($request->input('enc_list_id'));
        $commission                 = $request->input('commission');

        if($commission!='')
        {
          $arr_data['commission'] = $commission;
          $update_commission = $this->MediaListingMasterModel->where('id',$list_id)->update($arr_data);
          
          if($update_commission)
          {

            Flash::success("Commission has been updated successfully.");          
            return redirect()->back();     
          }
          else
          {
            Flash::error("Error occured while Updating Commission");          
            return redirect()->back();      
          }

        }
        else
        {
          Flash::error("Nothing has been updated.");          
          return redirect()->back();
        }
      }
    }

}
