@extends('front.layout.master')    
@section('main_content')

<?php
$user     = Sentinel::check();
$buyer_id = '';

if($user!=false && $user['role']!='admin' && $user['role']!='seller')
{
  $buyer_id = $user['id'];
}
?>

    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="buyer-account-bg-main min-heightsone">
        <div class="container">
            <div class="row">
                <!-- BEGIN Sidebar -->
                @include('front.layout._sidebar')
                <!-- END Sidebar -->            
                <div id="left-bar-buyer"></div>
                <div class="col-sm-8 col-md-8 col-lg-9">
                @if(isset($arr_data) && count($arr_data)>0)
                    <div class="light-grey-bg-block">
                        <h1 class="accou-heat"> Finance </h1>
                            <div class="order-list-text"> Orders List </div>
                           <div class="table-responsive ">
                           <table class="table border-none">
                               <tr class="head-title-bx">
                                   <th style="width: 10%;">Sr no</th>
                                   <th style="width: 35%;" >Order ID</th>
                                   <th style="width: 26%;" >Title</th>
                                   <th style="width: 13%;" >Type</th>
                                   <th style="width: 13%;" >Format</th>
                                   <th style="width: 10%;">Thumbnail</th>
                                   <th style="width: 11%;" >Price</th>
                               </tr>
                               @foreach($arr_data as $key=>$value)
                               <tr>
                                   <td align="center">{{$key+1}}</td>
                                   <td class="table-cell-break">{{isset($value['order_number'])?$value['order_number']:'NA'}}</td>
                                   <td >{{isset($value['listing_details']['master_details']['title'])?ucwords($value['listing_details']['master_details']['title']):'NA'}}</td>
                                   <td >{{isset($value['type'])?ucwords($value['type']):'NA'}}</td>
                                   <td >{{isset($value['listing_details']['format_details']['name'])?ucwords($value['listing_details']['format_details']['name']):'NA'}}</td>
                                   <td >
                                     @if($value['type']=='photo')
                                        @if($value['listing_details']['master_details']['admin_enc_item_name']!='')
                                          <img src="{{$admin_photos_public_img_path.$value['listing_details']['master_details']['admin_enc_item_name']}}" style="height: 55px;width: 90px">
                                        @else
                                          <img src="{{$admin_photos_public_img_path}}/default.png" style="height: 55px;width: 90px">
                                        @endif
                                     @else
                                        @if($value['listing_details']['master_details']['admin_enc_footage_image']!='')
                                          <img src="{{$admin_footage_image_public_path.$value['listing_details']['master_details']['admin_enc_footage_image']}}" style="height: 55px;width: 90px">
                                        @else
                                          <img src="{{$admin_footage_image_public_path}}/default.png" style="height: 55px;width: 90px">
                                        @endif
                                     @endif
                                   </td>
                                   <td >{{isset($value['price'])?'$'.$value['price']:'NA'}}</td>
                               </tr>
                               @endforeach

                             </table>
                            
                            
                           </div>
                           
                      <div class="clr"></div> 
                    </div>
                        
                      <div class="button-section right-side tp-btns"><a href="{{ url('/')}}/buyer/finance">Back</a> </div>
             <!-- Paination Links -->
              @else
              <span style="text-align: center; color: #FFF">No records Found.</span>
              @endif

                </div>
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->

@endsection

