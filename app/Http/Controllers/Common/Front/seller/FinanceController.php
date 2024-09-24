<?php

namespace App\Http\Controllers\Front\seller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\TransactionModel;
use App\Models\OrderDetailsModel;
use App\Models\SiteSettingModel;

use Sentinel;
use Flash;
use DB;
use PDF;

class FinanceController extends Controller
{
    public function __construct(
                    TransactionModel $transaction_model,
	    							SiteSettingModel $sitesetting_model,
	    							OrderDetailsModel $order_details_model
    							)  
    {   
  		$this->TransactionModel 		          = $transaction_model;
      $this->OrderDetailsModel              = $order_details_model;
  		$this->SiteSettingModel 		          = $sitesetting_model;
        
      $this->module_url_path          	      = url('/')."/seller/finance";
      $this->photos_base_img_path             = config('app.projesct.img_path.photos');
    	$this->invoice_public_img_path          = url('/').'/'.config('app.project.invoice');
    }

    public function index()
    {
  	  $user              = false;
      $obj_data          = false;
      $arr_pagination    = [];
      $arr_data          = [];
      $arr_transaction   = [];
      $arr_data['total'] = [];
      $seller_id         = '';
      $total             = 0;

      $user = Sentinel::check();
      
      if($user!=false && $user['role']=='seller')
      {
        $seller_id = $user['id'];
        $obj_data = $this->OrderDetailsModel->where('seller_id',$seller_id)
        									                  ->with('transaction_details','listing_details.master_details');

        $total = $obj_data->sum('price');
	  
  		  $obj_data = $obj_data->groupBy('transaction_id')
            			           ->orderBy('id','DESC')
            					       ->paginate(10);          									  
        									  
        if($obj_data!=false)
        {
     		  $arr_data = $obj_data->toArray();
          if(isset($arr_data) && sizeof($arr_data)>0)
          {
            foreach ($arr_data['data'] as $tkey => $data)
            {
              $total =0;
              $total = $this->get_transaction_price($data['transaction_details']['id'],$seller_id);

              $arr_transaction['data'][$tkey]['id']               = $data['transaction_details']['id'];
              $arr_transaction['data'][$tkey]['order_number']     = $data['order_number'];
              $arr_transaction['data'][$tkey]['transaction_date'] = $data['transaction_details']['transaction_date'];
              $arr_transaction['data'][$tkey]['total']            = $total;
              $arr_transaction['data'][$tkey]['commission']       = $data['listing_details']['master_details']['commission'];
              $arr_transaction['data'][$tkey]['status']           = $data['seller_status'];
            }
          }          
          $arr_pagination = $obj_data;
        }
        else
        {
          return redirect()->back();
        }
      }
      else
      {
        return redirect()->back();
      }

      
      $this->arr_view_data['total']                               = $total;
      $this->arr_view_data['arr_pagination']                      = $arr_pagination;
      $this->arr_view_data['arr_transaction']                     = $arr_transaction;
      $this->arr_view_data['module_url_path']                     = $this->module_url_path;
      $this->arr_view_data['invoice_public_img_path']             = $this->invoice_public_img_path;
  	  $this->arr_view_data['title']    							= "Finance - ".config('app.project.name');
     	
     	return view('front.seller.finance.index',$this->arr_view_data);
    }
    
    public function get_transaction_price($transaction_id,$seller_id)
    {
       
        $obj_total_orders = $this->TransactionModel->where('id',$transaction_id)
                                                    ->with(['order_details' => function($query) use ($transaction_id,$seller_id) {
                                                      $query->where('transaction_id',$transaction_id)
                                                            ->where('seller_id',$seller_id)
                                                            ->with('listing_details.master_details');
                                                          }])
                                                    ->OrderBy('created_at','DESC')
                                                    ->first();
        
        $additional_total = 0;
        $total            = 0;
        $commission       = 0;

        if(isset($obj_total_orders) && count($obj_total_orders)>0)
        {
          $total_details = $obj_total_orders->toArray();

          $admin_commission = isset($admin_commission) ? $admin_commission : 0;
          
          if(isset($total_details) && sizeof($total_details)>0)
          {
            foreach ($total_details['order_details'] as $key => $package_data)
            {
              if($package_data['seller_id']==$seller_id)
              {
                $total += $package_data['price'] - (($package_data['price'] * $package_data['listing_details']['master_details']['commission'])/100);
              }
            }
              $total = sprintf("%.2f", $total);
              return $total;
          }
        }
    }

