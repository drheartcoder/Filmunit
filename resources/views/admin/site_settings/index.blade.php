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
                <i class="fa fa-google-plus"></i>
            </span> 
            <li class="active">  {{ isset($page_title)?$page_title:"" }}</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    
    
    <div class="row">
        <div class="col-md-12">
            <div class="box  {{ $theme_color }}">
                <div class="box-title">
                    <h3><i class="fa fa-google-plus" aria-hidden="true"></i> {{ isset($page_title)?$page_title:"" }}</h3>
                    <div class="box-tool">
                    </div>
                </div>
                <div class="box-content">
                    @include('admin.layout._operation_status')
                    
                    {!! Form::open([ 'url' => $module_url_path.'/update/'.base64_encode($arr_data['site_setting_id']),
                                 'method'=>'POST',   
                                 'class'=>'form-horizontal', 
                                 'id'=>'validation-form' ,
                                 'enctype'=>'multipart/form-data'
                                ]) !!}
                         <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Google Plus URL<i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-4 controls">
                                 {!! Form::text('google_plus_url',isset($arr_data['google_plus_url'])?$arr_data['google_plus_url']:'',['class'=>'form-control','data-rule-required'=>'true', 'data-rule-url'=>'true', 'data-rule-maxlength'=>'500','placeholder'=>'Google Plus URL']) !!}
                                <span class='help-block'>{{ $errors->first('google_plus_url') }}</span>
                            </div>
                        </div>
  

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Facebook URL<i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-4 controls">
                                 {!! Form::text('fb_url',isset($arr_data['fb_url'])?$arr_data['fb_url']:'',['class'=>'form-control','data-rule-required'=>'true', 'data-rule-url'=>'true', 'data-rule-maxlength'=>'500','placeholder'=>'Facebook URL']) !!}
                                <span class='help-block'>{{ $errors->first('fb_url') }}</span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Twitter URL<i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-4 controls">
                                {!! Form::text('twitter_url',isset($arr_data['twitter_url'])?$arr_data['twitter_url']:'',['class'=>'form-control','data-rule-required'=>'true', 'data-rule-url'=>'true', 'data-rule-maxlength'=>'500','placeholder'=>'Twitter URL']) !!}
                                <span class='help-block'>{{ $errors->first('twitter_url') }}</span>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Pinterest URL<i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-4 controls">
                                {!! Form::text('pinterest_url',isset($arr_data['pinterest_url'])?$arr_data['pinterest_url']:'',['class'=>'form-control','data-rule-required'=>'true', 'data-rule-url'=>'true', 'data-rule-maxlength'=>'500']) !!}
                                <span class='help-block'>{{ $errors->first('pinterest_url') }}</span>
                            </div>
                        </div>
                        {{--
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">RSS Feed URL </label>
                            <div class="col-sm-9 col-lg-4 controls">
                                {!! Form::text('rss_feed_url',isset($arr_data['rss_feed_url'])?$arr_data['rss_feed_url']:'',['class'=>'form-control','data-rule-required'=>'true', 'data-rule-url'=>'true', 'data-rule-maxlength'=>'500']) !!}
                                <span class='help-block'>{{ $errors->first('rss_feed_url') }}</span>
                            </div>
                        </div> --}}   

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Instagram URL<i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-4 controls">
                                 {!! Form::text('instagram_url',isset($arr_data['instagram_url'])?$arr_data['instagram_url']:'',['class'=>'form-control','data-rule-required'=>'true', 'data-rule-url'=>'true', 'data-rule-maxlength'=>'500']) !!}
                                <span class='help-block'>{{ $errors->first('instagram_url') }}</span>
                            </div>
                        </div>   
                        <hr/>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                {!! Form::submit('Update',['class'=>'btn btn btn-primary','value'=>'true'])!!}
                            </div>
                       </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    
    <!-- END Main Content --> 
    <script type="text/javascript">
     $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 640 , 1920);
    });   
    </script>
@endsection