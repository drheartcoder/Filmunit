@extends('admin.layout.master')                
@section('main_content')
<style type="text/css">
   .ui-autocomplete
   {
   max-width: 26% !important;
   }
   .mass_min {
   background: #fcfcfc none repeat scroll 0 0;
   border: 1px dashed #d0d0d0;
   float: left;
   margin-bottom: 20px;
   margin-right: 21px;
   margin-top: 10px;
   padding: 5px;
   }
   .mass_addphoto {
   display: inline-block;
   margin: 0 10px;
   padding-top: 27px;
   text-align: center;
   vertical-align: top;
   }
   .mass_addphoto {
   text-align: center;
   }
   .upload_pic_btn {
   cursor: pointer;
   font-size: 14px;
   height: 100% !important;
   left: 0;
   margin: 0;
   opacity: 0;
   padding: 0;
   position: absolute;
   right: 0;
   top: 0;
   }
</style>
<!-- BEGIN Page Title -->
<div class="page-title">
   <div>
   </div>
</div>
<!-- END Page Title -->
<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
   <ul class="breadcrumb">
      <li>
         <i class="fa fa-home">
         </i>
         <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard
         </a>
      </li>
      <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa fa-dollar">
      </i>
      <a href="{{ $back_url or '' }}" class="call_loader">{{ $module_title or ''}}
      </a>
      </span> 
      <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa fa-eye">
      </i>
      </span> 
      <li class="active">   {{ $page_title or '' }}
      </li>
   </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
