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

@if(isset($arr_data) && count($arr_data)>0)
  <!--Cart Page Start Here-->
   <div class="buying-process-main">
        <div class="cart-section-block">
           
             <div class="cart-step-one">
                <div class="step-one step-active">
                    <span class="num-step font-style"><img src="{{url('/')}}/images/cart-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Cart</span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="cart-step-one">
                <div class="step-one">
                    <span class="num-step font-style"><img src="{{url('/')}}/images/payment-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Payment</span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="cart-step-one">
                <div class="step-one">
                    <span class="num-step font-style"><img src="{{url('/')}}/images/order-placed-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Order Placed</span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endif    
<!--    List-->
   <div class="container margin-top-new">
        <!--    Flash message Blade  -->
        @include('front.layout._operation_status')

       <div class="light-grey-bg-block cart-views bg-none @if(isset($arr_data) && count($arr_data)<=0) min-heightsone @endif" id="cart_update">
          <div class="subtitles space-botms"> Cart </div>
       
        <?php $grand_total = 0; ?>       
        @if(isset($arr_data) && count($arr_data)>0)
        @foreach($arr_data as $key=>$value)
        <?php $grand_total += $value['price']; ?>
        <div class="download-text-blo marg-top">
            <div class="img-block"> 
            @if($value['master_details']['type']=='photo')
            <img src="{{$admin_photos_public_img_path.$value['master_details']['admin_enc_item_name']}}" alt="{{$value['master_details']['title']}}" /> 
            @else
            <img src="{{$admin_footage_image_public_path.$value['master_details']['admin_enc_footage_image']}}" alt="{{$value['master_details']['title']}}" />
            @endif
            </div>
            <div class="downlo-text-blo-1">
                <h1> {{ucwords($value['master_details']['title'])}} {{'('.ucwords($value['type']).')'}} </h1>
                
                <h3> @if($value['type']=='photo'){{isset($arr_image_size['size'][$key])? $arr_image_size['size'][$key] : '' }} | @endif {{isset($value['listing_details']['format_details'])? strtoupper($value['listing_details']['format_details']['name']) : 'NA'}} | {{isset($arr_image_size['filesize']) ? $arr_image_size['filesize'][$key] : ''}} </h3>
{{--                 @else
                <h3> {{isset($value['master_details']['listing_details'][0]) ? strtoupper($value['master_details']['listing_details'][0]['format_details']['name']) : 'NA'}} | {{isset($arr_image_size['filesize']) ? $arr_image_size['filesize'][$key] : ''}} </h3>   --}}              
            </div>
            <div class="downlo-text-blo-2">
                
                <h3> $ {{$value['price']}} </h3>
            </div>
            @if(isset($value['master_details']['listing_details']) && count($value['master_details']['listing_details'])>1)
            <div class="downlo-text-blo-2 cart-film">
                <a class="edit_cart" data-master-id="{{base64_encode($value['master_id'])}}" data-list-id="{{base64_encode($value['list_id'])}}" data-listid="{{$value['list_id']}}" data-price="{{$value['price']}}" data-cart-id="{{base64_encode($value['id'])}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil-square-o"></i></a>
            </div>
            @endif
            <div class="downlo-close-icon">
                <a href="{{$module_url_path.'/delete/'.base64_encode($value['id'])}}" onclick="return confirm_action(this,event,'Do you really want to remove this item ?')"></a>
            </div>
            <div class="clr"></div>
        </div>
        @endforeach
        <div class="total-carts">
            Total <span>$ {{$grand_total}}</span>
        </div>
        @if($buyer_id!='')
        <div class="button-section cart-btnss"><a href="{{url('/')}}/checkout">Checkout</a> </div>
        @else
        <div class="button-section cart-btnss"><a href="{{url('/')}}/login">Sign In</a> </div>
        @endif
   </div>
   </div>
   @else
   <h3 align="center" style="color: #fff">No Items added to the cart.</h3>
   @endif

<!-- toast starts id="add_toast"-->    
<div class="toast-set" id="add_toast"></div> 
<!-- toast ends-->
    <!--Cart Page end Here-->
    
