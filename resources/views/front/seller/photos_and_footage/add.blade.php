@extends('front.layout.master')    
@section('main_content')
   
<style type="text/css">
   #uploadForm {
   border-top:#464646 2px solid;
   background:#FAF8F8;padding:10px;
   }
   #uploadForm label {
   margin:2px; 
   font-size:1em; 
   font-weight:bold;
   }
   .demoInputBox{
   padding:0px; 
   border:#464646 1px solid; 
   border-radius:4px; 
   background-color:#FFF;
   }
   #progress-bar {
   background: url(../../images/filmunit-loader.gif) no-repeat center center;
   height:40px;
   color: #000;
       line-height: 40px;
   width:100%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;
   }
   .btnSubmit{
   background-color:#464646;
   border:0;
   padding:10px 40px;
   color:#FFF;
   border:#464646 1px solid; 
   border-radius:4px;
   }
   #progress-div {max-width: 550px; width:100%;margin: 0 auto;text-align: center;
   border:#dbb339 1px solid;
   padding: 5px 0px;
   border-radius:4px;
   text-align:center; 
   }
   .progress_bor{width: 100%;height:100%;text-align: center;position: fixed;z-index: 999999999; padding-top: 25%;background:rgba(0,0,0,0.8);}
   .loader_div{
   position:absolute;
   top:0px;
   right:0px;
   width:100%;
   height:100%;
   background-color:#666;
   background-repeat:no-repeat;
   background-position:center;
   z-index:10000000;
   opacity: 0.4;
   filter: alpha(opacity=40);
   }
   #targetLayer{
   width:100%;
   text-align:center;
   }
   .loc_add_pht {
   margin: 25px 20px 0 !important;
   }
   .photo_view    {          
   overflow:hidden;
   cursor:pointer;
   }
   .overlay {  
   background: rgba(0, 0, 0, 0.75) none repeat scroll 0 0;
   bottom: 0;
   left: 6px;
   opacity: 0;
   padding: 0;
   position: absolute;
   right: 6px;
   text-align: center;
   top: 24px;
   transition: opacity 0.25s ease 0s;
   }
   .photo_view:hover .overlay {
   opacity:0.5;
   }
   .plus {
   font-family:Helvetica;
   font-weight:900;
   color:rgba(255,255,255,.85);
   font-size:65px;
   }
   #add_loader{
   /* For IE8 and earlier */
   }
</style>

<div class="progress_bor" style="display: none">
   <div id="progress-div">
      <div id="progress-bar"></div>
   </div>
   <div id="targetLayer"></div>
