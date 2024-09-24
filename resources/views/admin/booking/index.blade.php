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
                <i class="fa fa-dollar"></i>               
            </span> 
            <li class="active">{{ isset($module_title)?$module_title:"" }}</li>            
        </ul>
      </div>
    <!-- END Breadcrumb -->

    @if(isset($role) && $role != 'photographer')
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
          <?php $buyerid='';
                $buyerid = Request::get('buyerid');?> 
          <input type="hidden" id="buyer_id" name="buyer_id" value="{{Request::segment(4)}}">
          @include('admin.layout._operation_status')  
          
            <div class="row pull-left" >
              <form name="frmexport" id="frmexport" action="{{ $module_url_path }}/export" method="GET">   
                 {{ csrf_field() }}
                  <div class="col-md-3">
                        <div class="input-group date ">
                              <input type="text" id="order_number"  name="order_number" placeholder="Search Order Number" class="form-control" />
                                <div id="error_report_end" style="color:red;"></div>
                          </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group date ">
                              <input type="text" id="q_purchaser_name" onblur ="filterData();" name="purchaser_name" placeholder="Search Purchaser Name" class="form-control" />
                                <div id="error_report_end" style="color:red;"></div>
                          </div>
                    </div>
                  <div class="col-md-3">
                       <div  class="input-group date">
                          <span class="input-group-addon start_date" for="report_start" ><i class="fa fa-calendar" ></i></span>
                          <input type="text" value="" placeholder="Start Booking Date" size="16" class="form-control date-picker" name="report_start" id="report_start">
                          <div id="error_report_start" style="color:red;"></div>
                       </div>
                    </div>                  
                    <div class="col-md-3">
                        <div class="input-group date ">
                              <span class="input-group-addon end_date" for="report_end" ><i class="fa fa-calendar"></i></span>
                              <input type="text" value="" placeholder="End Booking Date" size="16" class="form-control date-picker" name="report_end" id="report_end">
                                <div id="error_report_end" style="color:red;"></div>
                          </div>
                    </div>
                    <div style="clear:both;"></div>
                    <br/>
                    <div class="col-md-3">
                        <div class="input-group date ">
                              <input type="text" id="transaction_id"  name="transaction_id" placeholder="Search Transaction ID" class="form-control" />
                                <div id="error_report_end" style="color:red;"></div>
                          </div>
                    </div>
                     <div class="col-md-3">
                        <div class="input-group date ">
                              <input type="text" id="order_amount"  name="order_amount" placeholder="Search Order Amount" class="form-control" />
                                <div id="error_report_end" style="color:red;"></div>
                          </div>
                    </div>
                     <div class="col-md-3">
                        <div class="input-group date ">
                              <input type="text" id="status"  name="status" placeholder="Search Status" class="form-control" />
                                <div id="error_report_end" style="color:red;"></div>
                          </div>
                    </div>
                    
               
                    <div class="col-md-2">
                      <div class="btn-group">
                        <button type="submit"  class="btn btn-primary export" title="Export Report">Export Report</button></div>
                    </div>
              </form>

              <hr/>
           </div>
          

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
              <div class="btn-group"> 
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Refresh" 
                   href="javascript:void(0)"
                   onclick="javascript:location.reload();"
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
                         <th><a class="sort-desc sort-asc" href="#">Order Number </a>
                            <input type="text" name="q_order_number" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc sort-asc" href="#">Purchaser name  </a>

                            <input type="text" id="q_purchaser_name" onblur ="filterData();" name="q_purchaser_name" placeholder="Search" class="search-block-new-table column_filter" />                         
                        </th> 

                        <th><a class="sort-desc sort-asc" href="#">Booking Date </a>
                            <input id="date" type="text" name="q_booking_date" placeholder="Search" class="search-block-new-table column_filter" onchange="filterData();"/>
                        </th> 

                         <th><a class="sort-desc sort-asc" href="#">Transaction ID </a>
                            <input type="text" name="q_transaction_id" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc sort-asc" href="#">Order Amount </a>
                            <input type="text" name="q_order_amount" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>                         
                        <th><a class="sort-desc sort-asc" href="#">Transaction Type</a>
                            <input type="text" name="q_transaction_type" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>
                        <th><a class="sort-desc sort-asc" href="#">Status</a>
                            <input type="text" name="q_status" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>
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
<!-- END Main Content -->
<script>
$(function() {
    $( "#q_purchaser_name" ).autocomplete({
        source: '{{ $module_url_path.'/usertype'}}',
        change: function() {
          filterData();
        }
    });
});


</script>
<script type="text/javascript">
    
    function show_details(url)
    {  
        window.location.href = url;
    } 

    $('#date').datepicker({ 
      dateFormat: "dd-mm-yy",
      setDate: new Date()
    });
  /*Script to show table data*/

  var table_module = false;
  $(document).ready(function()
  {
    table_module = $('#table_module').DataTable({ 
      processing: true,
      serverSide: true,
      autoWidth: false,
      bFilter: false ,
      ajax: {
      'url':'{{ $module_url_path.'/buyer/get_records?buyerid='}}{{$buyerid or ''}}',
       'data': function(d)
        {
          d['column_filter[q_order_number]']         = $("input[name='q_order_number']").val()
          d['column_filter[q_purchaser_name]']       = $("input[name='q_purchaser_name']").val()
          d['column_filter[q_booking_date]']         = $("input[name='q_booking_date']").val()
          d['column_filter[q_transaction_id]']       = $("input[name='q_transaction_id']").val()          
          d['column_filter[q_order_amount]']         = $("input[name='q_order_amount']").val()
          d['column_filter[q_transaction_type]']       = $("input[name='q_transaction_type']").val()          
          d['column_filter[q_status]']         = $("input[name='q_status']").val()
        }
      },
      columns: [
      {data: 'order_number', "orderable": true, "searchable":false},
      {data: 'user_name', "orderable": true, "searchable":false},
      {data: 'booking_date', "orderable": true, "searchable":false},
      {data: 'transaction_id', "orderable": true, "searchable":false},
      {data: 'order_amount', "orderable": true, "searchable":false},
      {data: 'transaction_type', "orderable": true, "searchable":false},
      {data: 'status', "orderable": true, "searchable":false},
      {
        render : function(data, type, row, meta) 
        {
          return row.build_action_btn;
        },
        "orderable": false, "searchable":false
      }
      ]
    });

    $('input.column_filter').on( 'keyup click', function () 
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

  function filterData()
  {
    console.log("im in here");
    table_module.draw();
  }

</script>

@stop