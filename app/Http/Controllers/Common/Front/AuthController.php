<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Services\EmailService;
use App\Common\Services\RoleService;
use App\Common\Services\CommonDataService;

use App\Models\EmailTemplateModel;
use App\Models\Usermodel;
use App\Models\NotificationModel;
use App\Models\CartModel;

use Sentinel;
use Activation;
use Hash;
use Mail;	
use Session;
use Reminder;
use Socialize;
use Validator;
use Flash;
use Image;
use Cartalyst\Sentinel\Hashing\NativeHasher;

class AuthController extends Controller
{
		public function __construct(UserModel $user_model,
								NotificationModel $notify,
								EmailTemplateModel $email_template,
								CartModel $cart_model,
								EmailService $mail_service,
								RoleService $role_service,
                                NativeHasher $NativeHasher,
                                CommonDataService $commondataservice)
		{
			$this->UserModel 		  	  = $user_model;
			$this->CartModel 		  	  = $cart_model;
			$this->NotificationModel      = $notify;
			$this->EmailTemplateModel 	  = $email_template;

			$this->EmailService       	  = $mail_service;
			$this->RoleService        	  = $role_service;
			$this->CommonDataService      = $commondataservice;
      		$this->NativeHasher 		  = $NativeHasher;
			
			$this->buyer_role_slug 		  = 'buyer';
			$this->seller_role_slug 	  = 'seller';
			$this->module_view_folder     = 'front.common';
			$this->module_title			  = config('app.project.name');
	        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
	        $this->profile_image_base_img_path   = base_path().config('app.project.img_path.user_profile_images');			
			$this->arr_view_data          = [];

		}
/*------------------------ Registration process ----------------------------*/
	public function store_user(Request $request)
 	{
	    $arr_response = [];
		$arr_rules    = [];
		$status       = false;

		$status = $this->validate_store_user($request);

		if($status==false)
		{
			Flash::error("This email Id is already registered.");
        	return redirect()->back();
		}

        $arr_rules['user_type']           = "required";
        $arr_rules['first_name']          = "required|min:3|max:25"; 
        $arr_rules['last_name']           = "required|min:3|max:25";
        $arr_rules['email']        		  = "required|max:75";
        $arr_rules['password']            = "required|min:6|max:20";
        $arr_rules['c_password']          = "required|min:6|max:20";
        $arr_rules['terms']               = "required";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }

	    $arr_data             	     = [];
	    $arr_data['role']  	         = $request->input('user_type') ;
		$arr_data['first_name'] 	 = $request->input('first_name') ;
		$arr_data['last_name']  	 = $request->input('last_name') ;
		$arr_data['email'] 	 		 = $request->input('email') ;
		$arr_data['password']   	 = $request->input('password') ;
		$arr_data['terms']   	 	 = $request->input('terms') ;
	    $arr_data['is_block'] 	     = '0';
	    $is_activate            	 = '0';

	  	$user_status = $this->register($arr_data,$is_activate);  // register method

	  	if($user_status)
        {   
            $id   			 = $user_status->id;
            $user 			 = Sentinel::findById($id);
        	$activation 	 = Activation::create($user);   	/* Create avtivation */
            $activation_code = $activation->code; 				// get activation code
            $email_id        = $request->input('email');

            $data['id'] 		     = $id;
            $data['role'] 		     = $arr_data['role'];
            $data['activation_code'] = $activation_code;
            $data['first_name'] 	 = $request->input('first_name');
            $data['email']           = $request->input('email');

            $arr_mail_data   = $this->send_regular_registration_mail($data, $activation_code);
            $email_status    = $this->EmailService->send_mail($arr_mail_data);

            // Admin notification
			$arr_notification_data_admin 				 = [];
			$send_notification_admin     	    		 = false;
            $arr_notification_data_admin['from_user_id'] = 0;
            $arr_notification_data_admin['to_user_id']   = 1;
            $arr_notification_data_admin['message']      = 'Hello! '.$arr_data['first_name'].' '.$arr_data['last_name'].' has been registered as '.$arr_data['role'].' <a target="_new" href=" '.url('/').'/admin/users/view/'.base64_encode($id).'">View</a>';;

             /*= ' Hello! Your request for My Photos and Footage has been rejected due to : '.$rejection_note.' <a target="_new" href=" '.url('/').'/seller/photos_and_footage/view/'.base64_encode($id).'">View</a>';*/


            $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);
                		
