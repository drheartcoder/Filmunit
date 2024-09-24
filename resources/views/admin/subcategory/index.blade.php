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
                <i class="fa fa-th-large"></i>                
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
          
          <form class="form-horizontal" id="frm_manage" method="POST" action="{{ url($module_url_path.'/multi_action') }}">

            {{ csrf_field() }}

            <div class="col-md-10">
            

            <div id="ajax_op_status">
                
            </div>
            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
          <div class="btn-toolbar pull-right clearfix">

          @if(array_key_exists('subcategories.create', $arr_current_user_access))         
                 
          <div class="btn-group">
          <a href="{{ url($module_url_path.'/create') }}" class="btn btn-primary btn-add-new-records" title="Add Subcategory">Add Subcategory</a> 
          </div>
          @endif
          {{-- <div class="btn-group">
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                title="Multiple Active/Unblock" 
                href="javascript:void(0);" 
                onclick="javascript : return check_multi_action('frm_manage','activate');" 
                style="text-decoration:none;">

                <i class="fa fa-unlock"></i>
            </a> 
          </div>
          <div class="btn-group">
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
               title="Multiple Deactive/Block" 
               href="javascript:void(0);" 
               onclick="javascript : return check_multi_action('frm_manage','deactivate');"  
               style="text-decoration:none;">
                <i class="fa fa-lock"></i>
            </a> 
          </div> --}}
          {{-- <div class="btn-group">
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
               title="Multiple Delete" 
               href="javascript:void(0);" 
               onclick="javascript : return check_multi_action('checked_record[]','frm_manage','delete');"  
               style="text-decoration:none;">
              <i class="fa fa-trash-o">
              </i>
            </a>
          </div>  --}}
      
            <div class="btn-group"> 
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
          <div class="clearfix"></div>
          <div class="table-responsive" style="border:0">

            <input type="hidden" name="multi_action" value="" />

            <table class="table table-advance" id="table1" >
              <thead>
                <tr>
                  {{-- <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th> --}}
                  <th>Subcategory</th>
                  <th>Category</th>
                  {{-- <th>Status</th>  --}}
                  @if(array_key_exists('subcategories.update', $arr_current_user_access))         
                 
                  <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @if(sizeof($arr_data)>0)
                  @foreach($arr_data as $result)
                  <tr>
{{--                     <td> 
                      <input type="checkbox" 
                             name="checked_record[]"  
                             value="{{ base64_encode($result['id']) }}" /> 
                    </td> --}}
                    <td> {{ $result['title'] }} </td> 
                    <td> {{ $result['parent_category']['title'] }} </td>  
                     {{-- <td>
                  @if($result['is_active']==1)
                     <a href="{{ $module_url_path.'/deactivate/'.base64_encode($result['id']) }}" class="btn btn-sm btn-success show-tooltip" onclick="return confirm_action(this,event,'Do you really want to deactivate this record ?')" title="Deactivate" ><i class="fa fa-unlock"></i></a>  
                   
                   @else
                    
                    <a href="{{ $module_url_path.'/activate/'.base64_encode($result['id']) }}" class="btn btn-sm btn-danger show-tooltip" onclick="return confirm_action(this,event,'Do you really want to activate this record ?')" title="Activate" ><i class="fa fa-lock"></i></a>
                    
                   @endif

                </td> --}}
                 @if(array_key_exists('subcategories.update', $arr_current_user_access))         
                 
                    <td> 
                        <a a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip call_loader" href="{{ url($module_url_path.'/edit/'.base64_encode($result['id'])) }}" title="Edit">
                          <i class="fa fa-edit" ></i>
                        </a>  
                     
                        {{-- &nbsp;  
                        <a href="{{ url($module_url_path.'/delete/'.base64_encode($result['id'])) }}"  
                           onclick="return confirm_delete();" 
                           title="Delete">
                          <i class="fa fa-trash" ></i>  
                        </a>   --}}
                    </td>
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
function show_details(url)
    { 
        window.location.href = url;
    }
</script>     

@stop                    


