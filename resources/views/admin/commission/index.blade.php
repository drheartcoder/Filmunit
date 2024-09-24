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
      <i class="fa fa-money">
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
        {!! Form::open([ 'url' => $module_url_path.'/update',
        'method'=>'POST',   
        'class'=>'form-horizontal', 
        'id'=>'validation-form' ,
        'enctype'=>'multipart/form-data'

        ]) !!}

        <div class="form-group">
          <label class="col-sm-3 col-lg-2 control-label">Commission (%)
            <i class="red">*
            </i>
          </label>
          <div class="col-sm-9 col-lg-4 controls">
          <input type="text" class="form-control"  name="commission" value="{{$arr_data['commission']}}" data-rule-number="true" data-rule-required="true" maxlength="5" placeholder="Commission in (%)" /> 
            {{-- {!! Form::text('commision',['class'=>'form-control','data-rule-required'=>'true','data-rule-lettersonly'=>'true','data-rule-maxlength'=>'255', 'placeholder'=>'commision']) !!} --}}
            <span class='help-block'>{{ $errors->first('commission') }}
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
  
 {{--  <script type="text/javascript" src="{{ url('')}}/front-assets/js/custom_jquery.validate.js"></script> --}}
  
  <script type="text/javascript">
    $("#validation-form").validate({
       /* rules: {
            first_name: {   required:true },
            last_name:  {   required:true },
            email:      {   required:true },
            phone:  {   required:true },
            fax:        {   required:true },
            address:    {   required:true },
           
        },
        messages: {
            first_name: {  required:"Enter a first name"   },
            last_name:  {  required:"Enter a last name"     },   
            email:      {  required:"Enter a email address" ,email:"Please enter the valid email address "}, 
            phone:  {  required:"Enter a mobile number" ,number:"Please enter the valid mobile number "},    
            fax      :  {  required:"Enter a fax number" },    
            address  :  {  required:"Enter a address" },    
                   },
        errorElement:"span",
        errorClass:"help-block",
        errorPlacement: function(error, element)
         {
                  if(element)
                  {
                    element.closest('.form-group').addClass('has-error');
                    error.insertAfter(element).wrap('');
                  }
                  else
                  {
                    element.closest('.form-group').removeClass('has-error');
                    return true;
                  }

          }*/
    });

  
    $(document).on("change", ".news-image", function()
    {            
        var file=this.files;
        traverseLogo(this.files);
    });   
    
    function traverseLogo (files) 
    {

      if (typeof files !== "undefined") 
      {
        for (var i=0, l=files.length; i<l; i++) 
        {
              var blnValid = false;
              var ext = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
              if(ext == "gif" || ext == "GIF" || ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "png" || ext == "PNG")
              {
                          blnValid = true;
              }
              
              if(blnValid ==false) 
              {
                  swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: gif , jpeg , jpg , png");
                  $(".fileupload-preview").html("");
                  $(".fileupload").attr('class',"fileupload fileupload-new");
                  $("#image").val();
                  return false;
              }
              else
              {              
                
                    var reader = new FileReader();
                    reader.readAsDataURL(files[0]);
                    reader.onload = function (e) 
                    {
                            var image = new Image();
                            image.src = e.target.result;
                               
                            /*image.onload = function () 
                            {
                                var height = this.height;
                                var width = this.width;
                                if (height < 287 || width < 370) 
                                {
                                    swal("Height and Width must be equal or greater than  370x287 .",'error');
                                    $(".fileupload-preview").html("");
                                    $(".fileupload").attr('class',"fileupload fileupload-new");
                                    $("#image").val();
                                    return false;


                               
                                }
                                else
                                {
                                   swal("Uploaded image has valid Height and Width.");
                                   return true;
                                }

                               
                            };*/
         
                    }
                  
              }                
         
          }
        
      }
      else
      {
        swal("No support for the File API in this web browser");
      } 
    }
 
  </script>

  <!-- END Main Content --> 
  @endsection
