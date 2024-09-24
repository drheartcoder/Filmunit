<?php

namespace App\Common\Services;
use Crypt;
use Illuminate\Http\Request;
/*use App\Models\PaymentSettingsAdminModel;
use App\Models\PaymentSettingsModule;*/
use Session;
use Redirect;
use Sentinel;
use PDF;
use Mail;
use Braintree_Configuration;
use Braintree_Transaction;

use App\Common\Services\PaymentService;
use App\Common\Services\CommonDataService;

//Models
use App\Models\TransactionModel;
use App\Models\OrderDetailsModel; 
use App\Models\CartModel; 
use App\Models\UserModel; 
use App\Models\EmailTemplateModel;
use App\Models\NotificationModel;


class BraintreeService
{
	public function __construct()
	{
		if(! $user = Sentinel::check()) 
        {
          return redirect('/login');
        }

	     $this->user_id                   = $user->id;
	     $this->env_mode                  = env('BRAINTREE_PAYMENT_ENV');
	     $this->merchant_id               = env('BRAINTREE_MERCHANT_ID');
	     $this->public_key                = env('BRAINTREE_PUBLIC_KEY');
	     $this->private_key               = env('BRAINTREE_PRIVATE_KEY');

      	//Models
 		$this->TransactionModel   = new TransactionModel;
 		$this->OrderDetailsModel  = new OrderDetailsModel;
 		$this->CartModel 		  = new CartModel;
 		$this->UserModel    	  = new UserModel;
 		$this->EmailTemplateModel = new EmailTemplateModel;
 		$this->NotificationModel  = new NotificationModel;

		 $this->CommonDataService		  = new CommonDataService();
		 $this->PaymentService		      = new PaymentService();

       	 $this->payment_cancel_url  = url('/').'/payment/cancel';
      	 $this->payment_success_url = url('/').'/payment/success';
       	 $this->invoice_public_path = url('/').'/'.config('app.project.invoice');
	}

    // payment method in braintree 
    public function postPayment($transaction_id ,$payment_type,$card_data)
	{
		if($transaction_id!='' && $payment_type!='' && $card_data!='' && $payment_type=='credit_card' )
		{
			$price = '';
		    $transaction_details = $this->TransactionModel->where('id',$transaction_id)->first();
		  	
		  	if ($transaction_details) 
		  	{
		  		$price = $transaction_details['total'];
		  		$transaction_details = $transaction_details->toArray();
		  	}

		  	//BrainTree Starts Here
			require_once 'payment/braintree/Braintree.php';

			Braintree_Configuration::environment($this->env_mode);
			Braintree_Configuration::merchantId($this->merchant_id);
			Braintree_Configuration::publicKey($this->public_key);
			Braintree_Configuration::privateKey($this->private_key);

			if(isset($card_data) && count($card_data)>0)
			{
				$card_number      = $card_data['card_number'];
				$card_name        = $card_data['card_holder_name'];
				$expirationDate   = $card_data['card_exp_month'].'/'.$card_data['card_exp_year'];
				$cvv              = $card_data['card_cvc'];

				$result = Braintree_Transaction::sale(array(
					'amount' => $price,
					'creditCard' => array(
											'number' 		 => $card_number,
											'cardholderName' => $card_name,
											'expirationDate' => $expirationDate,
											'cvv' => $cvv
										  )
				));
				if(isset($result))
				{
					$response_result = array();
					$arr_transaction = array();
					$arr_update      = array();
					$payment_status  = "0";
					$pay_status      = "unpaid";
					$update_status   = $order_list_update = false;

		            if($result->success == true)
		            {
		            	$pay_status = 'paid';
		            }
		            else
		            {
		            	$pay_status = 'unpaid';
		            }					

		            $arr_transaction['txn_id']   			 = $result->transaction->id;
		            $arr_transaction['status']   			 = $pay_status;
		            $arr_transaction['transaction_date']     = date('c');
		            $arr_transaction['response_data']    	 = json_encode($result->transaction);

		            if (isset($result->transaction)) 
		            {
		            	$obj_transactions = $this->TransactionModel->where('id',$transaction_id)->first();

		            	if ($obj_transactions) 
		            	{
		            		$update_status = $obj_transactions->update($arr_transaction);

				            if ($update_status && $update_status!=false) 
				            {
				            	// this payment is for subscription
				            	if (isset($payment_type) && $payment_type=='credit_card') 
				            	{
				            		if (isset($pay_status) && $pay_status!='')  
				            		{
				            			if($pay_status == 'paid')
				            			{
				            				$arr_update = array('buyer_status'=>'paid');
				            			}
				            			else
				            			{
				            				$arr_update = array('buyer_status'=>'unpaid');
				            			}
		              					
		              					$order_list_update = $this->OrderDetailsModel->where('buyer_id',$this->user_id)->where('transaction_id',$transaction_id)->update($arr_update);	
		              					
		              					if($order_list_update)
		              					{
		              						
	              							$invoice_name   = $this->PaymentService->build_invoice($transaction_id);
	              							$update_invoice = $this->TransactionModel->where('id',$transaction_id)->update(['invoice'=>$invoice_name]);	
										    
										    //Send Mail to user And Admin with Invoice Attachment
											$mail_status = $this->built_admin_and_user_payment_data($transaction_id,$invoice_name);
		              						$empty_cart = $this->CartModel->where('buyer_id',$this->user_id)->delete();
		              					}
				            		}
				            	}		            	
				            	/*Session::flash('success','You have made successfull transaction');*/
					            Redirect::to($this->payment_success_url)->send();
				            }
			        	}
		        	}					
				}
				else
				{
	    			Redirect::to($this->payment_cancel_url)->send();
				}
			}
			else
			{
	    		Redirect::to($this->payment_cancel_url)->send();
			}
		}
	    Redirect::to($this->payment_cancel_url)->send();
	}


