<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Session;
use Sentinel;
use CreditCard;
use Crypt;
use Validator;

/*Models*/
use App\Models\TransactionModel; 
use App\Models\CartModel; 
use App\Models\UserModel; 

/*Services*/
use App\Common\Services\PaymentService;
use App\Common\Services\PaypalService;
use App\Common\Services\CommonDataService;

use App\Common\Services\SubscriptionService;


class PaymentController extends Controller
{    
    public $arr_view_data;
    
    public function __construct(
    							TransactionModel $transaction_model,
    							CartModel $cart_model,
    							CommonDataService $commondataservice
    							)
    { 
      if(! $user = Sentinel::check()) 
      {
        return redirect('/');
      }

      $this->buyer_id 			 			     = $user->id;
      $this->client_id                 = env('PAYPAL_CLIENT_ID');
      $this->secret_key                = env('PAYPAL_SECRET_KEY');
      $this->payment_mode              = env('PAYPAL_PAYMENT_MODE');

      $this->CartModel                        = $cart_model;
      $this->arr_view_data 		  		          = [];
      $this->module_url_path          	      = url('/')."/payment";
      $this->photos_base_img_path             = config('app.project.img_path.photos');
	    $this->photos_public_img_path           = url('/').'/'.config('app.project.img_path.photos');
      $this->footage_base_img_path     	      = config('app.project.img_path.footage');
	    $this->footage_public_img_path      	  = url('/').'/'.config('app.project.img_path.footage');
      $this->admin_footage_public_img_path    = url('/').'/'.config('app.project.img_path.admin_footage');
      $this->admin_photos_public_img_path     = url('/').'/'.config('app.project.img_path.admin_photos');      

      $this->payment_cancel_url  			  = url('/').'/payment/cancel';

      $this->PaymentService		   = new PaymentService();
      $this->PaypalService 		   = FALSE;
      $this->SubscriptionService = new SubscriptionService();
      $this->CommonDataService   = $commondataservice;
    }

    public function index()
    {
    	$user 	  = false;
    	$obj_data = false;
    	$arr_data = [];

    	$user = Sentinel::check();
        
        if($user!=false && $user['role']=='buyer')
        {
          
          $buyer_id = $user['id'];
	        $obj_data = $this->CartModel->where('buyer_id',$buyer_id)->get();
	        if($obj_data!=false)
	        {
	        	$arr_data = $obj_data->toArray();	
	        }

          $obj_card_data = UserModel::select('card_number','card_type','card_exp_month','card_exp_year','card_cvc','card_holder_name','card_save','is_agree')->where('id',$buyer_id)->first();
          if($obj_card_data!=false)
          {
            $arr_card_data = $obj_card_data->toArray(); 
          }

	        if(count($arr_data)<=0)
	        {
       			return redirect()->back();
	        }
	        
          $this->arr_view_data['arr_data']            =  $arr_data;
  	    	$this->arr_view_data['arr_card_data'] 		  =  $arr_card_data;
  	    	$this->arr_view_data['title'] 			        =  "Checkout - ".config('app.project.name');
  	    	$this->arr_view_data['module_url_path']     =  $this->module_url_path;
		    	
	       	return view('front.payment.index',$this->arr_view_data);
        }
       	else
       	{
       		return redirect()->back();
       	}
    }

    public function cancel()
    {
    	$this->arr_view_data['title'] 			  =  "Payment Failed - ".config('app.project.name');
    	$this->arr_view_data['module_url_path']   =  $this->module_url_path;
	    	
       	return view('front.payment.cancel',$this->arr_view_data);
    }            

    public function success()
    {
    	$this->arr_view_data['title'] 			  =  "Payment Success - ".config('app.project.name');
    	$this->arr_view_data['module_url_path']   =  $this->module_url_path;
	    	
       	return view('front.payment.success',$this->arr_view_data);
    }            

    public function payment_handler()
    {
    	$form_data 		  = array();
    	$arr_cc_details = array();
      $status 		    = FALSE;
      $form_data['payment_method']   = 1;
      $form_data['transaction_type'] = 1;

	   	if (isset($form_data['transaction_type']) && $form_data['transaction_type']==1) 
	   	{
	   		// call payment method for subscription
	   		 $this->SubscriptionService->payment($this->CommonDataService->encrypt_value($this->buyer_id),'paypal');
	   	}

 		return redirect($this->payment_cancel_url)->with('error', 'Payment failed');
	   	
    }


