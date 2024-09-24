<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SiteSettingModel;

use Validator;
use Flash;
use Input;
use Sentinel;
 
class SiteSettingController extends Controller
{
    
    public function __construct(
                                    SiteSettingModel $siteSetting
                                )
    {
        $this->SiteSettingModel   = $siteSetting;
        $this->arr_view_data      = [];
        $this->BaseModel          = $this->SiteSettingModel;
        
        $this->module_title       = "Social Links";
        $this->module_view_folder = "admin.site_settings";
        $this->module_url_path    = url(config('app.project.admin_panel_slug')."/social");
        $this->theme_color        = theme_color();

        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_img_path   = base_path().config('app.project.img_path.user_profile_images');
    }

    public function index()
    {
        $arr_data = array();   

        $obj_data =  $this->BaseModel->first();

        if($obj_data != FALSE)
        {
            $arr_data = $obj_data->toArray();    
        }

        $this->arr_view_data['arr_data']        = $arr_data;
        $this->arr_view_data['page_title']      = str_singular($this->module_title);
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;
        $this->arr_view_data['profile_image_public_img_path']     = $this->profile_image_public_img_path;
        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function update(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);

        $arr_rules = array();

        $arr_rules['fb_url']              = "required";  
        $arr_rules['google_plus_url']     = "required"; 
        $arr_rules['twitter_url']         = "required";  
        $arr_rules['pinterest_url']       = "required";  
        $arr_rules['instagram_url']       = "required";  

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return back()->withErrors($validator)->withInput();  
        } 

        $arr_data['fb_url']              = $request->input('fb_url');
        $arr_data['google_plus_url']     = $request->input('google_plus_url');
        $arr_data['twitter_url']         = $request->input('twitter_url');
        $arr_data['pinterest_url']       = $request->input('pinterest_url');
        $arr_data['instagram_url']       = $request->input('instagram_url');

        $entity = $this->BaseModel->where('site_setting_id',$id)->update($arr_data);

        if($entity)
        {   
            Flash::success(str_plural($this->module_title).' Updated Successfully'); 
        }
        else
        {
            Flash::error('Problem Occured, While Updating '.str_plural($this->module_title));  
        } 
      
        return redirect()->back()->withInput();
    }
}