</div>

    <!--Buyer Account Section Start Here-->
    <div class="clearfix"></div>
    <div class="buyer-account-bg-main min-heightsone">
        <div class="container">
            <div class="row">
                <!-- BEGIN Sidebar -->
                @include('front.layout._sidebar')
                <!-- END Sidebar -->
            <form name="uploadFootagePhotosForm" id="uploadFootagePhotosForm" enctype="multipart/form-data" method="post" action="{{$module_url_path}}/store">
            {{csrf_field()}}
                <div class="col-sm-8 col-md-8 col-lg-9">
                    <!--    Flash message Blade  -->
                    {{-- @include('front.layout._operation_status') --}}
                     <div class="alert alert-danger" id="show-span" style="display: none">
                        <span id="span-error"></span>
                        <button type="button" class="close" style="margin-top: 0px !important;padding: 0px !important;" data-dismiss="alert" aria-hidden="true">&times;</button>
                      </div>

                    <div class="light-grey-bg-block">
                        <h1 class="accou-heat"> Upload Photos & footage </h1>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                               <div class="account-text-fild">
                                    <div class="user-box1">
                                        <label class="label-l">Type</label>
                                        <div class="cont-frm arrow-down">
                                            <select class="frm-select" name="type" id='type' onchange="checkMediaType();">
                                                <option value="">Select Type</option>
                                                <option value="photo" @if(old('type')=='photo')selected @endif>Photo</option>
                                                <option value="footage" @if(old('type')=='footage')selected @endif>Footage</option>
                                            </select>
                                        </div>
                                        <div class="error" id="err_type">{{$errors->first('type')}}</div>
                                    </div>
                                </div>
                            </div>
                           
                             <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="account-text-fild">
                                    <div class="user-box1">
                                        <label class="label-l">Footage/Photo Name</label>
                                        <input class="cont-frm" type="text" placeholder="Enter Footage/Photo Name" name='title' id='title' maxlength="255" value="{{ old('title') }}" />
                                        <div class="error" id="err_title">{{$errors->first('title')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="account-text-fild">
                                    <div class="user-box1">
                                        <label class="label-l">Add Search Keywords</label>
                                        <input class="cont-frm" type="text" placeholder="Enter Search Keywords" name='keywords' id='keywords' maxlength="255" value="{{ old('keywords') }}" />
                                        <div class="error" id="err_keywords">{{$errors->first('keywords')}}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="account-text-fild">
                                    <div class="user-box1">
                                        <label class="label-l">Add Description</label>
                                          <textarea class="textarea-frm" rows="5" placeholder="Enter Description" name='description' id='description' maxlength="1000">{{ old('description') }}</textarea>
                                        <div class="error" id="err_description">{{$errors->first('description')}}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Footage options start here-->
                            <div id=footage_options>
                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Ratio</label>
                                                    <div class="cont-frm arrow-down">
                                                        <select class="frm-select" name='ratio' id='ratio'>
                                                            <option value="">Select Ratio</option>
                                                            @if(isset($arr_ratio) && count($arr_ratio)>0)
                                                            @foreach($arr_ratio as $key=>$value)
                                                                <option value="{{$value['id']}}" @if(old('ratio')==$value['id'])selected @endif>{{$value['value']}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <span class="error" id="err_ratio">{{$errors->first('ratio')}}</span>
                                                </div>  
                                            </div>
                                        </div>  

                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Duration (In Seconds)</label>
                                                    <input class="cont-frm" type="text" placeholder="Enter Duration" name='duration' id='duration' maxlength="255" value="{{ old('duration') }}" />
                                                    <span class="error" id="err_duration">{{$errors->first('duration')}}</span>
                                                </div>  
                                            </div>
                                        </div>                                   

                                   <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">Alpha Channel</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="alpha_channel" id="alpha-a-option" value="yes">
                                                        <label for="alpha-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="alpha_channel" id="alpha-b-option" value="no" >
                                                        <label for="alpha-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_alpha_channel">{{$errors->first('alpha_channel')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">Alpha Matte</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="alpha_matte" id="matte-a-option" value="yes">
                                                        <label for="matte-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="alpha_matte" id="matte-b-option" value="no" >
                                                        <label for="matte-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_alpha_matte">{{$errors->first('alpha_matte')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">Media Release</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="media_release" id="media-a-option" value="yes">
                                                        <label for="media-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="media_release" id="media-b-option" value="no" >
                                                        <label for="media-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_media_release">{{$errors->first('media_release')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">Looping</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="looping" id="looping-a-option" value="yes">
                                                        <label for="looping-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="looping" id="looping-b-option" value="no" >
                                                        <label for="looping-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_looping">{{$errors->first('looping')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">Model Release</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="model_release" id="model-a-option" value="yes">
                                                        <label for="model-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="model_release" id="model-b-option" value="no" >
                                                        <label for="model-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_model_release">{{$errors->first('model_release')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">Liscense Type</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="liscense_type" id="liscense-a-option" value="yes">
                                                        <label for="liscense-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="liscense_type" id="liscense-b-option" value="no" >
                                                        <label for="liscense-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_liscense_type">{{$errors->first('liscense_type')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="account-text-fild">
                                            <div class="account-text-fild radio-btns radio-btns-main" >
                                                <div class="user-box1">
                                                    <label class="label-l">FX</label>
                                                    <div class="radio-btn">
                                                        <input type="radio" name="fx" id="fx-a-option" value="yes">
                                                        <label for="fx-a-option">Yes</label>
                                                        <div class="check"></div>
                                                    </div>  
                                                    <div class="radio-btn">
                                                        <input type="radio" name="fx" id="fx-b-option" value="no" >
                                                        <label for="fx-b-option">No</label>
                                                        <div class="check"><div class="inside"></div></div>
                                                    </div>
                                                    <div class="error" id="err_fx">{{$errors->first('fx')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div> 
                            <div class="clr"></div>
                            <!-- Footage options ends here-->
                            <div class="cloaing-main-container">
                                <div class="add-btn-main">
                                       <button class="btn btn-success add-remove-btn buttnsclon addOption bottom-space-less" type="button"  > <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>
                                        <!--<br><br><br>-->
                                </div>

                            <!-- Cloning section start here-->


                                <div class="cloan-section add_options" id="add_options">
                                    
                                    <!-- Photos Fields starts here-->
                                    <div id="photos_clone_fields">
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Price</label>
                                                    <input class="cont-frm option_photo_price" type="text" placeholder="Enter Your Price" name='photo_price[]' id='price'/>
                                                    <span class="error " style="display: none;" id="err_price">{{$errors->first('price')}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Format</label>
                                                    <div class="cont-frm arrow-down">
                                                        <select class="frm-select option_photo_format" name='photo_format[]' id='photo_format'>
                                                            <option value="">Select format</option>
                                                            @if(isset($arr_photo_formats) && count($arr_photo_formats)>0)
                                                            @foreach($arr_photo_formats as $key=>$value)
                                                            <option value="{{$value["id"]}}">{{ucwords($value["name"])}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                            <span class="error" style="display: none;" id="err_photo_formats">{{$errors->first('photo_format')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Orientation</label>
                                                    <div class="cont-frm arrow-down">
                                                        <select class="frm-select option_orientation" name='orientation[]' id='orientation'>
                                                            <option value="">Select Orientation</option>
                                                            @if(isset($arr_orientation) && count($arr_orientation)>0)
                                                            @foreach($arr_orientation as $key=>$value)
                                                            <option value="{{$value["id"]}}">{{ucwords($value["value"])}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                            <span class="error" style="display: none;" id="err_orientation">{{$errors->first('orientation')}}</span>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>

<!--
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                           <div class="account-text-fild">
                                               <div class="user-box1">
                                                    <label class="label-l">Upload Photo</label>
                                                    <div class="profile-img">
                                                      <div class="upload-img"><img src="{{url('/')}}/images/Profile-edit-img.png" class="Profile-edit-img" alt="interviewxp"></div>
                                                         <input class="file-upload option_arr_photo validate-image" type="file" id="profile_image" name="arr_photo[]" onchange="ImageValidation(this.files)">
                                                         <div class="profile_image"><img src="{{url('/')}}/images/Profile-img.jpg" id="upload-f" class="profile" alt="interviewxp"></div>
                                                         <span class="error error" style="display: none;" id="err_arr_photo">{{$errors->first('arr_photo')}}</span>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>                                        
-->
                                       <div class="col-sm-12 col-md-12 col-lg-12">
                                           <div class="account-text-fild add-footage-upload-btn">
                                               <div class="user-box1 parent-image">
                                                    <label class="label-l">Upload Photo</label>
                                                    <div class="upload-img"><img src="{{url('/')}}/images/Profile-edit-img.png" class="Profile-edit-img" alt="interviewxp">
                                                    <input class="file-upload option_arr_photo validate-image" type="file" id="profile_image" name="arr_photo[]" onchange="ImageValidation(this.files)">
                                                    </div>
                                                    <span class="error" style="display: none;" id="err_arr_photo">{{$errors->first('arr_photo')}}</span>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="clr"></div>
                                    </div>
                                    <!-- Photos Fields ends here-->

                                    <!-- footage Fields starts here-->
                                    <div id="footage_clone_fields" style="display: none">

                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Price</label>
                                                    <input class="cont-frm option_footage_price" type="text" placeholder="Enter Your Price" name='footage_price[]' id='footage_price'/>
                                                    <span class="error " style="display: none;" id="err_price">{{$errors->first('footage_price')}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Format</label>
                                                    <div class="cont-frm arrow-down">
                                                        <select class="frm-select option_footage_format" name='footage_format[]' id='footage_format'>
                                                            <option value="">Select format</option>
                                                            @if(isset($arr_footage_formats) && count($arr_footage_formats)>0)
                                                            @foreach($arr_footage_formats as $key=>$value)
                                                            <option value="{{$value["id"]}}">{{ucwords($value["name"])}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                            <span class="error " style="display: none;" id="err_footage_format">{{$errors->first('footage_format')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">Resolution</label>
                                                    <div class="cont-frm arrow-down">
                                                        <select class="frm-select option_resolution" name='resolution[]' id='resolution'>
                                                            <option value="">Select Resolution</option>
                                                            @if(isset($arr_resolution) && count($arr_resolution)>0)
                                                            @foreach($arr_resolution as $key=>$value)
                                                            <option value="{{$value["id"]}}">{{$value["size"]}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                            <span class="error" style="display: none;" id="err_resolution">{{$errors->first('resolution')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="account-text-fild">
                                                <div class="user-box1">
                                                    <label class="label-l">FPS</label>
                                                    <div class="cont-frm arrow-down">
                                                        <select class="frm-select option_fps" name='fps[]' id='fps'>
                                                            <option value="">Select FPS</option>
                                                            @if(isset($arr_fps) && count($arr_fps)>0)
                                                            @foreach($arr_fps as $key=>$value)
                                                            <option value="{{$value["id"]}}">{{$value["value"]}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                            <span class="error " style="display: none;" id="err_fps">{{$errors->first('fps')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

<!--
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                           <div class="account-text-fild">
                                               <div class="user-box1">
                                                <label class="label-l">Upload Footage</label>
                                                    <div class="profile-img">
                                                      <div class="upload-img"><img src="{{url('/')}}/images/Profile-edit-img.png" class="Profile-edit-img" alt="interviewxp"></div>
                                                         <input class="file-upload option_arr_footage" type="file" id="profile_image" name="arr_footage[]" onchange="FootageValidation(this.files)">
                                                         <div class="profile_image"><img src="{{url('/')}}/images/Profile-img.jpg" id="upload-f" class="profile" alt="interviewxp"></div>
                                                        <span class="error" style="display: none;" id="err_arr_footage">{{$errors->first('arr_footage')}}</span>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>                                         
-->
                                       <div class="col-sm-12 col-md-12 col-lg-12">
                                           <div class="account-text-fild add-footage-upload-btn">
                                               <div class="user-box1 parent-image">
                                                    <label class="label-l">Upload Footage</label>
                                                    <div class="upload-img"><img src="{{url('/')}}/images/Profile-edit-img.png" class="Profile-edit-img" alt="interviewxp">
                                                    <input class="file-upload option_arr_footage" type="file" id="profile_image" name="arr_footage[]" onchange="FootageValidation(this.files)">
                                                    </div>
                                                    <span class="error" style="display: none;" id="err_arr_footage">{{$errors->first('arr_footage')}}</span>
                                                </div>
                                            </div>
                                        </div>   
                                    </div>                                    
                                    <!-- footage Fields ends here-->
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                           <button class="btn btn-success add-remove-btn buttnsclon removeOption" type="button" style="display: none"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                                            <!--<br><br>-->
                                    </div>
                                </div>
                                <!-- Cloning section ends here-->
                            </div>
                        </div>
                        <div class="button-section right-side tp-btns">
                        <button id="btn_upload_media" class="" type="button">Add</button>
                        <a href="{{$module_url_path}}/add" class="canl">Cancel</a></div>
                        <div class="clr"></div>
                    </div>

                </div>
            </form>    
            </div>
        </div>
    </div>
    <div class="clr"></div>
    <!--Buyer Account Section End Here-->
    
<!--    SweetAlert JS and CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/admin/sweetalert.css" />
<script type="text/javascript" src="{{url('/')}}/js/front/sweetalert.min.js"></script>
<!--    Cloning JS -->

<!--    Progress bar JS -->
<script type="text/javascript" src="{{url('/')}}/js/front/progress-bar.js" type="text/javascript"></script>

<!--    Select2 JS and CSS -->
<link href="{{url('/')}}/css/front/jquery.tagsinput.css" rel="stylesheet" />
<script src="{{url('/')}}/js/front/jquery.tagsinput.js"></script>  

<script type="text/javascript">

    /*------------------ Tags Input --------------------*/
    $('#footage_options').hide();
    $('#keywords').tagsInput({'delimiter': [',']});

    /*------------------ Clone fields --------------------*/

    $('.addOption').click(function(){
        $('.addOption').addClass( "plus-btn-move" );
        if($('.add_options').length==1)
        {
             $('.removeOption').show();
        }

        if($('.add_options').length<5)
        {
            $('.add_options').last().clone().insertAfter($('.add_options').last());
            $('.add_options').last().find('input').val('');
            $('.add_options').last().find('.error').html('');
        }
        else
        {
            swal("Maximum 5 options are allowed!");
            return false;
        }
    })

    /*------------------ Remove cloned values--------------------*/

    $(document).on('click','.removeOption',function(){
        
        if($('.add_options').length<=2)
        {
             $('.addOption').removeClass( "plus-btn-move" );
             $('.removeOption').hide();
        }

        if($('.add_options').length>1)
        {
             $(this).closest('.add_options').remove();
        }
        else
        {
            swal("Minimum 1 option is required!");
            return false;
        }
    })
   
    /*------------------ Check whether the selected type is footage / photo --------------------*/

    function checkMediaType() 
    {
       var media_type = $('#type').val();
       if(media_type=='footage')
       {
            $('.error').hide();
            $('#footage_clone_fields').show();
            $('#footage_options').show();
            $('#photos_clone_fields').hide();
            
            while($('.add_options').length>1)
            {
             $('.add_options').last('.add_options').remove();
            }
       }
       else
       {

            $('.error').hide();
            $('#footage_clone_fields').hide();
            $('#footage_options').hide();
            $('#photos_clone_fields').show();
            
            while($('.add_options').length>1)
            {
                $('.add_options').last('.add_options').remove();
            }
       }
    }

    /*------------------ Cloned Fields and general field validations--------------------*/

    $(document).on('click','#btn_upload_media',function()
    {
        if($('#type').val()=='footage')
        {
            var msg= "Footage";
        }
        else if($('#type').val()=='photo')
        {
            var msg = "Photo";
        }
        else
        {
            var msg = "Photo/Footage";
        }
        
          var type            = $('#type').val();
          var title           = $('#title').val();
          var keywords        = $('#keywords').val();
          var description     = $('#description').val();

          var filter_price    = /^\d{0,8}(\.\d{1,10})?$/;

          var flag=1;

          $('#err_type').html(''); $('#err_type').hide();
          $('#err_title').html(''); $('#err_title').hide();
          $('#err_keywords').html(''); $('#err_keywords').hide();
          $('#err_description').html(''); $('#err_description').hide();
          $('#err_ratio').html(''); $('#err_ratio').hide();
          $('#err_duration').html(''); $('#err_duration').hide();
          $('#err_alpha_channel').html(''); $('#err_alpha_channel').hide();
          $('#err_alpha_matte').html(''); $('#err_alpha_matte').hide();
          $('#err_media_release').html(''); $('#err_media_release').hide();
          $('#err_looping').html(''); $('#err_looping').hide();
          $('#err_model_release').html(''); $('#err_model_release').hide();
          $('#err_liscense_type').html(''); $('#err_liscense_type').hide();
          $('#err_fx').html(''); $('#err_fx').hide();
          $('.error').html(''); $('.error').hide();

        if(type.trim()=='')
        {
            $('#err_type').html('Please select '+msg+' type.');
            $('#err_type').show();
            flag=0;  
        }

        if(title.trim()=='')
        {
            $('#err_title').html('Please enter '+msg+' name.');
            $('#err_title').show();
            flag=0;  
        }

        else if(title.length>255)
        {
            $('#err_title').html(msg+' name must contain maximum 255 characters.');
            $('#err_title').show();
            flag=0;  
        }

        else if(title.length<3)
        {
            $('#err_title').html(msg+' name must contain minimum 3 characters.');
            $('#err_title').show();
            flag=0;  
        }

        if(keywords.trim()=='')
        {
            $('#err_keywords').html('Please enter '+msg+' keywords.');
            $('#err_keywords').show();
            flag=0;  
        }

        else if(keywords.length>255)
        {
            $('#err_keywords').html(msg+' keywords must contain maximum 255 characters.');
            $('#err_keywords').show();
            flag=0;  
        }

        else if(keywords.length<3)
        {
            $('#err_keywords').html(msg+' keywords must contain minimum 3 characters.');
            $('#err_keywords').show();
            flag=0;  
        }

        if(description.trim()=='')
        {
            $('#err_description').html('Please enter '+msg+' description.');
            $('#err_description').show();
            flag=0;  
        }

        else if(description.length>1000)
        {
            $('#err_description').html(msg+' description must contain maximum 1000 characters.');
            $('#err_description').show();
            flag=0;  
        }

        else if(description.length<10)
        {
            $('#err_description').html(msg+' description must contain minimum 10 characters.');
            $('#err_description').show();
            flag=0;  
        }
        
        /*-------------------- Clone validation for general/photo -------------------------*/

        $('.add_options').each(function()
        {
            if(type=='photo')
            {
                if($(this).find('.option_photo_price').val()=='')
                {
                    $(this).find('.option_photo_price').next('span').show();         
                    $(this).find('.option_photo_price').next('span').html('Plese enter price');         
                    flag=0;     
                }
                else if(!filter_price.test($(this).find('.option_photo_price').val()))
                {
                    $(this).find('.option_photo_price').next('span').show();         
                    $(this).find('.option_photo_price').next('span').html('Plese enter valid price');         
                    flag=0;     
                }

                if($(this).find('.option_photo_format').val()=='')
                {
                    $(this).find('.option_photo_format').next('span').show();         
                    $(this).find('.option_photo_format').next('span').html('Plese select format');         
                    flag=0;     
                }   

                if($(this).find('.option_orientation').val()=='')
                {
                    $(this).find('.option_orientation').next('span').show();         
                    $(this).find('.option_orientation').next('span').html('Plese select orientation');         
                    flag=0;     
                }

                if($(this).find('.option_arr_photo').val()=='')
                {
                    $(this).find('.option_arr_photo').closest('.parent-image').find('.error').show();         
                    $(this).find('.option_arr_photo').closest('.parent-image').find('.error').html('Please upload photo');         
                    flag=0;     
                }
            }

            else if(type=='footage')
            {
                if($(this).find('.option_footage_price').val()=='')
                {
                    $(this).find('.option_footage_price').next('span').show();         
                    $(this).find('.option_footage_price').next('span').html('Plese enter price');         
                    flag=0;     
                }
                else if(!filter_price.test($(this).find('.option_footage_price').val()))
                {
                    $(this).find('.option_footage_price').next('span').show();         
                    $(this).find('.option_footage_price').next('span').html('Plese enter valid price');         
                    flag=0;     
                }

                if($(this).find('.option_resolution').val()=='')
                {
                    $(this).find('.option_resolution').next('span').show();         
                    $(this).find('.option_resolution').next('span').html('Plese enter resolution');         
                    flag=0;     
                }

                if($(this).find('.option_footage_format').val()=='')
                {
                    $(this).find('.option_footage_format').next('span').show();         
                    $(this).find('.option_footage_format').next('span').html('Plese select format');         
                    flag=0;     
                }

                if($(this).find('.option_fps').val()=='')
                {
                    $(this).find('.option_fps').next('span').show();         
                    $(this).find('.option_fps').next('span').html('Plese select fps');         
                    flag=0;     
                }

                if($(this).find('.option_arr_footage').val()=='')
                {
                    $(this).find('.option_arr_footage').closest('.parent-image').find('.error').show();         
                    $(this).find('.option_arr_footage').closest('.parent-image').find('.error').html('Please upload footage');         
                    flag=0;     
                }
            }
            else
            {
                if($(this).find('.option_photo_price').val()=='')
                {
                    $(this).find('.option_photo_price').next('span').show();         
                    $(this).find('.option_photo_price').next('span').html('Plese enter price');         
                    flag=0;     
                }
                else if(!filter_price.test($(this).find('.option_photo_price').val()))
                {
                    $(this).find('.option_photo_price').next('span').show();         
                    $(this).find('.option_photo_price').next('span').html('Plese enter valid price');         
                    flag=0;     
                }
                
                if($(this).find('.option_photo_format').val()=='')
                {
                    $(this).find('.option_photo_format').next('span').show();         
                    $(this).find('.option_photo_format').next('span').html('Plese select format');         
                    flag=0;     
                }

                if($(this).find('.option_orientation').val()=='')
                {
                    $(this).find('.option_orientation').next('span').show();         
                    $(this).find('.option_orientation').next('span').html('Plese select orientation');         
                    flag=0;     
                }

                if($(this).find('.option_arr_photo').val()=='')
                {
                    $(this).find('.option_arr_photo').closest('.parent-image').find('.error').show();         
                    $(this).find('.option_arr_photo').closest('.parent-image').find('.error').html('Please upload photo');
                    flag=0;     
                }
            }

            /*-------------------- Check Media Duplicate name -------------------------*/
    
            $.ajax({
                type: "POST",
                url: "check_media_duplication",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'title' : title,
                    'type'  : type
                },

                dataType: "text",
                success: function(msg){
                  if(msg=='true')
                  {
                     $('#err_title').show();
                     $('#err_title').html('This Title is already exists.');
                     flag=0;
                  }
                  else
                  {
                     flag=1;
                  }
                }
                });  

        });

        /*-------------------- Validation if selected media type is footage -------------------------*/

        if(type=='footage')
        {
            var number_filter      = /^[0-9]*$/;
            var ratio              = $('#ratio').val();
            var duration           = $('#duration').val();

           if(ratio.trim()=='')
           {
                $('#err_ratio').html('Please select ratio.');
                $('#err_ratio').show();
                flag=0;  
           }

          if(duration.trim()=='')
          {
            $('#err_duration').html('Please select duration.');
            $('#err_duration').show();
            flag=0;  
          }
          else if(!number_filter.test(duration))
          {
            $('#err_duration').html('Please enter valid duration.');
            $('#err_duration').show();
            flag=0; 
          }

          if($('input[name=alpha_channel]:checked').length<=0)
          {
            $('#err_alpha_channel').html('Please select alpha channel.');
            $('#err_alpha_channel').show();
            flag=0;
          }

          if($('input[name=alpha_matte]:checked').length<=0)
          {
            $('#err_alpha_matte').html('Please select alpha matte.');
            $('#err_alpha_matte').show();
            flag=0;
          }
          
          if($('input[name=media_release]:checked').length<=0)
          {
            $('#err_media_release').html('Please select media release.');
            $('#err_media_release').show();
            flag=0;
          }        

          if($('input[name=looping]:checked').length<=0)
          {
            $('#err_looping').html('Please select looping.');
            $('#err_looping').show();
            flag=0;
          }

          if($('input[name=model_release]:checked').length<=0)
          {
            $('#err_model_release').html('Please select model release.');
            $('#err_model_release').show();
            flag=0;
          }

          if($('input[name=liscense_type]:checked').length<=0)
          {
            $('#err_liscense_type').html('Please select liscense type.');
            $('#err_liscense_type').show();
            flag=0;
          }

          if($('input[name=fx]:checked').length<=0)
          {
            $('#err_fx').html('Please select fx.');
            $('#err_fx').show();
            flag=0;
          }
        }
          if(flag==1)
          { 
              var Site_URL = '{{url('/')}}/seller/photos_and_footage';
              var formData = new FormData($(this).parents('form')[0]);

               $.ajax({
               url    : Site_URL+'/store',
               type   : "post",
               data   : formData,
               cache: false,
               contentType: false,
               processData: false,
               target: '#targetLayer', 
               beforeSend: function() 
               {
                 $('.progress_bor').show();
                 $("#progress-bar").width('100%');
               },
               success:function (resp){
                  window.scrollTo(0, 0);                                     
                  if(resp.status=="success")
                  {
                    window.location.href = Site_URL+'?status=success'; 
                  }
                  else if(resp.status=="error")
                  {
                    $('#err_title').html('');
                    $('.progress_bor').hide();
                    $('#show-span').show();
                    $('#span-error').html('Error While addding listing. Please try again later.');
                  }
                  else if(resp.status=="error1")
                  {
                    $('#err_title').html('');
                    $('.progress_bor').hide();
                    $('#show-span').show();
                    $('#span-error').html('All the fields are required.');
                  }
                  else if(resp.status=="error2")
                  {
                    $('#err_title').html('');
                    $('.progress_bor').hide();
                    $('#show-span').show();
                    $('#span-error').html('This title is already exists.'); 
                  }
                  else
                  {
                    $('#err_title').html('');
                    $('.progress_bor').hide();
                    $('#show-span').show();
                    $('#span-error').html('Error While addding listing. Please try again later.');
                  }                  
               },
               resetForm: true 
            });
          }
          else
          {
            return false;
          }
    });    

   /*-------------------- Check Media Duplicate name -------------------------*/
    
  $("#title").focusout(function () {
        var title    = $(this).val();
        var type = $('#type').val();
          $.ajax({
                type: "POST",
                url: "check_media_duplication",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'title' : title,
                    'type'  : type
                },

                dataType: "text",
                success: function(msg){
                  if(msg=='true')
                  {
                     $('#err_title').show();
                     $('#err_title').html('This Title is already exists.');
                     return false;  
                  }
                  else if(msg=='false')
                  {
                     $('#err_title').hide();
                     return true;
                  }
                }
                });
    });

    function ImageValidation (files,format) 
    {
        //alert(format);
     if (typeof files !== "undefined") 
      {
        for (var i=0, l=files.length; i<l; i++) 
        {
          var blnValid = false;
          var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
          var ext = ext.toLowerCase()
          if( ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "gif")
          {
              blnValid = true;
          }
          
          if(blnValid ==false) 
          {
              swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpeg , jpg , png, gif");
              $('.add_options').find('.option_arr_photo').val('');
              return false;
          }

            return true;
          }
      }
      else
      {
        swal("No support for the File API in this web browser" ,"error");
      } 
    }

    function FootageValidation (files,format) 
    {
        //alert(format);
     if (typeof files !== "undefined") 
      {
        for (var i=0, l=files.length; i<l; i++) 
        {
          var blnValid = false;
          var ext      = files[0]['name'].substring(files[0]['name'].lastIndexOf('.') + 1);
          var ext = ext.toLowerCase()

          if(ext == "avi" || ext == "flv" || ext == "wmv" || ext == "mov" || ext == "mp4")
          {
              blnValid = true;
          }
          
          if(blnValid ==false) 
          {
              swal("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: avi , flv , wmv , mov, mp4");
              $('.add_options').find('.option_arr_footage').val('');
              return false;
          }

            return true;
          }
      }
      else
      {
        swal("No support for the File API in this web browser" ,"error");
      } 
    }
</script>

@endsection

