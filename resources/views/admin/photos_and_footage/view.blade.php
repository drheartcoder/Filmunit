@extends('admin.layout.master')                
@section('main_content')

<style type="text/css">
  .ui-autocomplete
  {
    max-width: 26% !important;
  }
  .mass_min {
    background: #fcfcfc none repeat scroll 0 0;
    border: 1px dashed #d0d0d0;
    float: left;
    margin-bottom: 20px;
    margin-right: 21px;
    margin-top: 10px;
    padding: 5px;
  }
  .mass_addphoto {
    display: inline-block;
    margin: 0 10px;
    padding-top: 27px;
    text-align: center;
    vertical-align: top;
  }
  .mass_addphoto {
    text-align: center;
  }
  .upload_pic_btn {
    cursor: pointer;
    font-size: 14px;
    height: 100% !important;
    left: 0;
    margin: 0;
    opacity: 0;
    padding: 0;
    position: absolute;
    right: 0;
    top: 0;
  }
</style>

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
      <i class="fa fa-home">
      </i>
      <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard
      </a>
    </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                <i class="fa fa-image"></i>
                <a href="{{ url($admin_panel_slug.'/photos_and_footage') }}">{{ $module_title or ''}}</a>
            </span>  
    <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa fa-eye">
      </i>
    </span> 
    <li class="active">   {{ $title or '' }}
    </li>
  </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box ">
      <div class="box-title">
        <h3>
          <i class="fa fa-eye">
          </i> {{ $title or '' }} 
        </h3>
        <div class="box-tool">
        </div>
      </div>
      <div class="box-content">
        @include('admin.layout._operation_status')
         <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                   
                      @if($arr_data['is_approved']==1)
                           <!-- <a href="javascript:void(0)" class="btn btn-sm btn-success show-tooltip" title="Approved" ><i class="fa fa-check"></i></a>  --> 
                           <a data-id="{{base64_encode($arr_data['id'])}}" title="Disapprove" href="#modal-2" data-toggle="modal" class="btn btn-sm btn-danger show-tooltip" title="Disapprove" onclick="clear_fields(this)";
                           ><i class="fa fa-times"></i></a>
                          
                          @elseif($arr_data['is_approved']==0)
                          
                           <a href="{{ url('/').'/admin/photos_and_footage/approve/'.base64_encode($arr_data['id']) }}" class="btn btn-sm btn-success show-tooltip" onclick="return confirm_action(this,event,'Do you really want to approve this record ?')" title="Approve" ><i class="fa fa-check"></i></a>   
                          <a data-id="{{base64_encode($arr_data['id'])}}" title="Disapprove" href="#modal-2" data-toggle="modal" class="btn btn-sm btn-danger show-tooltip" title="Disapprove" onclick="clear_fields(this)";><i class="fa fa-times"></i></a>

                         @elseif($arr_data['is_approved']==2)
                          <!-- <a href="javascript:void(0)" class="btn btn-sm btn-danger show-tooltip" title="Disapproved" ><i class="fa fa-times"></i></a>  -->  
                           <a href="{{ url('/').'/admin/photos_and_footage/approve/'.base64_encode($arr_data['id']) }}" class="btn btn-sm btn-success show-tooltip" onclick="return confirm_action(this,event,'Do you really want to approve this record ?')" title="Approve" ><i class="fa fa-check"></i></a>    

                         @endif
                  
                   
                    </div>
        <?php
       //dd($arr_data);
          $seller_name  = isset($arr_data['seller_details']) && $arr_data['seller_details']['first_name']!='' ? ucwords($arr_data['seller_details']['first_name'])." ".ucwords($arr_data['seller_details']['last_name']) : "NA";
          $seller_email        = isset($arr_data['seller_details']) && $arr_data['seller_details']['email']!='' ? ($arr_data['seller_details']['email']) : "NA";
          $type        = isset($arr_data['type']) && $arr_data['type']!='' ? ucwords($arr_data['type']) :"NA";
          $name        = isset($arr_data['title']) && $arr_data['title']!='' ? ucwords($arr_data['title']) :"NA";
          $keywords    = isset($arr_data['keywords']) && $arr_data['keywords']!='' ? ucwords($arr_data['keywords']) :"NA";
          $description = isset($arr_data['description']) && $arr_data['description']!='' ? ucwords($arr_data['description']) :"NA";
          $ratio       = isset($arr_ratio['value']) && $arr_ratio['value']!='' ? ucwords($arr_ratio['value']) :"NA";
          $duration    = isset($arr_duration['value']) && $arr_duration['value']!='' ? ucwords($arr_duration['value']) :"NA";
          $alpha_channel = isset($arr_data['alpha_channel']) && $arr_data['alpha_channel']!='' ? ucwords($arr_data['alpha_channel']) :"NA";
          $alpha_matte = isset($arr_data['alpha_matte']) && $arr_data['alpha_matte']!='' ? ucwords($arr_data['alpha_matte']) :"NA";
          $media_release = isset($arr_data['media_release']) && $arr_data['media_release']!='' ? ucwords($arr_data['media_release']) :"NA";
          $looping = isset($arr_data['looping']) && $arr_data['looping']!='' ? ucwords($arr_data['looping']) :"NA";
          $model_release = isset($arr_data['model_release']) && $arr_data['model_release']!='' ? ucwords($arr_data['model_release']) :"NA";
          $liscense_type = isset($arr_data['liscense_type']) && $arr_data['liscense_type']!='' ? ucwords($arr_data['liscense_type']) :"NA";
          $fx = isset($arr_data['fx']) && $arr_data['fx']!='' ? ucwords($arr_data['fx']) :"NA";

