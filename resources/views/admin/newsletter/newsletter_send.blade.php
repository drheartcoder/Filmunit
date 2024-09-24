@extends('admin.layout.master')                
@section('main_content')


    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/assets/data-tables/latest/dataTables.bootstrap.min.css">
    
    <div class="page-title">
        <div>
            <h1><i class="fa fa-newspaper-o"></i>@if(isset($page_title)) {{ $page_title }} @endif</h1>
        </div>
    </div>

    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
            </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                <a href="{{ $module_url_path }}">Newsletter</a>
            </span> 
            <span class="divider">
                <i class="fa fa-angle-right"></i>
            </span>
            <li class="active">{{ $page_title or ''}}</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
                <div class="box">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-newspaper-o"></i>
                            {{ isset($page_title)?$page_title:"" }}
                        </h3>
                        <div class="box-tool">
                            <a data-action="collapse" href="#"></a>
                            <a data-action="close" href="#"></a>
                        </div>
                    </div>

                    <div class="box-content">
                        @include('admin.layout._operation_status')
                        <form name="validation-form" id="validation-form" method="POST" class="form-horizontal" action="{{$module_url_path}}/send_email">
                            {{ csrf_field() }}
                            <div class="btn-toolbar pull-left clearfix">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-8">
                                            <select name="news_letter" class="form-control" id="news_letter">
                                                <option value="">--- Select Newsletter ---</option>
                                                @if(isset($arr_newsletters) && sizeof($arr_newsletters)>0)
                                                    @foreach($arr_newsletters as $newslette)
                                                        <option value="{{base64_encode($newslette['id'])}}">{{isset($newslette['title'])?$newslette['title']:'-'}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class='error' id="err_news_letter" style="color:red;font-size:13px;">{{ $errors->first('news_letter') }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            
                                            <input type="submit" value="Send" class="btn btn-primary btn_send" id="btn_send" name="btn_send">
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="btn-toolbar pull-right clearfix">
                                <div class="btn-group"> 
                                    <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" href="{{ $module_url_path }}/send" style="text-decoration:none;">
                                        <i class="fa fa-repeat"></i>
                                    </a> 
                                </div>
                            </div>

                            <br/><br/><br/><br/>
                            <div class="clearfix"></div>
                            
                            <div class="table-responsive" style="border:0">
                                <input type="hidden" name="multi_action" value="" />
                                
                                <table class="table table-advance table-bordered"  id="table1">
                                    <thead>
                                        <tr>
                                            <th style="width:18px"> <input type="checkbox" name="mult_change[]" id="mult_change" /></th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                        @if(isset($arr_subscriber) && sizeof($arr_subscriber)>0)
                                            @foreach($arr_subscriber as $subscriber)
                                                <tr>
                                                    <td> 
                                                        <input type="checkbox" name="checked_record[]" class="checked_record" value="{{ isset($subscriber['subscriber_email'])?$subscriber['subscriber_email']:''}}" /> 
                                                    </td>
                                                    <td>{{ isset($subscriber['subscriber_email'])?$subscriber['subscriber_email']:''  }} </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            
        </div>
    


<script type="text/javascript" src="{{ url('/assets/assets/data-tables/latest') }}/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ url('/assets/assets/data-tables/latest') }}/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $('#btn_send').on('click',function()
    {
        var name = $('#news_letter').val();

        $('#err_news_letter').html('');

        if(name =='') 
        {
            $('#err_news_letter').html('Please select newsletter');
            return false;
        }

        return true;
    })
</script>

@stop