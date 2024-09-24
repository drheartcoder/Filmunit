@extends('front.layout.master')    
@section('main_content')

<?php 

  if(isset($user) && $user!="")
  
  $user_id     = "";
  $role        = "";
  $profile_img = "";
  $user        = Sentinel::check();
  $i = $k = 2;
  $j = $l =3;
  $class  = $kclass  =  '';
  $jclass = $lclass = '';

  if($user!=false)
  {
    $user_id = $user['id'];
    $role    = $user['role'];
  }

?>
   <div class="clearfix"></div>
    @if(isset($arr_data) && count($arr_data)>0)
 
    <input type="hidden" id="enc_master_id" value="{{base64_encode($arr_data['id'])}}">
    <input type="hidden" id="enc_list_id" value="">
   
   <!--     Listing Detail Start Here-->
      <div class="listing-detail reproduced-section listing-details-main">
        <div class="modal-body-details">
           <div class="row">
                <!--    Flash message Blade  -->
                <div class="alert" style="display: none">
                    <span class="message"></span>
                </div>

                @include('front.layout._operation_status')

               <div class="col-xs-12 col-md-6 col-lg-7">
                <div class="left-section">
                <div class="image-listing-details">
                    @if($arr_data['type']=='footage')
                    <video src="{{$admin_footage_public_img_path.$arr_data['admin_enc_item_name']}}" width="100%" height="auto" alt="" controls="" autoplay="" /></video>
                    @else
                    <img src="{{$admin_photos_public_img_path.$arr_data['admin_enc_item_name']}}" alt="{{$arr_data['title']}}" />
                    @endif
                </div>
                <div class="buttons-details type-option">
                    @if(isset($arr_data['listing_details']) && count($arr_data['listing_details'])>0)
                    @foreach($arr_data['listing_details'] as $key => $list)
                    <div class="radio-sections">
                       <div class="radio-btn">
                            <input
                            data-format="{{strtoupper($list['format_details']['name'])}}"
                            data-id="{{base64_encode($list['id'])}}"
                            @if($arr_data['type']=='photo')
                            data-orientation    = "{{ucwords($list['orientation_details']['value'])}}"
                            data-photo-size     = "{{isset($arr_image_size['size']) ? $arr_image_size['size'][$key] : 'NA'}}"
                            data-photo-filesize = "{{isset($arr_image_size['filesize']) ? $arr_image_size['filesize'][$key] : 'NA'}}"
                            @else
                            data-fps = "{{ucwords($list['fps_details']['value'])}}"
                            data-footage-filesize = "{{isset($arr_image_size['filesize']) ? $arr_image_size['filesize'][$key] : 'NA'}}"
                            data-resolution = "{{ucwords($list['resolution_details']['size'])}}"
                            @endif 
                            class="option_price"
                             type="radio"
                              id="f-option_{{$key}}"
                               @if($key==0) checked @endif name="price" value="{{$list['price']}}"
                            />

                        <label for="f-option_{{$key}}">
                          <span class="interior-icon">
                          {{ucwords($list['format_details']['name'])}}
                          </span>
                          <p>${{$list['price']}}</p>
                        </label>
                        <div class="check"></div>
                      </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                   
            </div>
               <div class="product-description">
                <div class="title-descrip">Description</div>
                        <p>{{ucwords($arr_data['description'])}}
                        {{-- <a href="#" class="more-links">Read More...</a> --}}
                        </p>
                   </div>
               </div>
               <div class="col-xs-12 col-md-6 col-lg-5">
                    <div class="right-section">
                        <h1>{{ucwords($arr_data['title'])}}</h1>

                        @if($arr_data['type']=='footage')
                        <div class="small-text">
                            {{-- <span class='footage-size'></span> / --}} <span id='fps'></span> / <span class='footage_format'></span> / <span class='footage_resolution'></span> / <span class='footage_filesize'> / </span>                            
                        </div>
                        @else
                        <div class="small-text">
                        <span class='photo-size'></span> / <span class='orientation'></span> / <span class='photo_format'></span> / <span class='photo_filesize'> / </span>
                        </div>
                        @endif
                        
                        {{-- <div class="title-descrip">Description</div>
                        <p>{{ucwords($arr_data['description'])}}
                        <a href="#" class="more-links">Read More...</a> 
                        </p>
                         <div class="includes-our">Includes our <span>standard license</span></div> <div class="includes-our">Add an <span>extended license</span></div> --}}
                        <div class="border-hr"></div>
                        <div class="profile-sectin">
                         <div class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></div>
                         <div class="creadit-text">Credit : <span> <a href="{{$module_url_path.'/seller/'.base64_encode($arr_data['seller_details']['id'])}}" class="gold-color"> @if($arr_data['seller_details']['id']==1) FilmUnit @else {{isset($arr_data['seller_details']) ? $arr_data['seller_details']['first_name'].' '.$arr_data['seller_details']['last_name'] : 'NA'}} @endif</span></a></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="other-details">
                        @if($arr_data['type']=='footage')
                            <div class="other-left">Footage Duration</div>
                            <div class="other-right">: {{isset($arr_data['duration']) ? $arr_data['duration'].' Seconds' : ''}}</div>
                            <div class="other-left">Footage Ratio</div>
                            <div class="other-right">: {{isset($arr_data['ratio_details']['value']) ? ucwords($arr_data['ratio_details']['value']) : ''}}</div>
                            @if($arr_data['alpha_channel']=='yes')
                            <div class="other-left">Alpha Channel</div>
                            <div class="other-right">: {{isset($arr_data['alpha_channel']) ? ucwords($arr_data['alpha_channel']) : ''}}</div>
                            @endif
                            @if($arr_data['alpha_matte']=='yes')
                            <div class="other-left">Alpha Matte</div>
                            <div class="other-right">: {{isset($arr_data['alpha_matte']) ? ucwords($arr_data['alpha_matte']) : ''}}</div>
                            @endif
                            @if($arr_data['media_release']=='yes')
                            <div class="other-left">Media Release</div>
                            <div class="other-right">: {{isset($arr_data['media_release']) ? ucwords($arr_data['media_release']) : ''}}</div>
                            @endif
                            @if($arr_data['looping']=='yes')
                            <div class="other-left">Looping</div>
                            <div class="other-right">: {{isset($arr_data['looping']) ? ucwords($arr_data['looping']) : ''}}</div>
                            @endif
                            @if($arr_data['model_release']=='yes')
                            <div class="other-left">Model Release</div>
                            <div class="other-right">: {{isset($arr_data['model_release']) ? ucwords($arr_data['model_release']) : ''}}</div>
                            @endif
                            @if($arr_data['liscense_type']=='yes')
                            <div class="other-left">Liscense Type</div>
                            <div class="other-right">: {{isset($arr_data['liscense_type']) ? ucwords($arr_data['liscense_type']) : ''}}</div>
                            @endif
                            @if($arr_data['fx']=='yes')
                            <div class="other-left">FX</div>
                            <div class="other-right">: {{isset($arr_data['fx']) ? ucwords($arr_data['fx']) : ''}}</div>
                            @endif
                        @else
                            <div class="other-left">Orientation</div>
                            <div class="other-right">: <span class="orientation"></span></div>
                            <div class="other-left">Format</div>
                            <div class="other-right">: <span class="photo_format"></span></div>
                            <div class="other-left">Pixels</div>
                            <div class="other-right">: <span class="photo-size"></span></div>
                            <div class="other-left">Size</div>
                            <div class="other-right">: <span class="photo_filesize"></span></div>                                                                                    
                        @endif
                        </div>
                        <div class="detail-right-bottom">
                            <?php $is_booked = 0; ?>
                            @if($role!='seller' && $role!='admin')
                              @if(isset($arr_cart_data) && count($arr_cart_data)>0)
                                @foreach($arr_cart_data as $new_key=>$data)
                                  @if(isset($arr_data['listing_details']) && count($arr_data['listing_details']))
                                    @foreach($arr_data['listing_details'] as $detail_key=>$lists)
                                        @if($lists['id']==$data['list_id'])
                                          <?php $is_booked = 1; ?>
                                        @else
                                          <?php $is_booked = 0; ?>
                                        @endif
                                    @endforeach
                                  @endif                                    
                                @endforeach
                              @endif
                              @if($is_booked==1)
                              <div class="button-section btn-lisitng"><button type="button">Item in the cart</button> </div>
                              @else
                              <div class="button-section btn-lisitng" id="default-cart"><button type="button" class="add_to_cart">Add To Cart</button> </div>
                              @endif
                            <div class="button-section btn-lisitng" id="new-cart-button" style="display: none"><button type="button">Item in the cart</button> </div>
                              @if($user==false)
                              <div class="upload-btns-lisitng"><a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true">
                              </i></a></div>
                              @else
                              <div class="upload-btns-lisitng add_to_favourite"><a href="javascript:void(0)"><i class="fa fa-heart" aria-hidden="true"></i></a></div>
                              @endif
                            @endif
                            <div class="price-dollers">$<span id="price-doller">540.67</span></div>
                            
                           {{--  @if($user==false || $user['role']=='admin')s
                            <div class="upload-btns-lisitng"><a href="{{url('/')}}/login"><i class="fa fa-heart"></i></a></div>
                            @elseif($arr_data['favourite_list']['list_id']==$arr_data['id'])
                            <div class="upload-btns-lisitng"><a href="{{url('/')}}/favourite/remove/{{base64_encode($arr_data['id'])}}" onclick="return confirm_action(this,event,'Do you really want to remove this ?')"><i class="fa fa-heart"></i></a></div>
                            @else
                            <div class="upload-btns-lisitng"><a href="{{url('/')}}/favourite/add/{{base64_encode($arr_data['id'])}}"><i class="fa fa-heart"></i></a></div>
                            @endif --}}
                            {{-- <div class="upload-btns-lisitng"><a href="#"><i class="fa fa-cloud-download"></i></a></div> --}}
                      </div>
                    </div>
               </div>
           </div>

           @if(isset($arr_similar_data) && count($arr_similar_data)>0)
           <div class="similar-footage-details">
              <div class="category-tital tatl-details"> Similar {{str_plural(ucwords($arr_data['type'])) }}</div>
               <div class="row grid">
               @foreach($arr_similar_data as $new_key => $list_details)
               
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 listing-box">
                    <div class="common-effect effect-zoe">
                     <a href="{{$module_url_path.'/view/'.$list_details->M_slug}}" >
                      @if($list_details->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list_details->M_admin_enc_item_name}}" alt="{{ucwords($list_details->M_title)}}" />
                        @endif
                        @if($list_details->M_type=='footage')
                        <img src="{{$admin_footage_image_public_path.$list_details->M_admin_enc_footage_image}}" alt="{{ucwords($list_details->M_title)}}" />
                        @endif
                       </a>
                        <div class="content-hover">
                          <div class="view-txt-more">
                            @if($role!='seller' && $role!='admin')
                                <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($list_details->M_id)}}" data-list-id="{{base64_encode($list_details->List_id)}}"><i class="fa fa-shopping-cart" ></i></a>
                                @if($user==false)
                                  <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                @else
                                <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($list_details->List_id)}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                @endif
                            @endif  
                              <a href="{{$module_url_path.'/view/'.$list_details->M_slug}}" ><i class="fa fa-eye" ></i></a>
                          </div>
                      </div>  
                    </div>


                @if($new_key==$i)
                   <?php 
                      $i = $i + 4;
                      $class = 'last';
                   ?>
                @else
                   <?php $class = ''; ?>
                @endif
                @if($new_key==$j)
                   <?php 
                      $j = $j + 4;
                      $jclass = 'last';
                   ?>
                @else
                   <?php $jclass = ''; ?>                   
                @endif  

                       <div class="preview-panel hidden-md hidden-sm hidden-xs {{$class}} {{$jclass}}"> @if($list_details->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list_details->M_admin_enc_item_name}}" alt="{{ucwords($list_details->M_title)}}" />
                        @endif
                        @if($list_details->M_type=='footage')
                        <video src="{{$admin_footage_public_img_path.$list_details->M_admin_enc_item_name}}" autoplay="" loop="" width="400px" height="auto"></video>
                        @endif
                        <div class="preview-panel-desc"><div class="preview-panel-left"> <h4 class="gold-color">{{ucwords($list_details->M_title)}}</h4>
                                  <h5>{{ucwords($list_details->M_type)}}</h5></div>
                                  <div  class="preview-panel-right">
                                             <p class="format-price"><span>@if($list_details->maxPrice==$list_details->minPrice) ${{$list_details->maxPrice}} @else${{$list_details->minPrice}} - ${{$list_details->maxPrice}}@endif</span></p>
                                               @if($list_details->M_type=='photo')
                                              <p class="format-name">{{ucwords($list_details->format_name)}}</p>
                                          @else
                                              <p class="format-name">{{ucwords($list_details->format_name)}}{{-- {{ucwords($list->resolution_value)}} --}}</p>
                                          @endif
                                          </div>
                                  </div>
                        </div>                                      

