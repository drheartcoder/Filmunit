<?php 
     $admin_path     = config('app.project.admin_panel_slug');
     //dump($arr_current_user_access);

?>

            <div id="sidebar" class="navbar-collapse collapse">
                <!-- BEGIN Navlist -->
                <ul class="nav nav-list">
                    
                    
                    <li class="<?php  if(Request::segment(2) == 'dashboard'){ echo 'active'; } ?>">
                        <a href="{{ url('/').'/'.$admin_path.'/dashboard'}}">
                            <i class="fa fa-dashboard faa-vertical animated-hover"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="<?php  if(Request::segment(2) == 'account_settings' || Request::segment(2) == 'social'){ echo 'active'; }?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-cogs faa-vertical animated-hover"></i>
                            <span>Account Settings</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <ul class="submenu">
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'account_settings' && Request::segment(3) == ''){ echo 'active'; } ?>"><a href="{{ url('/').'/'.$admin_path.'/account_settings' }}">Profile </a></li>
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'social' && Request::segment(3) == ''){ echo 'active'; } ?>"><a href="{{ url('/').'/'.$admin_path.'/social' }}">Social Link </a></li>

                        </ul>
                    </li>

                    <li class="<?php  if(Request::segment(2) == 'email_template'){ echo 'active'; } ?>">
                        <a href="{{ url($admin_panel_slug.'/email_template')}}" class="dropdown-toggle">
                            <i class="fa fa-envelope faa-vertical animated-hover"></i>
                            <span>Email Templates</span>
                        </a>
                    </li>

                    <li class="<?php  if(Request::segment(2) == 'notifications'){ echo 'active'; } ?>">
                        <a href="{{ url($admin_panel_slug.'/notifications')}}" class="dropdown-toggle">
                            <i class="fa fa-bell faa-vertical animated-hover"></i>
                            <span>Notifications</span>
                        </a>
                    </li>

                    <li class="<?php  if(Request::segment(2) == 'photos_and_footage'){ echo 'active'; } ?>">
                        <a href="{{ url($admin_panel_slug.'/photos_and_footage')}}" class="dropdown-toggle">
                            <i class="fa fa-image faa-vertical animated-hover"></i>
                            <span>Photos And Footage</span>
                        </a>
                    </li>

                    <li class="<?php  if(Request::segment(2) == 'packs' || Request::segment(2) == 'social'){ echo 'active'; }?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-briefcase faa-vertical animated-hover"></i>
                            <span>Packages</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <ul class="submenu">
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'packs' && Request::segment(3) == ''){ echo 'active'; } ?>"><a href="{{ url('/').'/'.$admin_path.'/packs' }}">Manage </a></li>
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'packs' && Request::segment(3) == 'create'){ echo 'active'; } ?>"><a href="{{ url('/').'/'.$admin_path.'/packs/create' }}">Create </a></li>

                        </ul>
                    </li>

                     <!--
                    <li class="<?php  if(Request::segment(2) == 'contact_enquiry'){ echo 'active'; } ?>">

                        <a href="{{ url($admin_panel_slug.'/contact_enquiry')}}" class="dropdown-toggle" >
                            <i class="fa fa-info-circle faa-vertical animated-hover"></i>
                                <span>Contact Enquiries</span>
                        </a>
                    </li>
                    
                    <li class="<?php  if(Request::segment(2) == 'static_pages'){ echo 'active'; } ?>">
                        <a href="{{ url($admin_panel_slug.'/static_pages')}}" class="dropdown-toggle">
                            <i class="fa  fa-sitemap faa-vertical animated-hover"></i>
                            <span>CMS</span>
                        </a>
                    </li>
                    -->
                    
                    <!--
                    <li class="<?php  if(Request::segment(2) == 'faq'){ echo 'active'; } ?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-question-circle faa-vertical animated-hover"></i>
                            <span>FAQ's</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>

                         <ul class="submenu">
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'faq' && Request::segment(3) == ''){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/faq')}}">Manage </a></li>
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'faq' && Request::segment(3) == 'create'){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/faq/create')}}">Create </a></li>   
                                                     
                        </ul>
                    </li>
                    

                    <li class="<?php  echo Request::segment(2) == 'news'?'active':'';?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-newspaper-o faa-vertical animated-hover"></i>
                            <span>News</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <ul class="submenu">
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'news' && Request::segment(3) == ''){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/news')}}">Manage </a></li>  
                            <li class="<?php echo Request::segment(2) == 'news' && Request::segment(3) == 'create'?'active':'';?>"><a href="{{ url('/').'/'.$admin_panel_slug.'/news/create'}}">Create</a></li>                            
                        </ul>
                    </li>

                    <li class="<?php  if(Request::segment(2) == 'newsletter'){ echo 'active'; } ?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-newspaper-o"></i>
                            <span>Newsletter</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <ul class="submenu">
                            <li  class="<?php  if(Request::segment(2) == 'newsletter' && Request::segment(3) == ''){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/newsletter')}}">Manage newsletter</a></li>
                            
                            
                            <li class="<?php  if(Request::segment(2) == 'newsletter' && Request::segment(3) == 'subscriber'){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/newsletter/subscriber')}}">Manage Subscribers</a></li>
                            

                            <li class="<?php  if(Request::segment(2) == 'newsletter' && Request::segment(3) == 'send'){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/newsletter/send')}}">Send newsletter</a></li>
                        </ul> 
                    </li>
                     -->
                    <li class="<?php  if(Request::segment(2) == 'users'){ echo 'active'; } ?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-users faa-vertical animated-hover" aria-hidden="true"></i>
                            <span>Users</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                            <ul class="submenu">
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'users' && Request::get('u') == ''){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/users')}}">Buyers </a></li>
                            <li style="display: block;" class="<?php  if(Request::segment(2) == 'sellers' && Request::get('u') == 'seller'){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/users?u=seller')}}">Sellers </a></li>
                        </ul>
                    </li>
                    <!--
                    <li class="<?php  if(Request::segment(2) == 'testimonial'){ echo 'active'; } ?>">
                      <a href="javascript:void(0)" class="dropdown-toggle">
                      <i class="fa fa-slideshare"></i>
                      <span>Testimonial</span>
                      <b class="arrow fa fa-angle-right"></b>
                      </a>
                      <ul class="submenu">
                         <li style="display: block;" class="<?php  if(Request::segment(2) == 'testimonial' && Request::segment(3) == ''){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/testimonial')}}">Manage </a></li>
                         
                         <li style="display: block;" class="<?php  if(Request::segment(2) == 'testimonial' && Request::segment(3) == 'create'){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/testimonial/create')}}">Create </a></li>
                         
                      </ul>
                    </li>
                    -->
                    
                    <li class="<?php  if(Request::segment(2) == 'booking'){ echo 'active'; } ?>">
                      <a href="javascript:void(0)" class="dropdown-toggle">
                      <i class="fa fa-dollar"></i>
                      <span>Bookings</span>
                      <b class="arrow fa fa-angle-right"></b>
                      </a>
                      <ul class="submenu">                        
                         <li style="display: block;" class="<?php  if(Request::segment(2) == 'booking' && Request::segment(3) == 'buyer'){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/booking/buyer/')}}">Buyer </a></li>

                         <li style="display: block;" class="<?php  if(Request::segment(2) == 'booking' && Request::segment(3) == 'seller'){ echo 'active'; } ?>"><a href="{{ url($admin_panel_slug.'/booking/seller/')}}">Seller </a></li>
                      </ul>
                    </li>
                    
                    <li class="<?php  if(Request::segment(2) == 'commission'){ echo 'active'; } ?>">
                        <a href="{{ url('/').'/'.$admin_path.'/commission' }}" >
                            <i class="fa fa-money faa-vertical animated-hover"></i>
                            <span>{{ str_limit('Commission (%)',18)}}</span>
                        </a>

                    </li>

                    <li class="<?php  if(Request::segment(2) == 'download'){ echo 'active'; } ?>">
                        <a href="{{ url('/').'/'.$admin_path.'/download' }}" >
                            <i class="fa fa-download faa-vertical animated-hover"></i>
                            <span>Download Attempts</span>
                        </a>

                    </li>
                    

                      <li class="<?php  if(Request::segment(2) == 'newsletter'){ echo 'active'; } ?>">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="fa fa-newspaper-o"></i>
                            <span>Newsletter</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <ul class="submenu">
                            <li  class="<?php  if(Request::segment(2) == 'newsletter' && Request::segment(2) == ''){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/newsletter')}}">Manage newsletter</a></li>
                            <li class="<?php  if(Request::segment(2) == 'newsletter' && Request::segment(2) == 'subscriber'){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/newsletter/subscriber')}}">Manage Subscribers</a></li>
                            <li class="<?php  if(Request::segment(2) == 'newsletter' && Request::segment(2) == 'send'){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/newsletter/send')}}">Send newsletter</a></li>
                        </ul> 
                    </li> 

                <!-- END Navlist -->

                <!-- BEGIN Sidebar Collapse Button -->
                <div id="sidebar-collapse" class="visible-lg">
                    <i class="fa fa-angle-double-left"></i>
                </div>
                <!-- END Sidebar Collapse Button -->
            </div>