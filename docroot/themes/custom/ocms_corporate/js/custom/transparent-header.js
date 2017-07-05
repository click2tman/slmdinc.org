jQuery(document).ready(function($) {
  if($("#banner #slideshow-fullscreen").length>0) {
    $("#header-container").addClass("transparent-header");
  } else {
    $("#header-container").removeClass("transparent-header");
  };
  $("#header-container.transparent-header").css("backgroundColor", "rgba(30,37,39," + drupalSettings.ocms_corporate.transparentHeader.transparentHeaderOpacity + ")");
});