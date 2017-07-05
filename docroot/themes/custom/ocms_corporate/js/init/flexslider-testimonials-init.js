jQuery(document).ready(function($) {
  if ($(".view-testimonials-slider").length>0){
    $(window).load(function() {
      $(".view-testimonials-slider .flexslider").fadeIn("slow");
      $(".view-testimonials-slider .flexslider").flexslider({
        animation: drupalSettings.ocms_corporate.flexsliderTestimonialsInit.TestimonialsSliderEffect,
        slideshowSpeed: drupalSettings.ocms_corporate.flexsliderTestimonialsInit.TestimonialsSliderEffectTime,
        useCSS: false,
        prevText: "prev",
        nextText: "next",
        controlNav: false
      });
    });
  }
});