	public function built_admin_and_user_payment_data($transaction_id,$invoice_name)
	{	

		$enc_id = $transaction_id;
		$user = false;
    	$get_admin_details 		 = false;
    	$obj_transaction_details = false;
    	$order_id                = '';
    	$seller_id               = '';
    	$item_id				 = $type = '';
    	$seller_info             = array();
    	$photo_name              = array();

		$user = Sentinel::check();

    	$get_admin_details = $this->UserModel->where('id',1)
    										->select('id','first_name','last_name','email')
        									->first();

        $obj_transaction_details = $this->TransactionModel->where('id',$transaction_id)->first();
        
        if($obj_transaction_details!=false)
        {
        	$obj_transaction_details = $obj_transaction_details->toArray();
        	$order_id = $obj_transaction_details['order_number'];	
        }

        
        $seller_info = $this->OrderDetailsModel->where('buyer_id',$this->user_id)
        									   ->with('listing_details.master_details')
        									   ->where('transaction_id',$transaction_id)->first();

        if($seller_info)
        {
	       	 $seller_id  = $seller_info['seller_id'];
	       	 $item_id    = $seller_info['item_id'];
	       	 $type       = isset($seller_info['listing_details']['master_details']['type'])?$seller_info['listing_details']['master_details']['type']:'NA';
	       	 $title      = isset($seller_info['listing_details']['master_details']['title'])?$seller_info['listing_details']['master_details']['title']:'NA';
        }

        //Send Panel Notification TO admin And User
		// Admin notification
		$arr_notification_data_admin 				 = array();
		$send_notification_admin     	    		 = false;
        $arr_notification_data_admin['from_user_id'] = $this->user_id;
        $arr_notification_data_admin['to_user_id']   = 1;
        $arr_notification_data_admin['message']      = 'Hello! You have receieved a new payment from '.$user['first_name'].' '.$user['last_name'].' with Order ID : '.$order_id.' <a href=" '.url('/').'/admin/booking/buyer/view/'.base64_encode($enc_id).'">View</a>';

        //dd($arr_notification_data_admin);

        $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

		// Buyer notification
		$arr_notification_data_seller 				  = array();
		$send_notification_seller     	    		  = false;
        $arr_notification_data_seller['from_user_id'] = 1;
        $arr_notification_data_seller['to_user_id']   = $this->user_id;
        $arr_notification_data_seller['message']      = 'Hello! Your payment for Order ID : '.$order_id.' is done successfully.'.' <a href=" '.url('/').'/buyer/finance/view/'.base64_encode($enc_id).'">View</a>';

        //dd($arr_notification_data_seller);

        $send_notification_seller = $this->NotificationModel->create($arr_notification_data_seller);	

        // Seller notification
		$arr_notification_data_buyer 				  = array();
		$send_notification_buyer     	    		  = false;
        $arr_notification_data_buyer['from_user_id']  = 1;
        $arr_notification_data_buyer['to_user_id']    = $seller_id;
        $arr_notification_data_buyer['message']       = 'Hello! Your '.$type.' '.$title.' has been booked successfully with Order ID : '.$order_id.'. <a href=" '.url('/').'/seller/finance/view/'.base64_encode($enc_id).'">View</a>';

        //dd($arr_notification_data_buyer);

        $send_notification_buyer = $this->NotificationModel->create($arr_notification_data_buyer);



		if($get_admin_details!=false)
		{
			$get_admin_details = $get_admin_details->toArray();
		
			//Admin Mail Trigger
			$arr_admin_mail_data = $this->build_payment_mail($order_id,$invoice_name,7,$get_admin_details);
			if($arr_admin_mail_data)
			{ 
			  $email_status_admin  = $this->send_mail($arr_admin_mail_data);			
			}
		}

		//User Mail Trigger
		$arr_user_mail_data = $this->build_payment_mail($order_id,$invoice_name,6,$user);
		if($arr_user_mail_data)
		{ 
		  $email_status_user  = $this->send_mail($arr_user_mail_data);
		  return 'success';			
		}

		  return 'fail';			
	}
	   
