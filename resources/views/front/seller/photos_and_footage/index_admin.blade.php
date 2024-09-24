@extends('front.layout.master')    
@section('main_content')
    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="buyer-account-bg-main min-heightsone">
        <div class="container">
            <div class="row">
                <!-- BEGIN Sidebar -->
                @include('front.layout._sidebar')
                <!-- END Sidebar -->
                <div class="col-sm-8 col-md-8 col-lg-9">
                    <!--    Flash message Blade  -->
                    @include('front.layout._operation_status')
                    <!--    Flash message Blade  ends here-->
                    
                    <div class="light-grey-bg-block list-btn-set-block">
                        <h1 class="accou-heat"> {{ucwords($module_title)}} </h1>
                        
{{--                         <button class="btn btn-success add-remove-btn buttnsclon addOption" type="button"  > <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button> --}}

                        
                        <div class="order-list-text">  </div>
{{--                         <div class="btn btn-success add-remove-btn buttnsclon potos-footage-add-btn">
                            <a href="{{$module_url_path}}/add" title="Upload Photos/Footage">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </div> --}}
                        <div class="clearfix"></div>
                        @if(isset($arr_data['data']) && count($arr_data['data'])>0)

                        <div class="table-responsive ">
                            <table class="table border-none">
                                <tr class="head-title-bx">
                                    <th style="width: 5%; text-align: left; padding-left: 26px ! important;">Sr no</th>
                                    <th style="width: 15%;">Title</th>
                                    <th style="width: 15%;">Type</th>
                                    <th style="width: 15%;">Thumbnail</th>
                                    <th style="width: 20%;">Keywords</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                                @foreach($arr_data['data'] as $key=>$value)
                                <?php 
                                if(isset($value['is_approved']) && $value['is_approved']=='0')
                                {
                                    $status = "Sent for Approval";
                                }
                                else if(isset($value['is_approved']) && $value['is_approved']=='1')
                                {
                                    $status = "Approved";
                                }
                                else if(isset($value['is_approved']) && $value['is_approved']=='2')
                                {
                                    $status = 'Rejected';
                                }
                                 ?>
                                <tr>
                                    <td style="">{{$key+1}}</td>
                                    <td>{{isset($value['title']) & $value['title']!='' ? ucwords($value['title']):'NA'}}</td>
                                    <td>{{isset($value['type']) & $value['type']!='' ? ucwords($value['type']):'NA'}}</td>
                                    <td>
                                        @if($value['type']=='photo')
                                            @if($value['admin_enc_item_name']!='')
                                                <img style="height: 55px;width: 80px;" src="{{$admin_photos_public_img_path.$value['admin_enc_item_name']}}">
                                            @else
                                                <img style="height: 55px;width: 80px;" src="{{$admin_photos_public_img_path}}/default.png">
                                            @endif
                                        @else
                                            @if($value['admin_enc_footage_image']!='')
                                                <img style="height: 55px;width: 80px;" src="{{$admin_footage_image_public_path.$value['admin_enc_footage_image']}}">
                                            @else
                                                <img style="height: 55px;width: 80px;" src="{{$admin_footage_image_public_path}}/default.png">
                                            @endif
                                        @endif
                                    </td>                                    
                                    <td>{{isset($value['keywords']) & $value['keywords']!='' ? implode(', ', array_map('ucfirst', explode(',', $value['keywords']))):'NA'}}</td>
                                    <td>{{$status}}</td>
                                    <td style="text-align: center;">                                
                                        <a class="delet-i" href="{{$module_url_path}}/view/{{base64_encode($value['id'])}}"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="clr"></div>
                    @else
                    <h3 style="align-self: center; color:#fff"><center>No Records Found.</center></h3>
                    @endif
                    </div>
                      <!-- Paination Links -->
                     @include('front.templates.pagination_view')

                </div>
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->

<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/admin/sweetalert_msg.js"></script>

@endsection

