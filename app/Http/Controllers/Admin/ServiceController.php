<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Traits\MultiActionTrait;

use App\Models\ServiceModel;
use App\Models\CategoryModel;  

use Validator;
use Session;
use Flash;
use File;
use Sentinel;
use DB;

class ServiceController extends Controller
{
  	use MultiActionTrait;
    
    public function __construct(ServiceModel $service_model, CategoryModel $category)
    {

        $this->service_base_img_path         = public_path().config('app.project.img_path.services');
        $this->service_public_img_path       = url('/').config('app.project.img_path.services');

        $this->CategoryModel                 = $category;
        $this->ServiceModel            		 = $service_model;
        $this->BaseModel                	 = $this->ServiceModel;

        $this->arr_view_data      			 = [];
        $this->module_url_path    			 = url(config('app.project.admin_panel_slug')."/service");
        $this->module_title       			 = "Service";
        $this->module_url_slug    			 = "service";
        $this->module_view_folder 			 = "admin.service";
        $this->theme_color        			 = theme_color();
    }

    public function index()
    {
        $arr_service = array();

	    $details = $this->BaseModel->with('subcategory_details','category_details')->get();

        if($details)
        {
           $arr_service = $details->toArray();     
        }

        $this->arr_view_data['arr_service']                   = $arr_service;
        $this->arr_view_data['page_title']                    = str_plural($this->module_title);
        $this->arr_view_data['module_title']                  = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']               = $this->module_url_path;
        $this->arr_view_data['theme_color']                   = $this->theme_color;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function create()
    {
        $arr_parent_category = $this->get_all_parent_category();

        $this->arr_view_data['arr_parent_category_options'] = $arr_parent_category;
        $this->arr_view_data['page_title']   			    = "Create ".str_singular($this->module_title);
        $this->arr_view_data['module_title'] 			    = str_singular($this->module_title);
        $this->arr_view_data['theme_color']  			    = $this->theme_color;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {  
        $form_data = array();

        /* English Required */
        $arr_rules['category']          = "required";
        $arr_rules['subcategory']       = "required";
        $arr_rules['title']             = "required|max:100";
        $arr_rules['price']             = "required|numeric";
        $arr_rules['description']       = "required|max:1000";
        $arr_rules['image']             = "required|image|mimes:jpg,jpeg,png";
        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Flash::error('All Fields Required');
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
    
        /* File uploading code starts here */

        $form_data = $request->all();

        $file_name = "default.jpg";
        
        $file_name = $request->input('image');
        $file_extension = strtolower($request->file('image')->getClientOriginalExtension()); 

        $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
        $request->file('image')->move($this->service_base_img_path, $file_name);  

        $does_exists = $this->BaseModel
        					->where('title',$request->input('title'))
                            ->where('category_id',$request->input('category'))
                            ->where('subcategory_id',$request->input('subcategory'))
                            ->count()>0;
                            
        if($does_exists)
        {
            Flash::error(str_singular($this->module_title).' Already Exists.');
            return redirect()->back();
        }


        /* Insert into Category Table */

        $arr_data                   = array();
        $arr_data['category_id']    = $request->input('category');
        $arr_data['subcategory_id'] = $request->input('subcategory');
        $arr_data['title']          = $request->input('title');
        $arr_data['price']          = $request->input('price');
        $arr_data['description']    = $request->input('description');
        $arr_data['image'] 		    = $file_name;

        $service = $this->BaseModel->create($arr_data);

        if($service)      
        {
            Flash::success(str_singular($this->module_title).' Created Successfully');
        }        
        else
        {
            Flash::error('Problem Occurred, While Creating '.str_singular($this->module_title));
        }

        return redirect()->back();
    }

    public function edit($enc_id)
    {
    	$data = [];
        $id = base64_decode($enc_id);

        $data = $this->BaseModel->with('category_details','subcategory_details')
        						->where('id',$id)
        						->first();
        if($data)
        {
        	$arr_service_data = $data->toArray();
        }

        $arr_parent_category = $this->get_all_parent_category();

        $this->arr_view_data['arr_service_data']   			   = $arr_service_data;
        $this->arr_view_data['arr_parent_category_options']    = $arr_parent_category;
        $this->arr_view_data['enc_id']                         = $enc_id;
        $this->arr_view_data['page_title']                     = "Edit ".str_singular($this->module_title);
        $this->arr_view_data['module_title']                   = str_singular($this->module_title);
        $this->arr_view_data['service_public_img_path']        = $this->service_public_img_path;
        $this->arr_view_data['module_url_path']                = $this->module_url_path;
        $this->arr_view_data['theme_color']                    = $this->theme_color;
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);   
    }

    public function  get_all_parent_category()
    {
        $arr_parent_category = array();
        $obj_data = $this->CategoryModel->where('is_active',1)->where('parent','0')->get();

        if($obj_data)
        {
           $arr_parent_category = $obj_data->toArray();

        }
        return $arr_parent_category;
    }

    public function sub_category($enc_id)
	{
		$id = base64_decode($enc_id);
        $res = $this->CategoryModel->where('is_active',1)->where('parent',$id)->get();
		if($res)
		{
			$res = $res->toArray();
			if(sizeof($res))
			{
				return response()->json(['status'=>'success','subcategory'=> $res]);
			}
			else
			{
				return response()->json(['status'=>'error']);
			}
		}
	}

    public function update(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);

        $arr_rules                 =  array();
        $arr_rules['category']     =  "required";
        $arr_rules['subcategory']  =  "required";
        $arr_rules['title']        =  "required|max:100";
        $arr_rules['price']        =  "required|numeric";
        $arr_rules['description']  =  "required|max:1000";
        
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = array();
        $form_data = $request->all();   

        /* Retrieve Existing Category*/
        $service = $this->BaseModel->where('id',$id)->first();

        if(!$service)
        {
            Flash::error('Problem Occurred While Retrieving '.str_singular($this->module_title));
            return redirect()->back()->withInput($request->all());
        }

        /*     To check Whether category is parent or sub-category      */
        $does_exists = $this->BaseModel->where('title',$request->input('title'))
                                       ->where('category_id',$request->input('category'))
                                       ->where('subcategory_id',$request->input('subcategory'))
                                       ->where('id','<>',$id)
                                       ->count()>0;
        
        if($does_exists)
        {
            Flash::error(str_singular($this->module_title).' Already Exists');
            return redirect()->back()->withInput($request->all());
        }

        /*  File uploading code starts here  */

         $is_new_file_uploaded = FALSE;

         if ($request->hasFile('image')) 
         {
            $excel_file_name = $request->input('image');
            $fileExtension   = strtolower($request->file('image')->getClientOriginalExtension());

            $this->perform_service_image_unlink($service);

            $file_name = sha1(uniqid().$excel_file_name.uniqid()).'.'.$fileExtension;
            $request->file('image')->move($this->service_base_img_path, $file_name);    

            $is_new_file_uploaded = TRUE;         
        }
        
        $service_instance = clone $service ;
        
        /* Update Parent Category */   
        $arr_data = [];
        $arr_data['category_id']       = $request->input('category');
        $arr_data['subcategory_id']    = $request->input('subcategory'); 
        $arr_data['title']             = $request->input('title'); 
        $arr_data['price']             = $request->input('price'); 
        $arr_data['description']       = $request->input('description');   

        if($is_new_file_uploaded)
        {
            $arr_data['image'] = $file_name;
        }

        $service_instance->update($arr_data);
        if($service_instance)
        {
            Flash::success(str_singular($this->module_title).' Updated Successfully');
            return redirect()->back(); 
        }
        else
        {
            Flash::error('Problem Occurred While updating '.str_singular($this->module_title));
            return redirect()->back(); 
        }
    }

    public function perform_service_image_unlink($service)
    {   
        if($service)
        {
            if($service->image)
            {
                if(File::exists($this->service_base_img_path.$service->image))
                {
                    unlink($this->service_base_img_path.$service->image);
                }

                return true;
            }
        }
        else
        {
            return false;
        }

    }
   
}
