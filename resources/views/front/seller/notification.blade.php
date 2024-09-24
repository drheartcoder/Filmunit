@extends('front.layout.master')    
@section('main_content')
    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="buyer-account-bg-main min-heightsone">
        <div class="container">
            <div class="row">
                <!-- BEGIN Sidebar -->
                @include('front.layout._sidebar')
                <!-- END Sidebar -->
            <form name="uploadFootagePhotosForm" id="uploadFootagePhotosForm" enctype="multipart/form-data" method="post" action="{{$module_url_path}}/update">
            {{csrf_field()}}
                <div class="col-sm-8 col-md-8 col-lg-9">
                    <!--    Flash message Blade  -->
                    @include('front.layout._operation_status')

                @if(isset($arr_data['data']) && count($arr_data['data'])>0)
                    <div class="light-grey-bg-block">
                        <h1 class="accou-heat"> Notification </h1>
                        @foreach($arr_data['data'] as $key=>$value)                           
                        <div class="form-main-block notification-main-block">
                            <div class="notification-block">
                                <div class="notification-icon">
                                    <img src="{{url('/')}}/images/notifica-1.jpg" alt="" />
                                </div>
                                <div class="notification-content">
                                    
                                    <div class="noti-head-content">
                                        <span class="short-content">{{isset($value['message']) & $value['message']!='' ? ucwords($value['message']):'NA'}}</span>
                                        <span class="details-content">{{isset($value['message']) & $value['message']!='' ? ucwords($value['message']):'NA'}}</span>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $(".notification-block").click(function(){
                                           $(this).toggleClass("active");
                                            $(this).siblings().removeClass("active");
                                        });
                                    });
                                </script>
                                <button type="button" class="close-noti">
                                    <img src="{{url('/')}}/images/noti-close-icon.png" alt="" class="gray-close-icon" />
                                    <img src="{{url('/')}}/images/noti-close-icon-active.png" alt="" class="active-close-icon" />
                                </button>
                                <div class="clr"></div>
                            </div>
                        <div class="clr"></div> 
                    </div>
                    @endforeach
                </div>
                @else
                    <div class="order-list-text"></div>
                    <div align="center" style="color: #FFF">No Records Found.</div>
                @endif
                     <!-- Paination Links -->
                     @include('front.templates.pagination_view')
            </form>    
            </div>
        </div>
    </div>
    <div class="clr"></div>
    <!--Buyer Account Section End Here-->
    
<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<!--    Cloning JS -->

<!--    Select2 JS and CSS -->
<link href="{{url('/')}}/css/front/jquery.tagsinput.css" rel="stylesheet" />
<script src="{{url('/')}}/js/front/jquery.tagsinput.js"></script>  

<script type="text/javascript">

    /*------------------ Tags Input --------------------*/
    $('#keywords').tagsInput({'delimiter': [',']});

    /*------------------ Clone fields --------------------*/

    if($('.add_options').length<=1)
    {
         $('.addOption').removeClass( "plus-btn-move" );
         $('.removeOption').hide();
    }  


    $('.addOption').click(function(){
        $('.addOption').addClass( "plus-btn-move" );
        if($('.add_options').length==1)
        {
             $('.removeOption').show();
        }

        if($('.add_options').length<5)
        {
            $('.add_options').last().clone().insertAfter($('.add_options').last());
            $('.add_options').last().find('input').val('');
            $('.add_options').last().find('select').val('');
            $('.add_options').last().find('.help-block').html('');
            $('.add_options').last().find('.old_imagename').hide();
            $('.add_options').last().find('.old_footagename').hide();
        }
        else
        {
            swal("Maximum 5 options are allowed!");
            return false;
        }
    })

    /*------------------ Remove cloned values--------------------*/

    $(document).on('click','.removeOption',function(){
        
        if($('.add_options').length>1)
        {
            var thiss = $(this).closest('.add_options');
            
            swal({
              title: "Are you sure?",
              text: "Do you want to delete this option ?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes",
              cancelButtonText: "No",
              closeOnConfirm: true,
              closeOnCancel: true
            },
            function(isConfirm)
            {
              if(isConfirm==true)
              {
                if($('.add_options').length<=2)
                {
                     $('.addOption').removeClass( "plus-btn-move" );
                     $('.removeOption').hide();
                }                
                 thiss.remove();
              }
              else
              {
                return false;
              }
            });
        }
        else
        {
            swal("Minimum 1 option is required!");
            return false;
        }
    })
   
    /*------------------ Check whether the selected type is footage / photo --------------------*/

