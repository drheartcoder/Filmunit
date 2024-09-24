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
                <i class="fa fa-th-large"></i>
                <a href="{{ url($admin_panel_slug.'/subcategories') }}">{{ $module_title or ''}}</a>
            </span> 
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                  <i class="fa fa-edit"></i>
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
                <i class="fa fa-edit"></i>
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
                
                
                {!! Form::open([ 'url' => $admin_panel_slug.'/subcategories/update/'.$enc_id,
                                 'method'=>'POST',
                                 'enctype' =>'multipart/form-data',   
                                 'class'=>'form-horizontal',
                                 'id'=>'validation-form' 

                                ]) !!} 

                  
                <div  class="tab-content">
                            
                          <div class="form-group">
                              <label class="col-sm-3 col-lg-2 control-label" for="state">Category <i class="red">*</i></label>
                              <div class="col-sm-6 col-lg-4 controls">
                               <select name="parent" id="parent" data-rule-required="true" class="form-control">
                                 @foreach($arr_parent_category_options as $key => $category)
                                 
                                 @if($arr_category['parent_category']['id'] == $category['id'])
                                 <option value="{{$arr_category['parent_category']['id']}}" selected>{{$arr_category['parent_category']['title']}}</option>
                                 @else
                                 <option value="{{$category['id']}}" >{{$category['title']}}</option>
                                 @endif
                                 @endforeach

                               </select>
                              </div>
                        </div>

                          <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label"> Image <i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-10 controls">
                               <div class="fileupload fileupload-new" data-provides="fileupload">
                                  <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                                      <img src={{ $category_public_img_path.'/'.$arr_category['image']}} alt="" />  
                                  </div>
                                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                  <div>
                                     <span class="btn btn-default btn-file">
                                        <span class="fileupload-new" >Select image</span> 
                                        <span class="fileupload-exists">Change</span>
                                     <input type="file" class="file-input validate-image" name="image" id="image">
                                      {{-- {!! Form::file('image',['id'=>'ad_image','class'=>'file-input']) !!} --}}


                                      </span> 
                                      <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                  </div>
                                      <i class="red">{!!image_validate_note(400,770)!!}</i>
                               </div>
                                <span for="image" class='help-block'>{{ $errors->first('image') }}</span>  
                            </div>
                          </div>  

                          <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label" for="state"> Title <i class="red">*</i></label>
                                <div class="col-sm-6 col-lg-4 controls">
                                          <input type="text" class="form-control" name="title"  data-rule-required='true' value="{{$arr_category['title']}}" maxlength="255" placeholder="Enter Title">
                                            {{-- {!! Form::text('title',old('title'),['class'=>'form-control','data-rule-required'=>'true','data-rule-maxlength'=>'255' , 'placeholder'=>'Enter Title']) !!} --}}  
                                      <span class='help-block'>{{ $errors->first('title') }}</span>  
                                    </div> 
                          </div>
                          <div class="form-group">
                                      <label class="col-sm-3 col-lg-2 control-label" for="state"> Description <i class="red">*</i></label>
                                      <div class="col-sm-6 col-lg-4 controls">
 
                                         <textarea class='form-control' placeholder='Description' name='description' data-rule-required='true' maxlength="1000">{{$arr_category['description']}}</textarea>     
                                      </div>
                                      <span class='help-block'>{{ $errors->first('description') }}</span>
                          </div>
                </div>
                   <br>
                   <div class="form-group">
                          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                            {!! Form::submit('Save',['class'=>'btn btn btn-primary','value'=>'true'])!!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

         
</div>
</div>
</div>

<!-- END Main Content -->
<script type="text/javascript">
  $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 400, 770);
    });
</script>
@stop                    
