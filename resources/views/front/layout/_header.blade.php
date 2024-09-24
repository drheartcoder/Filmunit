<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta property="og:title" content="FilmUnit" />
    <meta property="og:type" content="FilmUnit" />
    <meta property="og:url" content="http://www.webwingtechnologies.com/" />
    <meta property="og:image" content="{{url('/')}}/images/top-bg.jpg" />
    <meta property="og:description" content="FilmUnit" />
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="description" content="FilmUnit" />
    <meta name="keywords" content="FilmUnit - Home | FilmUnit - Online Photos and Footage" />
    <title>{{$title}}</title>
    <!-- ======================================================================== -->
    <link rel="icon" href="{{url('/')}}/images/favicon-16x16.png" type="image/x-icon" />
    <!-- Bootstrap Core CSS -->
    <link href="{{url('/')}}/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!--font-awesome-css-start-here-->
    <link href="{{url('/')}}/css/admin/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/css/admin/filmunit.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/css/admin/set1.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript"  src="{{url('/')}}/js/front/jquery-1.11.3.min.js"></script>
       <!--Right Bar Sticky Float-->
    <!--      Flexslider JS-->
    <script type="text/javascript" src="{{url('/')}}/js/front/jquery.flexisel.js"></script>

    <script type="text/javascript">
        $(window).load(function() {
            $("#flexiselDemo1").flexisel();
            $("#flexiselDemo2").flexisel({
                enableResponsiveBreakpoints: true,
                responsiveBreakpoints: {
                    portrait: {
                        changePoint: 480,
                        visibleItems: 1
                    },
                    landscape: {
                        changePoint: 640,
                        visibleItems: 2
                    },
                    tablet: {
                        changePoint: 768,
                        visibleItems: 3
                    }
                }
            });
        });
    </script>
    <?php
	//code added by mapweb
    define('WP_USE_THEMES', false);
	require(dirname(dirname(dirname(dirname(__FILE__)))) . '/wptheme/wp-load.php');	
	wp_head();
	?>

<style type="text/css">
			
			.testimonial-theme4-theme4{
				text-align: center;
				background: #fff;
			}
			.testimonial-theme4-theme4 .testimonial-theme4-pic-theme4{
				width: 100px;
				height: 100px;
				border-radius: 50%;
				border: 5px solid rgba(255,255,255,0.3);
				display: inline-block;
				margin-top: 0px;
				overflow: hidden;
				box-shadow:0 2px 6px rgba(0, 0, 0, 0.15);
				margin: 0 auto;
				display:block;
			}
			.testimonial-theme4-theme4 .testimonial-theme4-pic-theme4 img{
				width: 100%;
				height: 100%;
			}
			.testimonial-theme4-theme4 .testimonial-theme4-description-theme4{
				font-size: 16px;
				font-style: italic;
				color: #808080;
				line-height: 30px;
				margin: 10px 0 20px;
			}
			.testimonial-theme4-theme4 .testimonial-theme4-title-theme4{
				font-size: 14px;
				font-weight: bold;
				margin: 0;
				color: #333;
				text-transform: uppercase;
				text-align:center;
			}
			.testimonial-theme4-theme4 .testimonial-theme4-post-theme4{
				display: block;
				font-size: 13px;
				color: #777;
				margin-bottom: 15px;
				text-transform: capitalize;
				text-align:center;
			}
			.testimonial-theme4-theme4 .testimonial-theme4-post-theme4:before{
				content: "";
				width: 30px;
				display: block;
				margin: 10px auto;
				border: 1px solid #d3d3d3;
			}
			.testimonial-theme4-theme4 .super-testimonial-theme4 {
			  display: block;
			  overflow: hidden;
			  text-align: center;
			}
			</style>
			
			<style type="text/css">
				.testimonial-theme4-theme4 .fa-fw {
				  text-align: center;
				  width: 1.28571em;
				  color:#1a1a1a;
				}
			</style>
			
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#testimonial-slider-theme4").owlCarousel({
						items:1,
						autoPlay: 1000,
						itemsDesktop:[1199,1],
						itemsDesktopSmall:[979,1],
						itemsTablet:[768,1],
						pagination: false,
						autoPlay:true,
					});
					$(".super-testimonial-theme4").raty({
						readOnly: true,
						score: function() {
						return $(this).attr("data-score");
						},
						number: function() {
						return $(this).attr("data-number");
						}
					});					
				});
			</script>
