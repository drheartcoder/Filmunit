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
                <i class="fa fa-list-alt"></i>                
            </span> 
            <li class="active">{{ $module_title or ''}}</li>
           
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

          {{-- <div class="btn-group">
          <a href="{{ url($module_url_path.'/create') }}" class="btn btn-primary btn-add-new-records" title="Add New Category/Sub Category">Add New Category/Sub Category</a> 
          </div>
  
          <div class="btn-group">
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
               title="Multiple Active/Unblock" 
               href="javascript:void(0);" 
               onclick="javascript : return check_multi_action('checked_record[]','frm_manage','activate');" 
               style="text-decoration:none;">
              <i class="fa fa-unlock">
              </i>
            </a> 
          </div>
          <div class="btn-group">
            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
               title="Multiple Deactive/Block" 
               href="javascript:void(0);" 
               onclick="javascript : return check_multi_action('checked_record[]','frm_manage','deactivate');"  
               style="text-decoration:none;">
              <i class="fa fa-lock">
              </i>
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
          </div> --}} 
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
          <br/><br/>
          <div class="clearfix"></div>

          <div class="table-responsive" style="border:0">

            <input type="hidden" name="multi_action" value="" />

            <table class="table table-advance"  id="table1">
              <thead>
                <tr>
                  {{-- <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th> --}}
                  <th>Category</th> 
                  @if(array_key_exists('subcategories.list', $arr_current_user_access))         
                    <th>Sub Category</th> 
                  @endif
                  
                  @if(array_key_exists('categories.update', $arr_current_user_access))         
                   <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
          
                @if(sizeof($arr_category)>0)
                  @foreach($arr_category as $category)
              
                  
                  <tr>
                    {{-- <td> 
                      <input type="checkbox"  name="checked_record[]" value="{{ base64_encode($category['id']) }}" /> 
                    </td> --}}
                    <td > {{ $category['title'] }} </td> 
                    @if(array_key_exists('subcategories.list', $arr_current_user_access))         
                
                    <td>
                        <a href="{{ url($module_url_path.'/sub_categories/'.base64_encode($category['id'])) }}" class="btn btn-success" title="View Sub Categories">View </a>
                    </td>
                    @endif
                    @if(array_key_exists('categories.update', $arr_current_user_access))         
                
                    <td> 
                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip call_loader" href="{{ url($module_url_path.'/edit/'.base64_encode($category['id'])) }}" title="Edit">
                          <i class="fa fa-edit" ></i>
                        </a>  
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