{{--                     <div class="main-block-cate grid listing-box">
                        <span class="effect-oscar">
                        @if($list_details->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list_details->M_admin_enc_item_name}}" alt="{{ucwords($list_details->M_title)}}" />
                        @endif
                        @if($list_details->M_type=='footage')
                        <img src="{{$admin_footage_image_public_path.$list_details->M_admin_enc_footage_image}}" alt="{{ucwords($list_details->M_title)}}" />
                        @endif
                          <span class="over-txt-block resize">   
                              <span class="view-txt-more">
                                @if($role!='seller' && $role!='admin')
                                  <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($list_details->M_id)}}" data-list-id="{{base64_encode($list_details->List_id)}}"><i class="fa fa-shopping-cart" ></i></a>
                                  <!--<a href="javascript:void(0)"><i class="fa fa-plus" ></i></a>-->
                                  @if($user==false)
                                    <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @else
                                  <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($list_details->List_id)}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @endif
                                @endif  
                                  <a href="{{$module_url_path.'/view/'.$list_details->M_slug}}" ><i class="fa fa-eye" ></i></a>

                                  <h4> <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">{{ucwords($list_details->M_type)}}</a></h4>
                                  <h5>{{ucwords($list_details->M_title)}}</h5>
                              </span>
                              <span class="footer-hover">
                                  <span class="flex-hover-cont">
                                      <span class="row">
                                          @if($list_details->M_type=='photo')
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{{ucwords($list_details->format_name)}}</p>
                                          @else
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{{ucwords($list_details->format_name)}}</p>
                                          @endif
                                          <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><span>@if($list_details->maxPrice==$list_details->minPrice) ${{$list_details->maxPrice}} @else ${{$list_details->minPrice}} - ${{$list_details->maxPrice}}@endif</span></p>
                                      </span>
                                  </span>
                              </span>
                          </span>
                        </span>
                    </div> --}}
               </div>
               
               @endforeach
             </div>
             @if(isset($arr_similar_data) && count($arr_similar_data)>=4)
                 <div class="button-section btn-lisitng view-mores"><a href="{{$module_url_path}}?type={{$arr_data['type']}}&keyword=">View More</a> </div>
             @endif
           </div>
           @endif

           @if(isset($arr_similar_photographer) && count($arr_similar_photographer)>0)
           <div class="similar-footage-details space-mrgs">
              <div class="category-tital tatl-details "> Photos of Similar Seller</div>
               <div class="row grid">
               @foreach($arr_similar_photographer as $seller_key => $similar_seller_list)
               <a href="{{$module_url_path.'/view/'.$similar_seller_list->M_slug}}" >
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 listing-box">
                                      <div class="common-effect effect-zoe">
                      @if($similar_seller_list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$similar_seller_list->M_admin_enc_item_name}}" alt="{{ucwords($similar_seller_list->M_title)}}" />
                        @endif
                        @if($similar_seller_list->M_type=='footage')
                        <img src="{{$admin_footage_image_public_path.$similar_seller_list->M_admin_enc_footage_image}}" alt="{{ucwords($similar_seller_list->M_title)}}" />
                      @endif
                        <div class="content-hover">
                          <div class="view-txt-more">
                            @if($role!='seller' && $role!='admin')
                                <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($similar_seller_list->M_id)}}" data-list-id="{{base64_encode($similar_seller_list->List_id)}}"><i class="fa fa-shopping-cart" ></i></a>
                                @if($user==false)
                                  <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                @else
                                <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($similar_seller_list->List_id)}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                @endif
                            @endif  
                              <a href="{{$module_url_path.'/view/'.$similar_seller_list->M_slug}}" ><i class="fa fa-eye" ></i></a>
                          </div>
                      </div>  
                    </div>


                @if($seller_key==$k)
                   <?php 
                      $k = $k + 4;
                      $kclass = 'last';
                   ?>
                @else
                   <?php $class = ''; ?>
                @endif
                @if($seller_key==$l)
                   <?php 
                      $l = $l + 4;
                      $jclass = 'last';
                   ?>
                @else
                   <?php $lclass = ''; ?>                   
                @endif  

                       <div class="preview-panel hidden-md hidden-sm hidden-xs {{$kclass}} {{$lclass}}"> @if($similar_seller_list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$similar_seller_list->M_admin_enc_item_name}}" alt="{{ucwords($similar_seller_list->M_title)}}" />
                        @endif
                        @if($similar_seller_list->M_type=='footage')
                        <video src="{{$admin_footage_public_img_path.$similar_seller_list->M_admin_enc_item_name}}" autoplay="" loop="" width="400px" height="auto"></video>
                        @endif
                        <div class="preview-panel-desc"><div class="preview-panel-left"> <h4 class="gold-color">{{ucwords($similar_seller_list->M_title)}}</h4>
                                  <h5>{{ucwords($similar_seller_list->M_type)}}</h5></div>
                                  <div  class="preview-panel-right">
                                             <p class="format-price"><span>@if($similar_seller_list->maxPrice==$similar_seller_list->minPrice) ${{$similar_seller_list->maxPrice}} @else${{$similar_seller_list->minPrice}} - ${{$similar_seller_list->maxPrice}}@endif</span></p>
                                               @if($similar_seller_list->M_type=='photo')
                                              <p class="format-name">{{ucwords($similar_seller_list->format_name)}}</p>
                                          @else
                                              <p class="format-name">{{ucwords($similar_seller_list->format_name)}}{{-- {{ucwords($list->resolution_value)}} --}}</p>
                                          @endif
                                          </div>
                                  </div>
                        </div>   