</head>

<body>
<div id="main"></div>
 
<?php 

  if(isset($user) && $user!="")
  
  $user_id = "";
  $path    = "";
  $role    = "";
  $profile_img = "";
  $user = Sentinel::check();
  {
    $user_id = $user['id'];
    $path    = url('/')."/get_notification_count";
    $role    = $user['role'];
    $profile_img = isset($user['profile_image'])  ? $user['profile_image'] : "";    
  }
?>
    <div id="header-home">
    <input type="hidden" value="{{ csrf_token() }}" name="token" id="token">
        @if($user==false || $role=='admin')
        <!--<div class="main-banner-block">-->
        <div class="header header-home header-set" style="background-color:black;">
            <div class="logo-block wow fadeInDown" data-wow-delay="0.2s">
                <a href="{{url('/')}}">
                    <img src="{{url('/')}}/images/logo-home.png" alt="" class="main-logo" />
                </a>
            </div>
            
            <form method="get" action="{{url('/')}}/listing">
                <div class="search-box-home hidden-xs hidden-sm">
                    <div class="select-box padd-0-catego">
                        <div class="select-style">
                            <select tabindex="1" name="type" class="frm-select">
                                   <option value="footage" @if(\Request::get('type')=='footage') selected @endif>Footage</option>
                                   <option value="photo" @if(\Request::get('type')=='photo') selected @endif>Photo</option>
                                </select>
                        </div>
                    </div>
                    <div class="input-box padd-0">
                        <input class="trans-input-search" name="keyword" placeholder="Search photos, footages" type="text" value="{{\Request::get('keyword')}}" />
                    </div>
                    <button type="submit" class="button-pos-serch"><img class="search-img-icns" src="{{url('/')}}/images/search-icon-top.png" alt="" /></button>

                    <div class="clr"></div>
                </div>
            </form>
            
            <form method="get" action="{{url('/')}}/listing">
                <div class="hidden-md hidden-lg search-box-header">
                    <div id="flipdashboard"><i class="fa fa-search" aria-hidden="true"></i></div>
                    <div id="paneldashboard">
                        <div class="search-box-home">
                            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-4 padd-0-catego">
                                <div class="select-style">
                                    <select tabindex="1" name="type" class="frm-select">
                                       <option value="footage" @if(\Request::get('type')=='footage') selected @endif>Footage</option>
                                       <option value="photo" @if(\Request::get('type')=='photo') selected @endif>Photo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8 padd-0">
                                <input class="trans-input-search" name="keyword" placeholder="Search photos, footages" type="text" value="{{\Request::get('keyword')}}"/>
                                <!--<div class="error-msg">Please enter valid Fields.</div>-->
                            </div>
                            <button type="submit" class="button-pos-serch"><img class="search-img-icns" src="{{url('/')}}/images/search-icon-top.png" alt="" /></button>
                            <div class="clr"></div>
                        </div>
                    </div>
                </div>
            </form>
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <!--Menu Start-->
            <div id="mySidenav" class="sidenav">
                <div class="signup-sectino">
                    <ul>
                        <li class="display-bck blcoks"><a href="{{url('/')}}/login">Sign In</a></li>
                        <li>
                            <a href="{{url('/')}}/cart"> <span><img src="{{url('/')}}/images/cart-icons.png" class="img-cart" alt="" /></span> <span class="set-cart-count">0</span></a>
                        </li>
                    </ul>
                </div>
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="banner-img-block">
                    <img src="{{url('/')}}/images/photoshoot-logo-responsive.png" alt="Logo" />
                    <div class="img-responsive-logo"></div>
                </div>
                <ul class="min-menu">
                    <li class="first-cls"><a href="{{url('/')}}/listing?type=footage&keyword=" class="">Footage</a></li>
                    <li><a href="{{url('/')}}/listing?type=photo&keyword=" class="drop-block">Photo</a></li>
                    {{-- <li><a href="{{url('/')}}/about" class="drop-block">About Us</a></li> --}}
                    <!--            <li><a href="contact.html" class="drop-block">Contact Us</a></li>-->
                    <li class="btn-become btn-homebecome display-bck "><a href="{{url('/')}}/signup" class="drop-block">Join Now</a></li>
                    <li class="display-bck-c "><a href="{{url('/')}}/signup" class="drop-block">Join Now</a></li>

                    <li class="display-bck-c"><a href="{{url('/')}}/login">Sign In</a></li>

                </ul>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="bank-div"></div>
        <div class="clr"></div>
        <!--</div>-->
        @endif


        @if($user==true && $role!='admin')
        <!--After Header-->
        <div class="header header-home header-set">
            <div class="logo-block wow fadeInDown" data-wow-delay="0.2s">
                <a href="{{url('/')}}">
                    <img src="{{url('/')}}/images/logo-home.png" alt="" class="main-logo after-heads" />
                </a>
            </div>
            <form method="get" action="{{url('/')}}/listing">
                <div class="search-box-home after-login hidden-xs hidden-sm">
                    <div class="select-box padd-0-catego">
                        <div class="select-style">
                            <select tabindex="1" name="type" class="frm-select">
                                   <option value="footage" @if(\Request::get('type')=='footage') selected @endif>Footage</option>
                                   <option value="photo" @if(\Request::get('type')=='photo') selected @endif>Photo</option>
                                </select>
                        </div>
                    </div>
                    <div class="input-box padd-0">
                        <input class="trans-input-search" name="keyword" placeholder="Search photos, footages" type="text" value="{{\Request::get('keyword')}}" />
                    </div>
                    <button type="submit" class="button-pos-serch"><img class="search-img-icns" src="{{url('/')}}/images/search-icon-top.png" alt="" /></button>

                    <div class="clr"></div>
                </div>
            </form>
            
            <form method="get" action="{{url('/')}}/listing">
                <div class="hidden-md hidden-lg search-box-header">
                    <div id="flipdashboard"><i class="fa fa-search" aria-hidden="true"></i></div>
                    <div id="paneldashboard">
                        <div class="search-box-home">
                            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-4 padd-0-catego">
                                <div class="select-style">
                                    <select tabindex="1" name="type" class="frm-select">
                                       <option value="footage" @if(\Request::get('type')=='footage') selected @endif>Footage</option>
                                       <option value="photo" @if(\Request::get('type')=='photo') selected @endif>Photo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8 padd-0">
                                <input class="trans-input-search" name="keyword" placeholder="Search photos, footages" type="text" value="{{\Request::get('keyword')}}"/>
                                <!--<div class="error-msg">Please enter valid Fields.</div>-->
                            </div>
                            <button type="submit" class="button-pos-serch"><img class="search-img-icns" src="{{url('/')}}/images/search-icon-top.png" alt="" /></button>
                            <div class="clr"></div>
                        </div>
                    </div>
                </div>
            </form>
           
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <!--Menu Start-->
            <div id="mySidenav" class="sidenav after-clns">
