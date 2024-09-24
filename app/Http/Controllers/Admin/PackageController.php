<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Traits\MultiActionTrait;

use App\Models\UserModel;
use App\Models\PackageModel;   
use App\Models\PackageDetailModel;   
use App\Models\MediaListingMasterModel;   
use App\Models\MediaListingDetailModel;   
use App\Models\FormatModel;   

use Flash;
use Validator;
use Sentinel;

use DB;
use Datatables;

class PackageController extends Controller
{
    use MultiActionTrait;

    public function __construct(    
                                    UserModel $user,
                                    MediaListingMasterModel $media_master_model,
                                    MediaListingDetailModel $media_detail_model,
                                    FormatModel $format_model,
                                    PackageModel $package_model,
                                    PackageDetailModel $package_detail_model
                                )
    {
        $user = Sentinel::createModel();

        $this->UserModel                    = $user;

        $this->PackageModel                 = $package_model;
        $this->PackageDetailModel           = $package_detail_model;
        $this->BaseModel                    = $package_model;
        $this->FormatModel                  = $format_model;
        
        $this->MediaListingMasterModel      = $media_master_model;
        $this->MediaListingDetailModel      = $media_detail_model;

        $this->arr_view_data                = [];
        $this->module_url_path              = url(config('app.project.admin_panel_slug')."/packs");
        $this->package_image_public_img_path = url('/').'/'.config('app.project.img_path.package_images');
        $this->package_image_base_img_path   = base_path().'/'.config('app.project.img_path.package_images');
        $this->module_title                 = "Package";
        $this->modyle_url_slug              = "Pack";
        $this->admin_photos_public_img_path       = url('/').'/'.config('app.project.img_path.admin_photos');
        $this->admin_footage_image_public_path    = url('/').'/'.config('app.project.img_path.admin_footage_image');

        $this->module_view_folder           = "admin.pack";
        $this->theme_color                  = theme_color();
    }	

    /* ---------------- Index ----------------------------------*/                    

    public function index()
    {
        $obj_packages = false;       
        $arr_packages = [];       

        $page_title = "Create ".str_plural($this->module_title);
        
        $back_url            = $this->module_url_path;
        $page_title          = str_plural($this->module_title);
        $module_title        = str_plural($this->module_title);
        $module_icon         = "fa fa-briefcase";
        $page_title_sub      = $this->module_title;
        $toreview            = '';

        $obj_packages = $this->PackageModel->orderBy('id','DESC')->with('package_details')->get();
        if($obj_packages!=false)
        {
            $arr_packages = $obj_packages->toArray();   
        }

        $this->arr_view_data['arr_packages']    = $arr_packages;
        $this->arr_view_data['page_title']      = $page_title;
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_icon']     = $module_icon;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;

        return view($this->module_view_folder.'.index', $this->arr_view_data);
    }

    /* ---------------- Create Page ----------------------------------*/                    

    public function create()
    {
        $obj_packages = false;       
        $arr_packages = [];       

        $page_title = "Create ".str_plural($this->module_title);
        
        $back_url            = $this->module_url_path;
        $page_title          = "Create ".str_plural($this->module_title);
        $module_title        = "Create ".str_plural($this->module_title);
        $module_icon         = "fa fa-briefcase";
        $page_title_sub      = $this->module_title;
        $toreview            = '';

        $this->arr_view_data['arr_packages']    = $arr_packages;
        $this->arr_view_data['page_title']      = $page_title;
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_icon']     = $module_icon;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;

        return view($this->module_view_folder.'.create', $this->arr_view_data);
    }

    /* ---------------- Store Package ----------------------------------*/                    

    public function store(Request $request)
    {
        $obj_data         = false;
        $status           = false;
        $arr_rules        = array();
        $arr_data         = [];
        $arr_detail_data  = [];
        $arr_packages     = [];
        $pack_id          = $packages = $media_slug = ''; 
        
        $arr_rules['title']             = "required";
        $arr_rules['checked_records']   = "required";
        $arr_rules['image']             = "required";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        
        if($this->PackageModel->where('title',$request->input('title'))
                              ->count()==1)
        {
            Flash::error('This Package is already present');
            return redirect()->back();
        }
        
        if($request->hasFile('image'))
        {
            $file_name      = $request->input('image');
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());
            if(in_array($file_extension,['png','jpg','jpeg','gif']))
            {
                $file_name  = time().uniqid().'.'.$file_extension;
                $isUpload   = $request->file('image')->move($this->package_image_base_img_path , $file_name);
            }
            else
            {
                Flash::error('Invalid File type, While creating package.');
                return redirect()->back();
            }
        }

