jQuery(document).ready(function($) {
  $(window).load(function() {
    if ($("#in-page-images-slider.flexslider").length>0) {
      $("#in-page-images-slider.flexslider").fadeIn("slow");
      $("#in-page-images-slider.flexslider").flexslider({
        animation: drupalSettings.ocms_corporate.flexsliderInPageInit.inPageSliderEffect, // Select your animation type, "fade" or "slide"
        prevText: "",
        nextText: "",
        pauseOnAction: false,
        useCSS: false,
        slideshow: false,
      });
    };
  });
});
