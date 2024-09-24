<?php 

namespace App\Common\Services;
use Illuminate\Http\Request;

//menthod paypal
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use PayPal\Api\Invoice;

//billing method
use PayPal\Api\ChargeModel; 
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition; 
use PayPal\Api\Plan;
use PayPal\Api\Agreement;

//billing update
use PayPal\Api\Patch; 
use PayPal\Api\PatchRequest; 
use PayPal\Common\PayPalModel;

//billing paypal
use PayPal\Api\ShippingAddress;

//billing CreditCard
use PayPal\Api\CreditCard; 
use PayPal\Api\FundingInstrument;

//Models
use App\Models\TransactionModel;
use App\Models\OrderDetailsModel; 
use App\Models\CartModel; 
use App\Models\UserModel; 
use App\Models\EmailTemplateModel;
use App\Models\NotificationModel;

/*card*/
use PayPal\Api\PaymentCard;

use Validator;
use Session;
use Input;
use DB;
use Mail;
use Redirect;
use Sentinel;

use App\Common\Services\PaymentService;
use PayPal\Api\Payout;

class PaypalService
{
	private $_api_context;

    public function __construct($client_id,$secret_key,$paypal_mode)    
    {

        // setup PayPal api context
        $paypal_conf = config('paypal');
        //$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));

        if (isset($paypal_mode) && $paypal_mode!="") 
        {
        	if (isset($paypal_conf['settings']['mode'])) 
        	{
        		if ($paypal_mode==2) 
        		{
        				$paypal_conf['settings']['mode'] = 'live';
        		}	
        		else if($paypal_mode==1)
        		{
        			$paypal_conf['settings']['mode'] = 'sandbox';
        		}
        	}
        }

        $this->_api_context = new ApiContext(new OAuthTokenCredential($client_id, $secret_key));
        // /dd($this->_api_context);
        $this->_api_context->setConfig($paypal_conf['settings']);

        if(!$user = Sentinel::check())
        {
         	Redirect::to('/login')->send();
        }
        $this->user_id = $user->id;

      	$this->payment_cancel_url  = url('/').'/payment/cancel';
      	$this->payment_success_url = url('/').'/payment/success';
      	$this->invoice_public_path = url('/').'/'.config('app.project.invoice');

      	//Models
 		$this->TransactionModel   = new TransactionModel;
 		$this->OrderDetailsModel  = new OrderDetailsModel;
 		$this->CartModel 		  = new CartModel;
 		$this->PaymentService     = new PaymentService;
 		$this->UserModel    	  = new UserModel;
 		$this->EmailTemplateModel = new EmailTemplateModel;
 		$this->NotificationModel  = new NotificationModel;
      
        $this->payment_redirect_url = url('/payment/charge');

    }

    // payment method in paypal 
    public function postPayment($transaction_id ,$payment_type)
	{
		if($transaction_id!='' && $payment_type!='' )
		{
			if($payment_type=='paypal')
			{
				$payer = new Payer();
			    $payer->setPaymentMethod('paypal');
			  	$invoice = new Invoice();
			}

		  	$transaction_details = $this->TransactionModel->where('id',$transaction_id)->first();
		  	
		  	if ($transaction_details) 
		  	{
		  		$transaction_details = $transaction_details->toArray();
		  	}

			//dd($transaction_details);

		  	if (isset($transaction_details) && sizeof($transaction_details)>0 && isset($transaction_details['total']) && isset($transaction_details['currency_code'])) 
		  	{
		  		$item1 = new Item();
		  		if (isset($transaction_details['transaction_type']) && $transaction_details['transaction_type']=='paypal') 
		  		{
		  			$item1->setName('FilmUnit Photos/Footage') // item name
		  				  ->setCurrency($transaction_details['currency_code'])				         
			              ->setQuantity(1) // quentity
			         	  ->setSku("FU".uniqid())
			         	  ->setPrice($transaction_details['total']); // unit price
		  		}
			         
				
				//add iteam to item list
				$itemList = new ItemList();
				$itemList->setItems(array($item1));

				$details = new Details();
				$details->setSubtotal($transaction_details['total']);

	  			$amount = new Amount();
	    		$amount->setCurrency($transaction_details['currency_code'])
	        	       ->setTotal($transaction_details['total'])
	        	       ->setDetails($details);

	        	$invoice_number = $transaction_id;
		        $transaction = new Transaction();
		    	$transaction->setAmount($amount)
		        			->setItemList($itemList)
		        			->setDescription($invoice_number);
		        
		        if($payment_type!='creditcard')
	            {
				    $redirect_urls = new RedirectUrls();
				    $redirect_urls->setReturnUrl($this->payment_redirect_url)
				        		  ->setCancelUrl($this->payment_redirect_url);
			    }
			    if($payment_type=='creditcard')
	            {
	            	$payment = new Payment();
					$payment->setIntent("sale")
					    ->setPayer($payer)
					    ->setTransactions(array($transaction));
	            }
	            else
	            {
	            	 $payment = new Payment();
			   			 $payment->setIntent('sale')
			        	->setPayer($payer)
			        	->setRedirectUrls($redirect_urls)
			        	->setTransactions(array($transaction));  
	            }

			    		
		  	}

		    try {
			        $payment->create($this->_api_context);
			      
			        
		    } catch (\PayPal\Exception\PayPalConnectionException $ex) {
		        if (\Config::get('app.debug')) {
		        	
		        	Redirect::to($this->payment_cancel_url)->send();
		        } else {
		        	Redirect::to($this->payment_cancel_url)->send();
		        }
		    }
		    catch (\Exception $ex) 
		    {
		    	Redirect::to($this->payment_cancel_url)->send();
		    }

		    if($payment_type=='creditcard')
            {
	           $request = clone $payment;
	           return $payment;
	        }
    

		    foreach($payment->getLinks() as $link) {
		        if($link->getRel() == 'approval_url')
		         {
		            $redirect_url = $link->getHref();
		            break;
		        }
		    }

		    // add payment ID to session
		    Session::put('paypal_payment_id', $payment->getId());
		    //set trasaction type
		    if (isset($transaction_details['transaction_type'])) 
	  		{
	  			Session::put('transaction_type', $transaction_details['transaction_type']);

	  			/* transaction type is release request then ger expert and set in session for charge_handler  in payment controller (for this payment we have to get experts paypal details)*/
	  			if ($transaction_details['transaction_type']==3) 
	  			{
	  				$expert_id = $this->get_expert_form_release_request($transaction_id);
	  				Session::put('expert_id_for_paypal', $expert_id);
	  				
	  			}
	  		}

		    if(isset($redirect_url)) 
		    {   // redirect to paypal
		        //return Redirect::away($redirect_url);
	            Redirect::to($redirect_url)->send();
		    }
		    
		    Redirect::to($this->payment_cancel_url)->send();
		}
	}