    		return redirect(url('/registration_success'));
        }
        else
        {
	    	Flash::error("Problem occuered while registration. Please try again.");
        	return redirect()->back();
        }
 	}

/*------------------------ Check if user already exists for signup ----------------------------*/
 	public function validate_store_user($request)
 	{
 		$email 		  = '';
 		$check_status = false;
 		
 		$email = $request->input('email');

 		if($email!='')
 		{
 			$check_status = $this->UserModel->where('email',$email)->first();
 			if(count($check_status)<=0)
 			{
 				return true;
 			}
 			else
 			{
 				return false;
 			}
 		}
 		return false;
 	}

/*------------------------ Check email duplication for ajax request while signup ----------------------------*/
 	public function check_email_duplication(Request $request)
 	{
 		$check_status = false;
 		
 		if($request->has('email'))
 		{
 			$email = $request->input('email');
 			$check_status = $this->UserModel->where('email',$email)->first();
 			if(count($check_status)>0)
 			{
 				return 'true';
 			}
 			else
 			{
 				return 'false';
 			}
 		}
 		return 'false';
 	}

/*------------------------ register init function ----------------------------*/
 	public function register($arr_data , $via_social = FALSE , $is_activate=FALSE)  // common register method
  	{	
        $user_status = Sentinel::register($arr_data);

    	$user = Sentinel::findById($user_status->id);

        /* Attaching both Roles to user */
		$role = Sentinel::findRoleBySlug($arr_data['role']);
		$user->roles()->attach($role);

		return $user_status; 
  	}

/*------------------------ Send mail after registeration ----------------------------*/
  	public function send_regular_registration_mail($arr_data,$reminder_code)
    {
        // Retrieve Email Template 
        $obj_email_template = $this->EmailTemplateModel->where('id','2')->first();
        if($obj_email_template)
        {

        	$activation_url = url('/').'/verify/'.base64_encode($arr_data['id']).'/'.$reminder_code;

	        $arr_built_content = ['NAME'       		 => $arr_data['first_name'],
	                              'EMAIL'            => $arr_data['email'],
	                              'ACTIVATION_URL'   => $activation_url,
	                              'PROJECT_NAME'     => config('app.project.name')];

	        $arr_mail_data                        = [];
	        $arr_mail_data['email_template_id']   = '2';
	        $arr_mail_data['arr_built_content']   = $arr_built_content;
	        $arr_mail_data['user']                = ['email' => $arr_data['email'], 'first_name' => $arr_data['first_name']];

	        return $arr_mail_data;
        }  
    }

/*------------------------ Email verification process ----------------------------*/
    public function verify($user_id, $activation_code)
    {
    	$verify_user = false;
    	$arr_tmp     = [];
        $id = base64_decode($user_id);
        $activation_code = $activation_code;

        $user = Sentinel::findById($id);

        $activation = Activation::exists($user); // check if activation aleady done ...
        if($activation) // if activation is done
        {	 
            if (Activation::complete($user, $activation_code)) // complete an activation process
            {
                $tmp_user = $this->UserModel->where('id',$id)->first();
                if($tmp_user)
                {
                	$arr_tmp = ['is_active'=>1];

                    $tmp_user->is_active = 1;
                    
                    $tmp_user->save();
                    
                    $verify_user = $this->UserModel->where('id',$id)->update($arr_tmp);    
                }

                Flash::success('Activation successful. Please login to your account.');
        		return redirect(url('/login'));
            }
            else
            {
                Flash::error('Activation failed. Activation not found or not complete.');
        		return redirect(url('/login'));
            }    
        }
        else // if user is trying activation first time ...
        {
            Flash::success('Your account is already activated.');
        	return redirect(url('/login'));
        }
    }