          $media_slug = $request->input('title');
          $media_slug = trim($request->input('title'));
          $media_slug = str_replace(' ', '_', $media_slug);
          $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
          $media_slug = $media_slug.rand(10,100);

        $arr_data['enc_image_name'] = $file_name;
        $arr_data['title']          = trim($request->input('title'));
        $arr_data['slug']           = $media_slug;

        $obj_data = $this->PackageModel->create($arr_data);

        if($obj_data)
        {
            $pack_id      = $obj_data->id;
            $packages     = $request->input('checked_records');
            $arr_packages = explode(',', $packages);
            
            if(isset($arr_packages) && count($arr_packages)>0)
            {
                foreach ($arr_packages as $key => $value)
                {
                    $arr_detail_data['pack_id'] = $pack_id;
                    $arr_detail_data['list_id'] = base64_decode($value);

                    $status = $this->PackageDetailModel->create($arr_detail_data);
                }
            }

            if($status)
            {
                Flash::success('Package has been Created Successfully'); 
            }
        }
        else
        {
            Flash::error('Problem Occurred, While Creating Package.');  
        } 
      
        return redirect()->back();
    }

    /* ---------------- View Package ----------------------------------*/                    

    public function view($enc_id)
    {
        $obj_data = false;
        $arr_data = [];
        $id = '';

        if($enc_id!='')
        {
            $page_title          = "View ".str_plural($this->module_title);
            
            $back_url            = $this->module_url_path;
            $page_title          = "View ".str_plural($this->module_title);
            $module_title        = "View ".str_plural($this->module_title);
            $module_icon         = "fa fa-briefcase";
            $page_title_sub      = $this->module_title;
            $toreview            = '';            

            $id = base64_decode($enc_id);

            $obj_data = $this->PackageModel->where('id',$id)
                                          ->with(['package_details.listing_details'=>function($query){
                                                    $query->with('master_details.seller_details','format_details');
                                                    }])
                                          ->first();

            if($obj_data!=false)
            {
                $arr_data = $obj_data->toArray();
            }

            $this->arr_view_data['arr_data']                         = $arr_data;
            $this->arr_view_data['page_title']                       = $page_title;
            $this->arr_view_data['module_title']                     = $this->module_title;
            $this->arr_view_data['module_url_path']                  = $this->module_url_path;
            $this->arr_view_data['theme_color']                      = $this->theme_color;
            $this->arr_view_data['admin_photos_public_img_path']     = $this->admin_photos_public_img_path;
            $this->arr_view_data['admin_footage_image_public_path']  = $this->admin_footage_image_public_path;

            return view($this->module_view_folder.'.view', $this->arr_view_data);
        }
        else
        {
            return redirect()->back();
        }

    }         

    /* ---------------- Edit Package View ----------------------------------*/                    

    public function edit($enc_id)
    {
        $obj_packages = false;       
        $arr_packages = [];       
        $id = '';

        if($enc_id!='')
        {
            $id = base64_decode($enc_id);
            
            $obj_packages = $this->PackageModel->where('id',$id)->with('package_details.listing_details.master_details')
                                                                ->with('package_details.listing_details.format_details')
                                                                ->first();
            
            if($obj_packages!=false)
            {
                $arr_packages = $obj_packages->toArray();
            }

            $page_title          = "Edit ".str_plural($this->module_title);
            $back_url            = $this->module_url_path;
            $page_title          = "Edit ".str_plural($this->module_title);
            $module_title        = "Edit ".str_plural($this->module_title);
            $module_icon         = "fa fa-briefcase";
            $page_title_sub      = $this->module_title;
            $toreview            = '';

            $this->arr_view_data['arr_packages']    = $arr_packages;
            $this->arr_view_data['page_title']      = $page_title;
            $this->arr_view_data['module_title']    = str_plural($this->module_title);
            $this->arr_view_data['module_icon']     = $module_icon;
            $this->arr_view_data['module_url_path'] = $this->module_url_path;
            $this->arr_view_data['theme_color']     = $this->theme_color;
            $this->arr_view_data['enc_id']          = $enc_id;

            $this->arr_view_data['package_image_public_img_path']     = $this->package_image_public_img_path;

            return view($this->module_view_folder.'.edit', $this->arr_view_data);
        }
        return redirect()->back();
    }

    /* ---------------- Update Package ----------------------------------*/                    

    public function update(Request $request,$enc_id)
    {
        if($enc_id=='')
        {
            return redirect()->back();
        }

        $obj_data         = false;
        $status           = false;
        $arr_rules        = array();
        $arr_data         = [];
        $arr_detail_data  = [];
        $arr_packages     = [];
        $pack_id          = ''; 
        $id               = $packages = $media_slug = ''; 
        
        $arr_rules['title']             = "required";
        $arr_rules['checked_records']   = "required";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        
        $id = base64_decode($enc_id);

        if($this->PackageModel->where('title',$request->input('title'))
                              ->where('id','<>',$id)
                              ->count()==1)
        {
            Flash::error('This Package is already present');
            return redirect()->back();
        }
        
        $oldimage = $request->input('oldimage');

        if($request->hasFile('image'))
        {
            $file_name      = $request->input('image');
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());
            if(in_array($file_extension,['png','jpg','jpeg','gif']))
            {
                $file_name  = time().uniqid().'.'.$file_extension;
                $isUpload   = $request->file('image')->move($this->package_image_base_img_path , $file_name);
                if($isUpload)
                {
                    @unlink($this->package_image_base_img_path.$oldimage);
                }
            }
            else
            {
                Flash::error('Invalid File type, While creating package.');
                return redirect()->back();
            }
        }
        else
        {
            $file_name = $oldimage;            
        }
          $media_slug = $request->input('title');
          $media_slug = trim($request->input('title'));
          $media_slug = str_replace(' ', '_', $media_slug);
          $media_slug = preg_replace('/[^a-zA-Z0-9_.]/','', $media_slug);
          $media_slug = $media_slug.rand(10,100);

        $arr_data['enc_image_name'] = $file_name;
        $arr_data['title']          = trim($request->input('title'));
        $arr_data['slug']           = $media_slug;

        $obj_data = $this->PackageModel->where('id',$id)->update($arr_data);

        if($obj_data)
        {
            $pack_id      = $id;
            $packages     = $request->input('checked_records');
            $arr_packages = explode(',', $packages);
            
            //Delete Old Values
            $delete_old_lisiting = $this->PackageDetailModel->where('pack_id',$pack_id)->delete();

            if(isset($arr_packages) && count($arr_packages)>0)
            {
                foreach ($arr_packages as $key => $value)
                {
                    $arr_detail_data['pack_id'] = $pack_id;
                    $arr_detail_data['list_id'] = base64_decode($value);

                    $status = $this->PackageDetailModel->create($arr_detail_data);
                }
            }

            if($status)
            {
                Flash::success('Package has been updated Successfully'); 
            }

        }
        else
        {
            Flash::error('Problem Occurred, While updating Package.');  
        } 
      
        return redirect()->back();
    }    

    /* ---------------- Deactive Package ----------------------------------*/                    

    public function deactive($enc_id)
    {
        $id     = '';
        $status = false;

        if($enc_id != '')
        {
            $id = base64_decode($enc_id);

            $status = $this->PackageModel->where('id',$id)->update(['is_active'=>0]);
            
            if($status!=false)
            {
                Flash::success("Package has been dectivated successfully.");
            }
            else
            {
                Flash::error("Error while dectivating package.");
            }
        }
        return redirect()->back();
    }

    /* ---------------- Active Package ----------------------------------*/                    

    public function active($enc_id)
    {
        $id     = '';
        $status = false;

        if($enc_id != '')
        {
            $id = base64_decode($enc_id);

            $status = $this->PackageModel->where('id',$id)->update(['is_active'=>1]);
            
            if($status!=false)
            {
                Flash::success("Package has been Activated successfully.");
            }
            else
            {
                Flash::error("Error while activating package.");
            }
        }
        return redirect()->back();
    }

    /* ---------------- Delete Package ----------------------------------*/                    

    public function delete($enc_id)
    {
        $id              = '';
        $arr_details     = [];
        $obj_details     = false;
        $status          = false;

        if($enc_id != '')
        {
            $id = base64_decode($enc_id);

            //unlink Image
            $obj_details = $this->PackageModel->where('id',$id)->first();
            if($obj_details!=false)
            {
                $arr_details = $obj_details->toArray();
                @unlink($this->package_image_base_img_path.$arr_details['enc_image_name']);
            }

            $status = $this->PackageModel->where('id',$id)->delete();
            
            if($status!=false)
            {
                $detail_status = $this->PackageDetailModel->where('pack_id',$id)->delete();
                if($detail_status)
                {
                    Flash::success("Package has been deleted successfully.");
                }
            }
            else
            {
                Flash::error("Error while deleting package.");
            }
        }
        return redirect()->back();
    } 

    /* ---------------- Get Lisiting of Package ----------------------------------*/                    

    function get_listing_details(Request $request)
    {
        $package_details             = $this->PackageModel->getTable();
        $prefixed_package_details    = DB::getTablePrefix().$this->PackageModel->getTable();

        $master_details              = $this->MediaListingMasterModel->getTable();
        $prefixed_master_details     = DB::getTablePrefix().$this->MediaListingMasterModel->getTable();

        $lisitng_details             = $this->MediaListingDetailModel->getTable();
        $prefixed_lisitng_details    = DB::getTablePrefix().$this->MediaListingDetailModel->getTable();

        $user_details                = $this->UserModel->getTable();
        $prefixed_user_details       = DB::getTablePrefix().$this->UserModel->getTable();        

        $format_details                = $this->FormatModel->getTable();
        $prefixed_format_details       = DB::getTablePrefix().$this->FormatModel->getTable();

        $obj_list = DB::table($master_details)
                                ->select(DB::raw($prefixed_master_details.".id as id,".
                                                "UCASE(".$prefixed_master_details.".type) as type, ".
                                                 $prefixed_master_details.".seller_id as seller_id, ".
                                                 $prefixed_master_details.".title as package_title, ".
                                                 $prefixed_master_details.".is_approved as is_approved, ".
                                                 $prefixed_master_details.".keywords as keywords, ".
                                                 $prefixed_master_details.".admin_enc_item_name as admin_enc_item_name, ".
                                                 $prefixed_master_details.".admin_enc_footage_image as admin_enc_footage_image, ".
                                                 $prefixed_lisitng_details.".id as detail_id,".
                                                 $prefixed_lisitng_details.".price as price, ".
                                                "UCASE(".$prefixed_format_details.".name) as format, ".
                                                 "CONCAT(".$prefixed_user_details.".first_name,' ',"
                                                          .$prefixed_user_details.".last_name) as seller_name "
                                                 ))
                                ->join($lisitng_details,$lisitng_details.'.list_id','=',$master_details.'.id')
                                ->join($user_details,$master_details.'.seller_id','=',$user_details.'.id')
                                ->join($format_details,$lisitng_details.'.format','=',$format_details.'.id')
                                ->where($prefixed_master_details.'.is_approved','=',1);

        
        /* ---------------- Filtering Logic ----------------------------------*/                    

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

        if(isset($arr_search_column['q_keyword']) && $arr_search_column['q_keyword']!="")
        {
            $search_term      = $arr_search_column['q_keyword'];
            $obj_list = $obj_list->having('keywords','LIKE', '%'.$search_term.'%');
        }        

        if(isset($arr_search_column['q_price']) && $arr_search_column['q_price']!="")
        {
            $search_term      = $arr_search_column['q_price'];
            $obj_list = $obj_list->where($lisitng_details.'.price','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_format']) && $arr_search_column['q_format']!="")
        {
            $search_term      = $arr_search_column['q_format'];
            $obj_list = $obj_list->where($format_details.'.name','LIKE', '%'.$search_term.'%');
        }        

        return $obj_list;
    }

    /* ---------------- Get Lisiting Records from create package page ----------------------------------*/                    

    public function get_records(Request $request)
    {   
       // dd($request->all());
        $obj_list        = $this->get_listing_details($request);

        $master_details              = $this->MediaListingMasterModel->getTable();
        $prefixed_master_details     = DB::getTablePrefix().$this->MediaListingMasterModel->getTable();        

        $obj_list = $obj_list->orderBy($master_details.'.id','DESC');

        $current_context = $this;

        $json_result     = Datatables::of($obj_list);

        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result->editColumn('enc_id',function($data) use ($current_context)
                            {
                                return base64_encode($data->detail_id);
                            })
                            ->editColumn('build_thumbnail',function($data) use ($current_context)
                            {
                                $build_thumbnail = '';
                                if($data->type=='PHOTO')
                                {
                                    $build_thumbnail = '<img src="'.$this->admin_photos_public_img_path.$data->admin_enc_item_name.'" style="height:100px;width:100px;">';                                    
                                }
                                else
                                {
                                    $build_thumbnail = '<img src="'.$this->admin_footage_image_public_path.$data->admin_enc_footage_image.'" style="height:100px;width:100px;">';                                    
                                }

                                return $build_thumbnail;
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        return response()->json($build_result);
    }

    /* ---------------- Get Lisiting Records from edit package page ----------------------------------*/                    

    public function get_edit_records(Request $request)
    {
        if($request->input('enc_id')!='')
        {
            $obj_data = false;
            $arr_data = [];
            $id       = $status = '';
            $id       = base64_decode($request->input('enc_id'));

            $obj_data = $this->PackageModel->where('id',$id)
                                          ->with('package_details')
                                          ->first();
            if($obj_data!=false)
            {
                 $arr_data = $obj_data->toArray();
            }

            $obj_list        = $this->get_listing_details($request);

            $current_context = $this;

            $json_result     = Datatables::of($obj_list);

            $json_result     = $json_result->blacklist(['id']);
            
            $json_result     = $json_result->editColumn('enc_id',function($data) use ($current_context,$arr_data,$id,$status)
                                {
                                    if($data->detail_id != null)
                                    {
                                        if(isset($arr_data['package_details']) && count($arr_data['package_details'] )>0)
                                        {
                                            foreach ($arr_data['package_details'] as $key => $value)
                                            {
                                                if($value['list_id']==$data->detail_id)
                                                {
                                                    $status = 'checked="checked"';
                                                }                                                    
                                            }    
                                            $enc_id = '<input type="checkbox" name="checked_record[]" onclick="onCheckedValues(this)" value="'.base64_encode($data->detail_id).'" '.$status.' class="check_values"/>';
                                        }
                                    }
                                    return $enc_id;
                                })
                                ->editColumn('build_thumbnail',function($data) use ($current_context)
                                {
                                    $build_thumbnail = '';
                                    
                                    if($data->type=='PHOTO')
                                    {
                                        $build_thumbnail = '<img src="'.$this->admin_photos_public_img_path.$data->admin_enc_item_name.'" style="height:100px;width:100px;">';                                    
                                    }
                                    else
                                    {
                                        $build_thumbnail = '<img src="'.$this->admin_footage_image_public_path.$data->admin_enc_footage_image.'" style="height:100px;width:100px;">';                                    
                                    }
                                    return $build_thumbnail;
                                })            
                                ->make(true);

            $build_result = $json_result->getData();
            
            return response()->json($build_result);
        }
        else
        {
            return false;
        }
    } 

    /* ---------------- Get Lisiting Records For Manage Page ----------------------------------*/                    

    public function manage_index_records(Request $request)
    {   
       // dd($request->all());
        $obj_list        = $this->get_index_listing_details($request);

        $current_context = $this;

        $json_result     = Datatables::of($obj_list);

        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result->editColumn('enc_id',function($data) use ($current_context)
                            {
                                return base64_encode($data->id);
                            })
                            ->editColumn('title',function($data) use ($current_context)
                            {
                                return ucwords($data->title);
                            })
                            ->editColumn('build_thumbnail',function($data) use ($current_context)
                            {
                                $build_thumbnail = '';

                                if($data->enc_image_name!='')
                                {
                                    $build_thumbnail = '<img src="'.$this->package_image_public_img_path.$data->enc_image_name.'" style="height:100px;width:100px;">';                                    
                                }
                                else
                                {
                                    $build_thumbnail = '<img src="'.$this->package_image_public_img_path.'/default.png" style="height:100px;width:100px;">';                                    
                                }

                                return $build_thumbnail;
                            })
                            ->editColumn('build_order_box',function($data) use ($current_context)
                            {
                                $build_order_box = '<input type="text" class="option_order_number" data-pack-id="'.base64_encode($data->id).'" data-rule-number="true" name="order_number" value="'.$data->order_number.'" data-rule-required="true" maxlength="1" style="width:50px"><span class="help-block"></span>';                                    
                                
                                return $build_order_box;
                            })
                            ->editColumn('build_order_btn',function($data) use ($current_context)
                            {   
                                $order_count = 0;
                                $order_count = $this->PackageDetailModel->where('pack_id',$data->id)->count();

                                $href_action =  $this->module_url_path.'/view/'.base64_encode($data->id);
                                $build_order_btn = '<a href="'.$href_action.'" title="View Buyer Orders List"><span class="badge badge-important">'.$order_count.'</span></a>';
                                return $build_order_btn;
                            })
                            ->editColumn('build_status_btn',function($data) use ($current_context)
                            {
                                if($data->is_active==1)
                                {
                                    $href_view =  $this->module_url_path.'/deactivate/'.base64_encode($data->id);
                                    $build_status_btn = '<a href="'.$href_view.'" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,\'Do you really want to deactivate this record?\')" title="Deactivate" ><i class="fa fa-unlock"></i></a>';
                                }
                                else
                                {
                                    $href_view =  $this->module_url_path.'/activate/'.base64_encode($data->id);
                                    $build_status_btn = '<a href="'.$href_view.'" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,\'Do you really want to activate this record?\')" title="Ativate" ><i class="fa fa-lock"></i></a>';
                                }
                                return $build_status_btn;
                            })
                            ->editColumn('build_action_btn',function($data) use ($current_context)
                            {
                                $href_view =  $this->module_url_path.'/view/'.base64_encode($data->id);
                                $build_view_btn = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$href_view.'" title="View" ><i class="fa fa-eye"></i></a>';

                                $href_edit =  $this->module_url_path.'/edit/'.base64_encode($data->id);
                                $build_edit_btn = '<a href="'.$href_edit.'" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Edit" ><i class="fa fa-edit"></i></a>';

                                $href_delete =  $this->module_url_path.'/delete/'.base64_encode($data->id);
                                $build_delete_btn = '<a href="'.$href_delete.'" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,\'Do you really want to delete this record?\')" title="Delete" ><i class="fa fa-trash"></i></a>';                                

                                return $build_view_btn." ".$build_edit_btn." ".$build_delete_btn." ";
                            })        
                            ->make(true);

        $build_result = $json_result->getData();
        
        return response()->json($build_result);
    }
    

    /* ---------------- Get Lisiting of Ifndex Pages ----------------------------------*/                    

    function get_index_listing_details(Request $request)
    {
        $column = '';

        if ($request->input('order')[0]['column'] == 0) 
        {
          $column = "title";
        } 
        if ($request->input('order')[0]['column'] == 1) 
        {
          $column = "enc_image_name";
        } 

        $order = strtoupper($request->input('order')[0]['dir']);

        $package_details             = $this->PackageModel->getTable();
        $prefixed_package_details    = DB::getTablePrefix().$this->PackageModel->getTable();

        $obj_list = DB::table($package_details)
                                ->select("id","title","is_active","enc_image_name","order_number");
                                /*->orderBy('id','DESC')*/
        
        /* ---------------- Filtering Logic ----------------------------------*/                    

        $arr_search_column = $request->input('column_filter');
        
        if(isset($arr_search_column['q_title']) && $arr_search_column['q_title']!="")
        {
            $search_term      = strtolower($arr_search_column['q_title']);
            $obj_list         = $obj_list->where($package_details.'.title','LIKE', '%'.$search_term.'%');
        }

        if(($order =='ASC' || $order =='') && $column=='')
        {
          $obj_list  = $obj_list->orderBy($package_details.'.id','DESC');
        }
        
        if( $order !='' && $column!='' )
        {
          $obj_list = $obj_list->orderBy($column,$order);
        }

        return $obj_list;
    }

    /* ---------------- Update Package Order ----------------------------------*/                    

    public function update_order(Request $request)
    {
        $update_order = false;
        $id  = '';
        $val = '';

        if($request->has('enc_id') && $request->has('val'))
        {
            $id = base64_decode($request->input('enc_id'));
            $val = $request->input('val');

            $update_order = $this->PackageModel->where('id',$id)->update(['order_number'=>$val]);
            if($update_order!=false)
            {
                return response()->json(['status'=>'success','msg'=>'Order has been updated successfully']);
            }
            else
            {
                return response()->json(['status'=>'error','msg'=>'Error while updating package order']);
            }
        }
        else
        {
            return response()->json(['status'=>'error','msg'=>'Error while updating package order']);
        }
    }
       
}