	public function build_payment_mail($order_id,$invoice_name,$template_id,$arr_user)
	{
		$invoice_for_attachment = "";
		$arr_built_content = [];
		$invoice_for_attachment = $this->invoice_public_path.$invoice_name;
		
        if($arr_user!=false)
        {
            if($arr_user['id']!=1)
            {
	            $arr_built_content = [
	            					  'FIRSTNAME'       => $arr_user['first_name'],
	            					  'ORDERID'         => $order_id
	                                  ];
            }
            else
            {
	            $arr_built_content = [
	            					  'ORDERID'         => $order_id
	                                 ];	            	
            }

            $arr_mail_data                         = array();
            $arr_mail_data['email_template_id']    = $template_id;
            $arr_mail_data['arr_built_content']    = $arr_built_content;
            $arr_mail_data['user']                 = $arr_user;
            $arr_mail_data['attachment_path']      = $invoice_for_attachment;
            $arr_mail_data['attachment_name']      = $invoice_name;

            return $arr_mail_data;
        }
          
	}

	public function send_mail($arr_mail_data = FALSE)
	{
		if(isset($arr_mail_data) && sizeof($arr_mail_data)>0)
		{

			$arr_email_template = array();
			$obj_email_template = $this->EmailTemplateModel
										->with(['translations' => function ($query) {
											$query->where('locale','en');
										}])
										->whereHas('translations' , function ($query) {
											$query->where('locale','en');
										})
										->where('id',$arr_mail_data['email_template_id'])
										->first();
			
			if($obj_email_template)
	      	{
	      		//dd($obj_email_template);
	        	$arr_email_template = $obj_email_template->toArray();
	        	$user               = $arr_mail_data['user'];
	        	
	        	if(isset($arr_email_template['translations'][0]['template_html']))
	        	{
		        	$content = $arr_email_template['translations'][0]['template_html'];
		        	
		        	if(isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content'])>0)
		        	{
		        		foreach($arr_mail_data['arr_built_content'] as $key => $data)
		        		{
		        			$content = str_replace("##".$key."##",$data,$content);
		        		}
		        	}

		        	$content = view('email.front_general',compact('content'))->render();
		        	$content = html_entity_decode($content);
		        	
		        	$send_mail = Mail::send(array(),array(), function($message) use($user,$arr_email_template,$content,$arr_mail_data)
			        {
			        	$name = isset($user['first_name']) ? $user['first_name']:"";
				        $message->from($arr_email_template['template_from_mail'], $arr_email_template['template_from']);
				        
				        $message->to($user['email'], $name )
						          ->subject($arr_email_template['translations'][0]['template_subject'])
						          ->setBody($content, 'text/html')
						          ->attach(\Swift_Attachment::fromPath($arr_mail_data['attachment_path']));

			        });
			        return $send_mail;
		        }
	        }
	    }

	    return false;    
	}

}
?>