/*------------------------ Process for login request ----------------------------*/
    public function process_login(Request $request)
	{
		$back_url     = '';
		$arr_rules    = [];
        
        $arr_rules['email']        		  = "required|max:75";
        $arr_rules['password']            = "required|min:6|max:20";

       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }

		$social_login = $redirect_from = false;
		$remember_me  = null;
		$arr_json     = $arr_rule     = [];

		$arr_credentials 			  = [];
		$arr_credentials['password']  = $request->input('password');
		$arr_credentials['email']     = $request->input('email');

		//Check Back Url
		if($request->input('b_url') && $request->input('b_url')!='')
		{
			$back_url = $request->input('b_url');	
		}
		/*$arr_credentials['user_type'] = $request->input('user_type'); */

		$remember_me = $request->input('remember_me');
		$remember_me = isset($remember_me) && $remember_me != "" ? $remember_me : null;

		return $this->init_login($arr_credentials, true, $remember_me, $social_login, $redirect_from,$back_url);
	}

/*------------------------ Init function for login process ----------------------------*/
	public function init_login(array $arr_credentials, $check_activation = true, $remember_me = null, $social_login = false, $redirect_from = false, $back_url)
	{
		$arr_json  = $arr_roles = $arr_user = [];
		$obj_user  = $instance  = false;
		/*$role_slug = $arr_credentials['user_type'];*/

		if(isset($arr_credentials['email']) && isset($arr_credentials['password']) /*&& isset($arr_credentials['user_type'])*/)
		{
		  $user_model = Sentinel::createModel();
		  if(isset($arr_credentials['email'])!='') 
		  {    
		    $obj_user = $user_model->where('email', '=', $arr_credentials['email']);   
		  } 

		  $obj_user =  $obj_user->whereHas('roles', function ($query) {
		                            $query->where('slug', '!=', 'admin');
		                         })
		  						 ->with('roles')
		                         ->first();

		  if( $obj_user->is_active == 0)
		  {
		    	Flash::error('Please verify your account from your email address and then login.');
		    	return redirect()->back();
		  }

		  if ($obj_user!=false) 
		  {
		  	  $instance = Sentinel::authenticate($arr_credentials);
		  }

		 // checking pasword combination is wrong and returning with error message.
		  if ($instance == false) 
		  {
		  	  Flash::error('Invalid login Credentials');
		      return redirect()->back();
		  }

		  if($obj_user != false)
		  {   
		    // account activation check.
		    $user_id = isset($obj_user->id) && $obj_user->id != "" ? $obj_user->id : "";

		    if($user_id == "")
		    {
		    	Flash::error('Invalid login Credentials');
		    	return redirect()->back();
		    }

		    if( $obj_user->is_block == 1)
		    {
		    	Flash::error('Your account is blocked by admin, please contact to admin.');
		    	return redirect()->back();
		    }

		    if($remember_me == 'yes')
		    {
		      $obj_user = Sentinel::authenticateAndRemember($arr_credentials);
		    }
		    else
		    {
		      $obj_user = Sentinel::authenticate($arr_credentials);
		    }
		    
		    // Get all roles for user and if has multiple roles then go to selection page.
	        $arr_roles = $this->RoleService->get_user_roles($user_id);

		    // For future changes regarding the Role just replace $arr_roles[0] with $role_slug and uncomment the $arr_credentials['user_type'] and $request->has('user_type'
		    if(count($arr_roles) > 0) 
        	{
	      	  if(isset($arr_roles[0]) && ($arr_roles[0] != ""))
			  {
			  	 $arr_data = [
		  						'first_name'    => $obj_user->first_name,
		  						'last_name'     => $obj_user->last_name,
		  						'user_id'       => $this->CommonDataService->encrypt_value($obj_user->id),
		  						'role'          => $arr_roles[0]
		  				     ];

		  		Session::put('arr_auth',$arr_data);

	    		//Flash::error('You have been logged in successfully.');

    			$arr_cart_data  = [];
    			$visitor_id     = '';
    			$visitor_id     = Session::get('visitor_id');

	    		if($back_url!='')
	    		{

	    			$back_url_segment = explode('/', $back_url);
	    			if((isset($back_url_segment[4]) && $back_url_segment[4]!='') )
	    			{
	    				if(($back_url_segment[4]=='listing' || $back_url_segment[4]=='cart' || $back_url_segment[4]=='listing?type=photo' || $back_url_segment[4]=='listing?type=footage' ) && $arr_roles[0]=='buyer')
	    				{
	    					$this->add_cart_item_to_user($user_id,$visitor_id);

			        		return redirect(url($back_url));
	    				}
	    			}
	    		}

	    		if(isset($arr_roles[0]) && $arr_roles[0]=='buyer')
	    		{
	    			$this->add_cart_item_to_user($user_id,$visitor_id);

		        	return redirect(url('buyer/dashboard'));
	    		}
	    		else if(isset($arr_roles[0]) && $arr_roles[0]=='seller')
	    		{
	    			$delete_cart = $this->CartModel->where('visitor_id',$visitor_id)->delete();
		        	return redirect(url('seller/dashboard'));
	    		}
        		return redirect()->back();  
			  }		   				    
		  	}
		  }
		}

		Flash::error('Invalid login Credentials.');
        return redirect()->back();  
	}


    /*------------- Attach cart items to user after login --------------*/

    public function add_cart_item_to_user($buyer_id,$visitor_id) 
    {
      if($buyer_id!='' && $visitor_id!='')
      {
        $check_status                     = 0;
        $update_cart                      = [];
        $obj_check_cart_items_of_user     = [];
        $obj_check_new_cart_items_of_user = [];
        $delete_duplicate_items           = false;
        $status                           = false;

        $obj_check_cart_items_of_user     = $this->CartModel->where('buyer_id', $buyer_id)
                                                            ->get();

        $obj_check_new_cart_items_of_user = $this->CartModel->where('visitor_id', $visitor_id)
                                                            ->get();

    
        if(isset($obj_check_new_cart_items_of_user) && count($obj_check_new_cart_items_of_user)>0)
        {
        	$obj_check_cart_items_of_user     = $obj_check_cart_items_of_user->toArray();
        	$obj_check_new_cart_items_of_user = $obj_check_new_cart_items_of_user->toArray();
          foreach ($obj_check_new_cart_items_of_user as $key => $new_cart_item_data)
          {
            if(isset($obj_check_cart_items_of_user) && count($obj_check_cart_items_of_user)>0)
            {
              foreach ($obj_check_cart_items_of_user as $key => $already_exists_cart_item_data)
              {
                if($new_cart_item_data['master_id'] == $already_exists_cart_item_data['master_id'])
                {
                    $delete_duplicate_items  = $this->CartModel->where('master_id', $new_cart_item_data['master_id'])
                                                               ->where('visitor_id', $visitor_id)
                                                               ->delete();
                }
                else
                {
                    $update_cart['buyer_id']  = $buyer_id;
                    $status = $this->CartModel->where('buyer_id', 0)
                                              ->where('master_id', $new_cart_item_data['master_id'])
                                              ->where('visitor_id', $visitor_id)
                                              ->update($update_cart);
                }
              } 
            }
            else
            {
                $update_cart['buyer_id'] = $buyer_id;
                $status = $this->CartModel->where('buyer_id', 0)
                                          ->where('master_id', $new_cart_item_data['master_id'])
                                          ->where('visitor_id', $visitor_id)
                                          ->update($update_cart);
            }  
          }
        }        
      }
    }

