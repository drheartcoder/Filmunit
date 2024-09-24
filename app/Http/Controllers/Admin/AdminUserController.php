<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\EmailService;
use App\Common\Traits\MultiActionTrait;

use App\Models\UserModel;
use App\Models\ModulesModel;

use Validator;
use Session;
use Sentinel;
use Flash;
use Mail;
use URL;


class AdminUserController extends Controller
{
    use MultiActionTrait;

    public function __construct(
                                UserModel $user,
                                EmailService $mail_service,
                                ModulesModel $modules
                                ) 
    {
        $this->BaseModel          = $user;
        $this->EmailService       = $mail_service;
        $this->ModulesModel       = $modules;
        $this->arr_view_data      = [];
        $this->admin_url_path     = url(config('app.project.admin_panel_slug'));

        $this->admin_panel_slug   = config('app.project.admin_panel_slug');
        $this->module_title       = "Admin Users";
        $this->module_view_folder = "admin.admin_users";
        $this->module_url_path    = $this->admin_url_path."/admin_users";

        $this->obj_data           = Sentinel::getUser();
        $this->first_name         = $this->obj_data->first_name;
        $this->last_name          = $this->obj_data->last_name;
        
        $this->theme_color        = theme_color();
    }

    public function index()
    {
        $arr_users = array();
        $obj_users = Sentinel::createModel()->whereHas('roles',function($query)
                                                {
                                                   return $query->whereIn('slug',['subadmin']);
                                                })->orderBy('id','DESC')->get();
        
        
        $is_last_user = count($obj_users)==1?true:false;

        $this->arr_view_data['is_last_user']    = $is_last_user;
        $this->arr_view_data['obj_users']       = $obj_users;
        $this->arr_view_data['page_title']      = "Manage ".str_singular( $this->module_title);
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;

        return view($this->module_view_folder.'.index',$this->arr_view_data);

    }

