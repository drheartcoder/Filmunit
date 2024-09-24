@extends('admin.layout.master')
@section('main_content')

<link rel="stylesheet" type="text/css" href="{{ url('/assets/assets/data-tables/latest/') }}/dataTables.bootstrap.min.css">


    <div class="page-title">
       <div>
          <h1><i class="fa fa-newspaper-o"></i>@if(isset($module_title)) {{ $module_title }} @endif</h1>
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
                    <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
                        {{ csrf_field() }}
                        <div class="col-md-10">
                            <div id="ajax_op_status"></div>
                            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
                            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
                        </div>
                        <div class="btn-toolbar pull-right clearfix">
                            <div class="btn-group">
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Add Newsletter" href="{{ $module_url_path.'/create'}}" style="text-decoration:none;">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Active" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','activate');" style="text-decoration:none;">
                                    <i class="fa fa-dot-circle-o"></i>
                                </a> 
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Deactive" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','deactivate');" style="text-decoration:none;">
                                    <i class="fa fa-circle-o"></i>
                                </a>                  
                            </div>
                            <div class="btn-group">    
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Delete" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','delete');" style="text-decoration:none;">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <div class="btn-group"> 
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" href="{{ $module_url_path }}"style="text-decoration:none;">
                                    <i class="fa fa-repeat"></i>
                                </a> 
                            </div>
                        </div>
                        
                        <br/><br/>
                        <div class="clearfix"></div>
                        
                        <div class="table-responsive" style="border:0">
                            <input type="hidden" name="multi_action" value="" />
                                <table class="table table-advance"  id="table1" >
                                    <thead>
                                        <tr>
                                           <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" value="delete" /></th>
                                           <th>News Title</th>
                                           <th>News Subject</th>
                                           <th>Status</th>
                                           <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($arr_news_letter as $news)
                                        <tr>
                                            <td> 
                                                <input type="checkbox" name="checked_record[]" class="checked_record" value="{{ base64_encode($news['id']) }}" /> 
                                            </td>
                                            
                                            <td> {{ isset($news['title'])?$news['title']:'' }} </td>
                                            
                                            <td> {{ isset($news['subject'])?$news['subject']:'' }} </td>
                                            
                                            
                                             <td>
                                                @if($news['is_active']==1)
                                                  <a class="btn btn-warning btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                                                  onclick="javascript:return confirm_deactivate()" 
                                                  style="text-decoration:none;" 
                                                  href="{{ $module_url_path.'/deactivate/'.base64_encode($news['id']) }}" 
                                                  title="Active">
                                                  <i class="fa fa-dot-circle-o"></i></a>
                                                @else
                                                 <a class="btn btn-warning btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                                                 style="text-decoration:none;" 
                                                 onclick="javascript:return confirm_activate()"
                                                 href="{{ $module_url_path.'/activate/'.base64_encode($news['id']) }}" 
                                                 title="Deactive">
                                                 <i class="fa fa-circle"></i></a>
                                                @endif
                                             </td>

                                            
                                            <td> 
                                                <a class="btn btn-primary btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Edit" href="{{ $module_url_path.'/edit/'.base64_encode($news['id']) }}" style="text-decoration:none;">
                                                    <i class="fa fa-pencil"></i>
                                                </a>&nbsp; 
                                                <a class="btn btn-success btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="View" href="{{ $module_url_path.'/view/'.base64_encode($news['id']) }}" style="text-decoration:none;">
                                                    <i class="fa fa-eye"></i>
                                                </a>&nbsp; 
                                                <a class="btn btn-danger btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="javascript:return confirm_delete()" title="Delete" href="{{ $module_url_path.'/delete/'.base64_encode($news['id'])}}" style="text-decoration:none;">
                                                    <i class="fa fa-trash-o"></i>
                                                </a> 
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
<!-- END Main Content -->

<script type="text/javascript" src="{{ url('/assets/assets/data-tables/latest') }}/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ url('/assets/assets/data-tables/latest') }}/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
   function confirm_delete()
   {
      if(confirm('Do you really want to delete this record ?'))
      {
       return true;
      }
      return false;
   }

   function confirm_activate()
   {
     var choice = confirm('Do you really want to activate ?');
          if(choice === false) 
          {
              return false;
          }
   }

   function confirm_deactivate()
   {
     var choice = confirm('Do you really want to deactivate ?');
      if(choice === false) 
      {
          return false;
      }
  }

   
   function check_multi_action(frm_id,action)
   {
     var frm_ref = jQuery("#"+frm_id);
     var checked_record = $('.checked_record').is(':checked');
      
      if (checked_record == false) 
      {
          alert('Please Select Atleast 1 Record');
          return false;
      }
     else if(jQuery(frm_ref).length && action!=undefined && action!="")
     {
      
      if(action == 'delete')
       {
          var choice = confirm('Do you really want to delete ?');
          if(choice === false) 
          {
              return false;
          }
       }

       if(action == 'activate')
       {
          var choice = confirm('Do you really want to activate ?');
          if(choice === false) 
          {
              return false;
          }
       }

       if(action == 'deactivate')
       {
          var choice = confirm('Do you really want to deactivate ?');
          if(choice === false) 
          {
              return false;
          }
       }
       
       /* Get hidden input reference */
       var input_multi_action = jQuery('input[name="multi_action"]');
       
       if(jQuery(input_multi_action).length)
       {
         /* Set Action in hidden input*/
         jQuery('input[name="multi_action"]').val(action);
   
         /*Submit the referenced form */
         jQuery(frm_ref)[0].submit();
   
       }
       else
       {
         console.warn("Required Hidden Input[name]: multi_action Missing in Form ")
       }
     }
     else
     {
         console.warn("Required Form[id]: "+frm_id+" Missing in Current Page ")
     }
   }
   
</script>
@stop