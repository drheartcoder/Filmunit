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
      <i class="fa fa-newspaper-o"></i>      
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

            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
               {{ csrf_field() }}
               
               <div class="btn-toolbar pull-right clearfix">
                  {{-- <div class="btn-group">
                     <a href="{{ $module_url_path.'/create'}}" class="btn btn-primary btn-add-new-records"  title="Add CMS">Add skill</a>                      
                  </div> --}}
                  @if(array_key_exists('news.create', $arr_current_user_access))         
                   <div class="btn-group">
                     <a class="btn btn-primary  btn-add-new-records" 
                        title="Add {{ $module_title or ''}}" 
                        href="{{ $module_url_path.'/create'}}" 
                        style="text-decoration:none;">
                    Add {{$module_title}}
                     </a> 
                  </div>
                  @endif
                  @if(array_key_exists('news.update', $arr_current_user_access))         
                
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
                  </div>
                  @endif
                  @if(array_key_exists('news.delete', $arr_current_user_access))         
                
                  <div class="btn-group"> 
                     <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                        title="Refresh" 
                        href="{{ $module_url_path }}"
                        style="text-decoration:none;">
                     <i class="fa fa-repeat"></i>
                     </a> 
                  </div>
                  @endif
               </div>
               <br/>
               <br/>
               <div class="clearfix"></div>
               <div class="table-responsive" style="border:0">
                  <input type="hidden" name="multi_action" value="" />
                  <table class="table table-advance"  id="table1" >
                     <thead>
                        <tr>
                           @if(array_key_exists('news.update', $arr_current_user_access))        
                            <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" value="delete" /></th>
                           @endif
                           <th>{{ $module_title or ''}} Title</th>
                           <th>{{ $module_title or ''}} Description</th>
                           @if(array_key_exists('news.update', $arr_current_user_access))        
                             <th>Status</th>
                           @endif
                           @if(array_key_exists('news.update', $arr_current_user_access) || array_key_exists('news.delete', $arr_current_user_access) )<th>Action</th>
                           @endif
                        </tr>
                     </thead>
                     <tbody>
                        @if(isset($arr_data) && sizeof($arr_data)>0)
                          @foreach($arr_data as $data)
                          <tr>
                            @if(array_key_exists('news.update', $arr_current_user_access))       
                             <td> 
                                <input type="checkbox" 
                                   name="checked_record[]"  
                                   value="{{ base64_encode($data['id']) }}" /> 
                             </td>
                            @endif

                             <td> {{ isset($data['title'])?$data['title']:'' }} </td>
                            
                             <td> {{ isset($data['description'])? substr(strip_tags($data['description']), 0,60).'...':'' }} </td>
                            
                            @if(array_key_exists('news.update', $arr_current_user_access))       
                            

                             <td>
                                @if($data['is_active']==1)
                                <a href="{{ $module_url_path.'/deactivate/'.base64_encode($data['id']) }}" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,'Do you really want to deactivate this record ?')" title="Deactivate" ><i class="fa fa-unlock"></i></a>
                                @else
                                <a href="{{ $module_url_path.'/activate/'.base64_encode($data['id']) }}" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,'Do you really want to activate this record ?')" title="Activate" ><i class="fa fa-lock"></i></a>
                                @endif
                             </td>
                            @endif


                             <td> 
                             @if(array_key_exists('news.update', $arr_current_user_access))       
                            
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/edit/'.base64_encode($data['id']) }}"  title="Edit">
                                <i class="fa fa-edit" ></i>
                                </a>  
                                &nbsp;  
                                @endif
                                @if(array_key_exists('news.delete', $arr_current_user_access))       
                            
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/delete/'.base64_encode($data['id'])}}" 
                                onclick="return confirm_action(this,event,'Do you really want to delete this record ?')"
                                title="Delete">
                                <i class="fa fa-trash" ></i>
                                </a>   

                                @endif
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
<script type="text/javascript" src="{{ url('/assets/data-tables/latest') }}/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ url('/assets/data-tables/latest') }}/dataTables.bootstrap.min.js"></script>

@stop