    public function create()
    {
        $obj_role = Sentinel::getRoleRepository()->createModel()->whereIn('slug',['subadmin']);
        $obj_role = $obj_role->orderBy('id','desc')->get(); 

        
        $obj_modules    =   $this->ModulesModel
                            ->where('is_active','1')
                            ->orderBy('title','ASC')
                            ->get();

        if($obj_modules != FALSE)
        {
            $arr_modules=$obj_modules->toArray();
        }

        if( $obj_role != FALSE)
        {
            $arr_roles = $obj_role->toArray();

        }

        $this->arr_view_data['arr_roles']       = $arr_roles;
        $this->arr_view_data['arr_modules']       = $arr_modules;
        $this->arr_view_data['page_title']      = "Create ".str_singular( $this->module_title);
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {

       

    	$arr_rules = [];

        $arr_rules['first_name'] = "required";
        $arr_rules['last_name']  = "required";
        $arr_rules['email']      = "required";
        $arr_rules['password']   = "required|confirmed";

    	$validator = Validator::make($request->all(),$arr_rules);
    	
    	if($validator->fails())
    	{
    		return redirect()->back()->withErrors($validator)->withInput($request->all());
    	}

    	/* Duplication Check */
    	$is_duplicate = Sentinel::createModel()->where('email',$request->input('email'))
                                               ->count();

    	if($is_duplicate>0)
    	{
    		Flash::error(str_singular($this->module_title).' Already Exists.');
    		return redirect()->back()->withInput($request->all());
    	}

        

        $arr_data               = [];
        $arr_data['first_name'] = $request->input('first_name');
        $arr_data['last_name']  = $request->input('last_name');
        $arr_data['email']      = $request->input('email');
        $arr_data['password']   = $request->input('password');
        $arr_data['is_active']  = 1;
       
    	$user = Sentinel::registerAndActivate($arr_data);
    	
        $insertedId = $user->id;

        $permission_arr =[];
        
        $permissions    =   $request->input('arr_permisssion');

        if(isset($permissions) && sizeof($permissions)>0)
        {
            if(isset($permissions['subadmin']) && sizeof($permissions['subadmin'])>0)
            {
              $permission_arr    =   $permissions['subadmin'];
            }
        }


       
       // dd($permission_arr);


        $obj_user = Sentinel::findById($insertedId);
        $obj_user->permissions = $permission_arr;
        $obj_user->save();



    	$arr_roles = $request->input('roles');
    	
    	if(sizeof($arr_roles)>0)
    	{
    		foreach ($arr_roles as $key => $id) 
    		{
    			$role = Sentinel::findRoleById($id);
    			$role->users()->attach($user);
    		}
    	}
    	

         $arr_mail_data = $this->built_mail_data($arr_data); 
         $email_status  = $this->EmailService->send_mail($arr_mail_data);
  

    	if($user)
    	{
           Flash::success(str_singular($this->module_title).' Created Successfully');
    	}
    	else
    	{
    		Flash::error('Problem Occurred, While Creating '.str_singular($this->module_title));
    	}

    	return redirect()->back();
    }

    public function built_mail_data($arr_data)
    {
        if(isset($arr_data) && sizeof($arr_data)>0)
        {
            $login_url = '<p class="email-button"><a target="_blank" href="'.URL::to($this->admin_panel_slug).'">Click Here</a></p><br/>' ;

            $arr_built_content = [
                                  'FIRST_NAME'       => $arr_data['first_name'],
                                  'LAST_NAME'        => $arr_data['last_name'],
                                  'EMAIL'            => $arr_data['email'],
                                  'PASSWORD'         => $arr_data['password'],
                                  'ADMIN_LOGIN_URL'  => $login_url,
                                  'PROJECT_NAME'     => config('app.project.name')];


            $arr_mail_data                      = [];
            $arr_mail_data['email_template_id'] = '3';
            $arr_mail_data['arr_built_content'] = $arr_built_content;
            $arr_mail_data['user']              = $arr_data;

            return $arr_mail_data;
        }
        return FALSE;
    }

  

    public function edit($enc_id)
    {
    	$id = base64_decode($enc_id);

    	$obj_user = Sentinel::findById($id);
    	$obj_role = Sentinel::getRoleRepository()->createModel()->whereIn('slug',['subadmin']);
        $obj_role = $obj_role->orderBy('id','desc')->get();	

        if( $obj_role != FALSE)
        {
            $arr_roles = $obj_role->toArray();
        }

        $obj_modules    =   $this->ModulesModel
                            ->where('is_active','1')
                            ->orderBy('title','ASC')
                            ->get();

        if($obj_modules != FALSE)
        {
            $arr_modules=$obj_modules->toArray();
        }


        $arr_user = [];
    	if($obj_user)
    	{
    		$arr_tmp = $obj_user->roles->toArray();
    		$arr_assigned_roles = array_column($arr_tmp,'id');
    	}

        $this->arr_view_data['edit_mode']          = TRUE;
        $this->arr_view_data['enc_id']             = $enc_id;
        $this->arr_view_data['arr_assigned_roles'] = $arr_assigned_roles;
        $this->arr_view_data['arr_roles']          = $arr_roles;
        $this->arr_view_data['arr_modules']        = $arr_modules;
        $this->arr_view_data['obj_user']           = $obj_user;
        $this->arr_view_data['page_title']         = "Edit ".str_singular($this->module_title);
        $this->arr_view_data['module_title']       = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']    = $this->module_url_path;
        $this->arr_view_data['theme_color']        = $this->theme_color;

        return view($this->module_view_folder.'.edit', $this->arr_view_data);    

    }

    public function update(Request $request,$enc_id)
    {	
    	$id = base64_decode($enc_id);
    	
    	$arr_rules = [];
    	$arr_rules['first_name'] = "required";
    	$arr_rules['last_name'] = "required";
    	$arr_rules['email'] = "required";
    	

    	$validator = Validator::make($request->all(),$arr_rules);
    	if($validator->fails())
    	{
    		return redirect()->back()->withErrors($validator)->withInput($request->all());
    	}

    	/* Duplication Check */
    	$is_duplicate = Sentinel::createModel()
    						    ->where('email',$request->input('email'))
    						    ->where('id',"<>",$id)
    						    ->count();

    	if($is_duplicate>0)
    	{
    		Flash::error(str_singular($this->module_title).' Already Exists.');
    		return redirect()->back()->withInput($request->all());
    	}


    	$permission_arr =[];
        
        $permissions    =   $request->input('arr_permisssion');

        if(isset($permissions) && sizeof($permissions)>0)
        {
            if(isset($permissions['subadmin']) && sizeof($permissions['subadmin'])>0)
            {
              $permission_arr    =   $permissions['subadmin'];
            }
        }


       
       // dd($permission_arr);


        $obj_user = Sentinel::findById($id);
        $obj_user->permissions = $permission_arr;
        $obj_user->save();


    	$arr_data['first_name']    = $request->input('first_name');
    	$arr_data['last_name']     = $request->input('last_name');
        $arr_data['email']         = $request->input('email');
    	$arr_data['permissions']   = $permission_arr;
    	

    	if($request->has('password'))
    	{
    		$arr_data['password']      = $request->input('password');	
    	}

        $obj_user = Sentinel::findById($id);
    	$obj_user = Sentinel::update($obj_user, $arr_data);


    	$arr_roles = $request->input('roles');
    	
    	if(sizeof($arr_roles)>0)
    	{
    		foreach ($arr_roles as $key => $id) 
    		{
    			$role = Sentinel::findRoleById($id);

    			if(!$obj_user->inRole($role))
    			{
    				$role->users()->attach($obj_user);	
    			}
    		}
    	}

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
    
   
}
