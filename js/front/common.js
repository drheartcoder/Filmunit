//<!-- Min Top Menu Start Here  -->

  function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        $("body").css({
            "margin-left": "250px",
            "overflow-x": "hidden",
            "transition": "margin-left .5s",
            "position": "fixed"
        });
        $("#main").addClass("overlay");
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        $("body").css({
            "margin-left": "0px",
            "transition": "margin-left .5s",
            "position": "relative"
        });
        $("#main").removeClass("overlay");
    }
    //  Min Top Sub Menu Start Here  

    $(".min-menu > li > .drop-block").click(function () {
        if (false == $(this).next().hasClass('menu-active')) {
            $('.sub-menu > ul').removeClass('menu-active');
        }
        $(this).next().toggleClass('menu-active');
        return true;
    });

 //<!-- Min Top Menu End Here  -->  


// footer Links dropdown links start 
     var min_applicable_width = 767; 
  
  $(document).ready(function () 
  {
    applyResponsiveSlideUp($(this).width(),min_applicable_width);
    
  });
  function applyResponsiveSlideUp(current_width,min_applicable_width)
  {
    /* Set For Initial Screen */
    initResponsiveSlideUp(current_width,min_applicable_width);

    /* Listen Window Resize for further changes */
    $(window).bind('resize',function()
    {
      if($(this).width()<=min_applicable_width)
      {
        unbindResponsiveSlideup();  
        bindResponsiveSlideup();
      }
      else
      {
        unbindResponsiveSlideup();  
      }  
    });
  }

  function initResponsiveSlideUp(current_width,min_applicable_width)
  {
    if(current_width<=min_applicable_width)
    {
      unbindResponsiveSlideup();  
      bindResponsiveSlideup();
    }
    else
    {
      unbindResponsiveSlideup();  
    }
  }

  function bindResponsiveSlideup()
  {
    $(".menu_name").hide();

    $(".footer_heading").bind('click', function () 
    {
      var $ans = $(this).next(".menu_name");
      $ans.slideToggle();
      $(".menu_name").not($ans).slideUp();
      $('.menu_name').removeClass('active');
      
      $('.footer_heading').not($(this)).removeClass('active');
      $(this).toggleClass('active');
      $(this).next('.menu_name').toggleClass('active');
    });


  }

  function unbindResponsiveSlideup()
  {
    $(".footer_heading").unbind('click');
    $(".menu_name").show();
  }
    // footer Links dropdown links end
    

    // Menu sticky start
$(document).ready(function () {
        var stickyNavTop = $('.header').offset().top;

        var stickyNav = function () {
            var scrollTop = $(window).scrollTop();

            if (scrollTop > stickyNavTop) {
                $('.header').addClass('sticky');
            } else {
                $('.header').removeClass('sticky');
            }
        };

        stickyNav();

        $(window).scroll(function () {
            stickyNav();
        });
    })

//Search Box Hide Show Start Here
$(document).ready(function(){
  $("#flipdashboard").click(function(){
  $("#paneldashboard").slideToggle("slow");
  });
 
});