/*------------------------ Forget password process ----------------------------*/
    public function process_forgot_password(Request $request)
    {
    	$arr_rules    = [];

        $arr_rules['email'] = "required";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }

	    $email = $request->input('email');

	    $user  = Sentinel::findByCredentials(['email' => $email]);

	    if($user==null)
	    {
	    	Flash::error('Invalid Email Id');
	    	return redirect()->back();
	    }

	    if($user->inRole('admin')==true)
	    {
	    	Flash::error('We are unable to process this Email Id');
	    	return redirect()->back();
	    }

	    $reminder = Reminder::create($user);

	    $arr_mail_data = $this->built_forget_password_mail_data($email, $reminder->code); 
	    $email_status  = $this->EmailService->send_mail($arr_mail_data);
	    
	    Flash::success('Password reset link sent successfully to your email id');
    	return redirect()->back();
   }

/*------------------------ Sending email for forgot password ----------------------------*/
    public function built_forget_password_mail_data($email, $reminder_code)
    {
	    $user = $this->get_user_details($email);

	    if($user)
	    {
	        $arr_user = $user->toArray();

	        $reminder_url = url('/').'/reset_password/'.$this->CommonDataService->encrypt_value($arr_user['id']).'/'.$this->CommonDataService->encrypt_value($reminder_code) ;

	        $arr_built_content = ['FIRST_NAME'       => $arr_user['first_name'],
	                              'EMAIL'            => $arr_user['email'],
	                              'REMINDER_URL'     => $reminder_url,
	                              'PROJECT_NAME'     => config('app.project.name')];

	        $arr_mail_data                         = [];
	        $arr_mail_data['email_template_id']    = '3';
	        $arr_mail_data['arr_built_content']    = $arr_built_content;
	        $arr_mail_data['user']  			   = $arr_user;

	        return $arr_mail_data;
	    }
	    return FALSE;
    }

