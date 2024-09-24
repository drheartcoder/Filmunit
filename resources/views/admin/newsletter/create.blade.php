@extends('admin.layout.master')    
@section('main_content')
<div class="page-title">
    <div>
        <h1><i class="fa fa-newspaper-o"></i> Add Newsletter</h1>
    </div>
</div>

<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ url('/admin/dashboard') }}">Home</a>
            <span class="divider"><i class="fa fa-angle-right"></i></span>
            <a href="{{ url('/admin/newsletter') }}">Newsletter</a>
            <span class="divider"><i class="fa fa-angle-right"></i></span>
        </li>
        <li class="active">Add Newsletter</li>
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
                <form class="form-horizontal" action="{{ url('/admin/newsletter/store') }}" name="Frm_newsletter" method="post" id="validation-form">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                       <label class="col-sm-3 col-lg-2 control-label">Title <i class="red">*</i></label>
                       <div class="col-sm-9 col-lg-7 controls">
                            <input type="text" class="form-control"  placeholder="News Title" name="news_title" id="news_title">
                            <span class='error' id='err_news_title' style="color:red;font-size:13px;">{{ $errors->first('news_title') }}</span>                    
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Subject <i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-7 controls">
                           <input type="text" class="form-control" name="news_subject" id="news_subject"  placeholder="News Subject"> 
                           <span class='error' id='err_news_subject' style="color:red;font-size:13px;">{{ $errors->first('news_subject') }}</span>                    
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Description <i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            <textarea class="form-control ckeditor" placeholder="News Decription"  rows="6"  name="news_description" id="news_description"></textarea>
                            <span class='error' id='err_news_description' style="color:red;font-size:13px;">{{ $errors->first('news_description') }}</span>
                        </div>
                    </div>
                     
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                            <input type="submit" class="btn btn-primary" name="create_news_letter" id="create_news_letter"  value="Add">
                            <a class="btn" href="{{ $module_url_path }}">Cancel</a>
                        </div>
                    </div>
                    
                 </form>
            </div>
        </div>
    </div>   
   <script src="{{ url('/') }}/assets/admin/js/ckeditor/ckeditor.js"></script>
  
@stop