<div class="col-md-12">
   <div class="box ">
      <div class="box-title">
         <h3>
            <i class="fa fa-eye">
            </i> {{ $page_title or '' }} 
         </h3>
         <div class="box-tool">
         </div>
      </div>
      <div class="box-content">
         <?php
            $order_number    = isset($booking['order_number']) ? $booking['order_number'] : "-";
            $transaction_id  = isset($booking['txn_id']) ? $booking['txn_id'] : "";
            
            $txn_date        = isset($booking['transaction_date']) ? date('d F,Y h:i A',strtotime($booking['transaction_date'])) : "";
            $total           = isset($booking['total']) ? $booking['total'] : "";
            $first_name      = isset($booking['buyer_details']['first_name']) ? $booking['buyer_details']['first_name'] : "";
            $last_name       = isset($booking['buyer_details']['last_name']) ? $booking['buyer_details']['last_name'] : "";
            $user_name       = $first_name.' '.$last_name;
            $email           = isset($booking['buyer_details']['email']) ? $booking['buyer_details']['email'] : "";
            $address         = isset($booking['buyer_details']['address']) ? $booking['buyer_details']['address'] : "";
            $contact_number  = isset($booking['buyer_details']['contact_number']) ? $booking['buyer_details']['contact_number'] : "";
            ?>
         <div class="box">
            <div class="box-content studt-padding">
               <div class="row">
                  <div class="col-md-8">
                     <h3>Order Information</h3>
                     <br>
                     <table class="table table-bordered">
                        <tbody>
                           <tr>
                              <th style="width: 30%">Order Number
                              </th>
                              <td>
                                 {{$order_number or ''}}
                              </td>
                           </tr>
                           <tr>
                              <th style="width: 30%">Order Date 
                              </th>
                              <td>
                                 {{$txn_date or ''}}
                              </td>
                           </tr>
                           <tr>
                              <th style="width: 30%">Transaction ID
                              </th>
                              <td>
                                 {{$transaction_id or ''}}
                              </td>
                           </tr>
                           <tr>
                              <th style="width: 30%">Order Amount
                              </th>
                              <td>
                                 ${{$total or 'NA'}}
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                                   
                  <div class="col-md-8">                    
                     <div class="col-md-8">
                        <h3>Purchaser Information</h3>
                        <br>
                        <table class="table table-bordered">
                           <tbody>
                              <tr>
                                 <th style="width: 30%">User Name
                                 </th>
                                 <td>
                                    {{$user_name or ''}}
                                 </td>
                              </tr>
                              <tr>
                                 <th style="width: 30%">Email 
                                 </th>
                                 <td>
                                    {{$email or ''}}
                                 </td>
                              </tr>
                              <tr>
                                 <th style="width: 30%">Address
                                 </th>
                                 <td>
                                    {{$address or ''}}
                                 </td>
                              </tr>
                              <tr>
                                 <th style="width: 30%">Contact Number
                                 </th>
                                 <td>
                                    {{$contact_number or ''}}
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>     
                  </div>

                  @if(isset($seller_details) && count($seller_details)>0)
                  <div class="col-md-8">                    
                     <div class="col-md-8">
                        <h3>Seller Information</h3>
                        <br>
                        <table class="table table-bordered">
                           <tbody>
                              <tr>
                                 <th style="width: 30%">User Name
                                 </th>
                                 <td>
                                    {{$seller_details['seller_name'] or ''}}
                                 </td>
                              </tr>
                              <tr>
                                 <th style="width: 30%">Email 
                                 </th>
                                 <td>
                                 {{$seller_details['seller_email'] or ''}}                                 
                                 </td>
                              </tr>
                              <tr>
                                 <th style="width: 30%">Address
                                 </th>
                                 <td>
                                 {{$seller_details['seller_address'] or '-'}}
                                    
                                 </td>
                              </tr>
                              <tr>
                                 <th style="width: 30%">Contact Number
                                 </th>
                                 <td>
                                 {{$seller_details['seller_contact_number'] or '-'}}                                    
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>     
                  </div>
                  @endif
               </div>
                <div class="clearfix"></div>                
                <h3>Transaction Details</h3>
          @include('admin.layout._operation_status')  
          <div class="table-responsive" style="border:0">
            <input type="hidden" name="multi_action" value="" />
            <table class="table table-advance"  id="table_module" >
              <thead>
                  <tr>                     
                     <th>Title</th>                     
                     <th>Type</th>
                     <th>Item</th>
                     <th>Price</th>                     
                     <th>Format</th>
                     <th>Orientation</th>
                     <th>Resolution</th>
                     @if(empty($seller_details) && count($seller_details)==0)
                     <th>Download Attempts</th>
                     @endif
                  </tr>
              </thead>
              <tbody>             
                @if(isset($booking_details) && sizeof($booking_details)>0)
                  @foreach($booking_details as $row)
                  <tr>                                          
                      <td>{{isset($row['title'])&&$row['title']!=''?$row['title']:"N/A"}}</td>                       
                      <td> {{isset($row['type'])&&$row['type']!=''?$row['type']:"N/A"}} </td>
                      <td> @if($row['type']=='Footage')
                           <video width="150px" height="150px" controls><source src="{{isset($row['enc_item_name'])?$row['enc_item_name']:""}}" type="video/mp4"></video>   
                           @else
                           <img src="{{isset($row['enc_item_name'])?$row['enc_item_name']:""}}" style="width: 150px;height: 150px;">
                           @endif
                      </td>                       
                      <td>${{isset($row['price'])&&$row['type']!=''?$row['price']:"N/A"}}</td>                                             
                      <td>{{isset($row['format_name'])&&$row['format_name']!=''?$row['format_name']:"N/A"}}</td>    
                      <td>{{isset($row['value'])&&$row['value']!=''?$row['value']:"N/A"}}</td>    
                      <td>{{isset($row['size'])&&$row['size']!=''?$row['size']:"N/A"}}</td>                                            
                      @if(empty($seller_details) && count($seller_details)==0)
                      <td>
                      <form method="post">
                       {{ csrf_field() }} 
                      <input type="hidden" name="id" value="{{$row['order_details_id']}}">
                      <input type="text" class="form-control" id="download_attempt" name="download_attempt" value="{{$row['download_attempt']}}" style="width: 70px; float: left;margin-right: 5px;">
                      <button type="submit" class="btn btn-primary" id="btnUpdateAttempt" name="btnUpdateAttempt" value="Update"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                      </form>
                      </td>
                      @endif
                  </tr>
                  @endforeach       
                @endif  
              </tbody>
            </table>
          </div>                
            </div>
         </div>
      </div>
   </div>
</div>
<!-- END Main Content --> 
@endsection

