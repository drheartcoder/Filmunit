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
                <i class="fa fa-users"></i>                
            </span> 
            <li class="active">{{ isset($page_title)?$page_title:"" }}</li>
        </ul>
      </div>
    <!-- END Breadcrumb -->

    @if(isset($role) && $role != 'seller')
    <script type="text/javascript">
        window.history.pushState("", "", "{{$module_url_path}}" );
        // above code sets url as we want . only url segments , parameters can be changed. but not domain name.
    </script>
    @endif

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box {{ $theme_color }}">
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
          <div class="alert alert-danger show-alert" style="display: none">
          <span id="alert_message"></span>
          <button type="button" class="close" style="margin-top: 0px !important;padding: 0px !important;" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>

          {!! Form::open([ 'url' => url('/').'/admin/photos_and_footage/multi_action',
                                 'method'=>'POST',
                                 'enctype' =>'multipart/form-data',   
                                 'class'=>'form-horizontal', 
                                 'id'=>'frm_manage' 
                                ]) !!}

            {{ csrf_field() }}

            <div class="col-md-10">
            

            <div id="ajax_op_status">
                
            </div>
            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
          <div class="btn-toolbar pull-right clearfix">

          <div class="btn-group">
          <a href="{{ url($module_url_path.'/create') }}" class="btn btn-primary btn-add-new-records" title="Add Package">Add Package</a> 
          </div>         
            
          <div class="btn-group">
{{--                 <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                    title="Multiple Active/Unblock" 
                    href="javascript:void(0);" 
                    onclick="javascript : return check_multi_action('frm_manage','deactive');" 
                    style="text-decoration:none;">

                    <i class="fa fa-unlock"></i>
                </a>
 --}}
             
             </div>

              <div class="btn-group"> 
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Refresh" 
                   href="{{ $module_url_path }}"
                   style="text-decoration:none;">
                   <i class="fa fa-repeat"></i>
                </a> 
              </div>
              <br>
          </div>
          <br/>
          <div class="clearfix"></div>

           <div class="table-responsive" style="border:0">      
              <input type="hidden" name="multi_action" value="" />
                <table class="table table-advance"  id="table_module">
                  <thead>
                    <tr>  
                        <th><a class="sort-desc" href="#">Title </a>
                            <input type="text" name="q_title" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>
                        <th>Thumbnail</th> 
                        <th>Front Listing Number</th> 
                        <th>Total Items</th> 
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                  </thead>
               </table>
            </div>

          <div> </div>

          {!! Form::close() !!}
      </div>
  </div>
</div>

 <script type="text/javascript">
    
    //Save values of order listing
    $(document).on('focusout','.option_order_number',function(){
        $('.help-block').html('');
        var flag   = 0;
        var enc_id = $(this).attr('data-pack-id');
        var val    = $(this).val();
        
        var filter_number = /[^0-9]/;

        if($.trim(val)=="")
        {
          $(this).next('.help-block').html('This field is required');
          flag=1;
        }
        else if(!filter_number.test($.trim(val))=="")
        {
          $(this).next('.help-block').html('Only numbers are allowed');
          flag=1;
        }
        else if($.trim(val)>6)
        {
          $(this).next('.help-block').html('Number could not be greater than 6');
          flag=1;  
        }

        $('.option_order_number').each(function()
        {
          if($(this).val() == val && $(this).attr('data-pack-id')!= enc_id && $(this).val()!= "0")
          {
            flag=2;
          }
        });

        if(flag==1)
        {
          return false;
        }
        else if(flag==2)
        {
          $(this).next('.help-block').html('This number is already exists');
          return false;
        }
        else
        {
          $.ajax({
            type: "POST",
                  url: "{{$module_url_path}}/update_order",
                  data: {
                      '_token' : '{{ csrf_token() }}',
                      'enc_id' : enc_id,
                      'val'    : val
                  },
                  success: function(response){
                  if(response.status=='success')
                  { 
                    $('.help-block').html('');
                  }
                  else
                  {
                     window.scrollTo(0, 0);
                     $('.show-alert').show();
                     $('#alert_message').html(response.msg);
                  }
                }
          });      
        }
    });

      //Data Tables start here  
      var table_module = false;
      $(document).ready(function()
      {
        table_module = $('#table_module').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          bFilter: false,
          ajax: {
          'url':'{{ $module_url_path.'/manage_index_records'}}',
          'data': function(d)
            {
              d['column_filter[q_title]']            = $("input[name='q_title']").val()
            }
          },
          columns: [
          /*{
            render : function(data, type, row, meta) 
            {
            return '<input type="checkbox" '+
            ' name="checked_record[]" '+  
            ' value="'+row.enc_id+'"/>';
            },
            "orderable": false,
            "searchable":false
          },*/
          {data: 'title', "orderable": true, "searchable":false},          
          {
            render : function(data, type, row, meta) 
            {
              return row.build_thumbnail;
            },
            "orderable": true, "searchable":false
          },
          {
            render : function(data, type, row, meta) 
            {
              return row.build_order_box;
            },
            "orderable": false, "searchable":false
          },
          {
            render : function(data, type, row, meta) 
            {
              return row.build_order_btn;
            },
            "orderable": false, "searchable":false
          },
          {
            render : function(data, type, row, meta) 
            {
              return row.build_status_btn;
            },
            "orderable": false, "searchable":false
          },          
          {
            render : function(data, type, row, meta) 
            {
              return row.build_action_btn;
            },
            "orderable": false, "searchable":false
          }
          ]
        });

        $('input.column_filter').on( 'keyup click change', function () 
        {
            filterData();
        });

        $('#table_module').on('draw.dt',function(event)
        {
          var oTable = $('#table_module').dataTable();
          var recordLength = oTable.fnGetData().length;
          $('#record_count').html(recordLength);
        });
      });
 </script> 

<!-- END Main Content -->
<script type="text/javascript">
 $("#q_created_at").datepicker(
 {
     dateFormat: 'dd-mm-yy'
 });

  function show_details(url)
  {  
      window.location.href = url;
  }
 function filterData()
  {
    table_module.draw();
  }
</script>


@stop