/*------------------------ TO get user details ----------------------------*/
    public function get_user_details($email)
    {
		$arr_credentials = ['email' => $email];
		$user = Sentinel::findByCredentials($arr_credentials); // check if user exists

		if($user)
		{
		  return $user;
		}
	    return FALSE;
    }

/*------------------------ Reset password page request processing  ----------------------------*/
    public function validate_reset_password_link($enc_id, $enc_reminder_code,Request $request)
    {
    	$arr_rules    = [];

		$arr_rules['password']            = "required|min:6|max:20";
        $arr_rules['c_password']          = "required|min:6|max:20";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }

	    $user_id       	      = $this->CommonDataService->decrypt_value($enc_id);
	    $reminder_code   = $this->CommonDataService->decrypt_value($enc_reminder_code);

	    $password          = $request->input('password');
		$confirm_password  = $request->input('c_password');

	    $user = Sentinel::findById($user_id);

		if(!$user)
		{
			Flash::error('Invalid User Request');
			return redirect()->back();
		}

		if ($reminder = Reminder::complete($user, $reminder_code, $password))
		{
			Flash::success('Password reset successfully. You can Login to your account');
			return redirect(url('/login'));
		}
		else
		{
			Flash::error('Reset Password Link Expired. Please request for new one.');
			return redirect()->back();
		}
    }

/*------------------------ My account for both Buyer and Seller ----------------------------*/
    public function account()
    {
    	$arr_user 	= [];
    	$obj_user 	= $check_user = false;
    	$user_id    = '';

    	$check_user = Sentinel::check();

    	if(isset($check_user) && $check_user==true )
    	{
    		$user_id = $check_user['id'];

    		$obj_user = $this->UserModel->where('id',$user_id)->select('id','first_name','last_name','email','contact_number','address')->first();
    		if($obj_user!=false)
    		{
    			$arr_user = $obj_user->toArray();
    		}
    	}

    	$this->arr_view_data['arr_user'] = $arr_user;
    	$this->arr_view_data['title']    = "My account - ".config('app.project.name');
       	return view('front.auth.account',$this->arr_view_data);
    }

/*------------------------ Edit account page for both Buyer and Seller ----------------------------*/
    public function edit_account()
    {
    	$arr_user 	= [];
    	$obj_user 	= $check_user = false;
    	$user_id    = '';

    	$check_user = Sentinel::check();

    	if(isset($check_user) && $check_user==true )
    	{
    		$user_id = $check_user['id'];

    		$obj_user = $this->UserModel->where('id',$user_id)->select('id','first_name','last_name','email','contact_number','address','zipcode','country','city')->first();
    		if($obj_user!=false)
    		{
    			$arr_user = $obj_user->toArray();
    		}
    	}

    	$this->arr_view_data['arr_user'] = $arr_user;
    	$this->arr_view_data['title']    = "Edit account - ".config('app.project.name');
    	$this->arr_view_data['profile_image_public_img_path']    = $this->profile_image_public_img_path;
       	return view('front.auth.edit_account',$this->arr_view_data);
    }

