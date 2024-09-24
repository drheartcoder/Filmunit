@extends('front.layout.master')    
@section('main_content')
    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="buyer-account-bg-main min-heightsone">
        <div class="container">
            <div class="row">
                <!-- BEGIN Sidebar -->
                @include('front.layout._sidebar')
                <!-- END Sidebar -->
            <form name="uploadFootagePhotosForm" id="uploadFootagePhotosForm" enctype="multipart/form-data" method="post" action="{{$module_url_path}}/update">
            {{csrf_field()}}
                <div class="col-sm-8 col-md-8 col-lg-9">
                    <!--    Flash message Blade  -->
                    @include('front.layout._operation_status')

                    <div class="light-grey-bg-block">
                        <h1 class="accou-heat"> Notification </h1>
                        @if(isset($arr_data['data']) && count($arr_data['data'])>0)
                        @foreach($arr_data['data'] as $key=>$value)                           
                        <div class="form-main-block notification-main-block">
                            <div class="notification-block">
                                <div class="notification-icon">
                                    <img src="{{$profile_image_public_img_path.$value['from_user_id_details']['profile_image']}}" alt="" />
                                </div>
                                <div class="notification-content">
                                    
                                    <div class="noti-head-content">
                                         <!-- <span class="short-content">{{isset($value['message']) & $value['message']!='' ? ucwords($value['message']):'NA'}}</span>
                                        <span class="details-content">{{isset($value['message']) & $value['message']!='' ? ucwords($value['message']):'NA'}}</span>  -->
  
                                                   <?php if(isset($value['message']))
                                                  {
                                                    echo html_entity_decode($value['message']); 
                                                  } ?> 
                                                
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $(".notification-block").click(function(){
                                           $(this).toggleClass("active");
                                            $(this).siblings().removeClass("active");
                                        });
                                    });
                                </script>
                                <button type="button" class="close-noti" >
                                    <a href="{{$module_url_path}}/delete/{{base64_encode($value['id'])}}" onclick="return confirm_action(this,event,'Do you really want to delete this record ?')">
                                    <img src="{{url('/')}}/images/noti-close-icon.png" alt="" class="gray-close-icon" />
                                    <img src="{{url('/')}}/images/noti-close-icon-active.png" alt="" class="active-close-icon" />
                                    </a>
                                </button>
                                <div class="clr"></div>
                            </div>
                        <div class="clr"></div> 
                    </div>
                    @endforeach
                </div>
                     <!-- Paination Links -->
                    @include('front.templates.pagination_view')
                    @else
                    <h3 style="text-align: center; color: #FFF"><center>No records Found.</center></h3>
                    @endif
            </div>
            </form>    
        </div>
    </div>
</div>    
    <div class="clr"></div>
    <!--Buyer Account Section End Here-->
    
<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/admin/sweetalert_msg.js"></script>

@endsection