/*          $last_name     = isset($arr_data['value']) && $arr_data['last_name']!='' ?$arr_data['last_name']:"NA";
          $profile_image = isset($arr_data['profile_image']) && $arr_data['profile_image']!='' ?$arr_data['profile_image']:"";

          $name = $first_name.' '.$last_name;

          $contact_number= isset($arr_data['contact_number']) && $arr_data['contact_number']!='' ?$arr_data['contact_number']:"NA";
          $email         = isset($arr_data['email']) && $arr_data['email']!='' ?$arr_data['email']:"NA";

          $address       = isset($arr_data['address']) && $arr_data['address']!='' ?$arr_data['address']:"NA";
          $city          = isset($arr_data['city']) && $arr_data['city']!='' ?$arr_data['city']:"NA";
          $zipcode       = isset($arr_data['zipcode']) && $arr_data['zipcode']!='' ?$arr_data['zipcode']:"NA";
          $country       = isset($arr_data['country']) && $arr_data['country']!='' ?$arr_data['country']:"NA";*/
        
        ?>
        <div class="box">
          <div class="box-content studt-padding">
            <div class="row">
                <div class="col-md-8">
                <h3>Information</h3>
                <br>
                    <table class="table table-bordered">
                      <tbody>
                            <tr>
                              <th style="width: 30%">Seller Name
                              </th>
                              <td>
                                {{$seller_name or ''}}
                              </td>
                            </tr> 

                            <tr>
                              <th style="width: 30%">Seller Email
                              </th>
                              <td>
                                {{$seller_email or ''}}
                              </td>
                            </tr> 

                             <tr>
                              <th style="width: 30%">Type
                              </th>
                              <td>
                                {{$type or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Name
                              </th>
                              <td>
                                {{$name or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Keywords
                              </th>
                              <td>
                                {{$keywords or ''}}
                              </td>
                            </tr>                                                             

                            <tr>
                              <th style="width: 30%">Description
                              </th>
                              <td>
                                {{$description or ''}}
                              </td>
                            </tr>
                            @if($type==='Photo')
                            
                            @endif

                            @if($type==='Footage')
                            <tr>
                              <th style="width: 30%">Ratio
                              </th>
                              <td>
                                {{$ratio or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Duration
                              </th>
                              <td>
                                {{$duration or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Alpha Channel
                              </th>
                              <td>
                                {{$alpha_channel or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Alpha Matte
                              </th>
                              <td>
                                {{$alpha_matte or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Media Release
                              </th>
                              <td>
                                {{$media_release or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Looping
                              </th>
                              <td>
                                {{$looping or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Model Release
                              </th>
                              <td>
                                {{$model_release or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Liscense Type
                              </th>
                              <td>
                                {{$liscense_type or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">FX
                              </th>
                              <td>
                                {{$fx or ''}}
                              </td>
                            </tr>                                                                                                                                                                                                                             
                            @endif
                            <?php $count=1; ?>
                            @if(isset($arr_data['listing_details']) && count($arr_data['listing_details']>0))
                            @foreach($arr_data['listing_details'] as $keys=>$list)
                            <table class="table table-bordered">
                            <strong>{{$count}} : </strong>
                            <tbody>
                                  <tr>
                                    <th style="width: 30%">Price
                                    </th>
                                    <td>
                                      ${{$list['price'] or ''}}
                                    </td>
                                  </tr>                            
                                @if($type=='Footage')
                                    @if(isset($list['format_details']) && count($list['format_details']>0))
                                    <tr>
                                      <th style="width: 30%">Formats
                                      </th>
                                      <td>
                                        {{ucwords($list['format_details']['name'])}}
                                      </td>
                                    </tr>  
                                    @endif

                                    @if(isset($list['resolution_details']) && count($list['resolution_details']>0))
                                    <tr>
                                      <th style="width: 30%">Resolution
                                      </th>
                                      <td>
                                        {{$list['resolution_details']['size'] or ''}}
                                      </td>
                                    </tr>
                                    @endif
         
                                    @if(isset($list['fps_details']) && count($list['fps_details']>0))
                                    <tr>
                                      <th style="width: 30%">FPS
                                      </th>
                                      <td>
                                        {{$list['fps_details']['value'] or ''}}
                                      </td>
                                    </tr>
                                    @endif                                                                                         

                                    <tr>
                                      <th style="width: 30%">Footage
                                      </th>
                                      <td valign="middle">
                                      <video src="{{$footage_public_img_path.$list['enc_item_name']}}" controls style="max-height: 150px;max-width: 200px; vertical-align: middle;">
                                      Your browser does not support the video tag.
                                    </video>
                                    <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{$footage_public_img_path.$list['enc_item_name'] }}" title="Download" target="_blank" download><i class="fa fa-download"></i></a>
                                      </td>
                                    </tr>                              
                                    @endif

                                @if($type==='Photo')
                                    @if(isset($list['format_details']) && count($list['format_details']>0))
                                    <tr>
                                      <th style="width: 30%">Format
                                      </th>
                                      <td>
                                        {{ucwords($list['format_details']['name'])}}
                                      </td>
                                    </tr>

                                    <tr>
                                      <th style="width: 30%">Orientation
                                      </th>
                                      <td>
                                        {{ucfirst($list['orientation_details']['value'])}}
                                      </td>
                                    </tr>

                                    <tr>
                                      <th style="width: 30%">Photo
                                      </th>
                                      <td>
                                            <img src="{{$photos_public_img_path.$list['enc_item_name'] }}" style="max-height: 150px;max-width:200px">
                                            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="{{$photos_public_img_path.$list['enc_item_name'] }}" title="Download" target="_blank" download><i class="fa fa-download"></i></a>
                                      </td>
                                    </tr>                                                                  
                                    @endif
                                @endif
                            </tbody>
                        </table>
                        <?php $count++; ?>  
                            @endforeach
                            </tbody>
                            </table>
                            @endif
                </div> 
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Main Content --> 
<!-- Pop Up Note For Rejection Starts here -->
<form method="post" id="validation-form" class="form-horizontal" action="{{url('/').'/admin/photos_and_footage/disapprove' }}">

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

<script type="text/javascript">

function clear_fields(obj)
{
  $('#note').val('');
  var enc_id = $(obj).attr('data-id');
  $('#enc_id').val(enc_id);
}
</script>


  @endsection