/*------------------------ Update account process for both Buyer and Seller ----------------------------*/
    public function update_account(Request $request)
    {
    	$arr_rules   = [];
    	$email       = $file_name = '';
    	$check_user  = 0;
    	$user_status = false;  

       /* $arr_rules['first_name']        = "required|min:3|max:25"; 
        $arr_rules['last_name']           = "required|min:3|max:25";
        $arr_rules['email']        		  = "required|max:75";
        $arr_rules['contact_number']      = "required|min:6|max:20";
        $arr_rules['address']          	  = "required|min:6|max:255";
        $arr_rules['zipcode']             = "required|min:3|max:25";
        $arr_rules['city']                = "required|min:3|max:25";
        $arr_rules['country']             = "required|min:3|max:25";
       
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }*/

        if($request->input('email') && $request->input('email')!='')
        {
        	$email = $request->input('email');

        	$check_user = $this->UserModel->where('email',$email)->where('id','<>',1)->count();
        	if($check_user==0)
        	{
        		Flash::error("invlid user request. Please try again.");
        		return redirect()->back();
        	}
        	else
        	{
		        $oldImage = $request->input('oldimage');
		        
		        if($request->hasFile('profile_image'))
		        {
		            $file_name      = $request->input('profile_image');
		            $file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());
		            if(in_array($file_extension,['png','jpg','jpeg']))
		            {
		                $file_name  = time().uniqid().'.'.$file_extension;
		                $isUpload   = $request->file('profile_image')->move($this->profile_image_base_img_path , $file_name);
		                if($isUpload)
		                {
		                	if($oldImage!='')
		                	{
			                    @unlink($this->profile_image_base_img_path.$oldImage);
			                    @unlink($this->profile_image_base_img_path.'/thumb_50X50_'.$oldImage);
		                	}
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

        	    $arr_data             	     = [];

		        if($request->hasFile('profile_image'))
		        {
		        	$arr_data['profile_image'] = $file_name;        		
		        }

				$arr_data['first_name'] 	 = $request->input('first_name') ;
				$arr_data['last_name']  	 = $request->input('last_name') ;
				$arr_data['contact_number']  = $request->input('contact_number') ;
				$arr_data['address']   	 	 = $request->input('address') ;
				$arr_data['zipcode']   	 	 = $request->input('zipcode') ;
				$arr_data['city']   	 	 = $request->input('city') ;
				$arr_data['country']   	 	 = $request->input('country') ;
				$user_status = $this->UserModel->where('email',$email)->update($arr_data);

				if($user_status)
		        {
			    	Flash::success("Account has been updated successfully.");
		       		return redirect(url('/account'));
		        }
		        else
		        {
			    	Flash::error("Problem occuered while updating account. Please try again.");
		        	return redirect()->back();
		        }
        	}
        }
        else
        {
	    	Flash::error("Problem occuered while updating account. Please try again.");
        	return redirect()->back();
        }
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

/*------------------------ Change password page for both Buyer and Seller ----------------------------*/
    public function change_password()
    {
    	$this->arr_view_data['title']    = "Change Password - ".config('app.project.name');
       	return view('front.auth.change_password',$this->arr_view_data);
    }

/*------------------------ Update changed password for both Buyer and Seller ----------------------------*/
    public function update_password(Request $request)
    {

    	$arr_rules     = [];
    	$user_id       = '';
    	$check_user    = $check_password = $user = false;

    	$check_user = Sentinel::check();
    	
    	if(isset($check_user) && $check_user==false)
    	{
    		Flash::error('Invalid user request. Please try again.');
    		return redirect()->back();	
    	}
    	else
    	{
    		$user_id = $check_user['id'];
    	
	        $arr_rules['old_password']           = "required"; 
	        $arr_rules['new_password']           = "required|min:6|max:20";
	        $arr_rules['confirm_password']       = "required|min:6|max:20";
	       
	        $validator = Validator::make($request->all(),$arr_rules);

	        if($validator->fails())
	        {
	            return redirect()->back()->withErrors($validator)->withInput();  
	        }

    	    $credentials             = [];
	    	$credentials['password'] = $request->input('old_password');

	    	$user = Sentinel::findById($user_id);

	    	if (Sentinel::validateCredentials($user,$credentials)) 
	        { 
	          $new_credentials             = [];
	          $new_credentials['password'] = $request->input('new_password');

	          if(Sentinel::update($user,$new_credentials))
	          {
	            Flash::success('Password changed successfully.');
    			return redirect()->back();
	          }
	          else
	          {
	            Flash::error('Problem occuered while changing password.');
    			return redirect()->back();
	          }
	        }
	        else
	        {
	            Flash::error('Invalid old password.');
    			return redirect()->back();
	        } 
	    }
    }

/*------------------------ logout for both Buyer and Seller ----------------------------*/
    public function logout()
    {
    	Sentinel::logout();
    	Session::flush();
    	return redirect(url('/'));

    }

}
