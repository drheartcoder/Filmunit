@extends('admin.layout.master')
@section('main_content')
<link rel="stylesheet" type="text/css" href="{{ url('/assets/data-tables/latest/') }}/dataTables.bootstrap.min.css">

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
                <i class="fa fa-bell"></i>                
            </span>
            <li class="active">{{ $module_title or ''}}</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box">
            <div class="box-title">
              <h3>
                <i class="fa {{$module_icon}}"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
        <div class="box-content">

          @include('admin.layout._operation_status')  

          <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
          {{ csrf_field() }} 
          <div class="col-md-10">
            

            <div id="ajax_op_status">
                
            </div>
            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
          <div class="btn-toolbar pull-right clearfix">
            <div class="btn-group">    
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
               title="Multiple Delete" 
               href="javascript:void(0);" 
               onclick="javascript : return check_multi_action('frm_manage','delete');"  
               style="text-decoration:none;">
               <i class="fa fa-trash-o"></i>
            </a>
            </div>

            <div class="btn-group"> 
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
               title="Refresh" 
               href="{{ $module_url_path }}"
               style="text-decoration:none;">
               <i class="fa fa-repeat"></i>
            </a> 
            </div>
          </div>
          <br/>
          <br/>

          <div class="clearfix"></div>
          <div class="table-responsive" style="border:0">
            <input type="hidden" name="multi_action" value="" />
            <table class="table table-advance"  id="table_module" >
              <thead>
                  <tr>
                     <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" value="delete" /></th>
                     <th>Date & Time</th>
                     <th>Notification</th>
                     <th>Action</th>
                  </tr>
              </thead>
              <tbody>             
                @if(isset($arr_notifications) && sizeof($arr_notifications)>0)
                  @foreach($arr_notifications as $notification)
                  <tr>                     
                      <td> 
                          <input type="checkbox" name="checked_record[]" class="checked_record" value="{{ base64_encode($notification['id']) }}" /> 
                      </td>
                      <td> {{ isset($notification['created_at'])?date("m/d/Y", strtotime($notification['created_at'])):'' }} </td>
                      
                      <td>  
                      <?php if(isset($notification['message']))
                      {
                        echo html_entity_decode($notification['message']); 
                      } ?>
                       </td>
                      
                      <td> 
                          <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/delete/'.base64_encode($notification['id'])}}" onclick="return confirm_action(this,event,'Do you really want to delete this record ?')" title="Delete"><i class="fa fa-trash" ></i></a>
                      </td>
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

<!-- END Main Content -->

<script type="text/javascript">
    
    $(document).ready(function() {
        $('#table_module').DataTable();
    });
</script>
@stop                    


