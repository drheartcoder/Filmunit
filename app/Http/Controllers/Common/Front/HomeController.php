<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()  
    {   
        $this->arr_view_data = [];
    }

    public function index()
    {
    	$this->arr_view_data['title'] = "Home - ".config('app.project.name');
       	return view('front.home',$this->arr_view_data);
    }

    /*------------------------ Front Auth Starts Here ----------------------------*/

    public function login()
    {
    	$this->arr_view_data['title'] = "Log In - ".config('app.project.name');
       	return view('front.auth.login',$this->arr_view_data);
    }

    public function signup()
    {
    	$this->arr_view_data['title'] = "Sing Up - ".config('app.project.name');
       	return view('front.auth.signup',$this->arr_view_data);
    }

    public function registration_success()
    {
    	$this->arr_view_data['title'] 	= "Thank You - ".config('app.project.name');
       	return view('front.templates.thankyou_register',$this->arr_view_data);
    }

    public function forgot_password()
    {
    	$this->arr_view_data['title'] 	= "Forgot Password - ".config('app.project.name');
       	return view('front.auth.forgot_password',$this->arr_view_data);
    }

    public function reset_password($enc_id, $enc_reminder_code)
    {

    	$this->arr_view_data['title'] 				= "Reset Password - ".config('app.project.name');
    	$this->arr_view_data['enc_id'] 				= $enc_id;
    	$this->arr_view_data['enc_reminder_code'] 	= $enc_reminder_code;
       	return view('front.auth.reset_password',$this->arr_view_data);
    }

    /*------------------------ Front Static Pages Starts Here ----------------------------*/
    
    public function about()
    {
    	$this->arr_view_data['title'] 	= "About - ".config('app.project.name');
       	return view('front.common.about',$this->arr_view_data);
    }

    public function contactus()
    {
    	$this->arr_view_data['title'] 	= "Contact - ".config('app.project.name');
       	return view('front.common.contact',$this->arr_view_data);
    }

    public function terms_and_condition()
    {
    	$this->arr_view_data['title'] 	= "Terms and Conditions - ".config('app.project.name');
       	return view('front.common.terms_and_condtions',$this->arr_view_data);
    }

    public function privacy_policy()
    {
    	$this->arr_view_data['title'] 	= "Privacy and Policy - ".config('app.project.name');
       	return view('front.common.privacy',$this->arr_view_data);
    }

}