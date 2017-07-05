jQuery(document).ready(function($) {
  if (($(".parallax-active #highlighted-bottom").length>0)  && !Modernizr.touch ){
    $(".parallax-active #highlighted-bottom").parallax("50%", 0.2, false);
  };
})