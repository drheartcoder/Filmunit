    @extends('admin.layout.master')                


    @section('main_content')

    
    <!-- BEGIN Page Title -->
    <div class="page-title">
        <div>

        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
            </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
            </span>
            <li>
                <i class="fa fa-users"></i>
                <a href="{{ $module_url_path }}">{{ $module_title or ''}}</a>
            </li>   
            <span class="divider">
                <i class="fa fa-angle-right"></i>
            </span>
            <li class="active"><i class="fa fa-edit"></i> {{$page_title}}</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->

<?php
    $profile_image = isset($arr_data['profile_image']) && $arr_data['profile_image']!='' ?$arr_data['profile_image']:"default.png";
?>

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">
          <div class="box {{ $theme_color }}">
            <div class="box-title">
              <h3>
                <i class="fa fa-edit"></i>
                @if($user_role == 'photographer') Edit Photographer @else {{ isset($page_title)?$page_title:"" }} @endif 
              </h3>
              <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
              </div>
            </div>
            <div class="box-content">

          @include('admin.layout._operation_status')  
           {!! Form::open([ 'url' => $module_form_path.'/update',
                                 'method'=>'POST',
                                 'enctype' =>'multipart/form-data',   
                                 'class'=>'form-horizontal', 
                                 'id'=>'validation-form' 
                                ]) !!} 

           {{ csrf_field() }}
           @if(isset($arr_data) && count($arr_data) > 0)
           {!! Form::hidden('user_id',isset($arr_data['id']) ? $arr_data['id']: "")!!}

            <div class="form-group" style="margin-top: 25px;">
                  <label class="col-sm-3 col-lg-2 control-label">First Name<i style="color: red;">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      
                      <input type="text" class="form-control" name="first_name"  data-rule-required='true' value="{{$arr_data['first_name']}}" maxlength="20" placeholder="Enter First Name">
                      
                      <span class="help-block">{{ $errors->first('first_name') }}</span>
                  </div>
            </div>

            <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">Last Name<i style="color: red;">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      
                      <input type="text" class="form-control" name="last_name"  data-rule-required='true' value="{{$arr_data['last_name']}}" maxlength="20" placeholder="Enter Last Name">

                      <span class="help-block">{{ $errors->first('last_name') }}</span>
                  </div>
            </div>

            <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">Email<i style="color: red;">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      
                    <input type="email" class="form-control" name="email"  data-rule-required='true' value="{{$arr_data['email']}}" maxlength="255" placeholder="Enter email" readonly="true">

                      <span class="help-block">{{ $errors->first('email') }}</span>
                  </div>
            </div>  

            <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">Contact Number<i style="color: red;">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      
                   <input type="text" class="form-control" name="contact_number"  data-rule-required='true' value="{{$arr_data['contact_number']}}" maxlength="15" minlength="10" placeholder="Enter Contact Number" data-rule-digits='true'>

                      <span class="help-block">{{ $errors->first('contact_number') }}</span>
                  </div>
            </div>


            <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">Address<i style="color: red;">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >

                    <input type="text" class="form-control" name="address"  data-rule-required='true' value="{{$arr_data['address']}}" placeholder="Enter address" id='autocomplete'>

                      <span class="help-block">{{ $errors->first('address') }}</span>
                  </div>
            </div>

