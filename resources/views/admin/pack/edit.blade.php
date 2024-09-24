    @extends('admin.layout.master')                


    @section('main_content')

    <!-- BEGIN Page Title -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>  

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
            </span>
            <li>
                <i class="fa fa-briefcase"></i>
                <a href="{{ $module_url_path }}">{{ $module_title or ''}}</a>
            </li>   
            <span class="divider">
                <i class="fa fa-angle-right"></i>
            </span>
            <li class="active"><i class="fa fa-plus-square-o"></i> {{ $page_title or ''}}</li>
        </ul>
    <!-- END Breadcrumb -->

    @if(isset($arr_packages) && count($arr_packages)>0)
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

          <div id="status_msg"></div>
        
          @include('admin.layout._operation_status')  
          
          <form action="{{$module_url_path.'/update/'.$enc_id}}" method="post" class="form-horizontal" id="validation-form" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-10">

            <div id="ajax_op_status">
                
            </div>
            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
          <br>  
            
        <div class="row">
        <div class="block-new-block">
        <div class="col-md-12">
            <div class="box {{ $theme_color }}">
                <div class="box-content">

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Title<i class="red">*</i></label>
                            <div class="col-sm-9 col-lg-4 controls">
                                
                            <input type="text" name="title" class="form-control" data-rule-required="true" data-rule-minlength="3" id="title" placeholder="Enter Title" value="{{$arr_packages['title']}}">

                                <span class='help-block'>{{ $errors->first('title') }}</span>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Package Image  <i class="red">*</i> </label>
                            <div class="col-sm-9 col-lg-10 controls">
                               <div class="fileupload fileupload-new" data-provides="fileupload">
                                  <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                                     @if($arr_packages['enc_image_name']!='')
                                      <img src="{{$package_image_public_img_path.$arr_packages['enc_image_name'] }}">
                                     @else
                                      <img src="{{url('/').'/uploads/default.png' }}">
                                     @endif
                                  </div>
                                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                  <div>
                                     <span class="btn btn-default btn-file" style="height:32px;">
                                     <span class="fileupload-new">Select Image</span>
                                     <span class="fileupload-exists">Change</span>
                                     <input type="file"  data-validation-allowing="jpg, jpeg, png, gif" class="file-input news-image validate-image" name="image" id="image" /><br>
                                     <input type="hidden" class="file-input " name="oldimage" id="oldimage"  
                                        value="{{$arr_packages['enc_image_name']}}"/>
                                     </span>
                                     <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                  </div>
                                  <i class="red"> {!! image_validate_note(652,1000) !!} </i>
                               </div>
                               <span class="label label-important">NOTE!</span>
                               <span>Attached image img-thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only</span>
                                  <span for="image" id="err-image" class="help-block">{{ $errors->first(' image') }}</span>

                            </div>
                            <div class="col-sm-6 col-lg-5 control-label help-block-red" style="color:#b94a48;" id="err_logo"></div>
                            <br/>
                            <div class="col-sm-6 col-lg-5 control-label help-block-green" style="color:#468847;" id="success_logo"></div>
                         </div>


                        @if(isset($arr_packages['package_details']) && count($arr_packages['package_details']))
                        <div class="col-md-6">
                           <h3>Package Information</h3>
                           <br>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr>
                                    <th style="width: 30%">Sr No</th>
                                    <th style="width: 30%">Package Name</th>
                                    <th style="width: 30%">Format</th>
                                    <th style="width: 30%">Price</th>
                                 </tr>
                                @foreach($arr_packages['package_details'] as $new_key=>$val)
                                 <tr>
                                    <td>{{++$new_key}}</td>
                                    <td>{{isset($val['listing_details']['master_details']['title']) && $val['listing_details']['master_details']['title']!='' ? $val['listing_details']['master_details']['title'] : "NA"}}</td>
                                    <td>{{isset($val['listing_details']['format_details']['name']) && $val['listing_details']['format_details']['name']!='' ? ucwords($val['listing_details']['format_details']['name']) : "NA"}}</td>
                                    <td>{{isset($val['listing_details']['price']) && $val['listing_details']['price']!='' ? '$'.$val['listing_details']['price'] : "NA"}}</td>                                    
                                 </tr>
                                @endforeach
                              </tbody>
                           </table>
                        </div>
                      @endif

                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $str_package=''; ?>
        @if(isset($arr_packages['package_details']) && count($arr_packages['package_details']))
        @foreach($arr_packages['package_details'] as $key=>$val)
          <?php 
            $str_package .= base64_encode($val['list_id']).',';
          ?>
        @endforeach
        <?php  $str_package=rtrim($str_package,','); ?>
        @endif

        <input type="hidden" name="arr_packages" id='arr_packages' value="{{$str_package}}">
        <input type="hidden" name="checked_records" id='checked_records' value="">

          <div class="clearfix"></div>

           <div class="table-responsive" style="border:0">      
              <input type="hidden" name="multi_action" value="" />
                <table class="table table-advance"  id="table_module">
                  <thead>
                    <tr>                          
                        <th style="width: 18px; vertical-align: initial;"><input name="select_all" type="checkbox"/></th>

                        <th><a class="sort-desc" href="#">Title </a>
                            <input type="text" name="q_title" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>

                        <th>Thumbnail</th>

                        <th><a class="sort-desc" href="#">Type </a>
                            <input type="text" name="q_type" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>

                        <th><a class="sort-desc" href="#">Format </a>
                            <input type="text" name="q_format" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>                         

                        <th><a class="sort-desc" href="#">Keywords</a>
                            <input type="text" name="q_keyword" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>     

                        <th><a class="sort-desc" href="#">Seller Name</a>
                            <input type="text" name="q_name" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc" href="#">Price ({{currency_symbol()}})</a>
                            <input type="text" name="q_price" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>  
                        
                    </tr>
                  </thead>
               </table>
            </div>
            

              <div class="form-group">
                  <div class="col-sm-12 col-sm-offset-3 col-md-6 col-md-offset-5">

                      {!! Form::Submit('Submit',['class'=>'btn btn-primary','id'=>'submit_package']) !!}        
                  </div>
             </div>
            </form>

            <span class='help-block' id="err_checked_records" >{{ $errors->first('checked_record') }}</span>

      </div>
  </div>
   @endif