{{--                     <div class="main-block-cate grid listing-box">
                        <span class="effect-oscar">
                        @if($similar_seller_list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$similar_seller_list->M_admin_enc_item_name}}" alt="{{ucwords($similar_seller_list->M_title)}}" />
                        @endif
                        @if($similar_seller_list->M_type=='footage')
                        <img src="{{$admin_footage_image_public_path.$similar_seller_list->M_admin_enc_footage_image}}" alt="{{ucwords($similar_seller_list->M_title)}}" />
                        @endif
                          <span class="over-txt-block resize">   
                              <span class="view-txt-more">
                              @if($role!='seller' && $role!='admin')
                                  <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($similar_seller_list->M_id)}}" data-list-id="{{base64_encode($similar_seller_list->List_id)}}">
                                  <i class="fa fa-shopping-cart" ></i>
                                  </a>
                                  @if($user==false)
                                    <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @else
                                  <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($similar_seller_list->List_id)}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @endif
                              @endif                                      
                                  <a href="{{$module_url_path.'/view/'.$similar_seller_list->M_slug}}" ><i class="fa fa-eye" ></i></a>

                                  <h4> <a href="javascript:void(0)">{{ucwords($similar_seller_list->M_type)}}</a></h4>
                                  <h5>{{ucwords($similar_seller_list->M_title)}}</h5>
                              </span>
                              <span class="footer-hover">
                                  <span class="flex-hover-cont">
                                      <span class="row">
                                          @if($similar_seller_list->M_type=='photo')
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{{ucwords($similar_seller_list->format_name)}}</p>
                                          @else
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{{ucwords($similar_seller_list->format_name)}}</p>
                                          @endif
                                          <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><span>@if($similar_seller_list->maxPrice==$similar_seller_list->minPrice) ${{$similar_seller_list->maxPrice}} @else ${{$similar_seller_list->minPrice}} - ${{$similar_seller_list->maxPrice}}@endif</span></p>
                                      </span>
                                  </span>
                              </span>
                          </span>
                        </span>
                    </div> --}}
               </div>
               </a>
               @endforeach
             </div>
             @if(isset($similar_seller_list) && count($similar_seller_list)>=4)
                 <div class="button-section btn-lisitng view-mores"><a href="{{$module_url_path}}?type={{$arr_data['type']}}&keyword=">View More</a> </div>
             @endif
           </div>
           @endif
{{--             <div class="similar-footage-details">
              <div class="category-tital tatl-details"> Keywords</div>
              <div class="keywords-details">
                  <ul>
                      <li><a href="#"> photographer</a></li>
                      <li><a href="#"> photo</a></li>
                      <li><a href="#"> sunset</a></li>
                      <li><a href="#"> beach</a></li>
                      <li><a href="#"> nature</a></li>
                      <li><a href="#"> wildlife</a></li>
                      <li><a href="#"> silhouette</a></li>
                      <li><a href="#"> outdoors</a></li>
                      <li><a href="#"> tripod</a></li>
                      <li><a href="#"> camera</a></li>
                      <li><a href="#"> workshop</a></li>
                      <li><a href="#"> photographer</a></li>
                      <li><a href="#"> photo</a></li>
                      <li><a href="#"> sunset</a></li>
                      <li><a href="#"> beach</a></li>
                      <li><a href="#"> nature</a></li>
                      <li><a href="#"> wildlife</a></li>
                  </ul>
              </div>
            </div> --}}

        </div>
      </div>
   <!--     Listing Detail End Here-->
   @endif

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
       <input type="hidden"  id="enc_master_id_modal" name="enc_master_id">
       <input type="hidden"  id="enc_list_id_modal" name="enc_list_id">
       
          <div class="listing-detail reproduced-section">
            <div class="close-pop-icon"><a href="#" data-dismiss="modal"><img src="{{url('/')}}/images/close-seller.png" alt="" /></a></div>
            <div class="modal-body-details">
               <div class="row">
                   <div class="col-xs-12">
                          <div class="left-section">
                    <div class="image-listing-details_new"><img src="{{url('/')}}/images/details-upload-img.jpg" alt="" /></div>
                    <div class="buttons-details type-option">
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

