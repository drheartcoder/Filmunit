<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SiteSettingModel;


use Validator;
use Flash;
use Sentinel;
use Hash;
use Image;

class AccountSettingsController extends Controller
{

    public function __construct(
                                UserModel $user,
                                SiteSettingModel $siteSetting
                               )
    {
        $this->UserModel          = $user;
        $this->BaseModel          = $this->UserModel;
        $this->SiteSettingModel   = $siteSetting;
        
        $this->arr_view_data      = [];
        $this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/account_settings";

        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_img_path   = base_path().config('app.project.img_path.user_profile_images');

        $this->module_title       = "Account Settings";
        $this->module_view_folder = "admin.account_settings";
        
        $this->theme_color        = theme_color();

        $this->module_icon        = "fa-cogs";

        $this->user_id = false;
        $user = Sentinel::check();
        if($user)
        {
            $this->user_id = $user->id;
        }
    }

    public function index()
    {
        $arr_account_settings = array();
        $obj_data  = $obj_site_setting_data = false;
        $arr_data  = $arr_site_setting_data = [];
        
        $obj_site_setting_data = $this->SiteSettingModel->first();

        $obj_data  = Sentinel::getUser();
        
        if($obj_data!=false)
        {
           $arr_data = $obj_data->toArray();    
        }

        if($obj_site_setting_data!=false)
        {
           $arr_site_setting_data = $obj_site_setting_data->toArray();    
        }

        if(isset($arr_data) && sizeof($arr_data)<=0)
        {
            return redirect($this->admin_url_path.'/login');
        }

        $this->arr_view_data['arr_data']                          = $arr_data;
        $this->arr_view_data['arr_site_setting_data']             = $arr_site_setting_data;
        $this->arr_view_data['page_title']                        = str_plural($this->module_title);
        $this->arr_view_data['module_title']                      = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']                   = $this->module_url_path;
        $this->arr_view_data['admin_url_path']                    = $this->admin_url_path;
        $this->arr_view_data['theme_color']                       = $this->theme_color;
        $this->arr_view_data['module_icon']                       = $this->module_icon;
        $this->arr_view_data['profile_image_public_img_path']     = $this->profile_image_public_img_path;


        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }
 
    /*------------------------------- Update Profile --------------------------------*/
    public function update_profile(Request $request)
    {
        $arr_rules = array();
        
        $arr_rules['first_name']            = "required";
        $arr_rules['last_name']             = "required"; 
        $arr_rules['email']                 = "email|required";
        $arr_rules['contact_number']        = "required|min:7|max:16";
        $arr_rules['address']               = "required";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        
        if($this->UserModel->where('email',$request->input('email'))
                           ->where('id','!=',$this->user_id)
                           ->count()==1)
        {
            Flash::error('This Email id already present in our system, please try another one');
            return redirect()->back();
        }
        
        $oldImage = $request->input('oldimage');
        
        if($request->hasFile('image'))
        {
            $file_name      = $request->input('image');
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());
            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file_name  = time().uniqid().'.'.$file_extension;
                $isUpload   = $request->file('image')->move($this->profile_image_base_img_path , $file_name);
                if($isUpload)
                {
                    @unlink($this->profile_image_base_img_path.$oldImage);
                    @unlink($this->profile_image_base_img_path.'/thumb_50X50_'.$oldImage);
                    $res= $this->attachmentThumb(file_get_contents($this->profile_image_base_img_path.$file_name), $file_name, 50, 50);
                }
            }
            else
            {
                Flash::error('Invalid File type, While creating Admin profile.');
                return redirect()->back();
            }
        }
        else
        {
             $file_name=$oldImage;
        }

        $arr_data['profile_image'] = $file_name;
        $arr_data['first_name']    = trim($request->input('first_name'));
        $arr_data['last_name']     = trim($request->input('last_name'));
        $arr_data['email']         = $request->input('email');
        $arr_data['contact_number']= $request->input('contact_number');
        $arr_data['fax']           = $request->input('fax');
        $arr_data['address']       = $request->input('address');

        $obj_data = $this->BaseModel->where('id',$this->user_id)->update($arr_data);
        if($obj_data)
        {
            Flash::success('Admin profile Updated Successfully'); 
        }
        else
        {
            Flash::error('Problem Occurred, While Updating Admin profile.');  
        } 
      
        return redirect()->back();
    }

    /*------------------------------- Update Site Settings --------------------------------*/
     public function update_site_setting(Request $request)
    {
        $obj_data   = false;
        $arr_rules  = [];
        $arr_data   = [];
        
        $arr_rules['site_status']  = "required";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }

        $arr_data['site_status']  = $request->input('site_status');
        
        $obj_data = $this->SiteSettingModel->where('site_setting_id',1)->update($arr_data);
        
        if($obj_data!=false)
        {
            Flash::success('Site settings Updated Successfully'); 
        }
        else
        {
            Flash::error('Problem Occurred, While Updating Site settings.');  
        } 
        return redirect()->back();
    }

    /*------------------------------- Attach Thumb --------------------------------*/
     public function attachmentThumb($input, $name, $width, $height)
    {
        $thumb_img = Image::make($input)->resize($width,$height);
        $thumb_img->fit($width,$height, function ($constraint) {
            $constraint->upsize();
        });
        $thumb_img->save($this->profile_image_base_img_path.'/thumb_'.$width.'X'.$height.'_'.$name);

         
    }

    /*------------------------------- Update Site Emails --------------------------------*/
     public function update_site_emails(Request $request)
    {
        $obj_data   = false;
        $arr_rules  = [];
        $arr_data   = [];
        
        $arr_rules['info_email_address']     = "email|required";
        $arr_rules['sales_email_address']    = "email|required";
        $arr_rules['billing_email_address']  = "email|required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }

        $arr_data['info_email_address']     = $request->input('info_email_address');
        $arr_data['sales_email_address']    = $request->input('sales_email_address');
        $arr_data['billing_email_address']  = $request->input('billing_email_address');
        
        $obj_data = $this->SiteSettingModel->where('site_setting_id',1)->update($arr_data);
        
        if($obj_data!=false)
        {
            Flash::success('Admin Emails Updated Successfully'); 
        }
        else
        {
            Flash::error('Problem Occurred, While Updating Admin Emails.');  
        } 
        return redirect()->back();
    }

    /*------------------------------- Update Site Contact Details --------------------------------*/
     public function update_site_contact_details(Request $request)
    {
        $obj_data   = false;
        $arr_rules  = [];
        $arr_data   = [];
        
        $arr_rules['site_contact_number']     = "required|min:7|max:16";
        $arr_rules['site_address']            = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }

        $arr_data['site_contact_number']  = $request->input('site_contact_number');
        $arr_data['site_address']         = $request->input('site_address');
        
        $obj_data = $this->SiteSettingModel->where('site_setting_id',1)->update($arr_data);
        
        if($obj_data!=false)
        {
            Flash::success('Admin Contact Details Updated Successfully'); 
        }
        else
        {
            Flash::error('Problem Occurred, While Updating Admin Contact Details.');  
        } 
        return redirect()->back();
    }

}
