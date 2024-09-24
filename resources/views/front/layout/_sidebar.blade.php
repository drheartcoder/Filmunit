<?php
$user = Sentinel::check();
/*if($user==false || $user['role']=='admin' || $user['terms']==0)
{
  $url = url('/');
  Sentinel::logout();
  die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
}*/
?>
   
    <!-- buyer Side bar -->
    @if(isset($user) && $user['role']=='buyer')
    <div class="col-sm-4 col-md-4 col-lg-3">
        <div class="left-bar-buyer">
            <div class="menu-box">
                <ul class="dashboard-menu-head">
                    <li>
                        <a href="javascript:void(0)">
                            <div class="menu-icon"><i class="fa fa-bars"></i></div>
                            <div class="menu-text">Dashboard Menu</div>
                        </a>
                    </li>
                </ul>
                <ul class="dashboard-menus">
                    <li>
                        <a href="{{url('/')}}/buyer/dashboard" class="<?php  if(Request::segment(1) == 'buyer' && Request::segment(2) == 'dashboard'){ echo 'active'; } ?>">
                            <div class="menu-icon"><i class="fa fa-tachometer"></i></div>
                            <div class="menu-text">Dashboard</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/account" class="<?php  if(Request::segment(1) == 'account'){ echo 'active'; } ?>"><img src="{{url('/')}}/images/menu-account-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-account-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">My Account</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/buyer/downloads" class="<?php  if(Request::segment(1) == 'buyer' && Request::segment(2) == 'downloads'){ echo 'active'; } ?>">
                            <div class="menu-icon"><i class="fa fa-cloud-download"></i></div>
                            <div class="menu-text">Downloads</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/my_collection" class="<?php  if(Request::segment(1) == 'my_collection'){ echo 'active'; } ?>">
                            <div class="menu-icon"><i class="fa fa-heart"></i></div>
                            <div class="menu-text">My Collection</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/buyer/finance" class="<?php  if(Request::segment(1) == 'buyer' && Request::segment(2) == 'finance'){ echo 'active'; } ?>"><img src="{{url('/')}}/images/menu-finance-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-finance-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">Finance</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/change_password" class="<?php  if(Request::segment(1) == 'change_password'){ echo 'active'; } ?>"><img src="{{url('/')}}/images/menu-change-pass-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-change-pass-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">Change Password</div>

                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/notifications" class="<?php  if(Request::segment(1) == 'notifications'){ echo 'active'; } ?>">
                            <div class="menu-icon"><i class="fa fa-bell"></i></div>
                            <div class="menu-text">Notification</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/logout"><img src="{{url('/')}}/images/menu-log-out-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-log-out-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">Logout</div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Seller Side bar -->
    @if(isset($user) && $user['role']=='seller')
    <div class="col-sm-4 col-md-4 col-lg-3">
        <div class="left-bar-buyer">
            <div class="menu-box" id="cssmenu1">
                <ul class="dashboard-menu-head">
                    <li>
                        <a href="javascript:void(0)">
                            <div class="menu-icon"><i class="fa fa-bars"></i></div>
                            <div class="menu-text">Dashboard Menu</div>
                        </a>
                    </li>
                </ul>
                <ul class="dashboard-menus">                    
                    <li>
                        <a href="{{url('/')}}/seller/dashboard" class="<?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'dashboard'){ echo 'active'; } ?>">
                            <div class="menu-icon"><i class="fa fa-tachometer"></i></div>
                            <div class="menu-text">Dashboard</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/account" class="<?php  if(Request::segment(1) == 'account'){ echo 'active'; } ?>"><img src="{{url('/')}}/images/menu-account-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-account-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">My Account</div>
                        </a>
                    </li>
                    <li class='has-sub'>
                        <a href="javascript:void(0);" class="<?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'photos_and_footage'){ echo 'active'; } ?>">
                            <div class="submenu1"><img src="{{url('/')}}/images/menu-upload-footage-1.png" class="menu-icon-img" alt="" />
                                <img src="{{url('/')}}/images/menu-upload-footage-2.png" class="menu-icon-hover" alt="" />
                                <div class="menu-text">Photos &amp; Footage <span class="plus-icon"> <i class="fa <?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'photos_and_footage'){ echo 'fa-minus'; } else {echo 'fa-plus';} ?>"></i></span></div>
                            </div>
                        </a>
                        <ul class="sub_menu submenu" style="<?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'photos_and_footage'){ echo 'display:visible'; }else{echo 'display:none';} ?>">
                            <li>
                                <a href="{{url('/')}}/seller/photos_and_footage" class="<?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'photos_and_footage' && (Request::segment(3) == '' || Request::segment(3) == 'edit')){ echo 'active'; } ?>">
                                    <div class="menu-text">My Listing</div>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/')}}/seller/photos_and_footage/admin_listing" class="<?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'photos_and_footage' && Request::segment(3) == 'admin_listing'){ echo 'active'; } ?>">
                                    <div class="menu-text">Admin Listing</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{url('/')}}/seller/finance" class="<?php  if(Request::segment(1) == 'seller' && Request::segment(2) == 'finance'){ echo 'active'; } ?>">
                            <img src="{{url('/')}}/images/menu-finance-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-finance-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">Finance</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/')}}/change_password" class="<?php  if(Request::segment(1) == 'change_password'){ echo 'active'; } ?>"><img src="{{url('/')}}/images/menu-change-pass-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-change-pass-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">Change Password</div>

                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/notifications" class="<?php  if(Request::segment(1) == 'notifications'){ echo 'active'; } ?>">
                            <div class="menu-icon"><i class="fa fa-bell"></i></div>
                            <div class="menu-text">Notification</div>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/')}}/logout"><img src="{{url('/')}}/images/menu-log-out-1.png" class="menu-icon-img" alt="" />
                            <img src="{{url('/')}}/images/menu-log-out-2.png" class="menu-icon-hover" alt="" />
                            <div class="menu-text">Logout</div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif