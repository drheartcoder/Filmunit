
$(document).ready(function(){

//Front Login validation
  $('#btn_signup').click(function()
  {
      var first_name      = $('#first_name').val();
      var last_name       = $('#last_name').val();
      var email           = $('#s_email').val();
      var password        = $('#password').val();
      var c_password      = $('#c_password').val();

      var filter          = /^([_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]*\.([a-z]{2,4})|[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]*\.([a-z]{2,4})*\.([a-z]{2,4}))$/;
      var name_filter     = /^[A-z]+$/;
      var password_filter = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/;

      $('#err_user_type').html(''); $('#err_user_type').hide();
      $('#err_first_name').html(''); $('#err_first_name').hide();
      $('#err_last_name').html(''); $('#err_last_name').hide();
      $('#err_s_email').html(''); $('#err_s_email').hide();
      $('#err_password').html(''); $('#err_password').hide();
      $('#err_c_password').html(''); $('#err_c_password').hide();
      $('#err_terms').html(''); $('#err_terms').hide();

      var flag=1;

      if($('input[name=user_type]:checked').length<=0)
      {
        $('#err_user_type').html('Please select User type.');
        $('#err_user_type').show();
        flag=0;
      }

      if(first_name.trim()=='')
      {
        $('#err_first_name').html('Please enter first name.');
        $('#err_first_name').show();
        flag=0;
      }
      else if(!name_filter.test(first_name))
      {
        $('#err_first_name').html('Please enter valid first name.');
        $('#err_first_name').show();
        flag=0;
      }
       else if(first_name.length<3)
      {
        $('#err_first_name').html('First name must contain minimum 3 characters.');
        $('#err_first_name').show();
        flag=0;
      }

      if(last_name.trim()=='')
      {
        $('#err_last_name').html('Please enter last name.');
        $('#err_last_name').show();
        flag=0;
      }
      else if(!name_filter.test(last_name))
      {
        $('#err_last_name').html('Please enter valid first name.');
        $('#err_last_name').show();
        flag=0;
      }
       else if(last_name.length<3)
      {
        $('#err_last_name').html('Last name must contain minimum 3 characters.');
        $('#err_last_name').show();
        flag=0;
      }

      if(email.trim()=='')
      {
        $('#err_s_email').html('Please enter email id.');
        $('#err_s_email').show();
        flag=0;
      }

      else if(!filter.test(email))
      {
        $('#err_s_email').html('Please enter valid email id.');
        $('#err_s_email').show();
        flag=0;
      }

      if(password.trim()=='')
      {
        $('#err_password').html('Please enter password.');
        $('#err_password').show();
        flag=0;
      }
      else if(!password_filter.test(password))
      {
        $('#err_password').html('Min. length should be 6 containing letter, number and special character.');
        $('#err_password').show();
        flag=0;
      }

      if(c_password.trim()=='')
      {
        $('#err_c_password').html('Please re-type password.');
        $('#err_c_password').show();
        flag=0;
      }

      else if(password!=c_password)
      {
        $('#err_c_password').html('Confirm password not matched.');
        $('#err_c_password').show();
        flag=0;
      }

      if(!$('#checkbox111').is(':checked'))
      {
        $('#err_terms').html('Please accept the Terms of Services.');
        $('#err_terms').show();
        flag=0;
      }

      if(flag==1)
      {
        return true;
      }
      else
      {
        return false;
      }

  });

  //Front Login validation
  $('#btn_login').click(function()
  {
      var email           = $('#email').val();
      var password        = $('#password').val();

      var filter          = /^([_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]*\.([a-z]{2,4})|[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]*\.([a-z]{2,4})*\.([a-z]{2,4}))$/;

      $('#err_email').html(''); $('#err_email').hide();
      $('#err_password').html(''); $('#err_password').hide();


      var flag=1;

      if(email.trim()=='')
      {
        $('#err_email').html('Please enter email id.');
        $('#err_email').show();
        flag=0;
      }

      else if(!filter.test(email))
      {
        $('#err_email').html('Please enter valid email id.');
        $('#err_email').show();
        flag=0;
      }

      if(password.trim()=='')
      {
        $('#err_password').html('Please enter password.');
        $('#err_password').show();
        flag=0;
      }

      if(flag==1)
      {
        return true;
      }
      else
      {
        return false;
      }

  });

  //Front forget Password validation
  $('#btn_forgot_password').click(function()
  {
      var email           = $('#email').val();
      var filter          = /^([_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]*\.([a-z]{2,4})|[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]*\.([a-z]{2,4})*\.([a-z]{2,4}))$/;

      $('#err_email').html(''); $('#err_email').hide();

      var flag=1;

      if(email.trim()=='')
      {
        $('#err_email').html('Please enter email id.');
        $('#err_email').show();
        flag=0;
      }
      else if(!filter.test(email))
      {
        $('#err_email').html('Please enter valid email id.');
        $('#err_email').show();
        flag=0;
      }

      if(flag==1)
      {
        return true;
      }
      else
      {
        return false;
      }

  });

  //Front reset Password validation
  $('#btn_reset_password').click(function()
  {
      var password        = $('#password').val();
      var c_password      = $('#c_password').val();

      var password_filter = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/;

      $('#err_password').html(''); $('#err_password').hide();
      $('#err_c_password').html(''); $('#err_c_password').hide();

      if(password.trim()=='')
      {
        $('#err_password').html('Please enter password.');
        $('#err_password').show();
        flag=0;
      }
      else if(!password_filter.test(password))
      {
        $('#err_password').html('Min. length should be 6 containing letter, number and special character.');
        $('#err_password').show();
        flag=0;
      }

      if(c_password.trim()=='')
      {
        $('#err_c_password').html('Please re-type password.');
        $('#err_c_password').show();
        flag=0;
      }

      else if(password!=c_password)
      {
        $('#err_c_password').html('Confirm password not matched.');
        $('#err_c_password').show();
        flag=0;
      }

      if(flag==1)
      {
        return true;
      }
      else
      {
        return false;
      }

  });

  //Buyer/Seller Edit Account validation
  /*$('#btn_edit_account').click(function()
  {
      var first_name      = $('#first_name').val();
      var last_name       = $('#last_name').val();
      var contact_number  = $('#contact_number').val();
      var address         = $('#autocomplete').val();
      var zipcode         = $('#postal_code').val();
      var city            = $('#locality').val();
      var country         = $('#country').val();

      var name_filter     = /^[A-z]+$/;
      var contact_filter  = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;

      $('#err_first_name').html(''); $('#err_first_name').hide();
      $('#err_last_name').html(''); $('#err_last_name').hide();
      $('#err_contact_number').html(''); $('#err_contact_number').hide();
      $('#err_address').html(''); $('#err_address').hide();
      $('#err_zipcode').html(''); $('#err_zipcode').hide();
      $('#err_city').html(''); $('#err_city').hide();
      $('#err_country').html(''); $('#err_country').hide();

      var flag=1;

      if(first_name.trim()=='')
      {
        $('#err_first_name').html('Please enter first name.');
        $('#err_first_name').show();
        flag=0;
      }
      else if(!name_filter.test(first_name))
      {
        $('#err_first_name').html('Please enter valid first name.');
        $('#err_first_name').show();
        flag=0;
      }
       else if(first_name.length<3)
      {
        $('#err_first_name').html('First name must contain minimum 3 characters.');
        $('#err_first_name').show();
        flag=0;
      }

      if(last_name.trim()=='')
      {
        $('#err_last_name').html('Please enter last name.');
        $('#err_last_name').show();
        flag=0;
      }
      else if(!name_filter.test(last_name))
      {
        $('#err_last_name').html('Please enter valid first name.');
        $('#err_last_name').show();
        flag=0;
      }
       else if(last_name.length<3)
      {
        $('#err_last_name').html('Last name must contain minimum 3 characters.');
        $('#err_last_name').show();
        flag=0;
      }
      if(contact_number.trim()=='')
      {
        $('#err_contact_number').html('Please enter contact number.');
        $('#err_contact_number').show();
        flag=0;
      }
      else if(!contact_filter.test(contact_number))
      {
        $('#err_contact_number').html('Plese Enter valid contact number');
        $('#err_contact_number').show();
        flag=0;
      }
      else if(contact_filter.length<7)
      {
        $('#err_contact_number').html('Plese Enter valid contact number');
        $('#err_contact_number').show();
        flag=0;
      }
      if(address.trim()=='')
      {
        $('#err_address').html('Please enter address.');
        $('#err_address').show();
        flag=0;
      }
      else if(address.length<5)
      {
        $('#err_address').html('Please enter valid address.');
        $('#err_address').show();
        flag=0;
      }
      if(zipcode.trim()=='')
      {
        $('#err_zipcode').html('Please enter postal code.');
        $('#err_zipcode').show();
        flag=0;
      }
      else if(zipcode.length<2)
      {
        $('#err_zipcode').html('Please enter valid postal code.');
        $('#err_zipcode').show();
        flag=0;
      }

      if(city.trim()=='')
      {
        $('#err_city').html('Please enter city.');
        $('#err_city').show();
        flag=0;
      }
      else if(city.length<2)
      {
        $('#err_city').html('Please enter valid city.');
        $('#err_city').show();
        flag=0;
      }

      if(country.trim()=='')
      {
        $('#err_country').html('Please enter country.');
        $('#err_country').show();
        flag=0;
      }
      else if(country.length<2)
      {
        $('#err_country').html('Please enter valid country.');
        $('#err_country').show();
        flag=0;
      }


      if(flag==1)
      {
        return true;
      }
      else
      {
        return false;
      }

  });*/

  // Buyer/Seller change password validation
  $('#btn_update_password').click(function()
  {
      var old_password       = $('#old_password').val();
      var new_password       = $('#new_password').val();
      var confirm_password   = $('#confirm_password').val();

      var password_filter = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/;

      $('#err_old_password').html(''); $('#err_old_password').hide();
      $('#err_new_password').html(''); $('#err_new_password').hide();
      $('#err_confirm_password').html(''); $('#err_confirm_password').hide();

      var flag=1;

      if(old_password.trim()=='')
      {
        $('#err_old_password').html('Please enter old password.');
        $('#err_old_password').show();
        flag=0;
      }

      if(new_password.trim()=='')
      {
        $('#err_new_password').html('Please enter new password.');
        $('#err_new_password').show();
        flag=0;
      }
      else if(old_password==new_password)
      {
        $('#err_new_password').html('old password and new password could not be same.');
        $('#err_new_password').show();
        flag=0;
      }
      else if(!password_filter.test(new_password))
      {
        $('#err_new_password').html('Min. length should be 6 containing letter, number and special character.');
        $('#err_new_password').show();
        flag=0;
      }

      if(confirm_password.trim()=='')
      {
        $('#err_confirm_password').html('Please re-type password.');
        $('#err_confirm_password').show();
        flag=0;
      }
      else if(new_password!=confirm_password)
      {
        $('#err_confirm_password').html('Confirm password not matched.');
        $('#err_confirm_password').show();
        flag=0;
      }

      if(flag==1)
      {
        return true;
      }
      else
      {
        return false;
      }

  });

});    
