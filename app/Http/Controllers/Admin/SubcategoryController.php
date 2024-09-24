<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Traits\MultiActionTrait;

use App\Models\CategoryModel;  

use Validator;
use Session;
use Flash;
use File;
use Sentinel;
use DB;
use Datatables;

class SubcategoryController extends Controller
{
    use MultiActionTrait;
    
    public function __construct(CategoryModel $category)
    {

        $this->category_base_img_path   = public_path().config('app.project.img_path.category');
        $this->category_public_img_path = url('/').config('app.project.img_path.category');

        $this->CategoryModel            = $category;
        $this->BaseModel                = $this->CategoryModel;
        
        $this->arr_view_data            = [];
        $this->module_url_path          = url(config('app.project.admin_panel_slug')."/subcategories");
        $this->module_title             = "Sub Categories";
        $this->module_url_slug          = "Subcategories";
        $this->module_view_folder       = "admin.subcategory";
        
        $this->theme_color              = theme_color();
    }

   public function index()
    {
        $arr_data = array();

	    $details = $this->BaseModel->with('parent_category')
	    						   ->where('parent','<>',0)
	    						   ->get();

        if($details)
        {
           $arr_data = $details->toArray();     
        }

        $this->arr_view_data['arr_data']         = $arr_data;
        $this->arr_view_data['page_title']       = str_plural($this->module_title);
        $this->arr_view_data['module_title']     = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']  = $this->module_url_path;
        $this->arr_view_data['theme_color']      = $this->theme_color;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function create()
    {
        $arr_parent_category = $this->get_all_parent_category();

        $this->arr_view_data['arr_parent_category_options'] = $arr_parent_category;
        $this->arr_view_data['page_title']                  = "Create ".str_singular($this->module_title);
        $this->arr_view_data['module_title']                = str_singular($this->module_title);
        $this->arr_view_data['theme_color']                 = $this->theme_color;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {  
        $form_data = array();

        /* rules Required */
        $arr_rules['title']             = "required";
        $arr_rules['parent']            = "required";
        $arr_rules['image']             = "required|image|mimes:jpg,jpeg,png";
        $arr_rules['description']       = "required|max:1000";
        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Flash::error('All Fields Required');
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
    
        
        $form_data = $request->all();

        /* get parent categories*/
        $arr_parent_category = $this->get_all_parent_category();
        foreach ($arr_parent_category as $key => $category)
        {
            if($request->input('title') == $category['title'])
            {
                Flash::error('You cannot create parent category as '.(str_singular($this->module_title)));
                return redirect()->back();    
            }
        }
        
        /* Check if category already exists */
        $does_exists = $this->BaseModel->where('title',$request->input('title'))
                                       ->where('parent',$request->input('parent'))
                                       ->count()>0;   

        if($does_exists)
        {
            Flash::error(str_singular($this->module_title).' Already Exists.');
            return redirect()->back()->withInput($request->all());
        }
        /* File uploading code starts here */

        $file_name = "default.png";
        
        $file_name = $request->input('image');
        $file_extension = strtolower($request->file('image')->getClientOriginalExtension()); 

        $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
        $request->file('image')->move($this->category_base_img_path, $file_name);  


        /* Insert into Category Table */

        $arr_data                = array();
        $arr_data['image']       = $file_name;
        $arr_data['parent']      = $request->input('parent');
        $arr_data['title']       = $request->input('title');
        $arr_data['description'] = $request->input('description');
        $arr_data['slug']        = str_slug($request->input('title'), "-");
        $arr_data['is_active']   = 1;

        $category = $this->BaseModel->create($arr_data);

        if($category)      
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

        $id = base64_decode($enc_id);

        $category_image  = "default.jpg";
        $category_parent = "0";

        $obj_data = $this->BaseModel->with('parent_category')->where('id', $id)->first();

        $arr_category = [];
        if($obj_data)
        {
           $arr_category = $obj_data->toArray();
        }

        $category_public_img_path = $this->category_public_img_path;
        $arr_parent_category                                = $this->get_all_parent_category();

        $this->arr_view_data['edit_mode']                   = TRUE;
        $this->arr_view_data['enc_id']                      = $enc_id;
        $this->arr_view_data['parent_id']                   = $arr_category['parent'];
        
        $this->arr_view_data['category_public_img_path']    = $category_public_img_path;
        $this->arr_view_data['theme_color']                 = $this->theme_color;
        $this->arr_view_data['arr_parent_category_options'] = $arr_parent_category;
        $this->arr_view_data['arr_category']                = $arr_category;  
        $this->arr_view_data['page_title']                  = "Edit ".str_singular($this->module_title);
        $this->arr_view_data['module_title']                = str_singular($this->module_title);
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);   
    }

    public function update(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);

        $arr_rules                = array();
        $arr_rules['title']       = "required";
        $arr_rules['description'] = "required|max:1000";

        if($request->has('parent'))
        {
            $arr_rules['parent']  = "required";
        }

        $arr_rules['image'] = "image";
        
         
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $form_data = array();
        $form_data = $request->all();  

        /* Retrieve Existing Category*/
        $subcategory = $this->BaseModel->where('id',$id)->first();

        if(!$subcategory)
        {
            Flash::error('Problem Occurred While Retrieving '.str_singular($this->module_title));
            return redirect()->back();   
        }

        /*     To check Whether sub-category is exisitng      */
            $does_exists = $this->BaseModel->where('title',$request->input('title'))
                                           ->where('parent',$request->input('parent'))
                                           ->where('id','<>',$id)
                                           ->count()>0;
        
        if($does_exists)
        {
            Flash::error(str_singular($this->module_title).' Already Exists');
            return redirect()->back();
        }

        /*  File uploading code starts here  */

         $is_new_file_uploaded = FALSE;

         if ($request->hasFile('image')) 
         {
            $excel_file_name = $request->input('image');
            $fileExtension   = strtolower($request->file('image')->getClientOriginalExtension());

            $this->perform_category_image_unlink($subcategory);

            $file_name = sha1(uniqid().$excel_file_name.uniqid()).'.'.$fileExtension;
            $request->file('image')->move($this->category_base_img_path, $file_name);    

            $is_new_file_uploaded = TRUE;         
        }
        
        $subcategory_instance = clone $subcategory ;
        
        /* Update Parent Category */   
        $arr_data = [];
        $arr_data['title']       = $request->input('title');
        $arr_data['description'] = $request->input('description');
        $arr_data['slug']        = str_slug($request->input('title'), "-");  

        if($request->has('parent'))
        {
            $arr_data['parent'] = $request->input('parent');
        }   

        if($is_new_file_uploaded)
        {
            $arr_data['image'] = $file_name;
        }

        $subcategory_instance->update($arr_data);
        if($subcategory_instance)
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

    public function perform_category_image_unlink($subcategory)
    {   
        if($subcategory)
        {
            if($subcategory->image)
            {
                if(File::exists($this->category_base_img_path.$subcategory->image))
                {
                    unlink($this->category_base_img_path.$subcategory->image);
                }

                return true;
            }
        }
        else
        {
            return false;
        }

    }

    public function  get_all_parent_category()
    {
        $arr_parent_category = array();
        $obj_data = $this->CategoryModel->where('parent','0')->get();

        if($obj_data)
        {
           $arr_parent_category = $obj_data->toArray();

        }
        return $arr_parent_category;
    }
}
