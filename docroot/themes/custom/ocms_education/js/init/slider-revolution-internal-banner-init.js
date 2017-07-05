jQuery(document).ready(function($) {
  var horizontalOffset = 15;
  if (drupalSettings.ocms_education.sliderRevolutionInternalBannerInit.slideshowInternalBannerBulletsPosition == "center") {
    horizontalOffset = 0;
  }
  jQuery("#slideshow-internal .slider-revolution").revolution({
    sliderType:"standard",
    sliderLayout:"auto",
    gridwidth: [1170,970,750,450],
    gridheight: drupalSettings.ocms_education.sliderRevolutionInternalBannerInit.slideshowInternalBannerInitialHeight,
    delay: drupalSettings.ocms_education.sliderRevolutionInternalBannerInit.slideshowInternalBannerEffectTime,
    disableProgressBar:'off',
    responsiveLevels:[1199,991,767,480],
    navigation: {
      onHoverStop:"off",
      arrows:{
        enable:true,
        tmp: '',
        left:{
          h_align:"left",
          v_align:"center",
          h_offset:15,
          v_offset:0
        },
        right:{
          h_align:"right",
          v_align:"center",
          h_offset:15,
          v_offset:0
        }
      },
      bullets:{
        style:"",
        enable:true,
        direction:"horizontal",
        space: 5,
        h_align: drupalSettings.ocms_education.sliderRevolutionInternalBannerInit.slideshowInternalBannerBulletsPosition,
        v_align:"top",
        h_offset: horizontalOffset,
        v_offset: 20,
        tmp:"",
      },
      touch:{
        touchenabled:"on",
        swipe_treshold:75,
        swipe_min_touches:1,
        drag_block_vertical:false,
        swipe_direction:"horizontal"
      }
    }
  });

  $('#slideshow-internal .slider-revolution').bind("revolution.slide.onloaded",function (e) {
    $(".slider-revolution-wrapper:not(.one-slide) .tparrows").fadeIn("slow");
  });

});
