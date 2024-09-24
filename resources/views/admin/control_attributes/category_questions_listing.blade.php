      @extends('admin.layout.master')                


    @section('main_content')


    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">
    
    <style type="text/css">
    .divpagination
    {
      margin: 0;
      text-align: right!important;
      white-space: nowrap;
      
      color: #fff;
    }
    </style>


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
          <i class="fa fa-crosshairs"></i>
          <a href="{{ $module_url_path }}">{{ $module_title or ''}}</a>
          </span> 
          <span class="divider">
          <i class="fa fa-angle-right"></i>
          <i class="fa fa-crosshairs"></i>
          </span>
          <li class="active"> Manage {{ $page_title or ''}}</li>
       </ul>
    </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box">
            <div class="box-title">
              <h3>
                <i class="fa fa-crosshairs"></i>
                Manage {{ isset($page_title)?$page_title:"" }}
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
            
          <div class="btn-toolbar pull-right clearfix">
          @if( array_key_exists('control_attributes.create', $arr_current_user_access))                      
           <div class="btn-group">
             <a href="{{ $module_url_path.'/create'}}" class="btn btn-primary btn-add-new-records"  title="Add {{isset($module_title) ? str_singular($module_title) : ""}}">Add {{isset($module_title) ? str_singular($module_title) : ""}}</a>                      
          </div>
          @endif

          {{-- <div class="btn-group">
             <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                title="Add Category" 
                href="{{ $module_url_path.'/create'}}" 
                style="text-decoration:none;">
             <i class="fa fa-plus"></i>
             </a> 
          </div> 

          <div class="btn-group">
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
          </div>

          <div class="btn-group">    
             <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                title="Multiple Delete" 
                href="javascript:void(0);" 
                onclick="javascript : return check_multi_action('frm_manage','delete');"  
                style="text-decoration:none;">
             <i class="fa fa-trash-o"></i>
             </a>
          </div> --}}

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
          <br/>
          <div class="clearfix"></div>
          <div class="table-responsive" style="border:0">

            <input type="hidden" name="multi_action" value="" />

            <table class="table table-advance" id="table1"  >
              <thead>
                <tr>
                  {{-- <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th> --}}
                  <th>Subcategory</th>
                  <th>Category</th>
                  <th>Number of Attributes</th> 
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
                @if(isset($arr_data) && sizeof($arr_data)>0)
                  @foreach($arr_data as $key => $attribute)

                  <tr>
                    {{-- 
                    <td> 
                      <input type="checkbox" 
                             name="checked_record[]"  
                             value="{{ base64_encode($attribute['id']) }}" /> 
                    </td>
                    --}}
                    <td> {{ isset($attribute['title']) ? str_limit($attribute['title'], 150):'NA' }}</td> 

                    <td> {{ isset($attribute['parent_category']['title']) ? str_limit($attribute['parent_category']['title'], 150):'NA' }}</td> 
                  

                    <td><b> {{ isset($attribute['category_control_attributes']) ? count($attribute['category_control_attributes']):'NA'  }}</b></td>  

                    <td> 
                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/view/'.base64_encode($attribute['id']) }}"  title="Edit">
                        <i class="fa fa-eye" ></i>
                        </a>  
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
</div>
<!-- END Main Content -->

@stop