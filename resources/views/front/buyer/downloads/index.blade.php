@extends('front.layout.master')    
@section('main_content')

<?php
$user     = Sentinel::check();
$buyer_id = '';

if($user!=false && $user['role']!='admin' && $user['role']!='seller')
{
  $buyer_id = $user['id'];
}
?>
    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="buyer-account-bg-main min-heightsone">
        <div class="container">
            <div class="row">
                @include('front.layout._sidebar')
                <div class="col-sm-8 col-md-8 col-lg-9">
                    <div class="light-grey-bg-block cart-views padding-none">
                        <h1 class="download-head-block"> Downloads </h1>
                        <div class="clr"></div>
                        @if((isset($arr_data) && count($arr_data)>0 ) && (isset($arr_data['data']) && count($arr_data['data'])>0))
                        @foreach($arr_data['data'] as $key => $value)

                        <div class="download-text-blo marg-top">
                            <div class="position-chnage"> 
                                @if($value['type']=='photo')
                                <img src="{{$admin_photos_public_img_path.$value['listing_details']['master_details']['admin_enc_item_name']}}" alt="{{$value['listing_details']['master_details']['title']}}" /> 
                                @else
                                <img src="{{$admin_footage_image_public_path.$value['listing_details']['master_details']['admin_enc_footage_image']}}" alt="{{$value['listing_details']['master_details']['title']}}" />
                                @endif 
                            </div>
                            <div class="downlo-text-blo-1 widht200px">
                            <h1 class="downlo-txt-blo-h1"> {{ucwords($value['listing_details']['master_details']['title'])}} {{'('.ucwords($value['type']).')'}} </h1>                        
                            <h3> @if($value['type']=='photo'){{isset($arr_image_size['size'][$key])? $arr_image_size['size'][$key] : '' }} | @endif {{isset($value['listing_details']['format_details'])? strtoupper($value['listing_details']['format_details']['name']) : 'NA'}} | {{isset($arr_image_size['filesize']) ? $arr_image_size['filesize'][$key] : ''}} </h3>
                                <h3 class="white-color">{{isset($value['transaction_details']['transaction_date']) && $value['transaction_details']['transaction_date']!= null ? date('d M Y',strtotime($value['transaction_details']['transaction_date'])) : ""}}</h3>
                            </div>

                            <div class="downlo-text-blo-2">
                                <h2 class="new-css"> $ {{$value['price']}} </h2>
                            </div>
                               <div class="downlo-text-blo-2 widthchange">
                                <h3> Pending Attempts : <span id="download_attempt_{{$value['id']}}">{{$value['download_attempt']}}</span> </h3>
                            </div>
                            @if($value['download_attempt'] <= 0)
                            <div class="downlo-close-icon" onclick="swal('Your download attempts are over')"><a><i class="fa fa-cloud-download"></i></a></div>
                            @else
                            <div class="downlo-close-icon"><a onclick="download_media(this,'{{$value['id']}}')" data-attempt="{{$value['download_attempt']}}" href="{{$module_url_path.'/download/'.base64_encode($value['id'])}}" onclick="location.reload();"><i class="fa fa-cloud-download"></i></a></div>
                            @endif

                            <div class="clr"></div>
                        </div>
                        @endforeach
                         <!-- Paination Links -->
                        @include('front.templates.pagination_view')                                                
                    </div>
                    @else
                    <h3 style="align-self: center; color:#fff"><center>No Records Found.</center></h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--Buyer Account Section End Here-->

<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/admin/sweetalert_msg.js"></script>       

<script type="text/javascript">
function download_media(ref,id)
{
    var attempt = parseInt($(ref).attr('data-attempt'));
    if(attempt>0)
    {
        var newattempt = attempt - 1;
        $('#download_attempt_'+id).html(newattempt);
        $(ref).attr('data-attempt',newattempt);
    }
    else
    {
        swal('Your download attempts are over');
    }
}    
</script>

@endsection
