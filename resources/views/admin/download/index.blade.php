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
      <i class="fa fa-download">
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
          <label class="col-sm-3 col-lg-2 control-label">Download Attempts 
            <i class="red">*
            </i>
          </label>
          <div class="col-sm-9 col-lg-4 controls">
          <input type="text" class="form-control"  name="attempts" id="attempts" value="{{$arr_data['attempts']}}" data-rule-number="true" data-rule-required="true" maxlength="5" min="2" placeholder="Download Attempts" /> 
            {{-- {!! Form::text('attempts',['class'=>'form-control','data-rule-required'=>'false','data-rule-lettersonly'=>'true','data-rule-maxlength'=>'255', 'placeholder'=>'attempts']) !!} --}}
            <span class='help-block'>{{ $errors->first('attempts') }}
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


  <!-- END Main Content --> 
  @endsection
