<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta property="og:title" content="FilmUnit" />
      <meta property="og:type" content="FilmUnit" />
      <meta property="og:url" content="http://www.webwingtechnologies.com/" />
      <meta property="og:image" content="{{url('/')}}/images/top-bg.jpg" />
      <meta property="og:description" content="FilmUnit" />
      <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
      <title>FilmUnit</title>
      <!-- ======================================================================== -->
      <link rel="icon" href="{{url('/')}}/images/favicon-16x16.png" type="image/x-icon" />
      <!-- Bootstrap Core CSS -->
      <link href="{{url('/')}}/css/admin/bootstrap.css" rel="stylesheet" type="text/css" />
      <!--font-awesome-css-start-here-->
      <link href="{{url('/')}}/css/admin/font-awesome.min.css" rel="stylesheet" type="text/css" />
      <link href="{{url('/')}}/css/admin/filmunit.css" rel="stylesheet" type="text/css" />
      <link href="{{url('/')}}/css/admin/set1.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" language="javascript" src="{{url('/')}}/js/front/jquery-1.11.3.min.js"></script>  
        
    <!--      Flexslider JS-->
      <script type="text/javascript" src="{{url('/')}}/js/front/jquery.flexisel.js"></script>
     
      <script type="text/javascript">
         $(function () {
             $("#header-home").load("home-header.html");
             $("#footer").load("footer.html");  
         });
      </script> 
      <script type="text/javascript">

        $(window).load(function() {
            $("#flexiselDemo1").flexisel();
            $("#flexiselDemo2").flexisel({
                enableResponsiveBreakpoints: true,
                responsiveBreakpoints: { 
                    portrait: { 
                        changePoint:480,
                        visibleItems: 1
                    }, 
                    landscape: { 
                        changePoint:640,
                        visibleItems: 2
                    },
                    tablet: { 
                        changePoint:768,
                        visibleItems: 3
                    }
                }
            });
        });
      </script>  
   </head>

<body>
    <div id="main"></div>
    <div id="header-home"></div>
    <div class="banner-404">
        <div class="container">
            <div class="error-block-404">
                <h1>404</h1>
                <span class="border">&nbsp;</span>
                <h4>Oops.. Page Not Found</h4>
                <div class="button-section"><a href={{url('/')}}>Go to home</a> </div>
            </div>
        </div>
    </div>

    <!--  About Us End Here-->
    <div class="clr"></div>
    <div id="footer"></div>

</body>

</html>