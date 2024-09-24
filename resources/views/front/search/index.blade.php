@extends('front.layout.master')    
@section('main_content')


 <script src="{{url('/')}}/js/front/jquery.min.js" type="text/javascript"></script>
       <script src="{{url('/')}}/js/front/left-menu-jquery.js" type="text/javascript"></script>
       <script src="{{url('/')}}/js/front/jquery-ui.js" type="text/javascript"></script>

<script src="{{url('/')}}/js/front/stickyfloat.js"></script>
 <!--range css start here-->
    <link rel="stylesheet" href="{{url('/')}}/css/admin/range-slider.css" />
    <link rel="stylesheet" href="{{url('/')}}/css/admin/jquery-ui.css" />
     
      <!--    Listing Page Here-->
      <div class="listing-page margin-tp-btm overflow-visible co-page">
            <div class="container-fluid">

             <div class="row">
<?php
$role = '';
$user = Sentinel::check();
if($user!=false)
{
  $role = $user['role']; 
}

$type       = $keyword = '';
//Photo varaibles
$arr_format = []; 
$arr_orient = [];

$type            = Request::get('type');
$keyword         = Request::get('keyword');
$arr_format      = Request::get('photo_format');
$arr_orient      = Request::get('orientation');
$photo_min_price = (Request::get('photo_min_price')!='') ? Request::get('photo_min_price') : 1;
$photo_max_price = (Request::get('photo_max_price')!='') ? Request::get('photo_max_price') : 500;

//Footage varaibles
$arr_footage_format = [];
$ratio              = [];
$fps                = [];
$alpha_channel      = '';
$alpha_matte        = '';
$media_release      = '';
$looping            = '';
$model_release      = '';
$liscense_type      = '';
$fx                 = '';

$arr_footage_format = Request::get('footage_format');
$ratio              = Request::get('ratio');
$resolution         = Request::get('resolution');
$fps                = Request::get('fps');
$duration           = Request::get('duration');
$footage_min_price  = (Request::get('footage_min_price')!='') ? Request::get('footage_min_price') : 1;
$footage_max_price  = (Request::get('footage_max_price')!='') ? Request::get('footage_max_price') : 500;
$min_duration       = (Request::get('min_duration')!='') ? Request::get('min_duration') : 1;
$max_duration       = (Request::get('max_duration')!='') ? Request::get('max_duration') : 120;

$alpha_channel      = Request::get('alpha_channel');
$alpha_matte        = Request::get('alpha_matte');
$media_release      = Request::get('media_release');
$looping            = Request::get('looping');
$model_release      = Request::get('model_release');
$liscense_type      = Request::get('liscense_type');
$fx                 = Request::get('fx');

