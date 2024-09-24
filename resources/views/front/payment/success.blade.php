@extends('front.layout.master')    

@section('main_content')

   <!--Cart Page Start Here-->
   <div class="buying-process-main">
        <div class="cart-section-block">
           
             <div class="cart-step-one">
                <div class="step-one step-active">
                    <span class="num-step font-style"><img src="{{url('/')}}/images/cart-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Cart</span>
                </div>
                <div class="clearfix"></div>
            </div>
            
             <div class="cart-step-one">
                <div class="step-one step-active">
                    <span class="num-step font-style"><img src="{{url('/')}}/images/payment-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Payment</span>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="cart-step-one">
                <div class="step-one step-active">
                    <span class="num-step font-style"><img src="{{url('/')}}/images/order-placed-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Order Placed</span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>  

    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="checkout-heights">
  
        <div class="container">
            <div class="light-grey-bg-block">
              <div class="subtitles">Order Placed</div>
               <div class="check-out-text-blo">
                   <h1 class="downlo-txt-blo-h1"> Success ! </h1>
                   <h2> Your Order Has Been successfully placed. </h2>
                   {{-- <span>Order No:</span>  <span class="grey-order-text"> #000000000</span> --}}
                   <br/>
                   <div class="button-section"><a href="{{url('/')}}/buyer/downloads">See Downloads</a> </div>
               </div>
                <div class="clr"></div> 
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->
@endsection