{{--             <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">State</label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      

                    <input type="text" class="form-control" name="state" value="{{$arr_data['state']}}" placeholder="Enter state" id='administrative_area_level_1' readonly="true">

                      <span class="help-block">{{ $errors->first('state') }}</span>
                  </div>
            </div> --}}

            <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">City</label>
                  <div class="col-sm-9 col-lg-4 controls" >
                     
                    <input type="text" class="form-control" name="city" value="{{$arr_data['city']}}" placeholder="Enter city" id='locality' readonly="true">

                      <span class="help-block">{{ $errors->first('city') }}</span>
                  </div>
            </div>

            <div class="form-group" style="">
                <label class="col-sm-3 col-lg-2 control-label">Country</label>
                <div class="col-sm-9 col-lg-4 controls" >

                    <input type="text" class="form-control" name="country"  data-rule-required='true' value="{{$arr_data['country']}}" placeholder="Enter country" id='country' readonly="true">

                    <span class="help-block">{{ $errors->first('role') }}</span>
                </div>
            </div>

            <div class="form-group" style="">
                  <label class="col-sm-3 col-lg-2 control-label">Zip Code</label>
                  <div class="col-sm-9 col-lg-4 controls" >

                    <input type="text" class="form-control" name="zipcode"  data-rule-required='true' value="{{$arr_data['zipcode']}}" placeholder="Enter zipcode" id='postal_code' minlength="4" maxlength="12">

                      <span class="help-block">{{ $errors->first('zipcode') }}</span>
                  </div>
            </div>
            
            @if($user_role=='seller')
            <div class="form-group">
              <label class="col-sm-3 col-lg-2 control-label">Profile Image</label>
              <div class="col-sm-9 col-lg-10 controls">
                 <div class="fileupload fileupload-new" data-provides="fileupload">
                   <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                      <img src={{ $user_profile_public_img_path.$profile_image}} alt="" />  
                  </div>
                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                         <img src={{ $user_profile_public_img_path.$profile_image}} alt="" />  
                    </div>
                    <div>
                       <span class="btn btn-default btn-file"><span class="fileupload-new" >Select Image</span> 
                       <span class="fileupload-exists">Change</span>
                       
                       {!! Form::file('profile_image',['id'=>'profile_image','class'=>'file-input','data-rule-required'=>'']) !!}

                       </span> 
                       <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                       <span>
                       </span> 
                    </div>
                 </div>
                  <i style="color:#ff6666;">Please use 140 x 140 pixel image for best result ,<br/> allowed only JPG, JPEG and PNG image</i>
                  <span class='help-block'><b>{{ $errors->first('profile_image') }}</b></span>  
              </div>
            </div>
            @endif

            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
               
                {!! Form::submit('Update',['class'=>'btn btn btn-primary','value'=>'true'])!!}
                &nbsp;
                <a class="btn btn-primary" href="{{ $module_url_path }}">Back</a>
              </div>
            </div>
            {!! Form::close() !!}
            @else 
              <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                  <h3><strong>No Record found..</strong></h3>     
                </div>
              </div>
            @endif
          
            

      </div>
    </div>
  </div>
  
  <!-- END Main Content -->


<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCccvQtzVx4aAt05YnfzJDSWEzPiVnNVsY&libraries=places&callback=initAutocomplete"
        async defer>
</script>

<script>  

  var glob_autocomplete;
  var glob_component_form = 
  {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'long_name',
    postal_code: 'short_name'
  };

  var glob_options = {};
  glob_options.types = ['address'];

  function changeCountryRestriction(ref)
  {
    var country_code = $(ref).val();
    destroyPlaceChangeListener(autocomplete);
    // load states function
    // loadStates(country_code);  

    glob_options.componentRestrictions = {country: country_code}; 

    initAutocomplete(country_code);

    glob_autocomplete = false;
    glob_autocomplete = initGoogleAutoComponent($('#autocomplete')[0],glob_options,glob_autocomplete);
  }


  function initAutocomplete(country_code) 
  {
    glob_options.componentRestrictions = {country: country_code}; 

    glob_autocomplete = false;
    glob_autocomplete = initGoogleAutoComponent($('#autocomplete')[0],glob_options,glob_autocomplete);
  }


  function initGoogleAutoComponent(elem,options,autocomplete_ref)
  {
    autocomplete_ref = new google.maps.places.Autocomplete(elem,options);
    autocomplete_ref = createPlaceChangeListener(autocomplete_ref,fillInAddress);

    return autocomplete_ref;
  }
  

  function createPlaceChangeListener(autocomplete_ref,fillInAddress)
  {
    autocomplete_ref.addListener('place_changed', fillInAddress);
    return autocomplete_ref;
  }

  function destroyPlaceChangeListener(autocomplete_ref)
  {
    google.maps.event.clearInstanceListeners(autocomplete_ref);
  }

  function fillInAddress() 
  {
    // Get the place details from the autocomplete object.
    var place = glob_autocomplete.getPlace();
    console.log(place)  ;
    for (var component in glob_component_form) 
    {
        $("#"+component).val("");
        $("#"+component).attr('disabled',false);
    }
    
    if(place.address_components.length > 0 )
    {
      $.each(place.address_components,function(index,elem)
      {
          var addressType = elem.types[0];
          if(glob_component_form[addressType])
          {
            var val = elem[glob_component_form[addressType]];
            $("#"+addressType).val(val) ;  
          }
      });  
    }
  }

</script>     
  

@stop                    
