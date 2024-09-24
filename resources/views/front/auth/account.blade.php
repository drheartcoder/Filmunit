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
                    <!--    Flash message Blade  -->
                    @include('front.layout._operation_status')
                    
                    <div class="light-grey-bg-block">
                        <h1 class="accou-heat"> My Account View </h1>
                          <div class="form-main-block">
                            <div class="profile-info-mian">
                                <div class="profile-info">
                                    <div class="label-profile-info">First Name</div>
                                    <div class="content-profile-info">: &nbsp;&nbsp; {{isset($arr_user) && isset($arr_user['first_name']) && $arr_user['first_name']!=''?$arr_user['first_name']:'NA'}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="profile-info">
                                    <div class="label-profile-info">Last Name</div>
                                    <div class="content-profile-info">: &nbsp;&nbsp; {{isset($arr_user) && isset($arr_user['last_name']) && $arr_user['last_name']!=''?$arr_user['last_name']:'NA'}}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="profile-info">
                                    <div class="label-profile-info">Email Address</div>
                                    <div class="content-profile-info">: &nbsp;&nbsp; {{isset($arr_user) && isset($arr_user['email']) && $arr_user['email']!=''?$arr_user['email']:'NA'}}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="profile-info">
                                    <div class="label-profile-info">Contact Number</div>
                                    <div class="content-profile-info">: &nbsp;&nbsp; {{isset($arr_user) && isset($arr_user['contact_number']) && $arr_user['contact_number']!=''?$arr_user['contact_number']:'NA'}}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="profile-info">
                                    <div class="label-profile-info">Address</div>
                                    <div class="content-profile-info">: &nbsp;&nbsp; {{isset($arr_user) && isset($arr_user['address']) && $arr_user['address']!=''?$arr_user['address']:'NA'}}</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                          
                         <div class="button-section right-side tp-btns"><a href="{{url('/')}}/edit_account">Edit</a> </div>
                         
                        <div class="clr"></div> 
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->
@endsection
