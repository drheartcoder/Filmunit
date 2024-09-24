<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TestimonialModel;  
use App\Common\Services\LanguageService;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Flash;
use Image;
use File;

class TestimonialController extends Controller
{
    use MultiActionTrait
    {
        delete as module_delete;
        multi_action as module_multi_action;
    }

    public function __construct(TestimonialModel $testimonial,LanguageService $langauge)
    {        
        $this->TestimonialModel            = $testimonial;
        $this->BaseModel                   = $this->TestimonialModel;
        $this->module_title                = "Testimonial";
        $this->LanguageService             = $langauge;
        $this->arr_view_data               = [];
        $this->module_url_path             = url(config('app.project.admin_panel_slug')."/testimonial");
        $this->module_view_folder          = "admin.testimonial";

        $this->testimonial_public_img_path = url('/').config('app.project.img_path.testimonial_images');
        $this->testimonial_base_img_path   = base_path().config('app.project.img_path.testimonial_images');

        $this->theme_color                 = theme_color();
        $this->module_icon                 = "fa-slideshare";
        $this->create_icon                 = "fa-plus-square-o";
        $this->edit_icon                   = "fa-edit";
        $this->view_icon                   = "fa-eye";
    }   
 
    public function index()
    {
        $arr_lang = array();
        $arr_data = array();
        $obj_data = $this->BaseModel->with('translations')->get();
        $arr_lang = $this->LanguageService->get_all_language();
       
        if(sizeof($obj_data)>0)
        {
            foreach ($obj_data as $key => $data) 
            {
                $arr_tmp = array();
                // Check Language Wise Transalation Exists
                foreach ($arr_lang as $key => $lang) 
                {
                    $arr_tmp[$key]['title']     = $lang['title'];
                    $arr_tmp[$key]['is_avail']  = $data->hasTranslation($lang['locale']);
                }    
                    $data->arr_translation_status = $arr_tmp;
        			// Call to hasTranslation method of object is triggering translations so need to unset it 
                    unset($obj_data->translations);
            }
        }


        if($obj_data != FALSE)
        {
            $arr_data = $obj_data->toArray();        
            
            foreach ($arr_data as $key => $value) 
            {
                if(isset($value['translations']) && sizeof($value['translations'])>0)
                {
                    $value['translations'] = $this->arrange_locale_wise($value['translations']); 
                }

                $arr_data[$key]['translations'] = $value['translations'];
            }

        }

        
        
        $this->arr_view_data['arr_data']                    = $arr_data;
        $this->arr_view_data['page_title']                  = "Manage Testimonial";
        $this->arr_view_data['module_title']                = "Testimonial";
        $this->arr_view_data['module_url_path']             = $this->module_url_path;
        $this->arr_view_data['testimonial_public_img_path'] = $this->testimonial_public_img_path;
        $this->arr_view_data['testimonial_base_img_path']   = $this->testimonial_base_img_path;
        $this->arr_view_data['module_icon']                 = $this->module_icon;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }


