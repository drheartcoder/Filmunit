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
         <i class="fa fa-home"></i>
         <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
      </li>
      <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$module_icon or ''}}"></i>     
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
          {{ $page_title or ''}}
        </h3>
        <div class="box-tool">
          <a data-action="collapse" href="#"></a>
          <a data-action="close" href="#"></a>
        </div>
      </div><!--box-title-->
      <div class="box-content">

        @include('admin.layout._operation_status')
       
        <form action="{{ $module_url_path.'/multi_action' }}" method="post" enctype="multipart/form-data" class="form-horizontal" name="frm_manage" id="frm_manage">
          {{ csrf_field() }}
          <div class="col-md-10">
          </div>
          
          <div class="btn-toolbar pull-right clearfix">
           @if(array_key_exists('testimonial.create', $arr_current_user_access)) 
             <div class="btn-group"> 
                 <a href="{{ $module_url_path.'/create' }}" class="btn btn-primary btn-add-new-records" title="Add {{ str_singular($module_title) }}">Add {{ str_singular($module_title) }}</a> 
             </div>
           @endif
           @if(array_key_exists('testimonial.update', $arr_current_user_access)) 
          
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
        <br/>
        <div class="clearfix">
      </div><!--box-content-->
        <div class="table-responsive" style="border:0">
          <table class="table table-advance"  id="table_module" >
                <input type="hidden" name="multi_action" id="multi_action" value="" />
            <thead>
              <tr>
               @if(array_key_exists('testimonial.update', $arr_current_user_access)) 
          
                 <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th>
               @endif
                  <th>User Name</th> 
                  {{-- <th>User Post</th> --}}
                  <th>Description</th>
                  <th>Image</th>
                 @if(array_key_exists('testimonial.update', $arr_current_user_access)) 
                   <th>Status</th>
                 @endif
                  {{-- <th style="text-align:center;">Translations</th> --}}
                 @if(array_key_exists('testimonial.update', $arr_current_user_access) || array_key_exists('testimonial.delete', $arr_current_user_access)) 
                
                  <th>Action</th>
                  @endif
              </tr>
            </thead>
            <tbody>
                @if(sizeof($arr_data)>0)
                  @foreach($arr_data as $data)
                  @if(isset($data['translations']) && count($data['translations']) >  0)

                  <?php 
                      $user_image = isset($data['user_image']) ? $data['user_image'] : "";                      
                  ?>

                  <tr>
                   @if(array_key_exists('testimonial.update', $arr_current_user_access)) 
          
                    <td> 
                      <input type="checkbox" 
                             name="checked_record[]"  
                             value="{{ base64_encode($data['id']) }}" /> 
                    </td>
                    @endif
                      <td> {{ $data['translations']['en']['user_name'] or ''}} </td> 
                      {{-- <td> {{ $data['translations']['en']['user_post'] or '' }} </td> --}}
                      <td> {{ str_limit($data['translations']['en']['description'], 100)}} </td>

                      <td> <img src="{{ get_resized_image($user_image,config('app.project.img_path.testimonial_images') , 150, 150) }}" style="height:50px;width: 50px;">
                      </td>
                    @if(array_key_exists('testimonial.update', $arr_current_user_access)) 
          
                    <td>
                  
                      @if($data['is_active']==1)
                      <a href="{{ $module_url_path.'/deactivate/'.base64_encode($data['id']) }}" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,'Do you really want to deactivate this record ?')" title="Deactivate" ><i class="fa fa-unlock"></i></a>
                      @else
                      <a href="{{ $module_url_path.'/activate/'.base64_encode($data['id']) }}" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,'Do you really want to activate this record ?')" title="Activate" ><i class="fa fa-lock"></i></a>
                      @endif

                    </td> 
                  @endif

                    <!-- Translations  -->
                  {{--   <td style="text-align:center;">
                       
                       <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#see_avail_translation_{{ $data['id']}}"  title="View Translations">View</button>

                        <!-- Modal -->
                        <div id="see_avail_translation_{{ $data['id']}}" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-sm">

                            <!-- Modal content-->
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Translation Available In</h4>
                              </div>
                              <div class="modal-body">
                                   @if(isset($data['arr_translation_status']) && sizeof($data['arr_translation_status'])>0)
                                    <ul style="list-style-type: none;">
                                      @foreach($data['arr_translation_status'] as $translation_status)
                                        @if($translation_status['is_avail']==1)
                                          <li>
                                            <h5>
                                              <i class="fa fa-check text-success"></i>
                                              {{ $translation_status['title'] }}
                                            </h5>
                                          </li>
                                        @else
                                          <li>
                                            <h5>
                                              <i class="fa fa-times text-danger"></i> 
                                              {{ $translation_status['title'] }}
                                            </h5>  
                                          </li>
                                        @endif       
                                      @endforeach
                                    </ul>
                                   @endif
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div><!--modal content-->
                          </div><!--modal-dialog modal-sm-->
                        </div><!--see_avail_translation_-->
                    </td> --}}
                    
                    <td> 
                     @if(array_key_exists('testimonial.update', $arr_current_user_access) ) 
          
                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/edit/'.base64_encode($data['id']) }}"  title="Edit">
                        <i class="fa fa-edit" ></i>
                        </a>  
                        &nbsp;  
                        @endif
                         @if( array_key_exists('testimonial.delete', $arr_current_user_access)) 
          
                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/delete/'.base64_encode($data['id'])}}" 
                        onclick="return confirm_action(this,event,'Do you really want to delete this record ?')"
                        title="Delete">
                        <i class="fa fa-trash" ></i>
                        </a>
                        @endif  
                    </td>
                    
                  </tr>
                  @endif
                  @endforeach
                @endif
            </tbody>
          </table>
        </div><!--table res-->
           </form>
      </div>
    </div>
  </div>

<!-- END Main Content -->
@stop                    


