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
                    <div class="light-grey-bg-block">
                        <h1 class="accou-heat"> Finance </h1>
                            @if(isset($arr_data['data']) && count($arr_data['data'])>0)
                            <div class="order-list-text"> Orders List </div>
                           <div class="table-responsive ">
                           <table class="table border-none">
                               <tr class="head-title-bx">
                                   <th style="width: 10%; text-align: left;">Sr no</th>
                                   <th style="width: 35%;" >Order ID</th>
                                   <th style="width: 26%;">Date</th>
                                   <th style="width: 26%;">Total</th>
                                   <th style="width: 11%;">Status</th>
                                   <th>Action</th>
                               </tr>
                               @if(isset($arr_data['data']) && count($arr_data['data'])>0)
                               @foreach($arr_data['data'] as $key=>$value)
                               <tr>
                                   <td align="center">{{$key+1}}</td>
                                   <td class="table-cell-break">{{isset($value['order_number'])?$value['order_number']:'NA'}}</td>
                                   <td>{{isset($value['transaction_date']) && $value['transaction_date']!= null ? date('d M Y',strtotime($value['transaction_date'])) : ""}}</td>
                                   <td>{{isset($value['total'])?'$ '.$value['total']:'NA'}}</td>
                                   <td>{{isset($value['status'])?ucwords($value['status']):'NA'}}</td>
                                   <td><a class="delet-i" href="{{$module_url_path.'/view/'.base64_encode($value['id'])}}"><i class="fa fa-eye"></i></a> 
                                       <a class="delet-i" href="{{$invoice_public_img_path.$value['invoice']}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a> 
                                   </td>
                               </tr>
                               @endforeach
                               @endif

                             </table>
                           </div>
                           
                      <div class="clr"></div> 
                    </div>
                   <!-- Paination Links -->
                    @include('front.templates.pagination_view')
                    @else
                    <h3 style="text-align: center; color: #FFF"><center>No records Found.</center></h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->

@endsection

