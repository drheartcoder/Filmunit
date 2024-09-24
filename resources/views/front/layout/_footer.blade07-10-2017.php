<?php 
   //code added by mapweb
   //wp_footer(); 
   ?>

   <div class="clearfix"></div>    
   <div class="footer_heading footer-col-head">
    <span class="line-footer"></span> <span>Quick Link</span>
    </div>  
   <div class="footer-in menu_name points-footer footer-menu-main">
   <div class="film-unit-footr-link">
       <div class="container">
            <ul>
            @if(count($pages)>0)
            @foreach($pages as $page)

            <li> <a href="{{url('/').'/'.$page->post_name}}">{{$page->post_title}}</a> </li>
            @endforeach
            @endif
        </ul>
       </div>
   </div>
   
   </div>
   <div id="footer_id" style="background-color: #2B2B2B;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6"><div class="copy-righttext">© 2017 FilmUnit. All rights reserved.</div></div>
            <div class="col-sm-6 col-md-6 col-lg-6">
              <div class="film-social">
               <div class="unit-li"><a href="{{$arr_social_links['fb_url']}}"><i class="fa fa-facebook"></i></a></div>
                    <div class="unit-li"><a href="{{$arr_social_links['twitter_url']}}"><i class="fa fa-twitter"></i></a></div>
                    <div class="unit-li"><a href="{{$arr_social_links['instagram_url']}}"><i class="fa fa-instagram"></i></a></div>
                    <div class="unit-li"><a href="{{$arr_social_links['pinterest_url']}}"><i class="fa fa-pinterest-p"></i></a></div>
                    <div class="unit-li"><a href="{{$arr_social_links['google_plus_url']}}"><i class="fa fa-google-plus"></i></a></div>
                    </div>
                
            </div>
        </div>
    </div>
</div>
  
  <script type="text/javascript">
      $( function() {
          $( ".line-footer" ).on( "click", function() {              
              $(this).next( ".footer-in" ).slideToggle("slow");              
          });
      } );
</script> 
 
   
   <script type="text/javascript" language="javascript" src="{{url('/')}}/js/admin/bootstrap.min.js"></script>
   <script type="text/javascript" language="javascript" src="{{url('/')}}/js/admin/common.js"></script>
   <a class="cd-top hidden-xs hidden-sm" href="#0">Top</a>
   <script type="text/javascript" language="javascript" src="{{url('/')}}/js/admin/backtotop.js"></script>
   <style type="text/css">html {
    background: #eee none repeat scroll 0 0;
    padding: 0px !important;
}</style>

<?php 
  //echo file_get_contents('http://webwingdemo.com/node2/filmunit/wptheme/footerpage/');
?>
</body>
</html>