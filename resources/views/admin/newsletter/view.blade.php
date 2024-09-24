@extends('admin.layout.master')                
@section('main_content')
 <style type="text/css"> .controls{ padding-top:8px; }</style>
<div class="page-title">
    <div>
        <h1><i class="fa fa-newspaper-o"></i> View Newsletter</h1>
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
        <li class="active">View Newsletter</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">

            @if(Session::has('success'))
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
            @endif
            
            <div class="box-title">
                <h3><i class="fa fa-eye"></i>@if(isset($page_title)) {{ $page_title }} @endif</h3>
                <div class="box-tool">
                    <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
                    <a href="#" data-action="close"><i class="fa fa-times"></i></a>
                </div>
            </div>
            
            <div class="box-content">
                <form class="form-horizontal" id="validation-form" action="{{$module_url_path}}/view/{{$enc_id}}" name="Frm_newsletter" method="post">
                    {{ csrf_field() }}
                   
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label"><b>Title :</b></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            {{isset($arr_news_letter['title'])?$arr_news_letter['title']:""}}
                        </div>
                    </div>

                     <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label"><b>Subject :</b></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            {{isset($arr_news_letter['subject'])?$arr_news_letter['subject']:""}}
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label"><b>Description :</b></label>
                        <div class="col-sm-9 col-lg-7 controls">
                            @if(isset($arr_news_letter['news_message']) && $arr_news_letter['news_message']!='')
                            <?php echo nl2br($arr_news_letter['news_message']) ?>
                            @endif
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop