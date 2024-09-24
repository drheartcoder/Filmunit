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
                                <h1> Sorry !</h1>
                                <h3> Transaction Failed</h3>
                                <a href="index.html"><img class="text-center max-wdths" src="{{url('/')}}/images/logo-home.png" alt="Film Unit"></a>
                                <div class="thanks-text">
                                    Your transaction is failed. Please try again.
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