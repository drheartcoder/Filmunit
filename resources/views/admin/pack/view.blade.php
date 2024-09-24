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
    </div>
    <!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box ">
      <div class="box-title">
        <h3>
          <i class="fa fa-eye">
          </i> {{ $page_title or '' }} 
        </h3>
        <div class="box-tool">
        </div>
      </div>
      <div class="box-content">
        
        <div class="box">
          <div class="box-content studt-padding">
            <div class="row">
                <div class="col-md-8">
                <h3>Package Information</h3>
                <br>
                    <table class="table table-bordered">
                      <tbody>
                            <?php $count=1; ?>

                            @if(isset($arr_data['package_details']) && count($arr_data['package_details']>0))
                            @foreach($arr_data['package_details'] as $keys=>$list)
                            <table class="table table-bordered">                          
                            <tbody>
                                    <span><strong>{{$count++}}.</strong></span>
                                    <tr>
                                      <th style="width: 30%">Title
                                      </th>
                                      <td>
                                      {{isset($list['listing_details']['master_details']['title'])?ucwords($list['listing_details']['master_details']['title']):'NA'}}
                                      </td>
                                    </tr>

                                    <tr>
                                      <th style="width: 30%">Thumbnail
                                      </th>
                                      <td>
                                      @if(isset($list['listing_details']['master_details']['type']) && $list['listing_details']['master_details']['type']=='photo')
                                        <img src="{{$admin_photos_public_img_path.$list['listing_details']['master_details']['admin_enc_item_name']}}" style="height: 150px;width: 150px">
                                      @else
                                      <img src="{{$admin_footage_image_public_path.$list['listing_details']['master_details']['admin_enc_footage_image']}}" style="height: 150px;width: 150px">
                                      </td>
                                      @endif
                                    </tr>                                    

                                    <tr>
                                      <th style="width: 30%">Type
                                      </th>
                                      <td>
                                      {{isset($list['listing_details']['master_details']['type'])?ucwords($list['listing_details']['master_details']['type']):'NA'}}
                                      </td>
                                    </tr>

                                    <tr>
                                      <th style="width: 30%">Keyword
                                      </th>
                                      <td>
                                      {{isset($list['listing_details']['master_details']['keywords'])?ucwords($list['listing_details']['master_details']['keywords']):'NA'}}
                                      </td>
                                    </tr>                                    

                                    <tr>
                                      <th style="width: 30%">Price
                                      </th>
                                      <td>
                                      {{isset($list['listing_details']['price'])?'$'.$list['listing_details']['price']:'NA'}}
                                      </td>
                                    </tr>
                                    
                                    <tr>
                                      <th style="width: 30%">Seller Name
                                      </th>
                                      <td>
                                      {{isset($list['listing_details']['master_details']['seller_details']['first_name'])?ucwords($list['listing_details']['master_details']['seller_details']['first_name']).' '.ucwords($list['listing_details']['master_details']['seller_details']['last_name']):'NA'}}
                                      </td>
                                    </tr>

                            </tbody>
                            </table>
                            @endforeach
                            @endif
                </div> 
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Main Content --> 
  @endsection