<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/admin/sweetalert_msg.js"></script>

<script type="text/javascript">
//hide Toast
$(document).on('click','.toast',function(){

  $(this).css("display","none");
  
});

//Default Selection
var type  = '{{$arr_data['type']}}';

$('#price-doller').html($('.option_price').val());
$('.photo_format').html($('.option_price').attr('data-format'));
$('.footage_format').html($('.option_price').attr('data-format'));
$('#enc_list_id').val($('.option_price').attr('data-id'));

if(type=='photo')
{
    $('.photo-size').html($('.option_price').attr('data-photo-size'));
    $('.orientation').html($('.option_price').attr('data-orientation'));
    $('.photo_filesize').html($('.option_price').attr('data-photo-filesize'));
}
else
{
    $('#fps').html("@" + $('.option_price').attr('data-fps') + "fps");
    $('.footage_filesize').html($('.option_price').attr('data-footage-filesize'));
    $('.footage_resolution').html($('.option_price').attr('data-resolution'));
}        

//on Selection
$(document).on('click','.option_price',function()
{
    var price = $(this).val();
    $('#price-doller').html(price);
    $('.photo_format').html($(this).attr('data-format'));
    $('.footage_format').html($(this).attr('data-format'));
    $('#enc_list_id').val($(this).attr('data-id'));

    if(type=='photo')
    {
        $('.orientation').html($(this).attr('data-orientation'));
        $('.photo_filesize').html($(this).attr('data-photo-filesize'));
        $('.photo-size').html($(this).attr('data-photo-size'));
    }
    else
    {
        $('#fps').html("@" + $(this).attr('data-fps') + "fps");
        $('.footage_filesize').html($(this).attr('data-footage-filesize'));
        $('.footage_resolution').html($(this).attr('data-resolution'));
    }
});