if($type!='photo' && $type!='footage')
{
  $type     ='footage';
  $keywords ='';
}
$i = 2;
$j = 3;
$class = '';
$jclass = '';

 ?>             
 @if($type=='photo')
  <form id="photo_form" name="photo_form" method="get">
  <!-- Searching Starts here Fields -->
  <!-- Searching Fields ends here -->
  <input type="hidden" name="type" value="{{$type}}">
  <input type="hidden" id="keywords" name="keyword" value="{{$keyword}}">
  {{-- <input type="hidden" id="p_min_price" name="photo_min_price" value="{{$photo_min_price}}">
  <input type="hidden" id="p_max_price" name="photo_max_price" value="{{$photo_max_price}}"> --}}

      <div class="col-md-2 col-lg-2 span3 menu">
           
           <div id='cssmenu' class="" >
             <ul>
                <li class="white-text-reset">
                    <a href="{{url('/')}}/listing?type={{$type}}&keyword="> <i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                </li>
                <li class='has-sub @if(isset($arr_format) && count($arr_format)>0) active @endif'  >
                   <a href='javascript:void(0)'><span class="side-head">Format</span></a>
                   <ul style="@if(isset($arr_photo_formats) && count($arr_photo_formats)>0) display:block; @endif'">
                      @if(isset($arr_photo_formats) && count($arr_photo_formats)>0)
                      @foreach($arr_photo_formats as $key => $value)
                      <li>
                         <a href="javascript:void(0)">
                         <span class="checkbox-sign ">
                         <input type="checkbox" id="checkbox1_{{$key}}" class="css-checkbox option_photo_format" name="photo_format[]" value="{{$value['name']}}" @if(isset($arr_format) && count($arr_format)>0) @if(in_array($value['name'], $arr_format)) checked @endif @endif/>
                         <label for="checkbox1_{{$key}}"  class="css-label submenu lite-red-check remember_me "> {{ucwords($value['name'])}}</label>
                         </span>
                         </a>
                      </li>
                      @endforeach
                      @endif
                   </ul>
                </li>
                
                <li class='has-sub last @if(isset($arr_orient) && count($arr_orient)>0) active @endif' >
                   <a href='javascript:void(0)'><span class="side-head">Orientation</span></a>
                   <ul style="@if(isset($arr_orient) && count($arr_orient)>0) display:block; @endif'">
                      @if(isset($arr_orientation) && count($arr_orientation)>0)
                      @foreach($arr_orientation as $key => $value)
                      <li>
                         <a href="javascript:void(0)">
                         <span class="checkbox-sign ">
                         <input type="checkbox" id="checkbox11_{{$key}}" class="css-checkbox option_orientation" name="orientation[]" value="{{$value['value']}}"@if(isset($arr_orient) && count($arr_orient)>0) @if(in_array($value['value'], $arr_orient)) checked @endif @endif/>
                         <label for="checkbox11_{{$key}}"  class="css-label submenu lite-red-check remember_me "> {{ucwords($value['value'])}}</label>
                         </span></a>
                      </li>
                      @endforeach
                      @endif
                   </ul>
                </li>
                <li class='has-sub last @if(isset($photo_max_price) && isset($photo_min_price)) active @endif' >
                   <a href='javascript:void(0)'><span class="side-head">Price</span></a>
                   <ul style="@if(isset($photo_max_price) && isset($photo_min_price)) display:block; @endif'">
                      <li>
                         <div class="range-t input-bx" for="amount">
                            <div id="slider-price-range" class="slider-rang"></div>
                            <div class="amount-no" id="slider_price_range_txt">
                            </div>
                            <input type="hidden" name="photo_max_price" id="photo_max_price" value="{{$photo_max_price}}">
                            <input type="hidden" name="photo_min_price" id="photo_min_price" value="{{$photo_min_price}}">
                         </div>
                          <div class="clearfix"></div>
                      </li>
                   </ul>
                </li>
             </ul>
          </div>
      </div>
  </form>              
 @endif
 @if($type=='footage')
  <form id="footage_form" name="footage_form" method="get">

    <input type="hidden" name="type" value="{{$type}}">
  <input type="hidden" id="keywords" name="keyword" value="{{$keyword}}">

              <div class="col-md-2 col-lg-2 span3 menu">
                   <div id='cssmenu' class="" >
                     <ul>
                         <li class="white-text-reset">
                    <a href="{{url('/')}}/listing?type={{$type}}&keyword="> <i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                </li>
                        <li class='has-sub @if(isset($arr_footage_format) && count($arr_footage_format)>0) active @endif'>
                           <a href="javascript:void(0)"><span class="side-head">Format</span></a>
                           <ul style="display:block;">
                              @if(isset($arr_footage_formats) && count($arr_footage_formats)>0)
                              @foreach($arr_footage_formats as $key => $value)
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox2_{{$key}}" class="css-checkbox option_footage_format" name="footage_format[]" value="{{$value['name']}}" @if(isset($arr_footage_format) && count($arr_footage_format)>0) @if(in_array($value['name'], $arr_footage_format)) checked @endif @endif/>
                                 <label for="checkbox2_{{$key}}"  class="css-label submenu lite-red-check remember_me "> {{ucwords($value['name'])}}</label>
                                 </span></a>
                              </li>
                              @endforeach
                              @endif
                           </ul>
                        </li>
                        
                        <li class='has-sub last @if(isset($ratio) && count($ratio)>0) active @endif' >
                           <a href="javascript:void(0)"><span class="side-head">Ratio</span></a>
                           <ul style="@if(isset($ratio) && count($ratio)>0) display:block; @endif'">
                              @if(isset($arr_ratio) && count($arr_ratio)>0)
                              @foreach($arr_ratio as $key => $value)
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox3_{{$key}}" class="css-checkbox option_ratio" name="ratio[]" value="{{$value['value']}}" @if(isset($ratio) && count($ratio)>0) @if(in_array($value['value'], $ratio)) checked @endif @endif/>
                                 <label for="checkbox3_{{$key}}"  class="css-label submenu lite-red-check remember_me "> {{ucwords($value['value'])}}</label>
                                 </span></a>
                              </li>
                              @endforeach
                              @endif                              
                           </ul>
                        </li>

                        <li class='has-sub last @if(isset($resolution) && count($resolution)>0) active @endif'>
                           <a href="javascript:void(0)"><span class="side-head">Resolution</span></a>
                           <ul style="@if(isset($resolution) && count($resolution)>0) display:block; @endif'">
                              @if(isset($arr_resolution) && count($arr_resolution)>0)
                              @foreach($arr_resolution as $key => $value)
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox4_{{$key}}" class="css-checkbox option_resolution" name="resolution[]" value="{{$value['size']}}" @if(isset($resolution) && count($resolution)>0) @if(in_array($value['size'], $resolution)) checked @endif @endif/>
                                 <label for="checkbox4_{{$key}}"  class="css-label submenu lite-red-check remember_me "> {{ucwords($value['size'])}}</label>
                                 </span></a>
                              </li>
                              @endforeach
                              @endif                              
                           </ul>
                        </li>

                        <li class='has-sub last @if(isset($fps) && count($fps)>0) active @endif'>
                           <a href="javascript:void(0)"><span class="side-head">FPS</span></a>
                           <ul style="@if(isset($fps) && count($fps)>0) display:block; @endif'">
                              @if(isset($arr_fps) && count($arr_fps)>0)
                              @foreach($arr_fps as $key => $value)
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox6_{{$key}}" class="css-checkbox option_fps" name="fps[]" value="{{$value['value']}}" @if(isset($fps) && count($fps)>0) @if(in_array($value['value'], $fps)) checked @endif @endif/>
                                 <label for="checkbox6_{{$key}}"  class="css-label submenu lite-red-check remember_me "> {{ucwords($value['value'])}}</label>
                                 </span></a>
                              </li>
                              @endforeach
                              @endif                              
                           </ul>
                        </li>

