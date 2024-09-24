<?php 

namespace App\Common\Services;
use Crypt;
use Illuminate\Http\Request;
/*use App\Models\PaymentSettingsAdminModel;
use App\Models\PaymentSettingsModule;*/
use Session;
use Redirect;
use PDF;

//Models
use App\Models\TransactionModel;
use App\Models\OrderDetailsModel; 
use App\Models\CartModel; 
use App\Models\SiteSettingModel; 

class PaymentService
{
	public function __construct()
	{
		 $this->TransactionModel  = new TransactionModel();
		 $this->OrderDetailsModel = new OrderDetailsModel();
		 $this->CartModel 		  = new CartModel();
		 $this->SiteSettingModel  = new SiteSettingModel();

		 $this->invoice = config('app.project.invoice');
		 
	}
	public function _encrypt($arr_data = array())
	{
		if(isset($arr_data)&& is_array($arr_data) && $arr_data!="" && sizeof($arr_data)>0)
		{
			$encoded_version  = json_encode($arr_data);
			$encrypted_version = Crypt::encrypt($encoded_version);
			return $encrypted_version;
		}
		else
		{
			return false;
		}		
	}

	public function _decrypt($encrypted_data="")
	{
		$arr_data = array();
		if(isset($encrypted_data) && $encrypted_data!="")
		{
			$decrypted_version = Crypt::decrypt($encrypted_data);	
			$arr_data  = json_decode($decrypted_version,true);
			return $arr_data;
		}
		else
		{
			return false;
		}
	}

	public function build_invoice($transaction_id)
	{
	  $arr_invoice                = array();
      $obj_invoice_details        = array();
      $obj_site_settings_details  = false;
      $total_amount               = 0;

      if($transaction_id!="")
      {
        $arr_invoice = array();
        //$order_id  = $request->input('enc_oder_details_id');
        $obj_invoice_details = $this->TransactionModel->where('id',$transaction_id)
                                                  ->with(['buyer_details'=>function($query){
                                                      $query->select('id','first_name','last_name');
                                                  }])
                                                  ->with('order_details.listing_details.master_details')
                                                  ->first();

        $obj_site_settings_details = $this->SiteSettingModel->where('site_setting_id',1)
                                          ->select('site_name','info_email_address','billing_email_address','site_contact_number','site_address')
                                          ->first();

        if($obj_invoice_details)
        {
          $obj_invoice_details          = $obj_invoice_details->toArray();
          $arr_invoice                  = $obj_invoice_details;
          $arr_invoice['invoice_id']    = $arr_invoice['order_number'];
          $arr_invoice['client_name']   = isset($obj_invoice_details['buyer_details'])?$obj_invoice_details['buyer_details']['first_name'].' '.$obj_invoice_details['buyer_details']['last_name'] : "";  
          //$arr_invoice['client_zipcode']= isset($obj_invoice_details['buyer_details'])?$obj_invoice_details['buyer_details']['zipcode'].' '.$obj_invoice_details['buyer_details']['zipcode'] : "";  
          $total_amount                 = $arr_invoice['total'];
          $total_amount                 = sprintf("%.2f", $total_amount);
          $arr_invoice['invoice_date']  = isset($arr_invoice['transaction_date']) && $arr_invoice['transaction_date']!= null ? date('d M Y',strtotime($arr_invoice['transaction_date'])) : "";
          
          if($obj_site_settings_details!=false)
          {
            $arr_invoice['site_name']       = $obj_site_settings_details['site_name'];
            $arr_invoice['email']           = $obj_site_settings_details['info_email_address'];
            $arr_invoice['billing_email']   = $obj_site_settings_details['billing_email_address'];
            $arr_invoice['contact_number']  = $obj_site_settings_details['site_contact_number'];
            $arr_invoice['address']         = $obj_site_settings_details['site_address'];
          }
        }

        $html ='';
        $file_name = $arr_invoice['invoice_id'];

		$pdf = new PDF();
		PDF::SetTitle('FilmUnit Invoice');
	    PDF::AddPage();

        PDF::writeHTML(view('invoice.invoice_pdf',array('arr_invoice'=>$arr_invoice,'total_amount'=>$total_amount)), 'FilmUnit');

	    $FileName = $file_name.'.pdf';
	    $pdf::output(public_path($this->invoice.$FileName),'F');
	    return $FileName;
	  }
	}	
		
/*	public function get_admin_payment_settings()
	{
		$payment_data =array();
		$obj_payment_settings =  $this->PaymentSettingsAdminModel->where('id',1)->first();

		if(isset($obj_payment_settings) && sizeof($obj_payment_settings)>0)
		{
			$arr_payment_settings  = $obj_payment_settings->toArray();
			
			if(isset($arr_payment_settings) && is_array($arr_payment_settings) && sizeof($arr_payment_settings)>0)
			{
				if (isset($arr_payment_settings['payment_details']) && $arr_payment_settings['payment_details']!="" && $arr_payment_settings['payment_details']!=NULL)
				{
					$payment_data  = $this->_decrypt($arr_payment_settings['payment_details']);	
				}
				
				return $payment_data;	
			}
			else
			{
				return false;	
			}
		}
		else
		{
			return false;
		}
	}

	public function get_user_payment_settings($user_id=false)
	{
		$payment_data =array();

		if (isset($user_id) && $user_id!=false) 
		{	
			$obj_payment_settings =  $this->PaymentSettingsModule->where('user_id',$user_id)->first();

			if(isset($obj_payment_settings) && sizeof($obj_payment_settings)>0)
			{

				$arr_payment_settings  = $obj_payment_settings->toArray();
				
				if(isset($arr_payment_settings) && is_array($arr_payment_settings) && sizeof($arr_payment_settings)>0)
				{
					if (isset($arr_payment_settings['payment_details']) && $arr_payment_settings['payment_details']!="" && $arr_payment_settings['payment_details']!=NULL)
					{
						$payment_data  = $this->_decrypt($arr_payment_settings['payment_details']);
						
						return $payment_data;	
					}
				}
			}
		}
	
		return false;
	}*/
}
?>