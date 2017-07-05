jQuery(document).ready(function($) {

  jQuery("#slideshow-boxedwidth .slider-revolution").revolution({
    sliderType:"standard",
    sliderLayout: "auto",
    gridwidth: [1140,970,750,436],
    gridheight: drupalSettings.ocms_education.sliderRevolutionBoxedWidthInit.slideshowBoxedWidthInitialHeight,
    autoHeight: "on",
    delay: drupalSettings.ocms_education.sliderRevolutionBoxedWidthInit.slideshowBoxedWidthEffectTime,
    disableProgressBar:'off',
    responsiveLevels:[1199,991,767,466],
    navigation: {
      onHoverStop:"off",
      arrows:{
        enable:true,
        hide_onmobile:true,
        tmp: "<div class='tp-title-wrap'><span class='tp-arr-titleholder'></span></div>",
        left:{
          h_align:"right",
          v_align:"bottom",
          h_offset:40,
          v_offset:0
        },
        right:{
          h_align:"right",
          v_align:"bottom",
          h_offset:0,
          v_offset:0
        }
      },
      bullets:{
        style:"",
        enable:true,
        direction:"horizontal",
        space: 10,
        h_align: "center",
        v_align:"bottom",
        h_offset: 0,
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

  $('#slideshow-boxedwidth .slider-revolution').bind("revolution.slide.onloaded",function (e) {
    $(".slider-revolution-wrapper:not(.one-slide) .tparrows").fadeIn("slow");
  });

});
