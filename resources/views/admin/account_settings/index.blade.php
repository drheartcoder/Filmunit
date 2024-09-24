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
         <i class="fa fa-home">
         </i>
         <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard
         </a>
      </li>
      <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa {{$module_icon}}">
      </i>
      </span> 
      <li class="active">  {{ isset($page_title)?$page_title:"" }}
      </li>
   </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
<div class="col-md-12">
   <div class="box {{ $theme_color }}">
      <div class="box-title">
         <h3>
            <i class="fa {{$module_icon}}">
            </i>{{ isset($page_title)?$page_title:"" }} 
         </h3>
         <div class="box-tool">
         </div>
      </div>
      <div class="box-content">
         @include('admin.layout._operation_status')
         {!! Form::open([ 'url' => $module_url_path.'/update_profile',
         'method'=>'POST',   
         'class'=>'form-horizontal', 
         'id'=>'validation-form' ,
         'enctype'=>'multipart/form-data'
         ]) !!}
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Profile Image  <i class="red">*</i> </label>
            <div class="col-sm-9 col-lg-10 controls">
               <div class="fileupload fileupload-new" data-provides="fileupload">
                  <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                     @if(isset($arr_data['profile_image']) && !empty($arr_data['profile_image']))
                     <img src="{{$profile_image_public_img_path.$arr_data['profile_image'] }}">
                     @else
                     <img src="{{url('/').'/uploads/default.png' }}">
                     @endif
                  </div>
                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                  <div>
                     <span class="btn btn-default btn-file" style="height:32px;">
                     <span class="fileupload-new">Select Image</span>
                     <span class="fileupload-exists">Change</span>
                     <input type="file"  data-validation-allowing="jpg, png, gif" class="file-input news-image validate-image" name="image" id="image"  /><br>
                     <input type="hidden" class="file-input " name="oldimage" id="oldimage"  
                        value="{{ $arr_data['profile_image'] }}"/>
                     </span>
                     <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                  </div>
                  <i class="red"> {!! image_validate_note(250,250) !!} </i>
                  <span for="image" id="err-image" class="help-block">{{ $errors->first(' image') }}</span>
               </div>
               <span class="label label-important">NOTE!</span>
               <span>Attached image img-thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only</span>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6 col-lg-5 control-label help-block-red" style="color:#b94a48;" id="err_logo"></div>
            <br/>
            <div class="col-sm-6 col-lg-5 control-label help-block-green" style="color:#468847;" id="success_logo"></div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">First Name
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               {!! Form::text('first_name',$arr_data['first_name'],['class'=>'form-control','data-rule-required'=>'true','data-rule-lettersonly'=>'true','data-rule-maxlength'=>'255', 'placeholder'=>'First Name']) !!}
               <span class='help-block'>{{ $errors->first('first_name') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Last Name
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               {!! Form::text('last_name',$arr_data['last_name'],['class'=>'form-control','data-rule-required'=>'true','data-rule-lettersonly'=>'true','data-rule-maxlength'=>'255', 'placeholder'=>'Last Name']) !!}
               <span class='help-block'>{{ $errors->first('last_name') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Email
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               {!! Form::text('email',$arr_data['email'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-email'=>'true','data-rule-maxlength'=>'255','placeholder'=>'Email']) !!}
               <span class='help-block'>{{ $errors->first('email') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Contact Number
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               {!! Form::text('contact_number',$arr_data['contact_number'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-number'=>'true','data-rule-maxlength'=>'16','data-rule-minlength'=>'7','placeholder'=>'Contact Number']) !!}
               <span class='help-block'>{{ $errors->first('contact_number') }}
               </span>
            </div>
         </div>

         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Fax
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               {!! Form::text('fax',$arr_data['fax'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-number'=>'true','data-rule-maxlength'=>'16','data-rule-minlength'=>'7','placeholder'=>'Contact Number']) !!}
               <span class='help-block'>{{ $errors->first('fax') }}
               </span>
            </div>
         </div>

         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Address
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               {!! Form::textarea('address',$arr_data['address'],['class'=>'form-control','rows'=>'2', 'data-rule-required'=>'true','data-rule-minlength'=>'3','data-rule-maxlength'=>'256','placeholder'=>'Address']) !!}
               <span class='help-block'>{{ $errors->first('address') }}
               </span>
            </div>
         </div>

         <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
               {!! Form::submit('Update',['class'=>'btn btn btn-primary','value'=>'true'])!!}
            </div>
         </div>
         {!! Form::close() !!}
      </div>
   </div>
</div>
</div>

    <div class="row">
        <div class="col-md-6">
            <div class="box {{ $theme_color }}">
                <div class=" box-title">
                    <h3><i class="fa fa-key"></i> Change Password</h3>
                    <div class="box-tool">
                    </div>
                </div>
                <div class="box-content">

                    {!! Form::open([ 'url' => $admin_panel_slug.'/update_password',
                                 'method'=>'POST',
                                 'id'=>'validation-form1',
                                 'class'=>'form-horizontal' 
                                ]) !!} 
                                    
                            {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-4 col-md-4 control-label">Current password</label>
                            <div class="col-sm-8 col-md-7 controls">

                                {!! Form::password('current_password',['class'=>'form-control',
                                        'data-rule-required'=>'true',
                                        'id'=>'current_password',
                                        'placeholder'=>'Current Password']) !!}
                                
                                <span class='help-block'>{{ $errors->first('current_password') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-md-4 control-label">New password</label>
                            <div class="col-sm-8 col-md-7 controls">
                                
                                {!! Form::password('new_password',['class'=>'form-control',
                                        'data-rule-required'=>'true',
                                        'data-rule-minlength'=>'6',
                                        'id'=>'new_password',
                                        'placeholder'=>'New Password']) !!}

                                <span class='help-block'>{{ $errors->first('new_password') }}</span>
                                <span style="color: red" id="err_new_password"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-md-4 control-label">Re-type New password</label>
                            <div class="col-sm-8 col-md-7 controls">
                                

                                {!! Form::password('new_password_confirmation',['class'=>'form-control',
                                        'data-rule-required'=>'true',
                                        'data-rule-equalto'=>'#new_password',
                                        'id'=>'new_password_confirmation',
                                        'placeholder'=>'Re-type New password']) !!}

                                <span class='help-block'>{{ $errors->first('new_password_confirmation') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4 col-md-7 col-md-offset-5">

                                {!! Form::Submit('Submit',['class'=>'btn btn-primary','id'=>'submit_password']) !!}        
                                <a class="btn" href="{{url('/')}}/admin/dashboard">Cancel</a>
                            </div>
                       </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box {{ $theme_color }}">
                <div class=" box-title">
                    <h3><i class="fa fa-wrench"></i> Site Settings</h3>
                    <div class="box-tool">
                    </div>
                </div>
                <div class="box-content">

                          {!! Form::open([ 'url' => $module_url_path.'/update_site_setting',
                                 'method'=>'POST',   
                                 'class'=>'form-horizontal', 
                                 'id'=>'validation-form' ,
                                 'enctype'=>'multipart/form-data'
                                ]) !!}
                                    
                            {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-sm-4 col-md-4 control-label">Site Status<i class="red">*</i></label>
                            <div class="col-sm-8 col-md-7 controls">
                                <select class="form-control" name="site_status" data-rule-required="true">
                                   <option value="0" {{$arr_site_setting_data['site_status']==0?'selected':'' }}>Offline</option>     
                                   <option value="1" {{$arr_site_setting_data['site_status']==1?'selected':'' }}>Online</option>     
                                </select>
                            </div>
                        </div>  
                        
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4 col-md-7 col-md-offset-6">

                                {!! Form::Submit('Submit',['class'=>'btn btn-primary']) !!}        
                            </div>
                       </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-12">
                <div class="box {{ $theme_color }}">
                    <div class=" box-title">
                        <h3><i class="fa fa-envelope-o"></i> Site Emails</h3>
                        <div class="box-tool">
                        </div>
                    </div>
                    <div class="box-content">

                    {!! Form::open([ 'url' => $module_url_path.'/update_site_emails',
                         'method'=>'POST',   
                         'class'=>'form-horizontal', 
                         'id'=>'validation-form2' ,
                         'enctype'=>'multipart/form-data'
                        ]) !!}
                            
                    {{ csrf_field() }}

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Info
                        <i class="red">*
                        </i>
                        </label>
                        <div class="col-sm-9 col-lg-8 controls">
                           {!! Form::text('info_email_address',$arr_site_setting_data['info_email_address'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-email'=>'true','data-rule-maxlength'=>'255','placeholder'=>'Info Email']) !!}
                           <span class='help-block'>{{ $errors->first('info_email_address') }}
                           </span>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Sales
                        <i class="red">*
                        </i>
                        </label>
                        <div class="col-sm-9 col-lg-8 controls">
                           {!! Form::text('sales_email_address',$arr_site_setting_data['sales_email_address'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-email'=>'true','data-rule-maxlength'=>'255','placeholder'=>'Sales Email']) !!}
                           <span class='help-block'>{{ $errors->first('sales_email_address') }}
                           </span>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Billing
                        <i class="red">*
                        </i>
                        </label>
                        <div class="col-sm-9 col-lg-8 controls">
                           {!! Form::text('billing_email_address',$arr_site_setting_data['billing_email_address'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-email'=>'true','data-rule-maxlength'=>'255','placeholder'=>'Billing Email']) !!}
                           <span class='help-block'>{{ $errors->first('billing_email_address') }}
                           </span>
                        </div>
                     </div>  
                      
                      <div class="form-group">
                          <div class="col-sm-8 col-sm-offset-4 col-md-7 col-md-offset-6">

                              {!! Form::Submit('Submit',['class'=>'btn btn-primary']) !!}        
                          </div>
                     </div>

                        {!! Form::close() !!}
                    </div>
                </div>
          </div>

          <div class="col-md-12">
                <div class="box {{ $theme_color }}">
                    <div class=" box-title">
                        <h3><i class="fa fa-phone-square"></i> Site Contact Details</h3>
                        <div class="box-tool">
                        </div>
                    </div>
                    <div class="box-content">

                    {!! Form::open([ 'url' => $module_url_path.'/update_site_contact_details',
                         'method'=>'POST',   
                         'class'=>'form-horizontal', 
                         'id'=>'validation-form3' ,
                         'enctype'=>'multipart/form-data'
                        ]) !!}
                            
                    {{ csrf_field() }}

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Contact Number
                        <i class="red">*
                        </i>
                        </label>
                        <div class="col-sm-9 col-lg-8 controls">
                           {!! Form::text('site_contact_number',$arr_site_setting_data['site_contact_number'],['class'=>'form-control', 'data-rule-required'=>'true','data-rule-number'=>'true','data-rule-maxlength'=>'16','data-rule-minlength'=>'7','placeholder'=>'Contact Number']) !!}
                           <span class='help-block'>{{ $errors->first('contact_number') }}
                           </span>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Address
                        <i class="red">*
                        </i>
                        </label>
                        <div class="col-sm-9 col-lg-8 controls">
                           {!! Form::textarea('site_address',$arr_site_setting_data['site_address'],['class'=>'form-control','rows'=>'2', 'data-rule-required'=>'true','data-rule-minlength'=>'3','data-rule-maxlength'=>'256','placeholder'=>'Address']) !!}
                           <span class='help-block'>{{ $errors->first('address') }}
                           </span>
                        </div>
                     </div> 
                      
                      <div class="form-group">
                          <div class="col-sm-8 col-sm-offset-4 col-md-7 col-md-offset-6">

                              {!! Form::Submit('Submit',['class'=>'btn btn-primary']) !!}        
                          </div>
                     </div>

                        {!! Form::close() !!}
                    </div>
                </div>
          </div>


{{--  <script type="text/javascript" src="{{ url('')}}/front-assets/js/custom_jquery.validate.js"></script> --}}
<script type="text/javascript">

   $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 250,250);
    });
   
  $(document).on("click",'#submit_password',function(){
      var current_password      = $('#current_password').val();
      var new_password          = $('#new_password').val();

      $('#err_new_password').html(''); $('#err_new_password').hide();

      if(current_password==new_password && (current_password!='' && new_password!=''))
      {
        $('#err_new_password').html('Current password and New password should not be same.');
        $('#err_new_password').show();
        return false;
      }
  });
   
   
</script>
<!-- END Main Content --> 
@endsection

