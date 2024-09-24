<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ControlAttributeModel; 
use App\Models\ControlAttributeOptionModel; 
use App\Models\CategoryModel;   

use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Input;
use Datatables;
use Flash;
 
class ControlAttributeController extends Controller
{
    use MultiActionTrait;

    public function __construct(
                                    ControlAttributeModel $control_attribute_model, 
                                    ControlAttributeOptionModel $control_attribute_option_model,
                                    CategoryModel $category_model
                                )
    {
        $this->ControlAttributeModel       = $control_attribute_model;
        $this->ControlAttributeOptionModel = $control_attribute_option_model;
        $this->CategoryModel               = $category_model;

        $this->BaseModel          = $this->ControlAttributeModel;   

        $this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/control_attributes";
        $this->module_title       = "Control Attributes";
        $this->module_view_folder = "admin.control_attributes";

        $this->arr_view_data    = [];
    } 
 
    public function show_category_questions()
    {   
        $arr_attributes = array();

        /* getting only those categories which have questions */
        $obj_category_questions = $this->CategoryModel
                                            ->whereHas('category_control_attributes',function(){})
                                            ->with('category_control_attributes')
                                            ->with('parent_category')
                                            ->get();

        if(isset($obj_category_questions) && sizeof($obj_category_questions)>0)
        {
            $arr_attributes = $obj_category_questions->toArray();
        }   

        $this->arr_view_data['page_title']      = str_plural( $this->module_title);
        $this->arr_view_data['module_title']    = str_plural( $this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['arr_data']        = $arr_attributes;
        
        return view($this->module_view_folder.'.category_questions_listing',$this->arr_view_data);
    }
 
    public function index($enc_id = FALSE)
    {       
        if($enc_id == "" )
        {
            Flash::error('Unauthorised action !');
            return redirect()->back();
        }

        $subcategory  = base64_decode($enc_id);

        $arr_attributes = array();

        $obj_category = $this->CategoryModel->where('id',$subcategory)->first();

        if($obj_category)
        {
            $arr_category = $obj_category->toArray();
        }

        $obj_attributes = $this->BaseModel
                                        ->with('control_attribute_options')
                                        ->where('subcategory_id',$subcategory) 
                                        ->get();

        if(isset($obj_attributes) && sizeof($obj_attributes)>0)
        {
            $arr_attributes = $obj_attributes->toArray();
        }   

        $this->arr_view_data['page_title']       = "Manage ".str_plural( $this->module_title);
        $this->arr_view_data['module_title']     = $this->module_title;
        $this->arr_view_data['module_url_path']  = $this->module_url_path;
        $this->arr_view_data['arr_category']     = $arr_category;
        $this->arr_view_data['arr_attributes']   = $arr_attributes;
        
        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function create()
    {
        $arr_categories = [];
        
        $obj_categories = $this->CategoryModel
                                        ->where('is_active',1)
                                        ->where('parent',0)
                                        ->get();

        if(isset($obj_categories) && sizeof($obj_categories)>0)
        {
            $arr_categories = $obj_categories->toArray();
        }

        $this->arr_view_data['page_title']      = "Create ".str_singular($this->module_title);
        $this->arr_view_data['module_title']    = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['arr_categories']  = $arr_categories;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {
        $arr_rules['category']       = "required";
        $arr_rules['subcategory']    = "required";
        $arr_rules['label']          = "required";
        $arr_rules['control_type']   = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Flash::error('Please confirm that all the mandatory fields are filled.');   
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }   
        
        $sort_order = $this->get_max_order($request->input('subcategory'));

        $arr_data = [];
        $arr_data['subcategory_id']     = $request->input('subcategory');
        $arr_data['label']              = $request->input('label');
        $arr_data['label_slug']         = str_slug($request->input('label'),'_');
        $arr_data['control_type']       = $request->input('control_type');
        $arr_data['order_index']        = $sort_order + 1;
        $arr_data['is_required']        = true; //$request->input('is_required');
        $arr_data['maxlength']          = $request->input('maxlength');
        $arr_data['minlength']          = $request->input('minlength');
        $arr_data['is_active']          = 1;

        $obj_question = $this->BaseModel->create($arr_data);

        if($obj_question)      
        {   
            /* storing options data if there option type 3 or  4 is selected */
            $control_type = $request->input('control_type');

            if( $control_type == 'CHECKBOX' || $control_type == 'RADIO' || $control_type == 'DROPDOWN' )
            {   
                if(count($request->input('control_options')) > 0)
                {
                    foreach ($request->input('control_options') as $key => $question) 
                    {
                        if($question != "")
                        {   
                            $arr_option = [];
                            $arr_option['control_attribute_id']  = $obj_question->id;
                            $arr_option['option_title']          = $question;
                            $arr_option['option_slug']           = str_slug($question,'_');

                            $this->ControlAttributeOptionModel->create($arr_option);
                        }   
                    }
                }
            }

            Flash::success(str_singular($this->module_title).' created successfully.');
        }
        else
        {
            Flash::error('Problem occured, while creating '.strtolower(str_singular($this->module_title)).'.');
        }

        return redirect()->back();
    }


    public function edit($enc_id)
    {
        $control_attribute_id = base64_decode($enc_id); 

        $obj_attributes = $this->BaseModel
                                    ->with('control_attribute_options','subcategory_details')
                                    ->whereHas('subcategory_details.parent_category', function () {})
                                    ->where('id', $control_attribute_id)
                                    ->first();

        $arr_attributes = [];

        if($obj_attributes)
        {
           $arr_attributes = $obj_attributes->toArray();
        }

        $arr_categories = [];
        $obj_categories = $this->CategoryModel
                                        ->where('is_active',1)
                                        ->where('parent',0)
                                        ->get();

        if(isset($obj_categories) && sizeof($obj_categories)>0)
        {
            $arr_categories = $obj_categories->toArray();
        }

        $this->arr_view_data['page_title']      = "Edit ".str_singular($this->module_title);
        $this->arr_view_data['module_title']    = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['enc_id']          = $enc_id;
        $this->arr_view_data['arr_attributes']  = $arr_attributes;  
        $this->arr_view_data['arr_categories']  = $arr_categories;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view($this->module_view_folder.'.edit',$this->arr_view_data);    
    }

    public function update(Request $request, $enc_id)
    {
        $control_attribute_id = base64_decode($enc_id);
        $arr_rules   = array();

        $arr_rules['category']       = "required";
        $arr_rules['subcategory']    = "required";
        $arr_rules['label']          = "required";
      
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            Flash::error( 'Please confirm that all the mandatory fields are filled.');   
            return back()->withErrors($validator)->withInput();
        }

        $obj_question = $this->BaseModel->where('id',$control_attribute_id)->first();

        if(!$obj_question)
        {
            Flash::error('Problem occured while updating '.strtolower(str_singular($this->module_title)).'.');
            return redirect()->back();   
        }
        
        $arr_data['subcategory_id']     = $request->input('subcategory');
        $arr_data['label']              = $request->input('label');
        $arr_data['maxlength']          = $request->input('maxlength');
        $arr_data['minlength']          = $request->input('minlength');
        
        $question_instance  = clone $obj_question;        
        $question_update    = $question_instance->update($arr_data);

        if ($question_update)
        {
            /* Deleting existing options and then again adding new options */
            $control_type = $request->input('control_type');

            $delete_options = $this->ControlAttributeOptionModel->where('control_attribute_id', '=' , $control_attribute_id)->delete();

            if(($delete_options == true) || $control_type == 'CHECKBOX' || $control_type == 'RADIO' || $control_type == 'DROPDOWN'  )
            {   
                if(count($request->input('control_options')) > 0)
                {
                    foreach ($request->input('control_options') as $key => $question) 
                    {
                        if($question != "")
                        {   
                            $arr_option = [];
                            $arr_option['control_attribute_id']  = $obj_question->id;
                            $arr_option['option_title']          = $question;
                            $arr_option['option_slug']           = str_slug($question,'_');
                            $this->ControlAttributeOptionModel->create($arr_option);
                        }   
                    }
                }
            }

            Flash::success(str_singular($this->module_title).' updated successfully.');    
        }

        return redirect()->back();
    }

    public function save_order(Request $request)
    {
        $attribute_id    = $request->input('attribute_id');
        $order_index     = $request->input('order_id');
        $subcategory_id  = $request->input('subcategory_id');

        $get_existing_order = $this->BaseModel
                                            ->where('id','<>','control_attribute_id')
                                            ->where('subcategory_id',$subcategory_id)
                                            ->select('order_index')
                                            ->get()
                                            ->toArray();
        /*Check if order index in a number */

        $check_number = is_numeric($order_index);
        if(!$check_number)
        {
            $data['status'] = "NUMERIC";
            $data['msg'] = "Please do not enter characters.";
            return response()->json($data);
            exit;
        }

        /* Check if orderindex is not duplicate */
        $flag = 0;
        foreach ($get_existing_order as $order) 
        {
            if($order['order_index'] == $order_index )
            {
                $flag++; 
            }
        }
        if($flag > 0)
        {
            $data['status'] = "DUPLICATE";
            $data['msg'] = "Please do not enter duplicate order.";
            return response()->json($data);
            exit;
        }

        $slider = $this->BaseModel->where('id',$attribute_id);
        $arr_update = array('order_index'=> $order_index);

        $status = $slider->update($arr_update); 
        if($status)
        {
            $data['status'] = "SUCCESS";
            $data['msg'] = "Order stored successfully.";
            return response()->json($data);
            exit;
        }
        else
        {
            $data['status'] = "ERROR";
            $data['msg'] = "Error while changing the order.";
            return response()->json($data);
            exit;
        }
    }

    public function get_max_order($subcategory_id)
    {
          $get_existing_order =  $this->BaseModel
                                      ->select('order_index')
                                      ->where('subcategory_id',$subcategory_id)
                                      ->get()
                                      ->toArray();

          $array_order = array();
          $max_order = 0;
          if(isset($get_existing_order) && count($get_existing_order) >0)
          {
                foreach ($get_existing_order as $order) 
                {
                    if(intval($order['order_index']) > $max_order)  
                    {
                        $max_order  = intval($order['order_index']);

                    }
                    
                }
          }
          return $max_order;
    }

    public function confirm_is_required(Request $request)
    {   
        $arr_json     = [];
        $arr_update   = [];
        $is_required     = $request->input('is_required');

        $control_attribute_id  = $request->input('attribute_id');
        $arr_update   = ['is_required'=>$is_required];
        $obj_update   = $this->ControlAttributeModel->where('id',$control_attribute_id)->update($arr_update);

        if($obj_update)
        {
           $arr_json['status'] = "success";
           $arr_json['msg']    = "Control attribute's compulsory status changed successfully.";
           return response()->json($arr_json);
        }
        else
        {
           $arr_json['status'] = "error";
           $arr_json['msg']    = "Error while changing control attribute's compulsory status.";
           return response()->json($arr_json);
        }
    }
}