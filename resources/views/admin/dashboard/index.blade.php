@extends('admin.layout.master')                


@section('main_content')


                <!-- BEGIN Page Title -->
                <div class="page-title">
                    <div>
                        <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
                         
                    </div>
                </div>
                <!-- END Page Title -->

                <!-- BEGIN Breadcrumb -->
                <div id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li class="active"><i class="fa fa-home"></i> Home</li>

                    </ul>
                </div>
                <!-- END Breadcrumb -->
                
                <!-- BEGIN Tiles -->
                <div class="row">
                    <div class="col-md-12">
                      @include('admin.layout._operation_status')
                        <div class="row">

            
                         @if(isset($arr_final_tile) && sizeof($arr_final_tile)>0)
                           <?php
                                    $i = 0;
                                    $total_color_shades = sizeof($arr_tile_color);
                           ?>      
                           @foreach($arr_final_tile as $key => $data)   
                              
                                 <div class="col-md-3">
                                    <a class="tile {{ $arr_tile_color[$i] }} box" href="{{ $admin_url_path.'/'.$data['module_slug'] }}">
                                        <div class="img img-center">

                                            <i class="fa fa-{{ $data['css_class'] or 'star-o' }}"></i>

                                        </div>
                                        <p class="title text-center">{{ $data['module_title'] or 'NA' }}</p>
                                    </a>
                                 </div>
                                <?php 
                                    $i++;
                                    if($i == $total_color_shades)
                                    {
                                        $i = 0;
                                    }
                                ?> 
                              
                            @endforeach
                        @endif
                                       
                        </div>
                         <div class="box box-black">
                    
                 <div class="box-title">
                                <h3><i class="fa fa-retweet"></i> Quick Stats</h3>
                                <div class="box-tool">
                                    <!-- <a data-action="collapse" href="#"><i class="fa fa-chevron-up"></i></a>
                                    <a data-action="close" href="#"><i class="fa fa-times"></i></a> -->
                                </div>
                            </div>
                            <div class="box-content">
                                <ul class="things-to-do">
                                     <li>
                                        <p>
                                            <i class="fa fa-users"></i>
                                            <span class="value">{{$buyer_count}}</span>
                                            Buyers
                                            <a class="btn btn-success" href="{{url('/')}}/admin/users">Go</a>
                                        </p>
                                    </li>

                                      <li>
                                        <p>
                                            <i class="fa fa-gift"></i>
                                            <span class="value">{{$seller_count}}</span>
                                            Sellers
                                            <a class="btn btn-success" href="{{url('/')}}/admin/users?u=seller">Go</a>
                                        </p>
                                    </li>

                                    <li>
                                        <p>
                                            <i class="fa fa-image faa-vertical animated-hover"></i>
                                            <span class="value">{{$photos_and_footage_count}}</span>
                                           Approve Photos and Footage
                                            <a class="btn btn-success" href="{{url('/')}}/admin/photos_and_footage">Go</a>
                                        </p>
                                    </li>

                                     <li>
                                        <p>
                                            <i class="fa fa-image faa-vertical animated-hover"></i>
                                            <span class="value">{{$photos_and_footage_disapprove_count}}</span>
                                           Disapprove Photos and Footage
                                            <a class="btn btn-success" href="{{url('/')}}/admin/photos_and_footage">Go</a>
                                        </p>
                                    </li>
                                              

                                     <li>
                                        <p>
                                            <i class="fa fa-briefcase faa-vertical animated-hover"></i>
                                            <span class="value">{{$packages_count}}</span>
                                            Package Count
                                            <a class="btn btn-success" href="{{url('/')}}/admin/packs">Go</a>
                                        </p>
                                    </li>

                                    <li>
                                        <p>
                                            <i class="fa fa-briefcase faa-vertical animated-hover"></i>
                                            <span class="value">{{$buyer_booking_count}}</span>
                                             Buyer Booking
                                            <a class="btn btn-success" href="{{url('/')}}/admin/booking/buyer">Go</a>
                                        </p>
                                    </li>

                                    <li>
                                        <p>
                                            <i class="fa fa-briefcase faa-vertical animated-hover"></i>
                                            <span class="value">{{$seller_booking_counts}}</span>
                                            Seller Booking
                                            <a class="btn btn-success" href="{{url('/')}}/admin/booking/seller">Go</a>
                                        </p>
                                    </li>
                                    
                                    <li>
                                        <p>
                                            <i class="fa fa-money"></i>
                                            <span class="value">$ {{$price}}</span>
                                            Current months Transactions
                                            <a class="btn btn-success" href="{{url('/')}}/admin/booking/seller">Go</a>
                                        </p>
                                    </li>
                                                                       
                                </ul>
                            </div>
                        </div>
                    </div>             
@stop                    