    public function charge_handler(Request $request)
    {	
    	$payment_id="";

    	$payment_id = Session::get('paypal_payment_id');
    	$transaction_type = Session::get('transaction_type');
    	$expert_id_for_paypal = Session::get('expert_id_for_paypal');
    	
	    // clear the session payment ID
	    Session::forget('paypal_payment_id');
	    Session::forget('transaction_type');
	    Session::forget('expert_id_for_paypal');

	    if (empty($request->get('PayerID')) || empty($request->get('token'))) 
	    {
	        return redirect($this->payment_cancel_url)->with('error', 'Payment failed');
	    }

	    if (!$transaction_type || !$payment_id) 
	    {
	    	return redirect($this->payment_cancel_url)->with('error', 'Payment failed');
	    }

	    if (isset($transaction_type) && $transaction_type=='paypal' ) 
	    {
   			$this->PaypalService = new PaypalService($this->client_id,$this->secret_key,$this->payment_mode);

   			if($this->PaypalService==FALSE)
  			{
  			 	return redirect($this->payment_cancel_url);
  			}
  			else
  			{
  				return $this->PaypalService->getPaymentStatus($payment_id,$request->get('PayerID'));
  			}
	    }
    	return redirect($this->payment_cancel_url);
    }


    public function transaction_success()
    {
    	$this->arr_view_data['page_title'] = "Success";
    	return view('front.common.success_page',$this->arr_view_data);
    }
    


    public function cardpay_handler(Request $request)
    {
      $card_number      = $request->input('card_number');
      $card_exp_month   = $request->input('card_exp_month');
      $card_exp_year    = $request->input('card_exp_year');
      $card_cvc         = $request->input('card_cvc');
      $card_holder_name = $request->input('card_holder_name');
      $note             = $request->input('note');
      $card_type        = $request->input('card_type');
      $card_save        = $request->input('card_save');
      $is_agree         = $request->input('is_agree');
      $is_card_save     = $card_save=='on'?'1':'0';
      $is_agree_save    = $is_agree=='on'?'1':'0';

      if($card_number!='' && $card_exp_month!='' && $card_exp_year!='' && $card_cvc!='' && $card_holder_name!='')
      {
        /* Validate CC Details if payment mathod is Braintree*/      
        // CC Number 
        $cc_number_status = CreditCard::validCreditCard($card_number);

        if($cc_number_status['valid']==FALSE)
        {
            Session::flash('error',"Invalid Credit Card Number");
            return redirect()->back()->withInput($request->all());
        }
        
        // CVV Number 
        $cc_security_number_status = CreditCard::validCvc($card_cvc,$cc_number_status['type']);

        if($cc_security_number_status==FALSE)
        {
            Session::flash('error',"Invalid Credit Card Security Number");
            return redirect()->back()->withInput($request->all());
        }

        if (isset($card_exp_month) && $card_exp_year!="" && isset($card_exp_year) && $card_exp_year!="") 
        {   
              $cc_expiry_year_status = CreditCard::validDate(trim($card_exp_year),trim($card_exp_month));

              if($cc_expiry_year_status==FALSE)
              {
                  Session::flash('error',"Invalid Credit Card Expiry Date.");
                  return redirect()->back()->withInput($request->all());
              } 
        }
        else
        {
            Session::flash('error',"Invalid Credit Card Expiry Date");
            return redirect()->back()->withInput($request->all());
        }
        
        $form_data = array(
        'card_number'=>$card_number,
        'card_exp_month'=>$card_exp_month,
        'card_exp_year'=>$card_exp_year,
        'card_cvc'=>$card_cvc,
        'card_holder_name'=>$card_holder_name,
        'card_save'=>$is_card_save,
        'is_agree'=>$is_agree_save,
        'card_type'=>$card_type
        );

        $user = Sentinel::check();        
        
        if($user!=false && $user['role']=='buyer')
        {
          $buyer_id = $user['id'];
        }
        
        if($buyer_id!='' && isset($form_data) && sizeof($form_data)>0)
        {
          if($is_card_save==1)
          {
            UserModel::where('id',$buyer_id)->update($form_data);
          }
          else
          {
            $arr_update = array(
                              'card_number'=>"",
                              'card_exp_month'=>"",
                              'card_exp_year'=>"",
                              'card_cvc'=>"",
                              'card_holder_name'=>"",
                              'card_save'=>$is_agree_save,
                              'is_agree'=>$is_agree_save,
                              'card_type'=>""
                              );

            UserModel::where('id',$buyer_id)->update($arr_update);
          }

          $this->SubscriptionService->payment($this->CommonDataService->encrypt_value($this->buyer_id),'creditcard',$form_data);
        }
      }
      else
      {
        Session::flash('error',"Invalid Credit Card Expiry Date");
        return redirect()->back()->withInput($request->all());  
      }
      /*end cards validations with CreditCard lib*/
    }
}