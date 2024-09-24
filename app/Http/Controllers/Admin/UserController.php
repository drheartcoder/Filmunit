<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Traits\MultiActionTrait;
use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\OrderDetailsModel;
use App\Models\MediaListingMasterModel;
use App\Models\UserRoleModel;   
use App\Models\RoleModel;

use App\Common\Services\RoleService;

use Flash;
use Validator;
use Sentinel;

use DB;
use Datatables;

class UserController extends Controller
{
    use MultiActionTrait;

    public function __construct(    
                                    UserModel $user,
                                    UserRoleModel $user_role_model,
                                    RoleModel $role_model,
                                    RoleService $role_service,
                                    OrderDetailsModel $order_details_models,
                                    MediaListingMasterModel $media_lsiting_master_model,
                                    TransactionModel $transaction
                                )
    {
        $user = Sentinel::createModel();

        $this->UserModel                    = $user;
        $this->TransactionModel             = $transaction;
        $this->UserRoleModel                = $user_role_model;
        $this->RoleModel                    = $role_model;
        $this->OrderDetailsModel            = $order_details_models;
        $this->RoleService                  = $role_service;
        $this->MediaListingMasterModel      = $media_lsiting_master_model;

        // $this->BaseModel                    = Sentinel::createModel();   // using sentinel for base model.
        $this->BaseModel                    = $this->UserModel;

        $this->user_profile_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
        $this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_images');

        $this->arr_view_data                = [];
        $this->module_url_path              = url(config('app.project.admin_panel_slug')."/users");
    
        $this->module_title                 = "User";
        $this->modyle_url_slug              = "users";

        $this->module_view_folder           = "admin.users";
        $this->theme_color                  = theme_color();
    }   

    public function index(Request $request)
    {   
        $page_title = "Manage ".str_plural($this->module_title);

        $role = 'buyer';
        
        if($request->input('u') == 'seller')
        {
            $role       = 'seller';
            $page_title = "Manage sellers";
        }

        $this->arr_view_data['role']            = $role;
        $this->arr_view_data['page_title']      = $page_title;
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;

        return view($this->module_view_folder.'.index', $this->arr_view_data);
    }