function add_to_cart(enc_master_id,enc_list_id)
{
  var Site_URL      = '{{url('/')}}/cart/add';
  var token         = "{{csrf_token()}}";  
    
    $.ajax(
    {
        'url':Site_URL,                    
        'type':'post',
        'data':{'enc_master_id':enc_master_id, 'enc_list_id':enc_list_id, '_token':token},
        success:function(res)   
        {
            $('#myModal').modal('toggle');
            if(res.status=='success')
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">Item has been added to cart </p></div>');
            }
            else 
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">This item is already present in the cart </p></div>');
            }

            $('.set-cart-count').html(res.cart_count);
        }

    });    
}

//Add to cart for this page
$(document).on('click','.add_to_cart',function()
{
    var Site_URL      = '{{url('/')}}/cart/add';
    var token         = "{{csrf_token()}}";
    var enc_master_id = $('#enc_master_id').val();
    var enc_list_id   = $('#enc_list_id').val();

    $.ajax(
    {
        'url':Site_URL,                    
        'type':'post',
        'data':{'enc_master_id':enc_master_id, 'enc_list_id':enc_list_id, '_token':token},
        success:function(res)   
        {
            if(res.status=='success')
            {
              if(res.is_booked==1)
              {
                  $('#default-cart').hide();
                  $('#new-cart-button').show();
              }
                $('.set-cart-count').html(res.cart_count);

                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">Item has been added to cart </p></div>');                
            }
            else 
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">This item is already present in the cart </p></div>');
                              
                $('.set-cart-count').html(res.cart_count);
            }
        }

    }); 
});

