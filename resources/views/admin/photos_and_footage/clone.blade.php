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
                <i class="fa fa-image"></i>
                <a href="{{ url($admin_panel_slug.'/photos_and_footage') }}">{{ $module_title or ''}}</a>
            </span> 
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                  <i class="fa fa-plus-square-o"></i>
            </span>
            <li class="active">{{ $page_title or ''}}</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->


    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box ">
            <div class="box-title">
              <h3>
                <i class="fa fa-plus-square-o"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
        <div class="box-content">

            @include('admin.layout._operation_status')  

      <div class="tabbable">
                @if(isset($arr_data) && count($arr_data)>0)
                {!! Form::open([ 'url' => $admin_panel_slug.'/photos_and_footage/store_clone',
                                 'method'=>'POST',
                                 'enctype' =>'multipart/form-data',   
                                 'class'=>'form-horizontal', 
                                ]) !!} 

                {{-- <ul  class="nav nav-tabs">
                    @include('admin.layout._multi_lang_tab')
                </ul> --}}
                <div  class="tab-content">
                        <div class="form-group">
                              <label class="col-sm-3 col-lg-2 control-label" for="state">Type <i class="red">*</i></label>
                              <div class="col-sm-6 col-lg-4 controls">
                                    <input type="text" class="form-control" type="text" placeholder="Enter Footage/Photo Name" name='type' id='type' maxlength="255" value="{{ $arr_data['type'] }}" readonly="">
                                    <div class="help-block" id="err_type">{{$errors->first('type')}}</div>
                              </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label" for="state"> Footage/Photo Title <i class="red">*</i></label>
                            <div class="col-sm-6 col-lg-4 controls">
                                  <input type="text" class="form-control" name="title" id="title" value="{{ $arr_data['title'] }}" maxlength="255" placeholder="Enter Footage/Photo Name">
                              <span class='help-block' id="err_title">{{ $errors->first('title') }}</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label" for="state"> Add Search Keywords <i class="red">*</i></label>
                            <div class="col-sm-6 col-lg-4 controls">
                                  <input type="text" class="form-control" name="keywords" id="keywords"  value="{{ $arr_data['keywords'] }}" maxlength="255" placeholder="Enter Search Keywords">
                              <span class='help-block' id="err_keywords">{{ $errors->first('keywords') }}</span>  
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label" for="state"> Description <i class="red">*</i></label>
                                <div class="col-sm-6 col-lg-4 controls">

                                   <textarea class='form-control' placeholder='Enter Description' name='description' id="description" maxlength="1000">{{ $arr_data['description'] }}</textarea>     
                                <span class='help-block' id="err_description">{{ $errors->first('description') }}</span>  
                                </div>
                        </div>

                        <!-- Footage Additional Fields ends Here -->
                        @if($arr_data['type']=='footage')
                        <div id="footage_options">
                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label" for="state">Ratio <i class="red">*</i></label>
                                  <div class="col-sm-6 col-lg-4 controls">
                                        <select type="text" name="ratio" id="ratio" class="form-control" value="">
                                             <option value="">Select Ratio</option>
                                              @if(isset($arr_ratio) && count($arr_ratio)>0)
                                              @foreach($arr_ratio as $key=>$value)
                                                  <option value="{{$value['id']}}" @if($arr_data['ratio']==$value['id'])selected @endif>{{$value['value']}}</option>
                                              @endforeach
                                              @endif
                                       </select>
                                    <span class='help-block' id="err_ratio">{{ $errors->first('ratio') }}</span>  
                                  </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label" for="state"> Duration (In Sec)<i class="red">*</i></label>
                                <div class="col-sm-6 col-lg-4 controls">
                                      <input type="text" class="form-control" name="duration" id="duration" value="{{$arr_data['duration']}}" maxlength="5" placeholder="Enter Duration">
                                  <span class='help-block' id="err_duration">{{ $errors->first('duration') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">Alpha Channel <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="alpha_channel" id="alpha_channel" value="yes" @if($arr_data['alpha_channel']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="alpha_channel" id="alpha_channel" value="no" @if($arr_data['alpha_channel']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_alpha_channel">{{ $errors->first('alpha_channel') }}</span>  
                                  </div>
                            </div>                                                                                                                         
                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">Alpha Matte <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="alpha_matte" value="yes" @if($arr_data['alpha_matte']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="alpha_matte" value="no" @if($arr_data['alpha_matte']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_alpha_matte">{{ $errors->first('alpha_matte') }}</span>  
                                  </div>
                            </div>  

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">Media Release <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="media_release" value="yes" @if($arr_data['media_release']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="media_release" value="no" @if($arr_data['media_release']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_media_release">{{ $errors->first('media_release') }}</span>  
                                  </div>
                            </div>                          

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">Looping <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="looping" value="yes" @if($arr_data['looping']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="looping" value="no" @if($arr_data['looping']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_looping">{{ $errors->first('looping') }}</span>  
                                  </div>
                            </div>  

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">Model Release <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="model_release" value="yes" @if($arr_data['model_release']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="model_release" value="no" @if($arr_data['model_release']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_model_release">{{ $errors->first('model_release') }}</span>  
                                  </div>
                            </div>

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">Liscense Type <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="liscense_type" value="yes" @if($arr_data['liscense_type']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="liscense_type" value="no" @if($arr_data['liscense_type']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_liscense_type">{{ $errors->first('liscense_type') }}</span>  
                                  </div>
                            </div>

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label">FX <i class="red">*</i></label>
                                  <div class="col-sm-9 col-lg-10 controls">
                                      <label class="radio-inline">
                                        <input type="radio" name="fx" value="yes" @if($arr_data['fx']=='yes')checked @endif> Yes
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" name="fx" value="no" @if($arr_data['fx']=='no')checked @endif> No
                                      </label>  
                                    <span class='help-block' id="err_fx">{{ $errors->first('fx') }}</span>  
                                  </div>
                            </div>
                        </div>
                        @endif              

                      <div class="form-group">
                          <div class="col-sm-6 col-lg-6 controls"></div><span class="btn btn-success addOption"><i class="fa fa-plus fa-1x"></i></span>
                      </div>
                      

                         <!-- Clonning section Starts Here --> 
                        
                        <!-- Footage Additional Fields ends Here -->
                  @if(isset($arr_data['listing_details']) && count($arr_data['listing_details']>0))
                  @foreach($arr_data['listing_details'] as $keys=>$list)
                    <div class="add_options">
                        <!-- Footage Additional Fields ends Here -->
                        <input type="hidden" name="listing_id[]" value="{{$list['id']}}">    
                        
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label" for="state"> Price <i class="red">*</i></label>
                            <div class="col-sm-6 col-lg-4 controls">
                                  <input type="text" class="form-control option_price" name="price[]" value="{{$list['price']}}" maxlength="255" placeholder="Enter Price">
                              <span class='help-block'>{{ $errors->first('price') }}</span>  
                            </div>
                            <span class="btn btn-danger removeOption" ><i class="fa fa-minus fa-1x"></i></span>
                        </div>

                        <!-- Footage Clone Fields Starts Here -->
                        @if($arr_data['type']=='footage')
                        <div id="footage_clone_fields">
                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label" for="state">Format <i class="red">*</i></label>
                                  <div class="col-sm-6 col-lg-4 controls">
                                        <select type="text" name="footage_format[]" id="footage_format" class="form-control option_footage_format">
                                             <option value="">Select Format</option>
                                              @if(isset($arr_footage_formats) && count($arr_footage_formats)>0)
                                              @foreach($arr_footage_formats as $key=>$value)
                                              <option value="{{$value['id']}}" @if($value['id']==$list['format']) selected @endif>{{ucwords($value["name"])}}</option>
                                              @endforeach
                                              @endif
                                       </select>
                                      <span class='help-block'>{{ $errors->first('arr_footage_format') }}</span>  
                                  </div>
                            </div>

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label" for="state">Resolution <i class="red">*</i></label>
                                  <div class="col-sm-6 col-lg-4 controls">
                                        <select type="text" name="resolution[]" id="arr_resolution" class="form-control option_resolution">
                                             <option value="">Select Resolution</option>
                                              @if(isset($arr_resolution) && count($arr_resolution)>0)
                                              @foreach($arr_resolution as $key=>$value)
                                              <option value="{{$value["id"]}}" @if($list['resolution']==$value["id"]) selected @endif >{{$value["size"]}}</option>
                                              @endforeach
                                              @endif
                                       </select>
                                      <span class='help-block'>{{ $errors->first('arr_resolution') }}</span>  
                                  </div>
                            </div>                                                                         

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label" for="state">FPS <i class="red">*</i></label>
                                  <div class="col-sm-6 col-lg-4 controls">
                                        <select type="text" name="fps[]" id="arr_fps" class="form-control option_fps" value="">
                                             <option value="">Select fps</option>
                                              @if(isset($arr_fps) && count($arr_fps)>0)
                                              @foreach($arr_fps as $key=>$value)
                                              <option value="{{$value["id"]}}" @if($list['fps']==$value["id"]) selected @endif >{{$value["value"]}}</option>
                                              @endforeach
                                              @endif
                                       </select>
                                      <span class='help-block'>{{ $errors->first('arr_fps') }}</span>  
                                  </div>
                            </div>

                          <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label"> Upload Footage <i class="red">*</i> </label>
                            <div class="col-sm-9 col-lg-10 controls">
                               <div class="fileupload fileupload-new parent-image" data-provides="fileupload">
                                  <div class="fileupload-new img-thumbnail option_footage_preview" style="max-width: 200px; max-height: 150px;">
                                     @if(isset($list['enc_item_name']) && !empty($list['enc_item_name']))
                                     <video src="{{ $footage_public_img_path.$list['enc_item_name'] }}" height="150px" width="200px"></video>
                                     @else
                                     <video src="" height="150px" width="200px"></video>
                                     @endif
                                  </div>
                                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                  <div>
                                     <span class="btn btn-default btn-file" style="height:32px;">
                                     <span class="fileupload-new">Select Footage</span>
                                     <span class="fileupload-exists">Change</span>
                                     <input type="file" class="file-input validate-image option_arr_footage" name="arr_footage[]" onchange="FootageValidation(this.files)" id="arr_footage" /><br>
                                     <input type="hidden" class="file-input option_old_footage" name="oldfootage[]" id="oldfootage"  
                                        value="{{ $list['enc_item_name'] }}"/>
                                     <input type="hidden" class="file-input option_old_footagename" name="oldfootagename[]" id="oldfootagename"  
                                        value="{{ $list['item_name'] }}"/>   
                                     </span>
                                     <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                  </div>
                                  <span for="image" class='help-block'>{{ $errors->first('arr_footage') }}</span>
                               </div>
                            </div>
                          </div>

                        </div>
                        @endif
                        <!-- Footage Clone Fields Ends Here -->

                        <!-- Photos Clone Fields starts here-->
                        @if($arr_data['type']=='photo')
                        <div id="photos_clone_fields">
                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label" for="state">Format <i class="red">*</i></label>
                                  <div class="col-sm-6 col-lg-4 controls">
                                        <select type="text" name="photo_format[]" id="photo_format" class="form-control option_photo_format" value="">
                                             <option value="">Select Format</option>
                                              @if(isset($arr_photo_formats) && count($arr_photo_formats)>0)
                                              @foreach($arr_photo_formats as $key=>$value)
                                              <option value="{{$value['id']}}" @if($value['id']==$list['format']) selected @endif>{{ucwords($value["name"])}}</option>
                                              @endforeach
                                              @endif
                                       </select>
                                      <span class='help-block'>{{ $errors->first('arr_photo_format') }}</span>  
                                  </div>
                            </div>

                            <div class="form-group">
                                  <label class="col-sm-3 col-lg-2 control-label" for="state">Orientation <i class="red">*</i></label>
                                  <div class="col-sm-6 col-lg-4 controls">
                                        <select type="text" name="orientation[]" id="arr_orientation" class="form-control option_orientation" value="">
                                             <option value="">Select Orientation</option>
                                              @if(isset($arr_orientation) && count($arr_orientation)>0)
                                              @foreach($arr_orientation as $key=>$value)
                                              <option value="{{$value["id"]}}" @if($value['id']==$list['orientation']) selected @endif>{{ucwords($value["value"])}}</option>
                                              @endforeach
                                              @endif
                                       </select>
                                      <span class='help-block'>{{ $errors->first('arr_orientation') }}</span>  
                                  </div>
                            </div>

                          <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label"> Upload Photo <i class="red">*</i> </label>
                            <div class="col-sm-9 col-lg-10 controls">
                               <div class="fileupload fileupload-new parent-image" data-provides="fileupload">
                                  <div class="fileupload-new img-thumbnail option_image_preview" style="width: 200px; height: 150px;">
                                     @if(isset($list['enc_item_name']) && !empty($list['enc_item_name']))
                                     <img src="{{$photos_public_img_path.$list['enc_item_name'] }}">
                                     @else
                                     <img src="{{url('/').'/uploads/default.png' }}">
                                     @endif
                                  </div>
                                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 250px; max-height: 150px; line-height: 20px;"></div>
                                  <div>
                                     <span class="btn btn-default btn-file" style="height:32px;">
                                     <span class="fileupload-new">Select Image</span>
                                     <span class="fileupload-exists">Change</span>
                                     <input type="file" class="file-input validate-image option_arr_photo" name="arr_photo[]" onchange="ImageValidation(this.files)" id="arr_photo" /><br>
                                     <input type="hidden" class="file-input option_old_photo" name="oldimage[]" id="oldimage"  
                                        value="{{ $list['enc_item_name'] }}"/>
                                     <input type="hidden" class="file-input option_old_imagename" name="oldimagename[]" id="oldimagename"  
                                        value="{{ $list['item_name'] }}"/>                                        
                                     </span>
                                     <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                  </div>
                                  <span for="image" class='help-block'>{{ $errors->first('arr_photo') }}</span>
                               </div>
                            </div>
                          </div>
                            
                        </div>
                        @endif
                        <!-- Photos Clone Fields ends here-->
                    </div>
                         <!-- Clonning section Ends Here --> 
                  @endforeach
                  @endif
                  @endif        
                </div>
                <br>
                <div class="form-group">
                      <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                      {{-- {!! Form::submit('Save',['class'=>'btn btn btn-primary','value'=>'true'])!!} --}}
                      <input type="hidden" class="file-input" name="enc_seller_id" id="enc_seller_id" value="{{ $enc_seller_id }}"/>   
                      <input class="btn btn btn-primary" name="Save" id="btn_upload_media" value="Update" type="submit">
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
    
</div>
</div>
</div>
<!-- END Main Content -->

<!--    Select2 JS and CSS -->
<link href="{{url('/')}}/css/front/jquery.tagsinput.css" rel="stylesheet" />
<script src="{{url('/')}}/js/front/jquery.tagsinput.js"></script>  

<script type="text/javascript">
  /*------------------ Tags Input --------------------*/
  $('#keywords').tagsInput({'delimiter': [',']});

/*------------------ Clone fields --------------------*/

$('.addOption').click(function(){
    
    if($('.add_options').length==1)
    {
         $('.removeOption').show();
    }

    if($('.add_options').length<5)
    {
        $('.add_options').last().clone().insertAfter($('.add_options').last());
        $('.add_options').last().find('input').val('');
        $('.add_options').last().find('select').val('');
        $('.add_options').last().find('select').val('');
        $('.add_options').last().find('select').val('');
        $('.add_options').last().find('.option_image_preview').html('<img src="{{url('/').'/uploads/default.png' }}">');
        $('.add_options').last().find('.option_footage_preview').html('');

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
                  $(this).closest('.add_options').remove();
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
      $('#err_duration').html(''); $('#err_duration').hide();
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
            $(this).find('.option_price').next('span').html('Please enter price');         
            flag=0;     
        }
        else if(!filter_price.test($(this).find('.option_price').val()))
        {
            $(this).find('.option_price').next('span').show();         
            $(this).find('.option_price').next('span').html('Please enter valid price');         
            flag=0;     
        }

        if(type=='photo')
        {
            if($(this).find('.option_photo_format').val()=='')
            {
                $(this).find('.option_photo_format').next('span').show();         
                $(this).find('.option_photo_format').next('span').html('Please select format');         
                flag=0;     
            }   

            if($(this).find('.option_orientation').val()=='')
            {
                $(this).find('.option_orientation').next('span').show();         
                $(this).find('.option_orientation').next('span').html('Please select orientation');         
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
                $(this).find('.option_resolution').next('span').html('Please enter resolution');         
                flag=0;     
            }

            if($(this).find('.option_footage_format').val()=='')
            {
                $(this).find('.option_footage_format').next('span').show();         
                $(this).find('.option_footage_format').next('span').html('Please select format');         
                flag=0;     
            }

            if($(this).find('.option_fps').val()=='')
            {
                $(this).find('.option_fps').next('span').show();         
                $(this).find('.option_fps').next('span').html('Please select fps');         
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
                $(this).find('.option_photo_format').next('span').html('Please select format');         
                flag=0;     
            }

            if($(this).find('.option_orientation').val()=='')
            {
                $(this).find('.option_orientation').next('span').show();         
                $(this).find('.option_orientation').next('span').html('Please select orientation');         
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
        var number_filter      = /^[0-9]*$/;
        var ratio              = $('#ratio').val();
        var duration           = $('#duration').val();

       if(ratio.trim()=='')
       {
            $('#err_ratio').html('Please select ratio');
            $('#err_ratio').show();
            flag=0;  
       }

      if(duration.trim()=='')
      {
        $('#err_duration').html('Please select duration');
        $('#err_duration').show();
        flag=0;  
      }
      else if(!number_filter.test(duration))
      {
        $('#err_duration').html('Please enter valid duration.');
        $('#err_duration').show();
        flag=0; 
      }      

      if($('input[name=alpha_channel]:checked').length<=0)
      {
        $('#err_alpha_channel').html('Please select alpha channel');
        $('#err_alpha_channel').show();
        flag=0;
      }

      if($('input[name=alpha_matte]:checked').length<=0)
      {
        $('#err_alpha_matte').html('Please select alpha matte');
        $('#err_alpha_matte').show();
        flag=0;
      }
      
      if($('input[name=media_release]:checked').length<=0)
      {
        $('#err_media_release').html('Please select media release');
        $('#err_media_release').show();
        flag=0;
      }        

      if($('input[name=looping]:checked').length<=0)
      {
        $('#err_looping').html('Please select looping');
        $('#err_looping').show();
        flag=0;
      }

      if($('input[name=model_release]:checked').length<=0)
      {
        $('#err_model_release').html('Please select model release');
        $('#err_model_release').show();
        flag=0;
      }

      if($('input[name=liscense_type]:checked').length<=0)
      {
        $('#err_liscense_type').html('Please select liscense type');
        $('#err_liscense_type').show();
        flag=0;
      }

      if($('input[name=fx]:checked').length<=0)
      {
        $('#err_fx').html('Please select fx');
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

  /*------------------ Photo Format Validation --------------------*/

/*$('.add_options').each(function()
{
  $(document).on('change',$(this).find('.option_arr_photo'),function(){
      alert($(this).find('.option_photo_format').closest('.option_photo_format').val());
      $(this).find('.option_photo_format').next('span').show();         
      $(this).find('.option_photo_format').next('span').html('Please select format');         
      return false; 
  })
});*/

</script>

@stop                    
