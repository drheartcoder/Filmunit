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
$user = Sentinel::check();
if($user!=false)
{
  $role = $user['role']; 
}
 ?>
    <!--    Listing Page Here-->
    <div class="listing-page margin-tp-btm co-page @if(isset($arr_data) && count($arr_data['data'])<=0) min-heightsone @endif">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="subtitles space-botms"> Packages </div>
                    <!--    Flash message Blade  -->
                    <div class="alert" style="display: none">
                        <span class="message"></span>
                    </div>
                </div>
            </div>
            <div class="row grid">
                @if(isset($arr_data) && count($arr_data['data'])>0) @foreach($arr_data['data'] as $key => $list)
                <a href="{{url('/')}}/package/{{$list['slug']}}">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-left-15px listing-box">
                        <div class="common-effect effect-zoe package-listing-main">
                           
                        @if(isset($list['package_details']))
                        <img src="{{$package_images_public_path.$list['enc_image_name']}}" alt="" />
                        @endif

                          <span class="content-hover">
                              <span class="view-txt-more">
                                  <h5 class="big-name">{{ucwords($list['title'])}}</h5>
                              </span>
                            </span>
                           
                        </div>
                    </div>
                </a>
                @endforeach @else
                <h3 style="color: #FFF" align="center">No Result Found.</h3>
                @endif
            </div>

            <div class="row">
                <!-- Paination Links -->
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

    <!--/*price range slider*/-->
    @endsection