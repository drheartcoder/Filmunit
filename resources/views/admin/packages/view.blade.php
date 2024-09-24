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
      <i class="fa fa-angle-right">
      </i>
      <i class="fa fa-briefcase faa-vertical animated-hover">
      </i>
      
      <a href="{{ url($module_url_path) }}" class="call_loader">{{ $module_title or ''}}
      </a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa fa-eye">
      </i>
    </span> 
    <li class="active">   {{ $page_title or '' }}
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
          </i> {{ $page_title or '' }} 
        </h3>
        <div class="box-tool">
        </div>
      </div>
      <div class="box-content">
        <?php
          $first_name              = isset($arr_data['user_details']['first_name']) ?$arr_data['user_details']['first_name']:"";
          $last_name               = isset($arr_data['user_details']['last_name']) ?$arr_data['user_details']['last_name']:"";
          $photographer            = $first_name.' '.$last_name;
          $package                 = isset($arr_data['title']) ?$arr_data['title']:"";
          $category                = isset($arr_data['category_details']['title']) ?$arr_data['category_details']['title']:"";
          $subcategory             = isset($arr_data['subcategory_details']['title']) ?$arr_data['subcategory_details']['title']:"";
          $description             = isset($arr_data['description']) ?$arr_data['description']:"";
          $validity                = isset($arr_data['validity']) ?$arr_data['validity']:"";
          $unedited_photo          = isset($arr_data['unedited_photo_count']) ?$arr_data['unedited_photo_count']:"";
          $edited_photo            = isset($arr_data['edited_photo_count']) ?$arr_data['edited_photo_count']:"";
          $price                   = isset($arr_data['price']) ?$arr_data['price']:"";
          $traveling_preference    = isset($arr_data['traveling_preference']) ?$arr_data['traveling_preference']:"";
          $sr_no                   = 1;
        ?>
        
        <div class="box">
          <div class="box-content studt-padding">
            <div class="row">
                <div class="col-md-9">
                 <h3>Package Information</h3>
                <br>
                    <table class="table table-bordered">
                      <tbody>

                            <tr >
                              <th style="width: 30%;">Package
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{$package or ''}}
                              </td>
                            </tr> 

                            <tr>
                              <th style="width: 30%">Photographer 
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{$photographer or ''}}
                              </td>
                            </tr>
                            
                            <tr>
                              <th style="width: 30%">Category
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{$category or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Subcategory
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{$subcategory or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Description
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{$description or ''}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Validity
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{ $validity or '' }}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Uneditd Photos
                              </th>
                              <td style="line-height: 1.5 !important">
                                {{$unedited_photo or '-'}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Edited Photos
                              </th>
                              <td >
                                {{$edited_photo or '-'}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Price ( in {{currency_code()}} )
                              </th>
                              <td>
                                {{$price or '-'}} {{currency_symbol()}}
                              </td>
                            </tr>

                            <tr>
                              <th style="width: 30%">Travelling Preferences
                              </th>
                              <td>
                                {{$traveling_preference or '-'}}
                              </td>
                            </tr>
                    </tbody>
                  </table>  
                </div>

              @if(isset($arr_data['package_location_details']) && sizeof($arr_data['package_location_details'])> 0 )
                <div class="col-md-9">
                 <h3>Location Information</h3>
                  <br>
                    <table class="table table-bordered">
                      <tbody>
                      </tbody>
                      @foreach($arr_data['package_location_details'] as $result)
                          <tr>
                              <th style="width: 30%">{{$sr_no++}}
                              </th>
                              <td>
                                {{$result['location']}}<br>
                              </td>
                          </tr>
                      @endforeach
                      </table>
                      </div>
                @endif      

              </div>

              
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Main Content --> 
  @endsection
