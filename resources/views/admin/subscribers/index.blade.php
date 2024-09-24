    @extends('admin.layout.master')                

    @section('main_content')

    <!-- BEGIN Page Title -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">

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
                <i class="fa fa-list"></i>
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
                <i class="fa fa-list"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
        <div class="box-content">
             @include('admin.layout._operation_status') 

           <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action" id="validation-form">
               {{ csrf_field() }}

          {{ csrf_field() }}

          <div class="col-md-10">
            <div id="ajax_op_status"></div>
             <div class="alert alert-danger" id="no_select" style="display:none;"></div>
              <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
          <div class="btn-toolbar pull-right clearfix">
            <div class="btn-group"> 
            @if(array_key_exists('subscribers.update', $arr_current_user_access))     
                    
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                 title="Multiple Active/Unblock" 
                 href="javascript:void(0);" 
                 onclick="javascript : return check_multi_action('frm_manage','activate');" 
                 style="text-decoration:none;">
                  <i class="fa fa-unlock"></i>
                </a>

                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Multiple Deactive/Block" 
                   href="javascript:void(0);" 
                   onclick="javascript : return check_multi_action('frm_manage','deactivate');"  
                   style="text-decoration:none;">
                    <i class="fa fa-lock"></i>
                </a>  

                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Multiple Delete" 
                   href="javascript:void(0);" 
                   onclick="javascript : return check_multi_action('frm_manage','delete');"  
                   style="text-decoration:none;">
                   <i class="fa fa-trash-o"></i>
                </a>

              @endif
            
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Refresh" 
                   href="javascript:void(0)"
                   onclick="javascript:location.reload();" 
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
                @if(array_key_exists('subscribers.update', $arr_current_user_access))     
            
                  <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th>
                @endif
                  <th>Email</th>
                @if(array_key_exists('subscribers.update', $arr_current_user_access))     
             
                  <th>Status</th> 
                @endif
                @if(array_key_exists('subscribers.delete', $arr_current_user_access))     
            
                  <th>Action</th>
                @endif
                </tr>
              </thead>
               <tbody>
                
                @if(isset($arr_news_subscriber) && sizeof($arr_news_subscriber)>0)
                  @foreach($arr_news_subscriber as $subscriber)

                  <tr>
                  @if(array_key_exists('subscribers.update', $arr_current_user_access))     
            
                    <td> 
                      <input type="checkbox" 
                             name="checked_record[]"  
                             class="checked_record" 
                             value="{{ base64_encode($subscriber['id']) }}" /> 
                    </td>
                  @endif
                    <td> {{isset($subscriber['subscriber_email'])?$subscriber['subscriber_email']:''  }} </td> 
                    
                  @if(array_key_exists('subscribers.update', $arr_current_user_access))     
            
                      <td>
                        @if($subscriber['is_active']==1)
                          <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                          onclick="return confirm_action(this,event,'Do you really want to Deactivate this record ?')"
                          style="text-decoration:none;" 
                          href="{{ $module_url_path.'/deactivate/'.base64_encode($subscriber['id']) }}" 
                          title="Deactive">
                          <i class="fa fa-unlock"></i></a>
                        @else
                         <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                         style="text-decoration:none;" 
                         onclick="return confirm_action(this,event,'Do you really want to Activate this record ?')"
                         href="{{ $module_url_path.'/activate/'.base64_encode($subscriber['id']) }}" 
                         title="Deactive">
                         <i class="fa fa-lock"></i></a>
                        @endif
                     </td>
                  @endif
                  @if(array_key_exists('subscribers.delete', $arr_current_user_access))     
            
                     <td> 
                        
                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                              onclick="return confirm_action(this,event,'Do you really want to delete this record ?')"  title="Delete"
                              href="{{ $module_url_path.'/delete/'.base64_encode($subscriber['id'])}}" 
                              style="text-decoration:none;">
                              <i class="fa fa-trash-o"></i>
                        </a>   
                     </td
                  @endif   
                  </tr>

                  @endforeach
                @endif
                 
              </tbody>
            </table>
          </div>
        <div> </div>
         
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


