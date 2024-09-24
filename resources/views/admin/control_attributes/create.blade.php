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
      <i class="fa fa-crosshairs"></i>
      <a href="{{ $module_url_path }}">{{ $module_title or ''}}</a>
      </span> 
      <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-plus"></i>
      </span>
      <li class="active">{{ $page_title or ''}}</li>
   </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
   <div class="col-md-12">
      <div class="box">
         <div class="box-title">
            <h3>
               <i class="fa fa-plus"></i>
               {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
               <a data-action="collapse" href="#"></a>
               <a data-action="close" href="#"></a>
            </div>
         </div>
         <div class="box-content">
            
            @include('admin.layout._operation_status')

            <form name="validation-form" id="validation-form" method="POST" class="form-horizontal" action="{{$module_url_path}}/store" enctype="multipart/form-data">

               {{ csrf_field() }}

               <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="category">Category<i class="red">*</i></label>
                  <div class="col-sm-6 col-lg-4 controls">
                     <select type="text" name="category" id="category" class="form-control" value data-rule-required="true" onclick="javascript: return getSubcategories();">
                           <option value="">Select Category</option>
                           @if(isset($arr_categories) && count($arr_categories) > 0 )
                              @foreach($arr_categories as $key => $category)
                                 @if(isset($category['id']) && isset($category['id']) && $category['id'] != "" && $category['title'] != "" )  
                                 <option value="{{$category['id']}}">{{$category['title']}}</option>
                                 @endif
                              @endforeach 
                           @endif
                     </select>
                     <span class='error'>{{ $errors->first('category') }}</span>
                  </div>
               </div>   

               <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="subcategory">Subcategory <i class="red">*</i></label>
                  <div class="col-sm-6 col-lg-4 controls">
                    <select name="subcategory" id="subcategory" data-rule-required="true" class="form-control">
                     <option value="" >Select Subcategory</option>
                   </select>
                  </div>
               </div>

               <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="label">Label<i class="red">*</i></label>
                  <div class="col-sm-6 col-lg-4 controls">
                     <input name="label" class="form-control"  data-rule-required="true" data-rule-maxlength="500" placeholder="Label" value="{{old('label')}}" />
                     <span class='error'>{{ $errors->first('label') }}</span>
                  </div>
               </div>

               <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="control_type">Control Type<i class="red">*</i></label>
                  <div class="col-sm-6 col-lg-4 controls">
                     <select type="text" name="control_type" id="control_type" class="form-control" onchange="javascript:return chkControlType(this);" data-rule-required="true">
                           <option value="">Select type</option>
                           <option value="TEXT">Textbox</option>
                           <option value="TEXTAREA">Textarea</option>
                           <option value="CHECKBOX">Checkbox</option>
                           <option value="RADIO">Radio Button</option>
                           <option value="DROPDOWN">Dropdown</option>
                     </select>
                     <span class='error'>{{ $errors->first('control_type') }}</span>
                  </div>
               </div>

               <input type="hidden" id="option_cnt" name="option_cnt" value="1" readonly="" ></input>

               <div id="min_max_options" style="display: none;">

                  <div class="form-group">
                     <label class="col-sm-3 col-lg-2 control-label" for="minlength">Minlength</label>
                     <div class="col-sm-6 col-lg-4 controls">
                        <input name="minlength" class="form-control"  
                              {{-- data-rule-required="true" data-rule-maxlength="10"  --}}
                              placeholder="Minlength" value="{{old('minlength')}}" />
                        <span class='error'>{{ $errors->first('minlength') }}</span>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-sm-3 col-lg-2 control-label" for="maxlength">Maxlength</label>
                     <div class="col-sm-6 col-lg-4 controls">
                        <input name="maxlength" class="form-control"  {{-- data-rule-required="true" data-rule-maxlength="500" --}} placeholder="Maxlength" value="{{old('maxlength')}}" />
                        <span class='error'>{{ $errors->first('maxlength') }}</span>
                     </div>
                  </div>

               </div>

               <div id="add_options" style="display: none;">
                  <div class="form-group">
                     <label class="col-sm-3 col-lg-2 control-label" >Control Options<i class="red">*</i></label>
                     <div class="col-sm-6 col-lg-4 controls">
                        <input type="text" class="form-control options" name="control_options[]" id="control_options_1" placeholder="Enter Option" ></input>
                     <span style="display: none;" class="help-block" for="">This field is required.</span>
                     </div>
                     <span class="btn btn-success" onclick="javascript:return addOption();"><i class="fa fa-plus fa-1x"></i></span>
                  </div>
               </div>

               <br>

               <div class="form-group">
                  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                     <input type="submit" value="Save" onclick="javascript:return chkOptionsAreFilled();" class="btn btn btn-primary">
                  </div>
               </div>
               
            </form>
         </div>
      </div>
   </div>
</div>
<!-- END Main Content -->


<script type="text/javascript">

$(document).ready(function () 
{
   /* setting first option of answer type one & making option_cn value 1*/
   $("#control_type").val($("#control_type option:first").val());
   $('#option_cnt').prop('value','1');
});         


function chkOptionsAreFilled()
{  
   var control_type = $('#control_type').val();

   if(control_type == 'CHECKBOX' || control_type == 'RADIO' || control_type == 'DROPDOWN')
   {
   
      $('#add_options').find('.help-block').hide();

      var flag = false;
      $('#add_options').find('.options').each(function () {
         
         if($(this).val()  == "")
         {
            $(this).parent().parent().prop('class','form-group has-error');
            $(this).next('span').show();         
            flag = true;
         }
      });
      
      $('#add_options').find('.help-block').fadeOut(3000);

      if(flag == true )
      {
         return false;
      }
      else if (flag == false )
      {
         return true;
      }
   }
   else if(control_type == 'TEXT' || control_type == 'TEXTAREA')
   {
      return true;
   }
   else
   {
      return false;  
   }
}

function chkControlType(ref) 
{
   var control_type = $(ref).val();   

   if(control_type != "")
   {
      if( control_type == 'CHECKBOX' || control_type == 'RADIO' || control_type == 'DROPDOWN' )
      {
         $('#add_options').show();
         $('#min_max_options').hide();
      }
      else if(control_type == 'TEXT' || control_type == 'TEXTAREA' )
      {
         $('#add_options').hide();
         $('#min_max_options').show();
      }
   }
}

function addOption(ref) 
{  
   var option_cnt =  $('#option_cnt').val();

   if(option_cnt == "")
   {
      option_cnt = 1;  
   }
   else
   {
      option_cnt = parseInt(option_cnt) + 1;  
   }

   $('#option_cnt').prop('value',option_cnt);

   var str = "";

   str = '<div class="form-group">'+
            '<label class="col-sm-3 col-lg-2 control-label" ></label>'+
            '<div class="col-sm-6 col-lg-4 controls">'+
               '<input type="text" class="form-control options" name="control_options[]" id="control_options_'+option_cnt+'" placeholder="Enter Option" ></input>'+
            '<span style="display: none;" class="help-block" for="">This field is required.</span></div>'+
            '<span class="btn btn-danger" onclick="javascript:return removeOption(this);"><i class="fa fa-trash fa-1x"></i></span>'+
         '</div>';

   $('#add_options').append(str);
   /*$("#control_options_"+option_cnt+"").rules('add',{required:true});*/
}

function removeOption(ref) 
{
   /*if(!confirm('Are you sure do you want to delete option ?'))
   {
      return false;
   }*/

   $(ref).parent().closest('div').remove();
   
   var option_cnt = $('#option_cnt').val();
   option_cnt     = parseInt(option_cnt) - 1;  
   $('#option_cnt').prop('value',option_cnt);
}


function getSubcategories()
{
   var category_id = $("#category").val();
   var category_id = Base64.encode(category_id);

   $.ajax({
      url:'{{url('common/get_subcategories')}}'+'?enc_id='+category_id,    
      type: 'get',
      success:function(response) {
        $('#subcategory').find('option').remove().end();
        $('#subcategory').append('<option value="">Select Subcategory</option>');
        if(response.status=='success')
        {   
          $.each(response.arr_subcategories, function(index,subcategory)
          {
            var subcat = '<option value="'+subcategory.id+'"">'+subcategory.title+'</option>';        
            $('#subcategory').append(subcat);
          });
        }   
        else
        {
          var subcat = '<option value="">Subcategories not available</option>';        
          $('#subcategory').append(subcat);
        }
      }
   });
}
</script>

@stop
