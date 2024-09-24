@if(isset($arr_invoice) && count($arr_invoice)>0)
<table width="100%" border="0" bgcolor="#2d2d2d" cellspacing="20" cellpadding="0" height="100%" style="border:1px solid #777777;font-family:Arial, Helvetica, sans-serif;margin:0 auto; background:#2d2d2d;">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="120px" style="background-color: #dbb339; verticle-align: middle; text-align: center; color:#2d2d2d;font-size:14.5pt;font-weight:bold;text-decoration: none;width: 120px; text-align: center; margin-bottom: 0; " valign="middle">  Invoice</td>
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
                            <td>{{isset($arr_invoice['invoice_id'])?$arr_invoice['invoice_id'] :'NA'}}</td>
                        </tr>
                        <tr>
                            <td>Purchased Date : </td>
                            <td>{{isset($arr_invoice['invoice_date'])?$arr_invoice['invoice_date'] :'NA'}}</td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>To:  {{isset($arr_invoice['buyer_details'])?$arr_invoice['buyer_details']['first_name'].' '.$arr_invoice['buyer_details']['last_name'] :'NA'}}  </td>
                        </tr>
                        <tr>
                            <td>From: {{isset($arr_invoice['site_name'])?$arr_invoice['site_name'] :'NA'}} </td>
                        </tr>
                         <tr>
                            <td>{{isset($arr_invoice['address'])?ucwords($arr_invoice['address']) :'NA'}} </td>
                        </tr>
                         <tr>
                            <td>Tel: {{isset($arr_invoice['contact_number'])?$arr_invoice['contact_number'] :'NA'}} </td>
                        </tr>
                         <tr>
                            <td>Email: {{isset($arr_invoice['email'])?$arr_invoice['email'] :'NA'}}</td>
                        </tr>
                        <tr>
                            <td><span style="color:#dbb339;text-decoration: none;">www.filmunit.com</span></td>
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
               <th width="58%" style="text-align:left;">Items</th>
               <th width="12%" style="text-align:left;">Unit Price</th>
               <th width="10%" style="text-align:left;">Qty</th>
               <th width="10%" style="text-align:left;">Total</th> 
             </tr>
              @if(isset($arr_invoice['order_details']) && count($arr_invoice['order_details'])>0)
              @foreach($arr_invoice['order_details'] as $key => $order)
               <tr>
                  <td>{{$key+1}}</td>
                  <td>{{isset($order['listing_details']['master_details']['title']) ? ucwords($order['listing_details']['master_details']['title']):'NA'}} ({{isset($order['type']) ? ucwords($order['type']):'NA'}})</td>
                  <td>{{isset($order['listing_details']['price'])?' $'.sprintf("%.2f", $order['listing_details']['price']):'NA'}}</td>
                  <td>1</td>
                  <td>{{isset($order['listing_details']['price'])?' $'.sprintf("%.2f", $order['listing_details']['price']):'NA'}}</td>
              </tr>
              @endforeach
              @endif
          </table>
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
                              <td style="font-weight: bold;">{{isset($total_amount)?' $'.sprintf("%.2f", $total_amount):'-'}}</td>
                          </tr>
                          <tr style="text-align:right; color:#a4a4a4;">
                              <td>Total Qty: </td>
                              <td style="font-weight: bold;">{{count($arr_invoice['order_details'])}}</td>
                          </tr>
                          
                          
                      </table>
                      
                  </td>
                  
              </tr>
              <tr>
                 <td width="60%"  style="background-color: #353535;">&nbsp;</td>
                  <td width="40%"  style="background-color: #353535;">
                      <table width="100%" border="0" cellspacing="10" cellpadding="0">
                          <tr style="color:#fafafa; text-align:right;">
                              <td>
                                  TOTAL: 
                              </td>
                              <td style="font-weight: bold;color:#fafafa;font-size:11pt; text-align: right;">{{isset($total_amount)?' $'.sprintf("%.2f", $total_amount):'-'}}</td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
      </td>
  </tr>
<br><br>
</table>
@endif   