<div class="signup-sectino">
     <div class="sond">
                @if($role=='buyer')
                <div class="signup-sectino none-diplay">
                    <a href="{{url('/')}}/cart"> <span><img src="{{url('/')}}/images/cart-icons.png" class="img-cart" alt="" /></span> <span class="set-cart-count">0</span></a>
                </div>
                @endif
                <div class="signup-sectino none-diplay">
                    <a href="{{url('/')}}/notifications"> <span><i class="fa fa-bell"></i></span> <span class="countss" id='notification_div1'>0</span></a>
                </div>
                @if($role!='seller' && $role!='admin')
                <div class="signup-sectino none-diplay">
                    <a href="{{url('/')}}/my_collection"> <span><i class="fa fa-heart"></i></span> <span class="countss" id=''><span class="set-favourite-count">0</span></span></a>
                </div>
                @endif
            </div>
                </div>
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="banner-img-block">
                    <img src="{{url('/')}}/images/photoshoot-logo-responsive.png" alt="Logo" />
                    <div class="img-responsive-logo"></div>
                </div>

                <ul class="min-menu">
                    {{-- <li class="first-cls"><a href="javascript:void(0)" class="">Footage</a></li>
                    <li><a href="javascript:void(0)" class="drop-block">Photo</a></li> --}}
                    @if($role=='buyer')
                    <li class="cartbtnss ">
                        <a href="{{url('/')}}/cart"> <span><i class="fa fa-shopping-cart"></i></span><span class="countss"><span class="set-cart-count">0</span></span></a>
                    </li>
                    @endif
                    <li class="cartbtnss ">
                        <a href="{{url('/')}}/notifications"> <span><i class="fa fa-bell"></i></span> <span class="countss" id='notification_div2'>0</span></a>
                    </li>
                    @if($role!='seller' && $role!='admin')
                    <li class="cartbtnss ">
                        <a href="{{url('/')}}/my_collection" > <span><i class="fa fa-heart"></i></span> <span class="countss" id=''><span class="set-favourite-count">0</span></span></a>
                    </li>
                    @endif
                    <li>
                        <a href="{{url('/')}}/account" class="">
                            @if($user['role']=='seller')        
                            <span class="profile-pic-block">
                            @if($profile_img!='')
                            <img src="{{ get_resized_image($profile_img,config('app.project.img_path.user_profile_images'),137,137) }}" class="img-cart" alt="" />
                            @else
                            <img src="{{url('/').config('app.project.img_path.user_profile_images')}}user-male.png" class="img-cart" alt="" />
                            @endif
                            </span>
                            @endif
                            {{$user['first_name']}} {{$user['last_name']}}
                        </a>
                    </li>
                </ul>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="bank-div afterhedr"></div>
        <div class="clr"></div>
        <!--</div>-->
        @endif

    </div>

    <!-- end header -->

