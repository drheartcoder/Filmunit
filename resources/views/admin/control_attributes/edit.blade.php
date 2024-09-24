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
      <a href="{{ $module_url_path }}/view/{{isset($arr_attributes['subcategory_id'])?base64_encode($arr_attributes['subcategory_id']):''}}">{{ $module_title or ''}}</a>
      </span> 
      <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-edit"></i>
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
               <i class="fa fa-edit"></i>
               {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
               <a data-action="collapse" href="#"></a>
               <a data-action="close" href="#"></a>
            </div>
         </div>

         <div class="box-content">
            @include('admin.layout._operation_status')  
            <form name="validation-form" id="validation-form" method="POST" class="form-horizontal" action="{{$module_url_path}}/update/{{$enc_id}}" enctype="multipart/form-data">
               {{ csrf_field() }}

               <?php 
                  $category_id = isset($arr_attributes['subcategory_details']['parent']) ? $arr_attributes['subcategory_details']['parent'] : "";  

                  $subcategory_id = isset($arr_attributes['subcategory_id']) ? $arr_attributes['subcategory_id'] : "";
               ?>

               <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="category">Category<i class="red">*</i></label>
                  <div class="col-sm-6 col-lg-4 controls">
                     <select type="text" name="category" id="category" class="form-control" data-rule-required="true"
                              onclick="javascript: return getSubcategories();" >
                           <option value="">Select Category</option>
                           @if(isset($arr_categories) && count($arr_categories) > 0 )
                              @foreach($arr_categories as $key => $data)
                                 @if(isset($data['id']) && isset($data['id']) && $data['id'] != "" && $data['title'] != "" )  
                                 <option value="{{$data['id']}}" 
                                    @if($data['id'] == $category_id)
                                       selected="" 
                                    @endif
                                    >{{$data['title']}}</option>
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
                     <input name="label" class="form-control" data-rule-required="true"
                            data-rule-maxlength="500" placeholder="Label" value="{{$arr_attributes['label']}}" />
                     <span class='error'>{{ $errors->first('label') }}</span>
                  </div>
               </div>


               <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label" for="control_type">Control Type<i class="red">*</i></label>
                  <div class="col-sm-6 col-lg-4 controls">
                  
                  <?php
                     $control_type = "";

                     if(isset($arr_attributes['control_type']) && $arr_attributes['control_type'] != "")
                     {
                        $control_type = $arr_attributes['control_type'];
                     }  
                  ?>

                  <input type="hidden" name="control_type" id="control_type" readonly="" value="{{isset($arr_attributes['control_type'])?strval($arr_attributes['control_type']):''}}"></input>

                     <select type="text"  class="form-control" data-rule-required="true" disabled="">
                           <option value="" >Select Type</option>

                           <option value="">Select type</option>
                           <option value="TEXT" @if($control_type == 'TEXT') selected="" @endif>Textbox</option>
                           <option value="TEXTAREA" @if($control_type == 'TEXTAREA') selected="" @endif >Textarea</option>
                           <option value="CHECKBOX" @if($control_type == 'CHECKBOX') selected="" @endif >Checkbox</option>
                           <option value="RADIO" @if($control_type == 'RADIO') selected="" @endif >Radio Button</option>
                           <option value="DROPDOWN" @if($control_type == 'DROPDOWN') selected="" @endif >Dropdown</option>
                     </select>

                     <span class='error'>{{ $errors->first('control_type') }}</span>
                  </div>
               </div>

               <?php 
                  $show_min_max_style = 'none'; 
                  if($arr_attributes['control_type'] == 'TEXT' || $arr_attributes['control_type'] == 'TEXTAREA')
                  {
                     $show_min_max_style = 'block'; 
                  }


               ?>
               
               <div id="min_max_options" style="display: {{ $show_min_max_style }};">

                  <div class="form-group">
                     <label class="col-sm-3 col-lg-2 control-label" for="minlength">Minlength</label>
                     <div class="col-sm-6 col-lg-4 controls">
                        <input name="minlength" class="form-control"  
                              {{-- data-rule-required="true" data-rule-maxlength="10"  --}}
                              placeholder="Minlength" value="{{$arr_attributes['minlength'] or ''}}" />
                        <span class='error'>{{ $errors->first('minlength') }}</span>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-sm-3 col-lg-2 control-label" for="maxlength">Maxlength</label>
                     <div class="col-sm-6 col-lg-4 controls">
                        <input name="maxlength" class="form-control" {{-- data-rule-required="true" data-rule-maxlength="500" --}} placeholder="Maxlength" value="{{$arr_attributes['maxlength'] or ''}}" />
                        <span class='error'>{{ $errors->first('maxlength') }}</span>
                     </div>
                  </div>

               </div>


                  @if( ($arr_attributes['control_type'] == 'CHECKBOX' || $arr_attributes['control_type'] == 'RADIO' || $arr_attributes['control_type'] == 'DROPDOWN' ) && isset($arr_attributes['control_attribute_options']) && count($arr_attributes['control_attribute_options']) > 0)

                  
                  <input type="hidden" id="option_cnt" name="option_cnt" value="{{isset($arr_attributes['control_attribute_options'])?count($arr_attributes['control_attribute_options']):'1'}}" readonly="" ></input>
                 
                  <div id="add_options" style="display: block;" >

                     @foreach($arr_attributes['control_attribute_options'] as $key => $option)

                        <div class="form-group">
                           
                           @if($key == 0) 
                           <label class="col-sm-3 col-lg-2 control-label" >Control Options<i class="red">*</i></label>
                           @else

                           <label class="col-sm-3 col-lg-2 control-label" ></label>

                           @endif

                           <div class="col-sm-6 col-lg-4 controls">
                              <input type="text" class="form-control options" name="control_options[]" value="{{$option['option_title']}}" id="control_options_{{$key + 1}}"  placeholder="Enter Option" ></input>
                              <span style="display: none;" class="help-block" for="">This field is required.</span>
                           </div>
                           
                           @if($key == 0)

                           <span class="btn btn-success" onclick="javascript:return addOption();"><i class="fa fa-plus fa-1x"></i></span>

                           @else
                           <span class="btn btn-danger" onclick="javascript:return removeOption(this);"><i class="fa fa-trash fa-1x"></i></span>

                           @endif


                        </div>

                     @endforeach   

                  </div> 

                  @endif

               <br>

               <div class="form-group">
                  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                     <input type="submit" value="Update"  onclick="javascript:return chkOptionsAreFilled();" class="btn btn btn-primary">
                  </div>
               </div>
               
            </form>
         </div>
      </div>
   </div>
</div>
<!-- END Main Content -->

<script type="text/javascript">

function chkOptionsAreFilled()
{
   var control_type = $('#control_type').val();

   /*if( answer_type == 'CHECKBOX' || answer_type == 'RADIO' || answer_type == 'DROPDOWN' )
   {
      $('#add_options').show();
      $('#min_max_options').hide();
   }
   else if(answer_type == 'TEXT' || answer_type == 'TEXTAREA' )
   {
      $('#add_options').hide();
      $('#min_max_options').show();
   }
   */

   if(control_type != "")
   {  
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

         $('#add_options').find('.help-block').fadeOut(5000);

         if(flag == true )
         {
            return false;
         }
         else if (flag == false )
         {
            return true;
         }

         $('#add_options').show();
         $('#min_max_options').hide();
      }
      else if(control_type == 'TEXT' || control_type == 'TEXTAREA')
      {
         $('#add_options').hide();
         $('#min_max_options').show();
         return true;
      }
   }
   else
   {
      return false;
   }
}

      
function addOption()  
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
               '<input type="text" class="form-control options" name="control_options[]" id="control_options_'+option_cnt+'"  value="" placeholder="Enter Option" ></input>'+
            '<span style="display: none;" class="help-block" for="">This field is required.</span></div>'+
            '<span class="btn btn-danger" onclick="javascript:return removeOption(this);"><i class="fa fa-trash fa-1x"></i></span>'+
         '</div>';
   
   $('#add_options').append(str);

   $("#control_options_"+option_cnt+"").rules('add',{required:true});
}

function removeOption(ref) 
{
   if(!confirm('Are you sure do you want to delete option ?'))
   {
      return false;
   }
   
   $(ref).parent().closest('div').remove();
   var option_cnt = $('#option_cnt').val();
   option_cnt     = parseInt(option_cnt) - 1;  
   $('#option_cnt').prop('value',option_cnt);
}

   
function loadSubcategory(category_id)
{     
   if(category_id != "")
   {
      var category_id = Base64.encode(category_id);
      getSubcategoriesAjax(category_id);
   }
}

function getSubcategories()
{
   var category_id = $("#category").val();
   var category_id = Base64.encode(category_id);

   getSubcategoriesAjax(category_id);
}

function getSubcategoriesAjax(category_id)
{
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
            var selected_text = "";
            if(subcategory.id == "{{$subcategory_id or ''}}")
            {
               selected_text =  'selected="selected"';
            } 
            var subcat = '<option value="'+subcategory.id+'"" '+selected_text+'>'+subcategory.title+'</option>';        
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

$(document).ready(function () {
   loadSubcategory('{{$category_id}}');
});

</script>

@stop