    public function view($enc_id)
    {
    	$user     = false;
      $obj_data = false;
      $arr_data = [];
      $id       = $seller_id = '';

      $user = Sentinel::check();
      
      if($user!=false && $user['role']=='seller' && $enc_id!='')
      {
        $id        = base64_decode($enc_id);	
        $seller_id = $user['id'];	
        $obj_data  = $this->OrderDetailsModel->where('transaction_id',$id)
                          									  ->where('seller_id',$seller_id)
                          									  ->with(['listing_details'=>function($query){
                          									  	$query->with('format_details')
                          									  	      ->with('master_details');
                          									  }])
                          								      ->orderBy('id','DESC')
                          								      ->get();

        if($obj_data!=false)
        {
     		   $arr_data  = $obj_data->toArray();
        }
      }

      $this->arr_view_data['arr_data']                = $arr_data;
      $this->arr_view_data['module_url_path']         = $this->module_url_path;
      $this->arr_view_data['invoice_public_img_path'] = $this->invoice_public_img_path;
  	  $this->arr_view_data['title']    							  = "View Finance - ".config('app.project.name');
     	
     	return view('front.seller.finance.view',$this->arr_view_data);
    }

    public function invoice($enc_transaction_id)
    {
      $arr_invoice                = array();
      $obj_invoice_details        = array();
      $user                       = false;
      $obj_site_settings_details  = false;
      $total_amount               = 0;

      $user = Sentinel::check();

      if($enc_transaction_id!="" && $user!=false && $user['role']=='seller')
      {
        $transaction_id = base64_decode($enc_transaction_id);
        $seller_id      = $user['id'];
        $obj_invoice_details = $this->TransactionModel->where('id',$transaction_id)
                                                      ->with(['order_details' => function($query) use ($transaction_id,$seller_id) {
                                                        $query->where('transaction_id',$transaction_id)
                                                              ->where('seller_id',$seller_id)
                                                              ->with('listing_details.master_details');
                                                            }])
                                                      ->OrderBy('created_at','DESC')
                                                      ->first();
        if(count($obj_invoice_details)<=0)
        {
          return redirect()->back();
        }

        $obj_site_settings_details = $this->SiteSettingModel->where('site_setting_id',1)
                                          ->select('site_name','info_email_address','billing_email_address','site_contact_number','site_address')
                                          ->first();

        if($obj_invoice_details)
        {
          $obj_invoice_details          = $obj_invoice_details->toArray();
          $arr_invoice                  = $obj_invoice_details;
          $arr_invoice['invoice_id']    = $arr_invoice['order_number'];
          $arr_invoice['client_name']   = isset($user)?$user['first_name'].' '.$user['last_name'] : "";  
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

        $html  = '';
        $html .= '<table width="100%" border="0" bgcolor="#2d2d2d" cellspacing="20" cellpadding="0" height="100%" style="border:1px solid #777777;font-family:Arial, Helvetica, sans-serif;margin:0 auto; background:#2d2d2d; height:100%;">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="120px" height="40px" style="background-color: #dbb339; verticle-align: middle; text-align: middlel;" valign="middle">  <p style="color:#2d2d2d;font-size:14.5pt;font-weight:bold;text-decoration: none;width: 120px;height: 40px; display: block;text-align: center; margin-bottom: 0; ">Invoice</p></td>
          <td >
          &nbsp;
          </td>   
          </tr>
      </table>  
    </td>
  </tr>
    
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="10" cellpadding="0" style="background-color:#353535;color:#a4a4a4;font-size:11pt;">
            <tr>
                <td width="60%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>Invoice ID : </td>
                            <td>'.$arr_invoice['invoice_id'].'</td>
                        </tr>
                        <tr>
                            <td>Purchased Date : </td>
                            <td>'.$arr_invoice['invoice_date'].'</td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>To:  '.$arr_invoice['client_name'].'  </td>
                        </tr>
                        <tr>
                            <td>From: '.$arr_invoice['site_name'].' </td>
                        </tr>
                         <tr>
                            <td>'.$arr_invoice['address'].' </td>
                        </tr>
                         <tr>
                            <td>Tel: '.$arr_invoice['contact_number'].' </td>
                        </tr>
                         <tr>
                            <td>Email: '.$arr_invoice['email'].'</td>
                        </tr>
                        <tr>
                            <td><a href="'.url('/').'" style="color:#dbb339;text-decoration: none;">www.filmunit.com</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
    </tr>
  <tr>
      <td style="font-size:18pt;color:#a4a4a4; font-weight:bold; margin-left: 15px;">Product Details</td>
  </tr>
  <tr>
      <td>
          <table width="100%" border="0" cellspacing="10" cellpadding="0" style="background-color:#353535;color:#a4a4a4;font-size:11pt;">
              
             <tr>
               <th width="10%" style="text-align:left;">Sr. No.</th> 
               <th width="40%" style="text-align:left;">Items</th>
               <th width="15%" style="text-align:left;">Unit Price</th>
               <th width="15%" style="text-align:left;">Commission</th>
               <th width="20%" style="text-align:left;">Total</th> 
             </tr>';
              
              if(isset($arr_invoice['order_details']) && count($arr_invoice['order_details'])>0)
              {
                foreach($arr_invoice['order_details'] as $key => $order)
                {
                   $html .='<tr>
                      <td>'.++$key.'</td>
                      <td>'.$order['listing_details']['master_details']['title'].'('.$order['type'].') </td>
                      <td>'.sprintf("%.2f", $order['price']).'</td>
                      <td>'.$order['listing_details']['master_details']['commission'].'%</td>
                      <td>'.sprintf("%.2f",$order['price'] - ($order['price'] * $order['listing_details']['master_details']['commission']/100)).'</td>
                      </tr>';

                      $total_amount += $order['price'] - ($order['price'] * $order['listing_details']['master_details']['commission']/100);
                }
              }

          $html .= '</table>
                    </td>
                </tr>
                <tr>
                    <td>
                    <table width="100%" border="0" cellspacing="10" cellpadding="0">
                         <tr>
                            <td width="60%">&nbsp;</td>
                            <td width="40%">
                                <table width="100%" border="0" cellspacing="10" cellpadding="0" style="font-size:11pt;">
                                    <tr style="text-align:right; color:#a4a4a4;">
                                        <td >Sub Total :</td>
                                        <td style="font-weight: bold;">'.sprintf("%.2f", $total_amount).'</td>
                                    </tr>
                                    <tr style="text-align:right; color:#a4a4a4;">
                                        <td>Total Qty: </td>
                                        <td style="font-weight: bold;">'.count($arr_invoice['order_details']).'</td>
                                    </tr>
                                    
                                    
                                </table>
                                
                            </td>
                            
                        </tr>
                        <tr>
                           <td width="60%"  style="background-color: #353535;">&nbsp;</td>
                            <td width="40%"  style="background-color: #353535;">
                                <table width="100%" border="0" cellspacing="10" cellpadding="0">
                                    <tr style="color:#666666;">
                                        <td>
                                            TOTAL: 
                                        </td>
                                        <td style="font-weight: bold;font-size:11pt">$ '.sprintf("%.2f", $total_amount).'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                   </td>
                  </tr>
                <br><br>
              </table>';
                  
        $file_name = $arr_invoice['invoice_id'];
        PDF::SetTitle('FilmUnit Invoice');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output($file_name.'.pdf');
      }
      else
      {
        return redirect()->back();
      }
    }
}
