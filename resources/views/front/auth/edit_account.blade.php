@extends('front.layout.master')    
@section('main_content')

<?php 

  if(isset($user) && $user!="")
  
  $user_id          = "";
  $path             = "";
  $role             = "";
  $profile_img      = "";

  $user = Sentinel::check();
  {
    $user_id        = $user['id'];
    $role           = $user['role'];
    $profile_img    = $user['profile_image'];
  }

?>
    <!--Edit Account Section Start Here-->
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
                        <h1 class="accou-heat">Edit Account </h1>
                        <form name="accountForm" id="accountForm" method="post" action="{{url('/')}}/update_account" enctype="multipart/form-data">
                        {{csrf_field()}}
                        @if($role=='seller')
                            <div class="row">
                               <div class="col-lg-12">
                                  <div class="profile-img-block">
                                        <div class="pro-img">
                                        @if($profile_img!='')
                                        <img src="{{$profile_image_public_img_path.$profile_img}}" class="img-responsive img-preview" alt="" />
                                        @else
                                        <img src="{{$profile_image_public_img_path}}user-male.png" class="img-responsive img-preview" alt="" />
                                        @endif
                                        </div>
                                        <div class="update-pic-btns">
                                            <button  class="up-btn">
                                                <span><i class="fa fa-cloud-upload"></i></span> Upload Photo</button>
                                            <input id="logo-id" name="profile_image" type="file" class="attachment_upload">
                                        </div>
                                            <input type="hidden" name="oldimage" type="text" value="{{$profile_img}}">
                                    </div>
                               </div>
                            </div>
                         @endif
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">First Name</label>
                                            <input class="cont-frm" type="text" name="first_name" placeholder="Enter first name" id="first_name" value="{{$arr_user['first_name']}}" maxlength="25" />
                                            <!-- <div class="error" id="err_first_name">{{ $errors->first('first_name') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l" type="text">Last Name</label>
                                            <input class="cont-frm" type="text" name="last_name" placeholder="Enter last name" id="last_name" value="{{$arr_user['last_name']}}" maxlength="25"/>
                                           <!--  <div class="error" id="err_last_name">{{ $errors->first('last_name') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">Email Address</label>
                                            <input class="cont-frm" type="email" name="email" id="email" readonly="" value="{{$arr_user['email']}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">Contact Number</label>
                                            <input class="cont-frm" type="text" name="contact_number" placeholder="Enter contact number" id="contact_number" value="{{$arr_user['contact_number']}}" maxlength="25"/>
                                           <!--  <div class="error" id="err_contact_number">{{ $errors->first('contact_number') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">Address</label>
                                            <input class="cont-frm" type="text" name="address" id="autocomplete" onFocus="geolocate()" value="{{$arr_user['address']}}" maxlength="1000"/>
                                           <!--  <div class="error" id="err_address">{{ $errors->first('address') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">Postal Code</label>
                                            <input class="cont-frm" type="text" name="zipcode" placeholder="Enter postal code" id="postal_code" value="{{$arr_user['zipcode']}}" maxlength="25"/>
                                            <!-- <div class="error" id="err_zipcode">{{ $errors->first('zipcode') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">City</label>
                                            <input class="cont-frm" type="text" name="city" placeholder="Enter city" id="locality" value="{{$arr_user['city']}}" maxlength="25"/>
                                            <!-- <div class="error" id="err_city">{{ $errors->first('city') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="account-text-fild">
                                        <div class="user-box1">
                                            <label class="label-l">Country</label>
                                            <input class="cont-frm" type="text" name="country" placeholder="Enter country" id="country" value="{{$arr_user['country']}}" maxlength="25"/>
                                            <!-- <div class="error" id="err_country">{{ $errors->first('country') }}</div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                        <a class="button-section right-side tp-btns right-new-btn-css"><button id="btn_edit_account" type="submit">Update Account</button></a>
                        <div class="clr"></div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--Edit Account Section End Here-->
<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/admin/sweetalert_msg.js"></script>
<!--    Cloning JS -->

<script type="text/javascript">
$(document).on('change','#logo-id',function(){
     var files = this.files;
     if (typeof files !== "undefined") 
      {
        for (var i=0, l=files.length; i<l; i++) 
        {
          var blnValid = false;
          var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
          var ext = ext.toLowerCase()
          if( ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "gif")
          {
                var reader = new FileReader();
                reader.readAsDataURL(files[0]);
                reader.onload = function (e) 
                {
                        var image = new Image();
                        image.src = e.target.result;
                           
                        image.onload = function () 
                        {
                            $('.img-preview').attr('src', e.target.result);
                            return true;
                        };
     
                }
              blnValid = true;
          }
              if(blnValid ==false) 
              {
                  swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpeg , jpg , png, gif");
                  $('#logo-id').val('');
                  return false;
              }
       }
      }
      else
      {
        swal("No support for the File API in this web browser" ,"error");
      } 
});
</script>

  <!-- Google Map Script -->
    <script>

      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        locality: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          /*document.getElementById(component).disabled = false;
          document.getElementById(component).value = '';*/
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZLX7A6hKtDM55CNsVSdU6ekXBi3bUFVM&libraries=places&callback=initAutocomplete"
        async defer></script>

<!--    validation JS  -->
<script type="text/javascript" src="{{url('/')}}/js/front/validations.js"></script>
 <!--new profile image upload demo script start-->
    <!--new profile image upload demo script end-->
@endsection
