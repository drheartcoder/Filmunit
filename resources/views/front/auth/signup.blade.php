@extends('front.layout.master')    

@section('main_content')

<!--    Login Section Page Here-->
<div class="login-page">
    <form name="SignupForm" id="SignupForm" method="post" action="{{url('/')}}/store_user">
    {{csrf_field()}}
      <div class="login-modal signup-page-block">
        <!--User Icon Section-->
          <div class="user-login">
              <img src="images/singup-icon.png" alt="" />
              <h1>Sign Up</h1>
              <p>Please Signup to your account</p>
          </div>
    
    <!--    Flash message Blade  -->
    @include('front.layout._operation_status')

    <!--    Form Start-->
         <div class="form-inputs" align="center">
<!--
           <input class="input" name="user_type" id="user_type"  value="buyer" type="radio"/> Buyer
           <input class="input" name="user_type" id="user_type"  value="seller" type="radio"/> Seller
-->
           <div class="radio-btns radio-btns-main">  
                <div class="radio-btn">
                    <input type="radio" name="user_type" id="f-option" value="buyer">
                    <label for="f-option">Buyer</label>
                    <div class="check"></div>
                </div>  
                <div class="radio-btn">
                    <input type="radio" name="user_type" id="s-option" value="seller" >
                    <label for="s-option">Seller</label>
                    <div class="check"><div class="inside"></div></div>
                </div>
                <span class="error" id="err_user_type">{{ $errors->first('user_type') }}</span>  
            </div>            
         </div>
         <div class="row">
             <div class="col-md-6">
                 <div class="form-inputs">
                   <input class="input-text" name="first_name" id="first_name" placeholder="First Name" value="{{ old('first_name') }}" type="text" maxlength="25" />
                   <span class="error" id="err_first_name">{{ $errors->first('first_name') }}</span> 
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-inputs">
                   <input class="input-text" name="last_name" id="last_name" placeholder="Last Name" type="text" maxlength="25"/>
                   <span class="error" id="err_last_name">{{ $errors->first('last_name') }}</span>  
                 </div>
             </div>
         </div>
          <div class="form-inputs">
           <input class="input-text" name="email" id="s_email" placeholder="Email" type="email" maxlength="75"/>
            <span class="error" id="err_s_email">{{ $errors->first('email') }}</span>  
         </div>
         
         <div class="form-inputs">
           <input class="input-text" name="password" id="password" placeholder="Password" type="password" maxlength="20"/>  
           <span class="error" id="err_password">{{ $errors->first('password') }}</span>  
         </div>
          
         <div class="form-inputs">
           <input class="input-text" name="c_password" id="c_password" placeholder="Confirm Password" type="password" maxlength="20"/>
           <span class="error" id="err_c_password">{{ $errors->first('c_password') }}</span>  
         </div>
         
         <div class="form-inputs">
           <div class="checkbox-sign">
              <input type="checkbox" name="terms" class="css-checkbox" id="checkbox111" tabindex="3" value="yes" />
              <label class="css-label lite-red-check remember_me"  for="checkbox111">Yes, I agree with Terms of services</label>
              <span class="error" id="err_terms">{{ $errors->first('terms') }}</span>
           </div>
          
        </div>
        <div class="button-logn">
            <button class="btn-login-section" id="btn_signup" type="submit">Sign Up</button>
        </div>
        <div class="footer-login">
            Already have an Account? <a href="{{url('/')}}/login">Login</a>
        </div>
      </div>
    </form>
</div>
<!--    Login Section Page End Here-->

<!--    validation JS  -->
<script type="text/javascript" src="{{url('/')}}/js/front/validations.js"></script>
<script type="text/javascript">
  $("#s_email").focusout(function () {
        email = $(this).val();
          $.ajax({
                type: "POST",
                url: "check_email_duplication",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'email' : email
                },

                dataType: "text",
                success: function(msg){
                  if(msg=='true')
                  {
                     $('#err_s_email').show();
                     $('#err_s_email').html('This Email Id is already exists');
                     return false;  
                  }
                  else if(msg=='false')
                  {
                     $('#err_s_email').hide();
                     return true;
                  }
                }
                });
    });

</script>
@endsection