<!-- Edit Cart Page Starts Here -->
<!-- Modal -->
<div id="myModal" class="listing-details-popup edit-cart-popup modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
       <!--     Listing Detail Start Here-->
       
       <input type="hidden"  id="enc_list_id" name="enc_list_id">
       <input type="hidden"  id="enc_master_id" name="enc_master_id">
       <input type="hidden"  id="enc_cart_id" name="enc_cart_id">
       
          <div class="listing-detail reproduced-section">
            <div class="close-pop-icon"><a href="#" data-dismiss="modal"><img src="images/close-seller.png" alt="" /></a></div>
            <div class="modal-body-details">
               <div class="row">
                   <div class="col-xs-12">
                          <div class="left-section">
                    <div class="image-listing-details"><img src="images/details-upload-img.jpg" alt="" /></div>
                    <div class="buttons-details type-option">
                            <span id="formats"></span>                                                     
                    </div>
                </div>
                       <div class="detail-right-bottom">
                           <div class="media_title price-dollers text-left"></div>
                           <div class="price-dollers new-price price_dollers"></div>
                                <div class="button-section btn-lisitng"><a href="javascript:void(0)" onclick="update_cart()">Update</a> </div>
                                
                                <div class="clearfix"></div>
                           </div>
                   </div>
               </div>
            </div>
        </div>
       <!--     Listing Detail End Here-->
      </div>
    </div>
  </div>
</div>

<!-- Modal of edit cart page ends here -->    

<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/admin/sweetalert_msg.js"></script>    

<script type="text/javascript">

//hide Toast
$(document).on('click','.toast',function(){

  $(this).css("display","none");
  
});

  $(document).on('click','.edit_cart',function(){
        
    var enc_master_id = $(this).attr('data-master-id');
    var enc_list_id   = $(this).attr('data-list-id');
    var list_id       = $(this).attr('data-listid');
    var enc_cart_id   = $(this).attr('data-cart-id');
    var token         = $('#token').val();

    var admin_photo_url = '{{$admin_photos_public_img_path}}';
    var admin_footage_url = '{{$admin_footage_image_public_path}}';    

    $('#enc_list_id').val($(this).attr('data-list-id'));
    $('#enc_master_id').val($(this).attr('data-master-id'));
    $('#enc_cart_id').val($(this).attr('data-cart-id'));

    var Site_URL = '{{url('/')}}/cart/edit';

    $.ajax({
                'url':Site_URL,                    
                'type':'post',
                'data':{'enc_master_id' : enc_master_id, 'enc_list_id' : enc_list_id, 'enc_cart_id':enc_cart_id, '_token' : token},
                success:function(res)   
                {
                    $('#formats').html('');
                    
                    if(res.arr_master_details!='')
                    {
                        $('.media_title').html(res.arr_master_details.title.charAt(0).toUpperCase() + res.arr_master_details.title.substr(1));
                        $.each( res.arr_master_details.listing_details, function( key, value ) {
                            if(value.id==list_id){ var checked = "checked"; } else {var checked = ""; }
                            $('#formats').append(
                                '<div class="radio-sections"><div class="radio-btn"><input type="radio" data-list-id="'+btoa(value.id)+'" data-master-id="'+enc_master_id+'" class="option_price" id="f-option_'+key+'" '+checked+' name="price" value="'+value.price+'"/>'+
                                '<label for="f-option_'+key+'">'+
                                '<div class="interior-icon">'+value.format_details.name+'</div>'+
                                '<p>$'+value.price+'</p></label><div class="check"></div></div></div>'
                                );    
                        });
                    }
                        
                        if(res.arr_master_details.type=='photo')
                        {
                          $('.image-listing-details').html('<img src="'+admin_photo_url+res.arr_master_details.admin_enc_item_name+'">');
                        }
                        else
                        {
                          $('.image-listing-details').html('<img src="'+admin_footage_url+res.arr_master_details.admin_enc_footage_image+'">');
                        }

                        $('.price_dollers').html('$'+ $('input[name="price"]:checked').val());
                        $('#enc_cart_id').val(res.enc_cart_id);
                }
            });
  })  

//on Selection
$(document).on('click','.option_price',function()
{
    $('.price_dollers').html('$'+ $(this).val());
    $('#enc_list_id').val($(this).attr('data-list-id'));
    $('#enc_master_id').val($(this).attr('data-master-id'));
});

function update_cart()
{
  var Site_URL      = '{{url('/')}}/cart/update';
  var token         = "{{csrf_token()}}";  
  var enc_master_id = $('#enc_master_id').val();
  var enc_list_id   = $('#enc_list_id').val();
  var enc_cart_id   = $('#enc_cart_id').val();

    $.ajax(
    {
        'url':Site_URL,                    
        'type':'post',
        'data':{'enc_master_id':enc_master_id, 'enc_list_id':enc_list_id, 'enc_cart_id':enc_cart_id, '_token':token},
        success:function(res)   
        {
          if(res.status=='fail')
          {
            $('#myModal').modal('toggle');
            $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
            '<i class="fa fa-times" aria-hidden="true"></i></a>'+
            '<p class="message">'+res.msg+' </p></div>');                       
          }
          else
          {

            $('.cart-views').load(location.href+" .cart-views");
            
            $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
            '<i class="fa fa-times" aria-hidden="true"></i></a>'+
            '<p class="message">'+res.msg+' </p></div>');
            
            $('#myModal').modal('toggle');
          }
        }
    });    
}

</script>

@endsection

