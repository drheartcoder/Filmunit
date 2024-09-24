@extends('admin.layout.master')                
@section('main_content')

<div class="page-title">
    <div>
        <h1><i class="fa fa-newspaper-o"></i> Edit Newsletter</h1>
    </div>
</div>

<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ url('/admin/dashboard') }}"> Home</a>
            <span class="divider"><i class="fa fa-angle-right"></i></span>
            <a href="{{ url('/admin/newsletter') }}"> Newsletter</a>
            <span class="divider"><i class="fa fa-angle-right"></i></span>
        </li>
        <li class="active">Edit Newsletter</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            
          <!--   @if(Session::has('success'))
                <div class="alert alert-success" id="msg_success">
                    <button data-dismiss="alert" class="close">×</button>
                    <strong>Success!</strong> {{ Session::get('success') }}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger" id="msg_error">
                    <button data-dismiss="alert" class="close">×</button>
                    <strong>Error!</strong> {{ Session::get('error') }}
                </div>
            @endif -->
            
            <div class="box-title">
                <h3><i class="fa fa-newspaper-o"></i>@if(isset($page_title)) {{ $page_title }} @endif</h3>
                <div class="box-tool">
                    <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
                    <a href="#" data-action="close"><i class="fa fa-times"></i></a>
                </div>
            </div>
            
            <div class="box-content">
            @include('admin.layout._operation_status')
                <form class="form-horizontal" id="validation-form" action="{{$module_url_path}}/update/{{$enc_id}}" name="Frm_newsletter" method="post">
                    {{ csrf_field() }}
                   
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Title <i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            <input type="text" class="form-control"  placeholder="News Title" name="news_title" value="{{isset($arr_news_letter['title'])?$arr_news_letter['title']:""}}" id="news_title">
                            <span class='error' id='err_news_title' style="color:red;font-size:13px;">{{ $errors->first('news_title') }}</span>                    
                        </div>
                    </div>

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Subject <i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            <input type="text" class="form-control"  placeholder="News Subject" name="news_subject" value="{{isset($arr_news_letter['subject'])?$arr_news_letter['subject']:""}}" id="news_subject">
                            <span class='error' id='err_news_subject' style="color:red;font-size:13px;">{{ $errors->first('news_subject') }}</span>                    
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Description <i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            <textarea class="form-control col-md-12 ckeditor news_description" name="news_description" id="news_description" rows="6" placeholder="News Description">{{isset($arr_news_letter['news_message'])?$arr_news_letter['news_message']:""}}</textarea>  
                            <span class='error' id='err_news_description' style="color:red;font-size:13px;">{{ $errors->first('news_description') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3 col-lg-6 col-lg-offset-2">
                            <button class="btn btn-primary" type="submit" name="update_news_letter" id="update_news_letter">Update</button>
                            <a class="btn" href="{{ $module_url_path }}">Cancel</a>            
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
       <script src="{{ url('/') }}/assets/admin/js/ckeditor/ckeditor.js"></script>
@stop