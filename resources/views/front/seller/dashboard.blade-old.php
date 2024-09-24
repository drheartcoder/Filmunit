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
                <div class="col-sm-8 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="light-grey-bg-block padding-small new-block-css">
                                <h1 class="accou-heat"> My Account </h1>
                                <div class="profile-info-mian">
                                    <div class="profile-info marg-botto">
                                        <span class="label-profile-info secon">First Name</span>
                                        <span class="content-profile-info">{{isset($arr_user) && isset($arr_user['first_name']) && $arr_user['first_name']!=''?$arr_user['first_name']:'NA'}} </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="profile-info marg-botto">
                                        <span class="label-profile-info secon">Last Name</span>
                                        <span class="content-profile-info">{{isset($arr_user) && isset($arr_user['last_name']) && $arr_user['last_name']!=''?$arr_user['last_name']:'NA'}} </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="profile-info marg-botto">
                                        <span class="label-profile-info secon">Email Id</span>
                                        <span class="content-profile-info">{{isset($arr_user) && isset($arr_user['email']) && $arr_user['email']!=''?$arr_user['email']:'NA'}}</span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="profile-info marg-botto">
                                        <span class="label-profile-info secon">Address</span>
                                        <span class="content-profile-info">{{isset($arr_user) && isset($arr_user['address']) && $arr_user['address']!=''?str_limit($arr_user['address'],50):'NA'}}</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="circle-icon-block hieght-no">
                                    <a href="{{url('/')}}/account" class="circle-icon"> <img src="{{url('/')}}/images/user-menu-1.png" class="circle-img-icon" alt="" /> <img src="{{url('/')}}/images/user-menu-2.png" class="circle-img-hover" alt="" /> </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="light-grey-bg-block padding-small  new-block-css">
                                <h1 class="accou-heat"> My Photos & Footage </h1>
                                <div class="account-text-blocl">
                                    <h1> {{$media_count}} </h1>
                                    <h3>(Till the Date)</h3>
                                    <div class="clr"></div>
                                </div>
                                <div class="circle-icon-block">
                                    <a href="{{url('/')}}/seller/photos_and_footage" class="circle-icon line-height"> <i class="fa fa-image"></i> </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="light-grey-bg-block padding-small  new-block-css">
                                <h1 class="accou-heat"> Finance </h1>
                                <div class="account-text-blocl">
                                    <h1> {{$transaction_count}} </h1>
                                    <h3>(Till the Date)</h3>
                                    <div class="clr"></div>
                                </div>
                                <div class="circle-icon-block hieght-no">
                                    <a href="{{url('/')}}/seller/finance" class="circle-icon"> <img src="{{url('/')}}/images/doller-1.png" class="circle-img-icon" alt="" /> <img src="{{url('/')}}/images/doller-2.png" class="circle-img-hover" alt="" /> </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="light-grey-bg-block padding-small  new-block-css">
                                <h1 class="accou-heat"> Admin Photos & Footage </h1>
                                <div class="account-text-blocl">
                                    <h1> {{$admin_media_count}} </h1>
                                    <h3>(Till the Date)</h3>
                                    <div class="clr"></div>
                                </div>
                                <div class="circle-icon-block">
                                    <a href="{{url('/')}}/seller/photos_and_footage/admin_listing" class="circle-icon line-height"> <i class="fa fa-image"></i> </a>
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clr"></div>
    <!--Buyer Account Section End Here-->

  

@endsection