{{--                         <li class='has-sub last @if(isset($duration) && $duration!='') active @endif'>
                           <a href="javascript:void(0)"><span class="side-head">duration</span></a>
                           <ul style="@if(isset($duration) && $duration!='') display:block; @endif'">
                                <li> 
                                 <input type="text" class="css-checkbox option_duration" name="duration" value="{{$duration}}"/>
                              </li>
                           </ul>
                        </li> --}}
                      
                      <li class='has-sub last @if(isset($max_duration) && isset($min_duration)) active @endif' >
                         <a href='javascript:void(0)'><span class="side-head">Duration</span></a>
                         <ul style="@if(isset($max_duration) && isset($min_duration)) display:block; @endif'">
                            <li>
                               <div class="range-t input-bx" for="amount">
                                  <div id="slider-price-range-duration" class="slider-rang"></div>
                                  <div class="amount-no" id="slider_price_range_txt_duration">
                                  </div>
                                  <input type="hidden" name="max_duration" id="max_duration" value="{{$max_duration}}">
                                  <input type="hidden" name="min_duration" id="min_duration" value="{{$min_duration}}">
                               </div>
                                <div class="clearfix"></div>
                            </li>
                         </ul>
                      </li>                                                

                        <li class='has-sub last @if(isset($alpha_channel) && count($alpha_channel)>0 || isset($alpha_matte) && count($alpha_matte)>0 ||isset($media_release) && count($media_release)>0 ||isset($looping) && count($looping)>0 ||isset($model_release) && count($model_release)>0 ||isset($liscense_type) && count($liscense_type)>0 ||isset($fx) && count($fx)>0) active @endif'>
                           <a href="javascript:void(0)"><span class="side-head">Others</span></a>
                           <ul style="@if(isset($alpha_channel) && count($alpha_channel)>0 || isset($alpha_matte) && count($alpha_matte)>0 ||isset($media_release) && count($media_release)>0 ||isset($looping) && count($looping)>0 ||isset($model_release) && count($model_release)>0 ||isset($liscense_type) && count($liscense_type)>0 ||isset($fx) && count($fx)>0) display:block; @endif'">
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox21" class="css-checkbox alpha_channel" name="alpha_channel" value="yes" @if(isset($alpha_channel) && count($alpha_channel)>0) checked @endif />
                                 <label for="checkbox21"  class="css-label submenu lite-red-check remember_me ">Alpha Channel</label>
                                 </span></a>
                              </li>
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox22" class="css-checkbox alpha_matte" name="alpha_matte" value="yes" @if(isset($alpha_matte) && count($alpha_matte)>0) checked @endif />
                                 <label for="checkbox22"  class="css-label submenu lite-red-check remember_me ">Alpha Matte</label>
                                 </span></a>
                              </li>
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox23" class="css-checkbox media_release" name="media_release" value="yes" @if(isset($media_release) && count($media_release)>0) checked @endif />
                                 <label for="checkbox23"  class="css-label submenu lite-red-check remember_me ">Media Release</label>
                                 </span></a>
                              </li>
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox24" class="css-checkbox looping" name="looping" value="yes" @if(isset($looping) && count($looping)>0) checked @endif />
                                 <label for="checkbox24"  class="css-label submenu lite-red-check remember_me ">Looping</label>
                                 </span></a>
                              </li>
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox25" class="css-checkbox model_release" name="model_release" value="yes" @if(isset($model_release) && count($model_release)>0) checked @endif />
                                 <label for="checkbox25"  class="css-label submenu lite-red-check remember_me ">Model Release</label>
                                 </span></a>
                              </li>
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox26" class="css-checkbox liscense_type" name="liscense_type" value="yes" @if(isset($liscense_type) && count($liscense_type)>0) checked @endif />
                                 <label for="checkbox26"  class="css-label submenu lite-red-check remember_me ">Liscense Type</label>
                                 </span></a>
                              </li>
                              <li>
                                 <a href="javascript:void(0)">
                                 <span class="checkbox-sign ">
                                 <input type="checkbox" id="checkbox27" class="css-checkbox fx" name="fx" value="yes" @if(isset($fx) && count($fx)>0) checked @endif />
                                 <label for="checkbox27"  class="css-label submenu lite-red-check remember_me ">FX</label>
                                 </span></a>
                              </li>
                           </ul>
                        </li>                        

                      <li class='has-sub last @if(isset($footage_max_price) && isset($footage_min_price)) active @endif' >
                         <a href='javascript:void(0)'><span class="side-head">Price</span></a>
                         <ul style="@if(isset($footage_max_price) && isset($footage_min_price)) display:block; @endif'">
                            <li>
                               <div class="range-t input-bx" for="amount">
                                  <div id="slider-price-range-footage" class="slider-rang"></div>
                                  <div class="amount-no" id="slider_price_range_txt_footage">
                                  </div>
                                  <input type="hidden" name="footage_max_price" id="footage_max_price" value="{{$footage_max_price}}">
                                  <input type="hidden" name="footage_min_price" id="footage_min_price" value="{{$footage_min_price}}">
                               </div>
                                <div class="clearfix"></div>
                            </li>
                         </ul>
                      </li>
                     </ul>
                  </div>
              </div>
  </form>            
 @endif
               <!-- Display values -->
               <div class="col-md-10 col-lg-10">
               
               <!--    Flash message Blade  -->
              <div class="alert" style="display: none">
                <span class="message"></span>
              </div>

               <div class="row grid">
               @if(isset($arr_data) && count($arr_data['data'])>0)
               @foreach($arr_data['data'] as $key => $list)
              
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 listing-box">
                   <div class="common-effect effect-zoe">
                    <a href="{{$module_url_path.'/view/'.$list->M_slug}}" >
				        @if($list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list->M_admin_enc_item_name}}" alt="{{ucwords($list->M_title)}}" />
                        @endif
                        @if($list->M_type=='footage')
                        <img src="{{$admin_footage_image_public_path.$list->M_admin_enc_footage_image}}" alt="{{ucwords($list->M_title)}}" />
                        @endif
                         </a>
						<div class="content-hover">
							<div class="view-txt-more">
                                
                              @if($role!='seller' && $role!='admin')  
                                  <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($list->M_id)}}" data-list-id="{{base64_encode($list->List_id)}}">
                                  <i class="fa fa-shopping-cart" title="Add to cart"></i>
                                  </a>
                                  @if($user==false)
                                    <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @else
                                    <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($list->List_id)}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @endif
                                  <!--<a href="javascript:void(0)"><i class="fa fa-eye" ></i></a>-->
                              @endif
                                  <a href="{{$module_url_path.'/view/'.$list->M_slug}}" ><i class="fa fa-eye" title="view"></i></a>
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

                       <div class="preview-panel hidden-md hidden-sm hidden-xs {{$class}} {{$jclass}}"> @if($list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list->M_admin_enc_item_name}}" alt="{{ucwords($list->M_title)}}" />
                        @endif
                        @if($list->M_type=='footage')
                        <video class="video_clear" src="{{$admin_footage_public_img_path.$list->M_admin_enc_item_name}}" autoplay="" loop="" width="400px" height="auto"></video>
                        @endif
                        <div class="preview-panel-desc"><div class="preview-panel-left"> <h4 class="gold-color">{{ucwords($list->M_title)}}</h4>
                                  <h5>{{ucwords($list->M_type)}}</h5></div>
                                  <div  class="preview-panel-right">
                                             <p class="format-price"><span>@if($list->maxPrice==$list->minPrice) ${{$list->maxPrice}} @else${{$list->minPrice}} - ${{$list->maxPrice}}@endif</span></p>
                                               @if($list->M_type=='photo')
                                              <p class="format-name">{{ucwords($list->format_name)}}</p>
                                          @else
                                              <p class="format-name">{{ucwords($list->format_name)}}{{-- {{ucwords($list->resolution_value)}} --}}</p>
                                          @endif
                                          </div>
                                  </div>
                        </div>	
                    <!--<div class="main-block-cate  listing-box">
                        <span class="effect-oscar">
                        @if($list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list->M_admin_enc_item_name}}" alt="{{ucwords($list->M_title)}}" />
                        @endif
                        @if($list->M_type=='footage')
                        <img src="{{$admin_footage_image_public_path.$list->M_admin_enc_footage_image}}" alt="{{ucwords($list->M_title)}}" />
                        @endif
                          <span class="over-txt-block resize">
                              <span class="view-txt-more">
                              @if($role!='seller' && $role!='admin')  
                                  <a href="javascript:void(0)" id="open_cart_modal" data-toggle="modal" data-target="#myModal" data-master-id="{{base64_encode($list->M_id)}}" data-list-id="{{base64_encode($list->List_id)}}">
                                  <i class="fa fa-shopping-cart" title="Add to cart"></i>
                                  </a>
                                  @if($user==false)
                                    <a href="{{url('/')}}/login"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @else
                                    <a href="javascript:void(0)" onclick="add_to_favourite('{{base64_encode($list->List_id)}}')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                  @endif
                                  <a href="javascript:void(0)"><i class="fa fa-eye" ></i></a>
                              @endif
                                  <a href="{{$module_url_path.'/view/'.$list->M_slug}}" ><i class="fa fa-eye" title="view"></i></a>
                                  <h4> <a href="javascript:void(0)">{{ucwords($list->M_type)}}</a></h4>
                                  <h5>{{ucwords($list->M_title)}}</h5>
                              </span>
                              <span class="footer-hover">
                                  <span class="flex-hover-cont">
                                      <span class="row">
                                          @if($list->M_type=='photo')
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{{ucwords($list->format_name)}}</p>
                                          @else
                                              <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{{ucwords($list->format_name)}}{{-- {{ucwords($list->resolution_value)}} --}}</p>
                                          @endif
                                          <p class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><span>@if($list->maxPrice==$list->minPrice) ${{$list->maxPrice}} @else${{$list->minPrice}} - ${{$list->maxPrice}}@endif</span></p>
                                      </span>
                                  </span>
                              </span>
                          </span>
                          <div class="preview-panel @if($key!=0 && ($key == 3*$key+1 || $key == 4*$key+1)) last @endif"> @if($list->M_type=='photo')
                        <img src="{{$admin_photos_public_img_path.$list->M_admin_enc_item_name}}" alt="{{ucwords($list->M_title)}}" />
                        @endif
                        @if($list->M_type=='footage')
                        <video src="{{$admin_footage_public_img_path.$list->M_admin_enc_item_name}}" autoplay="" loop="" width="400px" height="auto"></video>
                        @endif
                        </div>
                        </span>
                    </div>-->
               </div>
              
               @endforeach
               @else
                <div style="color: #FFF" align="center">No Result Found.</div>
               @endif
             </div>
            </div>
             
             
              <!--Display values ends here -->
      </div>
            <!-- Paination Links -->
             <div class="col-md-12"> 
              @include('front.templates.pagination_view')
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
       <input type="hidden"  id="enc_master_id" name="enc_master_id">
       <input type="hidden"  id="enc_list_id" name="enc_list_id">
       
          <div class="listing-detail reproduced-section">
            <div class="close-pop-icon"><a href="#" data-dismiss="modal"><img src="{{url('/')}}/images/close-seller.png" alt="" /></a></div>
            <div class="modal-body-details">
               <div class="row">
                   <div class="col-xs-12">
                          <div class="left-section">
                    <div class="image-listing-details"><img src="{{url('/')}}/images/details-upload-img.jpg" alt="" /></div>
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
<script type="text/javascript" src="{{url('/')}}/js/front/jquery.ui.touch-punch.min.js"></script>