</div>
<!-- END Main Content -->
<script type="text/javascript">

//Set checkboxes
var str_package = $('#arr_packages').val();
var arr_package = str_package.split(',');
$('#checked_records').val(arr_package);

function onCheckedValues(ref)
{
  if(ref.checked)
  {
    if(jQuery.inArray($(ref).val(), arr_package))
    {
      arr_package.push($(ref).val());
      $('#checked_records').val(arr_package);
    }
  }
  else
  {
      arr_package.pop($(ref).val());
      $('#checked_records').val(arr_package);
  }
}

   $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 652,1000);
    });

   $(document).on("click","#submit_package", function()
    {
       $('#err_checked_records').hide();
       if($('#validation-form').find('input[name="checked_record[]"]:checked').length == 0)
      {
          $('#err_checked_records').html('Please select media for adding into package');
          $('#err_checked_records').show();
          return false;
      }

    });   

	function show_details(url)
	{  
	    window.location.href = url;
	}

  /*Script to show table data*/

  var table_module = false;
  var rows_selected = [];

  $(document).ready(function()
  {
    var enc_id = '{{$enc_id}}';
    table_module = $('#table_module').DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      bFilter: false,
      pageLength: 10,
      ajax: {
      'url':'{{ $module_url_path.'/get_edit_records?enc_id='}}'+enc_id,
      'data': function(d)
        {
          d['column_filter[q_title]']           = $("input[name='q_title']").val()
          d['column_filter[q_type]']            = $("input[name='q_type']").val()
          d['column_filter[q_format]']       	  = $("input[name='q_format']").val()
          d['column_filter[q_keyword]']         = $("input[name='q_keyword']").val()
          d['column_filter[q_name]']            = $("input[name='q_name']").val()
          d['column_filter[q_price]']           = $("input[name='q_price']").val()
        }
      },
      columns: [
      {
        render : function(data, type, row, meta) 
        {
        return row.enc_id;
        },
        "orderable": false,
        "searchable":false
      },
      {data: 'package_title', "orderable": false, "searchable":false},
      {
        render : function(data, type, row, meta) 
        {
          return row.build_thumbnail;
        },
        "orderable": false, "searchable":false
      },
      {data: 'type', "orderable": false, "searchable":false},
      {data: 'format', "orderable": false, "searchable":false},
      {data: 'keywords', "orderable": false, "searchable":false},
      {data: 'seller_name', "orderable": false, "searchable":false},
      {data: 'price', "orderable": false, "searchable":false},
      ],
      rowCallback: function(row, data, dataIndex){
         // Get row ID
         var rowId = data.enc_id;

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
         }
      }
    });


    $('#table_module tbody').on('click', 'input[type="checkbox"]', function(e){ 
     // Handle click on checkbox
      
      var $row = $(this).closest('tr');
      
      // Get row data
      var data = table_module.row($row).data();

      // Get row ID
      var rowId = data.enc_id //data[0];

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, rows_selected);
        
      // console.log(rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         rows_selected.splice(index, 1);
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table_module);

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });


   // Handle click on table cells with checkboxes
   $('#table_module').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', table_module.table().container()).on('click', function(e){
      if(this.checked){
         $('#table_module tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#table_module tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   table_module.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table_module);
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

      // updateDataTableSelectAllCtrl(table_module);
    });
  });

  function filterData()
  {
    table_module.draw();
  }

  function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

     // If none of the checkboxes are checked
     if($chkbox_checked.length === 0){
        chkbox_select_all.checked = false;
        if('indeterminate' in chkbox_select_all){
           chkbox_select_all.indeterminate = false;
        }

     // If all of the checkboxes are checked
     } else if ($chkbox_checked.length === $chkbox_all.length){

        chkbox_select_all.checked = true;
        if('indeterminate' in chkbox_select_all) {
           chkbox_select_all.indeterminate = false;
        }

     // If some of the checkboxes are checked
     } else {
        chkbox_select_all.checked = true;
        if('indeterminate' in chkbox_select_all){
           chkbox_select_all.indeterminate = true;
        }
     }
  }

  function assignServices() {
    
    var arr_services = $('.services').val();
    if(arr_services == null)
    {
      showAlert('Please select services.' ,'error');
      return false;
    }

    var checked_records = $('input[name="checked_record[]"]:checked').map(function () {
                            return this.value;
                          }).get();

    if(checked_records.length <= 0) 
    {
      showAlert('Please select packages to assign services.' ,'error');
      return false;
    } 

    $.ajax({
      url:'{{url('/')}}/{{$admin_panel_slug}}/packages/assign_services',
      type:'POST',
      data:{arr_services:arr_services, arr_packages:checked_records,'_token':"{{csrf_token()}}" },
      dataType:'json',
      beforeSend: function () {

      },
      success: function (response) {
        var status;

        if(response.status == 'success') {
           status = 'success'; 
        } else if(response.status == 'error') {
           status = 'error';
        }
        
        var str_html = makeStatusMessageHtml(status,response.msg);
        $('#status_msg').html(str_html);
        $("#assign_service").select2("val","");
        
        setTimeout(function() {
          $('#status_msg').html("");
        }, 8000);
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

  function getCheckedValue(ref) {
    console.log($(ref).val());
    //console.log(document.getElementsByName('checked_record[]').value);
  }

</script>

@stop