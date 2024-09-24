    @extends('admin.layout.master')                


    @section('main_content')

    <style type="text/css">
    .divpagination
    {
      margin: 0;
      text-align: right!important;
      white-space: nowrap;
      
      color: #fff;
    }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">


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
          <a href="{{ $module_url_path }}">Manage {{ $module_title or ''}}</a>
          </span> 
          <span class="divider">
          <i class="fa fa-angle-right"></i>
          <i class="fa fa-crosshairs"></i>
          </span>
          <li class="active">{{ $page_title or ''}} {{isset($arr_category['title']) ? ' For '.$arr_category['title'] : '' }}</li> 
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
                {{ isset($page_title)?$page_title:"" }}{{isset($arr_category['title']) ? ' For '.$arr_category['title'] : '' }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
            </div>
        <div class="box-content">
          
          @include('admin.layout._operation_status')

          <div id="status_msg"></div>

          <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
               {{ csrf_field() }}
         
          <div class="btn-toolbar pull-right clearfix">

          {{-- @if( array_key_exists('control_attributes.create', $arr_current_user_access))                      
           <div class="btn-group">
             <a href="{{ $module_url_path.'/create'}}" class="btn btn-primary btn-add-new-records"  title="Add {{isset($module_title) ? str_singular($module_title) : ""}}">Add {{isset($module_title) ? str_singular($module_title) : ""}}</a>                      
          </div>
          @endif --}}

          {{-- <div class="btn-group">
             <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                title="Add Category" 
                href="{{ $module_url_path.'/create'}}" 
                style="text-decoration:none;">
             <i class="fa fa-plus"></i>
             </a> 
          </div> --}}

          @if( array_key_exists('control_attributes.update', $arr_current_user_access))                      
          
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

          @endif

          <div class="btn-group"> 
             <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                title="Refresh" 
                onclick="window.location.reload();" 
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
                  <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th>
                  <th>Label</th> 
                  <th>Control type</th> 
                  <th>Order</th> 
                  <th>Status</th> 
                  <th>Action</th>
                  <th>Is Required ?</th> 
                </tr>
              </thead>
              <tbody>


                @if(isset($arr_attributes) && sizeof($arr_attributes)>0)
                  @foreach($arr_attributes as $key => $attribute)
                  <tr>
                    <td> 
                      <input type="checkbox" 
                             name="checked_record[]"  
                             value="{{ base64_encode($attribute['id']) }}" /> 
                    </td>
                    <td> {{ isset($attribute['label']) ? str_limit($attribute['label'], 100):'NA' }}</td> 
                      
                    <?php 
                      $control_type = "NA";

                      if(isset($attribute['control_type']))
                      {
                        if($attribute['control_type'] == 'TEXT')
                        {
                          $control_type = 'Textbox';
                        } 
                        else if($attribute['control_type'] == 'TEXTAREA')
                        {
                          $control_type = 'Textarea';
                        }
                        else if($attribute['control_type'] == 'CHECKBOX')
                        {
                          $control_type = 'Checkbox';
                        }
                        else if($attribute['control_type'] == 'RADIO')
                        {
                          $control_type = 'Radio Button';
                        }
                        else if($attribute['control_type'] == 'DROPDOWN')
                        {
                          $control_type = 'Dropdown';
                        }
                      }

                    ?>

                    <td> {{ $control_type or 'NA' }}</td>  

                      
                    <td style="width:80px;"> 
                       <input type="text" class="form-control" style="background-color: white;width:120px;" 
                              value="{{ $attribute['order_index'] }}"
                              data-attribute-id="{{ $attribute['id'] }}" 
                              data-subcategory-id="{{ $attribute['subcategory_id'] or '' }}" 
                              onblur="save_order(this);" 
                              id="order_index" />
                    </td>
                    
                    <td>
                        @if($attribute['is_active']==1)
                             <a href="{{ $module_url_path.'/deactivate/'.base64_encode($attribute['id']) }}" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,'Do you really want to deactivate this record ?')" title="Deactivate" ><i class="fa fa-unlock"></i></a>
                        @else
                             <a href="{{ $module_url_path.'/activate/'.base64_encode($attribute['id']) }}" class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="return confirm_action(this,event,'Do you really want to activate this record ?')" title="Activate" ><i class="fa fa-lock"></i></a>
                        @endif
                     </td>
                     
                     <td> 
                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{ $module_url_path.'/edit/'.base64_encode($attribute['id']) }}"  title="Edit">
                        <i class="fa fa-edit" ></i>
                        </a>  
                        &nbsp;  
                        {{-- <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" onclick="javascript: return confirm_delete();" href="{{ $module_url_path.'/delete/'.base64_encode($attribute['id']) }}"  title="Delete">
                        <i class="fa fa-trash" ></i>
                        </a> --}}            
                     </td>

                     <td>
                        <label class="radio">
                       <input type="radio" name="is_required_{{ $attribute['id'] }}"
                        @if(isset($attribute['is_required']) && $attribute['is_required']=="true") checked="checked" @endif id="yes_skip_id_{{ $attribute['id'] or '' }}"  onchange="checkIsRequired(this.value,{{ $attribute['id'] }})" value="true"/>Yes
                        </label>
                        <label class="radio">
                          <input type="radio" name="is_required_{{ $attribute['id'] }}" @if(isset($attribute['is_required']) && $attribute['is_required']=="false") checked="checked" @endif id="no_skip_id_{{ $attribute['id'] }}"   onchange="checkIsRequired(this.value,{{ $attribute['id'] }})" value="false"/>No
                        </label> 
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

