@extends('front.layout.master')    
@section('main_content')

<?php
$user = Sentinel::check();

if($user==false || $user['role']=='admin')
{
  $url = url('/');
  Session::flush();
  Sentinel::logout();
  die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
}
?>

      <script src="{{url('/')}}/js/front/easyResponsiveTabs.js" type="text/javascript"></script>
  <!--tab css start-->
      <link href="{{url('/')}}/css/easy-responsive-tabs.css" rel="stylesheet" type="text/css" />
    <!--    Images Top Section Start Here-->
    
    <script src="{{url('/')}}/js/front/jquery.creditCardValidator.js"></script>
    <!--Cart Page Start Here-->
   <div class="buying-process-main">
        <div class="cart-section-block">
           
             <div class="cart-step-one">
                <div class="step-one step-active">
                    <span class="num-step font-style"><img src="images/cart-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Cart</span>
                </div>
                <div class="clearfix"></div>
            </div>
            
             <div class="cart-step-one">
                <div class="step-one step-active">
                    <span class="num-step font-style"><img src="images/payment-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Payment</span>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="cart-step-one">
                <div class="step-one">
                    <span class="num-step font-style"><img src="images/order-placed-icon.png" alt="" /></span>
                    <span class="name-step-confirm">Order Placed</span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>  
    
<!--    List-->
   <div class="container margin-top-new">
      <div class="checkout-film">
          <div class="row">
              <div class="col-sx-12 col-sm-7 col-md-7 col-lg-8">
                  <div class="checkout-box-bg">
                      <div class="subtitles"> Payment Method</div>
                 
                  
                  <div class="tabbing_area">
                      <div id="horizontalTab">
                          <ul class="resp-tabs-list">
                           <li>Credit Card</li>
                           <li>Paypal</li>
                        </ul>
                          <div class="resp-tabs-container">
                              <!--tab-1 start-->
                              <div>
                                  <div class="text_tab">
                                      <form action="{{url('/payment/cardpay')}}" method="post">
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      
                                      <div class="form-group cart-input">
                                        <div class="user-box1">
                                            <label class="label-l">Card Number</label>
                                            <input type="hidden" name="card_type" id="card_type" value="@if(isset($arr_card_data['card_type'])){{$arr_card_data['card_type']}}@endif" >
                                            <input class="cont-frm" type="text" name="card_number" id="card_number" value="@if(isset($arr_card_data['card_number'])){{$arr_card_data['card_number']}}@endif" />
                                            <img src="images/cart-icn-debt.png" class="img-carts" alt="" />
                                            <div class="error" id="err_card_number"></div>

                                        </div>
                                      </div>

                                      <div class="form-group mobile-mrg">
                                        <div class="user-box1">
                                         <div class="row">
                                             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                  <label class="label-l">Expiration Date</label>
                                             </div>
                                             <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="cont-frm arrow-down">
                                                    <select class="frm-select" name="card_exp_month" id="card_exp_month">
                                                    <option value="">Select Month</option>
                                                    <option value="01" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='01'){{"selected='selected'"}}@endif>Jan</option>
                                                    <option value="02"  @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='02'){{"selected='selected'"}}@endif>Feb</option>
                                                    <option value="03" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='03'){{"selected='selected'"}}@endif>Mar</option>
                                                    <option value="04" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='04'){{"selected='selected'"}}@endif>Apr</option>
                                                    <option value="05" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='05'){{"selected='selected'"}}@endif>May</option>
                                                    <option value="06" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='06'){{"selected='selected'"}}@endif>Jun</option>
                                                    <option value="07" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='07'){{"selected='selected'"}}@endif>Jul</option>
                                                    <option value="08" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='08'){{"selected='selected'"}}@endif>Aug</option>
                                                    <option value="09" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='09'){{"selected='selected'"}}@endif>Sept</option>
                                                    <option value="10" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='10'){{"selected='selected'"}}@endif>Oct</option>
                                                    <option value="11" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='11'){{"selected='selected'"}}@endif>Nov</option>
                                                    <option value="12" @if(isset($arr_card_data['card_exp_month']) && $arr_card_data['card_exp_month']=='12'){{"selected='selected'"}}@endif>Dec</option>
                                                </select>                                              

                                                </div>
                                                <div class="error" id="err_card_exp_month"></div>
                                             </div>
                                             <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="cont-frm arrow-down">
                                                    <select class="frm-select" name="card_exp_year" id="card_exp_year">
                                                        <option value="">Select Year</option>
                                                        <?php 
                                                        for ($i = date('Y'); $i <= date('Y')+10; $i++) 
                                                        {?>
                                                            <option value="{{$i}}" @if(isset($arr_card_data['card_exp_year']) && $arr_card_data['card_exp_year']==$i){{"selected='selected'"}}@endif>{{$i}}</option>
                                                           
                                                       <?php } 
                                                        ?>
                                                        
                                                        
                                                    </select>                                                  
                                                 </div>
                                                 <div class="error" id="err_card_exp_year"></div>
                                             </div>
                                             <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                               <div class="input-checkout">
                                                    <input class="cont-frm" placeholder="Enter Your CVC" type="text" name="card_cvc" id="card_cvc" value="@if(isset($arr_card_data['card_cvc']) && $arr_card_data['card_cvc']!='0'){{$arr_card_data['card_cvc']}}@endif" />
                                                    <a href="#">?</a>
                                                     <div class="error" id="err_card_cvc"></div>

                                               </div>
                                             </div>
                                         </div>
                                      </div>
                                      </div>
                                      
                                        <div class="form-group">
                                            <div class="user-box1">
                                                <label class="label-l">Card Holder Name</label>
                                                <input class="cont-frm" type="text" name="card_holder_name" id="card_holder_name" value="@if(isset($arr_card_data['card_holder_name'])){{$arr_card_data['card_holder_name']}}@endif"/>
                                                <div class="error" id="err_card_holder_name"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group checkout-checkobx">
                                              <div class="user-box1">
                                               <div class="checkbox-sign">
                                                  <input type="checkbox" class="css-checkbox" id="checkbox111" tabindex="3" name="card_save" id="card_save"  @if(isset($arr_card_data['card_save']) && $arr_card_data['card_save']=='1'){{"checked='checked'"}}@endif/>
                                                  <label class="css-label lite-red-check remember_me"  for="checkbox111">Save this card for future purposes</label>
                                                   <div class="error" id="err_card_save"></div>

                                               </div>
                                            </div>
                                         </div>
                                         