<!--/*price range slider*/-->
 <script>
  
  $(".effect-zoe").mouseout(function() {
    $(".video_clear").get(0).currentTime = 0;
  });

//hide Toast
$(document).on('click','.toast',function(){

  $(this).css("display","none");
  
})

//Add to cart service
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

  $(function () {
    var photo_max_price = '{{$photo_max_price}}';
    var photo_min_price = '{{$photo_min_price}}';

    var footage_max_price = '{{$footage_max_price}}';
    var footage_min_price = '{{$footage_min_price}}';

    var max_duration = '{{$max_duration}}';
    var min_duration = '{{$min_duration}}';    
    //alert(photo_min_price);

      //For Photo Section Slider
      $("#slider-price-range").slider({
          range: true,
          min: 0,
          max: 500,
          values: [photo_min_price, photo_max_price],
          slide:function(event,ui)
          {
            if(ui.values[1]==500) { var plus = "+"; } else { var plus = ""; }
            $("#slider_price_range_txt").html("<span class='slider_price_min pull-left'>$ " + ui.values[0] + " </span>  <span class='slider_price_max pull-right'>$ " + ui.values[1]  + plus + " </span>");
            $("#photo_min_price").val(ui.values[0]);                                      
            $("#photo_max_price").val(ui.values[1]);  
          },
          stop: function(event, ui) {
              $("#photo_form").submit();                    
          }
      });
        
      $("#slider_price_range_txt").html("<span class='slider_price_min pull-left'> $ " + $("#slider-price-range").slider("values", 0) + " </span>  <span class='slider_price_max pull-right'>$ " + $("#slider-price-range").slider("values", 1) + " </span>");

      //For Footage Section Slider
      $("#slider-price-range-footage").slider({
      range: true,
      min: 1,
      max: 500,
      values: [footage_min_price, footage_max_price],
      slide: function (event, ui) {        
          if(ui.values[1]==500) { var plus = "+"; } else { var plus = ""; }
        
          $("#slider_price_range_txt_footage").html("<span class='slider_price_min'>$ " + ui.values[0] + "</span>  <span class='slider_price_max'>$ " + ui.values[1] + plus + " </span>");
          $("#footage_min_price").val(ui.values[0]);                                      
          $("#footage_max_price").val(ui.values[1]);                                      
      },
       stop: function(event, ui) {
              $("#footage_form").submit();                    
          }
      });

      $("#slider_price_range_txt_footage").html("<span class='slider_price_min'> $ " + $("#slider-price-range-footage").slider("values", 0) + "</span>  <span class='slider_price_max'>$ " + $("#slider-price-range-footage").slider("values", 1) + "</span>");

      //For duration Section Slider
      $("#slider-price-range-duration").slider({
      range: true,
      min: 1,
      max: 120,
      values: [min_duration, max_duration],
      slide: function (event, ui) {        
          if(ui.values[1]==120) { var plus = "+"; } else { var plus = ""; }
        
          $("#slider_price_range_txt_duration").html("<span class='slider_price_min'>Sec " + ui.values[0] + "</span>  <span class='slider_price_max'>Sec " + ui.values[1] + plus + " </span>");
          $("#min_duration").val(ui.values[0]);                                      
          $("#max_duration").val(ui.values[1]);                                      
      },
       stop: function(event, ui) {
              $("#footage_form").submit();                    
          }
      });

      $("#slider_price_range_txt_duration").html("<span class='slider_price_min'> Sec " + $("#slider-price-range-duration").slider("values", 0) + "</span>  <span class='slider_price_max'>Sec " + $("#slider-price-range-duration").slider("values", 1) + "</span>");      

  });