//Add to Favourite service
function add_to_favourite(enc_list_id)
{
  var Site_URL      = '{{url('/')}}/my_collection/add';
  var token         = "{{csrf_token()}}";  
    
    $.ajax(
    {
        'url':Site_URL,                    
        'type':'post',
        'data':{'enc_list_id':enc_list_id, '_token':token},
        success:function(res)   
        {
            if(res.status=='success')
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">Item has been added to my collection </p></div>');
              
                $('.set-favourite-count').html(res.favourite_count);
            }
            else 
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">This item is already present in my collection </p></div>');
            }
        }

    });    
}

//Add to favourite for this page
$(document).on('click','.add_to_favourite',function()
{
    var Site_URL      = '{{url('/')}}/my_collection/add';
    var token         = "{{csrf_token()}}";
    var enc_list_id   = $('#enc_list_id').val();

    $.ajax(
    {
        'url':Site_URL,                    
        'type':'post',
        'data':{'enc_list_id':enc_list_id, '_token':token},
        success:function(res)   
        {
            if(res.status=='success')
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">Item has been added to my collection </p></div>');
              
                $('.set-favourite-count').html(res.favourite_count);
            }
            else 
            {
                $('#add_toast').append('<div class="toast"><a href="javascript:void(0)" onclick="closeToast(this)" class="close-icon">'+
                '<i class="fa fa-times" aria-hidden="true"></i></a>'+
                '<p class="message">This item is already present in my collection </p></div>');
            }
        }

    });
});