	public function getPaymentStatus($payment_id,$payer_id)
	{
		$response_result = array();
		$arr_transaction = array();
		$arr_update = array();
		$payment_status  = "0";
		$pay_status      = "unpaid";
		$update_status = $order_list_update = false;

		if ($payer_id!="" && $payment_id!="") 
		{
			try 
			{
				$payment   = Payment::get($payment_id, $this->_api_context);
			    $execution = new PaymentExecution();	    
			    $execution->setPayerId($payer_id);
			    //Execute the payment
			    $result = $payment->execute($execution, $this->_api_context);

			    if($result)
			    {
			    	$response_result = $result->toArray();
			    }	
		        
	    	} 
	    	catch (\Exception $ex) 
	    	{
	    		Redirect::to($this->payment_cancel_url)->send();
	    	}
		    //dd($response_result);
		}
		else
		{
			Redirect::to($this->payment_cancel_url)->send();
		}
	   		

	    if(isset($response_result) && sizeof($response_result)>0)
	    {
	    	
	    	if($response_result['state']=="approved")
	    	{
	    		$payment_status = "1";
	    	}
			elseif($response_result['state']=="pending")
            {   
                $payment_status = "0";
            }
            elseif($response_result['state']=="captured")
            {   
                $payment_status = "2";
            }
            elseif($response_result['state']=="loss")
            {
                $payment_status = "3";  
            }

            if($payment_status == 1)
            {
            	$pay_status = 'paid';
            }
            else
            {
            	$pay_status = $response_result['state'];
            }

            $arr_transaction['txn_id']   			 = $response_result['id'];
            $arr_transaction['status']   			 = $pay_status;
            $arr_transaction['transaction_date']     = date('c');
            $arr_transaction['response_data']    	 = json_encode($response_result);

            if (isset($response_result['transactions'][0]['description'])) 
            {
            	$transaction_id = $response_result['transactions'][0]['description'];
            	$obj_transactions = $this->TransactionModel->where('id',$transaction_id)->first();

            	if ($obj_transactions) 
            	{
            		$update_status = $obj_transactions->update($arr_transaction);

		            if ($update_status && $update_status!=false) 
		            {
		            	// this payment is for subscription
		            	if (isset($obj_transactions->transaction_type) && $obj_transactions->transaction_type=='paypal') 
		            	{
		            		if (isset($payment_status) && $payment_status==1 || isset($payment_status) && $payment_status==2)  
		            		{
		            			if($payment_status == 1)
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
              						if($payment_status==1)
              						{
              							$invoice_name   = $this->PaymentService->build_invoice($transaction_id);
              							$update_invoice = $this->TransactionModel->where('id',$transaction_id)->update(['invoice'=>$invoice_name]);	
									    
									    //Send Mail to user And Admin with Invoice Attachment
										$mail_status = $this->built_admin_and_user_payment_data($transaction_id,$invoice_name);
              						}
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

        $send_notification_admin = $this->NotificationModel->create($arr_notification_data_admin);

		// Buyer notification
		$arr_notification_data_seller 				  = array();
		$send_notification_seller     	    		  = false;
        $arr_notification_data_seller['from_user_id'] = 1;
        $arr_notification_data_seller['to_user_id']   = $this->user_id;
        $arr_notification_data_seller['message']      = 'Hello! Your payment for Order ID : '.$order_id.' is done successfully.'.' <a href=" '.url('/').'/buyer/finance/view/'.base64_encode($enc_id).'">View</a>';

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