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
          <a href="{{ $module_url_path.'/create' }}" class="btn btn-primary btn-add-new-records" title="Add Photos / Footage">Add Photos / Footage</a> 
          </div>         
            
          <div class="btn-group">
{{--                 <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                    title="Multiple Active/Unblock" 
                    href="javascript:void(0);" 
                    onclick="javascript : return check_multi_action('frm_manage','deactive');" 
                    style="text-decoration:none;">

                    <i class="fa fa-unlock"></i>
                </a> 
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Multiple Deactive/Block" 
                   href="javascript:void(0);" 
                   onclick="javascript : return check_multi_action('frm_manage','active');"  
                   style="text-decoration:none;">
                    <i class="fa fa-lock"></i>
                </a>

                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                 title="Multiple Delete" 
                 href="javascript:void(0);" 
                 onclick="javascript : return check_multi_action('frm_manage','delete');"  
                 style="text-decoration:none;">
                 <i class="fa fa-trash-o"></i>
                </a> --}}
             
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
                        {{-- <th style="width: 18px; vertical-align: initial;"><input type="checkbox"/></th> --}}

                        <th><a class="sort-desc sort-asc" href="#">Title </a>
                            <input type="text" name="q_title" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>

                        <th><a class="sort-desc sort-asc" href="#">Type </a>
                            <input type="text" name="q_type" placeholder="Search" class="search-block-new-table column_filter" />
                        </th> 

                        <th><a class="sort-desc sort-asc" href="#">Seller Name </a>
                            <input type="text" name="q_name" placeholder="Search" class="search-block-new-table column_filter" value="{{\Request::get('seller')}}" />
                        </th>
                        <th><a class="sort-desc sort-asc" href="#">Commission </a>
                            <input type="text" name="q_commission" placeholder="Search" class="search-block-new-table column_filter" />
                        </th>                        
                        <th>Thumbnail</th> 
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

<!-- Pop Up Note For Rejection Starts here -->
<form method="post" id="validation-form" class="form-horizontal" action="{{ $module_url_path.'/disapprove' }}">
{{csrf_field()}}
  <div id="modal-2" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3 id="myModalLabel">Note for Rejection</h3>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="title">Note<span style="color:#F00;">*</span></label>
                    <div class="col-sm-9 col-lg-8 controls">
                      <textarea class="form-control" name="note" id="note" 
                      data-rule-required="true" maxlength="128" placeholder="Enter Note here.."></textarea>
                        <div class="error" id="error_note"></div>
                    </div>
                    <input hidden name="enc_id" id="enc_id">
                </div>
             </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <input class="btn btn-primary" id="submit_note" type="submit" value="Send">
            </div>
          </div>
      </div>
  </div>
</form>
<!-- Pop Up Note For Rejection ends here -->

<!-- Pop Up For Upload Media for Photo is here -->
<form method="post" id="validation-form" class="form-horizontal" action="{{ $module_url_path.'/upload_replica' }}" enctype="multipart/form-data">
{{csrf_field()}}
  <div id="modal-3" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3 id="myModalLabel">Upload Photo</h3>
              </div>
              <div class="modal-body">
                          <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label"> Upload <i class="red">*</i> </label>
                            <div class="col-sm-9 col-lg-10 controls">
                               <div class="fileupload fileupload-new parent-image" data-provides="fileupload">
                                  <div class="fileupload-new img-thumbnail new_file" style="width: 200px; height: 150px;">
                                        <img src="">
                                  </div>
                                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                  <div>
                                     <span class="btn btn-default btn-file" style="height:32px;">
                                     <span class="fileupload-new">Select</span>
                                     <span class="fileupload-exists">Change</span>
                                     <input type="file" class="file-input validate-image" name="file" onchange="Validation(this.files,652,1000)" id="file" /><br>
                                     </span>
                                     <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                  </div>
                                  <span for="image" class='help-block' id="err_file">{{ $errors->first('image') }}</span>
                               </div>
                               <span class="label label-important">NOTE!</span> <span> Uploading Photo size should be <strong>1000 x 652   </strong> </span>
                            </div>
                          </div>
                    
                    <input hidden name="enc_list_id" id="enc_list_id">
                    <input hidden name="type" id="type">
                    <input hidden name="old_admin_enc_item_name" id="old_admin_enc_item_name" value=""> 
             </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <input class="btn btn-primary" id="submit_image" type="submit" value="Send">
            </div>
          </div>
      </div>
  </div>
