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
                        <h1 class="accou-heat"> Change Password </h1>
                        <form name="changePasswordForm" id="changePasswordForm" method="post" action="{{url('/')}}/update_password">
                        {{csrf_field()}}

                            <div class="row">
                                <div class="col-sm-12 col-md12 col-lg-12">
                                    <div class="account-text-fild">
                                        <div class="form-group">
                                            <div class="user-box1">
                                                <label class="label-l">Old Password</label>
                                                <input class="cont-frm" placeholder="Enter Your Old Password" type="password" name="old_password" id="old_password" />
                                                <div class="error" id="err_old_password"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="account-text-fild">
                                        <div class="form-group">
                                            <div class="user-box1">
                                                <label class="label-l">New Password</label>
                                                <input class="cont-frm" placeholder="Enter Your New Password" type="password" name="new_password" id="new_password"/>
                                                <div class="error" id="err_new_password"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="account-text-fild">
                                        <div class="form-group">
                                            <div class="user-box1">
                                                <label class="label-l">Confirm New Password</label>
                                                <input class="cont-frm" placeholder="Enter Your Confirm New Password" type="password" name="confirm_password" id="confirm_password"/>
                                                <div class="error" id="err_confirm_password"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                      
<!--                        <a class="button-section right-side tp-btns right-new-btn-css"><button id="btn_update_password" type="submit">Save</button></a>-->
                       <button class="change-pass-submit pull-right" id="btn_update_password" type="submit">Save</button>
                        <div class="clr"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->

<!--    validation JS  -->
<script type="text/javascript" src="{{url('/')}}/js/front/validations.js"></script>

@endsection
