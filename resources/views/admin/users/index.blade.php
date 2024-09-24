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
          
          {!! Form::open([ 'url' => $module_url_path.'/multi_action',
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

          {{--
          <div class="btn-group">
          <a href="{{ $module_url_path.'/create'}}" class="btn btn-primary btn-add-new-records">Add New {{ str_singular($module_title) }}</a> 
          </div> 
          --}}
         
            
          <div class="btn-group">
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                    title="Multiple Active/Unblock" 
                    href="javascript:void(0);" 
                    onclick="javascript : return check_multi_action('frm_manage','unblock');" 
                    style="text-decoration:none;">

                    <i class="fa fa-unlock"></i>
                </a> 
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Multiple Deactive/Block" 
                   href="javascript:void(0);" 
                   onclick="javascript : return check_multi_action('frm_manage','Block');"  
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
             
             </div>
              {{-- 
              <div class="btn-group">  
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Multiple Delete" 
                   href="javascript:void(0);" 
                   onclick="javascript : return check_multi_action('checked_record[]','frm_manage','delete');"  
                   style="text-decoration:none;">
                   <i class="fa fa-trash-o"></i>
                </a>
              </div>
              --}}

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
                        <th style="width: 18px; vertical-align: initial;"><input type="checkbox"/></th>

                        <th><a class="sort-desc sort-asc" href="#">Name </a>
                            <input type="text" name="q_name" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc sort-asc" href="#">Email </a>
                            <input type="text" name="q_email" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc sort-asc" href="#">Sign Up Date </a>
                            <input type="text" name="q_created_at" id="q_created_at" placeholder="Search" class="search-block-new-table column_filter" onselect="return false;" />
                        </th> 

                        <th>Orders Count</th> 
                        @if(\Request::get('u')=='seller')
                        <th id="media_upload">Total Media Uploaded</th> 
                        @endif
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
   
      /*Script to show table data*/
      
      /*var url = '{{ $module_url_path.'/get_records?role='}}{{$role or ''}}{{'&sellerid='}}{{ $sellerid or "" }}';
      var urld = url.replace(/&amp;/g, '&');*/
      //alert(urld);

      
      var table_module = false;
      $(document).ready(function()
      {
        var role = '{{\Request::get('u')}}';
        
        if(role=='seller')
        {
          table_module = $('#table_module').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          bFilter: false,
          ajax: {
          'url':'{{ $module_url_path.'/get_records?role='}}{{$role or ''}}',
          'data': function(d)
            {
              d['column_filter[q_name]']            = $("input[name='q_name']").val()
              d['column_filter[q_email]']           = $("input[name='q_email']").val()
              d['column_filter[q_created_at]']      = $("input[name='q_created_at']").val()
            }
          },
          columns: [
          {
            render : function(data, type, row, meta) 
            {
            return '<input type="checkbox" '+
            ' name="checked_record[]" '+  
            ' value="'+row.enc_id+'"/>';
            },
            "orderable": false,
            "searchable":false
          },
          {data: 'user_name', "orderable": true, "searchable":false},
          {data: 'email', "orderable": true, "searchable":false},
          {data: 'created_at', "orderable": true, "searchable":false},
          {
            render : function(data, type, row, meta) 
            {
              return row.BuyerOrderCount;
            },
            "orderable": false, "searchable":false
          },
          {
            render : function(data, type, row, meta) 
            {
              return row.mediaCount;
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
        
        }
        else
        {
          table_module = $('#table_module').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          bFilter: false,
          ajax: {
          'url':'{{ $module_url_path.'/get_records?role='}}{{$role or ''}}',
          'data': function(d)
            {
              d['column_filter[q_name]']            = $("input[name='q_name']").val()
              d['column_filter[q_email]']           = $("input[name='q_email']").val()
              d['column_filter[q_created_at]']      = $("input[name='q_created_at']").val()
            }
          },
          columns: [
          {
            render : function(data, type, row, meta) 
            {
            return '<input type="checkbox" '+
            ' name="checked_record[]" '+  
            ' value="'+row.enc_id+'"/>';
            },
            "orderable": true,
            "searchable":false
          },
          {data: 'user_name', "orderable": true, "searchable":false},
          {data: 'email', "orderable": true, "searchable":false},
          {data: 'created_at', "orderable": true, "searchable":false},
          {
            render : function(data, type, row, meta) 
            {
              return row.BuyerOrderCount;
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

        }

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