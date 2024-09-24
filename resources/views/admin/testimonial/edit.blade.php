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
      <a href="{{ url($admin_panel_slug.'/dashboard') }}"> Dashboard </a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$module_icon or ''}}"></i>
      <a href="{{ $module_url_path }}"> {{ $module_title or ''}} </a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$edit_icon or ''}}"></i>
    </span>
    <li class="active"> {{ $page_title or ''}} </li>
  </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3>
          <i class="fa {{$edit_icon or ''}}"></i>
          {{ isset($page_title)?$page_title:"" }}
        </h3>
        <div class="box-tool">
          <a data-action="collapse" href="#"></a>
          <a data-action="close" href="#"></a>
        </div>
      </div>
      <div class="box-content">
        @include('admin.layout._operation_status')
          <form action="{{ $module_url_path.'/update/'.$enc_id }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="validation-form">
          {{ csrf_field() }}
    
                <ul class="nav nav-tabs">
                    @include('admin.layout._multi_lang_tab')
                </ul>
                <br/><br/>

                <div  class="tab-content">

                  @if(isset($arr_lang) && sizeof($arr_lang)>0)
                    @foreach($arr_lang as $lang)
                    <?php
                      /* Locale Variable */  
                      $locale_question = "";
                      $locale_answer   = "";

                      if(isset($arr_data['translations'][$lang['locale']]))
                      {
                        if($lang['locale'] == 'en')
                        {
                         $locale_user_name_en   = $arr_data['translations'][$lang['locale']]['user_name'];
                         $locale_description_en = $arr_data['translations'][$lang['locale']]['description'];
                         // $locale_user_post_en   = $arr_data['translations'][$lang['locale']]['user_post'];
                        }
                        else
                        {
                         $locale_user_name_pr   = $arr_data['translations'][$lang['locale']]['user_name'];
                         $locale_description_pr = $arr_data['translations'][$lang['locale']]['description'];
                        // $locale_user_post_pr   = $arr_data['translations'][$lang['locale']]['user_post']; 
                        }
                      }
                    ?>
                       <div class="tab-pane fade {{ $lang['locale']=='en'?'in active':'' }}" 
                               id="{{ $lang['locale'] }}">
                        
                           <div class="form-group">
                                      <label class="col-sm-3 col-lg-2 control-label" for="state"> User Name @if($lang['locale'] == 'en') 
                                          <i class="red">*</i>
                                       @endif
                                       </label>
                                      <div class="col-sm-6 col-lg-4 controls">

                                        @if($lang['locale'] == 'en')        
                                          <input type="text" data-rule-required='true'  placeholder='User Name' name='user_name_{{$lang['locale']}}' value="{{$locale_user_name_en or ''}}" class="form-control">
                                        @else
                                            <input type="text" placeholder='User Name' name='user_name_{{$lang['locale']}}' value="{{$locale_user_name_pr or ''}}" class="form-control">
                                        @endif    
                                      </div>
                                      <span class='help-block'>{{ $errors->first('user_name_'.$lang['locale']) }}</span>  
                                </div>

                                <div class="form-group">
                                      <label class="col-sm- col-lg-2 control-label" for="state"> Description 
                                            @if($lang['locale'] == 'en') 
                                              <i class="red">*</i>
                                           @endif
                                      </label>
                                      <div class="col-sm-6 col-lg-4 controls">

                                        @if($lang['locale'] == 'en')  
                                         <textarea class='form-control' placeholder='Description' name='description_{{$lang['locale']}}'  data-rule-required='true'>{{$locale_description_en or ''}}</textarea>     
                                        @else
                                            <textarea class='form-control' placeholder='Description' name='description_{{$lang['locale']}}' >{{$locale_description_pr or ''}}</textarea>  
                                        @endif    
                                      </div>
                                      <span class='help-block'>{{ $errors->first('description_'.$lang['locale']) }}</span>  
                                </div>
                                
                                @if($lang['locale']=="en") 
                                  <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"> Image <i class="red">*</i> </label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                      <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                                        @if(isset($arr_data['user_image']) && !empty($arr_data['user_image']))
                                        <img src="{{$testimonial_public_img_path.$arr_data['user_image'] }}">
                                        @else
                                          <img src="{{url('/').'/uploads/default.png' }}">
                                        @endif
                                          {{-- <img src={{ $testimonial_public_img_path.'/'.$arr_data['user_image']}} alt="" />   --}}
                                        </div>
                                        <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                                        </div>
                                        <div>
                                          <span class="btn btn-default btn-file">
                                            <span class="fileupload-new" >Select image
                                            </span> 
                                            <span class="fileupload-exists">Change
                                            </span>
                                            <input type="file" class="file-input validate-image" name="user_image" id="image">
                                          </span> 
                                          <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                        </div>
                                      <i class="red"> {!!image_validate_note(400,770)!!} </i>
                                      </div>
                                      <span class='help-block'>{{ $errors->first('user_image') }}
                                      </span>  
                                    </div>
                                  </div> 
                                @endif


                        </div>

                      @endforeach
                  @endif

                </div>
                <br>
                <div class="form-group">
                      <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                     <input type="submit" name="btn_save"  class='btn btn btn-primary' value="Update">
                    </div>
                </div>

           </form>
        </div><!--box content-->
      </div><!--box-->
    </div><!--col-md-12-->
  {{-- </div> --}}<!--row-->

<script type="text/javascript">
  $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 400, 770);
    });
</script>
@endsection