</script>
<!--/*price range slider*/-->
<script>
        /*
        * jQuery easing functions (for this demo)
        */
        jQuery.extend( jQuery.easing,{
            def: 'easeOutQuad',
            swing: function (x, t, b, c, d) {
                //alert(jQuery.easing.default);
                return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
            },
            easeInQuad: function (x, t, b, c, d) {
                return c*(t/=d)*t + b;
            },
            easeOutQuad: function (x, t, b, c, d) {
                return -c *(t/=d)*(t-2) + b;
            },
            easeInOutQuad: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return c/2*t*t + b;
                return -c/2 * ((--t)*(t-2) - 1) + b;
            },
            easeOutElastic: function (x, t, b, c, d) {
                var s=1.70158;var p=0;var a=c;
                if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
                if (a < Math.abs(c)) { a=c; var s=p/4; }
                else var s = p/(2*Math.PI) * Math.asin (c/a);
                return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
            },
            easeInOutElastic: function (x, t, b, c, d) {
                var s=1.70158;var p=0;var a=c;
                if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
                if (a < Math.abs(c)) { a=c; var s=p/4; }
                else var s = p/(2*Math.PI) * Math.asin (c/a);
                if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
                return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
            },
            easeInBack: function (x, t, b, c, d, s) {
                if (s == undefined) s = 1.70158;
                return c*(t/=d)*t*((s+1)*t - s) + b;
            },
            easeOutBack: function (x, t, b, c, d, s) {
                if (s == undefined) s = 1.70158;
                return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
            },
            easeInOutBack: function (x, t, b, c, d, s) {
                if (s == undefined) s = 1.70158; 
                if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
                return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
            }
        });
        
        // init the pluging and bind it to the #menu element
        $('.menu').stickyfloat();
        
