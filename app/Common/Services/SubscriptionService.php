<?php 

namespace App\Common\Services;
use Crypt;
use Illuminate\Http\Request;

use Session;
use Redirect;
use Sentinel;

use App\Common\Services\PaymentService;
use App\Common\Services\PaypalService;
use App\Common\Services\BraintreeService;
use App\Common\Services\CommonDataService;

use App\Models\CartModel; 
use App\Models\TransactionModel; 
use App\Models\MediaListingMasterModel; 
use App\Models\OrderDetailsModel; 
use App\Models\DownloadModel; 

class SubscriptionService
{
	public function __construct()
	{
		$this->PaypalService = FALSE;
	    if(! $user = Sentinel::check()) 
        {
          return redirect('/login');
        }

	     $this->user_id                   = $user->id;
	     $this->client_id                 = env('PAYPAL_CLIENT_ID');
	     $this->secret_key                = env('PAYPAL_SECRET_KEY');
	     $this->payment_mode              = env('PAYPAL_PAYMENT_MODE');

	     $this->CartModel                 = new CartModel();
	     $this->TransactionModel          = new TransactionModel();
	     $this->MediaListingMasterModel   = new MediaListingMasterModel();
	     $this->OrderDetailsModel         = new OrderDetailsModel();
	     $this->DownloadModel             = new DownloadModel();

		 $this->CommonDataService		  = new CommonDataService();
	     $this->PaymentService            = new PaymentService();
	     $this->BraintreeService          = new BraintreeService();
		 $this->PaypalService             = new PaypalService($this->client_id,$this->secret_key,$this->payment_mode);
	}
  

	public function payment($buyer_id,$transaction_type,$card_data =[])
	{

		$arr_cart_details     = array();
		$arr_transaction      = array();
		$arr_order_details    = array();
	    $arr_master_list      = array();
	    $arr_download_attempts= array();
	    $obj_cart_details     = false;
		$order_number         = "";
	    $transaction_id       = '';
	    $master_id            = '';    
	    $total                = 0;
	    $download_attempts    = 2;

		if ($buyer_id!='' && $transaction_type!='') 
		{
            $buyer_id = $this->CommonDataService->decrypt_value($buyer_id);
			/* Payment method :1-paypal 2-Braintree Credit Cart;*/
			
			$obj_cart_details =  $this->CartModel->where('buyer_id',$buyer_id)->get();

			if ($obj_cart_details!=false) 
			{
				$arr_cart_details = $obj_cart_details->toArray();

	            if (isset($arr_cart_details) && count($arr_cart_details)>0) 
			    {
		            foreach ($arr_cart_details as $key => $value)
		            {
		              $total += $value['price']; 
		            }

		            $order_number	= $this->_generate_order_number();
				  	/*First make transaction  */ 
				  	$arr_transaction['buyer_id']         = $buyer_id;
				  	$arr_transaction['order_number']     = $order_number;
				  	$arr_transaction['status']           = 'unpaid';
		            $arr_transaction['transaction_type'] = $transaction_type;
		            $arr_transaction['transaction_date'] = date('c');
		            $arr_transaction['total']            = $total;
		            $arr_transaction['currency']         = '$';
		            $arr_transaction['currency_code']    = 'USD';

				  	$transaction = $this->TransactionModel->create($arr_transaction);

				  	if ($transaction) 
				  	{
		               $transaction_id = $transaction->id;
		              
		               foreach ($arr_cart_details as $key => $details)
		               {
			                //Get Master Lisitng Details
			                $master_id = $details['master_id'];
			                $arr_master_list = $this->MediaListingMasterModel->where('id',$master_id)->first();
			                if(isset($arr_master_list) && count($arr_master_list)>0)
			                {
			                  $arr_master_list = $arr_master_list->toArray(); 
			                }

			                $arr_download_attempts = $this->DownloadModel->select('attempts')->first();
			                if(isset($arr_download_attempts) && count($arr_download_attempts)>0)
			                {
			                	$download_attempts = $arr_download_attempts['attempts'];
			                }

	  					  	/*insert record in order_details */
							$arr_order_details['transaction_id']     = $transaction_id;
							$arr_order_details['buyer_id']           = $buyer_id;
							$arr_order_details['seller_id']          = isset($arr_master_list['seller_id'])?$arr_master_list['seller_id']:0;
							$arr_order_details['item_id']            = isset($details['list_id'])?$details['list_id']:0;
							$arr_order_details['order_number']       = $order_number;
							$arr_order_details['type']               = isset($details['type'])?$details['type']:'';
							$arr_order_details['price']              = isset($details['price'])?$details['price']:'';
	                        $arr_order_details['download_attempt']   = $download_attempts;
	                        $arr_order_details['buyer_status']       = 'unpaid';
							$arr_order_details['seller_status']      = 'unpaid';

							$subscription = $this->OrderDetailsModel->create($arr_order_details);
	                    }
						if ($subscription) 
						{
							/*Now redirect to payment gateway $arr_payment_details[payment_method] is Paypal and Stripe  1-Paypal 2-Credticart-braintree */
						  	if ($transaction_type=='paypal') 
						  	{
							  	  $payment_type ='paypal';
							  	  if(!$this->PaypalService==FALSE)
								  {
								 	 $this->PaypalService->postPayment($transaction_id,$payment_type);
								  }
						  	}
						  	else if($transaction_type=='creditcard')
						  	{						  	
						  		  $payment_type ='credit_card';
						  	 	  if(!$this->BraintreeService==FALSE)
								  {
								 	 $this->BraintreeService->postPayment($transaction_id,$payment_type,$card_data);
								  }
						  	}
						}
					}
				}
			}
		}		
	}

   private function _generate_order_number()
   {
   		$secure = TRUE;    
        $bytes = openssl_random_pseudo_bytes(12, $secure);
        $order_token = "FU_".bin2hex($bytes);
        return $order_token;
   }

}
?>