    function get_users_details(Request $request)
    {     
        $role = 'buyer';        
        if($request->input('role') == 'seller')
        {
            $role = 'seller';
            $page_title   = "Manage seller";
            $this->module_title  = "Seller";
        }

        $column = '';

        if ($request->input('order')[0]['column'] == 1) 
        {
          $column = "user_name";
        } 
        if ($request->input('order')[0]['column'] == 2) 
        {
          $column = "email";
        }
        if ($request->input('order')[0]['column'] == 3) 
        {
          $column = "created_at";
        } 

        $order = strtoupper($request->input('order')[0]['dir']);

        $user_details             = $this->BaseModel->getTable();
        $prefixed_user_details    = DB::getTablePrefix().$this->BaseModel->getTable();

        $user_role_table          = $this->UserRoleModel->getTable();
        $prefixed_user_role_table = DB::getTablePrefix().$this->UserRoleModel->getTable();

        $role_table               = $this->RoleModel->getTable();
        $prefixed_role_table      = DB::getTablePrefix().$this->RoleModel->getTable();

        $obj_user = DB::table($user_details)
                                ->select(DB::raw($prefixed_user_details.".id as id,".
                                                 $prefixed_user_details.".email as email, ".
                                                 $prefixed_user_details.".created_at as created_at, ".                                               
                                                 $prefixed_user_details.".is_active as is_active, ".
                                                 $prefixed_user_details.".is_block as is_block, ".
                                                 "CONCAT(".$prefixed_user_details.".first_name,' ',"
                                                          .$prefixed_user_details.".last_name) as user_name"
                                                 ))
                                ->join($user_role_table,$user_details.'.id','=',$user_role_table.'.user_id')
                                ->join($role_table, function ($join) use($role_table,$user_role_table,$role) {
                                    $join->on($role_table.'.id', ' = ',$user_role_table.'.role_id')
                                         ->where('slug','=',$role);
                                })
                                ->whereNull($user_details.'.deleted_at');
                                /*->orderBy($user_details.'.created_at','DESC');*/

        /* ---------------- Filtering Logic ----------------------------------*/                    
        $arr_search_column = $request->input('column_filter');
        
        if(isset($arr_search_column['q_name']) && $arr_search_column['q_name']!="")
        {
            $search_term      = $arr_search_column['q_name'];
            $obj_user = $obj_user->having('user_name','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_email']) && $arr_search_column['q_email']!="")
        {
            $search_term      = $arr_search_column['q_email'];
            $obj_user = $obj_user->where($user_details.'.email','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_created_at']) && $arr_search_column['q_created_at']!="")
        {
            $search_term      = $arr_search_column['q_created_at'];
            $search_term      = date('Y-m-d',strtotime($search_term));
            $obj_user = $obj_user->where($user_details.'.created_at','LIKE', '%'.$search_term.'%');
        }

        if(($order =='ASC' || $order =='') && $column=='')
        {
          $obj_user  = $obj_user->orderBy($user_details.'.created_at','DESC');
        }
        
        if( $order !='' && $column!='' )
        {
          $obj_user = $obj_user->orderBy($column,$order);
        }

        return $obj_user;
    }

    public function getBuyerOrderCount($id=false)
    {
        $order_details_get=0;
        if($id!='')
        {            
            $order_details_get = $this->TransactionModel->where('buyer_id',$id)->where('status','paid')->count();
        }
        return $order_details_get;
    }

    public function getSellerOrderCount($seller_id=false)
    {
        $arr_transaction   = [];
        if($seller_id!='')
        {            
            $arr_transaction = $this->OrderDetailsModel->where('seller_id',$seller_id)
                                                       ->groupBy('transaction_id')
                                                       ->get();
        }
        return count($arr_transaction);
    }

    public function getSellerMediaCount($seller_id=false)
    {
        $arr_media   = 0;
        if($seller_id!='')
        {            
            $arr_media = $this->MediaListingMasterModel->where('seller_id',$seller_id)
                                                       ->count();
        }
        return $arr_media;
    }

    public function get_records(Request $request)
    {        
        $arr_current_user_access =[];
        $arr_current_user_access = $request->user()->permissions;
       
        $obj_user        = $this->get_users_details($request);

        $role            =  $request->input('role');

        if($request->input('role') == 'seller')
        {
            $this->module_title  = "Seller";
        }

        $current_context = $this;

        $json_result     = Datatables::of($obj_user);

        $json_result     = $json_result->blacklist(['id']);
        
        
            $json_result     = $json_result->editColumn('enc_id',function($data) use ($current_context)
                            {
                                return base64_encode($data->id);
                            })
                            ->editColumn('BuyerOrderCount',function($data) use ($current_context,$role)
                            {
                                if($role != null && $role== 'buyer')
                                {
                                    if($data->id!='')
                                    {                                   
                                       $view_href =  url('/').'/admin/booking/buyer?buyerid='.base64_encode($data->id);
                                       $OerderCount = $this->getBuyerOrderCount($data->id);                      
                                       return '<a href="'.$view_href.'" title="View Buyer Orders List"><span class="badge badge-important">'.$OerderCount.'</span></a>';
                                    }
                                }
                                if($role!=null && $role=='seller')                                    
                                {
                                    if($data->id!='')
                                    {                                   
                                       $view_href =  url('/').'/admin/booking/seller?sellerid='.base64_encode($data->id);
                                       $OerderCount = $this->getSellerOrderCount($data->id);                      
                                       return '<a href="'.$view_href.'" title="View Buyer Orders List"><span class="badge badge-important">'.$OerderCount.'</span></a>';
                                    }   
                                }
                            })
                            ->editColumn('mediaCount',function($data) use ($current_context)
                            {
                                $media_count = 0;
                                $media_count = $this->MediaListingMasterModel->where('seller_id',$data->id)->count();

                                $view_href =  url('/').'/admin/photos_and_footage?seller='.$data->user_name;
                                return '<a href="'.$view_href.'" title="View Buyer Orders List"><span class="badge badge-important">'.$media_count.'</span></a>';
                            })
                            ->editColumn('created_at',function($data) use ($current_context)
                            {
                                return date('d-m-Y H:i A',strtotime($data->created_at));
                            })
                            ->editColumn('build_status_btn',function($data) use ($current_context)
                            {   
                                if($data->is_block != null && $data->is_block == "1")
                                {   
                                    $build_status_btn = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Lock" href="'.$this->module_url_path.'/unblock/'.base64_encode($data->id).'" 
                                    onclick="return confirm_action(this,event,\'Do you really want to activate this record?\')" ><i class="fa fa-lock"></i></a>';
                                }
                                elseif($data->is_block != null && $data->is_block == "0")
                                {
                                    $build_status_btn = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Unblocked" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to deactivate this record?\')" ><i class="fa fa-unlock"></i></a>';
                                }
                                return $build_status_btn;

                            });


        $json_result     = $json_result->editColumn('build_action_btn',function($data) use ($current_context)
                            {       
                                $view_href =  $this->module_url_path.'/view/'.base64_encode($data->id);
                                $build_view_action = '<a class="btn btn-warning btn-sm show-tooltip call_loader" href="'.$view_href.'" title="View"><i class="fa fa-eye" ></i></a>';


                            })    
                            ->editColumn('build_action_btn',function($data) use ($current_context,$role)
                            {
                                if($role != null && $role== 'buyer')
                                {       
                                    $edit_href =  $this->module_url_path.'/edit/'.base64_encode($data->id);
                                    $build_edit_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$edit_href.'" title="Edit"><i class="fa fa-edit" ></i></a>';

                                    $view_href =  $this->module_url_path.'/view/'.base64_encode($data->id);
                                    $build_view_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$view_href.'" title="View"><i class="fa fa-eye" ></i></a>';

                                    $delete_href =  $this->module_url_path.'/delete/'.base64_encode($data->id);
                                    $build_delete_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$delete_href.'" title="Delete" onclick="return confirm_action(this,event,\'Do you really want to delete this record?\')"><i class="fa fa-trash" ></i></a>';

                                    return  $build_view_action." ".$build_edit_action." ".$build_delete_action;
                                }

                                elseif($role != null && $role==  'seller')
                                {
                                    $edit_href =  $this->module_url_path.'/edit/'.base64_encode($data->id);
                                    $build_edit_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$edit_href.'" title="Edit"><i class="fa fa-edit" ></i></a>';

                                    $view_href =  $this->module_url_path.'/view/'.base64_encode($data->id);
                                    $build_view_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$view_href.'" title="View"><i class="fa fa-eye" ></i></a>';

                                    $delete_href =  $this->module_url_path.'/delete/'.base64_encode($data->id);
                                    $build_delete_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$delete_href.'" title="Delete" onclick="return confirm_action(this,event,\'Do you really want to delete this record?\')"><i class="fa fa-trash" ></i></a>';

                                    return  $build_view_action." ".$build_edit_action." ".$build_delete_action;  
                                }

                                
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        return response()->json($build_result);
    }

    public function view($enc_id)
    {   
        $id = base64_decode($enc_id);
        $arr_user = [];
        $page_title = "";

        $obj_user = $this->UserModel
                                ->where('id','=',$id)
                                ->whereHas('roles',function() {})
                                ->select('id','first_name','profile_image','last_name','email','contact_number','address','zipcode','country','city')
                                ->first();
        if($obj_user)
        {
            $arr_user = $obj_user->toArray();
        }   

        $is_seller = $this->RoleService->has_role($id, 'seller');
        $is_buyer  = $this->RoleService->has_role($id, 'buyer');

        if($is_seller == true) 
        {
            $page_title = 'View Seller Details';
            $module_url_path   = $this->module_url_path.'?u=Seller';
            $module_title  = "Manage Sellers";
        }

        if($is_buyer == true) 
        {
            $page_title = 'View Buyer Details';
            $module_url_path   = $this->module_url_path;
            $module_title  = "Manage ".str_plural($this->module_title);
        }



        $this->arr_view_data['page_title']      = $page_title;
        $this->arr_view_data['module_title']    = $module_title;
        $this->arr_view_data['module_url_path'] = $module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;
        $this->arr_view_data['arr_user']        = $arr_user;
        $this->arr_view_data['is_seller']       = $is_seller;
        $this->arr_view_data['is_buyer']        = $is_buyer;
        
        return view($this->module_view_folder.'.view', $this->arr_view_data);
    }

    public function edit($enc_id)
    {
        $arr_roles = [];
        $id = base64_decode($enc_id);

        $arr_roles = $this->RoleService->get_user_roles($id);
        
        if(isset($arr_roles) && count($arr_roles)>0)
        {
            $user_role = isset($arr_roles[0])?$arr_roles[0]:'';   
        
            $obj_user = $this->BaseModel->where('id','=',$id)
                                        ->first(['id','email','profile_image','first_name','last_name','country','state','city','address','zipcode','contact_number']);

            $arr_data = [];                                    
            
            if($obj_user)
            {
                $arr_data = $obj_user->toArray();
            }  

            if($user_role == 'seller')
            {
                $module_form_path = '';
                $this->module_title    = 'Seller';
                $this->module_url_path  =  url(config('app.project.admin_panel_slug')."/users?u=seller");                
            }
            
            $module_form_path =  url(config('app.project.admin_panel_slug')."/users");                

            $this->arr_view_data['page_title']                      = "Edit ".str_singular($this->module_title);
            $this->arr_view_data['module_title']                    = str_plural($this->module_title);
            $this->arr_view_data['module_url_path']                 = $this->module_url_path;
            $this->arr_view_data['module_form_path']                = $module_form_path;
            $this->arr_view_data['arr_data']                        = $arr_data;
            $this->arr_view_data['user_role']                       = $user_role;
            $this->arr_view_data['user_profile_public_img_path']    = $this->user_profile_public_img_path;
            $this->arr_view_data['theme_color']                     = $this->theme_color;
            
        } 
        return view($this->module_view_folder.'.edit', $this->arr_view_data);
    }

    public function update(Request $request)
    {
        $arr_rules  = [];
        $check_user = false;
        $role       = '';
        
        $arr_rules['user_id']            = "required";
        $arr_rules['first_name']         = "required";
        $arr_rules['last_name']          = "required";  
        $arr_rules['contact_number']     = "required";
        $arr_rules['country']            = "required"; 
        $arr_rules['address']            = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        /* Image Upload starts here */
        $is_new_file_uploaded = FALSE;

         if ($request->hasFile('profile_image')) 
         {
            $image_validation = Validator::make(array('file'=>$request->file('profile_image')),
                                                array('file'=>'mimes:jpg,jpeg,png'));
            

            
            if($request->file('profile_image')->isValid() && $image_validation->passes())
            {

            }
            else
            {
                Flash::error('Not valid image! Please Select Proper Image Format');
                return redirect()->back();
            }

            $arr_image_size = [];
            $arr_image_size = getimagesize($request->file('profile_image'));

            if(isset($arr_image_size) && $arr_image_size==false)
            {
                Flash::error('Please use valid image');
                return redirect()->back(); 
            }

            /*-----------------------------------------------------------------
                $arr_image_size[0] = width of image
                $arr_image_size[1] = height of image
            -------------------------------------------------------------------*/

            /* Check Resolution */
            $maxHeight = 140;
            $maxWidth  = 140;

            if(($arr_image_size[0] < $maxWidth) && ($arr_image_size[1] < $maxHeight))
            {
                Flash::error('Image resolution should not be less than 140 x 140 pixel and related dimensions');
                return redirect()->back();
            }  

            $excel_file_name = $request->input('profile_image');
            $fileExtension   = strtolower($request->file('profile_image')->getClientOriginalExtension()); 
            $file_name       = sha1(uniqid().$excel_file_name.uniqid()).'.'.$fileExtension;
            $request->file('profile_image')->move($this->user_profile_base_img_path,$file_name); 
            
            /* Unlink the Existing file from the folder */
            $obj_image = $this->BaseModel->where('id',$request->input('user_id'))->first(['profile_image']);
            if($obj_image)   
            {   
                $_arr = [];
                $_arr = $obj_image->toArray();
                if(isset($_arr['profile_image']) && $_arr['profile_image'] != "" )
                {
                    $unlink_path    = $this->user_profile_base_img_path.$_arr['profile_image'];
                    @unlink($unlink_path);
                }
            }

            $is_new_file_uploaded = TRUE;         
        }

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $arr_user_data = [];

        if($is_new_file_uploaded)
        {
            $arr_user_data['profile_image'] = $file_name;
        }

        $arr_user_data['first_name']     = $request->input('first_name');
        $arr_user_data['last_name']      = $request->input('last_name');
        $arr_user_data['contact_number'] = $request->input('contact_number');
        $arr_user_data['address']        = $request->input('address');
        $arr_user_data['country']        = $request->input('country');
        //$arr_user_data['state']          = $request->input('state');
        $arr_user_data['city']           = $request->input('city');
        $arr_user_data['zipcode']        = $request->input('zipcode');
            
        $check_user = $this->UserModel->where('id','=', $request->input('user_id'))->select('id','role')->first();
        if($check_user!=false)
        {
            $check_user = $check_user->toArray();
            $role = $check_user['role'];
            if($role=='seller')
            {
                $this->module_title = 'Seller';
            }
        }

        $obj_user = $this->BaseModel->where('id','=', $request->input('user_id'))->update($arr_user_data);

        if($obj_user)
        {
            Flash::success(str_singular($this->module_title).' Updated Successfully');
        }
        else
        {
            Flash::error('Problem Occured While Updating '.str_singular($this->module_title));
        }   

        return redirect()->back();      
    }

    public function favourites($enc_id)
    {
        $id = base64_decode($enc_id);
        
        $obj_user = $this->BaseModel->where('id','=',$id)
                                    ->first();
        $arr_data = $arr_favourite = [];                                    
        
        if($obj_user)
        {
            $arr_data = $obj_user->toArray();
        }  

        $obj_favourites = $this->FavouritePackageModel->with('package_details')->where('user_id','=',$id)->get();  
        
        if($obj_favourites)
        {
            $arr_favourite = $obj_favourites->toArray();                    
        }                                     
        
        $package_url_path   = url(config('app.project.admin_panel_slug')."/packages");

        $this->arr_view_data['page_title']                   = "Favourite Packages";
        $this->arr_view_data['module_title']                 = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']              = $this->module_url_path;
        $this->arr_view_data['package_url_path']             = $package_url_path;
        $this->arr_view_data['arr_data']                     = $arr_data;
        $this->arr_view_data['arr_favourite']                = $arr_favourite;
        $this->arr_view_data['theme_color']                  = $this->theme_color;
        /*dd($this->arr_view_data);*/
        return view($this->module_view_folder.'.favourite', $this->arr_view_data);
    }

}