/*    function checkMediaType() 
    {
       var media_type = $('#type').val();
       if(media_type=='footage')
       {
            $('.error').hide();
            $('#footage_clone_fields').show();
            $('#footage_options').show();
            $('#photos_clone_fields').hide();
            
            while($('.add_options').length>1)
            {
             $('.add_options').last('.add_options').remove();
            }
       }
       else
       {

            $('.error').hide();
            $('#footage_clone_fields').hide();
            $('#footage_options').hide();
            $('#photos_clone_fields').show();
            
            while($('.add_options').length>1)
            {
                $('.add_options').last('.add_options').remove();
            }
       }
    }
*/
    /*------------------ Cloned Fields and general field validations--------------------*/

    $(document).on('click','#btn_upload_media',function()
    {
        if($('#type').val()=='footage')
        {
            var msg= "Footage";
        }
        else if($('#type').val()=='photo')
        {
            var msg = "Photo";
        }
        else
        {
            var msg = "Photo/Footage";
        }
        
          var type            = $('#type').val();
          var title           = $('#title').val();
          var keywords        = $('#keywords').val();
          var description     = $('#description').val();

          var filter_price    = /^\d{0,8}(\.\d{1,10})?$/;

          var flag=1;

          $('#err_type').html(''); $('#err_type').hide();
          $('#err_title').html(''); $('#err_title').hide();
          $('#err_keywords').html(''); $('#err_keywords').hide();
          $('#err_description').html(''); $('#err_description').hide();
          $('#err_ratio').html(''); $('#err_ratio').hide();
          $('#err_alpha_channel').html(''); $('#err_alpha_channel').hide();
          $('#err_alpha_matte').html(''); $('#err_alpha_matte').hide();
          $('#err_media_release').html(''); $('#err_media_release').hide();
          $('#err_looping').html(''); $('#err_looping').hide();
          $('#err_model_release').html(''); $('#err_model_release').hide();
          $('#err_liscense_type').html(''); $('#err_liscense_type').hide();
          $('#err_fx').html(''); $('#err_fx').hide();
          $('.help-block').html(''); $('.help-block').hide();

        if(type.trim()=='')
        {
            $('#err_type').html('Please select '+msg+' type.');
            $('#err_type').show();
            flag=0;  
        }

        if(title.trim()=='')
        {
            $('#err_title').html('Please enter '+msg+' name.');
            $('#err_title').show();
            flag=0;  
        }

        else if(title.length>255)
        {
            $('#err_title').html(msg+' name must contain maximum 255 characters.');
            $('#err_title').show();
            flag=0;  
        }

        else if(title.length<3)
        {
            $('#err_title').html(msg+' name must contain minimum 3 characters.');
            $('#err_title').show();
            flag=0;  
        }

        if(keywords.trim()=='')
        {
            $('#err_keywords').html('Please enter '+msg+' keywords.');
            $('#err_keywords').show();
            flag=0;  
        }

        else if(keywords.length>255)
        {
            $('#err_keywords').html(msg+' keywords must contain maximum 255 characters.');
            $('#err_keywords').show();
            flag=0;  
        }

        else if(keywords.length<3)
        {
            $('#err_keywords').html(msg+' keywords must contain minimum 3 characters.');
            $('#err_keywords').show();
            flag=0;  
        }

        if(description.trim()=='')
        {
            $('#err_description').html('Please enter '+msg+' description.');
            $('#err_description').show();
            flag=0;  
        }

        else if(description.length>1000)
        {
            $('#err_description').html(msg+' description must contain maximum 1000 characters.');
            $('#err_description').show();
            flag=0;  
        }

        else if(description.length<10)
        {
            $('#err_description').html(msg+' description must contain minimum 10 characters.');
            $('#err_description').show();
            flag=0;  
        }
        
        /*-------------------- Clone validation for general/photo -------------------------*/

        $('.add_options').each(function()
        {
            if($(this).find('.option_price').val()=='')
            {
                $(this).find('.option_price').next('span').show();         
                $(this).find('.option_price').next('span').html('Plese enter price');         
                flag=0;     
            }
            else if(!filter_price.test($(this).find('.option_price').val()))
            {
                $(this).find('.option_price').next('span').show();         
                $(this).find('.option_price').next('span').html('Plese enter valid price');         
                flag=0;     
            }

            if(type=='photo')
            {
                if($(this).find('.option_photo_format').val()=='')
                {
                    $(this).find('.option_photo_format').next('span').show();         
                    $(this).find('.option_photo_format').next('span').html('Plese select format');         
                    flag=0;     
                }

                if($(this).find('.option_orientation').val()=='')
                {
                    $(this).find('.option_orientation').next('span').show();         
                    $(this).find('.option_orientation').next('span').html('Plese select orientation');         
                    flag=0;     
                }

                if($(this).find('.option_old_photo').val()=='' && $(this).find('.option_arr_photo').val()=='')
                {
                    $(this).find('.option_old_photo').closest('.parent-image').find('.help-block').show();         
                    $(this).find('.option_old_photo').closest('.parent-image').find('.help-block').html('Please upload photo');         
                    flag=0;     
                }
            }

            else if(type=='footage')
            {
                if($(this).find('.option_resolution').val()=='')
                {
                    $(this).find('.option_resolution').next('span').show();         
                    $(this).find('.option_resolution').next('span').html('Plese enter resolution');         
                    flag=0;     
                }

                if($(this).find('.option_footage_format').val()=='')
                {
                    $(this).find('.option_footage_format').next('span').show();         
                    $(this).find('.option_footage_format').next('span').html('Plese select format');         
                    flag=0;     
                }

                if($(this).find('.option_fps').val()=='')
                {
                    $(this).find('.option_fps').next('span').show();         
                    $(this).find('.option_fps').next('span').html('Plese select fps');         
                    flag=0;     
                }

                if($(this).find('.option_arr_footage').val()=='' && $(this).find('.option_old_footage').val()=='')
                {
                    $(this).find('.option_arr_footage').closest('.parent-image').find('.help-block').show();         
                    $(this).find('.option_arr_footage').closest('.parent-image').find('.help-block').html('Please upload footage');         
                    flag=0;     
                }
            }
            else
            {
                if($(this).find('.option_photo_format').val()=='')
                {
                    $(this).find('.option_photo_format').next('span').show();         
                    $(this).find('.option_photo_format').next('span').html('Plese select format');         
                    flag=0;     
                }

                if($(this).find('.option_orientation').val()=='')
                {
                    $(this).find('.option_orientation').next('span').show();         
                    $(this).find('.option_orientation').next('span').html('Plese select orientation');         
                    flag=0;     
                }

                if($(this).find('.option_arr_photo').val()=='')
                {
                    $(this).find('.option_arr_photo').closest('.parent-image').find('.help-block').show();         
                    $(this).find('.option_arr_photo').closest('.parent-image').find('.help-block').html('Please upload photo');         
                    flag=0;     
                }
            }            
        });

        /*-------------------- Validation if selected media type is footage -------------------------*/

        if(type=='footage')
        {
            var ratio              = $('#ratio').val();
            var duration           = $('#duration').val();

           if(ratio.trim()=='')
           {
                $('#err_ratio').html('Please select ratio.');
                $('#err_ratio').show();
                flag=0;  
           }

          if(duration.trim()=='')
          {
            $('#err_duration').html('Please select duration.');
            $('#err_duration').show();
            flag=0;  
          }

          if($('input[name=alpha_channel]:checked').length<=0)
          {
            $('#err_alpha_channel').html('Please select alpha channel.');
            $('#err_alpha_channel').show();
            flag=0;
          }

          if($('input[name=alpha_matte]:checked').length<=0)
          {
            $('#err_alpha_matte').html('Please select alpha matte.');
            $('#err_alpha_matte').show();
            flag=0;
          }
          
          if($('input[name=media_release]:checked').length<=0)
          {
            $('#err_media_release').html('Please select media release.');
            $('#err_media_release').show();
            flag=0;
          }        

          if($('input[name=looping]:checked').length<=0)
          {
            $('#err_looping').html('Please select looping.');
            $('#err_looping').show();
            flag=0;
          }

          if($('input[name=model_release]:checked').length<=0)
          {
            $('#err_model_release').html('Please select model release.');
            $('#err_model_release').show();
            flag=0;
          }

          if($('input[name=liscense_type]:checked').length<=0)
          {
            $('#err_liscense_type').html('Please select liscense type.');
            $('#err_liscense_type').show();
            flag=0;
          }

          if($('input[name=fx]:checked').length<=0)
          {
            $('#err_fx').html('Please select fx.');
            $('#err_fx').show();
            flag=0;
          }
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

   /*-------------------- Photo Validation -------------------------*/
    
/*    $('.add_options').each(function()
    {
        $($(this).find('.option_arr_photo')).change(function() {

            var file = this.files;
            var status = ImageValidation (file);
            
            if(status==false)
            {
                $(this).find('.option_arr_photo').val('');
            }
        });        
    });*/

    function ImageValidation (files,format) 
    {
        //alert(format);
     if (typeof files !== "undefined") 
      {
        for (var i=0, l=files.length; i<l; i++) 
        {
          var blnValid = false;
          var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
          var ext = ext.toLowerCase()
          if( ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "gif")
          {
              blnValid = true;
          }
          
          if(blnValid ==false) 
          {
              swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpeg , jpg , png, gif");
              $('.add_options').find('.option_arr_photo').val('');
              return false;
          }

            return true;
          }
      }
      else
      {
        swal("No support for the File API in this web browser" ,"error");
      } 
    }

    function FootageValidation (files,format) 
    {
        //alert(format);
     if (typeof files !== "undefined") 
      {
        for (var i=0, l=files.length; i<l; i++) 
        {
          var blnValid = false;
          var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
          var ext = ext.toLowerCase()

          if(ext == "avi" || ext == "flv" || ext == "wmv" || ext == "mov" || ext == "mp4")
          {
              blnValid = true;
          }
          
          if(blnValid ==false) 
          {
              swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: avi , flv , wmv , mov, mp4");
              $('.add_options').find('.option_arr_footage').val('');
              return false;
          }

            return true;
          }
      }
      else
      {
        swal("No support for the File API in this web browser" ,"error");
      } 
    }
</script>

@endsection