    public function create()
    {
        $this->arr_view_data['arr_lang']        = $this->LanguageService->get_all_language();  
        $this->arr_view_data['page_title']      = "Create Testimonial";
        $this->arr_view_data['module_title']    = "Testimonial";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['module_icon']     = $this->module_icon;
        $this->arr_view_data['create_icon']     = $this->create_icon;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {  
    	$form_data = array();

        /* English Required */
        $arr_rules['user_name_en']   = "required";        
        //$arr_rules['user_post_en']   = "required"; 
        $arr_rules['description_en'] = "required";   
        $arr_rules['user_image']     = "required";          
         
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }   

        $form_data = $request->all();
       
        /* Check if location already exists with given translation */
        $does_exists = $this->BaseModel
                            ->whereHas('translations',function($query)use($form_data)
                                        {
                                            $query->where('locale','en')
                                                  ->where('user_name',$form_data['user_name_en']);      
                                        })
                            ->count();   
                      
        if($does_exists)
        {
            Flash::error(str_singular($this->module_title).' Already Exists.');
            return redirect()->back()->withInput($request->all());
        }

        $files    = $request->file('user_image');

        $tmp_file = [];
        
        array_push($tmp_file, $files);

        $file_type_validated = $this->validate_file_type($tmp_file);

        
        if($file_type_validated == false)
        {
            Flash::error("Please upload valid image file.");
            return redirect()->back()->withInput($request->all());
        }

        /* Insert into Location Table */
        /*File uploading code start here*/

	        $form_data      = $request->all();
	        $file_name      = "default.jpg";
	        $file_name      = $form_data['user_image'];

	        $file_extension = strtolower($request->file('user_image')->getClientOriginalExtension());
	        $file_name      = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
	        $request->file('user_image')->move($this->testimonial_base_img_path, $file_name); 

        /*File uploading code End here*/
        $arr_data = array();
        $arr_data['is_active'] = 1; 
        $arr_data['user_image'] = $file_name;
        // dd($arr_data);
        $entity = $this->BaseModel->create($arr_data);

        if($entity)      
        {
            $arr_lang =  $this->LanguageService->get_all_language();      
         
            /* insert record into translation table */
            if(sizeof($arr_lang) > 0 )
            {
                foreach ($arr_lang as $lang) 
                {            
                    $arr_data = array();
                    $user_name   = $request->input('user_name_'.$lang['locale']);
                    //$user_post   = $request->input('user_post_'.$lang['locale']);
                    $description = $request->input('description_'.$lang['locale']);

                    if( (isset($user_name) && $user_name != '') && (isset($description) && $description != '') )
                    { 
                        $translation = $entity->translateOrNew($lang['locale']);
                        $translation->testimonial_id    = $entity->id;
                        $translation->user_name  		= $user_name;
                        //$translation->user_post       = $user_post;
                        $translation->description       = $description;
                        $translation->save();
                        Flash::success(str_singular($this->module_title).'Created Successfully');
                    }
                }//foreach 

            } //if
            else
            {
                Flash::error('Problem Occured, While Creating '.str_singular($this->module_title));
            }
        }

        if($entity)
        {
            $enc_id = base64_encode($entity->id);
            Flash::success(str_singular($this->module_title).'Created Successfully');
            return redirect($this->module_url_path);
        }
        else
        {    
            return redirect()->back();
        }
    }
    
    public function edit($enc_id)
    {
        $id       = base64_decode($enc_id);
        $arr_lang = $this->LanguageService->get_all_language();
        $obj_data = $this->BaseModel->where('id', $id)->with(['translations'])->first();
        $arr_data = [];

        if($obj_data)
        {
           $arr_data = $obj_data->toArray();
           /* Arrange Locale Wise */
           $arr_data['translations'] = $this->arrange_locale_wise($arr_data['translations']);
        }

        $this->arr_view_data['edit_mode']                   = TRUE;
        $this->arr_view_data['enc_id']                      = $enc_id;
        $this->arr_view_data['arr_lang']                    = $this->LanguageService->get_all_language();
        $this->arr_view_data['arr_data']                    = $arr_data;
        $this->arr_view_data['page_title']                  = "Edit Testimonial";
        $this->arr_view_data['module_title']                = "Testimonial";
        $this->arr_view_data['module_url_path']             = $this->module_url_path;
        $this->arr_view_data['testimonial_public_img_path'] = $this->testimonial_public_img_path;
        $this->arr_view_data['testimonial_base_img_path']   = $this->testimonial_base_img_path;
        $this->arr_view_data['module_icon']                 = $this->module_icon;
        $this->arr_view_data['edit_icon']                   = $this->edit_icon;

        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }


    public function update(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);

        $arr_rules = array();
        /* English Required */
        $arr_rules['user_name_en']   = "required";        
        //$arr_rules['user_post_en']   = "required"; 
        $arr_rules['description_en'] = "required";   
        //$arr_rules['user_image']     = "required";  

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $form_data = array();
        $form_data = $request->all();

        /* Get All Active Languages */ 
        $arr_lang = $this->LanguageService->get_all_language();  

        /* Retrieve Existing FAQ*/
        $entity = $this->BaseModel->where('id',$id)->first();

        if(!$entity)
        {
            Flash::error('Problem Occured While Retriving '.str_singular($this->module_title));
            return redirect()->back();   
        }

        /* Check if location type already exists with given translation */
        $does_exists = $this->BaseModel
                            ->where('id','<>',$id)
                            ->whereHas('translations',function($query) use($form_data)
                                        {
                                            $query->where('locale','en')
                                                  ->where('user_name',$form_data['user_name_en']);      
                                        })
                            ->count()>0;   
        if($does_exists)
        {
            Flash::error(str_singular($this->module_title).' Already Exists');
            return redirect()->back();
        }

        /*  File uploading code starts here  */
        $is_new_file_uploaded = FALSE;
        
        $file_name = '';
        
