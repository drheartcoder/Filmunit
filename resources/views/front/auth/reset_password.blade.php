@extends('front.layout.master')    

@section('main_content')
<!--    Login Section Page Here-->
<div class="login-page">
      <div class="login-modal">
        <!--User Icon Section-->
          <div class="user-login">
              <img src="{{url('/')}}/images/user-icon.png" alt="" />
              <h1>Reset Password</h1>
              <p>Please Enter your new password</p>
          </div>

    <!--    Flash message Blade  -->
    @include('front.layout._operation_status')

  <!--    Form Start-->
    <form name="ResetPasswordForm" id="ResetPasswordForm" method="post" action="{{url('/')}}/validate_reset_password_link/{{$enc_id}}/{{$enc_reminder_code}}">
    {{csrf_field()}}
       <div class="form-inputs">
           <input class="input-text" name="password" id="password" placeholder="New Password" type="password" maxlength="20"/>  
           <span class="error" id="err_password">{{ $errors->first('password') }}</span>  
         </div>
          
         <div class="form-inputs">
           <input class="input-text" name="c_password" id="c_password" placeholder="Confirm Password" type="password" maxlength="20"/>
           <span class="error" id="err_c_password">{{ $errors->first('c_password') }}</span>  
         </div>
      
      <div class="button-logn">
          <button class="btn-login-section" id="btn_reset_password" type="submit">Submit</button>
      </div>

      <div class="footer-login">
          Back to <a href="{{url('/')}}/login"> Login</a>
      </div>

    </form> 
</div>
<!--    Login Section Page End Here-->

<!--    validation JS  -->
<script type="text/javascript" src="{{url('/')}}/js/front/validations.js"></script>

@endsection