</form>
<!-- Pop Up For Upload Media ends here -->

<!-- Commission pop up starts here -->
<form method="post" id="validation-form" class="form-horizontal" action="{{$module_url_path}}/update_commission">
{{csrf_field()}}
<div id="modal-4" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Update Commission</h3>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label" for="title">Commission<span style="color:#F00;">*</span>(in %)</label>
                  <div class="col-sm-9 col-lg-8 controls">
                    <input type="text" class="form-control" name="commission" id="commission" 
                    data-rule-required="true" data-rule-number="true" maxlength="5" placeholder="Commission in (%)"/>
                      <div class="help-block" id="err_commission">{{ $errors->first('commission') }}</div>
                  </div>
                    <input hidden name="enc_list_id" id="enc_master_list_id">
              </div>
           </div>
          <div class="modal-footer">
              <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
              <input class="btn btn-primary" id="submit_commission" type="submit" value="Update">
          </div>
        </div>
    </div>
</div>
</form>

<!-- Pop Up For Upload Media for Photo is here -->
<form method="post" id="validation-form" class="form-horizontal" action="{{ $module_url_path.'/upload_replica' }}" enctype="multipart/form-data">
{{csrf_field()}}
  <div id="modal-5" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3 id="myModalLabel">Upload Footage</h3>
              </div>
              <div class="modal-body">
                    <div class="form-group">
                      <label class="col-sm-3 col-lg-2 control-label"> Upload Thumbnail<i class="red">*</i> </label>
                      <div class="col-sm-9 col-lg-10 controls">
                         <div class="fileupload fileupload-new parent-image" data-provides="fileupload">
                            <div class="fileupload-new img-thumbnail new_image" style="width: 200px; height: 150px;">
                                  <img src="">
                            </div>
                            <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                               <span class="btn btn-default btn-file" style="height:32px;">
                               <span class="fileupload-new">Select</span>
                               <span class="fileupload-exists">Change</span>
                               <input type="file" class="file-input validate-image " name="footage_image" onchange="Validation(this.files,652,1000)" id="footage_file" /><br>
                               </span>
                               <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                            <span for="image" class='help-block' id="err_footage_image">{{ $errors->first('footage_image') }}</span>
                         </div>
                         <span class="label label-important">NOTE!</span> <span> Uploading Footage thumbnail size should be <strong>1000 x 652   </strong> </span>
                      </div>
                    </div>
              </div>
              <div class="modal-body">
                          <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label"> Upload Footage<i class="red">*</i> </label>
                            <div class="col-sm-9 col-lg-10 controls">
                               <div class="fileupload fileupload-new parent-image" data-provides="fileupload">
                                  <div class="fileupload-new img-thumbnail new_file" style="width: 200px; height: 150px;">
                                        
                                  </div>
                                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                  <div>
                                     <span class="btn btn-default btn-file" style="height:32px;">
                                     <span class="fileupload-new">Select</span>
                                     <span class="fileupload-exists">Change</span>
                                     <input type="file" class="file-input validate-image" name="footage_file" onchange="FootageValidation(this.files,652,1000)" id="file_footage_video" /><br>
                                     </span>
                                     <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                  </div>
                                  <span for="image" class='help-block' id="err_footage_file">{{ $errors->first('footage_file') }}</span>
                               </div>
                               <span class="label label-important">NOTE!</span> <span> Uploading Footage size should be <strong>1000 x 652   </strong> </span>
                            </div>
                          </div>
                    
                    <input hidden name="enc_list_id" id="footage_enc_list_id">
                    <input hidden name="type" id="footage_type">
                    <input hidden name="old_admin_enc_item_name" id="footage_old_admin_enc_item_name" value=""> 
                    <input hidden name="old_admin_enc_footage_image" id="footage_old_admin_enc_footage_image" value=""> 
             </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <input class="btn btn-primary" id="submit_footage" type="submit" value="Send">
            </div>
          </div>
      </div>
  </div>
