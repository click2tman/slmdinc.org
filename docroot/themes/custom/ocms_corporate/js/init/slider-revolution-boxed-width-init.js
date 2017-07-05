jQuery(document).ready(function($) {

  jQuery("#slideshow-boxedwidth .slider-revolution").revolution({
    sliderType:"standard",
    sliderLayout: "auto",
    gridwidth: [1140,970,750,450],
    gridheight: drupalSettings.ocms_corporate.sliderRevolutionBoxedWidthInit.slideshowBoxedWidthInitialHeight,
    autoHeight: "on",
    delay: drupalSettings.ocms_corporate.sliderRevolutionBoxedWidthInit.slideshowBoxedWidthEffectTime,
    disableProgressBar:'off',
    responsiveLevels:[1199,991,767,480],
    navigation: {
      onHoverStop:"off",
      arrows:{
        enable:true,
        tmp: "<div class='tp-title-wrap'><span class='tp-arr-titleholder'>{{title}}</span></div>",
        left:{
          h_align:"left",
          v_align:"center",
          h_offset:0,
          v_offset:0
        },
        right:{
          h_align:"right",
          v_align:"center",
          h_offset:0,
          v_offset:0
        }
      },
      bullets:{
        style:"",
        enable:true,
        direction:"horizontal",
        space: 15,
        h_align: drupalSettings.ocms_corporate.sliderRevolutionBoxedWidthInit.slideshowBoxedWidthBulletsPosition,
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

  $(".transparent-bg").css("backgroundColor", "rgba(0,0,0," + drupalSettings.ocms_corporate.slideshowCaptionOpacity + ")");

});