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
                  <i class="fa fa-briefcase"></i>
            </span>
            <li class="active">{{ isset($module_title)?$module_title:"" }}</li>
        </ul>
      </div>
    <!-- END Breadcrumb -->

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
          
      {{--     <div class="btn-group">
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
 --}}
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
                        {{-- <th style="width: 18px; vertical-align: initial;"><input type="checkbox"/></th> --}}

                        <th><a class="sort-desc" href="#">Package </a>
                            <input type="text" name="q_title" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>

                        <th><a class="sort-desc" href="#">Photographer </a>
                            <input type="text" name="q_name" placeholder="Search" class="search-block-new-table column_filter" value="{{\Request::get('u')}}" />
                        </th> 

                        <th><a class="sort-desc" href="#">Category </a>
                            <input type="text" name="q_category" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">Sub Category </a>
                            <input type="text" name="q_subcategory" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>

                        <th><a class="sort-desc" href="#">Validity </a>
                            <input type="text" name="q_validity" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">Unedited Photo </a>
                            <input type="text" name="q_unedited_photo" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>

                        <th><a class="sort-desc" href="#">Edited Photo </a>
                            <input type="text" name="q_edited_photo" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">Price ({{currency_symbol()}})</a>
                            <input type="text" name="q_price" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>  
                        <th >Action</th>
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
          d['column_filter[q_title]']           = $("input[name='q_title']").val()
          d['column_filter[q_name]']        	  = $("input[name='q_name']").val()
          d['column_filter[q_category]']       	= $("input[name='q_category']").val()
          d['column_filter[q_subcategory]']     = $("input[name='q_subcategory']").val()
          d['column_filter[q_validity]']        = $("input[name='q_validity']").val()
          d['column_filter[q_unedited_photo]']  = $("input[name='q_unedited_photo']").val()
          d['column_filter[q_edited_photo]']    = $("input[name='q_edited_photo']").val()
          d['column_filter[q_price]']           = $("input[name='q_price']").val()
        }
      },
      columns: [
    /*  {
        render : function(data, type, row, meta) 
        {
        return '<input type="checkbox" '+
        ' name="checked_record[]" '+  
        ' value="'+row.enc_id+'"/>';
        },
        "orderable": false,
        "searchable":false
      },*/
      {data: 'package_title', "orderable": false, "searchable":false},
      {data: 'photographer_name', "orderable": false, "searchable":false},
      {data: 'category_title', "orderable": false, "searchable":false},
      {data: 'subcategory_title', "orderable": false, "searchable":false},
      {data: 'validity', "orderable": false, "searchable":false},
      {data: 'unedited_photo_count', "orderable": false, "searchable":false},
      {data: 'edited_photo_count', "orderable": false, "searchable":false},
      {data: 'price', "orderable": false, "searchable":false},
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
    table_module.draw();
  }

</script>

@stop