<script type="text/javascript">

  function save_order(elem)
  {
      var url       = "{{ $module_url_path }}";
      var order_id  = jQuery(elem).val();        
      var attribute_id = jQuery(elem).attr("data-attribute-id");
      var subcategory_id = jQuery(elem).attr("data-subcategory-id");
    
      if(order_id == "")
      {
        alert("Please Enter Order");
        document.focus(elem);
        return false;
      } 
      else { 
     
            jQuery.ajax({
                        url:url+'/save_order',
                        type:'GET',
                        dataType:'json',
                        data :{ 'order_id' : order_id ,'attribute_id':attribute_id ,'subcategory_id':subcategory_id} ,
                        success:function(response)
                        {  
                            if(response.status=="SUCCESS")
                            {

                            }
                            if(response.status=="DUPLICATE")
                            {
                                showAlert(response.msg, 'warning');
                            }
                             if(response.status=="NUMERIC")
                            {
                                showAlert(response.msg, 'warning');
                            }
                            return false;
                        }    
            });
      }
    } 

  function checkIsRequired(is_required,attribute_id)
  {
      var url   = "{{ $module_url_path }}/confirm_is_required"
      var token = $('input[name="_token"]').val(); 
      $.ajax({
               url: url,
               data:{ is_required:is_required,attribute_id:attribute_id,_token:token },
               dataType:'json',
               type:'POST',
               success:function(res)
               {  
                  if(res.status == 'success')
                  {
                    var str_html = makeStatusMessageHtml('success',res.msg);
                    $('#status_msg').html(str_html);

                    setTimeout(function() {
                      $('#status_msg').html("");
                    }, 3000);
                    
                  } 
                  else if(res.status == 'error')
                  {
                    var str_html = makeStatusMessageHtml('danger',res.msg);
                    $('#status_msg').html(str_html)
                    setTimeout(function() {
                      $('#status_msg').html("");
                    }, 3000);
                    
                  }
               }
        });
  }


  function makeStatusMessageHtml(status, message)
  {
      str = '<div class="alert alert-'+status+'">'+
      '<a aria-label="close" data-dismiss="alert" class="close" href="#">'+'×</a>'+message+
      '</div>';
      return str;
  }

</script>

@stop                    


