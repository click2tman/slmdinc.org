jQuery(document).ready(function($) {

  if ($(".transparent-header-active").length>0) {
    jQuery("#slideshow-fullscreen .slider-revolution").revolution({
      sliderType:"standard",
      sliderLayout:"fullscreen",
      gridwidth: [1170,970,750,450],
      autoHeight: "on",
      fullScreenOffsetContainer: ".toolbar-tray",
      delay: drupalSettings.ocms_corporate.sliderRevolutionFullScreenInit.slideshowFullScreenEffectTime,
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
          h_align:  drupalSettings.ocms_corporate.sliderRevolutionFullScreenInit.slideshowFullScreenBulletsPosition,
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
  } else {
    jQuery("#slideshow-fullscreen .slider-revolution").revolution({
      sliderType:"standard",
      sliderLayout:"fullscreen",
      gridwidth: [1170,970,750,450],
      autoHeight: "on",
      fullScreenOffsetContainer: "#header-container, .toolbar-tray",
      delay: drupalSettings.ocms_corporate.sliderRevolutionFullScreenInit.slideshowFullScreenEffectTime,
      disableProgressBar:'off',
      responsiveLevels:[1199,991,767,480],
      navigation: {
        onHoverStop:"off",
        arrows:{
          enable:true,
          tmp: "<div class='tp-title-wrap'><span class='tp-arr-titleholder'></span></div>",
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
          h_align:  drupalSettings.ocms_corporate.sliderRevolutionFullScreenInit.slideshowFullScreenBulletsPosition,
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
  }

  $(".transparent-bg").css("backgroundColor", "rgba(0,0,0," + drupalSettings.ocms_corporate.slideshowCaptionOpacity + ")");

});