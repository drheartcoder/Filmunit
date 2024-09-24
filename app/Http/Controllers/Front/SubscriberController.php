<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SubscribersModel;

use Validator;
use Sentinel;
use Flash;
use DB;

class SubscriberController extends Controller
{
    public function __construct(SubscribersModel $subscriber_model							
    							)  
    {   
		  $this->SubscribersModel 		        = $subscriber_model;
    }

    public function index(Request $request)
    {   
     //dd($request->all()); 
      //print_r($_REQUEST); exit;
    	$arr_rules = array(); $insert_subscriber = '';
        $arr_rules['email'] = 'required';
        $email = $request->input('email');
        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        else
        {  
           $email = $email;
        
           $is_exist = $this->SubscribersModel->where('subscriber_email',$email)->first();
           if($is_exist)
           {        
                Flash::error('Email already exist plese try with anothor email');
                return redirect()->back();  
           }    
           else
           {    
               $arr_data['subscriber_email']    = $email;
               $arr_data['is_active']           = 1 ;  
           }

            $insert_subscriber = $this->SubscribersModel->create($arr_data);
            if($insert_subscriber)
            {
               Flash::success('Email subscribed successfully');
               //dd(Flash::success('Email subscribed successfully'));
               return redirect()->back();   
            }
            else
            {
                Flash::error('Something went wrong plese try again');
                return redirect()->back();              
            }
        }
    }

    
}


    