@extends('front.layout.master') @section('main_content')


<script src="{{url('/')}}/js/front/jquery.min.js" type="text/javascript"></script>
<script src="{{url('/')}}/js/front/left-menu-jquery.js" type="text/javascript"></script>
<script src="{{url('/')}}/js/front/jquery-ui.js" type="text/javascript"></script>

<script src="{{url('/')}}/js/front/stickyfloat.js"></script>
<!--range css start here-->
<link rel="stylesheet" href="{{url('/')}}/css/admin/range-slider.css" />
<link rel="stylesheet" href="{{url('/')}}/css/admin/jquery-ui.css" />
<?php
$role = '';
$i = 2;
$j = 3;
$class = '';
$jclass = '';

$user = Sentinel::check();
if($user!=false)
{
  $role = $user['role']; 
}
 ?>
    <!--    Listing Page Here-->
    <div class="listing-page margin-tp-btm overflow-visible co-page @if(isset($arr_data) && count($arr_data['data'])<=0) min-heightsone @endif">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="subtitles space-botms"> {{ucwords($package_title)}} </div>
                    <!--    Flash message Blade  -->
                    <div class="alert" style="display: none">
                        <span class="message"></span>
                    </div>
                </div>
            </div>
            <div class="row grid">
                @if(isset($arr_data) && count($arr_data['data'])>0) @foreach($arr_data['data'] as $key => $list)
               
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-left-15px listing-box ">
{{--                    <div class="common-effect effect-zoe">
						@if(isset($list) && count($list)>0 && $list['listing_details']['master_details']!='')
                        @if($list['listing_details']['master_details']['type']=='photo')
                        <img src="{{$admin_photos_public_img_path.$list['listing_details']['master_details']['admin_enc_item_name']}}" alt="{{$list['listing_details']['master_details']['title']}}" />
                        @endif
                        @if($list['listing_details']['master_details']['type']=='footage')
                        <img src="{{$admin_footage_image_public_path.$list['listing_details']['master_details']['admin_enc_footage_image']}}" alt="{{$list['listing_details']['master_details']['title']}}" />
                        @endif
						<div class="content-hover">
							<div class="view-txt-more">
                                 @if($role!='seller' && $role!='admin')  
                                  <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($list['listing_details']['master_details']['id'])}}" data-list-id="{{base64_encode($list['list_id'])}}"><i class="fa fa-shopping-cart" ></i></a>
                                  <!--<a href="javascript:void(0)"><i class="fa fa-plus" ></i></a>-->
                                  @if($user==false)
                                    <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @else
                                    <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($list['list_id'])}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @endif
                                  <!--<a href="javascript:void(0)"><i class="fa fa-eye" ></i></a>-->
                              @endif
                                  <a href="{{$module_url_path.'/view/'.$list['listing_details']['master_details']['slug']}}" ><i class="fa fa-eye" ></i></a>
                            
							</div>
						</div>
						 @endif
					</div> 
 --}}

                    
{{--                         <span class="effect-oscar">
                        
                          <span class="over-txt-block resize">
                              <span class="view-txt-more">
                              
                                  <h4> <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">{{ucwords($list['listing_details']['master_details']['type'])}}</a></h4>
                                  <h5>{{ucwords($list['listing_details']['master_details']['title'])}}</h5>
                              </span>
                                <span class="footer-hover">
                                      <span class="flex-hover-cont">
                                          <span class="row">
                                              
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><span></span></p>
                                           </span>
                                      </span>
                                </span>
                            </span> --}}

                        @if(isset($list) && count($list)>0 && $list['listing_details']['master_details']!='')
                         <div class="common-effect effect-zoe">
                            <a href="{{$module_url_path.'/view/'.$list['listing_details']['master_details']['slug']}}" >
                            @if($list['listing_details']['master_details']['type']=='photo')
                            <img src="{{$admin_photos_public_img_path.$list['listing_details']['master_details']['admin_enc_item_name']}}" alt="{{$list['listing_details']['master_details']['title']}}" />
                            @endif
                            @if($list['listing_details']['master_details']['type']=='footage')
                            <img src="{{$admin_footage_image_public_path.$list['listing_details']['master_details']['admin_enc_footage_image']}}" alt="{{$list['listing_details']['master_details']['title']}}" />
                            @endif
                               </a>
                                <div class="content-hover">
                                <div class="view-txt-more">
                                 @if($role!='seller' && $role!='admin')  
                                  <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($list['listing_details']['master_details']['id'])}}" data-list-id="{{base64_encode($list['list_id'])}}"><i class="fa fa-shopping-cart" ></i></a>
                                  @if($user==false)
                                    <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @else
                                    <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($list['list_id'])}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @endif
                                  {{-- <a href="javascript:void(0)"><i class="fa fa-eye" ></i></a> --}}
                                  @endif
                                  <a href="{{$module_url_path.'/view/'.$list['listing_details']['master_details']['slug']}}" ><i class="fa fa-eye" ></i></a>
                                </div>
                            </div>  
                         </div>

                @if($key==$i)
                   <?php 
                      $i = $i + 4;
                      $class = 'last';
                   ?>
                @else
                   <?php $class = ''; ?>
                @endif
                @if($key==$j)
                   <?php 
                      $j = $j + 4;
                      $jclass = 'last';
                   ?>
                @else
                   <?php $jclass = ''; ?>                   
                @endif   

                        <div class="preview-panel hidden-md hidden-sm hidden-xs {{$class}} {{$jclass}}"> @if($list['listing_details']['master_details']['type']=='photo')
                        <img src="{{$admin_photos_public_img_path.$list['listing_details']['master_details']['admin_enc_item_name']}}" alt="{{ucwords($list['listing_details']['master_details']['title'])}}" />
                        @endif
                        @if($list['listing_details']['master_details']['type']=='footage')
                        <video src="{{$admin_footage_public_img_path.$list['listing_details']['master_details']['admin_enc_item_name']}}" autoplay="" loop="" width="400px" height="auto"></video>
                        @endif                         

                             <div class="preview-panel-desc"><div class="preview-panel-left"> <h4 class="gold-color">{{ucwords($list['listing_details']['master_details']['title'])}}</h4>
                                      <h5>{{ucwords($list['listing_details']['master_details']['type'])}}</h5></div>
                                      <div  class="preview-panel-right">
                                                 <p class="format-price">${{$list['listing_details']['price']}}</p>
                                                   @if($list['listing_details']['master_details']['type']=='photo')
                                                      <p class="format-name">{{ucwords($list['listing_details']['format_details']['name'])}}</p>
                                                  @else
                                                      <p class="format-name">{{ucwords($list['listing_details']['format_details']['name'])}}{{-- {{ucwords($list->resolution_value)}} --}}</p>
                                                  @endif
                                       </div>
                             </div>
                         </div>  

                        @endif
                       {{-- </span> --}}
                   
                </div>
                
                @endforeach @else
                <h3 style="color: #FFF" align="center">No Result Found.</h3>
                @endif
            </div>


            <!-- Paination Links -->
            <div class="row">
                <div class="col-md-12">
                    @include('front.templates.pagination_view')
                </div>
            </div>

        </div>
    </div>
    <!--    Listing Page End Here-->
    <div class="clearfix"></div>

    <!-- toast starts id="add_toast"-->
    <div class="toast-set" id="add_toast"></div>
    <!-- toast ends-->

    <!-- Modal -->
    <div id="myModal" class="listing-details-popup edit-cart-popup modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <!--     Listing Detail Start Here-->
                    <input type="hidden" id="enc_master_id" name="enc_master_id">
                    <input type="hidden" id="enc_list_id" name="enc_list_id">

                    <div class="listing-detail reproduced-section">
                        <div class="close-pop-icon"><a href="#" data-dismiss="modal"><img src="{{url('/')}}/images/close-seller.png" alt="" /></a></div>
                        <div class="modal-body-details">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="left-section">
                                        <div class="image-listing-details"><img src="{{url('/')}}/images/details-upload-img.jpg" alt="" /></div>
                                        <div class="buttons-details">
                                            <span id="formats"></span>
                                        </div>
                                    </div>
                                    <div class="detail-right-bottom">
                                        <div class="media_title price-dollers text-left"></div>
                                        <div class="price-dollers new-price price_dollers"></div>
                                        <div class="button-section btn-lisitng"><a href="javascript:void(0)" id="add_to_cart">Add To Cart</a> </div>
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

    <!--/*price range slider*/-->
    <script>
        //hide Toast
        $(document).on('click', '.toast', function() {

            $(this).css("display", "none");

        })

        //Add to cart service
        function add_to_cart(enc_master_id, enc_list_id) {
            var Site_URL = '{{url(' / ')}}/cart/add';
            var token = "{{csrf_token()}}";

            $.ajax({
                'url': Site_URL,
                'type': 'post',
                'data': {
                    'enc_master_id': enc_master_id,
                    'enc_list_id': enc_list_id,
                    '_token': token
                },
                success: function(res) {
                    $('#myModal').modal('toggle');
                    if (res.status == 'success') {
                        $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">' +
                            '<i class="fa fa-times" aria-hidden="true"></i></a>' +
                            '<p class="message">Item has been added to cart </p></div>');
                    } else {
                        $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">' +
                            '<i class="fa fa-times" aria-hidden="true"></i></a>' +
                            '<p class="message">This item is already present in the cart </p></div>');
                    }
                    $('.set-cart-count').html(res.cart_count);
                }

            });
        }

        //Add to Favourite service
        function add_to_favourite(enc_list_id) {
            var Site_URL = '{{url('/')}}/my_collection/add';
            var token = "{{csrf_token()}}";

            $.ajax({
                'url': Site_URL,
                'type': 'post',
                'data': {
                    'enc_list_id': enc_list_id,
                    '_token': token
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">' +
                            '<i class="fa fa-times" aria-hidden="true"></i></a>' +
                            '<p class="message">Item has been added to my collection </p></div>');

                        $('.set-favourite-count').html(res.favourite_count);
                    } else {
                        $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">' +
                            '<i class="fa fa-times" aria-hidden="true"></i></a>' +
                            '<p class="message">This item is already present in my collection </p></div>');
                    }
                }

            });
        }

        $(document).on('click', '#open_cart_modal', function() {

            var enc_master_id = $(this).attr('data-master-id');
            var token = $('#token').val();
            var admin_photo_url = '{{$admin_photos_public_img_path}}';
            var admin_footage_url = '{{$admin_footage_image_public_path}}';

            $('#enc_master_id').val($(this).attr('data-master-id'));

            var Site_URL = '{{url('/')}}/cart/get_sublisting';

            $.ajax({
                'url': Site_URL,
                'type': 'post',
                'data': {
                    'enc_master_id': enc_master_id,
                    '_token': token
                },
                success: function(res) {
                    $('#formats').html('');

                    if (res.arr_master_details != '') {
                        $('.media_title').html(res.arr_master_details.title.charAt(0).toUpperCase() + res.arr_master_details.title.substr(1));
                        $.each(res.arr_master_details.listing_details, function(key, value) {
                            if (key == 0) {
                                var checked = "checked";
                            } else {
                                var checked = "";
                            }
                            $('#formats').append(
                                '<div class="radio-sections"><div class="radio-btn"><input type="radio" data-list-id="' + btoa(value.id) + '" data-master-id="' + enc_master_id + '" class="option_price" id="f-option_' + key + '" ' + checked + ' name="price" value="' + value.price + '"/>' +
                                '<label for="f-option_' + key + '">' +
                                '<div class="interior-icon">' + value.format_details.name + '</div>' +
                                '<p>$' + value.price + '</p></label><div class="check"></div></div></div>'
                            );
                        });
                    }
                    if (res.arr_master_details.type == 'photo') {
                        $('.image-listing-details').html('<img src="' + admin_photo_url + res.arr_master_details.admin_enc_item_name + '">');
                    } else {
                        $('.image-listing-details').html('<img src="' + admin_footage_url + res.arr_master_details.admin_enc_footage_image + '">');
                    }

                    $('.price_dollers').html('$' + $('input[name="price"]:checked').val());
                    $('#enc_list_id').val($('input[name="price"]:checked').attr('data-list-id'));
                }
            });
        })

        //on Selection
        $(document).on('click', '.option_price', function() {
            $('.price_dollers').html('$' + $(this).val());
            $('#enc_list_id').val($(this).attr('data-list-id'));
            $('#enc_master_id').val($(this).attr('data-master-id'));
        });

        $(document).on('click', '#add_to_cart', function() {
            var enc_master_id = $('#enc_master_id').val();
            var enc_list_id = $('#enc_list_id').val();

            add_to_cart(enc_master_id, enc_list_id);

        });
    </script>
    @endsection