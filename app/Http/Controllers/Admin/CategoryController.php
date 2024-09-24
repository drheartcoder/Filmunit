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

class CategoryController extends Controller
{
    use MultiActionTrait;
    
    public function __construct(CategoryModel $category)
    {

        $this->category_base_img_path   = public_path().config('app.project.img_path.category');
        $this->category_public_img_path = url('/').config('app.project.img_path.category');

        $this->CategoryModel            = $category;
        $this->BaseModel                = $this->CategoryModel;

        $this->arr_view_data            = [];
        $this->module_url_path          = url(config('app.project.admin_panel_slug')."/categories");
        $this->module_title             = "Category";
        $this->module_url_slug          = "categories";
        $this->module_view_folder       = "admin.category";

        $this->theme_color              = theme_color();
    }   
 
    public function index()
    {
        $arr_category = array();

        $obj_data = $this->BaseModel->where('parent',0)->get();

        if($obj_data != FALSE)
        {
            $arr_category = $obj_data->toArray();
        }

        $this->arr_view_data['category_public_img_path'] = $this->category_public_img_path;
        $this->arr_view_data['arr_category']             = $arr_category;
        $this->arr_view_data['page_title']               = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_title']             = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']          = $this->module_url_path;
        $this->arr_view_data['theme_color']              = $this->theme_color;
        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }


    public function get_records(Request $request)
    {
        $obj_categories  = $this->get_categories($request);

        $current_context = $this;

        $json_result     = Datatables::of($obj_categories);

        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result->editColumn('enc_id',function($data) use ($current_context)
                            {
                                return base64_encode($data->id);
                            })
                            ->editColumn('build_view_sub_category',function($data) use ($current_context)
                            {
                                $view_href =  $this->module_url_path.'/sub_categories/'.base64_encode($data->id);
                                $build_view_sub_category = '<a class="btn btn-success btn-sm show-tooltip call_loader" href="'.$view_href.'" title="View">View</a>';
                                return $build_view_sub_category;
                            })
                            /*->editColumn('build_status_btn',function($data) use ($current_context)
                            {
                                if($data->is_active != null && $data->is_active == '0')
                                {   
                                    $build_status_btn = '<a class="btn btn-danger" title="Lock" btn-sm show-tooltip call_loader" href="'.$this->module_url_path.'/activate/'.base64_encode($data->id).'" onclick="return confirm(\'Are you sure to Activate this record?\')" ><i class="fa fa-lock"></i></a>';
                                }
                                elseif($data->is_active != null && $data->is_active == '1')
                                {
                                    $build_status_btn = '<a class="btn btn-success" title="Unlock" btn-sm show-tooltip call_loader" href="'.$this->module_url_path.'/deactivate/'.base64_encode($data->id).'" onclick="return confirm(\'Are you sure to Deactivate this record?\')" ><i class="fa fa-unlock"></i></a>';
                                }
                                return $build_status_btn;
                            })*/    
                            ->editColumn('build_action_btn',function($data) use ($current_context)
                            {   
                                $edit_href =  $this->module_url_path.'/edit/'.base64_encode($data->id);
                                $build_edit_action = '<a class="btn btn-primary btn-sm show-tooltip call_loader" href="'.$edit_href.'" title="Edit"><i class="fa fa-edit" ></i></a>';

                                $build_delete_action = '';
                               /* $delete_href =  $this->module_url_path.'/delete/'.base64_encode($data->id);
                                $confirm_delete = 'onclick="return confirm(\'Are you sure to delete this record?\')"';
                                $build_delete_action = '<a class="btn btn-danger btn-sm show-tooltip call_loader" '.$confirm_delete.' href="'.$delete_href.'" title="Delete"><i class="fa fa-trash" ></i></a>';*/

                                return $build_action = $build_edit_action.' '.$build_delete_action;
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        return response()->json($build_result);
    }

    public function get_locale()
    {
        $locale = '';
        if(Session::has('locale'))
        {
            $locale = Session::get('locale');
        }
        else
        {
            $locale = 'en';
        }
        return $locale;
    }

    function get_categories(Request $request)
    {
        $locale = $this->get_locale();

        $categories_table           = $this->BaseModel->getTable();
        $prefixed_categories_table  = DB::getTablePrefix().$this->BaseModel->getTable();

        $categories_trans_table     = $this->CategoryTranslationModel->getTable();
        $prefixed_categories_trans_table  = DB::getTablePrefix().$this->CategoryTranslationModel->getTable();

        $obj_categories = DB::table($categories_table)
                                ->select(DB::raw($prefixed_categories_table.".id as id,".
                                                 $prefixed_categories_table.".parent as parent,".
                                                 $prefixed_categories_table.".is_active as is_active,".
                                                 $prefixed_categories_trans_table.".category_title as category_title,".
                                                 $prefixed_categories_trans_table.".category_slug as category_slug,".
                                                 $prefixed_categories_trans_table.".locale as locale"
                                                 ))
                                ->where($categories_table.'.parent','=',0)
                                ->whereNull($categories_table.'.deleted_at')
                                ->whereNull($prefixed_categories_trans_table.'.deleted_at')
                                ->orderBy($categories_table.'.created_at','DESC')
                                ->where($categories_trans_table.'.locale', '=', $locale)
                                ->join($categories_trans_table,$categories_table.'.id' ,'=', $categories_trans_table.'.category_id');

        /* ---------------- Filtering Logic ----------------------------------*/                    

        $arr_search_column = $request->input('column_filter');
        
        if(isset($arr_search_column['q_category']) && $arr_search_column['q_category']!="")
        {
            $search_term      = $arr_search_column['q_category'];
            $obj_categories = $obj_categories->where($categories_trans_table.'.category_title','LIKE', '%'.$search_term.'%');
        }

        return $obj_categories;
    }


    public function index_sub_category($enc_id)
    {
        $parent_id = base64_decode($enc_id); 

        $arr_category = array();

        /* Get Parent Category Info */
        $parent_category = $this->BaseModel->where('id',$parent_id)->first();

        if($parent_category)
        {
           $page_title = "Manage Sub-".str_singular($this->module_title)." for ".$parent_category->category_title;       
        }

        $res = $this->BaseModel->with('parent_category')->where('parent',$parent_id)->orderBy('id','DESC')->get();
        if($res != FALSE)
        {
            $arr_category = $res->toArray();
        }

        $category_public_img_path = $this->category_public_img_path;

        $this->arr_view_data['category_public_img_path'] = $this->category_public_img_path;
        $this->arr_view_data['arr_category']             = $arr_category;
        $this->arr_view_data['parent_id']                = $parent_id;
        $this->arr_view_data['page_title']               = "Manage Sub-".str_plural($this->module_title);
        $this->arr_view_data['module_title']             = "Sub-".str_plural($this->module_title);
        $this->arr_view_data['module_url_path']          = $this->module_url_path;
        $this->arr_view_data['theme_color']              = $this->theme_color;
        
        return view($this->module_view_folder.'.index_sub_category',$this->arr_view_data);
    }    


    public function create($enc_parent_id=FALSE)
    {

        $parent_id = isset($enc_parent_id)?base64_decode($enc_parent_id):0;

        $arr_parent_category = $this->get_all_parent_category();


        $this->arr_view_data['arr_parent_category_options'] = $this->build_select_options_array($arr_parent_category,'id','title',[]);

        if($parent_id != '0')
        {
            $this->module_title = "Sub-Category";
        }

        $this->arr_view_data['parent_id']    = $parent_id;
        $this->arr_view_data['page_title']   = "Create ".str_singular($this->module_title);
        $this->arr_view_data['module_title'] = str_singular($this->module_title);
        $this->arr_view_data['theme_color']  = $this->theme_color;
        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {
        $this->module_title = "Sub Category";  
        $form_data = array();

        /* English Required */
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

        /* Check if category already exists with given translation */
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

        $data     = array();
        
        $category_image  = "default.jpg";
        $category_parent = "0";

        $obj_data = $this->BaseModel->where('id', $id)->first();

        $arr_category = [];
        if($obj_data)
        {
           $arr_category = $obj_data->toArray();
        }

        if($arr_category['parent'] != '0')
        {
            $this->module_title = "Sub-Category";
        }

        $category_public_img_path = $this->category_public_img_path;

        $this->arr_view_data['edit_mode']                   = TRUE;
        $this->arr_view_data['enc_id']                      = $enc_id;
        $this->arr_view_data['parent_id']                   = $arr_category['parent'];
        
        $this->arr_view_data['category_public_img_path']    = $category_public_img_path;
        $this->arr_view_data['theme_color']                 = $this->theme_color;
        $arr_parent_category                                = $this->get_all_parent_category();
        $this->arr_view_data['arr_parent_category_options'] = $this->build_select_options_array($arr_parent_category,'id','title',[]    
                            );
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
            $arr_rules['parent'] = "required";
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
        $category = $this->BaseModel->where('id',$id)->first();

        if(!$category)
        {
            Flash::error('Problem Occurred While Retrieving '.str_singular($this->module_title));
            return redirect()->back();   
        }

        /*     To check Whether category is parent or sub-category      */
        if($request->input('parent') == 0)
        {
            $does_exists = $this->BaseModel->where('title',$request->input('title'))
                                           ->where('id','<>',$id)
                                           ->count()>0;
        }
        else
        {
            $this->module_title = "Sub Category";
            $does_exists = $this->BaseModel->where('title',$request->input('title'))
                                           ->where('parent',$request->input('parent'))
                                           ->where('id','<>',$id)
                                           ->count()>0;
        }
        
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

            $this->perform_category_image_unlink($category);

            $file_name = sha1(uniqid().$excel_file_name.uniqid()).'.'.$fileExtension;
            $request->file('image')->move($this->category_base_img_path, $file_name);    

            $is_new_file_uploaded = TRUE;         
        }
        
        $category_instance = clone $category ;
        
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

        $category_instance->update($arr_data);
        if($category_instance)
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

    public function perform_category_image_unlink($category)
    {   
        if($category)
        {
            if($category->parent_category)
            {
                if($category->image)
                {
                    if(File::exists($this->category_base_img_path.$category->image))
                    {
                        unlink($this->category_base_img_path.$category->image);
                    }

                    return true;
                }
            }
            else if($category->child_category)
            {
                if($category->child_category)
                {
                   foreach($category->child_category as $key => $data)
                   {
                        if($data->image)
                        {
                            if(File::exists($this->category_base_img_path.$data->image))
                            {
                                unlink($this->category_base_img_path.$data->image);
                            }

                        }

                        $this->CategoryTranslationModel->where('category_id',$data->id)
                                                       ->delete();
                        $data->delete();
                   } 

                }

                if($category->image)
                {
                    if(File::exists($this->category_base_img_path.$category->image))
                    {
                        unlink($this->category_base_img_path.$category->image);
                    }

                    return true;
                }
            }
        }
        else
        {
            return false;
        }

    }

  
    public function build_select_options_array(array $arr_data,$option_key,$option_value,array $arr_default)
    {

        $arr_options = [];
        /*--------------------------------------------------------------
        |   Array Default - Main Category Hide For Temporary
        ---------------------------------------------------------------*/

        if(sizeof($arr_default)>0)
        {
            $arr_options =  $arr_default;   
        }

        if(sizeof($arr_data)>0)
        {
            foreach ($arr_data as $key => $data) 
            {
                if(isset($data[$option_key]) && isset($data[$option_value]))
                {
                    $arr_options[$data[$option_key]] = $data[$option_value];
                }
            }
        }

        return $arr_options;

    } 

    public function  get_all_parent_category()
    {
        $arr_parent_category = array();
        $obj_data = $this->BaseModel->where('parent','0')->get();

        if($obj_data)
        {
           $arr_parent_category = $obj_data->toArray();

        }
        return $arr_parent_category;
    }

}