        if ($request->hasFile('user_image')) 
        {
            $files    = $request->file('user_image');

            $tmp_file = [];
            
            array_push($tmp_file, $files);

            $file_type_validated = $this->validate_file_type($tmp_file);

            
            if($file_type_validated == false)
            {
                Flash::error("Please upload valid image file.");
                return redirect()->back()->withInput($request->all());
            }


            $file_name 		 = $form_data['user_image'];
            $fileExtension   = strtolower($request->file('user_image')->getClientOriginalExtension());
            $file_name       = sha1(uniqid().$file_name.uniqid()).'.'.$fileExtension;

            $request->file('user_image')->move($this->testimonial_base_img_path, $file_name);
           
           
            @unlink('uploads/testimonial_images/'.$entity->user_image);
           // @unlink('uploads/testimonial_images/thumb_107x107_'.$entity->user_image);

            $is_new_file_uploaded = TRUE;   
        }

        if($is_new_file_uploaded)
        {
            $arr_update['user_image']        = $file_name;
            $this->BaseModel->where('id',$id)->update($arr_update);
        }
        /* Insert Multi Lang Fields */

        if(sizeof($arr_lang) > 0)
        { 
            foreach($arr_lang as $i => $lang)
            {
                $user_name   = $request->input('user_name_'.$lang['locale']);
                //$user_post   = $request->input('user_post_'.$lang['locale']);
                $description = $request->input('description_'.$lang['locale']);


                if( (isset($user_name) && $user_name != '') && (isset($description) && $description != '') )
                {
                    /* Get Existing Language Entry */
                    $translation = $entity->getTranslation($lang['locale']);    
                    if($translation)
                    {
                        $translation->user_name      = $user_name;
                        // $translation->user_post    	 = $user_post;
                        $translation->description    = $description;
                        $translation->save();    
                    }  
                    else
                    {
                        /* Create New Language Entry  */
                        $translation = $entity->getNewTranslation($lang['locale']);
                        $translation->testimonial_id    = $id;
                        $translation->user_name      	= $user_name;
                        // $translation->user_post    	 	= $user_post;
                        $translation->description    	= $description;
                        $translation->save();
                    } 
                }   
            }
            
        }

        Flash::success(str_singular($this->module_title).' Updated Successfully');
          return redirect()->back(); 
    }

    public function arrange_locale_wise(array $arr_data)
    {
        if(sizeof($arr_data)>0)
        {       
            foreach ($arr_data as $key => $data) 
            {
                $arr_tmp = $data;
                unset($arr_data[$key]);

                $arr_data[$data['locale']] = $data;                    
            }
            return $arr_data;
        }
        else
        {
            return [];
        }
    }

    /*-----------------------------------------------------------
    | Check file mimetype validation
    -------------------------------------------------------------*/
    public function validate_file_type($files)
    {
        $arr_mimetypes = [
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/x-png'
                        ];

        if(count($files) > 0)
        {
            foreach($files as $key => $file)
            {
                if($file != NULL && is_file($file))
                {
                    $mimetype = strtolower($file->getClientMimeType());
                    if(!in_array($mimetype, $arr_mimetypes))
                    {
                        return false;
                    }
                }   
            }
        }
        return true;
    }
    /*-----------------------------------------------------------
    | Ends
    -------------------------------------------------------------*/

     public function attachmentThumb($input, $name, $width, $height)
    {
        $thumb_img = Image::make($input)->resize($width,$height);
        $thumb_img->fit($width,$height, function ($constraint) {
            $constraint->upsize();
        });
        $thumb_img->save('uploads/testimonial_images/thumb_'.$width.'X'.$height.'_'.$name);
         
    }

    public function delete($enc_id=false)
    {
        $id  = base64_decode($enc_id);

        $arr_data = [];
        $obj_data = $this->BaseModel->where('id',$id)->first();
        if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }   
        
        $image_name = isset($arr_data['user_image'])?$arr_data['user_image']:'';

        if(File::exists($this->testimonial_base_img_path.$image_name))
        {
            @unlink($this->testimonial_base_img_path.$image_name);
        }

        return $this->module_delete($enc_id);
    }

    public function multi_action(Request $request)
    {
        $arr_id   = [];
        $arr_data = [];
        $arr_id   = $request->input('checked_record');
        
        if($request->input('multi_action') == 'delete')
        {
            foreach ($arr_id as $id)
            {
                $id = base64_decode($id);
                $obj_data = $this->BaseModel->where('id',$id)->first();
                if($obj_data)
                {
                    $arr_data = $obj_data->toArray();
                }

                $image_name = isset($arr_data['user_image'])?$arr_data['user_image']:'';
                if(File::exists($this->testimonial_base_img_path.$image_name))
                {
                    @unlink($this->testimonial_base_img_path.$image_name);
                }
            }
        }
        return $this->module_multi_action($request);
    }
}