{{--                                          <div class="form-group">
                                            <div class="user-box1">
                                                <label class="label-l">Write a Note</label>
                                                <input class="cont-frm" type="text" name="note" id="note" />
                                                 <div class="error" id="err_note"></div>

                                            </div>
                                        </div> --}}
                                        
                                        
                                        <div class="form-group checkout-checkobx">
                                              <div class="user-box1">
                                               <div class="checkbox-sign">
                                                  <input type="checkbox" @if(isset($arr_card_data['is_agree']) && $arr_card_data['is_agree']=='1'){{"checked='checked'"}}@endif class="css-checkbox" id="checkbox4" tabindex="3" name="is_agree" id="is_agree"/>
                                                  <label class="css-label lite-red-check remember_me"  for="checkbox4">I have read and agreed to the  <a href="#">Royalty Free License Agreement</a></label>
                                                   <div class="error" id="err_is_agree"></div>

                                               </div>
                                            </div>
                                         </div>
                                        <div class="button-section cart-btnss right-side" type="submit">
                                        <button type="submit" name="cardpay" id="cardpay">Place Order</button>
                                         </div>
                                        </form>
                                  </div>
                              </div>

                              <!--tab-2 start-->
                               <div>
                                  <div class="text_tab">
                                      <div class="paypal-bottons-check">
                                          <div class="button-section checkout_paypal"><a href="{{url('/payment/paynow')}}"><span><img src="{{url('/')}}/images/paypal-icns.png" alt="" /></span> Check Out with Paypal</a> </div>
                                      </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>
                       </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
              @if(isset($arr_data) && count($arr_data)>0)
              <?php $grand_total = 0; ?>
              @foreach($arr_data as $key=>$value)
              <?php $grand_total += $value['price']; ?>
              @endforeach
              <div class="col-sx-12 col-sm-5 col-md-5 col-lg-4">
                 <div class="checkout-box-bg">
                     <div class="texts-center">
                      <div class="subtitles"> Total : ${{$grand_total}} </div>
                      <div class="order-summary-txt">Order Summery</div>
                      </div>
                      <div class="sub-totals">Subtotal ({{count($arr_data)}} Items)</div>
                      <div class="sub-totals-right">${{$grand_total}}</div>
                      
                      <div class="sub-totals totl-fonts">Total</div>
                      <div class="sub-totals-right totl-fonts">${{$grand_total}}</div>
                     <!-- <div class="border-checkbox"></div>-->
                      
{{--                       <div class="checkbox-sign right-side-txt">
                          <input checked="checked" class="css-checkbox" id="checkbox5" tabindex="3" type="checkbox">
                          <label class="css-label lite-red-check remember_me" for="checkbox5">Check out as guest</label>
                       </div>
                      <div class="button-section full-widths"><a href="http://webwingdemo.com/node8/demodesign/filmunit/check-out-3.html">Checkout</a> </div> --}}
                      </div>   
              </div>
              @endif
          </div>
      </div>
   </div>
    <!--Cart Page end Here-->
    <div id="footer"></div>
    