<script type="text/javascript">

var Site_URL = "{{ $path }}";
var to_user_id = '{{$user_id}}';
var token    = $('#token').val();

//cart count
var URL      = '{{url('/')}}/cart/cart_count';

$.ajax(
{
    'url':URL,                    
    'type':'get',
    success:function(res)   
    {
        if($.trim(res)!='')
        {
            $('.set-cart-count').html(res);
        }
    }
});

var fav_URL      = '{{url('/')}}/my_collection/count';

$.ajax(
{
    'url':fav_URL,                    
    'type':'get',
    success:function(res)   
    {
        if($.trim(res)!='')
        {
            $('.set-favourite-count').html(res);
        }
    }
}); 

$(document).ready(function()
{
    setInterval(function()
    {
        if(token != '')
        {    
            $.ajax(
            {
                'url':Site_URL,                    
                'type':'post',
                'data':{'to_user_id':to_user_id,'_token':token},
                success:function(res)   
                {
                    if($.trim(res)!='')
                    {
                        $('#notification_div1').html(res);
                        $('#notification_div2').html(res);
                    }
                }

            });
        }
    },5000);
});   

</script>    

 <script type="text/javascript" src="{{url('/')}}/js/front/menu_jquery.js"></script>
    <script>
        $(document).ready(function(){
           $(".dashboard-menu-head") .click(function(){
              $(".dashboard-menus").slideToggle("slow"); 
           });
        });
    </script>
    
<div class="themesflat-boxed">
    <!-- Preloader -->
    <div class="preloader">
        <div class="clear-loading loading-effect-2">
            <span></span>
        </div>
    </div>