</form>
<!-- Pop Up For Upload Media ends here -->


<!-- Commission pop up ends hert -->
 <script type="text/javascript">
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
              d['column_filter[q_title]']            = $("input[name='q_title']").val()
              d['column_filter[q_type]']             = $("input[name='q_type']").val()
              d['column_filter[q_name]']             = $("input[name='q_name']").val()
              d['column_filter[q_commission]']       = $("input[name='q_commission']").val()
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
          {data: 'type', "orderable": true, "searchable":false},
          {data: 'seller_name', "orderable": true, "searchable":false},
          {data: 'commission', "orderable": true, "searchable":false},
          {
            render : function(data, type, row, meta) 
            {
              return row.build_thumbnail_btn;
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

<script type="text/javascript">
function show_details(url)
{ 
    window.location.href = url;
}

function clear_fields(obj)
{
  $('#note').val('');
  var enc_id = $(obj).attr('data-id');
  $('#enc_id').val(enc_id);
}

function clear_all_fields(obj)
{
  $('#old_admin_enc_item_name').val('');
  $('#err_file').html(''); $('#err_file').hide();
  
  var enc_list_id = $(obj).attr('data-id');
  $('#enc_list_id').val(enc_list_id);
  $('#footage_enc_list_id').val(enc_list_id);
  
  var type = $(obj).attr('data-type');
  $('#type').val(type);
  $('#footage_type').val(type);

  var old_admin_enc_item_name = $(obj).attr('data-image');
  $('#old_admin_enc_item_name').val(old_admin_enc_item_name);
  $('#footage_old_admin_enc_item_name').val(old_admin_enc_item_name);

  var old_admin_enc_footage_image = $(obj).attr('data-footage');
  $('#footage_old_admin_enc_footage_image').val(old_admin_enc_footage_image);

  if($('#old_admin_enc_item_name').val()!='')
  {
    if(type=='photo')
    {
      $('.new_file').html('<img src="{{$admin_photos_public_img_path}}'+old_admin_enc_item_name+'" style="max-width: 200px; max-height: 150px; line-height: 20px;">');
    }
    else if(type=='footage')
    {
      $('.new_file').html('<video src="{{$admin_footage_public_img_path}}'+old_admin_enc_item_name+'" height="150px" width="200px" style="line-height: 20px;"></video>');
    }
  }
  else
  {
    $('.fileupload-preview').html('<img src="" style="max-width: 200px; max-height: 150px; line-height: 20px;">');
    $('#file').val('');
  }

  if($('#footage_old_admin_enc_footage_image').val()!='')
  {
    $('.new_image').html('<img src="{{$admin_footage_image_public_path}}'+old_admin_enc_footage_image+'" style="max-width: 200px; max-height: 150px; line-height: 20px;">');
  }
  else
  {
    $('.fileupload-preview').html('<img src="" style="max-width: 200px; max-height: 150px; line-height: 20px;">');
    $('#file').val('');
  }

}

function set_commission(obj)
{
  $('#err_commission').hide();$('#err_commission').html('');
  var enc_list_id = $(obj).attr('data-id');
  var commission  = $(obj).attr('data-val');
  $('#enc_master_list_id').val(enc_list_id);
  $('#commission').val(commission);
}

$(document).on('click','#submit_commission',function(){

  var commission = $('#commission').val();
  var commission_filter = /^\d{0,3}(\.\d{1,2})?$/
  var flag = 0;

  $('#err_commission').hide();$('#err_commission').html('');

  if(commission=='')
  {
    $('#err_commission').show();
    $('#err_commission').html('Please enter commission');
    var flag = 1;
  }
  
  else if(commission>100)
  {
    $('#err_commission').show();
    $('#err_commission').html('Please enter valid commission');
    var flag = 1;
  }

  else if(!commission_filter.test(commission))
  {
    $('#err_commission').show();
    $('#err_commission').html('Please enter valid commission');
    var flag = 1;
  }

  if(flag==1)
  {
    return false; 
  }

  return true;

});

$(document).on('click','#submit_image',function()
{
  var file                    = $('#file').val();
  var type                    = $('#type').val();
  var old_admin_enc_item_name = $('#old_admin_enc_item_name').val();
  
  $('#err_file').html(''); $('#err_file').hide();

  if(old_admin_enc_item_name=='')
  {
    if(file.trim()=='')
    {
        $('#err_file').html('Please select '+type);
        $('#err_file').show();
        return false;
    }
  }
  return true;
});

$(document).on('click','#submit_footage',function()
{
  var footage_file            = $('#footage_file').val();
  var file_footage_video      = $('#file_footage_video').val();
  var type                    = $('#type').val();
  var old_admin_enc_item_name = $('#old_admin_enc_item_name').val();
  var footage_old_admin_enc_footage_image = $('#footage_old_admin_enc_footage_image').val();
  
  $('#err_footage_file').html(''); $('#err_footage_file').hide();
  $('#err_footage_image').html(''); $('#err_footage_image').hide();

  if(old_admin_enc_item_name=='')
  {
    if(file_footage_video.trim()=='')
    {
        $('#err_footage_file').html('Please select '+type);
        $('#err_footage_file').show();
        return false;
    }
  }
  if(footage_old_admin_enc_footage_image=='')
  {
    if(footage_file.trim()=='')
    {
        $('#err_footage_image').html('Please select thumbnail for'+type);
        $('#err_footage_image').show();
        return false;
    }
  }
  return true;
});

 /*-------------------- Photo/Footage Thumbnail Validation -------------------------*/

  function Validation (files,height,width) 
  {
    var image_height = height || "";
    var image_width = width || "";

    var type = $('#type').val();
    
    if (typeof files !== "undefined") 
    {
      for (var i=0, l=files.length; i<l; i++) 
      {
        var blnValid = false;
        var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
        var ext = ext.toLowerCase();
          if( ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "gif")
          {
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onload = function (e) 
            {
              var image = new Image();
              image.src = e.target.result;
                 
              image.onload = function () 
              {
                  var height = this.height;
                  var width = this.width;

                  if (height != image_height || width != image_width ) 
                  {
                      showAlert("Width and Height must be equal to "+image_width+" X "+image_height+"." ,"error");
                      $(".new_image").html("");
                      $(".fileupload").attr('class',"fileupload fileupload-new");
                      $("#file").val('');
                      return false;
                  }
                  else
                  {
                     return true;
                  }
              }
            }
              blnValid = true;
          }
          
          if(blnValid ==false) 
          {
              swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpeg , jpg , png, gif");
              $('#file').val('');
              return false;
          }
        
          $('#err_file').hide();
          return true;
      }
    }
    else
    {
      swal("No support for the File API in this web browser" ,"error");
    } 
  }

 /*-------------------- Footage Validation -------------------------*/

  function FootageValidation (files,height,width) 
  {
    var image_height = height || "";
    var image_width = width || "";

    var type = $('#type').val();
    
    if (typeof files !== "undefined") 
    {
      for (var i=0, l=files.length; i<l; i++) 
      {
        var blnValid = false;
        var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
        var ext = ext.toLowerCase();
          if(ext == "mp4")
          {
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onload = function (e) 
            {
              var image = new Image();
              image.src = e.target.result;
                 
              image.onload = function () 
              {
                  var height = this.height;
                  var width = this.width;
                  if (height < image_height || width < image_width ) 
                  {
                      showAlert("Width and Height must be greater equal to "+image_width+" X "+image_height+"." ,"error");
                      $("#file_footage_video").val('');
                      return false;
                  }
                  else
                  {
                     return true;
                  }
              }
            }

            blnValid = true;
          }
          
          if(blnValid ==false) 
          {
              swal("Sorry, " + files[0]['name'] + " is invalid, only mp4 extension is allowed");
              $('#file_footage_video').val('');
              return false;
          }
        
          $('#err_file').hide();
          return true;
      }
    }
    else
    {
      swal("No support for the File API in this web browser" ,"error");
    } 
  }  

</script>  

@stop