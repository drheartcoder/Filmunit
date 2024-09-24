@extends('front.layout.master')    

@section('main_content')
<!--    Login Section Page Here-->
<div class="login-page">
  <form name="loginForm" id="loginForm" method="post" action="{{url('/')}}/process_login?b_url={{url()->previous()}}">
    {{csrf_field()}}
      <div class="login-modal">
        <!--User Icon Section-->
          <div class="user-login">
              <img src="images/user-icon.png" alt="" />
              <h1>Login</h1>
              <p>Please login to your account</p>
          </div>

    <!--    Flash message Blade  -->
    @include('front.layout._operation_status')
    
    <!--    Form Start-->
         <div class="form-inputs">
            <input class="input-text" name="email" id="email" placeholder="Email" type="email" maxlength="75" />
            <span class="error" id="err_email">{{ $errors->first('email') }}</span>  
         </div>
          <div class="form-inputs">
            <input class="input-text" name="password" id="password" placeholder="Password" type="password" maxlength="20"/>
            <span class="error" id="err_password">{{ $errors->first('password') }}</span>  
         </div>
         
         <div class="form-inputs">
           <div class="checkbox-sign">
              <input type="checkbox" name="remember_me" checked="checked" class="css-checkbox" id="checkbox111" tabindex="3" value="yes" />
              <label class="css-label lite-red-check remember_me"  for="checkbox111">Remember my details</label>
           </div>
           <div class="forget-pwd"><a href="{{url('/')}}/forgot_password">Forgot Password?</a></div>
        </div>
        <div class="button-logn">
            <button class="btn-login-section" id="btn_login" type="submit">Login</button>
        </div>
        <div class="footer-login">
            Don't have an account? <a href="{{url('/')}}/signup">Sign Up</a>
        </div> 
      </div>
  </form>
</div>
<!--    Login Section Page End Here-->

<!--    validation JS  -->
<script type="text/javascript" src="{{url('/')}}/js/front/validations.js"></script>

@endsection
