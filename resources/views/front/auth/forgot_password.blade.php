@extends('front.layout.master')    

@section('main_content')

<!--    Login Section Page Here-->
<div class="login-page">
    <div class="login-modal">
      <!--User Icon Section-->
        <div class="user-login">
            <img src="images/user-icon.png" alt="" />
            <h1>Forgot Password</h1>
            <p>We will send a link on your registered email to reset your password.</p>
        </div>
        
    <!--    Flash message Blade  -->
    @include('front.layout._operation_status')

  <!--    Form Start-->
    <form name="FrogotPasswordForm" id="FrogotPasswordForm" method="post" action="{{url('/')}}/process_forgot_password">
    {{csrf_field()}}
       <div class="form-inputs">
           <input class="input-text" name="email" id="email" placeholder="Email" type="email" maxlength="75"/>
            <span class="error" id="err_email">{{ $errors->first('email') }}</span>  
         </div>
      
      <div class="button-logn">
            <button class="btn-login-section" id="btn_forgot_password" type="submit">Reset Password</button>
      </div>

      <div class="footer-login">
          Back to <a href="{{url('/')}}/login"> Login</a>
      </div>


    </form>     

    </div>
</div>
<!--    Login Section Page End Here-->

<!--    validation JS  -->
<script type="text/javascript" src="{{url('/')}}/js/front/validations.js"></script>

@endsection
