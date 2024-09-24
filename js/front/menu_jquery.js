/*$( document ).ready(function() 
{
  $('#cssmenu ul ul li:odd').addClass('odd');
  $('#cssmenu ul ul li:even').addClass('even');
  $('#cssmenu > ul > li > a').click(function() 
  {
    $('#cssmenu li').removeClass('active');
    $(this).closest('li').addClass('active');	
    var checkElement = $(this).next();
    if((checkElement.is('ul')) && (checkElement.is(':visible'))) 
    {
      $(this).closest('li').removeClass('active');
      checkElement.slideUp('normal');
    }
    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) 
    {
      $('#cssmenu ul ul:visible').slideUp('normal');
      checkElement.slideDown('normal');
    }
    if($(this).closest('li').find('ul').children().length == 0) 
    {
      return true;
    } else {
      return false;	
    }		
  });
});*/


$( document ).ready(function() 
{
  $("#cssmenu1").find("li.has-sub a").click(function(event)
  {
      var ref = $(this).parent("li.has-sub");
      event.stopPropagation();

      var siblings = $(ref).siblings('li.has-sub').not($(ref));
      $(siblings).find('ul').eq(0).slideUp();
      $(siblings).find('a span.plus-icon').eq(0).find('i.fa').removeClass('fa-minus').addClass('fa-plus');
     

      var li = $(ref).find("ul").eq(0);
      $(li).slideToggle();
      
      if($(ref).find('a span.plus-icon').eq(0).find('i.fa').hasClass('fa-plus'))
      {
        $(ref).find('a span.plus-icon').eq(0).find('i.fa').removeClass('fa-plus').addClass('fa-minus');
      }
      else
      {
        $(ref).find('a span.plus-icon').eq(0).find('i.fa').removeClass('fa-minus').addClass('fa-plus');
      }

  });
});