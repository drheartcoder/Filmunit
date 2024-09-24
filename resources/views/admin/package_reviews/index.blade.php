    @extends('admin.layout.master')                


    @section('main_content')

    <!-- BEGIN Page Title -->
     <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">

     <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/star-rating.css">

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
                <a href="{{ $module_url_path }}">{{ $page_title or ''}}</a>
            </span> 
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                  <i class="fa fa-list"></i>
            </span>
            <li class="active">{{ isset($page_title)?$page_title:"" }}</li>
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
                        <th style="width: 18px;vertical-align: initial;"><input type="checkbox"/></th>

                        <th><a class="sort-desc" href="#">Package Title</a>
                            <input type="text" name="q_package_title" placeholder="Search" value="{{ \Request::get('t')}}" class="search-block-new-table column_filter" />
                        </th>

                        <th><a class="sort-desc" href="#">Review</a>
                            <input type="text" name="q_review" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">Rating </a>
                            <input type="text" name="q_rating" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">User</a>
                            <input type="text" name="q_user_name" placeholder="Search"  value="{{ \Request::get('u')}}" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">Photographer</a>
                            <input type="text" name="q_photographer" placeholder="Search" class="search-block-new-table column_filter"  value="{{ \Request::get('p')}}" />
                        </th>
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

<!-- END Main Content -->
<script type="text/javascript">

	function show_details(url)
	{  
	    window.location.href = url;
	}

  /*Script to show table data*/

  var table_module = false;
  $(document).ready(function()
  {
    table_module = $('#table_module').DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      bFilter: false,
      ajax: {
      'url':'{{ $module_url_path.'/get_records'}}',
      'data': function(d)
        {
          d['column_filter[q_package_title]'] = $("input[name='q_package_title']").val()
          d['column_filter[q_review]']       	= $("input[name='q_review']").val()
          d['column_filter[q_rating]']        = $("input[name='q_rating']").val()
          d['column_filter[q_user_name]']     = $("input[name='q_user_name']").val()
          d['column_filter[q_photographer]']  = $("input[name='q_photographer']").val()
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
      {data: 'package_title', "orderable": false, "searchable":false},
      {data: 'review', "orderable": false, "searchable":false},
      {
        render : function(data, type, row, meta) 
        {
          return '<span class="stars">'+row.rating+'</span>';
        },
        "orderable": false, "searchable":false
      },

      {data: 'user_name', "orderable": false, "searchable":false},
      {data: 'photographer_name', "orderable": false, "searchable":false},
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

    $('input.column_filter').on( 'keyup click', function () 
    {
        filterData();
    });

    $('#table_module').on('draw.dt',function(event)
    {
      initStars();
      var oTable = $('#table_module').dataTable();
      var recordLength = oTable.fnGetData().length;
      $('#record_count').html(recordLength);
    });
  });

  function filterData()
  {
    table_module.draw();
  }

   $(document).ready(function () 
   {
    initStars();
  });

   function initStars()
   {
      /*star rating demo*/ 
       $.fn.stars = function() 
       {
         return $(this).each(function() {
           $(this).html($('<span />').width(Math.max(0, (Math.min(5, parseFloat($(this).html())))) * 20));
         });
       }
       $(function() {       
         $('span.stars').stars();
       });
   }


</script>

@stop