$(document).on('click','#open_cart_modal',function(){
      
  var enc_master_id     = $(this).attr('data-master-id');
  var admin_photo_url   = '{{$admin_photos_public_img_path}}';
  var admin_footage_url = '{{$admin_footage_image_public_path}}';  
  var token             = $('#token').val();

  $('#enc_master_id_modal').val($(this).attr('data-master-id'));

  var Site_URL = '{{url('/')}}/cart/get_sublisting';

  $.ajax({
              'url':Site_URL,                    
              'type':'post',
              'data':{'enc_master_id' : enc_master_id, '_token' : token},
              success:function(res)   
              {
                  $('#formats').html('');
                  
                  if(res.arr_master_details!='')
                  {
                      $('.media_title').html(res.arr_master_details.title.charAt(0).toUpperCase() + res.arr_master_details.title.substr(1));
                      $.each( res.arr_master_details.listing_details, function( key, value ) {
                        if(key==0){ var checked = "checked"; } else {var checked = ""; }
                          $('#formats').append(
                              '<div class="radio-sections"><div class="radio-btn"><input type="radio" data-list-id="'+btoa(value.id)+'" data-master-id="'+enc_master_id+'" class="option_price_new" id="s-option_'+key+'" '+checked+' name="price_new" value="'+value.price+'"/>'+
                              '<label for="s-option_'+key+'">'+
                              '<div class="interior-icon">'+value.format_details.name+'</div>'+
                              '<p>$'+value.price+'</p></label><div class="check"></div></div></div>'
                            );    
                      });
                  }
                  if(res.arr_master_details.type=='photo')
                  {
                    $('.image-listing-details_new').html('<img src="'+admin_photo_url+res.arr_master_details.admin_enc_item_name+'">');
                  }
                  else
                  {
                    $('.image-listing-details_new').html('<img src="'+admin_footage_url+res.arr_master_details.admin_enc_footage_image+'">');
                  }

                  $('.price_dollers').html('$'+ $('input[name="price_new"]:checked').val());
                  $('#enc_list_id_modal').val($('input[name="price_new"]:checked').attr('data-list-id'));                                     
              }
          });
})

//on Selection
$(document).on('click','.option_price_new',function()
{
    $('.price_dollers').html('$'+ $(this).val());
    $('#enc_list_id_modal').val($(this).attr('data-list-id'));
    $('#enc_master_id_modal').val($(this).attr('data-master-id'));
});


$(document).on('click','#add_to_cart',function()
{
  var enc_master_id = $('#enc_master_id_modal').val();
  var enc_list_id   = $('#enc_list_id_modal').val();
 
  add_to_cart(enc_master_id,enc_list_id);

});

</script>

@endsection

