@extends('front.layout.master')    

@section('main_content')
    <div class="thku">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="thanks-sec">
                        <div class="top-head-strip"></div>
                        <div class="log-middle">
                            <div class="thank-bx">
                                <h1> Thank You !</h1>
                                <h3> For Chossing</h3>
                                <a href="index.html"><img class="text-center max-wdths" src="images/logo-home.png" alt="Film Unit"></a>
                                <div class="thanks-text">
                                    We have sent you an email with a confirmation link. Please click on the link to confirm your registration.
                                </div>
                                <div class="text-center">
                                    <div class="button-section"><a href="{{url('/')}}">Go to home</a> </div>
                                </div>
                            </div>
                        </div>
                        <div class="log-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  About Us End Here-->

@endsection