<!--    HorizontalTab Start -->
    <script type="text/javascript">

/*    $('.checkout_paypal').click(function(e) {
    $('.checkout_paypal').bind('click');
    });*/

     $('#horizontalTab').easyResponsiveTabs({
           type: 'default',      
           width: 'auto', 
           fit: true,
           closed: 'accordion',
           activate: function(event) { 
               var $tab = $(this);
               var $info = $('#tabInfo');
               var $name = $('span', $info);
         
               $name.text($tab.text());
         
               $info.show();
           }
         });
    </script>
    
   
<!--    HorizontalTab End -->
<script type="text/javascript">
  
  $("#cardpay").on('click',function()
  {
    var card_number =$("#card_number").val();
    var card_exp_month =$("#card_exp_month").val();
    var card_exp_year =$("#card_exp_year").val();
    var card_cvc =$("#card_cvc").val();
    var card_holder_name =$("#card_holder_name").val();
    var note =$("#note").val();


    $("#err_card_number").html('');
    $("#err_card_exp_month").html('');
    $("#err_card_exp_year").html('');
    $("#err_card_cvc").html('');
    $("#err_card_holder_name").html('');
    $("#err_is_agree").html('')
  
    var flag=1;
    if(card_number=='')
    {
      $("#err_card_number").html('Please enter the card number');    
      flag=0;
      $("#card_number").on('keyup',function(){$("#err_card_number").html('');});
    }
    else
    {
      var input = $("#card_number");
      var result = input.validateCreditCard();
      if(result.card_type === null)
      {

         $("#err_card_number").html('Unknown card type');
         flag=0;
         $("#card_number").on('keyup',function(){$("#err_card_number").html('');});
      }
      else 
      {
        //console.log(result.card_type.name);
        $("#card_type").attr('value',result.card_type.name); 
        if(result.valid!=true)
        {
          $("#err_card_number").html('Card number invalid');
          flag=0;
          $("#card_number").on('keyup',function(){$("#err_card_number").html('');});
        }
        else if(result.length_valid!=true)
        {
          $("#err_card_number").html('Card length invalid');
          flag=0;
          $("#card_number").on('keyup',function(){$("#err_card_number").html('');});
        }
        else if(result.luhn_valid!=true)
        {
           $("#err_card_number").html('Card number invalid format');
          flag=0;
          $("#card_number").on('keyup',function(){$("#err_card_number").html('');});
        }            
        
      }
     
    }

    if(card_exp_month=='')
    {
      $("#err_card_exp_month").html('Please enter the card expiry month');
      flag=0;
      $("#card_exp_month").on('change',function(){$("#err_card_exp_month").html('');});
    }
    else if(card_exp_year=='')
    { 
      $("#err_card_exp_year").html('Please enter the card expiry year');
      $("#card_exp_year").on('change',function(){$("#err_card_exp_year").html('');});
      flag=0;
    }
    else 
    {
      var minMonth = new Date().getMonth() + 1;
      var minYear = new Date().getFullYear();
      var month = parseInt(card_exp_month,10);
      var year = parseInt(card_exp_year,10);
      if(!month || !year || year > minYear || (year === minYear && month >= minMonth))
      {
        
      }
      else
      {
        $("#err_card_exp_year").html('Please enter the valid card expiry year');
        $("#card_exp_year").on('change',function(){$("#err_card_exp_year").html('');});
        flag=0;
      }

    }


    if(card_cvc=='')
    {
      $("#err_card_cvc").html('Please enter the card cvc');
      flag=0;
      $("#card_cvc").on('keyup',function(){$("#err_card_cvc").html('');});
    }
    else if(card_cvc.length>3)
    {
      $("#err_card_cvc").html('Please enter valid card cvc');
      flag=0;
      $("#card_cvc").on('keyup',function(){$("#err_card_cvc").html('');});
    }
    if(card_holder_name=='')
    {
      $("#err_card_holder_name").html('Please enter the card holder name');
      flag=0;
      $("#card_holder_name").on('keyup',function(){$("#err_card_holder_name").html('');});
    }
    else if(!isNaN(card_holder_name))
    {
      $("#err_card_holder_name").html('Please enter the valid card holder name');
      flag=0;
      $("#card_holder_name").on('keyup',function(){$("#err_card_holder_name").html('');});
    }

    if($('input[name=is_agree]:checked').length == 0)
    {
      $("#err_is_agree").html('This field is required');
      flag=0;
    }
   /* if(note=='')
    {
      $("#err_note").html('Please enter the note');
      flag=0;
      $("#note").on('keyup',function(){$("#err_note").html('');});
    }*/

    //return false;
    if(flag==0)
    {
      return false;
    }
    else
    {
      return true;
    }   
    

  });
</script>

@endsection

