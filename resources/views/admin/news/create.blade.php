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
      <i class="fa fa-newspaper-o"></i>
      <a href="{{ $module_url_path }}">{{'Manage ' }}{{ $module_title or ''}}</a>
      </span> 
      <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-plus"></i>
      </span>
      <li class="active">{{ $page_title or ''}}</li>
   </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
   <div class="col-md-12">
      <div class="box">
         <div class="box-title">
            <h3>
               <i class="fa fa-plus"></i>
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

               <form name="validation-form" id="validation-form" method="POST" class="form-horizontal" action="{{$module_url_path}}/store" enctype="multipart/form-data">

              {{ csrf_field() }}

                  {{-- <ul  class="nav nav-tabs">
                     @include('admin.layout._multi_lang_tab')
                  </ul> --}}
                  <div id="myTabContent1" class="tab-content">
                     @if(isset($arr_lang) && sizeof($arr_lang)>0)
                     @foreach($arr_lang as $lang)
                     
                     <div class="tab-pane fade {{ $lang['locale']=='en'?'in active':'' }}"
                        id="{{ $lang['locale'] }}">
                        <div class="form-group">
                           <label class="col-sm-3 col-lg-2 control-label" for="skill_name">{{ $module_title or ''}} Title<i class="red">*</i></label>
                           <div class="col-sm-6 col-lg-8 controls">
                              @if($lang['locale'] == 'en') 
                              <input type="text" name="title_{{$lang['locale']}}" class="form-control" value="{{old('title_'.$lang['locale'])}}" data-rule-required="true" data-rule-maxlength="255" placeholder="{{ $module_title or ''}} Title">
                              @else
                              <input type="text" name="title_{{$lang['locale']}}" class="form-control" value="{{old('title_'.$lang['locale'])}}" placeholder="{{ $module_title or ''}} Title" data-rule-required="" data-rule-maxlength="255">
                              @endif    
                              <span class='error'>{{ $errors->first('title_'.$lang['locale']) }}</span>
                           </div>
                        </div>
                         <div class="form-group">
                           <label class="col-sm-3 col-lg-2 control-label" for="skill_name">{{ $module_title or ''}} Description<i class="red">*</i></label>
                           <div class="col-sm-6 col-lg-8 controls">
                              @if($lang['locale'] == 'en') 
                              <textarea type="text" name="description_{{$lang['locale']}}" class="form-control" data-rule-required="true"  placeholder="{{ $module_title or ''}} Description">
                              {{old('description_'.$lang['locale'])}}
                              </textarea>
                              @else
                              <textarea type="text" name="description_{{$lang['locale']}}" class="form-control"  placeholder="{{ $module_title or ''}} Description" data-rule-required="" >{{old('description_'.$lang['locale'])}}
                              </textarea>
                              @endif    
                              <span class='error'>{{ $errors->first('description_'.$lang['locale']) }}</span>
                           </div>
                        </div>
                     </div>

                     @endforeach
                     @endif

                     <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">News Image  <i class="red">*</i> </label>
                      <div class="col-sm-9 col-lg-10 controls">
                          <div class="fileupload fileupload-new" data-provides="fileupload">
                              <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;"></div>
                              <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                              <div>
                                  <span class="btn btn-default btn-file" style="height:32px;">
                                      <span class="fileupload-new">Select Image</span>
                                      <span class="fileupload-exists">Change</span>

                                      <input type="file"  data-validation-allowing="jpg, png, gif" class="file-input news-image validate-image" name="image" id="image" data-rule-required="true" /><br>
                                      
                                  </span>
                                  <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                              </div>
                              <i class="red">{!!image_validate_note(400,770)!!}</i>
                            
                             <span for="image" id="err-image" class="help-block">{{ $errors->first(' image') }}</span>
                            
                          </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-sm-6 col-lg-5 control-label help-block-red" style="color:#b94a48;" id="err_logo"></div><br/>
                      <div class="col-sm-6 col-lg-5 control-label help-block-green" style="color:#468847;" id="success_logo"></div>

                </div>

                  </div>
                  <br>
                  <div class="form-group">
                     <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                        <input type="submit" value="Save" onclick="saveTinyMceContent();" class="btn btn btn-primary">
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
 <script type="text/javascript">
    function saveTinyMceContent()
    {
      tinyMCE.triggerSave();
    }


    $(document).ready(function()
    {
      tinymce.init({
        selector: 'textarea',
        height:350,
        plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
        ],
        valid_elements : '*[*]',
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ',
        content_css: [
        '//www.tinymce.com/css/codepen.min.css'
        ]
      });  
    });

    $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 400, 770);
    });   
    
  </script>
@stop