/*--------------------- Photo Sidebar Values -----------------------*/        
$('.option_photo_format').on('change',function(){
 //$('#keywords').val('');
 $('#photo_form').submit();
});

$('.option_orientation').on('change',function(){
 $('#photo_form').submit();
});        

/*--------------------- Footage Sidebar Values -----------------------*/        
/*$('.option_duration').on('keyup',function(){
 $('#footage_form').submit();
});*/

$('.option_footage_format').on('change',function(){
 $('#footage_form').submit();
});

$('.option_ratio').on('change',function(){
 $('#footage_form').submit();
});

$('.option_resolution').on('change',function(){
 $('#footage_form').submit();
});

$('.option_fps').on('change',function(){
 $('#footage_form').submit();
});

$('.alpha_channel').on('change',function(){
 $('#footage_form').submit();
});

$('.alpha_matte').on('change',function(){
 $('#footage_form').submit();
});

$('.media_release').on('change',function(){
 $('#footage_form').submit();
});

$('.looping').on('change',function(){
 $('#footage_form').submit();
});

$('.model_release').on('change',function(){
 $('#footage_form').submit();
});

$('.liscense_type').on('change',function(){
 $('#footage_form').submit();
});

$('.fx').on('change',function(){
 $('#footage_form').submit();
});


$(document).on('click','#open_cart_modal',function(){
      
  var enc_master_id = $(this).attr('data-master-id');
  var token         = $('#token').val();
  var admin_photo_url = '{{$admin_photos_public_img_path}}';
  var admin_footage_url = '{{$admin_footage_image_public_path}}';


  $('#enc_master_id').val($(this).attr('data-master-id'));

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
                  $('#enc_list_id').val($('input[name="price"]:checked').attr('data-list-id'));                                     
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

$(document).on('click','#add_to_cart',function()
{
  var enc_master_id = $('#enc_master_id').val();
  var enc_list_id   = $('#enc_list_id').val();
 
  add_to_cart(enc_master_id,enc_list_id);

});
    
</script>
<!--Right Bar Sticky Float-->
    
@endsection

